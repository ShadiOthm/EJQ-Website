RENAME TABLE  `demands_tenders_bids` TO  `bids` ;
ALTER TABLE  `bids` CHANGE  `demand_tender_id`  `tender_id` INT( 11 ) NULL DEFAULT NULL ;
RENAME TABLE  `demands_tenders_bids_conditions` TO `bids_conditions` ;
ALTER TABLE  `bids_conditions` CHANGE  `demand_tender_bid_id`  `bid_id` INT( 11 ) NULL DEFAULT NULL ;
RENAME TABLE  `demands_tenders_conditions` TO  `terms_conditions` ;
ALTER TABLE  `terms_conditions` CHANGE  `demand_tender_id`  `tender_id` INT( 11 ) NULL DEFAULT NULL ;
RENAME TABLE  `demands_tenders` TO  `tenders` ;
RENAME TABLE  `demands_tenders_files` TO  `tenders_files` ;
ALTER TABLE  `tenders_files` CHANGE  `demand_tender_id`  `tender_id` INT( 11 ) NULL DEFAULT NULL ;
RENAME TABLE  `demands_requests` TO  `requests` ;

ALTER TABLE  `bids` ADD  `status` VARCHAR( 256 ) NULL DEFAULT NULL AFTER  `provider_id` ;



CREATE TABLE IF NOT EXISTS `compliances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `removed` int(11) NOT NULL DEFAULT '0',
  `term_condition_id` INT NOT NULL, 
  `tender_id` int(11) DEFAULT NULL,
  `bid_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `compliant` tinyint(1) NULL DEFAULT NULL,
  `amendment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


