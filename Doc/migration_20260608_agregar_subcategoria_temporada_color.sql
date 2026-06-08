-- =====================================================================
-- Migración: Agregar Subcategoría, Temporada, Color y Género
-- Fecha: 2026-06-08
-- Descripción: Mejora sistema de productos para mejor granularidad en reportes
-- Base: pos_multisucursal
-- =====================================================================

-- USE pos_multisucursal; -- Descomenta para ejecutar directo

-- =====================================================================
-- 1. NUEVAS TABLAS (crear antes de modificar tbl_producto)
-- =====================================================================

-- Tabla de Temporadas (Navidad, Halloween, Verano, Regular, etc)
CREATE TABLE IF NOT EXISTS `tbl_temporada` (
  `id_temporada` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre_temporada` VARCHAR(100) NOT NULL UNIQUE,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `fecha_inicio` DATE DEFAULT NULL,
  `fecha_fin` DATE DEFAULT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `createdDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDtm` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_activa` (`activa`),
  INDEX `idx_fecha_rango` (`fecha_inicio`, `fecha_fin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Colores (con código hex para visualización)
CREATE TABLE IF NOT EXISTS `tbl_color` (
  `id_color` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre_color` VARCHAR(50) NOT NULL UNIQUE,
  `codigo_hex` VARCHAR(7) DEFAULT NULL COMMENT 'Código hexadecimal: #RRGGBB',
  `genero` VARCHAR(50) DEFAULT 'NA' COMMENT 'Género asociado al color',
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `createdDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDtm` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Subcategorías (depende de Categoría)
CREATE TABLE IF NOT EXISTS `tbl_subcategoria` (
  `id_subcategoria` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_categoria` INT(11) NOT NULL,
  `id_sucursal` INT(11) NOT NULL DEFAULT 1,
  `nombre_subcategoria` VARCHAR(200) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `activa` TINYINT(1) NOT NULL DEFAULT 1,
  `createdDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDtm` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_nombre_por_categoria` (`id_categoria`, `id_sucursal`, `nombre_subcategoria`),
  FOREIGN KEY (`id_categoria`) REFERENCES `tbl_categoria`(`id_categoria`) ON DELETE RESTRICT,
  FOREIGN KEY (`id_sucursal`) REFERENCES `tbl_sucursal`(`id_sucursal`) ON DELETE RESTRICT,
  INDEX `idx_categoria` (`id_categoria`),
  INDEX `idx_sucursal` (`id_sucursal`),
  INDEX `idx_activa` (`activa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla de Stock Mínimo por Subcategoría y Sucursal
CREATE TABLE IF NOT EXISTS `tbl_stock_minimo_subcategoria` (
  `id_stock_minimo` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_subcategoria` INT(11) NOT NULL,
  `id_sucursal` INT(11) NOT NULL,
  `stock_minimo` INT(11) NOT NULL DEFAULT 5,
  `createdDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDtm` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_subcategoria_sucursal` (`id_subcategoria`, `id_sucursal`),
  FOREIGN KEY (`id_subcategoria`) REFERENCES `tbl_subcategoria`(`id_subcategoria`) ON DELETE CASCADE,
  FOREIGN KEY (`id_sucursal`) REFERENCES `tbl_sucursal`(`id_sucursal`) ON DELETE CASCADE,
  INDEX `idx_subcategoria` (`id_subcategoria`),
  INDEX `idx_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- 2. MODIFICAR tbl_producto (agregar nuevas FKs)
-- =====================================================================

-- Agregar columnas si no existen
ALTER TABLE `tbl_producto` 
ADD COLUMN `id_subcategoria` INT(11) DEFAULT NULL AFTER `categoria`,
ADD COLUMN `id_temporada` INT(11) DEFAULT NULL AFTER `id_subcategoria`,
ADD COLUMN `id_color` INT(11) DEFAULT NULL AFTER `id_temporada`,
ADD COLUMN `genero` VARCHAR(50) DEFAULT 'NA' AFTER `id_color`;

-- Agregar FKs con manejo de restricciones
ALTER TABLE `tbl_producto` 
ADD CONSTRAINT `fk_producto_subcategoria` 
FOREIGN KEY (`id_subcategoria`) REFERENCES `tbl_subcategoria`(`id_subcategoria`) ON DELETE SET NULL,
ADD CONSTRAINT `fk_producto_temporada` 
FOREIGN KEY (`id_temporada`) REFERENCES `tbl_temporada`(`id_temporada`) ON DELETE SET NULL,
ADD CONSTRAINT `fk_producto_color` 
FOREIGN KEY (`id_color`) REFERENCES `tbl_color`(`id_color`) ON DELETE SET NULL;

-- =====================================================================
-- 3. CREAR ÍNDICES para optimizar consultas (CRÍTICO para rendimiento)
-- =====================================================================

-- Índice compuesto para búsquedas multidimensionales rápidas
ALTER TABLE `tbl_producto` 
ADD INDEX `idx_categoria_subcategoria_temporada` (`categoria`, `id_subcategoria`, `id_temporada`),
ADD INDEX `idx_color_genero` (`id_color`, `genero`),
ADD INDEX `idx_temporada` (`id_temporada`),
ADD INDEX `idx_subcategoria_producto` (`id_subcategoria`);

-- =====================================================================
-- 4. DATOS INICIALES (Plantilla base)
-- =====================================================================

-- Temporadas iniciales
INSERT INTO `tbl_temporada` (`nombre_temporada`, `descripcion`, `activa`) 
VALUES 
  ('Regular', 'Productos de venta regular sin temporada', 1),
  ('Navidad 2026', 'Colección navideña', 0),
  ('Año Nuevo 2027', 'Colección Año Nuevo', 0),
  ('Halloween 2026', 'Colección Halloween', 0),
  ('Verano 2026', 'Colección de verano', 0)
ON DUPLICATE KEY UPDATE activa=VALUES(activa);

-- Colores iniciales
INSERT INTO `tbl_color` (`nombre_color`, `codigo_hex`, `genero`, `activo`) 
VALUES 
  ('Negro', '#000000', 'NA', 1),
  ('Blanco', '#FFFFFF', 'NA', 1),
  ('Rojo', '#FF0000', 'NA', 1),
  ('Azul', '#0000FF', 'NA', 1),
  ('Verde', '#008000', 'NA', 1),
  ('Amarillo', '#FFFF00', 'NA', 1),
  ('Gris', '#808080', 'NA', 1),
  ('Multicolor', NULL, 'NA', 1)
ON DUPLICATE KEY UPDATE activo=VALUES(activo), genero=VALUES(genero);

-- =====================================================================
-- 5. VALIDACIÓN (Consultas para verificar correctitud)
-- =====================================================================

-- Verificar que las nuevas columnas existen
-- SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME='tbl_producto' AND COLUMN_NAME IN ('id_subcategoria', 'id_temporada', 'id_color', 'genero');

-- Verificar que las nuevas tablas existen
-- SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
-- WHERE TABLE_SCHEMA='pos_multisucursal' 
-- AND TABLE_NAME IN ('tbl_subcategoria', 'tbl_temporada', 'tbl_color', 'tbl_stock_minimo_subcategoria');

-- =====================================================================
-- FIN DE MIGRACIÓN
-- =====================================================================
