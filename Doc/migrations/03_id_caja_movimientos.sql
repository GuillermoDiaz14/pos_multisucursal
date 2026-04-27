-- =====================================================================
-- Migración 03: Asociar movimientos a su caja específica
-- =====================================================================
-- Objetivo:
--   Permitir que cada venta, cuota, gasto e ingreso quede vinculado a la
--   caja (turno) en la que se registró. Esto habilita:
--     - Multi-cajero por sucursal (cada cajero responde por su propia caja).
--     - Resumen de cierre exacto: en vez de filtrar por rango de fechas,
--       se filtran los movimientos por id_caja.
--
-- Nota: para registros históricos (anteriores a esta migración) el campo
-- id_caja queda en NULL. El resumen del turno solo considera movimientos
-- con id_caja igual al turno cerrado, así que registros viejos no afectan
-- los nuevos cierres.
--
-- Aplicar UNA sola vez:
--   mysql -u <usuario> -p <base_de_datos> < 03_id_caja_movimientos.sql
-- =====================================================================

START TRANSACTION;

-- 1) Agregar id_caja a las tablas de movimientos
ALTER TABLE tbl_venta
    ADD COLUMN id_caja INT(11) NULL AFTER id_sucursal,
    ADD INDEX idx_venta_caja (id_caja);

ALTER TABLE tbl_cuota
    ADD COLUMN id_caja INT(11) NULL AFTER id_venta,
    ADD INDEX idx_cuota_caja (id_caja);

ALTER TABLE tbl_gasto
    ADD COLUMN id_caja INT(11) NULL AFTER id_sucursal,
    ADD INDEX idx_gasto_caja (id_caja);

ALTER TABLE tbl_ingreso
    ADD COLUMN id_caja INT(11) NULL AFTER id_sucursal,
    ADD INDEX idx_ingreso_caja (id_caja);

COMMIT;

-- =====================================================================
-- Verificación
-- =====================================================================
SHOW COLUMNS FROM tbl_venta   LIKE 'id_caja';
SHOW COLUMNS FROM tbl_cuota   LIKE 'id_caja';
SHOW COLUMNS FROM tbl_gasto   LIKE 'id_caja';
SHOW COLUMNS FROM tbl_ingreso LIKE 'id_caja';
