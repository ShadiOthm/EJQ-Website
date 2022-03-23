DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `marketplace_id` int(11) DEFAULT NULL,
  `demand_id` int(11) DEFAULT NULL,
  `tender_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `date_begin_home_owner` date DEFAULT NULL,
  `date_begin_contractor` date DEFAULT NULL,
  `date_end_home_owner` date DEFAULT NULL,
  `date_end_contractor` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

