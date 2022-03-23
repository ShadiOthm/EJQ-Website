ALTER TABLE  `tenders` ADD  `municipality_id` INT NULL DEFAULT NULL ;


CREATE TABLE IF NOT EXISTS `municipalities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


