-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 26, 2016 at 12:55 PM
-- Server version: 5.5.49-0ubuntu0.14.04.1
-- PHP Version: 5.6.21-1+donate.sury.org~trusty+4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `metaplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

DROP TABLE IF EXISTS `acos`;
CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_acos_lft_rght` (`lft`,`rght`),
  KEY `idx_acos_alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=76 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 96),
(7, 1, NULL, NULL, 'Pages', 2, 5),
(8, 7, NULL, NULL, 'display', 3, 4),
(20, 1, NULL, NULL, 'AclExtras', 6, 7),
(21, 1, NULL, NULL, 'Groups', 8, 17),
(22, 21, NULL, NULL, 'create', 9, 10),
(23, 21, NULL, NULL, 'update', 11, 12),
(24, 21, NULL, NULL, 'delete', 13, 14),
(25, 1, NULL, NULL, 'Main', 18, 21),
(26, 25, NULL, NULL, 'index', 19, 20),
(27, 1, NULL, NULL, 'Users', 22, 41),
(28, 27, NULL, NULL, 'create', 23, 24),
(29, 27, NULL, NULL, 'update', 25, 26),
(30, 27, NULL, NULL, 'delete', 27, 28),
(31, 27, NULL, NULL, 'login', 29, 30),
(32, 27, NULL, NULL, 'logout', 31, 32),
(33, 1, NULL, NULL, 'DebugKit', 42, 43),
(34, 21, NULL, NULL, 'index', 15, 16),
(35, 27, NULL, NULL, 'index', 33, 34),
(46, 1, NULL, NULL, 'ServiceTypes', 44, 53),
(47, 46, NULL, NULL, 'index', 45, 46),
(48, 46, NULL, NULL, 'create', 47, 48),
(49, 46, NULL, NULL, 'update', 49, 50),
(50, 46, NULL, NULL, 'delete', 51, 52),
(51, 1, NULL, NULL, 'PaymentMethods', 54, 63),
(52, 51, NULL, NULL, 'index', 55, 56),
(53, 51, NULL, NULL, 'create', 57, 58),
(54, 51, NULL, NULL, 'update', 59, 60),
(55, 51, NULL, NULL, 'delete', 61, 62),
(56, 1, NULL, NULL, 'MetaProviders', 64, 75),
(57, 56, NULL, NULL, 'index', 65, 66),
(58, 56, NULL, NULL, 'create', 67, 68),
(59, 56, NULL, NULL, 'update', 69, 70),
(60, 56, NULL, NULL, 'delete', 71, 72),
(62, 56, NULL, NULL, 'add_attribute', 73, 74),
(63, 1, NULL, NULL, 'MetaMarketplaces', 76, 89),
(64, 63, NULL, NULL, 'index', 77, 78),
(65, 63, NULL, NULL, 'create', 79, 80),
(66, 63, NULL, NULL, 'update', 81, 82),
(67, 63, NULL, NULL, 'delete', 83, 84),
(68, 1, NULL, NULL, 'Marketplaces', 90, 95),
(69, 68, NULL, NULL, 'detail', 91, 92),
(70, 68, NULL, NULL, 'register_provider', 93, 94),
(71, 63, NULL, NULL, 'detail', 85, 86),
(72, 63, NULL, NULL, 'publish', 87, 88),
(73, 27, NULL, NULL, 'register', 35, 36),
(74, 27, NULL, NULL, 'confirm', 37, 38),
(75, 27, NULL, NULL, 'thanks', 39, 40);

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

DROP TABLE IF EXISTS `aros`;
CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `foreign_key` int(10) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_aros_lft_rght` (`lft`,`rght`),
  KEY `idx_aros_alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'group', 1, NULL, 1, 10),
(2, 1, 'User', 1, NULL, 2, 3),
(9, NULL, 'Group', 2, NULL, 11, 14),
(10, NULL, 'Group', 3, NULL, 15, 18),
(11, 9, 'User', 2, NULL, 12, 13),
(12, 10, 'User', 3, NULL, 16, 17),
(13, 1, 'User', 4, NULL, 4, 5),
(14, 1, 'User', 5, NULL, 6, 7),
(15, 1, 'User', 6, NULL, 8, 9);

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

DROP TABLE IF EXISTS `aros_acos`;
CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_read` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_update` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `_delete` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`),
  KEY `idx_aco_id` (`aco_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(12, 10, 47, '1', '1', '1', '1'),
(13, 10, 48, '1', '1', '1', '1'),
(14, 10, 49, '1', '1', '1', '1'),
(15, 10, 50, '1', '1', '1', '1'),
(16, 10, 52, '1', '1', '1', '1'),
(17, 10, 53, '1', '1', '1', '1'),
(18, 10, 54, '1', '1', '1', '1'),
(19, 10, 55, '1', '1', '1', '1'),
(20, 10, 57, '1', '1', '1', '1'),
(21, 10, 58, '1', '1', '1', '1'),
(22, 10, 59, '1', '1', '1', '1'),
(23, 10, 60, '1', '1', '1', '1'),
(24, 9, 1, '-1', '-1', '-1', '-1'),
(25, 10, 1, '-1', '-1', '-1', '-1'),
(26, 10, 64, '1', '1', '1', '1'),
(27, 10, 65, '1', '1', '1', '1'),
(28, 10, 66, '1', '1', '1', '1'),
(29, 10, 67, '1', '1', '1', '1'),
(30, 10, 62, '1', '1', '1', '1'),
(31, 10, 71, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `created`, `modified`, `active`, `removed`, `name`, `alias`) VALUES
(1, '2016-02-19 14:59:19', '2016-02-19 14:59:19', 1, 0, 'SuperUser', 'Super Usuário'),
(2, '2016-05-10 01:13:23', '2016-05-10 01:13:23', 1, 0, 'User', 'User'),
(3, '2016-05-10 01:13:38', '2016-05-10 01:13:38', 1, 0, 'Curator', 'Curador');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `confirmed` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created`, `modified`, `active`, `removed`, `confirmed`, `group_id`, `name`, `email`, `password`) VALUES
(1, '2016-05-05 18:27:00', '2016-05-05 18:27:00', 1, 0, 0, 1, 'Super', 'super@metaplace.com.br', '341fdc2974840cbf34015024e19279800e2f8935'),
(2, '2016-05-10 01:14:14', '2016-05-10 01:14:14', 1, 0, 0, 2, 'Usuario', 'usuario@metaesquema.com.br', '341fdc2974840cbf34015024e19279800e2f8935'),
(3, '2016-05-10 01:14:34', '2016-05-10 01:14:34', 1, 0, 0, 3, 'Curador', 'curador@metaplace.com.br', '341fdc2974840cbf34015024e19279800e2f8935'),
(4, '2016-05-10 01:14:47', '2016-05-10 01:14:47', 1, 0, 0, 1, 'Rodrigo Machado', 'rodrigo.machado@alemdisso.com.br', '341fdc2974840cbf34015024e19279800e2f8935'),
(5, '2016-05-10 01:15:05', '2016-05-10 01:15:05', 1, 0, 0, 1, 'Carlos Nepomuceno', 'cnepomu@gmail.com', '341fdc2974840cbf34015024e19279800e2f8935'),
(6, '2016-05-10 01:15:30', '2016-05-10 01:15:30', 1, 0, 0, 1, 'Haroldo', 'haroldoja@gmail.com', '341fdc2974840cbf34015024e19279800e2f8935');
