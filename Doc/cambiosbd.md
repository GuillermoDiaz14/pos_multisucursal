# Cambios a la Base de Datos

Bitácora de cambios DDL pendientes de aplicar en producción.
Cada bloque está numerado y se debe ejecutar **en orden**.
Después de aplicar todos, ejecutar la sección final de **verificación**.

> Pensado para escalar a **cientos de miles de productos** y millones de filas en stock.
> Todos los índices están diseñados para los patrones de consulta reales del POS
> (búsqueda por código, listado por sucursal, venta y reporte de stock bajo).

---

## Convenciones

- Motor: **InnoDB** (transacciones, row-locking para concurrencia de venta).
- Charset: `utf8mb4` / `utf8mb4_general_ci` (consistente con tablas existentes).
- Toda nueva columna FK lleva su `INDEX` correspondiente.
- `id_variante` es **NULL** en tablas de movimiento cuando el producto no tiene variantes (retrocompatible).

---

## 1. Variantes de Producto (tallas, colores, corridas)

### 1.1 Marcar productos con variantes

```sql
ALTER TABLE tbl_producto
  ADD COLUMN tiene_variantes TINYINT(1) NOT NULL DEFAULT 0 AFTER talla,
  ADD INDEX idx_producto_tiene_variantes (tiene_variantes);
```

> El índice acelera el filtrado en venta/listados cuando se necesite saber rápido si un producto requiere selección de talla.

### 1.2 Catálogo de variantes

```sql
CREATE TABLE tbl_producto_variante (
  id_variante   INT NOT NULL AUTO_INCREMENT,
  id_producto   INT NOT NULL,
  talla         VARCHAR(20) NOT NULL,
  orden         SMALLINT NOT NULL DEFAULT 0,
  activo        TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id_variante),
  UNIQUE KEY uk_producto_talla (id_producto, talla),
  KEY idx_variante_producto (id_producto, activo),
  CONSTRAINT fk_variante_producto
    FOREIGN KEY (id_producto) REFERENCES tbl_producto (id_producto)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

- `UNIQUE(id_producto, talla)` evita duplicar la misma talla del mismo producto.
- `orden` permite mostrar tallas en orden lógico (S, M, L o 22, 23, 24…) sin depender de `ORDER BY talla`.
- `activo` permite desactivar una talla sin borrarla (mantiene historial de ventas).

### 1.2.1 Precios por variante (opcional, override del producto padre)

```sql
ALTER TABLE tbl_producto_variante
  ADD COLUMN precio_compra DECIMAL(10,2) NULL AFTER talla,
  ADD COLUMN precio_venta  DECIMAL(10,2) NULL AFTER precio_compra;
```

- `NULL` significa "usar el del producto padre" (`tbl_producto.precio_compra/precio_venta`).
- Cuando hay valor, **prevalece** sobre el del padre tanto en venta como en compra.
- No requiere índice: siempre se accede vía el `id_variante` (PK).

### 1.3 Stock por variante y sucursal

```sql
CREATE TABLE tbl_stock_variante (
  id_stock_variante INT NOT NULL AUTO_INCREMENT,
  id_variante       INT NOT NULL,
  id_sucursal       INT NOT NULL,
  stock             INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id_stock_variante),
  UNIQUE KEY uk_variante_sucursal (id_variante, id_sucursal),
  KEY idx_stock_sucursal (id_sucursal, stock),
  CONSTRAINT fk_stockvar_variante
    FOREIGN KEY (id_variante) REFERENCES tbl_producto_variante (id_variante)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_stockvar_sucursal
    FOREIGN KEY (id_sucursal) REFERENCES tbl_sucursal (id_sucursal)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

- `UNIQUE(id_variante, id_sucursal)` es la clave de lookup natural y previene duplicados (espejo de `tbl_producto_stock`).
- `idx_stock_sucursal (id_sucursal, stock)` acelera reportes de **stock bajo por sucursal** sin escanear toda la tabla.
- Concurrencia: en venta se debe usar `UPDATE … WHERE id_variante=? AND id_sucursal=? AND stock>=?` y validar `affected_rows`, igual que el flujo actual.

### 1.4 Vincular movimientos al variante

`id_variante` es **NULL** cuando el producto no tiene variantes.
Cuando `tbl_producto.tiene_variantes=1`, la lógica de negocio (no DB) obliga a que sea NOT NULL en la inserción.

```sql
ALTER TABLE tbl_detalle_venta
  ADD COLUMN id_variante INT NULL AFTER id_producto,
  ADD INDEX idx_detventa_variante (id_variante),
  ADD CONSTRAINT fk_detventa_variante
    FOREIGN KEY (id_variante) REFERENCES tbl_producto_variante (id_variante)
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE tbl_detalle_compra
  ADD COLUMN id_variante INT NULL AFTER id_producto,
  ADD INDEX idx_detcompra_variante (id_variante),
  ADD CONSTRAINT fk_detcompra_variante
    FOREIGN KEY (id_variante) REFERENCES tbl_producto_variante (id_variante)
    ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE tbl_detalle_traslado
  ADD COLUMN id_variante INT NULL AFTER id_producto,
  ADD INDEX idx_dettraslado_variante (id_variante),
  ADD CONSTRAINT fk_dettraslado_variante
    FOREIGN KEY (id_variante) REFERENCES tbl_producto_variante (id_variante)
    ON DELETE RESTRICT ON UPDATE CASCADE;
```

> `ON DELETE RESTRICT` en movimientos: jamás borrar una variante que ya tiene ventas/compras. El soft-delete vía `activo=0` cubre el caso de “discontinuar talla”.

---

## 2. Índices de performance generales (recomendados a esta escala)

Aplicar **una vez** revisado que no existan ya (ver verificación al final).

> Auditado contra el esquema actual: `tbl_producto` ya tiene `idx_codigo`, `idx_categoria`, `idx_nombre` y FULLTEXT.
> `tbl_producto_stock` ya tiene `unique_producto_sucursal`, `idx_sucursal`, `idx_stock_sucursal_producto`, `idx_stock_sucursal_activo`.
> `tbl_venta` ya tiene `idx_venta_sucursal_tipo` y `idx_venta_sucursal_apartado`.
> Los índices que faltan son los siguientes:

```sql
-- Reportes históricos por fecha (no cubiertos por idx_venta_sucursal_tipo)
ALTER TABLE tbl_venta
  ADD INDEX idx_venta_sucursal_fecha (id_sucursal, fecha_venta);

ALTER TABLE tbl_compra
  ADD INDEX idx_compra_sucursal_fecha (id_sucursal, fecha_compra);

-- Joins detalle->cabecera y detalle->producto (faltantes en compra/traslado)
ALTER TABLE tbl_detalle_venta
  ADD INDEX idx_detventa_producto (id_producto);

ALTER TABLE tbl_detalle_compra
  ADD INDEX idx_detcompra_compra (id_compra),
  ADD INDEX idx_detcompra_producto (id_producto);

ALTER TABLE tbl_detalle_traslado
  ADD INDEX idx_dettraslado_traslado (id_traslado),
  ADD INDEX idx_dettraslado_producto (id_producto);
```

---

## 3. Verificación post-deploy

```sql
-- 1. Estructura
SHOW CREATE TABLE tbl_producto_variante\G
SHOW CREATE TABLE tbl_stock_variante\G

-- 2. Columnas nuevas
SHOW COLUMNS FROM tbl_producto LIKE 'tiene_variantes';
SHOW COLUMNS FROM tbl_detalle_venta LIKE 'id_variante';
SHOW COLUMNS FROM tbl_detalle_compra LIKE 'id_variante';
SHOW COLUMNS FROM tbl_detalle_traslado LIKE 'id_variante';

-- 3. Índices clave
SHOW INDEX FROM tbl_producto_variante;
SHOW INDEX FROM tbl_stock_variante;

-- 4. Integridad: no debe haber detalle de venta con id_variante apuntando a variante inexistente
SELECT COUNT(*) AS huerfanos
FROM tbl_detalle_venta dv
LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
WHERE dv.id_variante IS NOT NULL AND pv.id_variante IS NULL;
-- Debe devolver 0.
```

---

## 4. Rollback (solo si algo falla durante el deploy)

```sql
ALTER TABLE tbl_detalle_traslado DROP FOREIGN KEY fk_dettraslado_variante, DROP INDEX idx_dettraslado_variante, DROP COLUMN id_variante;
ALTER TABLE tbl_detalle_compra   DROP FOREIGN KEY fk_detcompra_variante,   DROP INDEX idx_detcompra_variante,   DROP COLUMN id_variante;
ALTER TABLE tbl_detalle_venta    DROP FOREIGN KEY fk_detventa_variante,    DROP INDEX idx_detventa_variante,    DROP COLUMN id_variante;
DROP TABLE IF EXISTS tbl_stock_variante;
DROP TABLE IF EXISTS tbl_producto_variante;
ALTER TABLE tbl_producto DROP INDEX idx_producto_tiene_variantes, DROP COLUMN tiene_variantes;
```

---

## 5. Fase 4 — Compras y Traslados con variantes

> **No requiere DDL nuevo.** Las columnas `id_variante` en `tbl_detalle_compra` y `tbl_detalle_traslado` ya quedaron creadas en la sección 1.4 durante Fase 1. Esta sección solo documenta los índices opcionales que pueden ayudar cuando el volumen de operaciones crezca, y las verificaciones recomendadas post-deploy.

### 5.1 Índices opcionales (aplicar solo si se observan queries lentas)

```sql
-- Acelera reportes de "compras por proveedor + variante" y consultas inversas
-- desde una variante hacia sus movimientos de compra.
-- Solo crear si tbl_detalle_compra supera ~500k filas o se detectan slow queries.
CREATE INDEX idx_detcompra_compra_variante
    ON tbl_detalle_compra (id_compra, id_variante);

-- Mismo razonamiento para traslados.
CREATE INDEX idx_dettraslado_traslado_variante
    ON tbl_detalle_traslado (id_traslado, id_variante);

-- Búsqueda por código en módulos de traslado/compra/etiquetas (escaneo con scanner).
-- Si tbl_producto.codigo ya tiene UNIQUE/INDEX no se requiere; crear sólo si EXPLAIN
-- muestra full table scan al buscar por código exacto.
-- CREATE UNIQUE INDEX idx_producto_codigo ON tbl_producto (codigo);
```

### 5.2 Verificación post-deploy (Fase 4)

```sql
-- 1. Detalles de compra apuntando a variantes que ya no existen
SELECT COUNT(*) AS huerfanos_compra
FROM tbl_detalle_compra dc
LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dc.id_variante
WHERE dc.id_variante IS NOT NULL AND pv.id_variante IS NULL;
-- Debe devolver 0.

-- 2. Detalles de traslado apuntando a variantes inexistentes
SELECT COUNT(*) AS huerfanos_traslado
FROM tbl_detalle_traslado dt
LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dt.id_variante
WHERE dt.id_variante IS NOT NULL AND pv.id_variante IS NULL;
-- Debe devolver 0.

-- 3. Productos con tiene_variantes=1 que tienen detalles de compra/traslado sin id_variante
--    (rompería la integridad lógica: si tiene variantes, todo movimiento debe especificarla).
SELECT 'compra' AS tipo, dc.id_detalle_compra
FROM tbl_detalle_compra dc
INNER JOIN tbl_producto p ON p.id_producto = dc.id_producto
WHERE p.tiene_variantes = 1 AND dc.id_variante IS NULL
UNION ALL
SELECT 'traslado' AS tipo, dt.id_detalle_traslado
FROM tbl_detalle_traslado dt
INNER JOIN tbl_producto p ON p.id_producto = dt.id_producto
WHERE p.tiene_variantes = 1 AND dt.id_variante IS NULL;
-- Debe devolver 0 filas. Si retorna alguna, son movimientos hechos antes
-- de activar tiene_variantes en ese producto y deben revisarse manualmente.
```

### 5.3 Notas de implementación (referencia)

- Todas las operaciones de compra/traslado se ejecutan dentro de una **transacción**: si una falla, ninguna escritura queda en BD.
- El descuento de stock en traslados usa `UPDATE … WHERE stock >= ?` con verificación de `affected_rows`, evitando la condición de carrera del patrón "SELECT + UPDATE".
- Los detalles se insertan con `insert_batch` (una sola query por compra/traslado) para soportar carritos grandes sin penalizar el tiempo de respuesta.
- El payload del front es JSON limpio (`payload` en compras, `productos` ya era JSON en traslados); ambos backends mantienen compatibilidad con el formato legado por si quedó algún consumidor.

---

## 6. Rollback de los índices opcionales de Fase 4

```sql
DROP INDEX idx_detcompra_compra_variante ON tbl_detalle_compra;
DROP INDEX idx_dettraslado_traslado_variante ON tbl_detalle_traslado;
```

> El rollback completo de variantes (Fase 1) sigue en la sección 4.

---

## 7. Fase 5 — Reportes con desglose por variante

### 7.1 Cambios DDL

**Ninguno.** La fase 5 solo modifica modelo + vistas (PHP). Las columnas `id_variante` y la tabla `tbl_stock_variante` ya existen desde fase 1.

### 7.2 Índices opcionales para escala (recomendados al pasar de ~50k movimientos)

```sql
-- Acelera los rankings de "productos más vendidos" cuando hay millones de detalles.
-- Cubre el patrón: filtrar por venta (vía join) + agrupar por id_producto/id_variante.
ALTER TABLE tbl_detalle_venta
  ADD INDEX idx_detventa_producto_variante (id_producto, id_variante);

-- Stock bajo por sucursal: ya existe idx_stock_sucursal (id_sucursal, stock) en
-- tbl_stock_variante (Fase 1). No requiere índice adicional.
```

### 7.3 Verificaciones post-deploy

```sql
-- Coherencia: un producto con variantes NO debe tener filas en tbl_producto_stock,
-- el stock real vive en tbl_stock_variante. Si existen, el reporte stock_actual
-- las ignorará automáticamente (filtro tiene_variantes=0), pero conviene limpiarlas.
SELECT ps.id_producto, p.nombre_producto, ps.id_sucursal, ps.stock
FROM tbl_producto_stock ps
INNER JOIN tbl_producto p ON p.id_producto = ps.id_producto
WHERE p.tiene_variantes = 1
  AND ps.stock <> 0;
-- Si retorna filas: stock huérfano (no se mostrará en reportes). Migrar a tbl_stock_variante
-- o auditar manualmente.

-- Tallas activas sin fila en stock por sucursal:
SELECT pv.id_variante, p.nombre_producto, pv.talla, s.id_sucursal, s.nombre_sucursal
FROM tbl_producto_variante pv
INNER JOIN tbl_producto p ON p.id_producto = pv.id_producto
CROSS JOIN tbl_sucursal s
LEFT JOIN tbl_stock_variante sv
  ON sv.id_variante = pv.id_variante AND sv.id_sucursal = s.id_sucursal
WHERE pv.activo = 1 AND sv.id_stock_variante IS NULL;
-- Estas tallas aparecerán en stock_bajo con stock=0 (COALESCE). Es esperado.
```

### 7.4 Notas de implementación (referencia)

- `getStockActualResumen` y `getStockBajoResumen` ahora retornan filas con campos extra: `id_variante`, `talla`, `tiene_variantes`. Productos sin variantes traen `id_variante=NULL`, `talla=NULL`.
- El conteo de `totales['productos']` agrupa por `id_producto` único (no por talla) para no inflar el KPI.
- `getProductosMasVendidosResumen` acepta un nuevo parámetro `$desglosarPorTalla` (default `false`). Cuando es `true`, agrega `variantes[]` a cada fila del ranking que tenga `tiene_variantes=1`. El ranking principal sigue siendo consolidado por producto (sin duplicar el top por cada talla).
- Esto evita el patrón `WITH ROLLUP` (poco portable) y requiere solo dos queries: una para el top, otra para el desglose limitado a los IDs del top.
- El desglose por talla **incluye** filas con `id_variante=NULL` agrupándolas como "Sin talla" (caso legacy: ventas hechas antes de activar variantes en el producto). Esto garantiza que `SUM(desglose.unidades) == total.unidades` de la fila padre; de lo contrario, el reporte mostraría un agujero.

---

## 8. Rollback de los índices opcionales de Fase 5

```sql
DROP INDEX idx_detventa_producto_variante ON tbl_detalle_venta;
```

> El rollback completo de variantes (Fase 1) sigue en la sección 4.

---

## 9. Fase 6 — QA completo y consistencia de costos por variante

**Sin DDL**. Solo correcciones en `application/models/Reporte_model.php` para usar el costo de la variante cuando aplique.

### Bug encontrado y corregido

Los reportes de **utilidad/ganancia** usaban `p.precio_compra` (costo del producto) para calcular utilidad de ventas con variantes. Cuando una talla tiene `precio_compra` distinto al del producto principal (ej. talla 22 con costo $15 vs producto con costo $20), el reporte calculaba utilidad incorrecta.

**Fix aplicado en 6 lugares** (todos los queries de utilidad/ganancias):

```sql
-- ANTES
SELECT ... SUM(dv.cantidad * p.precio_compra) ...
FROM tbl_detalle_venta dv
INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto

-- DESPUÉS
SELECT ... SUM(dv.cantidad * COALESCE(pv.precio_compra, p.precio_compra)) ...
FROM tbl_detalle_venta dv
INNER JOIN tbl_producto p ON p.id_producto = dv.id_producto
LEFT JOIN tbl_producto_variante pv ON pv.id_variante = dv.id_variante
```

Funciones afectadas:
- `get_detalles_ventas_sumatorias`
- `get_detalles_ganancias_sumatorias_entre_dos_fechas`
- `get_detalles_ganancias_sumatorias_entre_dos_fechas_Count`
- `getVentasPorVendedorResumen` (subquery de utilidad)
- `getFlujoTotalResumen` (subquery utilidad estimada)
- `getUtilidadEstimadaResumen`

### Verificaciones de integridad ejecutadas (todas OK tras limpiar 1 huérfano)

```sql
-- 1) Producto con tiene_variantes=1 sin variantes activas
SELECT COUNT(*) FROM tbl_producto p
WHERE p.tiene_variantes=1
  AND NOT EXISTS (SELECT 1 FROM tbl_producto_variante v WHERE v.id_producto=p.id_producto AND v.activo=1);

-- 2) Producto con tiene_variantes=0 con variantes activas
SELECT COUNT(*) FROM tbl_producto p
INNER JOIN tbl_producto_variante v ON v.id_producto=p.id_producto AND v.activo=1
WHERE p.tiene_variantes=0;

-- 3) Stock simple en producto con variantes (huérfano)
SELECT COUNT(*) FROM tbl_producto_stock ps
INNER JOIN tbl_producto p ON p.id_producto=ps.id_producto
WHERE p.tiene_variantes=1;

-- 4) Stock de variante huérfano (variante eliminada o inactiva)
SELECT COUNT(*) FROM tbl_stock_variante sv
LEFT JOIN tbl_producto_variante v ON v.id_variante=sv.id_variante
WHERE v.id_variante IS NULL OR v.activo=0;

-- 5) Venta con id_variante apuntando a producto sin variantes
SELECT COUNT(*) FROM tbl_detalle_venta dv
INNER JOIN tbl_producto p ON p.id_producto=dv.id_producto
WHERE dv.id_variante IS NOT NULL AND p.tiene_variantes=0;

-- 6) Venta legacy sin talla en producto con variantes
SELECT COUNT(*) FROM tbl_detalle_venta dv
INNER JOIN tbl_producto p ON p.id_producto=dv.id_producto
WHERE dv.id_variante IS NULL AND p.tiene_variantes=1;
```

Si la consulta (3) regresa filas con `stock=0`, se pueden limpiar con seguridad (no afectan el reporte UNION pero confunden el conteo de productos):

```sql
DELETE ps FROM tbl_producto_stock ps
INNER JOIN tbl_producto p ON p.id_producto=ps.id_producto
WHERE p.tiene_variantes=1 AND ps.stock=0;
```

---

## Historial de cambios

| Fecha | Sección | Descripción |
|-------|---------|-------------|
| 2026-05-17 | 1, 2 | Diseño inicial: variantes de producto (tallas) + índices de performance para escala 100k+ productos |
| 2026-05-18 | 5 | Fase 4: compras y traslados con variantes (sin DDL nuevo, índices opcionales y verificaciones) |
| 2026-05-18 | 7, 8 | Fase 5: reportes con desglose por variante (stock actual/bajo vía UNION ALL, más vendidos con desglose opcional por talla; sin DDL, índice opcional `idx_detventa_producto_variante`) |
| 2026-05-18 | 9 | Fase 6: QA completo. Fix en 6 reportes de utilidad para usar `COALESCE(pv.precio_compra, p.precio_compra)` cuando la venta tiene variante; verificación de integridad ejecutada (sin inconsistencias); 1 stock huérfano limpiado |
| 2026-05-18 | 10 | Movimientos de inventario: nuevo reporte (controller + model + view) que unifica ventas, compras y traslados con saldo corriente por producto/variante. Sin DDL. Índice opcional `idx_detventa_producto`, `idx_detcompra_producto`, `idx_dettraslado_producto` ya existentes son suficientes. |
