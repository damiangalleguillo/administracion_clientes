-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-10-2020 a las 04:08:13
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clientes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `ID` int(11) NOT NULL,
  `APELLIDO` varchar(45) NOT NULL,
  `NOMBRES` varchar(45) NOT NULL,
  `DNI` int(11) DEFAULT NULL,
  `CUIT` varchar(20) DEFAULT NULL,
  `EMAIL` varchar(45) DEFAULT NULL,
  `TELEFONO` varchar(45) DEFAULT NULL,
  `DOMICILIO` varchar(45) DEFAULT NULL,
  `LOCALIDAD` varchar(45) DEFAULT NULL,
  `CONDICION` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`ID`, `APELLIDO`, `NOMBRES`, `DNI`, `CUIT`, `EMAIL`, `TELEFONO`, `DOMICILIO`, `LOCALIDAD`, `CONDICION`) VALUES
(1, 'Galleguillo', 'Diego', 31333155, '20-31333155-6', 'ldiego@gmail.com', '03865632934', 'avenida donde vive 1569', 'aguilares', 0),
(2, 'Lopez', 'Roberto', 31333152, '20-31333152-5', 'tgdam.rc@gmail.com', '132132132123', 'ALEJANDRO AGUADO 1620', 'aguilares', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `ID` int(11) NOT NULL,
  `FECHA` date NOT NULL,
  `HS_CAPACITACION_CANT` decimal(10,0) NOT NULL,
  `HS_CAPACITACION_IMP` decimal(10,0) NOT NULL,
  `HS_DESARAROLLO_CANT` decimal(10,0) NOT NULL,
  `HS_DESARAROLLO_IMP` decimal(10,0) NOT NULL,
  `PROYECTO_ID` int(11) NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ANULADO` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `factura`
--

INSERT INTO `factura` (`ID`, `FECHA`, `HS_CAPACITACION_CANT`, `HS_CAPACITACION_IMP`, `HS_DESARAROLLO_CANT`, `HS_DESARAROLLO_IMP`, `PROYECTO_ID`, `USUARIO_ID`, `ANULADO`) VALUES
(1, '2020-10-25', '200', '50', '100', '10', 1, 1, 0),
(2, '2020-10-25', '200', '50', '100', '10', 1, 1, 0),
(3, '2020-10-25', '200', '50', '100', '10', 1, 1, 1),
(4, '2020-10-25', '200', '50', '100', '10', 1, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `ID` int(11) NOT NULL,
  `NOMBRE` varchar(45) NOT NULL,
  `DESCRIPCION` blob NOT NULL,
  `CLIENTE_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`ID`, `NOMBRE`, `DESCRIPCION`, `CLIENTE_ID`) VALUES
(1, 'gestion de clientes', 0x73697374656d6120706172612067657374696f6e20646520636c69656e7465732c207375732070726f796563746f732079206661637475726173, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID` int(11) NOT NULL,
  `EMAIL` varchar(45) NOT NULL,
  `PASS` varchar(60) NOT NULL,
  `NOMBRE` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID`, `EMAIL`, `PASS`, `NOMBRE`) VALUES
(1, 'tgdam.rc@gmail.com', '1234', 'Damian'),
(2, 'ignacioarroyo17@gmail.com', '1234', 'Ignacio'),
(3, 'milumedina98.mm@gmail.com', '1234', 'Milagros');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FACTURA_PROYECTO` (`PROYECTO_ID`),
  ADD KEY `FACTURA_USUARIO` (`USUARIO_ID`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `PROYECTO_CLIENTE` (`CLIENTE_ID`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `FACTURA_PROYECTO` FOREIGN KEY (`PROYECTO_ID`) REFERENCES `proyecto` (`ID`),
  ADD CONSTRAINT `FACTURA_USUARIO` FOREIGN KEY (`USUARIO_ID`) REFERENCES `usuarios` (`ID`);

--
-- Filtros para la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD CONSTRAINT `PROYECTO_CLIENTE` FOREIGN KEY (`CLIENTE_ID`) REFERENCES `cliente` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
