-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 14 mars 2023 à 14:18
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_easygold2`
--

-- --------------------------------------------------------

--
-- Structure de la table `alimentation_caisse`
--

DROP TABLE IF EXISTS `alimentation_caisse`;
CREATE TABLE IF NOT EXISTS `alimentation_caisse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `montant` double NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `alimentation_caisse`
--

INSERT INTO `alimentation_caisse` (`id`, `montant`, `created_at`, `type`) VALUES
(1, 7000000, '2021-12-12 03:22:42', ''),
(2, 2000000, '2021-12-07 01:25:00', ''),
(3, 5000, '2022-02-07 00:51:47', 'Dollar');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
