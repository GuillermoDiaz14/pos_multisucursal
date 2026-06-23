-- =====================================================================
-- Migración de sincronización: PROD → DEV (alinear estructura)
-- Fecha: 2026-06-08
-- Base: miohzxqg_pos_m (producción)
--
-- CONTEXTO:
--   Producción ya tiene las tablas nuevas (color, genero, temporada,
--   subcategoria, stock_minimo_subcategoria) y las columnas nuevas de
--   tbl_producto. Sin embargo hay diferencias menores con desarrollo
--   que conviene alinear antes de subir el código del módulo de
--   reportes nuevos.
--
-- IMPORTANTE: Ejecutar UNA SOLA VEZ. Hacer backup antes.
-- =====================================================================

-- USE miohzxqg_pos_m; -- descomentar si se ejecuta sin seleccionar BD

-- ---------------------------------------------------------------------
-- 1. tbl_color: eliminar columna `genero` (no se usa; género vive en
--    tbl_genero como catálogo independiente).
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_color` DROP COLUMN `genero`;

-- ---------------------------------------------------------------------
-- 2. tbl_categoria: corregir DEFAULT incorrecto (11 → 1)
-- ---------------------------------------------------------------------
--ALTER TABLE `tbl_categoria`
--  MODIFY `id_sucursal` int(11) NOT NULL DEFAULT 1;

-- ---------------------------------------------------------------------
-- 3. tbl_genero: agregar "Niña" si no existe (en dev se agregó después)
-- ---------------------------------------------------------------------
INSERT INTO `tbl_genero` (`nombre_genero`, `descripcion`, `activa`)
VALUES ('Niña', 'Productos orientados a niñas', 1)
ON DUPLICATE KEY UPDATE descripcion=VALUES(descripcion), activa=VALUES(activa);

-- ---------------------------------------------------------------------
-- 4. tbl_producto: agregar índices simples que faltan en prod.
--    CRÍTICO para rendimiento del módulo de productos y reportes.
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_producto`
  ADD INDEX `idx_codigo` (`codigo`),
  ADD INDEX `idx_categoria` (`categoria`),
  ADD INDEX `idx_nombre` (`nombre_producto`(50));

-- ---------------------------------------------------------------------
-- 5. tbl_producto_stock: agregar índice simple `idx_sucursal` que falta.
-- ---------------------------------------------------------------------
ALTER TABLE `tbl_producto_stock`
  ADD INDEX `idx_sucursal` (`id_sucursal`);

-- ---------------------------------------------------------------------
-- 6. Índices compuestos extra que prod ya tiene (NO los dropeamos,
--    son benignos y pueden ayudar a otras consultas). Solo documentar:
--      - tbl_detalle_compra.idx_detcompra_compra_variante
--      - tbl_detalle_traslado.idx_dettraslado_traslado_variante
--      - tbl_detalle_venta.idx_detventa_producto_variante
--    Si se quiere mantener simetría con desarrollo, dropearlos:
--
-- ALTER TABLE tbl_detalle_compra   DROP INDEX idx_detcompra_compra_variante;
-- ALTER TABLE tbl_detalle_traslado DROP INDEX idx_dettraslado_traslado_variante;
-- ALTER TABLE tbl_detalle_venta    DROP INDEX idx_detventa_producto_variante;

-- =====================================================================
-- VALIDACIÓN POST-MIGRACIÓN
-- =====================================================================
-- SELECT 'tbl_color tiene genero?' AS test,
--        COUNT(*) AS columnas
--   FROM INFORMATION_SCHEMA.COLUMNS
--  WHERE TABLE_NAME='tbl_color' AND COLUMN_NAME='genero'; -- esperado: 0
--
-- SELECT 'idx_codigo en producto?' AS test, COUNT(*) AS idx
--   FROM INFORMATION_SCHEMA.STATISTICS
--  WHERE TABLE_NAME='tbl_producto' AND INDEX_NAME='idx_codigo'; -- esperado: 1
--
-- SELECT * FROM tbl_genero WHERE nombre_genero='Niña'; -- esperado: 1 fila
-- =====================================================================
-- FIN
-- =====================================================================
