-- =====================================================================
-- POS Multisucursal - Estructura completa de base de datos
-- =====================================================================
-- Genera la base `pos_multisucursal` con todas las tablas e inserta
-- los valores mínimos para:
--   1. Iniciar sesión (usuario admin)
--   2. Crear productos (categoría + sucursal lista)
--   3. Registrar una venta (cliente, método de pago, caja abierta)
--
-- Credenciales iniciales:
--   Email:    admin@tunegocio.com
--   Password: admin123
--
-- Importación (modo seguro, no destructivo):
--   mysql -u root -p < structure.sql
--
-- IMPORTANTE: este script crea la base solo si NO existe. Si ya existe
-- una base llamada `pos_multisucursal`, las sentencias CREATE TABLE
-- fallarán para preservar los datos actuales. Para una instalación
-- limpia desde cero, eliminar manualmente la base antes:
--   DROP DATABASE pos_multisucursal;
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `pos_multisucursal`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;
USE `pos_multisucursal`;

-- =====================================================================
-- TABLAS DE LA APLICACIÓN (estructura completa de bd_desarrollo)
-- =====================================================================

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tbl_access_matrix` (
  `id` int(11) NOT NULL,
  `access` text DEFAULT NULL,
  `roleId` smallint(6) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tbl_caja` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `saldo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_usuario_cierre` int(11) DEFAULT NULL,
  `monto_apertura` decimal(10,2) NOT NULL DEFAULT 0.00,
  `efectivo_esperado` decimal(10,2) DEFAULT NULL,
  `efectivo_contado` decimal(10,2) DEFAULT NULL,
  `diferencia` decimal(10,2) DEFAULT NULL,
  `observaciones_cierre` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_categoria` (
  `id_categoria` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL DEFAULT 1,
  `nombre_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `doc_identidad` varchar(200) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_compra` (
  `id_compra` int(11) NOT NULL,
  `fecha_compra` date NOT NULL,
  `proveedor` int(11) NOT NULL,
  `nota` varchar(400) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `nombre_empresa` varchar(200) NOT NULL,
  `telefono` int(20) NOT NULL,
  `impuesto` float NOT NULL,
  `simbolo_moneda` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_cuota` (
  `id_cuota` int(11) NOT NULL,
  `cuota` decimal(10,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_detalle_traslado` (
  `id_detalle_traslado` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `cantidad` int(20) NOT NULL,
  `id_traslado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_variante` int(11) DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_empleado` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `dni` varchar(200) NOT NULL,
  `celular` varchar(200) NOT NULL,
  `esEliminado` varchar(200) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL COMMENT 'usuario asociado (rol Vendedor) creado junto al empleado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_gasto` (
  `id_gasto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_ingreso` (
  `id_ingreso` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tbl_metodo_pago` (
  `id_metodo_pago` int(11) NOT NULL,
  `nombre_metodo_pago` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_producto` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(200) NOT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `codigo` varchar(200) NOT NULL,
  `categoria` int(11) NOT NULL,
  `imagen` varchar(200) NOT NULL,
  `detalles` varchar(200) NOT NULL,
  `talla` varchar(50) NOT NULL DEFAULT 'NA',
  `tiene_variantes` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_producto_stock` (
  `id_producto_stock` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_producto_variante` (
  `id_variante` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `talla` varchar(20) NOT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `orden` smallint(6) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `doc_fiscal` varchar(20) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_reset_password` (
  `id` bigint(20) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activation_id` varchar(32) NOT NULL,
  `agent` varchar(512) NOT NULL,
  `client_ip` varchar(32) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` bigint(20) NOT NULL DEFAULT 1,
  `createdDtm` datetime NOT NULL,
  `updatedBy` bigint(20) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `tbl_roles` (
  `roleId` smallint(6) NOT NULL,
  `role` varchar(50) NOT NULL COMMENT 'role text',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tbl_stock_variante` (
  `id_stock_variante` int(11) NOT NULL,
  `id_variante` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `nombre_sucursal` varchar(200) NOT NULL,
  `impuesto` float NOT NULL,
  `celular` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `ciudad` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `simbolo_moneda` varchar(200) NOT NULL,
  `ticket_mostrar_logo` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_logo` varchar(255) DEFAULT NULL,
  `ticket_mostrar_tel` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_dir` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_ciudad` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_correo` tinyint(1) NOT NULL DEFAULT 0,
  `ticket_mostrar_num` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_fecha` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_cliente` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_desc` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_mostrar_cambio` tinyint(1) NOT NULL DEFAULT 1,
  `ticket_subtitulo` varchar(200) NOT NULL DEFAULT '',
  `ticket_msg_gracias` varchar(300) NOT NULL DEFAULT '¡Gracias por su compra!',
  `ticket_politica` text DEFAULT NULL,
  `ticket_logo_opacidad` int(11) NOT NULL DEFAULT 80,
  `ticket_logo_ancho` int(11) NOT NULL DEFAULT 70,
  `ticket_margen` int(11) NOT NULL DEFAULT 5,
  `ticket_separador` int(11) NOT NULL DEFAULT 3,
  `ticket_fs_titulo` int(11) NOT NULL DEFAULT 48,
  `ticket_fs_info` int(11) NOT NULL DEFAULT 22,
  `ticket_fs_normal` int(11) NOT NULL DEFAULT 24,
  `ticket_fs_total` int(11) NOT NULL DEFAULT 40,
  `ticket_fs_gracias` int(11) NOT NULL DEFAULT 28,
  `zebra_ticket_printer` varchar(100) DEFAULT NULL,
  `zebra_label_printer` varchar(100) DEFAULT NULL,
  `zebra_ticket_media_type` varchar(10) DEFAULT '^MNC',
  `zebra_label_media_type` varchar(10) DEFAULT '^MNN'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_traslado` (
  `id_traslado` int(11) NOT NULL,
  `fecha_actual` date NOT NULL,
  `comentario` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal_descuento` int(11) NOT NULL,
  `id_sucursal_aumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `email` varchar(128) NOT NULL COMMENT 'login email',
  `password` varchar(128) NOT NULL COMMENT 'hashed login password',
  `name` varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile` varchar(20) DEFAULT NULL,
  `roleId` smallint(6) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 2,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL,
  `id_sucursal` int(11) NOT NULL,
  `foto` int(11) DEFAULT NULL COMMENT 'timestamp ultimo upload foto perfil',
  `color_tema` varchar(7) NOT NULL DEFAULT '#3c8dbc' COMMENT 'color tema personal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `tbl_venta` (
  `id_venta` int(11) NOT NULL,
  `fecha_venta` date NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
  `base_imponible` decimal(10,2) NOT NULL,
  `impuesto` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_pago` varchar(200) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `saldo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cambio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_sucursal` int(11) NOT NULL,
  `tipo_venta` varchar(20) NOT NULL DEFAULT 'normal' COMMENT 'normal | apartado',
  `estado_apartado` varchar(20) DEFAULT NULL COMMENT 'en_proceso | entregado | cancelado',
  `anticipo` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Pago inicial registrado al crear el apartado',
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================================
-- ÍNDICES Y CONSTRAINTS
-- =====================================================================

ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);
ALTER TABLE `tbl_access_matrix`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_caja`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `idx_caja_estado_sucursal` (`id_sucursal`,`estado`),
  ADD KEY `idx_caja_usuario_estado` (`id_usuario`,`estado`);
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `idx_cat_sucursal` (`id_sucursal`);
ALTER TABLE `tbl_cliente`
  ADD PRIMARY KEY (`id_cliente`);
ALTER TABLE `tbl_compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `idx_compra_sucursal_fecha` (`id_sucursal`,`fecha_compra`);
ALTER TABLE `tbl_configuracion`
  ADD PRIMARY KEY (`id_configuracion`);
ALTER TABLE `tbl_cuota`
  ADD PRIMARY KEY (`id_cuota`),
  ADD KEY `idx_cuota_venta` (`id_venta`);
ALTER TABLE `tbl_detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `idx_detcompra_variante` (`id_variante`),
  ADD KEY `idx_detcompra_compra` (`id_compra`),
  ADD KEY `idx_detcompra_producto` (`id_producto`);
ALTER TABLE `tbl_detalle_traslado`
  ADD PRIMARY KEY (`id_detalle_traslado`),
  ADD KEY `idx_dettraslado_variante` (`id_variante`),
  ADD KEY `idx_dettraslado_traslado` (`id_traslado`),
  ADD KEY `idx_dettraslado_producto` (`id_producto`);
ALTER TABLE `tbl_detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `idx_detalle_venta` (`id_venta`),
  ADD KEY `idx_detventa_variante` (`id_variante`),
  ADD KEY `idx_detventa_producto` (`id_producto`);
ALTER TABLE `tbl_empleado`
  ADD PRIMARY KEY (`id_empleado`);
ALTER TABLE `tbl_gasto`
  ADD PRIMARY KEY (`id_gasto`);
ALTER TABLE `tbl_ingreso`
  ADD PRIMARY KEY (`id_ingreso`);
ALTER TABLE `tbl_last_login`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_metodo_pago`
  ADD PRIMARY KEY (`id_metodo_pago`);
ALTER TABLE `tbl_producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `idx_codigo` (`codigo`),
  ADD KEY `idx_categoria` (`categoria`),
  ADD KEY `idx_nombre` (`nombre_producto`(50)),
  ADD KEY `idx_producto_tiene_variantes` (`tiene_variantes`);
ALTER TABLE `tbl_producto` ADD FULLTEXT KEY `ft_producto_nombre_codigo` (`nombre_producto`,`codigo`);
ALTER TABLE `tbl_producto_stock`
  ADD PRIMARY KEY (`id_producto_stock`),
  ADD UNIQUE KEY `unique_producto_sucursal` (`id_producto`,`id_sucursal`),
  ADD KEY `idx_sucursal` (`id_sucursal`),
  ADD KEY `idx_stock_sucursal_producto` (`id_sucursal`,`id_producto`),
  ADD KEY `idx_stock_sucursal_activo` (`id_sucursal`,`stock`);
ALTER TABLE `tbl_producto_variante`
  ADD PRIMARY KEY (`id_variante`),
  ADD UNIQUE KEY `uk_producto_talla` (`id_producto`,`talla`),
  ADD KEY `idx_variante_producto` (`id_producto`,`activo`);
ALTER TABLE `tbl_proveedor`
  ADD PRIMARY KEY (`id_proveedor`);
ALTER TABLE `tbl_reset_password`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`roleId`);
ALTER TABLE `tbl_stock_variante`
  ADD PRIMARY KEY (`id_stock_variante`),
  ADD UNIQUE KEY `uk_variante_sucursal` (`id_variante`,`id_sucursal`),
  ADD KEY `idx_stock_sucursal` (`id_sucursal`,`stock`);
ALTER TABLE `tbl_sucursal`
  ADD PRIMARY KEY (`id_sucursal`);
ALTER TABLE `tbl_traslado`
  ADD PRIMARY KEY (`id_traslado`);
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);
ALTER TABLE `tbl_venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `idx_venta_sucursal_tipo` (`id_sucursal`,`tipo_pago`),
  ADD KEY `idx_venta_sucursal_apartado` (`id_sucursal`,`tipo_venta`,`estado_apartado`),
  ADD KEY `idx_venta_caja` (`id_caja`),
  ADD KEY `idx_venta_usuario` (`id_usuario`),
  ADD KEY `idx_venta_cliente` (`id_cliente`),
  ADD KEY `idx_venta_sucursal_fecha` (`id_sucursal`,`fecha_venta`);
ALTER TABLE `tbl_access_matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_cuota`
  MODIFY `id_cuota` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_detalle_traslado`
  MODIFY `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_gasto`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_ingreso`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `tbl_metodo_pago`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `tbl_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_producto_stock`
  MODIFY `id_producto_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_producto_variante`
  MODIFY `id_variante` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_roles`
  MODIFY `roleId` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_stock_variante`
  MODIFY `id_stock_variante` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `tbl_venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tbl_detalle_compra`
  ADD CONSTRAINT `fk_detcompra_variante` FOREIGN KEY (`id_variante`) REFERENCES `tbl_producto_variante` (`id_variante`) ON UPDATE CASCADE;
ALTER TABLE `tbl_detalle_traslado`
  ADD CONSTRAINT `fk_dettraslado_variante` FOREIGN KEY (`id_variante`) REFERENCES `tbl_producto_variante` (`id_variante`) ON UPDATE CASCADE;
ALTER TABLE `tbl_detalle_venta`
  ADD CONSTRAINT `fk_detventa_variante` FOREIGN KEY (`id_variante`) REFERENCES `tbl_producto_variante` (`id_variante`) ON UPDATE CASCADE;
ALTER TABLE `tbl_producto_variante`
  ADD CONSTRAINT `fk_variante_producto` FOREIGN KEY (`id_producto`) REFERENCES `tbl_producto` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `tbl_stock_variante`
  ADD CONSTRAINT `fk_stockvar_sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `tbl_sucursal` (`id_sucursal`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stockvar_variante` FOREIGN KEY (`id_variante`) REFERENCES `tbl_producto_variante` (`id_variante`) ON DELETE CASCADE ON UPDATE CASCADE;