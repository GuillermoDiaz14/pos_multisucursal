-- Agregar columna talla a tbl_producto
ALTER TABLE tbl_producto ADD COLUMN talla VARCHAR(50) DEFAULT 'NA' NOT NULL;