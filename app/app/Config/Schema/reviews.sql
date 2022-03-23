DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `job_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `consumer_id` int(11) DEFAULT NULL,
  `ponctuality_comment` text COLLATE utf8_unicode_ci,
  `ponctuality_rating` int(10) unsigned DEFAULT NULL,
  `behaviour_comment` text COLLATE utf8_unicode_ci,
  `behaviour_rating` int(10) unsigned DEFAULT NULL,
  `cleanliness_comment` text COLLATE utf8_unicode_ci,
  `cleanliness_rating` int(10) unsigned DEFAULT NULL,
  `quality_of_work_comment` text COLLATE utf8_unicode_ci,
  `quality_of_work_rating` int(10) unsigned DEFAULT NULL,
  `likelihood_to_recommend_comment` text COLLATE utf8_unicode_ci,
  `likelihood_to_recommend_rating` int(10) unsigned DEFAULT NULL,
  `overall_rating` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;


ALTER TABLE  `reviews` CHANGE  `ponctuality_comment`  `punctuality_comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE  `ponctuality_rating`  `punctuality_rating` INT( 10 ) UNSIGNED NULL DEFAULT NULL ;
