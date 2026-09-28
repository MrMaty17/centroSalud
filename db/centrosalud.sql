-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-09-2026 a las 04:52:24
-- Versión del servidor: 8.0.42
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `centrosalud`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atencion`
--

CREATE TABLE `atencion` (
  `id_atencion` int NOT NULL,
  `id_turno` int NOT NULL,
  `diagnostico` text,
  `observaciones` text,
  `archivo_pdf` varchar(255) DEFAULT NULL,
  `fecha_atencion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `atencion`
--

INSERT INTO `atencion` (`id_atencion`, `id_turno`, `diagnostico`, `observaciones`, `archivo_pdf`, `fecha_atencion`) VALUES
(1, 1, 'Control general sin hallazgos', 'Se indican estudios de rutina', NULL, '2026-09-15 09:20:00'),
(2, 2, 'Arritmia leve', 'Se solicita electrocardiograma', 'estudios/ecg_40000002.pdf', '2026-09-18 10:50:00'),
(3, 4, 'Sin fracturas visibles', 'Radiografía de rodilla derecha', 'estudios/rx_40000004.pdf', '2026-09-22 08:45:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctor`
--

CREATE TABLE `doctor` (
  `dni` int NOT NULL,
  `matricula` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `doctor`
--

INSERT INTO `doctor` (`dni`, `matricula`) VALUES
(30000006, 'MP12345'),
(30000007, 'MP12346'),
(30000008, 'MP12347');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctor_especialidad`
--

CREATE TABLE `doctor_especialidad` (
  `dni_doctor` int NOT NULL,
  `id_especialidad` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `doctor_especialidad`
--

INSERT INTO `doctor_especialidad` (`dni_doctor`, `id_especialidad`) VALUES
(30000006, 1),
(30000007, 2),
(30000006, 3),
(30000008, 4),
(30000008, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidad`
--

CREATE TABLE `especialidad` (
  `id_especialidad` int NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `costo_base` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `especialidad`
--

INSERT INTO `especialidad` (`id_especialidad`, `nombre`, `costo_base`) VALUES
(1, 'Clínica General', 5000.00),
(2, 'Pediatría', 5500.00),
(3, 'Cardiología', 7000.00),
(4, 'Traumatología', 6500.00),
(5, 'Laboratorio', 3000.00),
(6, 'Rayos X', 4000.00),
(7, 'Ecografía', 4500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra_social`
--

CREATE TABLE `obra_social` (
  `id_obra_social` int NOT NULL,
  `nombre` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `obra_social`
--

INSERT INTO `obra_social` (`id_obra_social`, `nombre`) VALUES
(1, 'OSDE'),
(2, 'Swiss Medical'),
(3, 'IOMA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `dni` int NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `id_plan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`dni`, `fecha_nacimiento`, `id_plan`) VALUES
(40000001, '1990-05-14', 1),
(40000002, '1985-11-02', 3),
(40000003, '2001-03-27', NULL),
(40000004, '1972-08-09', 5),
(40000005, '2015-01-20', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `dni` int NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`dni`, `nombre_completo`, `email`, `password`, `telefono`, `id_rol`) VALUES
(30000001, 'Admin Principal', 'admin@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334455', 1),
(30000002, 'Laura Gómez', 'lgomez@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334456', 2),
(30000003, 'Carlos Ruiz', 'cruiz@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334457', 2),
(30000004, 'Ana Torres', 'atorres@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334458', 4),
(30000005, 'Marcos Díaz', 'mdiaz@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334459', 4),
(30000006, 'Juan Pérez', 'jperez@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334460', 3),
(30000007, 'Sofía Martínez', 'smartinez@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334461', 3),
(30000008, 'Diego Fernández', 'dfernandez@centrosalud.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1122334462', 3),
(40000001, 'María López', 'mlopez@mail.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1155001001', 5),
(40000002, 'Pedro Sánchez', 'psanchez@mail.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1155001002', 5),
(40000003, 'Lucía Fernández', 'lfernandez@mail.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1155001003', 5),
(40000004, 'Jorge Ramírez', 'jramirez@mail.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1155001004', 5),
(40000005, 'Valentina Castro', 'vcastro@mail.com', '$2b$10$QS7ZCFottMWiFJPbx0sjjeHc8L.T24NEUg2BNGzNudQyirmotCt4e', '1155001005', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id_plan` int NOT NULL,
  `id_obra_social` int NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `porcentaje_cobertura` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`id_plan`, `id_obra_social`, `nombre`, `porcentaje_cobertura`) VALUES
(1, 1, 'Plan 210', 70.00),
(2, 1, 'Plan 310', 80.00),
(3, 2, 'SMG20', 60.00),
(4, 2, 'SMG02', 90.00),
(5, 3, 'Plan Básico', 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int NOT NULL,
  `nombre_rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre_rol`) VALUES
(1, 'administrador'),
(3, 'doctor'),
(5, 'paciente'),
(4, 'secretario'),
(2, 'supervisor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `id_turno` int NOT NULL,
  `dni_paciente` int NOT NULL,
  `dni_doctor` int NOT NULL,
  `id_especialidad` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `estado` enum('pendiente','confirmado','cancelado','realizado','ausente') NOT NULL DEFAULT 'pendiente',
  `costo_final` decimal(10,2) DEFAULT NULL,
  `fecha_solicitud` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`id_turno`, `dni_paciente`, `dni_doctor`, `id_especialidad`, `fecha`, `hora`, `estado`, `costo_final`, `fecha_solicitud`) VALUES
(1, 40000001, 30000006, 1, '2026-09-15', '09:00:00', 'realizado', 1500.00, '2026-09-27 23:34:59'),
(2, 40000002, 30000006, 3, '2026-09-18', '10:30:00', 'realizado', 2800.00, '2026-09-27 23:34:59'),
(3, 40000003, 30000008, 4, '2026-09-20', '11:00:00', 'ausente', 6500.00, '2026-09-27 23:34:59'),
(4, 40000004, 30000008, 6, '2026-09-22', '08:30:00', 'realizado', 2000.00, '2026-09-27 23:34:59'),
(5, 40000005, 30000007, 2, '2026-09-25', '16:00:00', 'cancelado', 1100.00, '2026-09-27 23:34:59'),
(6, 40000001, 30000006, 3, '2026-10-02', '09:30:00', 'pendiente', 2100.00, '2026-09-27 23:34:59'),
(7, 40000005, 30000007, 2, '2026-10-05', '15:00:00', 'confirmado', 1100.00, '2026-09-27 23:34:59'),
(8, 40000003, 30000006, 1, '2026-10-07', '10:00:00', 'pendiente', 5000.00, '2026-09-27 23:34:59'),
(9, 40000002, 30000008, 6, '2026-10-08', '12:00:00', 'pendiente', 1600.00, '2026-09-27 23:34:59'),
(10, 40000001, 30000008, 6, '2026-09-30', '08:30:00', 'pendiente', 4000.00, '2026-09-27 23:35:34'),
(11, 40000001, 30000008, 6, '2026-09-30', '08:30:00', 'pendiente', 1200.00, '2026-09-27 23:36:36');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atencion`
--
ALTER TABLE `atencion`
  ADD PRIMARY KEY (`id_atencion`),
  ADD UNIQUE KEY `id_turno` (`id_turno`);

--
-- Indices de la tabla `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`dni`);

--
-- Indices de la tabla `doctor_especialidad`
--
ALTER TABLE `doctor_especialidad`
  ADD PRIMARY KEY (`dni_doctor`,`id_especialidad`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  ADD PRIMARY KEY (`id_obra_social`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `id_plan` (`id_plan`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`dni`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id_plan`),
  ADD KEY `id_obra_social` (`id_obra_social`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`id_turno`),
  ADD KEY `dni_paciente` (`dni_paciente`),
  ADD KEY `dni_doctor` (`dni_doctor`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atencion`
--
ALTER TABLE `atencion`
  MODIFY `id_atencion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `especialidad`
--
ALTER TABLE `especialidad`
  MODIFY `id_especialidad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  MODIFY `id_obra_social` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id_plan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `id_turno` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `atencion`
--
ALTER TABLE `atencion`
  ADD CONSTRAINT `atencion_ibfk_1` FOREIGN KEY (`id_turno`) REFERENCES `turno` (`id_turno`);

--
-- Filtros para la tabla `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `persona` (`dni`);

--
-- Filtros para la tabla `doctor_especialidad`
--
ALTER TABLE `doctor_especialidad`
  ADD CONSTRAINT `doctor_especialidad_ibfk_1` FOREIGN KEY (`dni_doctor`) REFERENCES `doctor` (`dni`),
  ADD CONSTRAINT `doctor_especialidad_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`dni`) REFERENCES `persona` (`dni`),
  ADD CONSTRAINT `paciente_ibfk_2` FOREIGN KEY (`id_plan`) REFERENCES `plan` (`id_plan`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);

--
-- Filtros para la tabla `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `plan_ibfk_1` FOREIGN KEY (`id_obra_social`) REFERENCES `obra_social` (`id_obra_social`);

--
-- Filtros para la tabla `turno`
--
ALTER TABLE `turno`
  ADD CONSTRAINT `turno_ibfk_1` FOREIGN KEY (`dni_paciente`) REFERENCES `paciente` (`dni`),
  ADD CONSTRAINT `turno_ibfk_2` FOREIGN KEY (`dni_doctor`) REFERENCES `doctor` (`dni`),
  ADD CONSTRAINT `turno_ibfk_3` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
