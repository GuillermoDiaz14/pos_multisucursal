# Módulo: Ventas (Carrito)

> Punto de venta (POS). Gestiona ventas de contado, crédito, apartados y la integración con la caja activa.

- **Controlador:** `application/controllers/Carrito.php`
- **Modelo:** `application/models/Carrito_model.php`
- **Vistas:** `application/views/carrito/`
- **Módulo de permisos:** `Ventas` (permisos finos: `editar`, `eliminar`)

---

## 1. Pre-requisitos operativos

1. **Sesión activa** con `id_sucursal`.
2. **Caja abierta** para la sucursal y el usuario actual (`Caja_model::getCajaAbiertaPorSucursal`).
3. Permiso `Ventas.total_access = 1` en la matriz.

Si no hay caja abierta, la vista POS redirige al formulario de apertura del módulo Caja.

---

## 2. Tipos de venta

| Tipo | `tipo_pago` | `tipo_venta` | Comportamiento |
|------|-------------|--------------|----------------|
| Contado | `contado` | `normal` | `saldo = 0`, registra `monto_recibido` y `cambio` |
| Crédito | `crédito` | `normal` | `saldo > 0`, abonos posteriores en `tbl_cuota` |
| Apartado | `contado` o `crédito` | `apartado` | `estado_apartado ∈ {en_proceso, entregado, cancelado}`, `anticipo` inicial |

---

## 3. Flujo de venta de contado

```
1. Usuario abre POS (Carrito::carrito)
2. Busca producto (Carrito::buscarPOS — AJAX)
3. Agrega al carrito (frontend acumula)
4. Selecciona cliente, método de pago, monto recibido
5. Confirma venta
   ├─ INSERT tbl_venta (tipo_pago=contado, saldo=0)
   ├─ INSERT tbl_detalle_venta (por cada item)
   ├─ UPDATE tbl_producto_stock o tbl_stock_variante (decrementar)
   └─ UPDATE tbl_caja.saldo += total
6. Genera ticket (TCPDF / jsPDF)
```

Todo el bloque de inserción + actualización de stock debe ejecutarse en **transacción** (`trans_begin/commit/rollback`).

---

## 4. Flujo de venta a crédito

```
1-4. Igual a contado, pero cliente debe existir y tener doc_identidad.
5. Confirma venta
   ├─ INSERT tbl_venta (tipo_pago=crédito, saldo=total)
   ├─ INSERT tbl_detalle_venta
   ├─ UPDATE stock
   └─ NO afecta tbl_caja.saldo (no entró efectivo todavía)
6. Abonos posteriores
   ├─ INSERT tbl_cuota (id_venta, cuota, fecha_pago)
   ├─ UPDATE tbl_venta SET saldo = saldo - cuota
   └─ UPDATE tbl_caja.saldo += cuota (el abono sí entra a caja del día del abono)
```

---

## 5. Flujo de apartado

```
1. Cliente reserva productos con anticipo
   ├─ INSERT tbl_venta (tipo_venta='apartado', estado_apartado='en_proceso', anticipo=X)
   ├─ Stock reservado: depende de la implementación
   │    Opción A: descontar al apartar
   │    Opción B: descontar al entregar
   └─ UPDATE tbl_caja.saldo += anticipo
2. Abonos parciales → tbl_cuota
3. Entrega final
   └─ UPDATE tbl_venta SET estado_apartado = 'entregado'
4. Cancelación
   ├─ UPDATE tbl_venta SET estado_apartado = 'cancelado'
   └─ Reversa de stock si fue descontado, política de retención de anticipo según negocio
```

> Verificar en el código actual qué opción (A o B) se aplica para el stock — esto impacta directamente la disponibilidad reportada en POS.

---

## 6. Búsqueda de productos en POS

`Carrito_model::buscar_productos_pos($id_sucursal, $termino, $limit = 20)`:

- LIKE sobre `nombre_producto` y `codigo`.
- Combina stock de productos simples (`tbl_producto_stock`) y suma de stock de variantes (`tbl_stock_variante`).
- Filtra `stock > 0`.

`Carrito_model::get_variantes_para_venta($id_producto, $id_sucursal)`:

- Retorna variantes activas con stock > 0 para selección en el POS.

---

## 7. Eliminación de ventas

`Carrito::eliminar_venta($id_venta)`:

1. Requiere `hasVentaPermission('eliminar') === true`.
2. En transacción:
   - DELETE de `tbl_detalle_venta`.
   - **Reversa de stock** (devolver al inventario lo vendido).
   - **Reversa de caja** si era contado del día actual.
   - DELETE de `tbl_venta`.

> Idealmente, **no eliminar** ventas: aplicar borrado lógico o anulación con motivo, para mantener trazabilidad fiscal.

---

## 8. Métodos clave

### Controller

| Método | Función |
|--------|---------|
| `carrito()` | Carga vista POS si hay caja abierta |
| `buscarPOS()` | AJAX: búsqueda de productos con stock |
| `variantes_pos()` | AJAX: variantes de un producto |
| `eliminar_venta($id)` | Elimina venta (con permiso) |
| `ventas_lista()` | Listado/historial de ventas |

### Model

| Método | Función |
|--------|---------|
| `addNewVenta($info)` | INSERT cabecera |
| `addNewDetalleVenta($items)` | INSERT detalle (batch) |
| `buscar_productos_pos(...)` | Búsqueda con stock combinado |
| `get_variantes_para_venta(...)` | Variantes con stock |
| `eliminar_detalles($id_venta)` | DELETE detalle |
| `eliminar_venta($id_venta)` | DELETE cabecera |
| `hayCajasAbiertas($id_sucursal, $id_user)` | Validación pre-venta |
| `get_saldo_cajaabierta(...)` | Saldo actual de la caja |

---

## 9. Consideraciones técnicas

### 9.1 Concurrencia

Dos usuarios pueden intentar vender el último producto en stock simultáneamente. Para evitarlo:

- Bloqueo a nivel transacción con `SELECT ... FOR UPDATE` sobre `tbl_producto_stock` antes del `UPDATE`.
- Validar stock **dentro** de la transacción, no antes.

```php
$this->db->trans_begin();
$stock = $this->db->query(
    "SELECT stock FROM tbl_producto_stock
     WHERE id_producto = ? AND id_sucursal = ? FOR UPDATE",
    [$id_producto, $id_sucursal]
)->row()->stock;

if ($stock < $cantidad) {
    $this->db->trans_rollback();
    return ['ok' => false, 'msg' => 'Stock insuficiente'];
}
// UPDATE y resto del flujo
```

### 9.2 Consistencia de totales

`base_imponible + impuesto - descuento = total`. Calcular siempre en servidor; no confiar en el total enviado desde frontend.

### 9.3 Impuestos

Tasa por sucursal en `tbl_sucursal.impuesto`. Aplicar como porcentaje sobre la base.

### 9.4 Tickets y PDF

- **Tickets POS**: ZPL para Zebra o vista HTML imprimible.
- **PDF de venta**: TCPDF (servidor) o jsPDF (cliente).
- Incluir: folio, fecha, cliente, ítems, totales, sucursal, vendedor.

---

## 10. Errores comunes y prevención

| Error | Causa | Prevención |
|-------|-------|------------|
| Stock negativo | No validar dentro de transacción | `SELECT ... FOR UPDATE` + validación |
| Venta duplicada por doble click | Sin idempotencia frontend | Deshabilitar botón al primer click, token único en form |
| Saldo de caja desfasado | Excepción en medio del flujo sin rollback | Envolver toda la operación en transacción |
| Total ≠ suma de detalles | Cálculo solo en frontend | Recalcular en servidor antes de INSERT |
| Crédito sin cliente identificado | Validación faltante | Bloquear submit si `tipo_pago=crédito` y `id_cliente=null` |

---

## 11. Referencias

- [Arquitectura](../arquitectura.md)
- [Modelo de datos — tbl_venta](../modelo_datos.md#tbl_venta)
- [Módulo Caja](caja.md)
- [Módulo Productos](productos.md)
