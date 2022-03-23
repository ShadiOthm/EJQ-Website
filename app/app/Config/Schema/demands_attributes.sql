-- --------------------------------------------------------

--
-- Table structure for table `demands_attributes`
--

DROP TABLE IF EXISTS `demands_attributes`;
CREATE TABLE IF NOT EXISTS `demands_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
