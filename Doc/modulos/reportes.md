# Módulo: Reportes

> Sistema de reportes operativos y administrativos. Configuración declarativa en `application/config/reports.php` y control granular de acceso vía la matriz de permisos.

- **Controladores:**
  - `application/controllers/Reporte.php` — operativos por sucursal
  - `application/controllers/Reporte_administrador.php` — consolidados multi-sucursal
- **Modelos:** `Reporte_model`, `Reporte_administrador_model`
- **Configuración:** `application/config/reports.php`
- **Módulo de permisos:** `Reportes` (`scope`, `reports[]`)

---

## 1. Modelo de acceso

La matriz de permisos para `Reportes` tiene una estructura especial:

```json
{
  "module": "Reportes",
  "total_access": 1,
  "scope": "sucursal",
  "reports": {
    "ventas_diarias": 1,
    "ventas_periodo": 0,
    "ventas_mensuales": 1,
    ...
  }
}
```

- **`total_access`** — habilita el módulo en general.
- **`scope`**:
  - `sucursal` — solo ve datos de la sucursal de sesión.
  - `todas` — puede ejecutar reportes para cualquier sucursal o consolidados (requiere selector).
- **`reports`** — habilitación granular por clave de reporte.

### 1.1 Validación en runtime

`BaseController`:

```php
$this->hasReportAccess('ventas_diarias');       // bool
$this->canAccessAllBranchesReports();           // bool (scope=='todas')
$this->getAccessibleReports();                  // array de reportes permitidos con URLs
```

---

## 2. Configuración declarativa — `reports.php`

Cada reporte se declara como:

```php
array(
    'key'         => 'ventas_diarias',
    'title'       => 'Ventas diarias',
    'category'    => 'Ventas',
    'description' => 'Resumen diario de ventas',
    'icon'        => 'fa-line-chart',
    'single_url'  => 'reporte/reporte_venta_diario',
    'multi_url'   => 'reporte/seleccionar_sucursal/ventas_diarias',
)
```

- `single_url` — destino para usuarios con scope `sucursal`.
- `multi_url` — destino para usuarios con scope `todas` (típicamente lleva a un selector de sucursal previo).

### 2.1 Categorías

Los reportes se agrupan por `category`:

- **Ventas**: diarias, período, mensuales, productos más vendidos, utilidad estimada, por vendedor.
- **Compras**: período, mensuales, por proveedor.
- **Caja / Flujo**: caja operativa, flujo total, historial de cajas.
- **Inventario**: stock actual, stock bajo, movimientos, traslados enviados, traslados recibidos.

---

## 3. Centro de reportes

`Reporte::index()` arma la vista del centro de reportes:

1. Llama a `getAccessibleReports()` en BaseController.
2. Filtra por `total_access`, `reports[key]` y construye la URL apropiada (`single_url` o `multi_url`).
3. Agrupa por `category`.
4. Renderiza tarjetas con `icon`, `title`, `description`.

---

## 4. Selector de sucursal

Para usuarios con `scope = 'todas'`:

```
GET /reporte/seleccionar_sucursal/<reportKey>
   → Vista con dropdown de sucursales activas
   → POST → /reporte/<accion>?id_sucursal=<id>
```

Esto permite que un administrador genere el mismo reporte para distintas sucursales sin cambiar de sesión.

---

## 5. Reportes principales

### 5.1 Ventas

| Reporte | Función modelo |
|---------|----------------|
| `ventas_diarias` | `Reporte_model::reporte_venta_diario($id_sucursal, $fecha)` |
| `ventas_periodo` | `Reporte_model::reporte_venta_entre_dos_fechas($ini, $fin, $id_sucursal)` |
| `ventas_mensuales` | Agregación GROUP BY YEAR(fecha), MONTH(fecha) |
| `productos_mas_vendidos` | GROUP BY producto, SUM(cantidad), ORDER BY total DESC |
| `utilidad_estimada` | `total_cantidad * (precio_venta - precio_compra)` |
| `ventas_por_vendedor` | GROUP BY id_usuario |

### 5.2 Compras

Análogos a ventas, sobre `tbl_compra` / `tbl_detalle_compra`.

### 5.3 Caja / Flujo

| Reporte | Detalle |
|---------|---------|
| `caja_operativa` | Flujo de efectivo por sucursal/período: apertura, ventas, abonos, ingresos, gastos, cierre |
| `flujo_total` | Resumen ejecutivo: total entradas vs salidas |
| `historial_cajas` | Listado de cajas cerradas con totales y operadores |

### 5.4 Inventario

| Reporte | Origen |
|---------|--------|
| `stock_actual` | `tbl_producto_stock` + `tbl_stock_variante` por sucursal |
| `stock_bajo` | Productos con stock < umbral configurable |
| `movimientos_inventario` | UNION ALL ventas/compras/traslados (ver §6) |
| `traslados_enviados` | `tbl_traslado` WHERE `id_sucursal_descuento = ?` |
| `traslados_recibidos` | `tbl_traslado` WHERE `id_sucursal_aumento = ?` |

---

## 6. Movimientos de inventario (sin tabla física)

`Movimiento_inventario_model::getMovimientos($id_sucursal, $filtros)` reconstruye la bitácora con `UNION ALL`:

```sql
-- Salidas por venta
SELECT 'venta' AS tipo, v.fecha_venta AS fecha,
       dv.id_producto, dv.cantidad * -1 AS cantidad, v.id_venta AS referencia
FROM tbl_detalle_venta dv
JOIN tbl_venta v ON v.id_venta = dv.id_venta
WHERE v.id_sucursal = ?

UNION ALL

-- Entradas por compra
SELECT 'compra', c.fecha_compra,
       dc.id_producto, dc.cantidad, c.id_compra
FROM tbl_detalle_compra dc
JOIN tbl_compra c ON c.id_compra = dc.id_compra
WHERE c.id_sucursal = ?

UNION ALL

-- Traslados (salida en origen, entrada en destino)
SELECT 'traslado_salida', t.fecha_actual,
       dt.id_producto, dt.cantidad * -1, t.id_traslado
FROM tbl_detalle_traslado dt
JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
WHERE t.id_sucursal_descuento = ?

UNION ALL

SELECT 'traslado_entrada', t.fecha_actual,
       dt.id_producto, dt.cantidad, t.id_traslado
FROM tbl_detalle_traslado dt
JOIN tbl_traslado t ON t.id_traslado = dt.id_traslado
WHERE t.id_sucursal_aumento = ?

ORDER BY fecha DESC;
```

> Si se introduce un nuevo origen de movimiento (mermas, ajustes, devoluciones), **agregarlo al UNION**.

---

## 7. Reportes administrativos consolidados

`Reporte_administrador_model`:

| Método | Función |
|--------|---------|
| `get_sumatoriaPorDia($id_sucursal)` | GROUP BY DATE(fecha_venta): base, impuesto, descuento, total |
| `get_detalles_ventas_sumatorias($id_sucursal)` | GROUP BY producto: cantidad, precio_compra_total, precio_venta_total, ganancias |
| `get_detalles_ganancias_sumatorias_entre_dos_fechas($ini, $fin, $id_sucursal)` | Igual filtrado por rango |
| `reporte_venta_entre_dos_fechas($ini, $fin, $id_sucursal)` | Detalle de ventas en período |

Cuando se invoca sin filtro de sucursal (o con `id_sucursal = null`), agrega multi-sucursal.

---

## 8. Exportación

Los reportes admiten exportación a:

- **PDF** — TCPDF en servidor o jsPDF en cliente.
- **CSV / Excel** — SheetJS en cliente.

Patrón recomendado:

- Generar datos en servidor (un único query).
- Renderizar tabla HTML.
- Botones cliente para PDF/Excel sin re-pedir datos.

---

## 9. Performance

Para reportes con alto volumen:

- Filtrar **siempre** por rango de fecha acotado.
- Agregar índices a:
  - `tbl_venta (id_sucursal, fecha_venta)`
  - `tbl_compra (id_sucursal, fecha_compra)`
  - `tbl_traslado (id_sucursal_descuento, fecha_actual)` y `(id_sucursal_aumento, fecha_actual)`
- Considerar **vistas materializadas** o tablas agregadas (`tbl_venta_diaria_agg`) para dashboards con millones de filas.
- Evitar `SELECT *`; pedir solo las columnas necesarias.

---

## 10. Errores comunes

| Error | Causa | Solución |
|-------|-------|----------|
| Reporte vacío para admin | `scope` no es `todas` o sucursal incorrecta | Verificar `accessInfo` del rol |
| Totales no cuadran con caja | Reporte incluye ventas anuladas | Filtrar por `isDeleted = 0` o estado |
| Movimientos faltantes | Nueva fuente no agregada al UNION | Extender `getMovimientos` |
| Performance lento | Sin índice en `fecha + id_sucursal` | Crear índice compuesto |
| Reporte no aparece en menú | `key` no declarada o sin permiso | Verificar `reports.php` y matriz |

---

## 11. Cómo agregar un reporte nuevo

1. **Declarar** en `application/config/reports.php`:
   ```php
   array(
       'key' => 'mi_reporte',
       'title' => 'Mi reporte',
       'category' => 'Ventas',
       'description' => '...',
       'icon' => 'fa-chart-bar',
       'single_url' => 'reporte/mi_reporte',
       'multi_url'  => 'reporte/seleccionar_sucursal/mi_reporte',
   )
   ```
2. **Implementar** `Reporte::mi_reporte()`.
3. **Modelo**: agregar método de consulta en `Reporte_model`.
4. **Vista**: `application/views/reporte/mi_reporte.php`.
5. **Permisos**: agregar `mi_reporte` al JSON de roles que deben verlo (`tbl_access_matrix.access`).

---

## 12. Referencias

- [Arquitectura](../arquitectura.md)
- [Modelo de datos](../modelo_datos.md)
- [Seguridad — Matriz de permisos](../seguridad.md#2-autorización)
