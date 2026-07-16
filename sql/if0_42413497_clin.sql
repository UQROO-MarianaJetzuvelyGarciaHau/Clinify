-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: sql302.infinityfree.com
-- Tiempo de generación: 15-07-2026 a las 22:29:21
-- Versión del servidor: 11.4.12-MariaDB
-- Versión de PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `if0_42413497_clin`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caracteristicas`
--

CREATE TABLE `caracteristicas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caracteristicas`
--

INSERT INTO `caracteristicas` (`id`, `titulo`, `descripcion`, `estado`) VALUES
(1, 'Organización de estudios', 'Permite mantener los estudios médicos ordenados en un solo lugar.', 1),
(2, 'Acceso rápido', 'Facilita consultar información médica importante desde cualquier dispositivo.', 1),
(3, 'Historial centralizado', 'Ayuda a reunir información médica dispersa en una plataforma sencilla.', 1),
(4, 'Mayor seguridad', 'Reduce el riesgo de perder documentos físicos importantes por situaciones como la dispersión de estudios.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `plan_interes` int(11) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Nuevo','Atendido') NOT NULL DEFAULT 'Nuevo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `nombre`, `correo`, `telefono`, `plan_interes`, `mensaje`, `fecha_envio`, `estado`) VALUES
(1, 'Mariana Garcia', 'admin@demo.com', '9831119899', 1, 'Quisiera recibir más información', '2026-07-09 21:30:12', 'Atendido'),
(2, 'Paola Tun', 'paolaaa@gmail.com', '9831768769', 1, 'Quisiera más información', '2026-07-15 04:47:44', 'Atendido'),
(3, 'July Cabrera Carvajal', 'carvCarJ@outlook.com', '9831780029', 2, 'Quisiera ingresar al plan', '2026-07-15 04:48:50', 'Nuevo'),
(4, 'abby ram', 'roro23@uq.com', '9802392843', 2, 'Más info porfavor', '2026-07-15 04:49:28', 'Nuevo'),
(5, 'Daniel Lopez Uac', 'Didii09@hotmail.com', '8302842940', 2, 'Quisiera más información sobre el plan', '2026-07-15 04:50:27', 'Nuevo'),
(6, 'Maria Lopez Chuc', 'chhLopMaria@outlook.com', '9872900018', 2, 'Quisiera informacion sobre el plan a salir', '2026-07-15 13:04:06', 'Nuevo'),
(7, 'Luis', 'calb@gmail.com', '9836565134', 2, 'Quisiera más informaciones para registrarme y contratar', '2026-07-15 15:56:25', 'Nuevo'),
(8, 'Alejandro Hernandez', 'HHerAl@uqroo.mx', '9831151789', 6, 'Quisiera que se comuniquen conmigo cuando el plan salga y pueda registrarme', '2026-07-15 19:08:53', 'Nuevo'),
(9, 'Marina Herrera', 'marih@gmail.com', '9839999999', 6, 'Más información', '2026-07-15 23:25:19', 'Nuevo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

CREATE TABLE `equipo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` varchar(100) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `equipo`
--

INSERT INTO `equipo` (`id`, `nombre`, `rol`, `descripcion`) VALUES
(1, 'Diego Aldair Tillit Ihuit', 'Diseño y programación', 'Responsable del desarrollo del frontend, apoyo en el backend y conexión con la base de datos.'),
(2, 'Mariana Jetzuvely Garcia Hau', 'Diseño y programación', 'Apoyo en el diseño visual, backend, programación y conexión con la base de datos.'),
(3, 'Paola Juliette Tun Gómez', 'Documentación y análisis', 'Encargada de la documentación, análisis de requisitos y organización del proyecto.'),
(4, 'Abeline Stacey Ramírez', 'Pruebas y validación', 'Responsable de las pruebas funcionales y validación del sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `almacenamiento` varchar(100) NOT NULL,
  `cantidad_estudios` varchar(100) NOT NULL,
  `soporte` varchar(100) NOT NULL,
  `estado` enum('Activo','Proximamente','Desactivado') NOT NULL DEFAULT 'Activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`id`, `nombre`, `descripcion`, `precio`, `periodo`, `almacenamiento`, `cantidad_estudios`, `soporte`, `estado`) VALUES
(1, 'Básico', 'Ideal para usuarios que desean organizar sus estudios personales.', '99.00', 'Mensual', 'Almacenamiento básico', 'Hasta 20 estudios', 'Soporte por correo', 'Activo'),
(2, 'Familiar', 'Perfecto para familias que desean mantener varios perfiles organizados.', '199.00', 'Mensual', 'Almacenamiento familiar', 'Hasta 100 estudios', 'Soporte prioritario', 'Activo'),
(3, 'Premium', 'Para usuarios que buscan mayor capacidad y funciones avanzadas.', '299.00', 'Mensual', 'Mayor almacen', 'Estudios ilimitados', 'Soporte avanzado', 'Proximamente'),
(6, 'Arcus', 'Plan asociado a un tutor, mediante el cual se designa a la persona responsable de gestionar y dar seguimiento a los estudios clínicos del titular del plan. Esta modalidad está dirigida a menores de edad o a personas que, por su condición, requieran la representación y supervisión de un tutor.', '350.00', 'Mensual', 'Almacenamiento Básico', 'Hasta 80 archivos', 'Soporte por Correo', 'Proximamente'),
(8, 'Beta', 'Sistema de gestión de estudios médicos que permite organizar pacientes, programar estudios, almacenar resultados y dar seguimiento de forma rápida, segura y eficiente.', '780.00', 'Mensual', '5GB', 'Hasta 50 archivos', 'Soporte por correo', 'Desactivado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Asistente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(120) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `id_rol`, `estado`, `fecha_registro`) VALUES
(1, 'Administrador Chopi', 'admin@clinify.com', '$2y$10$YwTcb1jvzZ5zP4edJSi2NOY3vdgsa4g8tr9AQJ3DHzqbYWQp14ctO', 1, 1, '2026-07-14 01:16:07'),
(4, 'Diego', 'prueba@correo.com', '$2y$10$ZmDLf2xMkK4apksb6g.lTe1goPkfG.xmSaAAHz/3SlHUV1YkVQKAG', 1, 1, '2026-07-15 04:40:28'),
(5, 'Paola', 'paola@clin.com', '$2y$10$kopNYCbyxEYik/YHcXH8n.xcoaW8LDjVmIjkkN4iKeQJRt6OQeZte', 2, 1, '2026-07-15 04:43:41'),
(6, 'Abby', 'abbRam@clin.com', '$2y$10$E90TzAU9nykUNu7NRG4L5.3XQ2hsHGedjeIP.jP2S2OY0bAXqQg6a', 2, 1, '2026-07-15 04:55:15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_contactos_planes` (`plan_interes`);

--
-- Indices de la tabla `equipo`
--
ALTER TABLE `equipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuario_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caracteristicas`
--
ALTER TABLE `caracteristicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `equipo`
--
ALTER TABLE `equipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `fk_contactos_planes` FOREIGN KEY (`plan_interes`) REFERENCES `planes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
