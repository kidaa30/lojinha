ALTER TABLE `#__djcf_promotions` 
	ADD `ordering` int(11) NOT NULL;
	
ALTER TABLE `#__djcf_items` 
	ADD `type_id` int(11) NOT NULL,
	ADD `price_negotiable` int(11) NOT NULL,
	ADD `alias` VARCHAR( 255 ) NOT NULL,
	ADD	`metakey` text NOT NULL,
	ADD `metadesc` text NOT NULL,
	ADD `latitude` DECIMAL( 18, 15 ) NOT NULL ,
	ADD `longitude` DECIMAL( 18, 15 ) NOT NULL;	
	
ALTER TABLE `#__djcf_categories` 
	ADD `alias` VARCHAR( 255 ) NOT NULL;	
	 	
CREATE TABLE IF NOT EXISTS `#__djcf_types` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR( 255 ) NOT NULL ,
	`ordering` INT NOT NULL ,
	`published` INT NOT NULL ,
	`params` TEXT NOT NULL,
	 PRIMARY KEY  (`id`)
) ENGINE = MYISAM  DEFAULT CHARSET=utf8 ;

INSERT IGNORE INTO `#__djcf_types` (`id`, `name`, `ordering`, `published`, `params`) VALUES
	(1, 'For Sale', 1, 1, '{"bt_class":"bt_forsale","bt_use_styles":"1","bt_color":"#000000","bt_bg":"#FEC657","bt_border_color":"#A2691B","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;margin:3px 5px;"}'),
	(2, 'Exchange', 2, 1, '{"bt_class":"bt_exchange","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#6D9AD2","bt_border_color":"#14547E","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;;margin:3px 5px;"}'),
	(5, 'Free', 4, 1, '{"bt_class":"","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#CC5092","bt_border_color":"#E32B75","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;margin:3px 5px;"}'),
	(6, 'Urgent', 5, 1, '{"bt_class":"","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#D14141","bt_border_color":"#FFFFFF","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;;margin:3px 5px;"}');
