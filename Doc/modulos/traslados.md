# Módulo: Traslados de inventario

> Mueve stock entre sucursales de forma atómica. No existe estado intermedio: el traslado se materializa al confirmarse.

- **Controladores:**
  - `application/controllers/Trasladar.php` — origen, generación
  - `application/controllers/Transferencia_inventario.php` — utilitario de transferencia masiva / verificación
- **Modelo principal:** `application/models/Trasladar_model.php`
- **Vistas:** `application/views/traslado/`
- **Módulo de permisos:** `Traslados`

---

## 1. Modelo conceptual

```
            ┌────────────────────┐
            │  Sucursal ORIGEN   │
            │  id_sucursal_descuento
            └─────────┬──────────┘
                      │  decrementar stock
                      ▼
            ┌────────────────────┐
            │  tbl_traslado      │  cabecera + tbl_detalle_traslado
            └─────────┬──────────┘
                      │  incrementar stock
                      ▼
            ┌────────────────────┐
            │  Sucursal DESTINO  │
            │  id_sucursal_aumento
            └────────────────────┘
```

Todo el flujo (cabecera + detalle + ajuste de stock de ambas sucursales) se ejecuta en **una sola transacción**.

---

## 2. Flujo `Trasladar::addNewTrasladar()`

```
1. Validaciones de input
   ├─ destino != origen
   ├─ productos[] no vacío
   ├─ cantidades > 0
   └─ permiso Traslados.total_access

2. trans_begin()

3. INSERT tbl_traslado
   ├─ fecha_actual = NOW()
   ├─ comentario
   ├─ id_usuario = sesión
   ├─ id_sucursal_descuento = sucursal sesión (origen)
   └─ id_sucursal_aumento = destino seleccionado

4. Para cada ítem:
   a. Si tiene variante:
      ├─ variante_pertenece_producto(id_variante, id_producto)
      ├─ decrementar_stock_variante(id_variante, origen, cantidad)
      ├─ Verificar resultado: si stock insuficiente → rollback
      └─ incrementar_stock_variante(id_variante, destino, cantidad)
   b. Si NO tiene variante:
      ├─ decrementar_stock_producto(id_producto, origen, cantidad)
      ├─ Verificar resultado
      └─ incrementar_stock_producto(id_producto, destino, cantidad)
   c. Acumular detalle

5. INSERT batch tbl_detalle_traslado

6. trans_commit() (si todo ok) o trans_rollback()
```

### 2.1 Validación de stock insuficiente

Los métodos `decrementar_stock_*` aplican `UPDATE ... SET stock = stock - ?` y deben verificar que el resultado no haya quedado negativo. Si quedó negativo, lanzar rollback.

Patrón recomendado:

```php
$this->db->query(
    "UPDATE tbl_producto_stock
     SET stock = stock - ?
     WHERE id_producto = ? AND id_sucursal = ? AND stock >= ?",
    [$cantidad, $id_producto, $id_sucursal, $cantidad]
);
if ($this->db->affected_rows() === 0) {
    return false;  // dispara rollback
}
```

---

## 3. Búsqueda de productos transferibles

`Trasladar_model::buscar_productos_traslado($id_sucursal, $texto, $limit)`:

- Devuelve productos del catálogo con stock combinado > 0 en la sucursal **origen**.
- Stock combinado = `tbl_producto_stock.stock` (simples) o `SUM(tbl_stock_variante.stock)` (con variantes).
- `HAVING stock > 0`.

`Trasladar_model::get_variantes_por_sucursal($id_sucursal)`:

- Mapa `id_producto → [variantes con stock]` para enriquecer la vista.

---

## 4. Recepción / vista de traslados

El controlador `Transferencia_inventario` ofrece utilitarios complementarios:

- `ejecutar()` — invoca `Transferencia_inventario_model::transferir_inventario_completo` (transferencia masiva por nombre de sucursal).
- `verificar_inventario($nombre_sucursal)` — diagnóstico pre/post transferencia.

> **Nota:** no existe un flujo de "recepción confirmada" — los traslados son inmediatos. Si el negocio requiere un estado intermedio (enviado / recibido / observado), debe extenderse el modelo agregando una columna `estado` y, opcionalmente, un movimiento de stock diferido.

---

## 5. Métodos clave del modelo

| Método | Función |
|--------|---------|
| `buscar_productos_traslado($id_sucursal, $texto, $limit)` | Listado con stock |
| `decrementar_stock_producto($id_producto, $id_sucursal, $cantidad)` | Stock simple |
| `incrementar_stock_producto($id_producto, $id_sucursal, $cantidad)` | Stock simple |
| `decrementar_stock_variante($id_variante, $id_sucursal, $cantidad)` | Stock variante |
| `incrementar_stock_variante($id_variante, $id_sucursal, $cantidad)` | Stock variante |
| `variante_pertenece_producto($id_variante, $id_producto)` | Validación de coherencia |
| `get_variantes_por_sucursal($id_sucursal)` | Mapa de variantes |

---

## 6. Tablas afectadas

| Tabla | Operación |
|-------|-----------|
| `tbl_traslado` | INSERT cabecera |
| `tbl_detalle_traslado` | INSERT detalle batch |
| `tbl_producto_stock` | UPDATE (origen decrementa, destino incrementa) |
| `tbl_stock_variante` | UPDATE para variantes |

---

## 7. Reportes

- `traslados_enviados` — traslados generados desde la sucursal activa.
- `traslados_recibidos` — traslados con destino la sucursal activa.

Para administradores con `scope = 'todas'`, ambos reportes ofrecen selección de sucursal.

---

## 8. Errores comunes y mitigación

| Error | Causa | Mitigación |
|-------|-------|------------|
| Stock destino sin row | `tbl_producto_stock` no tiene fila para `(producto, destino)` | El incrementar debe hacer `INSERT ... ON DUPLICATE KEY UPDATE` o crear fila si no existe |
| Stock origen queda negativo | Decremento sin guard `WHERE stock >= cantidad` | Aplicar el guard y rollback si `affected_rows = 0` |
| Doble envío por clic múltiple | Sin idempotencia | Token de formulario único + deshabilitar botón |
| Pérdida de detalle si commit parcial | Falta de transacción | Toda la operación en `trans_begin/commit` |
| Variante de otro producto | Frontend manipula IDs | `variante_pertenece_producto()` antes de mover stock |

---

## 9. Consideraciones futuras

- **Estados de traslado** (`enviado`, `en_tránsito`, `recibido`, `observado`) — requeriría columna `estado` y desacople en el movimiento de stock (descontar al enviar, incrementar al recibir).
- **Documento físico** del traslado (guía/remisión) en PDF.
- **Recepción con observaciones**: si llegan menos unidades que las enviadas, registrar diferencia.

---

## 10. Referencias

- [Modelo de datos — tbl_traslado](../modelo_datos.md#tbl_traslado)
- [Arquitectura — Transacciones](../arquitectura.md#6-transacciones-e-integridad)
- [Módulo Productos](productos.md)
