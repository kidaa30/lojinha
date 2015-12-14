ALTER TABLE `#__djcf_categories` 
	ADD `theme` varchar(255) NOT NULL;	
	
ALTER TABLE `#__djcf_fields`
	ADD `in_item` int(11) NOT NULL default '0', 
	ADD `source` int(11) NOT NULL,
	ADD `ordering` int(11) NOT NULL;
	

ALTER TABLE `#__djcf_items` 
	ADD `email` VARCHAR( 255 ) NOT NULL ,
	ADD `token` VARCHAR( 55 ) NOT NULL; 
	


CREATE TABLE IF NOT EXISTS `#__djcf_fields_values_profile` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `value_date` DATE NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 ;	

CREATE TABLE IF NOT EXISTS `#__djcf_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `type` varchar(55) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ext` varchar(55) NOT NULL,
  `path` varchar(512) NOT NULL,
  `caption` varchar(512) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;	
	
