-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-07-2023 a las 02:33:59
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `merci_vue`
--

--
-- Volcado de datos para la tabla `users_indicadores_datos`
--

INSERT INTO `users_indicadores_datos` (`id`, `mes`, `data_1`, `data_2`, `user_indicadore_id`, `created_at`, `updated_at`) VALUES
(1, '2023-01', '1', NULL, 4, '2023-07-17 02:04:21', '2023-07-17 02:04:21'),
(2, '2023-02', '3', NULL, 4, '2023-07-17 02:09:07', '2023-07-17 02:09:07'),
(3, '2023-03', '5', NULL, 4, '2023-07-17 02:09:39', '2023-07-17 02:09:39'),
(4, '2023-01', '7', '10.8', 1, '2023-07-17 02:11:14', '2023-07-17 02:11:14'),
(5, '2023-01', '45', '20.2', 2, '2023-07-17 02:12:21', '2023-07-17 02:12:21'),
(6, '2023-04', '5', NULL, 4, '2023-07-17 02:13:58', '2023-07-17 02:13:58'),
(7, '2023-01', '53.5', NULL, 3, '2023-07-17 02:18:59', '2023-07-17 02:18:59'),
(9, '2023-02', '15.3', '15.4', 1, '2023-07-17 02:11:14', '2023-07-17 02:11:14'),
(11, '2023-02', '54.1', NULL, 3, '2023-07-17 05:14:20', '2023-07-17 05:14:20'),
(12, '2023-02', '54', '28.3', 2, '2023-07-17 05:15:28', '2023-07-17 05:15:28'),
(13, '2023-03', '15.3', '20.8', 1, '2023-07-17 02:11:14', '2023-07-17 02:11:14'),
(14, '2023-04', '20', '25.6', 1, '2023-07-17 02:11:14', '2023-07-17 02:11:14'),
(15, '2023-03', '56', '33.6', 2, '2023-07-17 05:15:28', '2023-07-17 05:15:28'),
(16, '2023-04', '48.9', '45', 2, '2023-07-17 05:15:28', '2023-07-17 05:15:28'),
(17, '2023-03', '61.9', NULL, 3, '2023-07-17 05:14:20', '2023-07-17 05:14:20'),
(18, '2023-04', '52.4', NULL, 3, '2023-07-17 05:14:20', '2023-07-17 05:14:20');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
