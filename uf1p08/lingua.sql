-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Temps de generació: 19-05-2021 a les 20:32:47
-- Versió del servidor: 10.4.17-MariaDB
-- Versió de PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de dades: `lingua`
--

-- --------------------------------------------------------

--
-- Estructura de la taula `idiomes`
--

CREATE TABLE `idiomes` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Bolcament de dades per a la taula `idiomes`
--

INSERT INTO `idiomes` (`id`, `nom`) VALUES
(4, 'Alemany'),
(2, 'Anglès'),
(5, 'Catalan'),
(1, 'Espanyol'),
(3, 'Francès');

-- --------------------------------------------------------

--
-- Estructura de la taula `usuaris`
--

CREATE TABLE `usuaris` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `idioma_natiu` int(11) NOT NULL,
  `idioma_aprendre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Bolcament de dades per a la taula `usuaris`
--

INSERT INTO `usuaris` (`email`, `password`, `nom`, `idioma_natiu`, `idioma_aprendre`) VALUES
('adrian.ocania@gmail.com', '0cc175b9c0f1b6a831c399e269772661', 'Ocania', 1, 2),
('redu.nati@gmail.com', '0cc175b9c0f1b6a831c399e269772661', 'Redu', 1, 2);

--
-- Índexs per a les taules bolcades
--

--
-- Índexs per a la taula `idiomes`
--
ALTER TABLE `idiomes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idiomes_nom_uindex` (`nom`),
  ADD UNIQUE KEY `idiomes_id_uindex` (`id`);

--
-- Índexs per a la taula `usuaris`
--
ALTER TABLE `usuaris`
  ADD PRIMARY KEY (`email`),
  ADD KEY `FK_idioma_aprendre` (`idioma_aprendre`),
  ADD KEY `FK_idioma_natiu` (`idioma_natiu`);

--
-- AUTO_INCREMENT per les taules bolcades
--

--
-- AUTO_INCREMENT per la taula `idiomes`
--
ALTER TABLE `idiomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restriccions per a les taules bolcades
--

--
-- Restriccions per a la taula `usuaris`
--
ALTER TABLE `usuaris`
  ADD CONSTRAINT `FK_idioma_aprendre` FOREIGN KEY (`idioma_aprendre`) REFERENCES `idiomes` (`id`),
  ADD CONSTRAINT `FK_idioma_natiu` FOREIGN KEY (`idioma_natiu`) REFERENCES `idiomes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
