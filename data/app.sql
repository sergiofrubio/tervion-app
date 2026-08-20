-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 18-06-2026 a las 08:18:04
-- Versión del servidor: 8.0.44
-- Versión de PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencias_terapeutas`
--

CREATE TABLE `ausencias_terapeutas` (
  `ausencia_id` int NOT NULL,
  `fisioterapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos`
--

CREATE TABLE `bonos` (
  `bono_id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `numero_sesiones` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Activo',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `bonos`
--

INSERT INTO `bonos` (`bono_id`, `nombre`, `numero_sesiones`, `precio`, `estado`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 'Sesión individual', 1, 30.00, 'Activo', NULL, '2026-05-10 07:59:45', NULL, NULL),
(2, 'Bono 10 sesiones', 10, 280.00, 'Activo', NULL, '2026-05-10 08:16:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos_pacientes`
--

CREATE TABLE `bonos_pacientes` (
  `bono_paciente_id` int NOT NULL,
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bono_id` int NOT NULL,
  `sesiones_restantes` int NOT NULL,
  `fecha_compra` date NOT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `cita_id` int NOT NULL,
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fisioterapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('Programada','Cancelada','Realizada','Pendiente','Confirmada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bono_paciente_id` int DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `token_confirmacion` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`cita_id`, `paciente_id`, `fisioterapeuta_id`, `fecha_hora`, `estado`, `bono_paciente_id`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, '123456789', '234567890', '2024-01-15 10:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, NULL),
(2, '123456789', '234567890', '2024-02-20 11:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, NULL),
(3, '123456789', '234567890', '2024-03-25 09:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, NULL),
(4, '123456789', '234567890', '2024-04-20 10:00:00', 'Programada', NULL, NULL, '2026-05-04 17:37:34', NULL, NULL),
(5, '234567890', '345678901', '2024-05-05 14:00:00', 'Cancelada', NULL, NULL, '2026-05-04 17:37:34', NULL, NULL),
(8, '123456789', '234567890', '2026-05-12 08:42:00', 'Programada', NULL, NULL, '2026-05-10 08:42:05', NULL, NULL),
(9, '123456789', '234567890', '2026-05-12 08:42:00', 'Programada', NULL, NULL, '2026-05-10 08:42:29', NULL, NULL),
(10, '123456789', '234567890', '2026-05-12 08:45:00', 'Programada', NULL, NULL, '2026-05-10 08:45:47', NULL, NULL),
(11, '123456789', '234567890', '2026-05-12 08:47:00', 'Programada', NULL, NULL, '2026-05-10 08:47:34', NULL, NULL),
(12, '123456789', '234567890', '2026-05-12 08:49:00', 'Programada', NULL, NULL, '2026-05-10 08:49:44', NULL, NULL),
(13, '123456789', '234567890', '2026-05-12 08:50:00', 'Programada', NULL, NULL, '2026-05-10 08:50:29', NULL, NULL),
(14, '123456789', '234567890', '2026-06-15 09:00:00', 'Programada', NULL, NULL, '2026-06-14 18:41:50', NULL, NULL),
(15, '123456789', '234567890', '2026-06-15 10:00:00', 'Programada', NULL, NULL, '2026-06-14 18:41:57', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinicas`
--

CREATE TABLE `clinicas` (
  `id_clinica` int NOT NULL,
  `nombre_comercial` varchar(150) NOT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `nif_cif` varchar(20) DEFAULT 'B12345678',
  `direccion_calle` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia_estado` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(20) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'España',
  `telefono_contacto` varchar(20) NOT NULL,
  `email_contacto` varchar(150) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `verifactu_env` enum('pruebas','produccion') DEFAULT 'pruebas',
  `verifactu_cert_path` varchar(255) DEFAULT NULL,
  `verifactu_cert_password` varchar(255) DEFAULT NULL,
  `verifactu_activo` tinyint(1) DEFAULT '1',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `clinicas`
--

INSERT INTO `clinicas` (`id_clinica`, `nombre_comercial`, `razon_social`, `nif_cif`, `direccion_calle`, `ciudad`, `provincia_estado`, `codigo_postal`, `pais`, `telefono_contacto`, `email_contacto`, `sitio_web`, `verifactu_env`, `verifactu_cert_path`, `verifactu_cert_password`, `verifactu_activo`, `fecha_registro`, `activo`) VALUES
(1, 'Clínica Velion', 'Velion S.L.', 'B87654321', 'Paseo de la Castellana 120', 'Madrid', 'Madrid', '28046', 'España', '910123456', 'contacto@velion.es', 'https://www.velion.es', 'pruebas', NULL, NULL, 1, '2026-05-01 08:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `contrato_id` int NOT NULL,
  `usuario_id` varchar(9) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `tipo_contrato` varchar(50) DEFAULT 'Indefinido',
  `salario_base_mensual` decimal(10,2) NOT NULL,
  `complementos_mensuales` decimal(10,2) DEFAULT '0.00',
  `pagas_extra` int DEFAULT '2' COMMENT '12 pagas + 2 extras = 14',
  `irpf_porcentaje` decimal(5,2) DEFAULT '15.00',
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`contrato_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `tipo_contrato`, `salario_base_mensual`, `complementos_mensuales`, `pagas_extra`, `irpf_porcentaje`, `activo`) VALUES
(1, '234567890', '2025-01-01', NULL, 'Indefinido', 2200.00, 300.00, 2, 15.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_clientes`
--

CREATE TABLE `cuentas_clientes` (
  `cuenta_id` int NOT NULL,
  `nombre_empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nif_cif` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Identificador corto para URL o subdominio',
  `plan_suscripcion` enum('Basico','Profesional','Premium') DEFAULT 'Basico',
  `plan_proximo` enum('Basico','Profesional','Premium') DEFAULT NULL COMMENT 'Downgrade programado a fin de ciclo',
  `estado_cuenta` enum('Activo','Suspendido','Cancelado') DEFAULT 'Activo',
  `email_admin` varchar(150) NOT NULL,
  `fecha_alta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_renovacion` date DEFAULT NULL,
  `configuracion_json` json DEFAULT NULL COMMENT 'Para ajustes específicos de marca, colores o límites'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cuentas_clientes`
--

INSERT INTO `cuentas_clientes` (`cuenta_id`, `nombre_empresa`, `nif_cif`, `slug`, `plan_suscripcion`, `plan_proximo`, `estado_cuenta`, `email_admin`, `fecha_alta`, `fecha_renovacion`, `configuracion_json`) VALUES
(1, 'Velion S.L.', 'B87654321', 'velion-sl', 'Profesional', NULL, 'Activo', 'admin@example.com', '2026-05-01 08:00:00', '2026-09-01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `usuario_id` varchar(9) NOT NULL,
  `nss` varchar(12) DEFAULT NULL COMMENT 'Número Seguridad Social',
  `iban` varchar(24) DEFAULT NULL,
  `grupo_cotizacion` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`usuario_id`, `nss`, `iban`, `grupo_cotizacion`) VALUES
('234567890', '281234567890', 'ES2100001234567890123456', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `factura_id` int NOT NULL,
  `serie` varchar(10) DEFAULT 'A',
  `numero` int NOT NULL,
  `tipo_factura` enum('F1','F2','R1','R2','R3','R4','R5') DEFAULT 'F1' COMMENT 'F1: Ordinaria, F2: Simplificada, Rx: Rectificativas',
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_hora_emision` datetime NOT NULL,
  `estado` enum('Pendiente','Pagada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `impuesto` decimal(5,2) NOT NULL,
  `cuota_iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `nif_emisor` varchar(20) DEFAULT 'B12345678',
  `fecha_hora_huso` varchar(50) DEFAULT NULL,
  `huella` varchar(256) DEFAULT NULL COMMENT 'Hash del registro actual para Verifactu',
  `huella_anterior` varchar(256) DEFAULT NULL COMMENT 'Hash de la factura anterior para encadenamiento',
  `firma_digital` text,
  `estado_verifactu` enum('Pendiente','Enviado','Aceptado','AceptadoConErrores','Rechazado','ErrorConexion') DEFAULT 'Pendiente',
  `csv_verifactu` varchar(255) DEFAULT NULL,
  `codigo_error_verifactu` varchar(50) DEFAULT NULL,
  `mensaje_verifactu` text DEFAULT NULL,
  `fecha_envio_verifactu` datetime DEFAULT NULL,
  `qr_url` text DEFAULT NULL,
  `xml_peticion` mediumtext DEFAULT NULL,
  `xml_respuesta` mediumtext DEFAULT NULL,
  `subsanacion` tinyint(1) DEFAULT '0',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`factura_id`, `serie`, `numero`, `tipo_factura`, `paciente_id`, `fecha_emision`, `fecha_hora_emision`, `estado`, `descripcion`, `precio`, `impuesto`, `cuota_iva`, `total`, `huella`, `huella_anterior`, `firma_digital`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(4, 'A', 1, 'F1', '123456789', '2026-05-09', '2026-05-09 19:06:34', 'Pendiente', 'prueba', 10.00, 21.00, 2.10, 12, '8f28111958bd099216831fb420f37087733deb3502330570e8079b3f75769663', NULL, NULL, '345678901', '2026-05-09 19:06:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `gasto_id` int NOT NULL,
  `nif_proveedor` varchar(20) NOT NULL,
  `nombre_proveedor` varchar(255) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha_emision` date NOT NULL,
  `concepto` varchar(255) NOT NULL,
  `base_imponible` decimal(10,2) NOT NULL,
  `tipo_iva` decimal(5,2) NOT NULL DEFAULT '21.00',
  `cuota_iva` decimal(10,2) NOT NULL,
  `retencion_irpf` decimal(5,2) NOT NULL DEFAULT '0.00',
  `cuota_irpf` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `categoria` enum('Alquileres','Suministros','Personal','Servicios profesionales','Bienes de inversiÃ³n','Otros') NOT NULL DEFAULT 'Otros',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`gasto_id`, `nif_proveedor`, `nombre_proveedor`, `numero_factura`, `fecha_emision`, `concepto`, `base_imponible`, `tipo_iva`, `cuota_iva`, `retencion_irpf`, `cuota_irpf`, `total`, `categoria`, `fecha_creacion`) VALUES
(1, 'A95075578', 'Iberdrola Clientes S.A.U.', 'BANC-20260609-97', '2026-06-09', 'Conciliación bancaria: PAGO CON TARJETA IBERDROLA', 118.60, 21.00, 24.91, 0.00, 0.00, 143.51, 'Suministros', '2026-06-10 15:44:20'),
(2, 'B82345678', 'Gestoría Rivas S.L.', 'BANC-20260607-38', '2026-06-07', 'Conciliación bancaria: RECIBO GESTORIA RIVAS CUOTA', 85.61, 21.00, 17.98, 15.00, 12.84, 90.75, 'Servicios profesionales', '2026-06-10 15:44:20'),
(3, 'F91122334', 'Patrimonial Centro Histórico S.A.', 'BANC-20260605-31', '2026-06-05', 'Conciliación bancaria: TRANSFERENCIA ALQUILER LOCAL JUNIO', 1423.53, 21.00, 298.94, 19.00, 270.47, 1452.00, 'Alquileres', '2026-06-10 15:44:20'),
(4, 'TGSS0001', 'Tesorería General de la S.S.', 'BANC-20260604-56', '2026-06-04', 'Conciliación bancaria: TRANSFERENCIA SEGUROS SOCIALES TGSS', 450.00, 0.00, 0.00, 0.00, 0.00, 450.00, 'Personal', '2026-06-10 15:44:20'),
(5, 'B99221100', 'FisioDistribuciones España S.L.', 'BANC-20260531-13', '2026-05-31', 'Conciliación bancaria: COMPRA MATERIAL SANITARIO FISIODIST', 145.50, 21.00, 30.56, 0.00, 0.00, 176.06, 'Otros', '2026-06-10 15:44:20'),
(6, 'A95075578', 'Iberdrola Clientes S.A.U.', 'BANC-20260609-75', '2026-06-09', 'Conciliación bancaria: PAGO CON TARJETA IBERDROLA', 118.60, 21.00, 24.91, 0.00, 0.00, 143.51, 'Suministros', '2026-06-10 15:44:32'),
(7, 'B82345678', 'Gestoría Rivas S.L.', 'BANC-20260607-57', '2026-06-07', 'Conciliación bancaria: RECIBO GESTORIA RIVAS CUOTA', 85.61, 21.00, 17.98, 15.00, 12.84, 90.75, 'Servicios profesionales', '2026-06-10 15:44:32'),
(8, 'F91122334', 'Patrimonial Centro Histórico S.A.', 'BANC-20260605-75', '2026-06-05', 'Conciliación bancaria: TRANSFERENCIA ALQUILER LOCAL JUNIO', 1423.53, 21.00, 298.94, 19.00, 270.47, 1452.00, 'Alquileres', '2026-06-10 15:44:32'),
(9, 'TGSS0001', 'Tesorería General de la S.S.', 'BANC-20260604-90', '2026-06-04', 'Conciliación bancaria: TRANSFERENCIA SEGUROS SOCIALES TGSS', 450.00, 0.00, 0.00, 0.00, 0.00, 450.00, 'Personal', '2026-06-10 15:44:32'),
(10, 'B99221100', 'FisioDistribuciones España S.L.', 'BANC-20260531-19', '2026-05-31', 'Conciliación bancaria: COMPRA MATERIAL SANITARIO FISIODIST', 145.50, 21.00, 30.56, 0.00, 0.00, 176.06, 'Otros', '2026-06-10 15:44:32'),
(11, 'A95075578', 'Iberdrola Clientes S.A.U.', 'BANC-20260609-14', '2026-06-09', 'Conciliación bancaria: PAGO CON TARJETA IBERDROLA', 118.60, 21.00, 24.91, 0.00, 0.00, 143.51, 'Suministros', '2026-06-10 15:46:02'),
(12, 'B82345678', 'Gestoría Rivas S.L.', 'BANC-20260607-32', '2026-06-07', 'Conciliación bancaria: RECIBO GESTORIA RIVAS CUOTA', 85.61, 21.00, 17.98, 15.00, 12.84, 90.75, 'Servicios profesionales', '2026-06-10 15:46:02'),
(13, 'F91122334', 'Patrimonial Centro Histórico S.A.', 'BANC-20260605-45', '2026-06-05', 'Conciliación bancaria: TRANSFERENCIA ALQUILER LOCAL JUNIO', 1423.53, 21.00, 298.94, 19.00, 270.47, 1452.00, 'Alquileres', '2026-06-10 15:46:02'),
(14, 'TGSS0001', 'Tesorería General de la S.S.', 'BANC-20260604-21', '2026-06-04', 'Conciliación bancaria: TRANSFERENCIA SEGUROS SOCIALES TGSS', 450.00, 0.00, 0.00, 0.00, 0.00, 450.00, 'Personal', '2026-06-10 15:46:02'),
(15, 'B99221100', 'FisioDistribuciones España S.L.', 'BANC-20260531-70', '2026-05-31', 'Conciliación bancaria: COMPRA MATERIAL SANITARIO FISIODIST', 145.50, 21.00, 30.56, 0.00, 0.00, 176.06, 'Otros', '2026-06-10 15:46:02'),
(16, 'A95075578', 'Iberdrola Clientes S.A.U.', 'BANC-20260609-68', '2026-06-09', 'Conciliación bancaria: PAGO CON TARJETA IBERDROLA', 118.60, 21.00, 24.91, 0.00, 0.00, 143.51, 'Suministros', '2026-06-10 15:46:06'),
(17, 'B82345678', 'Gestoría Rivas S.L.', 'BANC-20260607-69', '2026-06-07', 'Conciliación bancaria: RECIBO GESTORIA RIVAS CUOTA', 85.61, 21.00, 17.98, 15.00, 12.84, 90.75, 'Servicios profesionales', '2026-06-10 15:46:06'),
(18, 'F91122334', 'Patrimonial Centro Histórico S.A.', 'BANC-20260605-95', '2026-06-05', 'Conciliación bancaria: TRANSFERENCIA ALQUILER LOCAL JUNIO', 1423.53, 21.00, 298.94, 19.00, 270.47, 1452.00, 'Alquileres', '2026-06-10 15:46:06'),
(19, 'TGSS0001', 'Tesorería General de la S.S.', 'BANC-20260604-77', '2026-06-04', 'Conciliación bancaria: TRANSFERENCIA SEGUROS SOCIALES TGSS', 450.00, 0.00, 0.00, 0.00, 0.00, 450.00, 'Personal', '2026-06-10 15:46:06'),
(20, 'B99221100', 'FisioDistribuciones España S.L.', 'BANC-20260531-52', '2026-05-31', 'Conciliación bancaria: COMPRA MATERIAL SANITARIO FISIODIST', 145.50, 21.00, 30.56, 0.00, 0.00, 176.06, 'Otros', '2026-06-10 15:46:06'),
(21, 'A95075578', 'Iberdrola Clientes S.A.U.', 'BANC-20260609-32', '2026-06-09', 'Conciliación bancaria: PAGO CON TARJETA IBERDROLA', 118.60, 21.00, 24.91, 0.00, 0.00, 143.51, 'Suministros', '2026-06-10 15:46:23'),
(22, 'B82345678', 'Gestoría Rivas S.L.', 'BANC-20260607-34', '2026-06-07', 'Conciliación bancaria: RECIBO GESTORIA RIVAS CUOTA', 85.61, 21.00, 17.98, 15.00, 12.84, 90.75, 'Servicios profesionales', '2026-06-10 15:46:23'),
(23, 'F91122334', 'Patrimonial Centro Histórico S.A.', 'BANC-20260605-97', '2026-06-05', 'Conciliación bancaria: TRANSFERENCIA ALQUILER LOCAL JUNIO', 1423.53, 21.00, 298.94, 19.00, 270.47, 1452.00, 'Alquileres', '2026-06-10 15:46:23'),
(24, 'TGSS0001', 'Tesorería General de la S.S.', 'BANC-20260604-68', '2026-06-04', 'Conciliación bancaria: TRANSFERENCIA SEGUROS SOCIALES TGSS', 450.00, 0.00, 0.00, 0.00, 0.00, 450.00, 'Personal', '2026-06-10 15:46:23'),
(25, 'B99221100', 'FisioDistribuciones España S.L.', 'BANC-20260531-71', '2026-05-31', 'Conciliación bancaria: COMPRA MATERIAL SANITARIO FISIODIST', 145.50, 21.00, 30.56, 0.00, 0.00, 176.06, 'Otros', '2026-06-10 15:46:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historiales_medicos`
--

CREATE TABLE `historiales_medicos` (
  `historial_id` int NOT NULL,
  `paciente_id` varchar(9) NOT NULL,
  `fisioterapeuta_id` varchar(9) NOT NULL,
  `fecha_consulta` datetime NOT NULL,
  `motivo_consulta` varchar(255) DEFAULT NULL,
  `diagnostico` text,
  `tratamiento` text,
  `observaciones` text,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `historiales_medicos`
--

INSERT INTO `historiales_medicos` (`historial_id`, `paciente_id`, `fisioterapeuta_id`, `fecha_consulta`, `motivo_consulta`, `diagnostico`, `tratamiento`, `observaciones`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, '123456789', '345678901', '2026-05-06 07:28:10', 'dolor lumbar', 'mucho cuento', 'terapia de hostias', 'sdfdsf', '345678901', '2026-05-06 07:28:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_terapeutas`
--

CREATE TABLE `horarios_terapeutas` (
  `horario_id` int NOT NULL,
  `fisioterapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `horarios_terapeutas`
--

INSERT INTO `horarios_terapeutas` (`horario_id`, `fisioterapeuta_id`, `dia_semana`, `hora_inicio`, `hora_fin`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, '234567890', 'Lunes', '09:00:00', '18:30:00', NULL, '2026-05-10 16:33:30', NULL, NULL),
(2, '234567890', 'Martes', '09:00:00', '21:00:00', NULL, '2026-06-14 17:26:12', NULL, NULL),
(3, '234567890', 'Miércoles', '09:00:00', '21:00:00', NULL, '2026-06-14 17:26:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `metodo_id` int NOT NULL,
  `usuario_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tipo` enum('Tarjeta','PayPal','Transferencia') NOT NULL DEFAULT 'Tarjeta',
  `proveedor` varchar(50) DEFAULT NULL COMMENT 'Ej: Visa, MasterCard, Stripe',
  `last4` varchar(4) DEFAULT NULL COMMENT 'Últimos 4 dígitos para identificación visual',
  `fecha_expiracion` varchar(7) DEFAULT NULL COMMENT 'Formato MM/YYYY',
  `token_externo` varchar(255) DEFAULT NULL COMMENT 'ID o Token de la pasarela de pago (Stripe/PayPal)',
  `es_predeterminado` tinyint(1) DEFAULT '0',
  `nombre_titular` varchar(150) DEFAULT NULL,
  `numero_completo` varchar(25) DEFAULT NULL,
  `cvv` varchar(4) DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`metodo_id`, `usuario_id`, `tipo`, `proveedor`, `last4`, `fecha_expiracion`, `token_externo`, `es_predeterminado`, `nombre_titular`, `numero_completo`, `cvv`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, '345678901', 'Tarjeta', 'Visa', '4242', '12/2028', 'tok_velion_test_card', 1, 'PEDRO GOMEZ', '4548 1234 5678 4242', '123', '345678901', '2026-05-01 08:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominas`
--

CREATE TABLE `nominas` (
  `nomina_id` int NOT NULL,
  `contrato_id` int NOT NULL,
  `mes` int NOT NULL,
  `anio` int NOT NULL,
  `fecha_emision` date NOT NULL,
  `devengos_base` decimal(10,2) NOT NULL,
  `devengos_complementos` decimal(10,2) DEFAULT '0.00',
  `devengos_total_bruto` decimal(10,2) NOT NULL,
  `deduccion_seguridad_social_trabajador` decimal(10,2) NOT NULL,
  `deduccion_irpf` decimal(10,2) NOT NULL,
  `deducciones_total` decimal(10,2) NOT NULL,
  `liquido_a_percibir` decimal(10,2) NOT NULL,
  `coste_seguridad_social_empresa` decimal(10,2) NOT NULL,
  `estado` enum('Pendiente','Pagada') DEFAULT 'Pendiente',
  `pdf_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `pago_id` int NOT NULL,
  `factura_id` int NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `metodo_pago_id` int DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` varchar(9) NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `cp` varchar(7) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `genero` enum('Hombre','Mujer','Otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `rol` enum('Administrador','Fisioterapeuta','Secretario','Paciente') NOT NULL DEFAULT 'Paciente',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellidos`, `telefono`, `fecha_nacimiento`, `direccion`, `provincia`, `municipio`, `cp`, `email`, `pass`, `genero`, `rol`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
('123456789', 'Juan', 'Perez', '123456789', '1990-01-01', 'Calle 123', 'Provincia 1', 'Ciudad 1', '12345', 'patient@example.com', '$2y$12$bIEopyzNCTfMkRN7b/W.EOf22V1.Ss/n9bDOYE6pew9w5oX4ciseC', 'Hombre', 'Paciente', NULL, '2026-05-04 17:30:03', NULL, '2026-06-09 17:16:00'),
('234567890', 'Maria', 'Lopez', '234567890', '1995-05-05', 'Avenida 456', 'Provincia 2', 'Ciudad 2', '23456', 'fisio@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Mujer', 'Fisioterapeuta', NULL, '2026-05-04 17:30:03', NULL, '2026-06-12 20:07:40'),
('345678901', 'Pedro', 'Gomez', '345678901', '1985-10-10', 'Plaza 789', 'Provincia 3', 'Ciudad 3', '34567', 'admin@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Hombre', 'Administrador', NULL, '2026-05-04 17:30:03', NULL, '2026-06-12 20:07:40');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ausencias_terapeutas`
--
ALTER TABLE `ausencias_terapeutas`
  ADD PRIMARY KEY (`ausencia_id`),
  ADD KEY `fisioterapeuta_id` (`fisioterapeuta_id`),
  ADD KEY `fk_ausencia_creador` (`creado_por`),
  ADD KEY `fk_ausencia_modificador` (`modificado_por`);

--
-- Indices de la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD PRIMARY KEY (`bono_id`),
  ADD KEY `fk_bonos_creador` (`creado_por`),
  ADD KEY `fk_bonos_modificador` (`modificado_por`);

--
-- Indices de la tabla `bonos_pacientes`
--
ALTER TABLE `bonos_pacientes`
  ADD PRIMARY KEY (`bono_paciente_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `bono_id` (`bono_id`),
  ADD KEY `fk_bp_creador` (`creado_por`),
  ADD KEY `fk_bp_modificador` (`modificado_por`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`cita_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `fisioterapeuta_id` (`fisioterapeuta_id`),
  ADD KEY `fk_citas_creador` (`creado_por`),
  ADD KEY `fk_citas_modificador` (`modificado_por`),
  ADD KEY `fk_citas_bono` (`bono_paciente_id`);

--
-- Indices de la tabla `clinicas`
--
ALTER TABLE `clinicas`
  ADD PRIMARY KEY (`id_clinica`),
  ADD UNIQUE KEY `email_contacto` (`email_contacto`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`contrato_id`),
  ADD KEY `fk_contrato_usuario` (`usuario_id`);

--
-- Indices de la tabla `cuentas_clientes`
--
ALTER TABLE `cuentas_clientes`
  ADD PRIMARY KEY (`cuenta_id`),
  ADD UNIQUE KEY `uk_nif_cif` (`nif_cif`),
  ADD UNIQUE KEY `uk_slug` (`slug`),
  ADD UNIQUE KEY `uk_email_admin` (`email_admin`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`usuario_id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`factura_id`),
  ADD UNIQUE KEY `uk_serie_numero` (`serie`,`numero`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `fk_facturas_creador` (`creado_por`),
  ADD KEY `fk_facturas_modificador` (`modificado_por`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`gasto_id`);

--
-- Indices de la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  ADD PRIMARY KEY (`historial_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `fisioterapeuta_id` (`fisioterapeuta_id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `modificado_por` (`modificado_por`);

--
-- Indices de la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  ADD PRIMARY KEY (`horario_id`),
  ADD KEY `fisioterapeuta_id` (`fisioterapeuta_id`),
  ADD KEY `fk_horario_creador` (`creado_por`),
  ADD KEY `fk_horario_modificador` (`modificado_por`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`metodo_id`),
  ADD KEY `fk_usuario_id` (`usuario_id`),
  ADD KEY `fk_metodos_creador` (`creado_por`),
  ADD KEY `fk_metodos_modificador` (`modificado_por`);

--
-- Indices de la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`nomina_id`),
  ADD KEY `fk_nomina_contrato` (`contrato_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pago_id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `fk_pagos_creador` (`creado_por`),
  ADD KEY `fk_pagos_modificador` (`modificado_por`),
  ADD KEY `fk_pagos_metodo_pago` (`metodo_pago_id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_creador` (`creado_por`),
  ADD KEY `fk_usuarios_modificador` (`modificado_por`),
  ADD KEY `idx_usuarios_rol` (`rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ausencias_terapeutas`
--
ALTER TABLE `ausencias_terapeutas`
  MODIFY `ausencia_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bonos`
--
ALTER TABLE `bonos`
  MODIFY `bono_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bonos_pacientes`
--
ALTER TABLE `bonos_pacientes`
  MODIFY `bono_paciente_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `cita_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `clinicas`
--
ALTER TABLE `clinicas`
  MODIFY `id_clinica` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `contrato_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cuentas_clientes`
--
ALTER TABLE `cuentas_clientes`
  MODIFY `cuenta_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `factura_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `gasto_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  MODIFY `historial_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  MODIFY `horario_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `metodo_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `nominas`
--
ALTER TABLE `nominas`
  MODIFY `nomina_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `pago_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ausencias_terapeutas`
--
ALTER TABLE `ausencias_terapeutas`
  ADD CONSTRAINT `fk_ausencia_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ausencia_fisio` FOREIGN KEY (`fisioterapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ausencia_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD CONSTRAINT `fk_bonos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `bonos_pacientes`
--
ALTER TABLE `bonos_pacientes`
  ADD CONSTRAINT `fk_bonopaciente_bono` FOREIGN KEY (`bono_id`) REFERENCES `bonos` (`bono_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonopaciente_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bp_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bp_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`fisioterapeuta_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `fk_citas_bono` FOREIGN KEY (`bono_paciente_id`) REFERENCES `bonos_pacientes` (`bono_paciente_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `fk_contrato_usuario_link` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleado_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `fk_facturas_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facturas_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  ADD CONSTRAINT `fk_hm_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_fisio` FOREIGN KEY (`fisioterapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  ADD CONSTRAINT `fk_horario_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_horario_fisio` FOREIGN KEY (`fisioterapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_horario_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD CONSTRAINT `fk_metodo_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_metodos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_metodos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD CONSTRAINT `fk_nomina_contrato_link` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`contrato_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`factura_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_metodo_pago` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`metodo_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Estructura de tabla para la tabla `registro_horario`
--
CREATE TABLE `registro_horario` (
  `registro_id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` varchar(9) NOT NULL,
  `fecha` date NOT NULL,
  `entrada` datetime NOT NULL,
  `salida` datetime DEFAULT NULL,
  `notas` varchar(255) DEFAULT NULL,
  `creado_por` varchar(9) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`registro_id`),
  KEY `idx_registro_usuario` (`usuario_id`),
  KEY `idx_registro_fecha` (`fecha`),
  CONSTRAINT `fk_registro_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_registro_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_registro_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
