-- =====================================================================
-- Migración 01: Normalizar tbl_metodo_pago
-- =====================================================================
-- Objetivo: estandarizar nombres a Title Case (primera mayúscula) para
-- evitar duplicados visuales y facilitar comparaciones consistentes.
--
-- Las consultas del sistema ya comparan con LOWER(TRIM(...)), por lo que
-- esta normalización es cosmética/de higiene, no rompe nada existente.
--
-- Aplicar UNA sola vez:
--   mysql -u <usuario> -p <base_de_datos> < 01_normalize_metodo_pago.sql
-- =====================================================================

START TRANSACTION;

-- 1) Trim de espacios sobrantes
UPDATE tbl_metodo_pago
SET nombre_metodo_pago = TRIM(nombre_metodo_pago);

-- 2) Capitalización Title Case (primera letra mayúscula, resto minúsculas)
UPDATE tbl_metodo_pago
SET nombre_metodo_pago = CONCAT(
    UCASE(LEFT(nombre_metodo_pago, 1)),
    LCASE(SUBSTRING(nombre_metodo_pago, 2))
);

-- 3) (Opcional, revisar antes de descomentar) eliminar registros huérfanos
--    apuntando a una sucursal inexistente. Verifica primero con:
--      SELECT mp.* FROM tbl_metodo_pago mp
--      LEFT JOIN tbl_sucursal s ON s.id_sucursal = mp.id_sucursal
--      WHERE s.id_sucursal IS NULL;
--    Si confirmas que son basura:
-- DELETE mp FROM tbl_metodo_pago mp
-- LEFT JOIN tbl_sucursal s ON s.id_sucursal = mp.id_sucursal
-- WHERE s.id_sucursal IS NULL;

COMMIT;

-- =====================================================================
-- Verificación post-migración
-- =====================================================================
SELECT id_metodo_pago, nombre_metodo_pago, id_sucursal
FROM tbl_metodo_pago
ORDER BY id_sucursal, nombre_metodo_pago;
