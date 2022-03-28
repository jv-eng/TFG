-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-02-2020 a las 09:29:27
-- Versión del servidor: 10.4.8-MariaDB
-- Versión de PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prueba2_tfg_tutorias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `idalumno` int(11) NOT NULL,
  `password` varchar(150) NOT NULL,
  `nombre_alumno` varchar(100) NOT NULL,
  `apellidos_alumno` varchar(100) NOT NULL,
  `mail_alumno` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`idalumno`, `password`, `nombre_alumno`, `apellidos_alumno`, `mail_alumno`) VALUES
(35, '21232f297a57a5a743894a0e4a801fc3', 'Admin', 'Admin', 'admin@fi.upm.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `franja_disponibilidad`
--

CREATE TABLE `franja_disponibilidad` (
  `idfranja` int(11) NOT NULL COMMENT 'Identificador',
  `id_profesor_fk` int(11) NOT NULL COMMENT 'Identificador fk profesor',
  `asignatura` varchar(100) NOT NULL,
  `tipo_citas` varchar(100) NOT NULL,
  `hora` int(4) NOT NULL COMMENT 'Hora de la franja',
  `minutos` int(4) NOT NULL,
  `duracion_slots` int(11) NOT NULL COMMENT 'Duración de la franja',
  `dia` date NOT NULL COMMENT 'Día de la franja',
  `numero_slots` int(10) NOT NULL,
  `ubicacion` varchar(10) NOT NULL COMMENT 'Por defecto el despacho del profesor'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones_alumno`
--

CREATE TABLE `notificaciones_alumno` (
  `id_notificaciones_alumno` int(11) NOT NULL,
  `id_alumno_fk` int(11) NOT NULL,
  `mail_alumno` varchar(100) NOT NULL,
  `asignatura` varchar(100) NOT NULL,
  `tipo_citas` varchar(100) NOT NULL,
  `motivo` varchar(300) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` int(4) NOT NULL,
  `minutos_cita` int(4) NOT NULL,
  `fecha_notif` date NOT NULL,
  `hora_notif` int(4) NOT NULL,
  `minutos_notif` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones_profesor`
--

CREATE TABLE `notificaciones_profesor` (
  `id_notificaciones_profesor` int(11) NOT NULL,
  `id_profesor_fk` int(11) NOT NULL,
  `mail_profesor` varchar(100) NOT NULL,
  `asignatura` varchar(100) NOT NULL,
  `tipo_citas` varchar(100) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` int(4) NOT NULL,
  `minutos_cita` int(4) NOT NULL,
  `fecha_notif` date NOT NULL,
  `hora_notif` int(4) NOT NULL,
  `minutos_notif` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id_profesor` int(11) NOT NULL,
  `password` varchar(150) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `tbuscar` varchar(100) DEFAULT NULL COMMENT 'Combinación de nombre y apellido para buscar',
  `mail` varchar(40) NOT NULL,
  `Despacho` varchar(10) DEFAULT NULL,
  `Administrador` tinyint(1) NOT NULL,
  `Validado` tinyint(1) NOT NULL,
  `calendarID` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session`
--

CREATE TABLE `session` (
  `id_sesion` varchar(50) NOT NULL,
  `mail_profesor` varchar(100) DEFAULT NULL,
  `mail_alumno` varchar(100) DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_click` time NOT NULL,
  `hora_fin` time NOT NULL,
  `profesor` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `slot`
--

CREATE TABLE `slot` (
  `id_slot_posicion` int(11) NOT NULL,
  `id_franja_disponibilidad` int(11) NOT NULL,
  `id_alumno_fk` int(11) DEFAULT NULL,
  `hora` int(4) NOT NULL,
  `minutos` int(4) NOT NULL,
  `duracion` int(20) NOT NULL COMMENT 'Heredado de la FD',
  `dia` date NOT NULL COMMENT 'Heredado de la FD',
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `comentarios_alumno` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`idalumno`),
  ADD UNIQUE KEY `mail_alumno` (`mail_alumno`);

--
-- Indices de la tabla `franja_disponibilidad`
--
ALTER TABLE `franja_disponibilidad`
  ADD PRIMARY KEY (`idfranja`),
  ADD KEY `id_prof_fk` (`id_profesor_fk`);

--
-- Indices de la tabla `notificaciones_alumno`
--
ALTER TABLE `notificaciones_alumno`
  ADD PRIMARY KEY (`id_notificaciones_alumno`),
  ADD KEY `id_alumno_fk` (`id_alumno_fk`);

--
-- Indices de la tabla `notificaciones_profesor`
--
ALTER TABLE `notificaciones_profesor`
  ADD PRIMARY KEY (`id_notificaciones_profesor`),
  ADD KEY `id_profesor_fk` (`id_profesor_fk`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id_profesor`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- Indices de la tabla `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id_sesion`),
  ADD UNIQUE KEY `mail` (`mail_profesor`),
  ADD UNIQUE KEY `mail_alumno` (`mail_alumno`);

--
-- Indices de la tabla `slot`
--
ALTER TABLE `slot`
  ADD PRIMARY KEY (`id_slot_posicion`),
  ADD KEY `id_franja_disponibilidad` (`id_franja_disponibilidad`),
  ADD KEY `id_alumno_fk` (`id_alumno_fk`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumno`
--
ALTER TABLE `alumno`
  MODIFY `idalumno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `franja_disponibilidad`
--
ALTER TABLE `franja_disponibilidad`
  MODIFY `idfranja` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificador', AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `notificaciones_alumno`
--
ALTER TABLE `notificaciones_alumno`
  MODIFY `id_notificaciones_alumno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones_profesor`
--
ALTER TABLE `notificaciones_profesor`
  MODIFY `id_notificaciones_profesor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `slot`
--
ALTER TABLE `slot`
  MODIFY `id_slot_posicion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `franja_disponibilidad`
--
ALTER TABLE `franja_disponibilidad`
  ADD CONSTRAINT `franja_disponibilidad_ibfk_1` FOREIGN KEY (`id_profesor_fk`) REFERENCES `profesor` (`id_profesor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones_alumno`
--
ALTER TABLE `notificaciones_alumno`
  ADD CONSTRAINT `notificaciones_alumno_ibfk_1` FOREIGN KEY (`id_alumno_fk`) REFERENCES `alumno` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones_profesor`
--
ALTER TABLE `notificaciones_profesor`
  ADD CONSTRAINT `notificaciones_profesor_ibfk_1` FOREIGN KEY (`id_profesor_fk`) REFERENCES `profesor` (`id_profesor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`mail_profesor`) REFERENCES `profesor` (`mail`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `session_ibfk_2` FOREIGN KEY (`mail_alumno`) REFERENCES `alumno` (`mail_alumno`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `slot`
--
ALTER TABLE `slot`
  ADD CONSTRAINT `slot_fks` FOREIGN KEY (`id_franja_disponibilidad`) REFERENCES `franja_disponibilidad` (`idfranja`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `slot_ibfk_1` FOREIGN KEY (`id_alumno_fk`) REFERENCES `alumno` (`idalumno`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
