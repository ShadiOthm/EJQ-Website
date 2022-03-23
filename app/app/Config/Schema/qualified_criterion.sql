ALTER TABLE  `service_types` ADD  `qualified_criterion` TINYINT NULL AFTER  `online_criterion` ;
ALTER TABLE  `providers` ADD  `qualified` TINYINT NULL AFTER  `online` ;

