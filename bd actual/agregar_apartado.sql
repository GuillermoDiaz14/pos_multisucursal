-- Módulo de Apartados: campos adicionales en tbl_venta
-- Ejecutar en la base de datos pos_multisucursal

ALTER TABLE `tbl_venta`
  ADD COLUMN `tipo_venta` VARCHAR(20) NOT NULL DEFAULT 'normal' COMMENT 'normal | apartado',
  ADD COLUMN `estado_apartado` VARCHAR(20) NULL DEFAULT NULL COMMENT 'en_proceso | entregado | cancelado',
  ADD COLUMN `anticipo` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Pago inicial registrado al crear el apartado';
