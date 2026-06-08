-- =====================================================================
-- Migración: Crear catálogo de géneros
-- Fecha: 2026-06-08
-- Descripción: Convierte géneros en un catálogo CRUD simple
-- Base: pos_multisucursal
-- =====================================================================

CREATE TABLE IF NOT EXISTS `tbl_genero` (
  `id_genero` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre_genero` VARCHAR(50) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `createdDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDtm` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_activa` (`activa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `tbl_genero` (`nombre_genero`, `descripcion`, `activa`)
VALUES
  ('NA', 'Sin género especificado', 1),
  ('Hombre', 'Productos orientados a hombres', 1),
  ('Mujer', 'Productos orientados a mujeres', 1),
  ('Unisex', 'Productos para cualquier género', 1),
  ('Niño', 'Productos orientados a niños', 1)
ON DUPLICATE KEY UPDATE
  `descripcion` = VALUES(`descripcion`),
  `activa` = VALUES(`activa`);

