-- Índices de performance para el módulo de ventas
-- Ejecutar una sola vez en la base de datos de producción.
-- Todos usan IF NOT EXISTS para ser idempotentes.

-- tbl_venta: consultas por sucursal + filtros de tipo_pago / tipo_venta / estado_apartado
ALTER TABLE tbl_venta
    ADD INDEX IF NOT EXISTS idx_venta_sucursal_tipo    (id_sucursal, tipo_pago),
    ADD INDEX IF NOT EXISTS idx_venta_sucursal_apartado (id_sucursal, tipo_venta, estado_apartado),
    ADD INDEX IF NOT EXISTS idx_venta_caja              (id_caja),
    ADD INDEX IF NOT EXISTS idx_venta_usuario           (id_usuario),
    ADD INDEX IF NOT EXISTS idx_venta_cliente           (id_cliente);

-- tbl_detalle_venta: JOIN con tbl_venta por id_venta
ALTER TABLE tbl_detalle_venta
    ADD INDEX IF NOT EXISTS idx_detalle_venta (id_venta);

-- tbl_producto_stock: lookup por sucursal + producto (join y validaciones de inventario)
ALTER TABLE tbl_producto_stock
    ADD INDEX IF NOT EXISTS idx_stock_sucursal_producto (id_sucursal, id_producto),
    ADD INDEX IF NOT EXISTS idx_stock_sucursal_activo   (id_sucursal, stock);

-- tbl_producto: búsqueda LIKE por nombre en el POS
-- FULLTEXT es más eficiente que LIKE '%...%' para búsquedas de texto
ALTER TABLE tbl_producto
    ADD FULLTEXT INDEX IF NOT EXISTS ft_producto_nombre_codigo (nombre_producto, codigo);

-- tbl_caja: consultas frecuentes de caja abierta por sucursal/usuario
ALTER TABLE tbl_caja
    ADD INDEX IF NOT EXISTS idx_caja_estado_sucursal  (id_sucursal, estado),
    ADD INDEX IF NOT EXISTS idx_caja_usuario_estado   (id_usuario, estado);

-- tbl_cuota: JOIN con tbl_venta
ALTER TABLE tbl_cuota
    ADD INDEX IF NOT EXISTS idx_cuota_venta (id_venta);
