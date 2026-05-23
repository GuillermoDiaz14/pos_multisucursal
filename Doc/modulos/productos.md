# Módulo: Productos

> Catálogo de productos con soporte para variantes (talla/color), stock por sucursal, validación y generación automática de códigos EAN-13, importación masiva e impresión de etiquetas.

- **Controlador:** `application/controllers/Producto.php`
- **Modelo:** `application/models/Producto_model.php`
- **Vistas:** `application/views/producto/`
- **Módulo de permisos:** `Productos` (permisos finos: `ver_precio_compra`, `gestionar`)

---

## 1. Tipos de producto

### 1.1 Producto simple

- Una sola SKU.
- Stock en `tbl_producto_stock` por sucursal.
- Campo `tiene_variantes = 0`, `talla = 'NA'`.

### 1.2 Producto con variantes

- Múltiples SKUs por atributo (típicamente talla).
- Cada variante vive en `tbl_producto_variante` (id_variante, talla, precios, orden, activo).
- Stock por variante y sucursal en `tbl_stock_variante`.
- Campo `tiene_variantes = 1` en `tbl_producto`.

> **Reglas excluyentes:** un producto con `tiene_variantes = 1` **no** tiene stock en `tbl_producto_stock`. El POS, traslados y reportes deben elegir la fuente correcta según esta bandera.

---

## 2. Códigos EAN-13

### 2.1 Formato

- 13 dígitos numéricos: `^\d{13}$`.
- Último dígito = **dígito verificador** (checksum).

### 2.2 Cálculo del dígito verificador

```
suma = Σ (digito_i * peso_i)   donde peso = [1,3,1,3,...] (12 dígitos)
verificador = (10 − (suma % 10)) % 10
```

### 2.3 Métodos del modelo

| Método | Función |
|--------|---------|
| `validar_formato_ean13($codigo)` | Regex 13 dígitos |
| `validar_checksum_ean13($codigo)` | Valida último dígito |
| `validar_ean13_duplicado($ean, $id_actual)` | Busca duplicados (permite edición del propio) |
| `generar_ean13_automatico()` | Genera aleatorio en rango interno |
| `generar_ean13_automatico_unico($reservados)` | Evita lista de códigos en uso |

### 2.4 Generación automática

- Rango interno: `5000000000001` – `5999999999999` (prefijo `5` reservado para uso interno por convención del proyecto).
- Hasta 100 intentos si hay colisión.
- Calcula y agrega dígito verificador correcto.

---

## 3. Alta de producto

### 3.1 Flujo

```
1. GET /producto/add[/<codigo_prefill>]
2. POST /producto/addNewProducto
   ├─ Validar nombre, categoría, precios
   ├─ Si código no provisto → generar EAN-13
   ├─ Si código provisto → validar formato + checksum + duplicado
   ├─ Si tiene_variantes:
   │   ├─ Validar al menos 1 variante con talla única
   │   └─ INSERT tbl_producto_variante por cada fila
   └─ INSERT tbl_producto + tbl_producto_stock inicial por sucursal
```

### 3.2 Permisos

- `Productos.total_access = 1` para listar y editar.
- `Productos.ver_precio_compra = 1` para ver/editar precio de compra.
- `Productos.gestionar = 1` para alta/baja/cambios sensibles.

---

## 4. Importación CSV

Soporte para alta masiva con SheetJS (frontend) + backend de inserción:

- Plantilla típica: `nombre, categoria, precio_compra, precio_venta, codigo, stock_inicial`.
- Validar EAN-13 por fila o generarlo si está vacío.
- Reportar filas con error sin abortar el lote (o abortar todo en transacción, según política).

> Verificar el método concreto en `Producto.php` (sección de importación) para ajustar la documentación cuando se requiera detalle exacto.

---

## 5. Etiquetas y códigos de barras

Generación de etiquetas para impresión Zebra o impresoras genéricas:

- Librerías:
  - `picqer/php-barcode-generator` — backend, imagen PNG.
  - `zendframework/zend-barcode` — alternativa adicional.
  - ZPL nativo para impresoras Zebra (controlador `Zebra`).
- Vista típica: grilla imprimible con `codigo` + `nombre_producto` + `precio_venta`.

Ver:

- `application/controllers/Zebra.php`
- `application/config/zebra_printers.php`
- [Doc/manual_impresoras_zebra.md](../manual_impresoras_zebra.md)
- [ZEBRA_SETUP.md](../../ZEBRA_SETUP.md)

---

## 6. Stock por sucursal

### 6.1 Consulta

```sql
-- Producto simple
SELECT stock FROM tbl_producto_stock
WHERE id_producto = ? AND id_sucursal = ?;

-- Producto con variantes (total)
SELECT SUM(stock) FROM tbl_stock_variante sv
JOIN tbl_producto_variante pv ON pv.id_variante = sv.id_variante
WHERE pv.id_producto = ? AND sv.id_sucursal = ? AND pv.activo = 1;
```

### 6.2 Modificación

**Nunca** modificar stock vía `UPDATE` directo en controlador. Siempre vía métodos del modelo:

- `Producto_model::obtener_stock_sucursal($id_producto, $id_sucursal)`
- `Trasladar_model::incrementar_stock_producto` / `decrementar_stock_producto`
- `Trasladar_model::incrementar_stock_variante` / `decrementar_stock_variante`

### 6.3 Inicialización en sucursales nuevas

Al crear una sucursal, todos los productos existentes deben obtener una fila en `tbl_producto_stock` con `stock = 0`. Implementar como hook al alta de sucursal o validar lazy: si no existe la fila al consultarla, asumir stock 0 (y crear al primer incremento).

---

## 7. Métodos clave

### Controller

| Método | Función |
|--------|---------|
| `producto_lista()` | Catálogo con búsqueda y stock por sucursal |
| `add($codigo_prefill)` | Formulario nuevo |
| `addNewProducto()` | Alta de producto + variantes |
| `editOldProducto()` | Edición |
| `etiquetas*` | Generación de etiquetas |

### Model

| Método | Función |
|--------|---------|
| `buscar_por_ean13($ean)` | Lookup por código |
| `buscar_por_id($id)` | Lookup por ID |
| `buscar_por_nombre($nombre)` | LIKE limit 20 |
| `validar_formato_ean13`, `validar_checksum_ean13`, `validar_ean13_duplicado` | Validaciones |
| `generar_ean13_automatico`, `generar_ean13_automatico_unico` | Generadores |
| `actualizar_precio_compra($id, $precio)` | Update parcial |
| `obtener_stock_sucursal($id, $id_sucursal)` | Stock actual |

---

## 8. Errores comunes

| Error | Causa | Solución |
|-------|-------|----------|
| EAN-13 con checksum inválido | Capturado manualmente | Validar siempre con `validar_checksum_ean13` |
| Producto con variantes pero `tiene_variantes = 0` | Edición inconsistente | Sincronizar la bandera al guardar variantes |
| Stock desfasado entre `tbl_producto_stock` y `tbl_stock_variante` | Conversión simple ↔ variante sin migración | Al cambiar el tipo, migrar/cerrar stock anterior explícitamente |
| Imágenes huérfanas en `uploads/` | DELETE de producto sin borrar archivo | Borrar archivo al borrar producto, o limpiar con tarea programada |
| Importación CSV con duplicados | Sin validación previa | Acumular `reservados` antes de generar EAN automáticos |

---

## 9. Referencias

- [Modelo de datos — tbl_producto](../modelo_datos.md#tbl_producto)
- [Módulo Ventas — búsqueda en POS](ventas.md#6-búsqueda-de-productos-en-pos)
- [Módulo Traslados — stock](traslados.md)
