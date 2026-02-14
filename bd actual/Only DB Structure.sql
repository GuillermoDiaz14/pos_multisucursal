-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8888
-- Generation Time: Feb 07, 2026 at 06:45 PM
-- Server version: 11.4.9-MariaDB-cll-lve
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_multisucursal`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_access_matrix`
--

CREATE TABLE `tbl_access_matrix` (
  `id` int(11) NOT NULL,
  `access` text DEFAULT NULL,
  `roleId` int(11) NOT NULL,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tbl_access_matrix`
--

INSERT INTO `tbl_access_matrix` (`id`, `access`, `roleId`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, '[{\"module\":\"Carrito\",\"total_access\":1},{\"module\":\"Compra\",\"total_access\":1},{\"module\":\"Gasto\",\"total_access\":1},{\"module\":\"Ingreso\",\"total_access\":1},{\"module\":\"Metodo_pago\",\"total_access\":1},{\"module\":\"Categoria\",\"total_access\":1},{\"module\":\"Producto\",\"total_access\":1},{\"module\":\"Proveedor\",\"total_access\":1},{\"module\":\"Reparacion\",\"total_access\":1},{\"module\":\"Reporte\",\"total_access\":0}]', 12, 0, 1, '2022-06-17 21:01:02', 1, '2023-10-28 02:30:55'),
(2, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":1},{\"module\":\"Gasto\",\"total_access\":1},{\"module\":\"Ingreso\",\"total_access\":1},{\"module\":\"Metodo_pago\",\"total_access\":1},{\"module\":\"Categoria\",\"total_access\":1},{\"module\":\"Producto\",\"total_access\":1},{\"module\":\"Proveedor\",\"total_access\":1},{\"module\":\"Reparacion\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":1}]', 6, 0, 1, '2023-09-18 08:13:11', 1, '2023-11-23 22:56:11'),
(3, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":1},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Reparacion\",\"total_access\":1},{\"module\":\"Reporte\",\"total_access\":0}]', 3, 0, 1, '2023-10-03 02:24:25', 1, '2023-11-23 23:01:05'),
(4, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Compra\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Reparacion\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0}]', 11, 0, 1, '2023-10-28 02:52:59', NULL, NULL),
(5, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Reparacion\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0}]', 5, 0, 1, '2023-11-23 22:56:21', NULL, NULL),
(6, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Reparacion\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0}]', 1, 0, 1, '2023-11-23 23:01:46', NULL, NULL),
(7, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Reparacion\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0}]', 13, 0, 1, '2023-12-17 07:54:30', NULL, NULL),
(8, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Trasladar\",\"total_access\":1},{\"module\":\"Reporte\",\"total_access\":0},{\"module\":\"Reporte_administrador\",\"total_access\":0}]', 14, 0, 1, '2023-12-17 07:58:14', 1, '2024-01-11 08:02:54'),
(9, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Trasladar\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0},{\"module\":\"Reporte_administrador\",\"total_access\":0}]', 15, 0, 1, '2024-01-11 07:16:47', NULL, NULL),
(10, '[{\"module\":\"Carrito\",\"total_access\":1},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":1},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Trasladar\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0},{\"module\":\"Reporte_administrador\",\"total_access\":0}]', 16, 0, 1, '2026-01-24 20:31:53', 19, '2026-01-25 03:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_caja`
--

CREATE TABLE `tbl_caja` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` date NOT NULL,
  `fecha_cierre` date NOT NULL,
  `saldo` float NOT NULL,
  `estado` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`id_categoria`, `nombre_categoria`) VALUES
(6, 'Ropa Hombre'),
(7, 'Ropa Mujer'),
(8, 'Ropa Niño'),
(9, 'Ropa Niña'),
(10, 'Ropa Bebe'),
(11, 'Accesorios Hombre'),
(12, 'Accesorios Mujer'),
(13, 'Zapatos Hombre'),
(14, 'Zapatos Mujer'),
(15, 'Zapatos Niño'),
(16, 'Zapato Niña'),
(17, 'Zapato Bebe'),
(18, 'Accesorios Niño'),
(19, 'Accesorios Niña');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cliente`
--

CREATE TABLE `tbl_cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `doc_identidad` varchar(200) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_cliente`
--

INSERT INTO `tbl_cliente` (`id_cliente`, `nombre`, `correo`, `doc_identidad`, `celular`, `id_sucursal`) VALUES
(1, 'juan obregon', 'obregonasd@gmail.com\r\n', '2423', '', 0),
(2, 'jaime mendoza', 'mendoza12@gmail.com\r\n', '24324', '', 0),
(3, 'Cedrid digori', 'Cedrid@gmail.com\r\n', '3453535', '', 0),
(4, 'Maria Durand', 'Maria123@gmail.com\r\n', '64564645', '', 0),
(5, 'ronaldinio', 'sheldon@gmail.com2342', '234242', '6786868', 0),
(10, 'Cliente general', 'cliente@tultepec.com', '1234567890', '1234567890', 1),
(11, 'Cliente general', 'cliente@lerma.com', 'asdfghjkl', '1234567890', 11),
(12, 'cliente general', 'cliente@lerma.com', 'qwerty', '1234567890', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_compra`
--

CREATE TABLE `tbl_compra` (
  `id_compra` int(11) NOT NULL,
  `fecha_compra` date NOT NULL,
  `proveedor` int(11) NOT NULL,
  `nota` varchar(400) NOT NULL,
  `total` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_configuracion`
--

CREATE TABLE `tbl_configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `nombre_empresa` varchar(200) NOT NULL,
  `telefono` int(20) NOT NULL,
  `impuesto` float NOT NULL,
  `simbolo_moneda` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_configuracion`
--

INSERT INTO `tbl_configuracion` (`id_configuracion`, `nombre_empresa`, `telefono`, `impuesto`, `simbolo_moneda`) VALUES
(1, 'tusolutionweb', 25324242, 18, 'PEN');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cuota`
--

CREATE TABLE `tbl_cuota` (
  `id_cuota` int(11) NOT NULL,
  `cuota` float NOT NULL,
  `fecha_pago` date NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detalle_compra`
--

CREATE TABLE `tbl_detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `cantidad` float NOT NULL,
  `sub_total` float NOT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detalle_traslado`
--

CREATE TABLE `tbl_detalle_traslado` (
  `id_detalle_traslado` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(20) NOT NULL,
  `id_traslado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_detalle_venta`
--

CREATE TABLE `tbl_detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `cantidad` float NOT NULL,
  `sub_total` float NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_empleado`
--

CREATE TABLE `tbl_empleado` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `dni` varchar(200) NOT NULL,
  `celular` varchar(200) NOT NULL,
  `esEliminado` varchar(200) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_empleado`
--

INSERT INTO `tbl_empleado` (`id_empleado`, `nombre`, `dni`, `celular`, `esEliminado`, `id_cat`, `id_sucursal`) VALUES
(7, 'Richard', '243242', '987789789', '', 3, 0),
(8, 'gdfgd', '34543', '4454', '', 0, 0),
(9, 'calixto', '353543', '646546546', '', 0, 0),
(10, 'yamcha', '64564', '9797897', '', 0, 0),
(11, 'empleado1', '3453453', '5354353535', '', 0, 1),
(14, 'Empleado1', 'qwertyu', '1234567890', '', 0, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gasto`
--

CREATE TABLE `tbl_gasto` (
  `id_gasto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ingreso`
--

CREATE TABLE `tbl_ingreso` (
  `id_ingreso` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_last_login`
--

CREATE TABLE `tbl_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_metodo_pago`
--

CREATE TABLE `tbl_metodo_pago` (
  `id_metodo_pago` int(11) NOT NULL,
  `nombre_metodo_pago` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_metodo_pago`
--

INSERT INTO `tbl_metodo_pago` (`id_metodo_pago`, `nombre_metodo_pago`, `id_sucursal`) VALUES
(1, 'tarjeta', 0),
(2, 'yape', 0),
(3, 'efectivo', 1),
(4, 'Efectivo', 11),
(5, 'Transferencia', 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_producto`
--

CREATE TABLE `tbl_producto` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(200) NOT NULL,
  `precio_compra` varchar(200) NOT NULL,
  `precio_venta` varchar(200) NOT NULL,
  `codigo` varchar(200) NOT NULL,
  `categoria` int(11) NOT NULL,
  `imagen` varchar(200) NOT NULL,
  `detalles` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_producto`
--

INSERT INTO `tbl_producto` (`id_producto`, `nombre_producto`, `precio_compra`, `precio_venta`, `codigo`, `categoria`, `imagen`, `detalles`) VALUES
(125, 'Camisa', '100', '150', 'ABC123', 11, '', 'Camisa azul'),
(126, 'Pantalon', '200', '300', 'DEF456', 12, '', 'Pantaln negro');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_producto_stock`
--

CREATE TABLE `tbl_producto_stock` (
  `id_producto_stock` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_producto_stock`
--

INSERT INTO `tbl_producto_stock` (`id_producto_stock`, `id_producto`, `stock`, `id_sucursal`) VALUES
(98, 125, 14, 11),
(99, 126, 15, 11),
(100, 125, 5, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_proveedor`
--

CREATE TABLE `tbl_proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `doc_fiscal` varchar(20) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_proveedor`
--

INSERT INTO `tbl_proveedor` (`id_proveedor`, `nombre`, `email`, `celular`, `direccion`, `doc_fiscal`, `id_sucursal`) VALUES
(1, 'SADISAC', 'SADISAC@gmail.com', '9875656', 'av san ilarion', '2432424', 0),
(2, 'dicosac', 'dicosac@gmail.com', '985455634', 'av. dicosac', '2353453', 0),
(3, 'multibebidas', 'multibebidas@gmail.com', '946564', 'av san martin', '354353', 0),
(5, 'chinderico sac 2', 'chinderico@gmail.com', '95435356456', 'av sn francisco S N', '4243242342', 0),
(7, 'chandra sac', 'chandrasac@gmail.com', '93424242', 'av sn francisco S N', '2342424242424', 1),
(8, 'proveedor1', 'proveedor@lerma.com', '1234567890', 'Lerma', '0987654321', 11),
(9, 'proveedor1', 'proveedor@lerma.com', '1234567890', 'Lerma', '0987654321', 12);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reset_password`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

CREATE TABLE `tbl_roles` (
  `roleId` tinyint(4) NOT NULL COMMENT 'role id',
  `role` varchar(50) NOT NULL COMMENT 'role text',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`roleId`, `role`, `status`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'Administrador', 1, 1, 0, '2021-01-21 00:00:00', 1, '2023-11-23 23:01:58'),
(2, 'Manager', 1, 1, 0, '2021-01-13 00:00:00', NULL, NULL),
(3, 'Empleado', 1, 1, 0, '2021-01-13 00:00:00', 1, '2023-11-23 23:01:34'),
(4, 'Office Boy', 1, 1, 1, '2021-01-22 17:40:24', 1, '2021-01-22 18:33:49'),
(5, 'Recepcionista', 2, 1, 1, '2021-01-22 18:12:41', 1, '2023-11-23 22:56:32'),
(6, 'Administrador', 1, 0, 1, '2021-02-05 18:25:00', 1, '2023-11-23 22:55:41'),
(7, 'Project Manager L2', 1, 1, 1, '2021-02-05 18:27:30', 1, '2021-03-26 14:54:08'),
(8, 'Project Manager L3', 1, 1, 1, '2021-02-05 18:29:11', 1, '2021-03-26 14:54:02'),
(9, 'Project Manager L4', 1, 1, 1, '2021-02-05 18:29:43', 1, '2021-03-26 14:53:51'),
(10, 'Project Manager L5', 1, 1, 1, '2021-02-05 18:56:47', 1, '2021-03-20 19:21:06'),
(11, 'Project Manager L6', 1, 1, 1, '2021-02-05 18:57:23', 1, '2022-06-17 20:56:55'),
(12, 'operador', 1, 1, 1, '2022-06-17 20:57:22', 1, '2023-10-27 18:57:48'),
(13, 'contratado', 1, 1, 1, '2023-12-17 07:54:30', NULL, NULL),
(14, 'salariado', 1, 1, 1, '2023-12-17 07:58:14', NULL, NULL),
(15, 'cas', 1, 1, 1, '2024-01-11 07:16:47', 1, '2024-01-11 07:16:55'),
(16, 'Vendedor', 1, 0, 1, '2026-01-24 20:31:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sucursal`
--

CREATE TABLE `tbl_sucursal` (
  `id_sucursal` int(11) NOT NULL,
  `nombre_sucursal` varchar(200) NOT NULL,
  `impuesto` float NOT NULL,
  `celular` varchar(200) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `ciudad` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `simbolo_moneda` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sucursal`
--

INSERT INTO `tbl_sucursal` (`id_sucursal`, `nombre_sucursal`, `impuesto`, `celular`, `direccion`, `ciudad`, `correo`, `simbolo_moneda`) VALUES
(11, 'Tienda de ropa', 0, '7281447874', 'Vasco de Quiroga', 'Tultepec', '', '$'),
(12, 'Zapateria', 0, '7281447874', 'Vasco de Quiroga', 'Tultepec', '', '$');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_traslado`
--

CREATE TABLE `tbl_traslado` (
  `id_traslado` int(11) NOT NULL,
  `fecha_actual` date NOT NULL,
  `comentario` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal_descuento` int(11) NOT NULL,
  `id_sucursal_aumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userId` int(11) NOT NULL,
  `email` varchar(128) NOT NULL COMMENT 'login email',
  `password` varchar(128) NOT NULL COMMENT 'hashed login password',
  `name` varchar(128) DEFAULT NULL COMMENT 'full name of user',
  `mobile` varchar(20) DEFAULT NULL,
  `roleId` tinyint(4) NOT NULL,
  `isAdmin` tinyint(4) NOT NULL DEFAULT 2,
  `isDeleted` tinyint(4) NOT NULL DEFAULT 0,
  `createdBy` int(11) NOT NULL,
  `createdDtm` datetime NOT NULL,
  `updatedBy` int(11) DEFAULT NULL,
  `updatedDtm` datetime DEFAULT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `email`, `password`, `name`, `mobile`, `roleId`, `isAdmin`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`, `id_sucursal`) VALUES
(1, 'admin@example.com', '$2y$10$6Y28WIo2Oz.p8xsEMYcCmuvvijXZU8.sRT3737ix.vN1CwGs3NJk6', 'System Administrator', '9343333333', 1, 1, 0, 0, '2015-07-01 18:56:49', 1, '2024-01-11 07:53:07', 1),
(19, 'admin.ropa@lerma.com', '$2y$10$0kcktgFM96QZL55GK1qp/eRaBvcWxp4MGY/5NZGGrvFAoIRGa.M9G', 'Administrador Ropa', '1234567890', 6, 1, 0, 1, '2026-01-24 20:31:06', 1, '2026-01-24 23:32:11', 11),
(20, 'xochitl@ropa.com', '$2y$10$y7OWEQwBJuXVWdFY3bBHUeOOA40vm3ixk0jVJleOR6WxupQfmQPQy', 'Xochitl', '1234567890', 16, 2, 0, 1, '2026-01-24 20:34:22', 19, '2026-01-25 03:28:22', 11),
(21, 'xanat@ropa.com', '$2y$10$6U3foOgz/lKiFyvsnhCdNOebUolEYMFto50b9l0U5iYArUnFYboee', 'Xanat', '1234567890', 16, 2, 0, 1, '2026-01-24 20:35:02', 19, '2026-01-25 03:28:31', 11),
(22, 'guillermo@ropa.com', '$2y$10$47cOZCZa9DdwsxrQG.6HB.6Ko7DSOmwk5CpaeVNzwSyTxpNkZjprq', 'Guillermo', '1234567890', 16, 2, 0, 1, '2026-01-24 20:35:55', 19, '2026-01-25 03:27:41', 11),
(23, 'paty@ropa.com', '$2y$10$ZzPutZTM7CbO6G.oMdaYPOWkDwh7NjmL4wex210PZWb/Q7QXhAhKq', 'Paty', '1234567890', 1, 1, 0, 1, '2026-01-24 20:36:47', 19, '2026-01-25 03:27:21', 11),
(24, 'admin@zapateria.com', '$2y$10$eD7fQc6qA.SrZjjQEOE.rOBJljNThwTECo7l5oQRF73daV65VyWqK', 'Administrador Zapateria', '1234567890', 1, 1, 0, 1, '2026-01-24 23:32:58', 19, '2026-01-25 03:26:55', 12),
(25, 'admin@ropa.com', '$2y$10$rDhWl0TnBgwNSKcnRyTtXeyQAZyX9bdCeJkOq40BbgzFxarBRiO6G', 'Administrador Ropa', '1234567890', 1, 1, 0, 19, '2026-01-25 03:29:27', NULL, NULL, 11);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_venta`
--

CREATE TABLE `tbl_venta` (
  `id_venta` int(11) NOT NULL,
  `fecha_venta` date NOT NULL,
  `cliente` int(11) NOT NULL,
  `descuento` float NOT NULL,
  `base_imponible` float NOT NULL,
  `impuesto` float NOT NULL,
  `total` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `tipo_pago` varchar(200) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL,
  `saldo` float NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indexes for table `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_caja`
--
ALTER TABLE `tbl_caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indexes for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indexes for table `tbl_compra`
--
ALTER TABLE `tbl_compra`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indexes for table `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indexes for table `tbl_cuota`
--
ALTER TABLE `tbl_cuota`
  ADD PRIMARY KEY (`id_cuota`);

--
-- Indexes for table `tbl_detalle_compra`
--
ALTER TABLE `tbl_detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`);

--
-- Indexes for table `tbl_detalle_traslado`
--
ALTER TABLE `tbl_detalle_traslado`
  ADD PRIMARY KEY (`id_detalle_traslado`);

--
-- Indexes for table `tbl_detalle_venta`
--
ALTER TABLE `tbl_detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`);

--
-- Indexes for table `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indexes for table `tbl_gasto`
--
ALTER TABLE `tbl_gasto`
  ADD PRIMARY KEY (`id_gasto`);

--
-- Indexes for table `tbl_ingreso`
--
ALTER TABLE `tbl_ingreso`
  ADD PRIMARY KEY (`id_ingreso`);

--
-- Indexes for table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_metodo_pago`
--
ALTER TABLE `tbl_metodo_pago`
  ADD PRIMARY KEY (`id_metodo_pago`);

--
-- Indexes for table `tbl_producto`
--
ALTER TABLE `tbl_producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indexes for table `tbl_producto_stock`
--
ALTER TABLE `tbl_producto_stock`
  ADD PRIMARY KEY (`id_producto_stock`);

--
-- Indexes for table `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indexes for table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indexes for table `tbl_sucursal`
--
ALTER TABLE `tbl_sucursal`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- Indexes for table `tbl_traslado`
--
ALTER TABLE `tbl_traslado`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `tbl_venta`
--
ALTER TABLE `tbl_venta`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_caja`
--
ALTER TABLE `tbl_caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_compra`
--
ALTER TABLE `tbl_compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_cuota`
--
ALTER TABLE `tbl_cuota`
  MODIFY `id_cuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_detalle_compra`
--
ALTER TABLE `tbl_detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_detalle_traslado`
--
ALTER TABLE `tbl_detalle_traslado`
  MODIFY `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_detalle_venta`
--
ALTER TABLE `tbl_detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_gasto`
--
ALTER TABLE `tbl_gasto`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_ingreso`
--
ALTER TABLE `tbl_ingreso`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_metodo_pago`
--
ALTER TABLE `tbl_metodo_pago`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_producto`
--
ALTER TABLE `tbl_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `tbl_producto_stock`
--
ALTER TABLE `tbl_producto_stock`
  MODIFY `id_producto_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `roleId` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'role id', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_sucursal`
--
ALTER TABLE `tbl_sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_traslado`
--
ALTER TABLE `tbl_traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tbl_venta`
--
ALTER TABLE `tbl_venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;