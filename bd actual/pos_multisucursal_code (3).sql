-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-01-2024 a las 00:51:49
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pos_multisucursal_code`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_access_matrix`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_access_matrix`
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
(9, '[{\"module\":\"Carrito\",\"total_access\":0},{\"module\":\"Entrada\",\"total_access\":0},{\"module\":\"Gasto\",\"total_access\":0},{\"module\":\"Ingreso\",\"total_access\":0},{\"module\":\"Metodo_pago\",\"total_access\":0},{\"module\":\"Categoria\",\"total_access\":0},{\"module\":\"Producto\",\"total_access\":0},{\"module\":\"Proveedor\",\"total_access\":0},{\"module\":\"Trasladar\",\"total_access\":0},{\"module\":\"Reporte\",\"total_access\":0},{\"module\":\"Reporte_administrador\",\"total_access\":0}]', 15, 0, 1, '2024-01-11 07:16:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_caja`
--

CREATE TABLE `tbl_caja` (
  `id_caja` int(11) NOT NULL,
  `fecha_apertura` date NOT NULL,
  `fecha_cierre` date NOT NULL,
  `saldo` float NOT NULL,
  `estado` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_caja`
--

INSERT INTO `tbl_caja` (`id_caja`, `fecha_apertura`, `fecha_cierre`, `saldo`, `estado`, `id_sucursal`) VALUES
(1, '2023-10-07', '0000-00-00', 66.44, 'cerrado', 0),
(2, '2023-10-07', '0000-00-00', 967.46, 'cerrado', 0),
(3, '2023-10-19', '0000-00-00', 44, 'cerrado', 0),
(4, '2023-10-20', '0000-00-00', 22, 'cerrado', 0),
(5, '2023-10-20', '0000-00-00', 568.2, 'cerrado', 0),
(6, '2023-10-27', '0000-00-00', 477.2, 'cerrado', 0),
(7, '2023-11-13', '0000-00-00', -517.6, 'cerrado', 0),
(8, '2023-11-21', '0000-00-00', 262.8, 'cerrado', 0),
(9, '2023-11-21', '0000-00-00', 55, 'cerrado', 0),
(10, '2023-11-21', '0000-00-00', 363.2, 'cerrado', 0),
(11, '2023-12-24', '0000-00-00', 31.1, 'cerrado', 1),
(12, '2023-12-25', '0000-00-00', 15.5, 'cerrado', 1),
(13, '2024-01-09', '0000-00-00', 30, 'abierto', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`id_categoria`, `nombre_categoria`) VALUES
(2, 'bebidas'),
(3, 'rones'),
(4, 'otros'),
(5, 'wiskis');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cliente`
--

CREATE TABLE `tbl_cliente` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `doc_identidad` varchar(200) NOT NULL,
  `celular` varchar(100) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_cliente`
--

INSERT INTO `tbl_cliente` (`id_cliente`, `nombre`, `correo`, `doc_identidad`, `celular`, `id_sucursal`) VALUES
(1, 'juan obregon', 'obregonasd@gmail.com\r\n', '2423', '', 0),
(2, 'jaime mendoza', 'mendoza12@gmail.com\r\n', '24324', '', 0),
(3, 'Cedrid digori', 'Cedrid@gmail.com\r\n', '3453535', '', 0),
(4, 'Maria Durand', 'Maria123@gmail.com\r\n', '64564645', '', 0),
(5, 'ronaldinio', 'sheldon@gmail.com2342', '234242', '6786868', 0),
(7, 'dumbo', 'dumbo@gmail.com', '25423453', '97456545645', 1),
(8, 'hans', 'ham1s@gmail.com', '45677898', '92934329349', 1),
(9, 'nicky lauda', 'nickylauda@gmail.com', '35456576', '9234242', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_compra`
--

CREATE TABLE `tbl_compra` (
  `id_compra` int(11) NOT NULL,
  `fecha_compra` date NOT NULL,
  `proveedor` int(11) NOT NULL,
  `nota` varchar(400) NOT NULL,
  `total` float NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_compra`
--

INSERT INTO `tbl_compra` (`id_compra`, `fecha_compra`, `proveedor`, `nota`, `total`, `id_usuario`, `id_sucursal`) VALUES
(1, '2023-12-26', 7, 'g', 640, 1, 1),
(2, '2023-12-29', 7, '', 450, 1, 1),
(3, '2023-12-30', 7, '', 524, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_configuracion`
--

CREATE TABLE `tbl_configuracion` (
  `id_configuracion` int(11) NOT NULL,
  `nombre_empresa` varchar(200) NOT NULL,
  `telefono` int(20) NOT NULL,
  `impuesto` float NOT NULL,
  `simbolo_moneda` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_configuracion`
--

INSERT INTO `tbl_configuracion` (`id_configuracion`, `nombre_empresa`, `telefono`, `impuesto`, `simbolo_moneda`) VALUES
(1, 'tusolutionweb', 25324242, 18, 'PEN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cuota`
--

CREATE TABLE `tbl_cuota` (
  `id_cuota` int(11) NOT NULL,
  `cuota` float NOT NULL,
  `fecha_pago` date NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_cuota`
--

INSERT INTO `tbl_cuota` (`id_cuota`, `cuota`, `fecha_pago`, `id_venta`) VALUES
(1, 10, '2024-01-09', 3),
(2, 15, '2024-01-09', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalle_compra`
--

CREATE TABLE `tbl_detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `cantidad` float NOT NULL,
  `sub_total` float NOT NULL,
  `id_compra` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_detalle_compra`
--

INSERT INTO `tbl_detalle_compra` (`id_detalle_compra`, `id_producto`, `precio_compra`, `cantidad`, `sub_total`, `id_compra`) VALUES
(1, 2, 1, 400, 400, 1),
(2, 3, 0.8, 300, 240, 1),
(3, 4, 45, 10, 450, 2),
(4, 1, 0.8, 30, 24, 3),
(5, 3, 20, 25, 500, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalle_traslado`
--

CREATE TABLE `tbl_detalle_traslado` (
  `id_detalle_traslado` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(20) NOT NULL,
  `id_traslado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_detalle_traslado`
--

INSERT INTO `tbl_detalle_traslado` (`id_detalle_traslado`, `id_producto`, `cantidad`, `id_traslado`) VALUES
(1, 1, 3, 1),
(2, 3, 2, 1),
(3, 3, 5, 2),
(4, 1, 6, 2),
(5, 1, 4, 3),
(6, 3, 5, 3),
(7, 1, 2, 4),
(8, 3, 1, 4),
(9, 1, 2, 7),
(10, 1, 3, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_detalle_venta`
--

CREATE TABLE `tbl_detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_venta` float NOT NULL,
  `cantidad` float NOT NULL,
  `sub_total` float NOT NULL,
  `id_venta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_detalle_venta`
--

INSERT INTO `tbl_detalle_venta` (`id_detalle_venta`, `id_producto`, `precio_venta`, `cantidad`, `sub_total`, `id_venta`) VALUES
(1, 2, 1.5, 3, 4.5, 1),
(2, 3, 3, 2, 6, 1),
(4, 3, 25, 1, 25, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_empleado`
--

CREATE TABLE `tbl_empleado` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `dni` varchar(200) NOT NULL,
  `celular` varchar(200) NOT NULL,
  `esEliminado` varchar(200) NOT NULL,
  `id_cat` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_empleado`
--

INSERT INTO `tbl_empleado` (`id_empleado`, `nombre`, `dni`, `celular`, `esEliminado`, `id_cat`, `id_sucursal`) VALUES
(7, 'Richard', '243242', '987789789', '', 3, 0),
(8, 'gdfgd', '34543', '4454', '', 0, 0),
(9, 'calixto', '353543', '646546546', '', 0, 0),
(10, 'yamcha', '64564', '9797897', '', 0, 0),
(11, 'genkis klan', '3453453', '5354353535', '', 0, 1),
(12, 'jhon titor', '53534', '94234242', '', 0, 1),
(13, 'matias', '3453535', '923424234', '', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_gasto`
--

CREATE TABLE `tbl_gasto` (
  `id_gasto` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_gasto`
--

INSERT INTO `tbl_gasto` (`id_gasto`, `descripcion`, `monto`, `fecha`, `id_sucursal`) VALUES
(1, 'viaticos', 65.33, '2023-10-05', 0),
(3, 'dgfdgdfg', 2.2, '2023-10-13', 0),
(4, 'gdf', 4, '2023-10-13', 0),
(5, 'dgdf', 444, '2023-10-18', 0),
(6, 'fds', 3534, '2023-10-11', 0),
(7, 'dgdfg', 444, '2023-10-12', 0),
(8, 'gdfgfd', 554, '2023-10-19', 0),
(9, 'sdfdf', 3334, '2023-10-26', 0),
(10, 'dasda', 232323, '2023-10-12', 0),
(11, 'sdfsd', 233, '2023-10-11', 0),
(12, '', 343, '2023-10-18', 0),
(13, 'coinmarket_cap.jpg', 3232, '2023-10-18', 0),
(14, 'viaticos', 33, '2023-10-27', 0),
(15, 'ff', 11, '2023-12-23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_ingreso`
--

CREATE TABLE `tbl_ingreso` (
  `id_ingreso` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` float NOT NULL,
  `fecha` date NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_ingreso`
--

INSERT INTO `tbl_ingreso` (`id_ingreso`, `descripcion`, `monto`, `fecha`, `id_sucursal`) VALUES
(1, 'camaroman', 5545, '2023-10-05', 0),
(3, 'cobro prestamo', 1000, '2023-12-23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_last_login`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_last_login`
--

INSERT INTO `tbl_last_login` (`id`, `userId`, `sessionData`, `machineIp`, `userAgent`, `agentString`, `platform`, `createdDtm`) VALUES
(1, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 99.0.4844.84', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36', 'Windows 7', '2022-04-04 22:19:07'),
(2, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 01:33:45'),
(3, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 01:35:50'),
(4, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 01:36:25'),
(5, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:06:57'),
(6, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:08:21'),
(7, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:16:40'),
(8, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:17:26'),
(9, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:30:21'),
(10, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 02:30:39'),
(11, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-17 23:49:29'),
(12, 14, '{\"role\":\"11\",\"roleText\":\"Project Manager L6\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 01:41:39'),
(13, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 01:42:38'),
(14, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 01:51:18'),
(15, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 01:54:04'),
(16, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 02:15:01'),
(17, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 23:52:14'),
(18, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 23:53:41'),
(19, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 23:55:24'),
(20, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-18 23:57:25'),
(21, 14, '{\"role\":\"12\",\"roleText\":\"Data Entry Operator\",\"name\":\"Pml6\",\"isAdmin\":\"2\"}', '::1', 'Chrome 102.0.0.0', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36', 'Windows 7', '2022-06-19 00:21:13'),
(22, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-17 23:32:50'),
(23, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-18 16:09:56'),
(24, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-18 17:39:53'),
(25, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-20 02:05:18'),
(26, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-21 18:42:29'),
(27, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-21 18:49:36'),
(28, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-21 21:37:04'),
(29, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 116.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', 'Windows 10', '2023-09-22 23:08:10'),
(30, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 02:39:14'),
(31, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 02:45:47'),
(32, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 02:47:04'),
(33, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 03:45:01'),
(34, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 09:34:29'),
(35, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 10:27:20'),
(36, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 19:48:54'),
(37, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 22:27:48'),
(38, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-24 23:26:31'),
(39, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-25 15:52:33'),
(40, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-27 03:43:23'),
(41, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-27 11:00:25'),
(42, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-27 15:58:01'),
(43, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-27 16:05:18'),
(44, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-27 17:56:12'),
(45, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 03:26:33'),
(46, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 04:33:24'),
(47, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 04:57:12'),
(48, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 10:41:37'),
(49, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 13:41:48'),
(50, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 15:19:28'),
(51, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-09-29 18:15:05'),
(52, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-01 10:32:37'),
(53, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-01 10:37:59'),
(54, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-01 18:23:54'),
(55, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-02 02:15:55'),
(56, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-02 14:06:54'),
(57, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-02 19:23:42'),
(58, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-04 18:55:40'),
(59, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-05 00:59:15'),
(60, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-05 17:47:13'),
(61, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-05 23:37:56'),
(62, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-06 15:13:55'),
(63, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-07 01:17:59'),
(64, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-07 02:52:50'),
(65, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-07 19:02:22'),
(66, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-07 20:12:48'),
(67, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-07 20:49:12'),
(68, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 00:42:47'),
(69, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 08:16:48'),
(70, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 08:44:35'),
(71, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 08:45:03'),
(72, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 08:46:31'),
(73, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 08:53:38'),
(74, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 11:42:18'),
(75, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 17:20:13'),
(76, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-08 22:39:21'),
(77, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-09 13:50:34'),
(78, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-09 22:36:02'),
(79, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 117.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36', 'Windows 10', '2023-10-10 10:47:35'),
(80, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '127.0.0.1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-12 04:02:25'),
(81, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-14 02:25:03'),
(82, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-16 03:02:59'),
(83, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-16 13:51:18'),
(84, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-18 00:57:59'),
(85, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-18 14:22:50'),
(86, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-18 14:35:56'),
(87, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-18 16:42:01'),
(88, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-19 12:45:23'),
(89, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-19 18:16:21'),
(90, 15, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"Harry\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-19 18:16:49'),
(91, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-20 14:21:34'),
(92, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-20 14:25:54'),
(93, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-21 15:26:51'),
(94, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-21 15:57:04'),
(95, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-22 02:04:04'),
(96, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-22 18:06:28'),
(97, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '127.0.0.1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-23 17:30:51'),
(98, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-24 16:12:12'),
(99, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-24 16:59:56'),
(100, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-24 19:10:50'),
(101, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-26 11:14:42'),
(102, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-26 19:06:00'),
(103, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 11:15:04'),
(104, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 11:58:05'),
(105, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 11:59:06'),
(106, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 11:59:14'),
(107, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 11:59:44'),
(108, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:00:11'),
(109, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:00:30'),
(110, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:00:41'),
(111, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:01:01'),
(112, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:01:18'),
(113, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:01:46'),
(114, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:02:14'),
(115, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 12:02:34'),
(116, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 15:56:33'),
(117, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:24:27'),
(118, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:25:13'),
(119, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:30:58'),
(120, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:35:49'),
(121, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:36:10'),
(122, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:54:23'),
(123, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:54:59'),
(124, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:55:04'),
(125, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:55:24'),
(126, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 16:57:22'),
(127, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:07:09'),
(128, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:08:21'),
(129, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Dd\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:09:26'),
(130, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:15:18'),
(131, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Tusbasa\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:16:31'),
(132, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 17:17:17'),
(133, 14, '{\"role\":\"12\",\"roleText\":\"operador\",\"name\":\"Operador Tusbasa\",\"isAdmin\":\"2\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 19:31:08'),
(134, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 118.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36', 'Windows 10', '2023-10-27 19:31:22'),
(135, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-05 11:30:00'),
(136, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-05 15:47:19'),
(137, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-07 17:17:17'),
(138, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-07 17:39:21'),
(139, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-08 01:06:26'),
(140, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-08 15:58:10'),
(141, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-09 13:21:33'),
(142, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-10 19:50:00'),
(143, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '127.0.0.1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-12 01:05:47'),
(144, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-12 11:17:06'),
(145, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-12 17:35:32'),
(146, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-12 22:17:48'),
(147, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-13 11:12:04'),
(148, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-14 14:29:45'),
(149, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-14 19:15:31'),
(150, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-17 12:56:02'),
(151, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-17 13:01:22'),
(152, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-18 01:17:38'),
(153, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-18 09:41:50'),
(154, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-18 15:32:14'),
(155, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-18 15:32:14'),
(156, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-18 20:00:14'),
(157, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-19 03:37:51'),
(158, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-19 17:11:05'),
(159, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-19 22:44:17'),
(160, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-20 10:14:20'),
(161, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-20 20:44:05'),
(162, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-21 11:17:23'),
(163, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-21 19:08:23'),
(164, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-23 13:50:48'),
(165, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-23 16:32:47'),
(166, 1, '{\"role\":\"1\",\"roleText\":\"System Administrator\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-23 16:54:33'),
(167, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-23 21:59:25'),
(168, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-24 09:13:17'),
(169, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 119.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36', 'Windows 10', '2023-11-24 12:39:09'),
(170, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-14 16:28:25');
INSERT INTO `tbl_last_login` (`id`, `userId`, `sessionData`, `machineIp`, `userAgent`, `agentString`, `platform`, `createdDtm`) VALUES
(171, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-15 11:06:57'),
(172, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-15 11:15:16'),
(173, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', 'Android', '2023-12-16 12:14:19'),
(174, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-17 00:14:08'),
(175, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-17 10:13:27'),
(176, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-18 00:44:54'),
(177, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-18 02:58:10'),
(178, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-20 11:00:56'),
(179, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-21 16:02:25'),
(180, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-23 17:14:47'),
(181, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-24 00:16:33'),
(182, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-25 01:25:59'),
(183, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '127.0.0.1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-25 09:22:01'),
(184, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-25 14:07:08'),
(185, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-25 22:46:53'),
(186, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-26 01:38:13'),
(187, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-29 00:44:20'),
(188, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-29 23:44:43'),
(189, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-30 08:29:46'),
(190, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2023-12-30 19:03:14'),
(191, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Mobile Safari/537.36', 'Android', '2023-12-30 21:43:00'),
(192, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-02 00:39:11'),
(193, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-02 22:59:02'),
(194, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-03 15:15:53'),
(195, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-04 10:33:46'),
(196, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-05 11:50:29'),
(197, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-08 22:49:08'),
(198, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-09 09:17:53'),
(199, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-10 11:53:46'),
(200, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-10 13:58:13'),
(201, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-10 20:18:39'),
(202, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-10 20:20:09'),
(203, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 00:58:01'),
(204, 17, '{\"role\":\"14\",\"roleText\":\"salariado\",\"name\":\"Chichanito\",\"isAdmin\":\"2\",\"id_sucursal\":\"9\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 02:02:13'),
(205, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 02:02:21'),
(206, 17, '{\"role\":\"14\",\"roleText\":\"salariado\",\"name\":\"Chichanito\",\"isAdmin\":\"2\",\"id_sucursal\":\"9\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 02:03:12'),
(207, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 02:03:54'),
(208, 1, '{\"role\":\"1\",\"roleText\":\"Administrador\",\"name\":\"System Administrator\",\"isAdmin\":\"1\",\"id_sucursal\":\"1\"}', '::1', 'Chrome 120.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'Windows 10', '2024-01-11 18:19:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_metodo_pago`
--

CREATE TABLE `tbl_metodo_pago` (
  `id_metodo_pago` int(11) NOT NULL,
  `nombre_metodo_pago` varchar(200) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_metodo_pago`
--

INSERT INTO `tbl_metodo_pago` (`id_metodo_pago`, `nombre_metodo_pago`, `id_sucursal`) VALUES
(1, 'tarjeta', 0),
(2, 'yape', 0),
(3, 'efectivo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_producto`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_producto`
--

INSERT INTO `tbl_producto` (`id_producto`, `nombre_producto`, `precio_compra`, `precio_venta`, `codigo`, `categoria`, `imagen`, `detalles`) VALUES
(1, 'AGUA CIELO', '0.80', '1', '042334', 2, 'agua-cielo-2l5.png', 'agua'),
(3, 'old times 750ml', '18', '25', 'fg3453', 5, 'old-times-rojo-102x300.png', 'fdgd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_producto_stock`
--

CREATE TABLE `tbl_producto_stock` (
  `id_producto_stock` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_producto_stock`
--

INSERT INTO `tbl_producto_stock` (`id_producto_stock`, `id_producto`, `stock`, `id_sucursal`) VALUES
(1, 1, 16, 1),
(2, 1, 8, 9),
(7, 3, 11, 1),
(8, 3, 9, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedor`
--

CREATE TABLE `tbl_proveedor` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `celular` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `doc_fiscal` varchar(20) NOT NULL,
  `id_sucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_proveedor`
--

INSERT INTO `tbl_proveedor` (`id_proveedor`, `nombre`, `email`, `celular`, `direccion`, `doc_fiscal`, `id_sucursal`) VALUES
(1, 'SADISAC', 'SADISAC@gmail.com', '9875656', 'av san ilarion', '2432424', 0),
(2, 'dicosac', 'dicosac@gmail.com', '985455634', 'av. dicosac', '2353453', 0),
(3, 'multibebidas', 'multibebidas@gmail.com', '946564', 'av san martin', '354353', 0),
(5, 'chinderico sac 2', 'chinderico@gmail.com', '95435356456', 'av sn francisco S N', '4243242342', 0),
(7, 'chandra sac', 'chandrasac@gmail.com', '93424242', 'av sn francisco S N', '2342424242424', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_reset_password`
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_reset_password`
--

INSERT INTO `tbl_reset_password` (`id`, `email`, `activation_id`, `agent`, `client_ip`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'admin@example.com', 'lmXpaPbvro4Q6ZU', 'Chrome 119.0.0.0', '::1', 0, 1, '2023-11-25 00:54:06', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_roles`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_roles`
--

INSERT INTO `tbl_roles` (`roleId`, `role`, `status`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`) VALUES
(1, 'Administrador', 1, 0, 0, '2021-01-21 00:00:00', 1, '2023-11-23 23:01:58'),
(2, 'Manager', 1, 1, 0, '2021-01-13 00:00:00', NULL, NULL),
(3, 'Empleado', 1, 0, 0, '2021-01-13 00:00:00', 1, '2023-11-23 23:01:34'),
(4, 'Office Boy', 1, 1, 1, '2021-01-22 17:40:24', 1, '2021-01-22 18:33:49'),
(5, 'Recepcionista', 2, 0, 1, '2021-01-22 18:12:41', 1, '2023-11-23 22:56:32'),
(6, 'Administrador', 1, 0, 1, '2021-02-05 18:25:00', 1, '2023-11-23 22:55:41'),
(7, 'Project Manager L2', 1, 1, 1, '2021-02-05 18:27:30', 1, '2021-03-26 14:54:08'),
(8, 'Project Manager L3', 1, 1, 1, '2021-02-05 18:29:11', 1, '2021-03-26 14:54:02'),
(9, 'Project Manager L4', 1, 1, 1, '2021-02-05 18:29:43', 1, '2021-03-26 14:53:51'),
(10, 'Project Manager L5', 1, 1, 1, '2021-02-05 18:56:47', 1, '2021-03-20 19:21:06'),
(11, 'Project Manager L6', 1, 1, 1, '2021-02-05 18:57:23', 1, '2022-06-17 20:56:55'),
(12, 'operador', 1, 0, 1, '2022-06-17 20:57:22', 1, '2023-10-27 18:57:48'),
(13, 'contratado', 1, 0, 1, '2023-12-17 07:54:30', NULL, NULL),
(14, 'salariado', 1, 0, 1, '2023-12-17 07:58:14', NULL, NULL),
(15, 'cas', 1, 0, 1, '2024-01-11 07:16:47', 1, '2024-01-11 07:16:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_sucursal`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_sucursal`
--

INSERT INTO `tbl_sucursal` (`id_sucursal`, `nombre_sucursal`, `impuesto`, `celular`, `direccion`, `ciudad`, `correo`, `simbolo_moneda`) VALUES
(1, 'sucursal mendoza', 0, '', '', '', '', 'PEN'),
(9, 'sjl market', 18, '946464564', 'asad', 'trujillo', '', 'MXN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_traslado`
--

CREATE TABLE `tbl_traslado` (
  `id_traslado` int(11) NOT NULL,
  `fecha_actual` date NOT NULL,
  `comentario` varchar(200) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_sucursal_descuento` int(11) NOT NULL,
  `id_sucursal_aumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_traslado`
--

INSERT INTO `tbl_traslado` (`id_traslado`, `fecha_actual`, `comentario`, `id_usuario`, `id_sucursal_descuento`, `id_sucursal_aumento`) VALUES
(1, '2023-12-31', '', 1, 1, 9),
(2, '2023-12-31', '', 1, 1, 9),
(3, '2024-01-02', '', 1, 1, 9),
(4, '2024-01-02', '', 1, 1, 9),
(5, '2024-01-09', '', 1, 1, 9),
(6, '2024-01-09', '', 1, 1, 9),
(7, '2024-01-09', '', 1, 1, 9),
(8, '2024-01-11', '', 17, 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_users`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tbl_users`
--

INSERT INTO `tbl_users` (`userId`, `email`, `password`, `name`, `mobile`, `roleId`, `isAdmin`, `isDeleted`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`, `id_sucursal`) VALUES
(1, 'admin@example.com', '$2y$10$6Y28WIo2Oz.p8xsEMYcCmuvvijXZU8.sRT3737ix.vN1CwGs3NJk6', 'System Administrator', '9343333333', 1, 1, 0, 0, '2015-07-01 18:56:49', 1, '2024-01-11 07:53:07', 1),
(2, 'manager@example.com', '$2y$10$quODe6vkNma30rcxbAHbYuKYAZQqUaflBgc4YpV9/90ywd.5Koklm', 'Manager', '9890098900', 2, 0, 0, 1, '2016-12-09 17:49:56', 1, '2020-02-01 19:36:12', 0),
(3, 'employee@example.com', '$2y$10$UYsH1G7MkDg1cutOdgl2Q.ZbXjyX.CSjsdgQKvGzAgl60RXZxpB5u', 'Employee', '9890098900', 3, 0, 1, 1, '2016-12-09 17:50:22', 1, '2019-11-09 18:13:17', 0),
(9, 'employee2@example.com', '$2y$10$DBnqnZDQMNW3GASPNJQ2RubfqG1WNupMBP4HKwHYRKQNHgA65Nhly', 'Employee2', '9890098900', 3, 0, 1, 1, '2019-03-26 11:40:50', 1, '2019-11-09 18:12:13', 0),
(10, 'employee@example.com', '$2y$10$DcK/YcVNOP/CxfGbcEDH1OzR8D4KyG1lpe/XgRGfijj158Ru0kKN.', 'Employee', '7410852000', 3, 2, 0, 1, '2020-02-01 19:36:41', 1, '2022-04-01 19:27:27', 0),
(12, 'email@example.com', '$2y$10$CGJsY/FZMn8L4.JfO.ZojOwbK9RUCQSx4dnqU1NgkO3ffq26i0WDG', 'From', '8520741000', 3, 0, 0, 1, '2021-01-15 13:42:11', 1, '2021-02-05 17:25:44', 0),
(14, 'operador@gmail.com', '$2y$10$xcYyIQS9pz4c2BJSK6zDfuDognv2.04.gw.odwpIKEci175pdV1w6', 'Operador Tusbasa', '9456464643', 12, 2, 0, 1, '2022-06-16 22:05:15', 14, '2023-10-28 00:10:08', 0),
(15, 'harry@domain.com', '$2y$10$YyMPGh5yEl9xUh7lZAYWQe6U7hxpi.vAZvgeDwlBUD0yHfnLk0VDi', 'Harry', '7657575654', 1, 1, 0, 1, '2023-10-20 01:16:00', NULL, NULL, 0),
(16, 'user2@example.com', '$2y$10$cP.o1fq8KUNu32TQu6X6D.G1XzF400P59Uqr2iwOs9.C6M9jUT6Iu', 'User2', '9845645654', 3, 2, 0, 1, '2023-12-17 07:50:11', NULL, NULL, 1),
(17, 'chichanito@gmail.com', '$2y$10$gFxSKKx3j9FpGp28c1XU8.leSo9zc4O14Cm4NPSgeoo42sWAMLowa', 'Chichanito', '9776567567', 14, 2, 0, 1, '2024-01-11 07:00:23', 1, '2024-01-11 07:51:23', 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_venta`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `tbl_venta`
--

INSERT INTO `tbl_venta` (`id_venta`, `fecha_venta`, `cliente`, `descuento`, `base_imponible`, `impuesto`, `total`, `id_usuario`, `tipo_pago`, `id_metodo_pago`, `saldo`, `id_sucursal`) VALUES
(1, '2023-12-26', 9, 0, 8.86, 1.64, 10.5, 1, 'contado', 3, 0, 1),
(3, '2024-01-09', 7, 0, 21.19, 3.81, 25, 1, 'credito', 0, 25, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indices de la tabla `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_caja`
--
ALTER TABLE `tbl_caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `tbl_compra`
--
ALTER TABLE `tbl_compra`
  ADD PRIMARY KEY (`id_compra`);

--
-- Indices de la tabla `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  ADD PRIMARY KEY (`id_configuracion`);

--
-- Indices de la tabla `tbl_cuota`
--
ALTER TABLE `tbl_cuota`
  ADD PRIMARY KEY (`id_cuota`);

--
-- Indices de la tabla `tbl_detalle_compra`
--
ALTER TABLE `tbl_detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`);

--
-- Indices de la tabla `tbl_detalle_traslado`
--
ALTER TABLE `tbl_detalle_traslado`
  ADD PRIMARY KEY (`id_detalle_traslado`);

--
-- Indices de la tabla `tbl_detalle_venta`
--
ALTER TABLE `tbl_detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`);

--
-- Indices de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  ADD PRIMARY KEY (`id_empleado`);

--
-- Indices de la tabla `tbl_gasto`
--
ALTER TABLE `tbl_gasto`
  ADD PRIMARY KEY (`id_gasto`);

--
-- Indices de la tabla `tbl_ingreso`
--
ALTER TABLE `tbl_ingreso`
  ADD PRIMARY KEY (`id_ingreso`);

--
-- Indices de la tabla `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_metodo_pago`
--
ALTER TABLE `tbl_metodo_pago`
  ADD PRIMARY KEY (`id_metodo_pago`);

--
-- Indices de la tabla `tbl_producto`
--
ALTER TABLE `tbl_producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `tbl_producto_stock`
--
ALTER TABLE `tbl_producto_stock`
  ADD PRIMARY KEY (`id_producto_stock`);

--
-- Indices de la tabla `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD PRIMARY KEY (`roleId`);

--
-- Indices de la tabla `tbl_sucursal`
--
ALTER TABLE `tbl_sucursal`
  ADD PRIMARY KEY (`id_sucursal`);

--
-- Indices de la tabla `tbl_traslado`
--
ALTER TABLE `tbl_traslado`
  ADD PRIMARY KEY (`id_traslado`);

--
-- Indices de la tabla `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userId`);

--
-- Indices de la tabla `tbl_venta`
--
ALTER TABLE `tbl_venta`
  ADD PRIMARY KEY (`id_venta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_access_matrix`
--
ALTER TABLE `tbl_access_matrix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_caja`
--
ALTER TABLE `tbl_caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_compra`
--
ALTER TABLE `tbl_compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  MODIFY `id_configuracion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_cuota`
--
ALTER TABLE `tbl_cuota`
  MODIFY `id_cuota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_detalle_compra`
--
ALTER TABLE `tbl_detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_detalle_traslado`
--
ALTER TABLE `tbl_detalle_traslado`
  MODIFY `id_detalle_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_detalle_venta`
--
ALTER TABLE `tbl_detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `tbl_gasto`
--
ALTER TABLE `tbl_gasto`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tbl_ingreso`
--
ALTER TABLE `tbl_ingreso`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_last_login`
--
ALTER TABLE `tbl_last_login`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT de la tabla `tbl_metodo_pago`
--
ALTER TABLE `tbl_metodo_pago`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_producto`
--
ALTER TABLE `tbl_producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_producto_stock`
--
ALTER TABLE `tbl_producto_stock`
  MODIFY `id_producto_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbl_reset_password`
--
ALTER TABLE `tbl_reset_password`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_roles`
--
ALTER TABLE `tbl_roles`
  MODIFY `roleId` tinyint(4) NOT NULL AUTO_INCREMENT COMMENT 'role id', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tbl_sucursal`
--
ALTER TABLE `tbl_sucursal`
  MODIFY `id_sucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tbl_traslado`
--
ALTER TABLE `tbl_traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `tbl_venta`
--
ALTER TABLE `tbl_venta`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
