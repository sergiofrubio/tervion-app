-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 24-08-2026 a las 11:51:31
-- Versión del servidor: 8.0.46
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
  `cuenta_id` int NOT NULL DEFAULT '1',
  `terapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `motivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos`
--

CREATE TABLE `bonos` (
  `bono_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_sesiones` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Activo',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `bonos`
--

INSERT INTO `bonos` (`bono_id`, `cuenta_id`, `nombre`, `numero_sesiones`, `precio`, `estado`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, 'Sesión individual', 1, 30.00, 'Activo', NULL, '2026-05-10 07:59:45', NULL, NULL),
(2, 1, 'Bono 10 sesiones', 10, 280.00, 'Activo', NULL, '2026-05-10 08:16:14', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bonos_pacientes`
--

CREATE TABLE `bonos_pacientes` (
  `bono_paciente_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bono_id` int NOT NULL,
  `sesiones_restantes` int NOT NULL,
  `fecha_compra` date NOT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `cita_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `terapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_cita_id` int DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('Programada','Cancelada','Realizada','Pendiente','Confirmada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bono_paciente_id` int DEFAULT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `token_confirmacion` varchar(64) DEFAULT NULL,
  `despacho_id` int DEFAULT NULL,
  `fecha_hora_fin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`cita_id`, `cuenta_id`, `paciente_id`, `terapeuta_id`, `tipo_cita_id`, `fecha_hora`, `estado`, `bono_paciente_id`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`, `token_confirmacion`) VALUES
(1, 1, '123456789', '234567890', 2, '2024-01-15 10:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, '2026-08-24 07:24:15', NULL),
(2, 1, '123456789', '234567890', 2, '2024-02-20 11:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, '2026-08-24 07:24:15', NULL),
(3, 1, '123456789', '234567890', 2, '2024-03-25 09:00:00', 'Realizada', NULL, NULL, '2026-05-04 17:37:34', NULL, '2026-08-24 07:24:15', NULL),
(4, 1, '123456789', '234567890', 2, '2024-04-20 10:00:00', 'Programada', NULL, NULL, '2026-05-04 17:37:34', NULL, '2026-08-24 07:24:15', NULL),
(5, 1, '234567890', '345678901', 2, '2024-05-05 14:00:00', 'Cancelada', NULL, NULL, '2026-05-04 17:37:34', NULL, '2026-08-24 07:24:15', NULL),
(8, 1, '123456789', '234567890', 2, '2026-05-12 08:42:00', 'Programada', NULL, NULL, '2026-05-10 08:42:05', NULL, '2026-08-24 07:24:15', NULL),
(9, 1, '123456789', '234567890', 2, '2026-05-12 08:42:00', 'Programada', NULL, NULL, '2026-05-10 08:42:29', NULL, '2026-08-24 07:24:15', NULL),
(10, 1, '123456789', '234567890', 2, '2026-05-12 08:45:00', 'Programada', NULL, NULL, '2026-05-10 08:45:47', NULL, '2026-08-24 07:24:15', NULL),
(11, 1, '123456789', '234567890', 2, '2026-05-12 08:47:00', 'Programada', NULL, NULL, '2026-05-10 08:47:34', NULL, '2026-08-24 07:24:15', NULL),
(12, 1, '123456789', '234567890', 2, '2026-05-12 08:49:00', 'Programada', NULL, NULL, '2026-05-10 08:49:44', NULL, '2026-08-24 07:24:15', NULL),
(13, 1, '123456789', '234567890', 2, '2026-05-12 08:50:00', 'Programada', NULL, NULL, '2026-05-10 08:50:29', NULL, '2026-08-24 07:24:15', NULL),
(14, 1, '123456789', '234567890', 2, '2026-06-15 09:00:00', 'Programada', NULL, NULL, '2026-06-14 18:41:50', NULL, '2026-08-24 07:24:15', NULL),
(15, 1, '123456789', '234567890', 2, '2026-06-15 10:00:00', 'Programada', NULL, NULL, '2026-06-14 18:41:57', NULL, '2026-08-24 07:24:15', NULL),
(16, 1, '123456789', '234567890', 2, '2026-06-15 09:00:00', 'Programada', NULL, NULL, '2026-08-24 07:02:02', NULL, '2026-08-24 07:24:15', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinicas`
--

CREATE TABLE `clinicas` (
  `clinica_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nombre_comercial` varchar(150) NOT NULL,
  `razon_social` varchar(150) DEFAULT NULL,
  `nif_cif` varchar(20) DEFAULT 'B12345678',
  `direccion` varchar(255) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clinicas`
--

INSERT INTO `clinicas` (`clinica_id`, `cuenta_id`, `nombre_comercial`, `razon_social`, `nif_cif`, `direccion`, `ciudad`, `provincia_estado`, `codigo_postal`, `pais`, `telefono_contacto`, `email_contacto`, `sitio_web`, `verifactu_env`, `verifactu_cert_path`, `verifactu_cert_password`, `verifactu_activo`, `fecha_registro`, `activo`) VALUES
(1, 1, 'Clínica Tervion', 'Tervion S.L.', 'B87654321', 'Paseo de la Castellana 120', 'Madrid', 'Madrid', '28046', 'España', '910123456', 'contacto@tervion.es', 'https://www.tervion.es', 'pruebas', NULL, NULL, 1, '2026-05-01 08:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_descuento`
--

CREATE TABLE `codigos_descuento` (
  `codigo_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_descuento` enum('porcentaje','fijo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'porcentaje',
  `valor` decimal(10,2) NOT NULL,
  `monto_minimo` decimal(10,2) DEFAULT '0.00',
  `usos_maximos` int DEFAULT NULL,
  `usos_actuales` int NOT NULL DEFAULT '0',
  `fecha_inicio` datetime DEFAULT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `despachos`
--

CREATE TABLE `despachos` (
  `despacho_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacidad` int NOT NULL DEFAULT '1',
  `equipamiento` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#6366f1',
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `despachos`
--

INSERT INTO `despachos` (`despacho_id`, `cuenta_id`, `nombre`, `ubicacion`, `capacidad`, `equipamiento`, `color`, `estado`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, 'Despacho Principal 1', 'Planta 1 - Puerta A', 1, 'Camilla eléctrica, ecógrafo de alta resolución, ordenador', '#3b82f6', 'Activo', NULL, '2026-08-29 18:00:00', NULL, NULL),
(2, 1, 'Cabina de Electroterapia', 'Planta 1 - Puerta B', 1, 'Camilla fija, equipo de diatermia/tecarterapia y TENS', '#10b981', 'Activo', NULL, '2026-08-29 18:00:00', NULL, NULL),
(3, 1, 'Sala de Readaptación', 'Planta Baja', 4, 'Pesas, elásticos, esterillas, bosu y poleas funcionales', '#f59e0b', 'Activo', NULL, '2026-08-29 18:00:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE `contratos` (
  `contrato_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `usuario_id` varchar(9) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `tipo_contrato` varchar(50) DEFAULT 'Indefinido',
  `salario_base_mensual` decimal(10,2) NOT NULL,
  `complementos_mensuales` decimal(10,2) DEFAULT '0.00',
  `pagas_extra` int DEFAULT '2' COMMENT '12 pagas + 2 extras = 14',
  `irpf_porcentaje` decimal(5,2) DEFAULT '15.00',
  `horas_semanales` decimal(5,2) DEFAULT '40.00' COMMENT 'Jornada laboral semanal acordada',
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `contratos`
--

INSERT INTO `contratos` (`contrato_id`, `cuenta_id`, `usuario_id`, `fecha_inicio`, `fecha_fin`, `tipo_contrato`, `salario_base_mensual`, `complementos_mensuales`, `pagas_extra`, `irpf_porcentaje`, `activo`) VALUES
(1, 1, '234567890', '2025-01-01', NULL, 'Indefinido', 2200.00, 300.00, 2, 15.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_clientes`
--

CREATE TABLE `cuentas_clientes` (
  `cuenta_id` int NOT NULL,
  `nombre_empresa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nif_cif` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Identificador corto para URL o subdominio',
  `plan_suscripcion` enum('Basico','Profesional','Premium') DEFAULT 'Basico',
  `plan_proximo` enum('Basico','Profesional','Premium') DEFAULT NULL COMMENT 'Downgrade programado a fin de ciclo',
  `estado_cuenta` enum('Activo','Suspendido','Cancelado') DEFAULT 'Activo',
  `email_admin` varchar(150) NOT NULL,
  `fecha_alta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_renovacion` date DEFAULT NULL,
  `configuracion_json` json DEFAULT NULL COMMENT 'Para ajustes específicos de marca, colores o límites'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuentas_clientes`
--

INSERT INTO `cuentas_clientes` (`cuenta_id`, `nombre_empresa`, `nif_cif`, `slug`, `plan_suscripcion`, `plan_proximo`, `estado_cuenta`, `email_admin`, `fecha_alta`, `fecha_renovacion`, `configuracion_json`) VALUES
(1, 'Tervion S.L.', 'B87654321', 'tervion-sl', 'Basico', NULL, 'Activo', 'admin@example.com', '2026-05-01 08:00:00', '2026-09-01', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `documento_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `categoria` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'General',
  `contenido` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_letra` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Inter',
  `tamano_letra` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '14px',
  `alineacion` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'left',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`documento_id`, `cuenta_id`, `titulo`, `descripcion`, `categoria`, `contenido`, `tipo_letra`, `tamano_letra`, `alineacion`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, 'Consentimiento Informado para Fisioterapia General', 'Autorización general del paciente para la realización de terapia manual, ejercicios terapéuticos y electroterapia.', 'General', '<h2>CONSENTIMIENTO INFORMADO DE FISIOTERAPIA</h2><p>Mediante el presente documento, el paciente declara haber sido informado satisfactoriamente sobre los <strong>tratamientos de fisioterapia</strong> que le serán aplicados en la clínica.</p><h3>Objetivo del tratamiento</h3><p>El tratamiento tiene como finalidad la disminución del dolor, la mejora de la movilidad articular y la recuperación funcional mediante técnicas manuales, ejercicio terapéutico y aparatología especializada.</p><h3>Posibles efectos secundarios</h3><ul><li>Molestias musculares o agujetas tras la sesión (24-48 horas).</li><li>Leve enrojecimiento de la piel en caso de termoterapia o electroterapia.</li></ul><p>El paciente consiente expresamente la realización de las técnicas prescritas por el fisioterapeuta colegiado.</p>', 'Inter', '14px', 'left', '123456789', '2026-05-15 10:00:00', NULL, NULL),
(2, 1, 'Cláusula de Protección de Datos Personales (RGPD)', 'Consentimiento expreso del tratamiento de datos de salud conforme al Reglamento General de Protección de Datos.', 'General', '<h2>INFORMACIÓN SOBRE PROTECCIÓN DE DATOS (RGPD / LOPDGDD)</h2><p>En cumplimiento del Reglamento (UE) 2016/679, le informamos de que sus datos personales de salud serán tratados bajo la responsabilidad de <strong>Clínica Tervion</strong>.</p><h3>Finalidad del tratamiento</h3><p>Gestión de su historia clínica, programación de citas, facturación y prestación de servicios sanitarios de fisioterapia y rehabilitación.</p><h3>Derechos del usuario</h3><p>Usted puede ejercer en cualquier momento sus derechos de <strong>acceso, rectificación, supresión, oposición y portabilidad</strong> de sus datos dirigiéndose por escrito a la clínica o mediante correo electrónico.</p>', 'Inter', '14px', 'left', '123456789', '2026-05-20 11:30:00', NULL, NULL),
(3, 1, 'Consentimiento Informado para Punción Seca', 'Consentimiento específico detallando riesgos, contraindicaciones y beneficios del tratamiento invasivo miofascial.', 'General', '<h2>CONSENTIMIENTO INFORMADO: PUNCIÓN SECA</h2><p>La <strong>Punción Seca</strong> es una técnica invasiva utilizada en fisioterapia para el tratamiento de los puntos gatillo miofasciales mediante el uso de agujas de acupuntura.</p><h3>Beneficios esperados</h3><p>Relajación inmediata del músculo afectado, alivio del dolor referido y restauración del rango de movimiento habitual.</p><h3>Riesgos y efectos secundarios informados</h3><ul><li>Dolor post-punción durante 24 a 72 horas.</li><li>Aparición de pequeños hematomas locales.</li><li>Neumotórax (riesgo extremadamente bajo en zonas torácicas/escapulares, minimizado mediante ecografía y técnica adecuada).</li></ul><p>Declaró no presentar alergia a metales ni alteraciones graves de la coagulación.</p>', 'Inter', '14px', 'left', '234567890', '2026-06-01 09:15:00', NULL, NULL),
(4, 1, 'Informe de Alta de Tratamiento y Pauta Domiciliaria', 'Plantilla normalizada para emitir el informe final de alta del paciente con recomendaciones de ejercicios domiciliarios.', 'General', '<h2>INFORME DE ALTA DE FISIOTERAPIA</h2><p>Se emite el presente informe tras haber completado satisfactoriamente el plan de tratamiento prescrito.</p><table border=\"1\" style=\"width:100%; border-collapse: collapse; text-align: left;\"><thead><tr style=\"background-color: #f1f5f9;\"><th>Parámetro</th><th>Evaluación Inicial</th><th>Estado al Alta</th></tr></thead><tbody><tr><td>Dolor (Escala EVA)</td><td>8 / 10</td><td>1 / 10</td></tr><tr><td>Balance Articular</td><td>Limitación moderada</td><td>Completo sin restricción</td></tr><tr><td>Autonomía Funcional</td><td>Parcial</td><td>100% Autónomo</td></tr></tbody></table><h3>Recomendaciones al Alta</h3><ol><li>Realizar estiramientos diarios según la pauta entregada.</li><li>Mantener hidratación adecuada e higiene postural en el puesto de trabajo.</li><li>Revisión preventiva en 6 meses o ante reaparición de síntomas.</li></ol>', 'Inter', '14px', 'left', '123456789', '2026-06-10 16:45:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_pacientes`
--

CREATE TABLE `documentos_pacientes` (
  `id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `documento_id` int NOT NULL,
  `paciente_id` varchar(9) NOT NULL,
  `contenido_firmado` mediumtext,
  `firma_paciente` longtext,
  `firmado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_firma` datetime DEFAULT NULL,
  `creado_por` varchar(9) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `usuario_id` varchar(9) NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nss` varchar(12) DEFAULT NULL COMMENT 'Número Seguridad Social',
  `iban` varchar(24) DEFAULT NULL,
  `grupo_cotizacion` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`usuario_id`, `cuenta_id`, `nss`, `iban`, `grupo_cotizacion`) VALUES
('234567890', 1, '281234567890', 'ES2100001234567890123456', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `factura_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `serie` varchar(10) DEFAULT 'A',
  `numero` int NOT NULL,
  `tipo_factura` enum('F1','F2','R1','R2','R3','R4','R5') DEFAULT 'F1' COMMENT 'F1: Ordinaria, F2: Simplificada, Rx: Rectificativas',
  `paciente_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_hora_emision` datetime NOT NULL,
  `estado` enum('Pendiente','Pagada') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `mensaje_verifactu` text,
  `fecha_envio_verifactu` datetime DEFAULT NULL,
  `qr_url` text,
  `xml_peticion` mediumtext,
  `xml_respuesta` mediumtext,
  `subsanacion` tinyint(1) DEFAULT '0',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`factura_id`, `cuenta_id`, `serie`, `numero`, `tipo_factura`, `paciente_id`, `fecha_emision`, `fecha_hora_emision`, `estado`, `descripcion`, `precio`, `impuesto`, `cuota_iva`, `total`, `nif_emisor`, `fecha_hora_huso`, `huella`, `huella_anterior`, `firma_digital`, `estado_verifactu`, `csv_verifactu`, `codigo_error_verifactu`, `mensaje_verifactu`, `fecha_envio_verifactu`, `qr_url`, `xml_peticion`, `xml_respuesta`, `subsanacion`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(4, 1, 'A', 1, 'F1', '123456789', '2026-05-09', '2026-05-09 19:06:34', 'Pendiente', 'prueba', 10.00, 21.00, 2.10, 12.00, 'B12345678', NULL, '8f28111958bd099216831fb420f37087733deb3502330570e8079b3f75769663', NULL, NULL, 'Pendiente', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '345678901', '2026-05-09 19:06:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas_lineas`
--

CREATE TABLE `facturas_lineas` (
  `linea_id` int NOT NULL,
  `factura_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `concepto` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT '1.00',
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento_porcentaje` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tipo_iva` decimal(5,2) NOT NULL DEFAULT '21.00',
  `cuota_iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `gasto_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
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
  `categoria` enum('Alquileres','Suministros','Personal','Servicios profesionales','Bienes de inversión','Otros') NOT NULL DEFAULT 'Otros',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historiales_medicos`
--

CREATE TABLE `historiales_medicos` (
  `historial_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `paciente_id` varchar(9) NOT NULL,
  `terapeuta_id` varchar(9) NOT NULL,
  `fecha_consulta` datetime NOT NULL,
  `motivo_consulta` varchar(255) DEFAULT NULL,
  `diagnostico` text,
  `tratamiento` text,
  `observaciones` text,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historiales_medicos`
--

INSERT INTO `historiales_medicos` (`historial_id`, `cuenta_id`, `paciente_id`, `terapeuta_id`, `fecha_consulta`, `motivo_consulta`, `diagnostico`, `tratamiento`, `observaciones`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, '123456789', '345678901', '2026-05-06 07:28:10', 'dolor lumbar', 'mucho cuento', 'terapia de hostias', 'sdfdsf', '345678901', '2026-05-06 07:28:10', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_accesos_historial`
--

CREATE TABLE `auditoria_accesos_historial` (
  `auditoria_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `historial_id` int NOT NULL,
  `usuario_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Usuario que realizó la acción',
  `accion` enum('CONSULTA','CREACION','MODIFICACION','ELIMINACION') NOT NULL DEFAULT 'CONSULTA',
  `detalles` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_origen` varchar(45) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_terapeutas`
--

CREATE TABLE `horarios_terapeutas` (
  `horario_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `terapeuta_id` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `horarios_terapeutas`
--

INSERT INTO `horarios_terapeutas` (`horario_id`, `cuenta_id`, `terapeuta_id`, `dia_semana`, `hora_inicio`, `hora_fin`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, '234567890', 'Lunes', '09:00:00', '18:30:00', NULL, '2026-05-10 16:33:30', NULL, NULL),
(2, 1, '234567890', 'Martes', '09:00:00', '21:00:00', NULL, '2026-06-14 17:26:12', NULL, NULL),
(3, 1, '234567890', 'Miércoles', '09:00:00', '21:00:00', NULL, '2026-06-14 17:26:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominas`
--

CREATE TABLE `nominas` (
  `nomina_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `pago_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `factura_id` int NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `metodo_pago` enum('Efectivo','Tarjeta','Transferencia','Bizum','Otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Efectivo',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes_suscripcion`
--

CREATE TABLE `planes_suscripcion` (
  `plan_id` int NOT NULL,
  `codigo` enum('Basico','Profesional','Premium') NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio_mensual` decimal(10,2) NOT NULL,
  `precio_anual` decimal(10,2) DEFAULT NULL,
  `max_fisioterapeutas` int DEFAULT NULL COMMENT 'NULL o 0 = ilimitado',
  `incluye_verifactu` tinyint(1) NOT NULL DEFAULT '1',
  `incluye_nominas` tinyint(1) NOT NULL DEFAULT '1',
  `soporte_prioritario` tinyint(1) NOT NULL DEFAULT '0',
  `estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `planes_suscripcion`
--

INSERT INTO `planes_suscripcion` (`plan_id`, `codigo`, `nombre`, `descripcion`, `precio_mensual`, `precio_anual`, `max_fisioterapeutas`, `incluye_verifactu`, `incluye_nominas`, `soporte_prioritario`, `estado`, `fecha_creacion`, `fecha_modificacion`) VALUES
(1, 'Basico', 'Plan Inicial Clínicas', 'Diseñado para fisioterapeutas autónomos y pequeñas consultas.', 29.00, 290.00, 1, 0, 0, 0, 'Activo', '2026-08-23 11:34:08', NULL),
(2, 'Profesional', 'Plan Clínica Multidisciplinar', 'Para clínicas en crecimiento con varios terapeutas y control fiscal Verifactu.', 79.00, 790.00, 5, 1, 1, 0, 'Activo', '2026-08-23 11:34:08', NULL),
(3, 'Premium', 'Plan Red de Clínicas / Franquicias', 'Capacidad ilimitada, integraciones dedicadas y soporte prioritario 24/7.', 199.00, 1990.00, NULL, 1, 1, 1, 'Activo', '2026-08-23 11:34:08', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_horario`
--

CREATE TABLE `registro_horario` (
  `registro_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `usuario_id` varchar(9) NOT NULL,
  `fecha` date NOT NULL,
  `entrada` datetime NOT NULL,
  `salida` datetime DEFAULT NULL,
  `notas` varchar(255) DEFAULT NULL,
  `creado_por` varchar(9) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_citas`
--

CREATE TABLE `tipos_citas` (
  `tipo_cita_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `duracion_minutos` int NOT NULL DEFAULT '60',
  `precio` decimal(10,2) DEFAULT '0.00',
  `color` varchar(20) DEFAULT '#3b82f6',
  `estado` enum('Activo','Inactivo') DEFAULT 'Activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipos_citas`
--

INSERT INTO `tipos_citas` (`tipo_cita_id`, `cuenta_id`, `nombre`, `descripcion`, `duracion_minutos`, `precio`, `color`, `estado`, `fecha_creacion`) VALUES
(1, 1, 'Valoracion Inicial', 'Evaluacion completa y diagnostico inicial del paciente.', 60, 50.00, '#3b82f6', 'Activo', '2026-08-24 07:24:15'),
(2, 1, 'Tratamiento General de Fisioterapia', 'Sesion estandar de terapia manual y tratamiento.', 45, 40.00, '#10b981', 'Activo', '2026-08-24 07:24:15'),
(3, 1, 'Sesion de Fisioterapia Deportiva', 'Tratamiento enfocado en lesiones deportivas y rendimiento.', 60, 45.00, '#f59e0b', 'Activo', '2026-08-24 07:24:15'),
(4, 1, 'Rehabilitacion Post-Quirurgica', 'Sesion intensiva de recuperacion postoperatoria.', 60, 55.00, '#ef4444', 'Activo', '2026-08-24 07:24:15'),
(5, 1, 'Sesion Corta / Revision', 'Revision rapida de evolucion y ajustes puntuales.', 30, 25.00, '#8b5cf6', 'Activo', '2026-08-24 07:24:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `dni` varchar(20) DEFAULT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `cp` varchar(7) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `genero` enum('Hombre','Mujer','Otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('Administrador','Terapeuta','Secretario','Paciente') NOT NULL DEFAULT 'Paciente',
  `creado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `dni`, `cuenta_id`, `nombre`, `apellidos`, `telefono`, `fecha_nacimiento`, `direccion`, `provincia`, `municipio`, `cp`, `email`, `pass`, `genero`, `rol`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
('123456789', 1, 'Juan', 'Perez', '123456789', '1990-01-01', 'Calle 123', 'Provincia 1', 'Ciudad 1', '12345', 'patient@example.com', '$2y$12$bIEopyzNCTfMkRN7b/W.EOf22V1.Ss/n9bDOYE6pew9w5oX4ciseC', 'Hombre', 'Paciente', NULL, '2026-05-04 17:30:03', NULL, '2026-06-09 17:16:00'),
('234567890', 1, 'Maria', 'Lopez', '234567890', '1995-05-05', 'Avenida 456', 'Provincia 2', 'Ciudad 2', '23456', 'fisio@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Mujer', 'Terapeuta', NULL, '2026-05-04 17:30:03', NULL, '2026-06-12 20:07:40'),
('345678901', 1, 'Pedro', 'Gomez', '345678901', '1985-10-10', 'Plaza 789', 'Provincia 3', 'Ciudad 3', '34567', 'admin@example.com', '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6', 'Hombre', 'Administrador', NULL, '2026-05-04 17:30:03', NULL, '2026-06-12 20:07:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
-- (Fichas de pacientes: datos administrativos, filiación, contacto de emergencia y tutores)
--

CREATE TABLE `pacientes` (
  `paciente_id` int NOT NULL,
  `cuenta_id` int NOT NULL DEFAULT '1',
  `usuario_id` int NOT NULL,
  `numero_expediente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_tutor` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dni_tutor` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_tutor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto_emergencia_nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto_emergencia_telefono` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `compania_seguro` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_poliza` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones_administrativas` text COLLATE utf8mb4_unicode_ci,
  `alergias_alertas` text COLLATE utf8mb4_unicode_ci,
  `creado_por` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado_por` varchar(9) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`paciente_id`, `cuenta_id`, `usuario_id`, `numero_expediente`, `nombre_tutor`, `dni_tutor`, `telefono_tutor`, `contacto_emergencia_nombre`, `contacto_emergencia_telefono`, `compania_seguro`, `numero_poliza`, `observaciones_administrativas`, `alergias_alertas`, `creado_por`, `fecha_creacion`, `modificado_por`, `fecha_modificacion`) VALUES
(1, 1, 2, 'EXP-2026-0001', NULL, NULL, NULL, 'Familiar Contacto', '699887766', 'Sanitas', 'POL-99281', 'Ficha administrativa inicial del paciente', 'Sin alergias farmacológicas conocidas', NULL, '2026-05-04 17:30:03', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ausencias_terapeutas`
--
ALTER TABLE `ausencias_terapeutas`
  ADD PRIMARY KEY (`ausencia_id`),
  ADD KEY `idx_ausencia_cuenta` (`cuenta_id`),
  ADD KEY `terapeuta_id` (`terapeuta_id`),
  ADD KEY `fk_ausencia_creador` (`creado_por`),
  ADD KEY `fk_ausencia_modificador` (`modificado_por`);

--
-- Indices de la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD PRIMARY KEY (`bono_id`),
  ADD KEY `idx_bonos_cuenta` (`cuenta_id`),
  ADD KEY `fk_bonos_creador` (`creado_por`),
  ADD KEY `fk_bonos_modificador` (`modificado_por`);

--
-- Indices de la tabla `bonos_pacientes`
--
ALTER TABLE `bonos_pacientes`
  ADD PRIMARY KEY (`bono_paciente_id`),
  ADD KEY `idx_bp_cuenta` (`cuenta_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `bono_id` (`bono_id`),
  ADD KEY `fk_bp_creador` (`creado_por`),
  ADD KEY `fk_bp_modificador` (`modificado_por`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`cita_id`),
  ADD KEY `idx_citas_cuenta` (`cuenta_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `terapeuta_id` (`terapeuta_id`),
  ADD KEY `fk_citas_creador` (`creado_por`),
  ADD KEY `fk_citas_modificador` (`modificado_por`),
  ADD KEY `fk_citas_bono` (`bono_paciente_id`),
  ADD KEY `fk_citas_tipos_citas` (`tipo_cita_id`),
  ADD KEY `fk_citas_despacho` (`despacho_id`);

--
-- Indices de la tabla `clinicas`
--
ALTER TABLE `clinicas`
  ADD PRIMARY KEY (`clinica_id`),
  ADD UNIQUE KEY `email_contacto` (`email_contacto`),
  ADD KEY `idx_clinicas_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `codigos_descuento`
--
ALTER TABLE `codigos_descuento`
  ADD PRIMARY KEY (`codigo_id`),
  ADD UNIQUE KEY `uk_cuenta_codigo` (`cuenta_id`,`codigo`),
  ADD KEY `idx_codigo_cuenta` (`cuenta_id`),
  ADD KEY `idx_codigo_estado` (`estado`),
  ADD KEY `fk_codigos_creador` (`creado_por`),
  ADD KEY `fk_codigos_modificador` (`modificado_por`);

--
-- Indices de la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD PRIMARY KEY (`despacho_id`),
  ADD KEY `idx_despachos_cuenta` (`cuenta_id`),
  ADD KEY `fk_despachos_creador` (`creado_por`),
  ADD KEY `fk_despachos_modificador` (`modificado_por`);

--
-- Indices de la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD PRIMARY KEY (`contrato_id`),
  ADD KEY `idx_contratos_cuenta` (`cuenta_id`),
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
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`documento_id`);

--
-- Indices de la tabla `documentos_pacientes`
--
ALTER TABLE `documentos_pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_docpac_cuenta` (`cuenta_id`),
  ADD KEY `idx_docpac_paciente` (`paciente_id`),
  ADD KEY `idx_docpac_documento` (`documento_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`usuario_id`),
  ADD KEY `idx_empleados_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`factura_id`),
  ADD UNIQUE KEY `uk_cuenta_serie_numero` (`cuenta_id`,`serie`,`numero`),
  ADD KEY `idx_facturas_cuenta` (`cuenta_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `fk_facturas_creador` (`creado_por`),
  ADD KEY `fk_facturas_modificador` (`modificado_por`);

--
-- Indices de la tabla `facturas_lineas`
--
ALTER TABLE `facturas_lineas`
  ADD PRIMARY KEY (`linea_id`),
  ADD KEY `idx_fl_factura` (`factura_id`),
  ADD KEY `idx_fl_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`gasto_id`),
  ADD KEY `idx_gastos_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  ADD PRIMARY KEY (`historial_id`),
  ADD KEY `idx_hm_cuenta` (`cuenta_id`),
  ADD KEY `paciente_id` (`paciente_id`),
  ADD KEY `terapeuta_id` (`terapeuta_id`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `modificado_por` (`modificado_por`);

--
-- Indices de la tabla `auditoria_accesos_historial`
--
ALTER TABLE `auditoria_accesos_historial`
  ADD PRIMARY KEY (`auditoria_id`),
  ADD KEY `idx_auditoria_cuenta` (`cuenta_id`),
  ADD KEY `idx_auditoria_historial` (`historial_id`),
  ADD KEY `idx_auditoria_usuario` (`usuario_id`),
  ADD KEY `idx_auditoria_fecha` (`fecha_hora`);

--
-- Indices de la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  ADD PRIMARY KEY (`horario_id`),
  ADD KEY `idx_horarios_cuenta` (`cuenta_id`),
  ADD KEY `terapeuta_id` (`terapeuta_id`),
  ADD KEY `fk_horario_creador` (`creado_por`),
  ADD KEY `fk_horario_modificador` (`modificado_por`);

--
-- Indices de la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD PRIMARY KEY (`nomina_id`),
  ADD KEY `idx_nominas_cuenta` (`cuenta_id`),
  ADD KEY `fk_nomina_contrato` (`contrato_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pago_id`),
  ADD KEY `idx_pagos_cuenta` (`cuenta_id`),
  ADD KEY `factura_id` (`factura_id`),
  ADD KEY `fk_pagos_creador` (`creado_por`),
  ADD KEY `fk_pagos_modificador` (`modificado_por`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `planes_suscripcion`
--
ALTER TABLE `planes_suscripcion`
  ADD PRIMARY KEY (`plan_id`),
  ADD UNIQUE KEY `uk_plan_codigo` (`codigo`);

--
-- Indices de la tabla `registro_horario`
--
ALTER TABLE `registro_horario`
  ADD PRIMARY KEY (`registro_id`),
  ADD KEY `idx_registro_cuenta` (`cuenta_id`),
  ADD KEY `idx_registro_usuario` (`usuario_id`),
  ADD KEY `idx_registro_fecha` (`fecha`),
  ADD KEY `fk_registro_creador` (`creado_por`),
  ADD KEY `fk_registro_modificador` (`modificado_por`);

--
-- Indices de la tabla `tipos_citas`
--
ALTER TABLE `tipos_citas`
  ADD PRIMARY KEY (`tipo_cita_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `uk_cuenta_email` (`cuenta_id`,`email`),
  ADD KEY `idx_usuarios_cuenta` (`cuenta_id`),
  ADD KEY `fk_usuarios_creador` (`creado_por`),
  ADD KEY `fk_usuarios_modificador` (`modificado_por`),
  ADD KEY `idx_usuarios_rol` (`rol`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`paciente_id`),
  ADD KEY `idx_pacientes_cuenta` (`cuenta_id`),
  ADD KEY `idx_pacientes_usuario` (`usuario_id`),
  ADD KEY `fk_pacientes_creador` (`creado_por`),
  ADD KEY `fk_pacientes_modificador` (`modificado_por`);

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
  MODIFY `cita_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `clinicas`
--
ALTER TABLE `clinicas`
  MODIFY `clinica_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `codigos_descuento`
--
ALTER TABLE `codigos_descuento`
  MODIFY `codigo_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `despachos`
--
ALTER TABLE `despachos`
  MODIFY `despacho_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `documento_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `documentos_pacientes`
--
ALTER TABLE `documentos_pacientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `factura_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `facturas_lineas`
--
ALTER TABLE `facturas_lineas`
  MODIFY `linea_id` int NOT NULL AUTO_INCREMENT;


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
-- AUTO_INCREMENT de la tabla `auditoria_accesos_historial`
--
ALTER TABLE `auditoria_accesos_historial`
  MODIFY `auditoria_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  MODIFY `horario_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `planes_suscripcion`
--
ALTER TABLE `planes_suscripcion`
  MODIFY `plan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `registro_horario`
--
ALTER TABLE `registro_horario`
  MODIFY `registro_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos_citas`
--
ALTER TABLE `tipos_citas`
  MODIFY `tipo_cita_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `paciente_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ausencias_terapeutas`
--
ALTER TABLE `ausencias_terapeutas`
  ADD CONSTRAINT `fk_ausencia_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ausencia_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ausencia_fisio` FOREIGN KEY (`terapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ausencia_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `bonos`
--
ALTER TABLE `bonos`
  ADD CONSTRAINT `fk_bonos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `bonos_pacientes`
--
ALTER TABLE `bonos_pacientes`
  ADD CONSTRAINT `fk_bonopaciente_bono` FOREIGN KEY (`bono_id`) REFERENCES `bonos` (`bono_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bonopaciente_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bp_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bp_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bp_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`terapeuta_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `fk_citas_bono` FOREIGN KEY (`bono_paciente_id`) REFERENCES `bonos_pacientes` (`bono_paciente_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_despacho` FOREIGN KEY (`despacho_id`) REFERENCES `despachos` (`despacho_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citas_tipos_citas` FOREIGN KEY (`tipo_cita_id`) REFERENCES `tipos_citas` (`tipo_cita_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `clinicas`
--
ALTER TABLE `clinicas`
  ADD CONSTRAINT `fk_clinicas_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `codigos_descuento`
--
ALTER TABLE `codigos_descuento`
  ADD CONSTRAINT `fk_codigos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_codigos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_codigos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `despachos`
--
ALTER TABLE `despachos`
  ADD CONSTRAINT `fk_despachos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_despachos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_despachos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `documentos_pacientes`
--
ALTER TABLE `documentos_pacientes`
  ADD CONSTRAINT `fk_docpac_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docpac_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docpac_documento` FOREIGN KEY (`documento_id`) REFERENCES `documentos` (`documento_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docpac_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_docpac_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `fk_contrato_usuario_link` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_contratos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD CONSTRAINT `fk_empleado_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_empleados_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`),
  ADD CONSTRAINT `fk_facturas_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facturas_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_facturas_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `facturas_lineas`
--
ALTER TABLE `facturas_lineas`
  ADD CONSTRAINT `fk_fl_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fl_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`factura_id`) ON DELETE CASCADE ON UPDATE CASCADE;


--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `fk_gastos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historiales_medicos`
--
ALTER TABLE `historiales_medicos`
  ADD CONSTRAINT `fk_hm_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_fisio` FOREIGN KEY (`terapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_hm_paciente` FOREIGN KEY (`paciente_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `auditoria_accesos_historial`
--
ALTER TABLE `auditoria_accesos_historial`
  ADD CONSTRAINT `fk_auditoria_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auditoria_historial` FOREIGN KEY (`historial_id`) REFERENCES `historiales_medicos` (`historial_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horarios_terapeutas`
--
ALTER TABLE `horarios_terapeutas`
  ADD CONSTRAINT `fk_horario_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_horario_fisio` FOREIGN KEY (`terapeuta_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_horario_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_horarios_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nominas`
--
ALTER TABLE `nominas`
  ADD CONSTRAINT `fk_nomina_contrato_link` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`contrato_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_nominas_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_factura` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`factura_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `registro_horario`
--
ALTER TABLE `registro_horario`
  ADD CONSTRAINT `fk_registro_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `fk_pacientes_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuentas_clientes` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pacientes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pacientes_creador` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pacientes_modificador` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`usuario_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

-- --------------------------------------------------------
-- Procedimiento almacenado para generar datos sintéticos masivos
-- Objetivo: Forzar I/O de disco, saturar el buffer pool de InnoDB
-- y evidenciar las ventajas del uso de caché (Redis/Memcached/APCu/Query Cache)
-- --------------------------------------------------------

DELIMITER $$

DROP PROCEDURE IF EXISTS `poblar_datos_benchmark`$$

CREATE PROCEDURE `poblar_datos_benchmark`()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE total_pacientes INT DEFAULT 1000;
    DECLARE total_citas_por_paciente INT DEFAULT 20;
    DECLARE cur_paciente_id VARCHAR(9);
    DECLARE lorem_texto LONGTEXT;

    -- Texto extenso para saturar páginas InnoDB en disco (campos TEXT / MEDIUMTEXT)
    SET lorem_texto = CONCAT(
        'Paciente acude a consulta refiriendo dolor osteomuscular severo con irradiacion radicular. ',
        'Tras anamnesis y exploracion clinica se detecta hipertonia acusada, puntos gatillo miofasciales activos en cuadrado lumbar y trapecio superior. ',
        'Se realiza ecografia musculoesqueletica descartando rotura fibrilar aguda y observando engrosamiento fascial compatible con sobrecarga mecanica cronica. ',
        'Pauta de intervencion: Terapia manual articular ortopedica, neuromodulacion percutanea guiada por ecografia, puncion seca profunda y diatermia capacitiva/resistiva a 448 kHz. ',
        'Recomendaciones domiciliarias de readaptacion y descanso activo durante 72 horas. Evolucion sujeta a respuesta biologica. ',
        REPEAT('Parametros biomecanicos de control y seguimiento continuo de rango articular (ROM) y dinamometria muscular isometrica basal. ', 8)
    );

    -- Ajustes de sesion para acelerar insercion masiva inicial
    SET autocommit = 0;
    SET unique_checks = 0;
    SET foreign_key_checks = 0;

    -- 1. Inserción de Pacientes sintéticos (~1,000 pacientes con DNIs únicos)
    WHILE i <= total_pacientes DO
        SET cur_paciente_id = LPAD(i + 1000000, 9, '0');
        
        INSERT INTO `usuarios` (
            `dni`, `cuenta_id`, `nombre`, `apellidos`, `telefono`,
            `fecha_nacimiento`, `direccion`, `provincia`, `municipio`, `cp`,
            `email`, `pass`, `genero`, `rol`, `creado_por`, `fecha_creacion`, `activo`
        ) VALUES (
            cur_paciente_id,
            1,
            ELT(1 + (i MOD 10), 'Carlos', 'Elena', 'David', 'Laura', 'Alejandro', 'Sofia', 'Manuel', 'Patricia', 'Javier', 'Lucia'),
            ELT(1 + (i MOD 10), 'Garcia Romero', 'Rodriguez Gil', 'Fernandez Sanz', 'Lopez Diaz', 'Sanchez Cano', 'Perez Ruiz', 'Gomez Molina', 'Martin Ortiz', 'Navarro Torres', 'Serrano Marin'),
            CONCAT('6', LPAD(FLOOR(RAND() * 99999999), 8, '0')),
            DATE_SUB('2000-01-01', INTERVAL (i MOD 15000) DAY),
            CONCAT('Avenida de la Salud nº ', i),
            'Madrid',
            'Madrid',
            '28001',
            CONCAT('paciente_', cur_paciente_id, '@benchmark.local'),
            '$2y$10$N7JA82u/XFyaeHM.4t44S.9KKcgpj5yikEYBZ8k/0cp4qmvA/MEb6',
            IF(i MOD 2 = 0, 'Hombre', 'Mujer'),
            'Paciente',
            '345678901',
            NOW() - INTERVAL (i MOD 365) DAY,
            1
        );

        -- Guardar en bloques de 250 pacientes
        IF i MOD 250 = 0 THEN
            COMMIT;
        END IF;

        SET i = i + 1;
    END WHILE;
    COMMIT;

    -- 2. Inserción masiva de Citas e Historiales Médicos pesados
    -- Total estimado: ~20.000 citas y ~20.000 historiales médicos con textos voluminosos
    SET i = 1;
    WHILE i <= (total_pacientes * total_citas_por_paciente) DO
        SET cur_paciente_id = LPAD((i MOD total_pacientes) + 1000001, 9, '0');

        -- Citas clínicas distribuidas a lo largo del tiempo
        INSERT INTO `citas` (
            `cuenta_id`, `paciente_id`, `terapeuta_id`, `tipo_cita_id`, `fecha_hora`,
            `estado`, `creado_por`, `fecha_creacion`, `despacho_id`
        ) VALUES (
            1,
            cur_paciente_id,
            '234567890',
            1 + (i MOD 4),
            NOW() - INTERVAL (i MOD 730) DAY - INTERVAL (i MOD 12) HOUR,
            ELT(1 + (i MOD 3), 'Realizada', 'Programada', 'Confirmada'),
            '345678901',
            NOW() - INTERVAL (i MOD 730) DAY,
            1 + (i MOD 3)
        );

        -- Historiales médicos con payload de texto para forzar lectura pesada de disco
        INSERT INTO `historiales_medicos` (
            `cuenta_id`, `paciente_id`, `terapeuta_id`, `fecha_consulta`,
            `motivo_consulta`, `diagnostico`, `tratamiento`, `observaciones`,
            `creado_por`, `fecha_creacion`
        ) VALUES (
            1,
            cur_paciente_id,
            '234567890',
            NOW() - INTERVAL (i MOD 730) DAY,
            CONCAT('Episodio patologico #', i, ' - Lumbalgia mecanica / Cervicobraquialgia recurrente'),
            CONCAT('Diagnostico funcional detallado caso #', i, ': ', SUBSTRING(lorem_texto, 1, 300)),
            CONCAT('Plan terapeutico aplicado #', i, ': ', lorem_texto),
            CONCAT('Observaciones clinicas y seguimiento #', i, ': Paciente responde favorablemente. ', lorem_texto),
            '234567890',
            NOW() - INTERVAL (i MOD 730) DAY
        );

        -- Auditorías de accesos a historias clínicas (aumenta el tamaño global de índices y tablas)
        INSERT INTO `auditoria_accesos_historial` (
            `cuenta_id`, `historial_id`, `usuario_id`, `accion`, `detalles`, `ip_origen`, `fecha_hora`
        ) VALUES (
            1,
            LAST_INSERT_ID(),
            '234567890',
            'CONSULTA',
            CONCAT('Acceso clinico recurrente a historia del paciente ', cur_paciente_id),
            '192.168.1.100',
            NOW() - INTERVAL (i MOD 730) DAY
        );

        -- Facturas y líneas de facturación asociadas
        IF i MOD 2 = 0 THEN
            INSERT INTO `facturas` (
                `cuenta_id`, `serie`, `numero`, `tipo_factura`, `paciente_id`,
                `fecha_emision`, `fecha_hora_emision`, `estado`, `descripcion`,
                `precio`, `impuesto`, `cuota_iva`, `total`, `creado_por`
            ) VALUES (
                1,
                'A',
                1000 + i,
                'F1',
                cur_paciente_id,
                DATE(NOW() - INTERVAL (i MOD 730) DAY),
                NOW() - INTERVAL (i MOD 730) DAY,
                'Pagada',
                CONCAT('Servicio de Fisioterapia y Rehabilitacion #', i),
                50.00,
                21.00,
                10.50,
                60.50,
                '345678901'
            );

            INSERT INTO `facturas_lineas` (
                `factura_id`, `cuenta_id`, `concepto`, `cantidad`, `precio_unitario`,
                `descuento_porcentaje`, `tipo_iva`, `cuota_iva`, `total`
            ) VALUES (
                LAST_INSERT_ID(),
                1,
                'Sesion de Fisioterapia y Terapia Manual Especializada',
                1.00,
                50.00,
                0.00,
                21.00,
                10.50,
                60.50
            );
        END IF;

        -- Commit cada 1000 iteraciones para no colapsar el buffer de logs de transacciones
        IF i MOD 1000 = 0 THEN
            COMMIT;
        END IF;

        SET i = i + 1;
    END WHILE;
    COMMIT;

    -- Restaurar configuración
    SET unique_checks = 1;
    SET foreign_key_checks = 1;
    SET autocommit = 1;
END$$

-- Ejecutar la generación masiva de datos para benchmark
-- CALL `poblar_datos_benchmark`()$$

-- Limpiar el procedimiento tras la carga inicial
-- DROP PROCEDURE IF EXISTS `poblar_datos_benchmark`$$

DELIMITER ;

-- ========================================================
-- INSTRUCCIONES DE EJECUCIÓN MANUAL:
-- El procedimiento queda registrado en la base de datos sin ejecutarse automáticamente.
-- Cuando quieras generar la carga de datos para tus pruebas de I/O y caché, ejecuta:
--
--     CALL poblar_datos_benchmark();
--
-- Y si más adelante deseas eliminar el procedimiento:
--     DROP PROCEDURE IF EXISTS poblar_datos_benchmark;
-- ========================================================

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
