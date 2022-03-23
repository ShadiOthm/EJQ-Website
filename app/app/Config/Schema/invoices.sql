-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `number` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `to` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `for` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_description` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_value` decimal(8,2) unsigned NOT NULL,
  `tax_value` decimal(8,2) unsigned NOT NULL,
  `total_value` decimal(8,2) unsigned NOT NULL,
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `tender_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `type` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `status` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

