ALTER TABLE `#__djcf_items`  		           
	ADD `post_code` varchar(55) NOT NULL,
	ADD `video` text NOT NULL, 
	ADD `website` varchar(255) NOT NULL,
	ADD `ip_address` varchar(55) NOT NULL,
	ADD `currency` varchar(55) NOT NULL;
	
ALTER TABLE `#__djcf_categories` 
	ADD `access` int(11) NOT NULL;
	
ALTER TABLE `#__djcf_days` 
	ADD `price_renew` FLOAT( 12, 2 ) NOT NULL ;
	
ALTER TABLE `#__djcf_fields` 
	ADD `description` TEXT NOT NULL;
	
ALTER TABLE `#__djcf_fields_values` 
	ADD `value_date` DATE NOT NULL;
	
CREATE TABLE IF NOT EXISTS `#__djcf_categories_groups` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `cat_id` INT NOT NULL ,
  `group_id` INT NOT NULL ,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;	 