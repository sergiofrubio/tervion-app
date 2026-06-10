-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 15-05-2026 a las 19:02:24
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
-- Base de datos: `velion`
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
  `estado` enum('Programada','Cancelada','Realizada','Pendiente') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `bono_paciente_id` int DEFAULT NULL,
  `especialidad_id` int NOT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`cita_id`, `paciente_id`, `fisioterapeuta_id`, `fecha_hora`, `estado`, `bono_paciente_id`, `especialidad_id`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, '123456789', '234567890', '2024-01-15 10:00:00', 'Realizada', NULL, 1, NULL, '2026-05-04 17:37:34', NULL, NULL),
(2, '123456789', '234567890', '2024-02-20 11:00:00', 'Realizada', NULL, 1, NULL, '2026-05-04 17:37:34', NULL, NULL),
(3, '123456789', '234567890', '2024-03-25 09:00:00', 'Realizada', NULL, 1, NULL, '2026-05-04 17:37:34', NULL, NULL),
(4, '123456789', '234567890', '2024-04-20 10:00:00', 'Programada', NULL, 1, NULL, '2026-05-04 17:37:34', NULL, NULL),
(5, '234567890', '345678901', '2024-05-05 14:00:00', 'Cancelada', NULL, 2, NULL, '2026-05-04 17:37:34', NULL, NULL),
(8, '123456789', '234567890', '2026-05-12 08:42:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:42:05', NULL, NULL),
(9, '123456789', '234567890', '2026-05-12 08:42:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:42:29', NULL, NULL),
(10, '123456789', '234567890', '2026-05-12 08:45:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:45:47', NULL, NULL),
(11, '123456789', '234567890', '2026-05-12 08:47:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:47:34', NULL, NULL),
(12, '123456789', '234567890', '2026-05-12 08:49:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:49:44', NULL, NULL),
(13, '123456789', '234567890', '2026-05-12 08:50:00', 'Programada', NULL, 1, NULL, '2026-05-10 08:50:29', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinicas`
--

CREATE TABLE `clinicas` (
  `id_clinica` int NOT NULL,
  `nombre_comercial` varchar(150) NOT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `direccion_calle` varchar(255) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia_estado` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(20) DEFAULT NULL,
  `pais` varchar(100) DEFAULT 'España',
  `telefono_contacto` varchar(20) NOT NULL,
  `email_contacto` varchar(150) DEFAULT NULL,
  `sitio_web` varchar(255) DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `estado_cuenta` enum('Activo','Suspendido','Cancelado') DEFAULT 'Activo',
  `email_admin` varchar(150) NOT NULL,
  `fecha_alta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_renovacion` date DEFAULT NULL,
  `configuracion_json` json DEFAULT NULL COMMENT 'Para ajustes específicos de marca, colores o límites'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cuentas_clientes`
--

INSERT INTO `cuentas_clientes` (`cuenta_id`, `nombre_empresa`, `nif_cif`, `slug`, `plan_suscripcion`, `estado_cuenta`, `email_admin`, `fecha_alta`, `fecha_renovacion`, `configuracion_json`) VALUES
(1, 'Fisioterapia Avanzada S.L.', 'B12345678', 'fisio-avanzada', 'Profesional', 'Activo', 'contacto@fisioavanzada.com', '2026-05-09 20:56:55', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `especialidad_id` int NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`especialidad_id`, `descripcion`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 'Fisioterapia Deportiva', NULL, '2026-05-04 17:30:18', NULL, NULL),
(2, 'Fisioterapia Neurologica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(3, 'Fisioterapia Respiratoria', NULL, '2026-05-04 17:30:18', NULL, NULL),
(4, 'Fisioterapia Pediatrica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(5, 'Fisioterapia Geriatrica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(6, 'Fisioterapia Ortopedica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(7, 'Fisioterapia Cardiovascular', NULL, '2026-05-04 17:30:18', NULL, NULL),
(8, 'Fisioterapia Oncologica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(9, 'Fisioterapia del Suelo Pelvico', NULL, '2026-05-04 17:30:18', NULL, NULL),
(10, 'Fisioterapia Musculoesqueletica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(11, 'Fisioterapia Acuatica (Hidroterapia)', NULL, '2026-05-04 17:30:18', NULL, NULL),
(12, 'Fisioterapia Manual', NULL, '2026-05-04 17:30:18', NULL, NULL),
(13, 'Fisioterapia Deportiva Adaptada', NULL, '2026-05-04 17:30:18', NULL, NULL),
(14, 'Fisioterapia del Dolor', NULL, '2026-05-04 17:30:18', NULL, NULL),
(15, 'Fisioterapia Vestibular', NULL, '2026-05-04 17:30:18', NULL, NULL),
(16, 'Fisioterapia en Salud Mental', NULL, '2026-05-04 17:30:18', NULL, NULL),
(17, 'Fisioterapia Dermatofuncional', NULL, '2026-05-04 17:30:18', NULL, NULL),
(18, 'Fisioterapia en Disfunciones Temporomandibulares', NULL, '2026-05-04 17:30:18', NULL, NULL),
(19, 'Fisioterapia en Traumatologia y Cirugi­a Ortopedica', NULL, '2026-05-04 17:30:18', NULL, NULL),
(20, 'Fisioterapia en Salud de la Mujer (Maternidad y Postparto)', NULL, '2026-05-04 17:30:18', NULL, NULL);

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
  `total` int NOT NULL,
  `huella` varchar(256) DEFAULT NULL COMMENT 'Hash del registro actual para Verifactu',
  `huella_anterior` varchar(256) DEFAULT NULL COMMENT 'Hash de la factura anterior para encadenamiento',
  `firma_digital` text,
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
-- Estructura de tabla para la tabla `fisioterapeutas`
--

CREATE TABLE `fisioterapeutas` (
  `usuario_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `especialidad_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `fisioterapeutas`
--

INSERT INTO `fisioterapeutas` (`usuario_id`, `especialidad_id`) VALUES
('234567890', 5);

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
(1, '234567890', 'Lunes', '09:00:00', '18:30:00', NULL, '2026-05-10 16:33:30', NULL, NULL);

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
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `usuario_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`usuario_id`) VALUES
('123456789');

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
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellidos`, `telefono`, `fecha_nacimiento`, `direccion`, `provincia`, `municipio`, `cp`, `email`, `pass`, `genero`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
('123456789', 'Juan', 'Perez', '123456789', '1990-01-01', 'Calle 123', 'Provincia 1', 'Ciudad 1', '12345', 'patient@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Hombre', NULL, '2026-05-04 17:30:03', NULL, NULL),
('234567890', 'Maria', 'Lopez', '234567890', '1995-05-05', 'Avenida 456', 'Provincia 2', 'Ciudad 2', '23456', 'fisio@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Mujer', NULL, '2026-05-04 17:30:03', NULL, NULL),
('345678901', 'Pedro', 'Gomez', '345678901', '1985-10-10', 'Plaza 789', 'Provincia 3', 'Ciudad 3', '34567', 'admin@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Hombre', NULL, '2026-05-04 17:30:03', NULL, NULL);

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
  ADD KEY `especialidad_id` (`especialidad_id`),
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
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`especialidad_id`),
  ADD KEY `fk_especialidades_creador` (`creado_por`),
  ADD KEY `fk_especialidades_modificador` (`modificado_por`);

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
-- Indices de la tabla `fisioterapeutas`
--
ALTER TABLE `fisioterapeutas`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `fk_fisios_especialidad` (`especialidad_id`);

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
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`usuario_id`);

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
  ADD KEY `fk_usuarios_modificador` (`modificado_por`);

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
  MODIFY `cita_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `clinicas`
--
ALTER TABLE `clinicas`
  MODIFY `id_clinica` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contratos`
--
ALTER TABLE `contratos`
  MODIFY `contrato_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cuentas_clientes`
--
ALTER TABLE `cuentas_clientes`
  MODIFY `cuenta_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `especialidad_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `factura_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  MODIFY `historial_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  MODIFY `horario_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `metodo_id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`especialidad_id`),
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
-- Filtros para la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD CONSTRAINT `fk_especialidades_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_especialidades_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `fk_facturas_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facturas_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `fisioterapeutas`
--
ALTER TABLE `fisioterapeutas`
  ADD CONSTRAINT `fk_fisios_especialidad` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`especialidad_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fisios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_pacientes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
