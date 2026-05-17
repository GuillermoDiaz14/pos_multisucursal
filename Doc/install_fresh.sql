-- ============================================================
-- POS Multisucursal - Script de instalación limpia
-- Genera: estructura completa + datos mínimos para arrancar
-- Contraseña admin: Admin1234
-- ============================================================

CREATE DATABASE IF NOT EXISTS `pos_multisucursal`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE `pos_multisucursal`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- ------------------------------------------------------------
-- TABLAS
-- ------------------------------------------------------------

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `session_id`    varchar(40)        NOT NULL DEFAULT '0',
  `ip_address`    varchar(45)        NOT NULL DEFAULT '0',
  `user_agent`    varchar(120)       NOT NULL,
  `last_activity` int(10) unsigned   NOT NULL DEFAULT 0,
  `user_data`     text               NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tbl_access_matrix`;
CREATE TABLE `tbl_access_matrix` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `access`      text         DEFAULT NULL,
  `roleId`      smallint(6)  NOT NULL,
  `isDeleted`   tinyint(4)   NOT NULL DEFAULT 0,
  `createdBy`   int(11)      NOT NULL,
  `createdDtm`  datetime     NOT NULL,
  `updatedBy`   int(11)      DEFAULT NULL,
  `updatedDtm`  datetime     DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tbl_caja`;
CREATE TABLE `tbl_caja` (
  `id_caja`           int(11)      NOT NULL AUTO_INCREMENT,
  `fecha_apertura`    date         NOT NULL,
  `fecha_cierre`      date         DEFAULT NULL,
  `saldo`             float        NOT NULL,
  `estado`            varchar(200) NOT NULL,
  `id_sucursal`       int(11)      NOT NULL,
  `id_usuario`        int(11)      NOT NULL,
  `id_usuario_cierre` int(11)      DEFAULT NULL,
  PRIMARY KEY (`id_caja`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_categoria`;
CREATE TABLE `tbl_categoria` (
  `id_categoria`     int(11)      NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(200) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_cliente`;
CREATE TABLE `tbl_cliente` (
  `id_cliente`   int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`       varchar(200) NOT NULL,
  `correo`       varchar(200) NOT NULL,
  `doc_identidad`varchar(200) NOT NULL,
  `celular`      varchar(100) NOT NULL,
  `id_sucursal`  int(11)      NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_compra`;
CREATE TABLE `tbl_compra` (
  `id_compra`    int(11)         NOT NULL AUTO_INCREMENT,
  `fecha_compra` date            NOT NULL,
  `proveedor`    int(11)         NOT NULL,
  `nota`         varchar(400)    NOT NULL,
  `total`        decimal(10,2)   NOT NULL,
  `id_usuario`   int(11)         NOT NULL,
  `id_sucursal`  int(11)         NOT NULL,
  PRIMARY KEY (`id_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_configuracion`;
CREATE TABLE `tbl_configuracion` (
  `id_configuracion` int(11)      NOT NULL AUTO_INCREMENT,
  `nombre_empresa`   varchar(200) NOT NULL,
  `telefono`         int(20)      NOT NULL,
  `impuesto`         float        NOT NULL,
  `simbolo_moneda`   varchar(200) NOT NULL,
  PRIMARY KEY (`id_configuracion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_cuota`;
CREATE TABLE `tbl_cuota` (
  `id_cuota`   int(11)       NOT NULL AUTO_INCREMENT,
  `cuota`      decimal(10,2) NOT NULL,
  `fecha_pago` date          NOT NULL,
  `id_venta`   int(11)       NOT NULL,
  PRIMARY KEY (`id_cuota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_detalle_compra`;
CREATE TABLE `tbl_detalle_compra` (
  `id_detalle_compra` int(11)       NOT NULL AUTO_INCREMENT,
  `id_producto`       int(11)       NOT NULL,
  `precio_compra`     decimal(10,2) NOT NULL,
  `cantidad`          int(11)       NOT NULL,
  `sub_total`         decimal(10,2) NOT NULL,
  `id_compra`         int(11)       NOT NULL,
  PRIMARY KEY (`id_detalle_compra`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_detalle_traslado`;
CREATE TABLE `tbl_detalle_traslado` (
  `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto`         int(11) NOT NULL,
  `cantidad`            int(20) NOT NULL,
  `id_traslado`         int(11) NOT NULL,
  PRIMARY KEY (`id_detalle_traslado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_detalle_venta`;
CREATE TABLE `tbl_detalle_venta` (
  `id_detalle_venta` int(11)       NOT NULL AUTO_INCREMENT,
  `id_producto`      int(11)       NOT NULL,
  `precio_venta`     decimal(10,2) NOT NULL,
  `cantidad`         int(11)       NOT NULL,
  `sub_total`        decimal(10,2) NOT NULL,
  `id_venta`         int(11)       NOT NULL,
  PRIMARY KEY (`id_detalle_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_empleado`;
CREATE TABLE `tbl_empleado` (
  `id_empleado` int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      varchar(200) NOT NULL,
  `dni`         varchar(200) NOT NULL,
  `celular`     varchar(200) NOT NULL,
  `esEliminado` varchar(200) NOT NULL,
  `id_cat`      int(11)      NOT NULL,
  `id_sucursal` int(11)      NOT NULL,
  PRIMARY KEY (`id_empleado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_gasto`;
CREATE TABLE `tbl_gasto` (
  `id_gasto`    int(11)       NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200)  NOT NULL,
  `monto`       decimal(10,2) NOT NULL,
  `fecha`       date          NOT NULL,
  `id_sucursal` int(11)       NOT NULL,
  PRIMARY KEY (`id_gasto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_ingreso`;
CREATE TABLE `tbl_ingreso` (
  `id_ingreso`  int(11)       NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200)  NOT NULL,
  `monto`       decimal(10,2) NOT NULL,
  `fecha`       date          NOT NULL,
  `id_sucursal` int(11)       NOT NULL,
  PRIMARY KEY (`id_ingreso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_last_login`;
CREATE TABLE `tbl_last_login` (
  `id`          bigint(20)    NOT NULL AUTO_INCREMENT,
  `userId`      bigint(20)    NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp`   varchar(1024) NOT NULL,
  `userAgent`   varchar(128)  NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform`    varchar(128)  NOT NULL,
  `createdDtm`  datetime      NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tbl_metodo_pago`;
CREATE TABLE `tbl_metodo_pago` (
  `id_metodo_pago`    int(11)      NOT NULL AUTO_INCREMENT,
  `nombre_metodo_pago`varchar(200) NOT NULL,
  `id_sucursal`       int(11)      NOT NULL,
  PRIMARY KEY (`id_metodo_pago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_producto`;
CREATE TABLE `tbl_producto` (
  `id_producto`    int(11)       NOT NULL AUTO_INCREMENT,
  `nombre_producto`varchar(200)  NOT NULL,
  `precio_compra`  decimal(10,2) NOT NULL,
  `precio_venta`   decimal(10,2) NOT NULL,
  `codigo`         varchar(200)  NOT NULL,
  `categoria`      int(11)       NOT NULL,
  `imagen`         varchar(200)  NOT NULL,
  `detalles`       varchar(200)  NOT NULL,
  `talla`          varchar(50)   NOT NULL DEFAULT 'NA',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_producto_stock`;
CREATE TABLE `tbl_producto_stock` (
  `id_producto_stock` int(11) NOT NULL AUTO_INCREMENT,
  `id_producto`       int(11) NOT NULL,
  `stock`             int(11) NOT NULL,
  `id_sucursal`       int(11) NOT NULL,
  PRIMARY KEY (`id_producto_stock`),
  UNIQUE KEY `unique_producto_sucursal` (`id_producto`,`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_proveedor`;
CREATE TABLE `tbl_proveedor` (
  `id_proveedor` int(11)      NOT NULL AUTO_INCREMENT,
  `nombre`       varchar(200) NOT NULL,
  `email`        varchar(200) NOT NULL,
  `celular`      varchar(20)  NOT NULL,
  `direccion`    varchar(200) NOT NULL,
  `doc_fiscal`   varchar(20)  NOT NULL,
  `id_sucursal`  int(11)      NOT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_reset_password`;
CREATE TABLE `tbl_reset_password` (
  `id`            bigint(20)   NOT NULL AUTO_INCREMENT,
  `email`         varchar(128) NOT NULL,
  `activation_id` varchar(32)  NOT NULL,
  `agent`         varchar(512) NOT NULL,
  `client_ip`     varchar(32)  NOT NULL,
  `isDeleted`     tinyint(4)   NOT NULL DEFAULT 0,
  `createdBy`     bigint(20)   NOT NULL DEFAULT 1,
  `createdDtm`    datetime     NOT NULL,
  `updatedBy`     bigint(20)   DEFAULT NULL,
  `updatedDtm`    datetime     DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `tbl_roles`;
CREATE TABLE `tbl_roles` (
  `roleId`     smallint(6)  NOT NULL AUTO_INCREMENT,
  `role`       varchar(50)  NOT NULL COMMENT 'role text',
  `status`     tinyint(4)   NOT NULL DEFAULT 1,
  `isDeleted`  tinyint(4)   NOT NULL DEFAULT 0,
  `createdBy`  int(11)      NOT NULL,
  `createdDtm` datetime     NOT NULL,
  `updatedBy`  int(11)      DEFAULT NULL,
  `updatedDtm` datetime     DEFAULT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tbl_sucursal`;
CREATE TABLE `tbl_sucursal` (
  `id_sucursal`     int(11)      NOT NULL AUTO_INCREMENT,
  `nombre_sucursal` varchar(200) NOT NULL,
  `impuesto`        float        NOT NULL,
  `celular`         varchar(200) NOT NULL,
  `direccion`       varchar(200) NOT NULL,
  `ciudad`          varchar(200) NOT NULL,
  `correo`          varchar(200) NOT NULL,
  `simbolo_moneda`  varchar(200) NOT NULL,
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_traslado`;
CREATE TABLE `tbl_traslado` (
  `id_traslado`          int(11)      NOT NULL AUTO_INCREMENT,
  `fecha_actual`         date         NOT NULL,
  `comentario`           varchar(200) NOT NULL,
  `id_usuario`           int(11)      NOT NULL,
  `id_sucursal_descuento`int(11)      NOT NULL,
  `id_sucursal_aumento`  int(11)      NOT NULL,
  PRIMARY KEY (`id_traslado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE `tbl_users` (
  `userId`      int(11)      NOT NULL AUTO_INCREMENT,
  `email`       varchar(128) NOT NULL  COMMENT 'login email',
  `password`    varchar(128) NOT NULL  COMMENT 'hashed login password',
  `name`        varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile`      varchar(20)  DEFAULT NULL,
  `roleId`      smallint(6)  NOT NULL,
  `isAdmin`     tinyint(4)   NOT NULL DEFAULT 2,
  `isDeleted`   tinyint(4)   NOT NULL DEFAULT 0,
  `createdBy`   int(11)      NOT NULL,
  `createdDtm`  datetime     NOT NULL,
  `updatedBy`   int(11)      DEFAULT NULL,
  `updatedDtm`  datetime     DEFAULT NULL,
  `id_sucursal` int(11)      NOT NULL,
  `foto`        varchar(200) DEFAULT NULL,
  `color_tema`  varchar(20)  DEFAULT '#343a40',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS `tbl_venta`;
CREATE TABLE `tbl_venta` (
  `id_venta`       int(11)      NOT NULL AUTO_INCREMENT,
  `fecha_venta`    date         NOT NULL,
  `id_cliente`     int(11)      NOT NULL,
  `descuento`      decimal(10,2)NOT NULL DEFAULT 0.00,
  `base_imponible` decimal(10,2)NOT NULL,
  `impuesto`       decimal(10,2)NOT NULL,
  `total`          decimal(10,2)NOT NULL,
  `id_usuario`     int(11)      NOT NULL,
  `tipo_pago`      varchar(200) NOT NULL,
  `id_metodo_pago` int(11)      NOT NULL,
  `saldo`          decimal(10,2)NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(10,2)NOT NULL DEFAULT 0.00,
  `cambio`         decimal(10,2)NOT NULL DEFAULT 0.00,
  `id_sucursal`    int(11)      NOT NULL,
  `tipo_venta`     varchar(20)  NOT NULL DEFAULT 'normal' COMMENT 'normal | apartado',
  `estado_apartado`varchar(20)  DEFAULT NULL COMMENT 'en_proceso | entregado | cancelado',
  `anticipo`       decimal(10,2)NOT NULL DEFAULT 0.00 COMMENT 'Pago inicial registrado al crear el apartado',
  PRIMARY KEY (`id_venta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- DATOS INICIALES
-- ------------------------------------------------------------

-- Sucursal principal
-- Cambia nombre, ciudad, etc. según tu negocio
INSERT INTO `tbl_sucursal`
  (`id_sucursal`, `nombre_sucursal`, `impuesto`, `celular`, `direccion`, `ciudad`, `correo`, `simbolo_moneda`)
VALUES
  (1, 'Sucursal Principal', 0, '00000000', 'Dirección principal', 'Ciudad', 'correo@empresa.com', 'Q');

-- Configuración general de la empresa
INSERT INTO `tbl_configuracion`
  (`id_configuracion`, `nombre_empresa`, `telefono`, `impuesto`, `simbolo_moneda`)
VALUES
  (1, 'Mi Empresa', 0, 0, 'Q');

-- Rol Administrador (roleId=1 — el sistema lo busca por ID, no por nombre)
INSERT INTO `tbl_roles`
  (`roleId`, `role`, `status`, `isDeleted`, `createdBy`, `createdDtm`)
VALUES
  (1, 'Administrador', 1, 0, 1, NOW());

-- Acceso total al rol Administrador (todos los módulos + todos los reportes)
INSERT INTO `tbl_access_matrix`
  (`roleId`, `access`, `isDeleted`, `createdBy`, `createdDtm`)
VALUES (
  1,
  '[{"module":"Caja","total_access":1},{"module":"Ventas","total_access":1,"editar":1,"eliminar":1},{"module":"Compras","total_access":1},{"module":"Gastos","total_access":1},{"module":"Ingresos","total_access":1},{"module":"Métodos de Pago","total_access":1},{"module":"Productos","total_access":1,"ver_precio_compra":1,"gestionar":1},{"module":"Proveedores","total_access":1},{"module":"Traslados","total_access":1},{"module":"Sucursal","total_access":1},{"module":"Empleado","total_access":1},{"module":"Cliente","total_access":1},{"module":"Configuracion","total_access":1},{"module":"Reportes","total_access":1,"scope":"todas","reports":[{"key":"ventas_diarias","allowed":1},{"key":"ventas_periodo","allowed":1},{"key":"ventas_mensuales","allowed":1},{"key":"productos_mas_vendidos","allowed":1},{"key":"utilidad_estimada","allowed":1},{"key":"ventas_por_vendedor","allowed":1},{"key":"compras_periodo","allowed":1},{"key":"compras_mensuales","allowed":1},{"key":"compras_por_proveedor","allowed":1},{"key":"caja_operativa","allowed":1},{"key":"flujo_total","allowed":1},{"key":"historial_cajas","allowed":1},{"key":"stock_actual","allowed":1},{"key":"stock_bajo","allowed":1},{"key":"traslados_enviados","allowed":1},{"key":"traslados_recibidos","allowed":1}]}]',
  0, 1, NOW()
);

-- Usuario Administrador
-- Email:    admin@pos.com
-- Password: Admin1234
-- isAdmin=1 → acceso total sin restricción de permisos
INSERT INTO `tbl_users`
  (`userId`, `email`, `password`, `name`, `mobile`, `roleId`, `isAdmin`, `isDeleted`, `createdBy`, `createdDtm`, `id_sucursal`, `color_tema`)
VALUES (
  1,
  'admin@pos.com',
  '$2y$12$AES5VbQ00c/kFzkfgldnI.crC6jFDna41CRVKZn4IQ0w.a47K7Ehq',
  'Administrador',
  NULL,
  1,
  1,       -- isAdmin=1: administrador del sistema
  0,
  1,
  NOW(),
  1,
  '#343a40'
);

-- Cliente genérico (requerido para registrar ventas sin cliente específico)
INSERT INTO `tbl_cliente`
  (`id_cliente`, `nombre`, `correo`, `doc_identidad`, `celular`, `id_sucursal`)
VALUES
  (1, 'Consumidor Final', 'sin@correo.com', '0000000', '00000000', 1);

-- Método de pago básico
INSERT INTO `tbl_metodo_pago`
  (`id_metodo_pago`, `nombre_metodo_pago`, `id_sucursal`)
VALUES
  (1, 'Efectivo', 1);

-- Categoría general de productos
INSERT INTO `tbl_categoria`
  (`id_categoria`, `nombre_categoria`)
VALUES
  (1, 'General');

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- ============================================================
-- FIN — Acceso al sistema:
--   URL: http://localhost/pos_multisucursal
--   Sucursal: Sucursal Principal
--   Email: admin@pos.com
--   Password: Admin1234
-- ============================================================
