-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 26-10-2026 a las 18:13:36
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `veintidosrazonesparaaprobar_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','Otro') DEFAULT 'M',
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lugar_nacimiento` varchar(100) DEFAULT NULL,
  `escuela_origen` varchar(100) DEFAULT NULL,
  `observaciones_salud` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `dni`, `nombre`, `apellido`, `fecha_nacimiento`, `genero`, `direccion`, `telefono`, `email`, `creado_en`, `lugar_nacimiento`, `escuela_origen`, `observaciones_salud`) VALUES
(1, '4324328343', 'Jose', 'Sanchez', '2002-02-05', 'M', 'wdwdw', '244242442', 'Jose@gmail', '2026-10-22 22:33:47', NULL, NULL, NULL),
(2, '', 'deewfe', 'fewefw', '1888-03-14', 'M', NULL, NULL, NULL, '2026-10-26 17:56:43', 'Chilecito', '406', 'wrgwrgr wgr'),
(4, '375566', 'fwefew', 'wekyu', '2000-03-31', 'M', NULL, NULL, NULL, '2026-10-26 18:11:19', 'hrhrthet', '4683', 'yjft yyty');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int NOT NULL,
  `alumno_id` int NOT NULL,
  `madre_id` int DEFAULT NULL,
  `padre_id` int DEFAULT NULL,
  `responsable_firma` varchar(50) NOT NULL,
  `correo_firma` varchar(100) DEFAULT NULL,
  `autoriza_imagen` enum('si','no') NOT NULL,
  `usuario_preceptor_id` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `alumno_id`, `madre_id`, `padre_id`, `responsable_firma`, `correo_firma`, `autoriza_imagen`, `usuario_preceptor_id`, `creado_en`) VALUES
(1, 2, 1, 2, 'padre', 'cexar@deede.de', 'si', 1, '2026-10-26 17:56:43'),
(2, 4, 3, 4, 'madre', 'regerg@de.de', 'si', 1, '2026-10-26 18:11:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Administrador', 'Control total del sistema'),
(2, 'Preceptor', 'Gestión de inscripciones y datos de alumnos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutores`
--

CREATE TABLE `tutores` (
  `id` int NOT NULL,
  `tipo_tutor` enum('madre','padre','tutor') NOT NULL,
  `apellido_nombre` varchar(100) NOT NULL,
  `dni` varchar(20) DEFAULT NULL,
  `cuil` varchar(20) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `lugar_nacimiento` varchar(100) DEFAULT NULL,
  `telefono` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `domicilio` varchar(200) NOT NULL,
  `percibe_asignacion` enum('si','no') DEFAULT NULL,
  `estudios_cursados` varchar(50) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tutores`
--

INSERT INTO `tutores` (`id`, `tipo_tutor`, `apellido_nombre`, `dni`, `cuil`, `fecha_nacimiento`, `lugar_nacimiento`, `telefono`, `email`, `domicilio`, `percibe_asignacion`, `estudios_cursados`, `creado_en`) VALUES
(1, 'madre', 'Cexar', '63563553', '27635635538', '1989-05-24', 'La Rioja', '3825323923', 'cexar@gmail.com', 'larga', NULL, NULL, '2026-10-26 17:56:43'),
(2, 'padre', 'rergerg r', '53453434', '27534534348', '1989-04-23', 'chilecito', '35746155', 'dwafawdwa@dw.dw', 'larga', 'si', 'secundario', '2026-10-26 17:56:43'),
(3, 'madre', 'ynfnrt', '5456344', '47467467', '2000-04-23', 'rgerr gegr e', '6735857856', 'trrthrthwr@wd.dw', 'hrttr', NULL, NULL, '2026-10-26 18:11:19'),
(4, 'padre', 'rgrger r ', '46354645', '3463563543', '1220-06-24', 'ewfewefe', '86542544', 'ttrwrwg@fefe.fe', 'rgerg rege', 'si', 'secundario', '2026-10-26 18:11:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol_id` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `rol_id`, `creado_en`) VALUES
(1, 'cexar', 'cexar@gmail.com', 'cexar03', 1, '2026-10-15 22:24:31');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alumno_id` (`alumno_id`),
  ADD KEY `madre_id` (`madre_id`),
  ADD KEY `padre_id` (`padre_id`),
  ADD KEY `usuario_preceptor_id` (`usuario_preceptor_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tutores`
--
ALTER TABLE `tutores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tutores`
--
ALTER TABLE `tutores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`madre_id`) REFERENCES `tutores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inscripciones_ibfk_3` FOREIGN KEY (`padre_id`) REFERENCES `tutores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inscripciones_ibfk_4` FOREIGN KEY (`usuario_preceptor_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
