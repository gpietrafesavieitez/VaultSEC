-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-09-2021 a las 20:00:36
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `vaultsec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vs_mimetypes`
--

CREATE TABLE `vs_mimetypes` (
  `mid` tinyint(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vs_mimetypes`
--

INSERT INTO `vs_mimetypes` (`mid`, `name`) VALUES
(11, 'application/octet-stream'),
(4, 'application/pdf'),
(10, 'application/zip'),
(5, 'audio/mp4'),
(6, 'audio/mpeg'),
(9, 'image/gif'),
(2, 'image/jpeg'),
(3, 'image/png'),
(7, 'image/webp'),
(1, 'text/plain'),
(8, 'video/mp4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vs_roles`
--

CREATE TABLE `vs_roles` (
  `rid` tinyint(4) NOT NULL,
  `type` tinytext COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vs_roles`
--

INSERT INTO `vs_roles` (`rid`, `type`) VALUES
(0, 'admin'),
(1, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vs_storage`
--

CREATE TABLE `vs_storage` (
  `sid` int(11) NOT NULL,
  `checksum` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `mimetype` tinyint(4) NOT NULL,
  `user` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `size` int(11) NOT NULL DEFAULT 0,
  `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vs_storage`
--

INSERT INTO `vs_storage` (`sid`, `checksum`, `path`, `name`, `mimetype`, `user`, `size`, `modified`) VALUES
(432, 'd273d63619c9aeaf15cdaf76422c4f87', '\\Documentos', 'LICENSE.txt', 1, 'user', 11560, '2021-09-01 17:44:11'),
(433, 'a4c8b6e3a38cce25e492694a0ac76523', '\\Documentos', 'README.txt', 1, 'user', 1368, '2021-09-01 17:44:11'),
(434, 'c051daabcd77df7173a9e76cbdacf638', '\\Fotos', 'yo.png', 3, 'user', 165503, '2021-09-01 17:44:18'),
(435, 'ec14f5869990a175d9ae1dbe1f838a6d', '\\Fotos', '5b9bd3f3c68ddb25c6748300c1d91872.png', 3, 'user', 186943, '2021-09-01 17:44:20'),
(436, 'ca930a3828e439bbe916e397c97c5665', '\\Fotos', '7tasbza6x3161.png', 3, 'user', 2429565, '2021-09-01 17:44:21'),
(437, 'ca1e3cbffbdb38fad2f932b9b83827a8', '\\Fotos', 'arch.png', 3, 'user', 41798, '2021-09-01 17:44:23'),
(438, '52f73762b5be7b69e9c14b988f5976d3', '', 'tenor.gif', 9, 'user', 2195489, '2021-09-01 17:44:34'),
(439, '2d0837843d2f86a726945c574795c55b', '', 'gifanimado.gif', 9, 'user', 181699, '2021-09-01 17:44:35'),
(440, '88c05cb684026ea9e51b1616a26c9d4b', '', 'The Lord of the Rings Opening Theme.mp3', 6, 'user', 7029749, '2021-09-01 17:45:11'),
(441, '358d0afe3682c3b94e02b696a6e3e75b', '\\Documentos', 'factura.txt', 1, 'user', 6, '2021-09-01 17:45:25'),
(442, '5e841c611d738b16fb7e1b7b28c9fc1c', '\\Documentos', 'factura2.txt', 1, 'user', 6, '2021-09-01 17:45:27'),
(443, '327104fd2110558ae5959aac6fc12b3d', '', 'factura3.txt', 1, 'user', 6, '2021-09-01 17:45:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vs_users`
--

CREATE TABLE `vs_users` (
  `uid` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `displayname` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 2,
  `creation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `vs_users`
--

INSERT INTO `vs_users` (`uid`, `displayname`, `password`, `role`, `creation`) VALUES
('admin', 'Administrador', '$2y$10$RlQjhQCt728e6b2ZeBYxp.vE2O/rb3y.93B5gCJnYXz/cdllfGzGm', 0, '2021-09-01 17:40:27'),
('test', 'Test', '$2y$10$cBBr1isiJCkbFsILd2nfL.qiNabJNBVP6w71GUPMiKpaxrRK4uXQq', 1, '2021-09-01 17:41:40'),
('user', 'Usuario', '$2a$12$BwpaLFtMxkpBvfWeSIfkT.5NnYvQlQNdp6ijuikKG45YPhEsgPmb6', 1, '2021-09-01 17:59:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `vs_mimetypes`
--
ALTER TABLE `vs_mimetypes`
  ADD PRIMARY KEY (`mid`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `vs_roles`
--
ALTER TABLE `vs_roles`
  ADD PRIMARY KEY (`rid`);

--
-- Indices de la tabla `vs_storage`
--
ALTER TABLE `vs_storage`
  ADD PRIMARY KEY (`sid`);

--
-- Indices de la tabla `vs_users`
--
ALTER TABLE `vs_users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `vs_mimetypes`
--
ALTER TABLE `vs_mimetypes`
  MODIFY `mid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `vs_roles`
--
ALTER TABLE `vs_roles`
  MODIFY `rid` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `vs_storage`
--
ALTER TABLE `vs_storage`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=444;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
