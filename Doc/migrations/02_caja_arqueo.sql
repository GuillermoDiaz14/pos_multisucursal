-- =====================================================================
-- Migración 02: Soporte para arqueo de caja
-- =====================================================================
-- Objetivo:
--   - Conservar el monto de apertura (sin que las ventas/gastos lo reescriban).
--   - Registrar el arqueo al cierre: esperado, contado, diferencia, observaciones.
--   - Cambiar fechas a DATETIME para conocer la hora exacta de apertura/cierre.
--
-- Aplicar UNA sola vez:
--   mysql -u <usuario> -p <base_de_datos> < 02_caja_arqueo.sql
-- =====================================================================

START TRANSACTION;

-- 1) Nuevos campos para arqueo
ALTER TABLE tbl_caja
    ADD COLUMN monto_apertura       DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER saldo,
    ADD COLUMN efectivo_esperado    DECIMAL(10,2) NULL              AFTER monto_apertura,
    ADD COLUMN efectivo_contado     DECIMAL(10,2) NULL              AFTER efectivo_esperado,
    ADD COLUMN diferencia           DECIMAL(10,2) NULL              AFTER efectivo_contado,
    ADD COLUMN observaciones_cierre VARCHAR(500)  NULL              AFTER diferencia;

-- 2) Cambiar saldo de FLOAT a DECIMAL para evitar pérdida de precisión
ALTER TABLE tbl_caja
    MODIFY saldo DECIMAL(10,2) NOT NULL DEFAULT 0;

-- 3) Cambiar fechas a DATETIME (preserva los valores existentes)
ALTER TABLE tbl_caja
    MODIFY fecha_apertura DATETIME NOT NULL,
    MODIFY fecha_cierre   DATETIME NULL;

-- 4) Backfill: para cajas históricas no podemos reconstruir el monto_apertura
--    real, así que se queda en 0. Si quieres preservar el saldo actual como
--    referencia para las cajas aún abiertas, descomenta:
-- UPDATE tbl_caja SET monto_apertura = saldo WHERE estado = 'abierto';

-- 5) Índice útil para búsquedas por sucursal y estado
ALTER TABLE tbl_caja
    ADD INDEX idx_caja_sucursal_estado (id_sucursal, estado);

COMMIT;

-- =====================================================================
-- Verificación post-migración
-- =====================================================================
DESCRIBE tbl_caja;
SELECT id_caja, fecha_apertura, fecha_cierre, monto_apertura, saldo,
       efectivo_esperado, efectivo_contado, diferencia, estado
FROM tbl_caja
ORDER BY id_caja DESC
LIMIT 5;
