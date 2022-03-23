-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 15, 2017 at 04:53 PM
-- Server version: 5.5.55-0ubuntu0.14.04.1
-- PHP Version: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `ejq_localtest`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=133 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 168),
(7, 1, NULL, NULL, 'Pages', 2, 5),
(8, 7, NULL, NULL, 'display', 3, 4),
(20, 1, NULL, NULL, 'AclExtras', 6, 7),
(21, 1, NULL, NULL, 'Groups', 8, 17),
(22, 21, NULL, NULL, 'create', 9, 10),
(23, 21, NULL, NULL, 'update', 11, 12),
(24, 21, NULL, NULL, 'delete', 13, 14),
(25, 1, NULL, NULL, 'Main', 18, 27),
(26, 25, NULL, NULL, 'index', 19, 20),
(27, 1, NULL, NULL, 'Users', 28, 51),
(28, 27, NULL, NULL, 'create', 29, 30),
(29, 27, NULL, NULL, 'update', 31, 32),
(30, 27, NULL, NULL, 'delete', 33, 34),
(31, 27, NULL, NULL, 'login', 35, 36),
(32, 27, NULL, NULL, 'logout', 37, 38),
(33, 1, NULL, NULL, 'DebugKit', 52, 53),
(34, 21, NULL, NULL, 'index', 15, 16),
(35, 27, NULL, NULL, 'index', 39, 40),
(46, 1, NULL, NULL, 'ServiceTypes', 54, 63),
(47, 46, NULL, NULL, 'index', 55, 56),
(48, 46, NULL, NULL, 'create', 57, 58),
(49, 46, NULL, NULL, 'update', 59, 60),
(50, 46, NULL, NULL, 'delete', 61, 62),
(51, 1, NULL, NULL, 'PaymentMethods', 64, 73),
(52, 51, NULL, NULL, 'index', 65, 66),
(53, 51, NULL, NULL, 'create', 67, 68),
(54, 51, NULL, NULL, 'update', 69, 70),
(55, 51, NULL, NULL, 'delete', 71, 72),
(56, 1, NULL, NULL, 'MetaProviders', 74, 85),
(57, 56, NULL, NULL, 'index', 75, 76),
(58, 56, NULL, NULL, 'create', 77, 78),
(59, 56, NULL, NULL, 'update', 79, 80),
(60, 56, NULL, NULL, 'delete', 81, 82),
(63, 1, NULL, NULL, 'MetaMarketplaces', 86, 99),
(64, 63, NULL, NULL, 'index', 87, 88),
(65, 63, NULL, NULL, 'create', 89, 90),
(66, 63, NULL, NULL, 'update', 91, 92),
(67, 63, NULL, NULL, 'delete', 93, 94),
(68, 1, NULL, NULL, 'Marketplaces', 100, 119),
(69, 68, NULL, NULL, 'detail', 101, 102),
(71, 63, NULL, NULL, 'detail', 95, 96),
(72, 63, NULL, NULL, 'publish', 97, 98),
(73, 27, NULL, NULL, 'register', 41, 42),
(74, 27, NULL, NULL, 'confirm', 43, 44),
(75, 27, NULL, NULL, 'thanks', 45, 46),
(76, 56, NULL, NULL, 'detail', 83, 84),
(77, 1, NULL, NULL, 'MetaConsumers', 120, 131),
(78, 77, NULL, NULL, 'index', 121, 122),
(79, 77, NULL, NULL, 'create', 123, 124),
(80, 77, NULL, NULL, 'detail', 125, 126),
(81, 77, NULL, NULL, 'update', 127, 128),
(82, 77, NULL, NULL, 'delete', 129, 130),
(85, 25, NULL, NULL, 'metamarketplaces', 21, 22),
(91, 1, NULL, NULL, 'Providers', 132, 141),
(92, 91, NULL, NULL, 'profile', 133, 134),
(93, 91, NULL, NULL, 'update_service_types', 135, 136),
(94, 25, NULL, NULL, 'marketplaces', 23, 24),
(96, 68, NULL, NULL, 'identify_consumer', 103, 104),
(97, 68, NULL, NULL, 'identify_provider', 105, 106),
(98, 68, NULL, NULL, 'register_provider', 107, 108),
(99, 68, NULL, NULL, 'register_consumer', 109, 110),
(102, 1, NULL, NULL, 'Consumers', 142, 147),
(103, 102, NULL, NULL, 'profile', 143, 144),
(105, 27, NULL, NULL, 'profile', 47, 48),
(106, 68, NULL, NULL, 'consumer_login', 111, 112),
(107, 68, NULL, NULL, 'provider_login', 113, 114),
(108, 27, NULL, NULL, 'recover_password', 49, 50),
(109, 1, NULL, NULL, 'Demands', 148, 159),
(110, 109, NULL, NULL, 'accept', 149, 150),
(111, 109, NULL, NULL, 'cancel', 151, 152),
(112, 109, NULL, NULL, 'hire', 153, 154),
(114, 91, NULL, NULL, 'update_online_status', 137, 138),
(115, 91, NULL, NULL, 'update_weekdays', 139, 140),
(116, 109, NULL, NULL, 'evaluate', 155, 156),
(117, 109, NULL, NULL, 'supply', 157, 158),
(118, 25, NULL, NULL, 'change_language', 25, 26),
(120, 68, NULL, NULL, 'inform_consumer_identification', 115, 116),
(121, 68, NULL, NULL, 'inform_provider_identification', 117, 118),
(122, 1, NULL, NULL, 'Curators', 160, 167),
(123, 122, NULL, NULL, 'inform_identification', 161, 162),
(124, 122, NULL, NULL, 'login', 163, 164),
(126, 122, NULL, NULL, 'register', 165, 166),
(132, 102, NULL, NULL, 'update_service_types', 145, 146);

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

DROP TABLE IF EXISTS `administrators`;
CREATE TABLE IF NOT EXISTS `administrators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `administrators`
--

INSERT INTO `administrators` (`id`, `created`, `modified`, `active`, `removed`, `marketplace_id`, `user_id`, `name`, `slug`, `identification`) VALUES
(1, '2017-06-12 02:22:22', '2017-06-12 02:22:22', 1, 0, 1, 47, 'Max', 'max', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=107 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'group', 1, NULL, 1, 10),
(2, 1, 'User', 1, NULL, 2, 3),
(9, NULL, 'Group', 2, NULL, 11, 100),
(13, 1, 'User', 4, NULL, 4, 5),
(14, 1, 'User', 5, NULL, 6, 7),
(15, 1, 'User', 6, NULL, 8, 9),
(17, 9, 'User', 8, NULL, 12, 13),
(20, 9, 'User', 11, NULL, 14, 15),
(22, 9, 'User', 13, NULL, 16, 17),
(26, 9, 'User', 17, NULL, 18, 19),
(27, 9, 'User', 18, NULL, 20, 21),
(28, 9, 'User', 19, NULL, 22, 23),
(29, 9, 'User', 20, NULL, 24, 25),
(30, 9, 'User', 21, NULL, 26, 27),
(31, 9, 'User', 22, NULL, 28, 29),
(32, 9, 'User', 23, NULL, 30, 31),
(34, 9, 'User', 25, NULL, 32, 33),
(35, 9, 'User', 26, NULL, 34, 35),
(36, 9, 'User', 27, NULL, 36, 37),
(37, 9, 'User', 28, NULL, 38, 39),
(39, 9, 'User', 30, NULL, 40, 41),
(40, 9, 'User', 31, NULL, 42, 43),
(41, 9, 'User', 32, NULL, 44, 45),
(42, 9, 'User', 33, NULL, 46, 47),
(48, 9, 'User', 39, NULL, 48, 49),
(49, 9, 'User', 40, NULL, 50, 51),
(50, 9, 'User', 41, NULL, 52, 53),
(51, 9, 'User', 42, NULL, 54, 55),
(52, 9, 'User', 43, NULL, 56, 57),
(54, 9, 'User', 45, NULL, 58, 59),
(55, 9, 'User', 46, NULL, 60, 61),
(56, 9, 'User', 47, NULL, 62, 63),
(57, 9, 'User', 48, NULL, 64, 65),
(58, 9, 'User', 48, NULL, 66, 67),
(59, 9, 'User', 49, NULL, 68, 69),
(60, 9, 'User', 50, NULL, 70, 71),
(61, 9, 'User', 51, NULL, 72, 73),
(62, 9, 'User', 52, NULL, 74, 75),
(63, 9, 'User', 53, NULL, 76, 77),
(64, 9, 'User', 54, NULL, 78, 79),
(65, 9, 'User', 55, NULL, 80, 81),
(66, 9, 'User', 56, NULL, 82, 83),
(71, 9, 'User', 61, NULL, 84, 85),
(76, 9, 'User', 66, NULL, 86, 87),
(78, 9, 'User', 68, NULL, 88, 89),
(95, 9, 'User', 84, NULL, 90, 91),
(96, 9, 'User', 85, NULL, 92, 93),
(97, 9, 'User', 86, NULL, 94, 95),
(98, 9, 'User', 87, NULL, 96, 97),
(106, 9, 'User', 95, NULL, 98, 99);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=80 ;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(24, 9, 1, '-1', '-1', '-1', '-1'),
(41, 9, 92, '1', '1', '1', '1'),
(43, 9, 93, '1', '1', '1', '1'),
(44, 9, 103, '1', '1', '1', '1'),
(47, 9, 114, '1', '1', '1', '1'),
(48, 9, 115, '1', '1', '1', '1'),
(50, 9, 120, '1', '1', '1', '1'),
(51, 9, 121, '1', '1', '1', '1'),
(52, 9, 85, '-1', '-1', '-1', '-1'),
(54, 9, 78, '-1', '-1', '-1', '-1'),
(55, 9, 79, '-1', '-1', '-1', '-1'),
(56, 9, 82, '-1', '-1', '-1', '-1'),
(57, 9, 80, '-1', '-1', '-1', '-1'),
(58, 9, 81, '-1', '-1', '-1', '-1'),
(59, 9, 64, '-1', '-1', '-1', '-1'),
(60, 9, 65, '-1', '-1', '-1', '-1'),
(61, 9, 67, '-1', '-1', '-1', '-1'),
(62, 9, 71, '-1', '-1', '-1', '-1'),
(63, 9, 66, '-1', '-1', '-1', '-1'),
(64, 9, 72, '-1', '-1', '-1', '-1'),
(66, 9, 57, '-1', '-1', '-1', '-1'),
(67, 9, 58, '-1', '-1', '-1', '-1'),
(68, 9, 60, '-1', '-1', '-1', '-1'),
(69, 9, 76, '-1', '-1', '-1', '-1'),
(70, 9, 59, '-1', '-1', '-1', '-1'),
(71, 9, 47, '-1', '-1', '-1', '-1'),
(72, 9, 48, '-1', '-1', '-1', '-1'),
(73, 9, 49, '-1', '-1', '-1', '-1'),
(74, 9, 50, '-1', '-1', '-1', '-1'),
(75, 9, 52, '-1', '-1', '-1', '-1'),
(76, 9, 53, '-1', '-1', '-1', '-1'),
(77, 9, 54, '-1', '-1', '-1', '-1'),
(78, 9, 55, '-1', '-1', '-1', '-1'),
(79, 9, 132, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `consumers`
--

DROP TABLE IF EXISTS `consumers`;
CREATE TABLE IF NOT EXISTS `consumers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `meta_consumer_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `consumers`
--

INSERT INTO `consumers` (`id`, `created`, `modified`, `active`, `removed`, `marketplace_id`, `meta_consumer_id`, `user_id`, `name`, `slug`, `identification`, `payment_method_id`) VALUES
(1, '2017-06-12 14:22:57', '2017-06-15 11:05:41', 1, 0, 1, 1, 85, 'Holly', NULL, 'ffff', NULL),
(2, '2017-06-12 14:29:39', '2017-06-15 14:11:34', 1, 0, 1, 1, 84, 'Helga', NULL, 'hehehehe', NULL),
(7, '2017-06-14 11:42:26', '2017-06-15 16:45:57', 1, 0, 1, 1, 95, 'Homer', NULL, 'hoomeoemeoe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `curators`
--

DROP TABLE IF EXISTS `curators`;
CREATE TABLE IF NOT EXISTS `curators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `curators`
--

INSERT INTO `curators` (`id`, `created`, `modified`, `active`, `removed`, `user_id`, `name`, `slug`, `identification`) VALUES
(1, '2016-11-15 16:08:28', '2016-11-15 16:08:28', 1, 0, 61, 'Curador', NULL, '1234141324252'),
(2, '2016-11-15 17:20:16', '2016-11-15 17:20:16', 1, 0, 66, 'Ted', NULL, '7367367367763737-73'),
(3, '2016-11-17 14:24:18', '2016-11-17 14:24:18', 1, 0, 1, 'Super', NULL, '00000000000000000000000000');

-- --------------------------------------------------------

--
-- Table structure for table `demands`
--

DROP TABLE IF EXISTS `demands`;
CREATE TABLE IF NOT EXISTS `demands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `status` char(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_places`
--

DROP TABLE IF EXISTS `demands_places`;
CREATE TABLE IF NOT EXISTS `demands_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demand_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_requests`
--

DROP TABLE IF EXISTS `demands_requests`;
CREATE TABLE IF NOT EXISTS `demands_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_schedules`
--

DROP TABLE IF EXISTS `demands_schedules`;
CREATE TABLE IF NOT EXISTS `demands_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `schedule` datetime DEFAULT NULL,
  `schedule_date` datetime DEFAULT NULL,
  `schedule_period_begin` datetime DEFAULT NULL,
  `schedule_period_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_service_types`
--

DROP TABLE IF EXISTS `demands_service_types`;
CREATE TABLE IF NOT EXISTS `demands_service_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demand_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `price` decimal(7,2) DEFAULT NULL,
  `consumer_evaluation_value` int(11) DEFAULT NULL,
  `pending_consumer_evaluation` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_tenders`
--

DROP TABLE IF EXISTS `demands_tenders`;
CREATE TABLE IF NOT EXISTS `demands_tenders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `demands_tenders_files`
--

DROP TABLE IF EXISTS `demands_tenders_files`;
CREATE TABLE IF NOT EXISTS `demands_tenders_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `demand_tender_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phase` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `created`, `modified`, `active`, `removed`, `name`, `alias`) VALUES
(1, '2016-02-19 14:59:19', '2016-02-19 14:59:19', 1, 0, 'SuperUser', 'Super Usuário'),
(2, '2016-05-10 01:13:23', '2016-05-10 01:13:23', 1, 0, 'User', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `marketplaces`
--

DROP TABLE IF EXISTS `marketplaces`;
CREATE TABLE IF NOT EXISTS `marketplaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `curator_id` int(11) DEFAULT NULL,
  `meta_marketplace_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo_image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover_image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purpose` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `marketplaces`
--

INSERT INTO `marketplaces` (`id`, `created`, `modified`, `active`, `removed`, `curator_id`, `meta_marketplace_id`, `name`, `slug`, `logo_image`, `cover_image`, `purpose`, `description`) VALUES
(1, '2017-06-08 16:23:12', '2017-06-08 16:23:12', 1, 0, NULL, 1, 'Easy Job Quote ', 'easy-job-quote', 'logo.png', 'home-owner-contractor-bid.jpg', 'We Can Help You To Find a Contractor', 'We’ve done the research for you! The construction companies bidding on your projects are local contractors whose background information has been researched and is available for your review. Previous customers’ reviews of their work, integrity, professionalism and experience also help you make your decision.');

-- --------------------------------------------------------

--
-- Table structure for table `marketplaces_service_types`
--

DROP TABLE IF EXISTS `marketplaces_service_types`;
CREATE TABLE IF NOT EXISTS `marketplaces_service_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marketplace_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `marketplaces_service_types`
--

INSERT INTO `marketplaces_service_types` (`id`, `marketplace_id`, `service_type_id`) VALUES
(7, 1, 2),
(8, 1, 3),
(9, 1, 1),
(10, 1, 4),
(11, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `meta_consumers`
--

DROP TABLE IF EXISTS `meta_consumers`;
CREATE TABLE IF NOT EXISTS `meta_consumers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `meta_marketplace_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `meta_consumers`
--

INSERT INTO `meta_consumers` (`id`, `created`, `modified`, `active`, `removed`, `meta_marketplace_id`, `name`, `slug`, `identification`, `payment_method_id`) VALUES
(1, '2017-06-02 21:43:35', '2017-06-02 21:43:35', 1, 0, 1, 'Home Owner', 'home-owner', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meta_marketplaces`
--

DROP TABLE IF EXISTS `meta_marketplaces`;
CREATE TABLE IF NOT EXISTS `meta_marketplaces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `curator_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logo_image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cover_image` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purpose` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `status` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `meta_marketplaces`
--

INSERT INTO `meta_marketplaces` (`id`, `created`, `modified`, `active`, `removed`, `curator_id`, `name`, `slug`, `logo_image`, `cover_image`, `purpose`, `description`, `status`) VALUES
(1, '2017-06-02 21:40:37', '2017-06-02 21:40:37', 1, 0, 3, 'Easy Job Quote ', 'easy-job-quote', 'logo.png', 'home-owner-contractor-bid.jpg', 'We Can Help You To Find a Contractor', 'We’ve done the research for you! The construction companies bidding on your projects are local contractors whose background information has been researched and is available for your review. Previous customers’ reviews of their work, integrity, professionalism and experience also help you make your decision.', '');

-- --------------------------------------------------------

--
-- Table structure for table `meta_providers`
--

DROP TABLE IF EXISTS `meta_providers`;
CREATE TABLE IF NOT EXISTS `meta_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `meta_marketplace_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marketplace_credential` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `unit_of_service` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `meta_providers`
--

INSERT INTO `meta_providers` (`id`, `created`, `modified`, `active`, `removed`, `meta_marketplace_id`, `name`, `slug`, `service_type_id`, `payment_method_id`, `identification`, `marketplace_credential`, `zip_code`, `price`, `unit_of_service`) VALUES
(1, '2017-06-02 21:43:26', '2017-06-02 21:43:26', 1, 0, 1, 'Estimator', 'estimator', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '2017-06-08 16:18:48', '2017-06-08 16:18:48', 1, 0, 1, 'Contractor', 'contractor', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `created`, `modified`, `active`, `removed`, `name`, `slug`) VALUES
(1, '2016-05-13 18:02:59', '2016-05-13 18:02:59', 1, 0, 'Cartão de Crédito VISA', 'cartao-de-credito-visa'),
(2, '2016-05-20 10:08:32', '2016-05-20 10:08:32', 1, 0, 'Cartão de Crédito Mastercard', 'cartao-de-credito-mastercard');

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

DROP TABLE IF EXISTS `places`;
CREATE TABLE IF NOT EXISTS `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `meta_marketplace_id` int(11) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `belongs_to_place` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `places_providers`
--

DROP TABLE IF EXISTS `places_providers`;
CREATE TABLE IF NOT EXISTS `places_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

DROP TABLE IF EXISTS `providers`;
CREATE TABLE IF NOT EXISTS `providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `meta_provider_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `online` tinyint(1) DEFAULT NULL,
  `qualified` tinyint(1) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `identification` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marketplace_credential` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip_code` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(9,2) DEFAULT NULL,
  `unit_of_service` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `providers`
--

INSERT INTO `providers` (`id`, `created`, `modified`, `active`, `removed`, `marketplace_id`, `meta_provider_id`, `user_id`, `name`, `slug`, `online`, `qualified`, `payment_method_id`, `identification`, `marketplace_credential`, `zip_code`, `price`, `unit_of_service`) VALUES
(1, '2017-06-12 14:23:14', '2017-06-12 14:23:26', 1, 0, 1, 1, 87, 'Yuri', 'yuri', NULL, NULL, NULL, 'yyyyyy', NULL, NULL, NULL, NULL),
(2, '2017-06-12 14:23:36', '2017-06-12 14:23:49', 1, 0, 1, 1, 8, 'Milena', 'milena', NULL, NULL, NULL, 'mmmmmmm', NULL, NULL, NULL, NULL),
(3, '2017-06-12 14:24:11', '2017-06-12 14:24:22', 1, 0, 1, 1, 11, 'Pascoal', 'pascoal', NULL, NULL, NULL, 'papapapaa', NULL, NULL, NULL, NULL),
(4, '2017-06-12 14:27:08', '2017-06-12 14:27:19', 1, 0, 1, 1, 17, 'Lewis', 'lewis', NULL, NULL, NULL, 'llllelelelee', NULL, NULL, NULL, NULL),
(5, '2017-06-12 14:27:42', '2017-06-12 14:27:59', 1, 0, 1, 1, 20, 'Martin', 'martin', NULL, NULL, NULL, 'mamamama', NULL, NULL, NULL, NULL),
(6, '2017-06-12 14:28:29', '2017-06-12 14:28:45', 1, 0, 1, 1, 23, 'Teco', 'teco', NULL, NULL, NULL, 'tetete', NULL, NULL, NULL, NULL),
(7, '2017-06-12 14:29:05', '2017-06-12 14:29:26', 1, 0, 1, 1, 21, 'Luke', 'luke', NULL, NULL, NULL, 'luuuuu', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `providers_service_types`
--

DROP TABLE IF EXISTS `providers_service_types`;
CREATE TABLE IF NOT EXISTS `providers_service_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `evaluations_count` int(10) unsigned DEFAULT NULL,
  `evaluations_average` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `providers_service_types`
--

INSERT INTO `providers_service_types` (`id`, `provider_id`, `service_type_id`, `evaluations_count`, `evaluations_average`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 2, 5, NULL, NULL),
(7, 3, 4, NULL, NULL),
(8, 3, 5, NULL, NULL),
(9, 4, 2, NULL, NULL),
(10, 4, 3, NULL, NULL),
(11, 4, 4, NULL, NULL),
(12, 5, 1, NULL, NULL),
(13, 5, 2, NULL, NULL),
(14, 5, 3, NULL, NULL),
(15, 5, 5, NULL, NULL),
(16, 6, 1, NULL, NULL),
(17, 6, 3, NULL, NULL),
(18, 6, 5, NULL, NULL),
(19, 7, 1, NULL, NULL),
(20, 7, 2, NULL, NULL),
(21, 7, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `providers_weekdays`
--

DROP TABLE IF EXISTS `providers_weekdays`;
CREATE TABLE IF NOT EXISTS `providers_weekdays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `weekdays` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service_types`
--

DROP TABLE IF EXISTS `service_types`;
CREATE TABLE IF NOT EXISTS `service_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `meta_marketplace_id` int(11) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `price` decimal(7,2) DEFAULT NULL,
  `weekdays_criterion` tinyint(1) DEFAULT NULL,
  `online_criterion` tinyint(1) DEFAULT NULL,
  `qualified_criterion` tinyint(1) DEFAULT NULL,
  `scheduled_criterion` tinyint(4) DEFAULT NULL COMMENT '#TEMP#',
  `scheduled_date_criterion` tinyint(1) DEFAULT NULL,
  `scheduled_period_criterion` tinyint(1) DEFAULT NULL,
  `places_criterion` tinyint(1) DEFAULT NULL,
  `consumer_evaluation` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `service_types`
--

INSERT INTO `service_types` (`id`, `created`, `modified`, `active`, `removed`, `meta_marketplace_id`, `name`, `slug`, `description`, `price`, `weekdays_criterion`, `online_criterion`, `qualified_criterion`, `scheduled_criterion`, `scheduled_date_criterion`, `scheduled_period_criterion`, `places_criterion`, `consumer_evaluation`) VALUES
(1, '2017-06-02 21:41:37', '2017-06-02 21:41:37', 1, 0, 1, 'General Contractor', 'general-contractor', 'General Contractor', NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL),
(2, '2017-06-02 21:41:58', '2017-06-02 21:41:58', 1, 0, 1, 'Countertops', 'countertops', 'Countertops', NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL),
(3, '2017-06-02 21:42:50', '2017-06-02 21:42:50', 1, 0, 1, 'Electrical', 'electrical', 'Electrical', NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL),
(4, '2017-06-02 21:43:06', '2017-06-02 21:43:06', 1, 0, 1, 'Plumbing', 'plumbing', 'Plumbing', NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL),
(5, '2017-06-02 21:47:04', '2017-06-02 21:47:04', 1, 0, 1, 'Roofer', 'roofer', 'Roofer', NULL, 0, 0, 1, 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_types_meta_providers`
--

DROP TABLE IF EXISTS `service_types_meta_providers`;
CREATE TABLE IF NOT EXISTS `service_types_meta_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_type_id` int(11) NOT NULL,
  `meta_provider_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `suitabilities`
--

DROP TABLE IF EXISTS `suitabilities`;
CREATE TABLE IF NOT EXISTS `suitabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `demand_id` int(11) NOT NULL,
  `suitability` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) DEFAULT '1',
  `removed` int(11) DEFAULT '0',
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=96 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created`, `modified`, `active`, `removed`, `confirmed`, `group_id`, `name`, `email`, `password`) VALUES
(1, '2016-05-05 18:27:00', '2016-05-05 18:27:00', 1, 0, 1, 1, 'Super', 'super@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(4, '2016-05-10 01:14:47', '2016-05-10 01:14:47', 1, 0, 1, 1, 'Rodrigo Machado', 'rodrigo.machado@alemdisso.com.br', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(5, '2016-05-10 01:15:05', '2016-05-10 01:15:05', 1, 0, 1, 1, 'Carlos Nepomuceno', 'cnepomu@gmail.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(6, '2016-05-10 01:15:30', '2016-05-10 01:15:30', 1, 0, 1, 1, 'Haroldo', 'haroldoja@gmail.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(8, '2016-06-04 21:09:12', '2016-06-04 21:09:12', 1, 0, 1, 2, 'Milena', 'milena@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(11, '2016-06-13 09:51:01', '2016-06-13 09:51:01', 1, 0, 1, 2, 'Pascoal', 'pascoal@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(13, '2016-06-14 16:11:54', '2016-06-14 16:11:54', 1, 0, 1, 2, 'Nelson', 'nelson@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(17, '2016-06-16 09:58:36', '2016-06-16 09:58:36', 1, 0, 1, 2, 'Lewis', 'lewis@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(18, '2016-06-16 09:59:32', '2016-06-16 09:59:32', 1, 0, 1, 2, 'Carrol', 'carrol@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(19, '2016-06-16 10:01:00', '2016-06-16 10:01:00', 1, 0, 1, 2, 'Curren', 'curren@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(20, '2016-06-16 10:06:26', '2016-06-16 10:06:26', 1, 0, 1, 2, 'Martin', 'martin@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(21, '2016-06-16 10:08:16', '2016-06-16 10:08:16', 1, 0, 1, 2, 'Luke', 'luke@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(22, '2016-06-16 10:09:24', '2016-06-16 10:09:24', 1, 0, 1, 2, 'Fabio', 'fabio@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(23, '2016-06-16 10:12:49', '2016-06-16 10:12:49', 1, 0, 1, 2, 'Teco', 'teco@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(25, '2016-08-24 14:41:26', '2016-08-24 14:41:26', 1, 0, 1, 2, 'Bob', 'bob@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(26, '2016-08-24 14:45:26', '2016-08-24 14:45:26', 1, 0, 1, 2, 'Bruno', 'bruno@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(27, '2016-08-24 15:21:10', '2016-08-24 15:21:10', 1, 0, 1, 2, 'Albus', 'albus@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(28, '2016-08-24 15:23:06', '2016-08-24 15:23:06', 1, 0, 1, 2, 'Camilo', 'camilo@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(30, '2016-08-24 15:27:20', '2016-08-24 15:27:20', 1, 0, 1, 2, 'Guto', 'guto@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(31, '2016-08-24 15:28:06', '2016-08-24 15:28:06', 1, 0, 1, 2, 'Ines', 'ines@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(32, '2016-08-24 15:28:33', '2016-08-24 15:28:33', 1, 0, 1, 2, 'Joana', 'joana@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(33, '2016-08-24 15:31:26', '2016-08-24 15:31:26', 1, 0, 1, 2, 'Julio', 'julio@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(39, '2016-09-29 19:30:49', '2016-09-29 19:30:49', 1, 0, 1, 2, 'Duda', 'duda@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(40, '2016-09-29 19:32:25', '2016-09-29 19:32:25', 1, 0, 1, 2, 'Maria', 'maria@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(41, '2016-10-17 11:16:02', '2016-10-17 11:16:02', 1, 0, 1, 2, 'Ian', 'ian@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(42, '2016-10-31 18:44:25', '2016-10-31 18:44:25', 1, 0, 1, 2, 'Bart', 'bart@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(43, '2016-10-31 20:41:19', '2016-10-31 20:41:19', 1, 0, 1, 2, 'Abel', 'abel@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(45, '2016-11-07 10:08:00', '2016-11-07 10:08:00', 1, 0, 1, 2, 'Ben', 'ben@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(46, '2016-11-07 11:03:41', '2016-11-07 11:03:41', 1, 0, 1, 2, 'Alex', 'alex@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(47, '2016-11-07 11:35:55', '2016-11-07 11:35:55', 1, 0, 1, 2, 'Max', 'max@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(53, '2016-11-08 11:07:28', '2016-11-08 11:07:28', 1, 0, 1, 2, 'Bia', 'bia@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(54, '2016-11-08 13:20:17', '2016-11-08 13:20:17', 1, 0, 1, 2, 'Otto', 'otto@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(55, '2016-11-08 13:33:16', '2016-11-08 13:33:16', 1, 0, 1, 2, 'Paul', 'paul@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(56, '2016-11-11 15:18:06', '2016-11-11 15:18:06', 1, 0, 1, 2, 'Don', 'don@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(61, '2016-11-14 18:44:19', '2016-11-14 18:44:19', 1, 0, 1, 2, 'Curador', 'curador@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(66, '2016-11-15 17:20:15', '2016-11-15 17:20:15', 1, 0, 1, 2, 'Ted', 'ted@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(84, '2017-06-09 09:09:10', '2017-06-09 09:09:10', 1, 0, 1, 2, 'Helga', 'helga@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(85, '2017-06-09 09:18:13', '2017-06-09 09:18:13', 1, 0, 1, 2, 'Holly', 'holly@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(86, '2017-06-09 09:22:43', '2017-06-09 09:22:43', 1, 0, 1, 2, 'Sam', 'sam@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(87, '2017-06-09 09:52:06', '2017-06-09 09:52:06', 1, 0, 1, 2, 'Yuri', 'yuri@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48'),
(95, '2017-06-14 11:42:26', '2017-06-14 11:42:26', 1, 0, 1, 2, 'Homer', 'homer@easyjobquote.com', 'db5cf4a2470074d8b0681faff2a3291b32fb4f48');
