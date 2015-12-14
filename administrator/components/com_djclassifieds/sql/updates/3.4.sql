	ALTER TABLE `#__djcf_items`
		ADD `quantity` INT NOT NULL,
		ADD `buynow` INT NOT NULL,
		ADD `unit_id` INT NOT NULL;

	ALTER TABLE `#__djcf_itemsask` 
		ADD `custom_fields` TEXT NOT NULL ;
	
	ALTER TABLE `#__djcf_images` 
		ADD `optimized` INT NOT NULL ;	
		
	ALTER TABLE `#__djcf_fields` 
		ADD `numbers_only` TEXT NOT NULL,
		ADD `in_registration` INT NOT NULL,
		ADD `in_buynow` INT NOT NULL ,
		ADD `buynow_values` TEXT NOT NULL,
		ADD `hide_on_start` INT NOT NULL;
		
	CREATE TABLE IF NOT EXISTS `#__djcf_orders` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`item_id` int(11) NOT NULL,
		`user_id` int(11) NOT NULL,
		`ip_address` int(11) NOT NULL,
		`date` timestamp NOT NULL default CURRENT_TIMESTAMP,
		`price` float(12,2) NOT NULL,
		`currency` varchar(55) NOT NULL,
		`quantity` int(11) NOT NULL,
		`status` int(11) NOT NULL,
		`item_name` varchar(255) NOT NULL,
		`item_option` text NOT NULL,
  	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields_values_sale` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `item_id` int(11) NOT NULL,
	  `quantity` int(11) NOT NULL,
	  `options` text NOT NULL,
	  PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_files` (
  		`id` int(11) NOT NULL AUTO_INCREMENT,
  		`item_id` int(11) NOT NULL,
  		`type` varchar(64) NOT NULL,
  		`fullname` varchar(255) NOT NULL,
  		`name` varchar(255) NOT NULL,
  		`ext` varchar(255) NOT NULL,
  		`path` varchar(255) DEFAULT NULL,
  		`fullpath` varchar(255) DEFAULT NULL,
  		`caption` varchar(255) NOT NULL,
  		`ordering` int(11) NOT NULL DEFAULT '0',
  		`hits` int(11) NOT NULL DEFAULT '0',
  		PRIMARY KEY (`id`),
  		KEY `idx_type_item` (`type`,`item_id`),
  		KEY `idx_item_type` (`item_id`,`type`),
  		KEY `idx_item_type_ordering` (`item_id`,`type`,`ordering`)
	) DEFAULT CHARSET=utf8;	
	
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_items_shipping` (
  		`id` int(11) NOT NULL AUTO_INCREMENT,
  		`item_id` int(11) NOT NULL,
  		`shipping_id` int(11) NOT NULL,
  		`price` decimal(12,2) NOT NULL,
  		`description` text NOT NULL,
  		PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_orders_shipping` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
  	  `order_id` int(11) NOT NULL,
  	  `shipping_id` int(11) NOT NULL,
  	  `name` varchar(255) NOT NULL,
  	  `price` decimal(12,2) NOT NULL,
  	  PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8 ;
	
CREATE TABLE IF NOT EXISTS `#__djcf_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` float(12,2) NOT NULL,
  `points` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `groups_assignment` text NOT NULL,
  `groups_restriction` text NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;
		
		
CREATE TABLE IF NOT EXISTS `#__djcf_plans_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;		

CREATE TABLE IF NOT EXISTS `#__djcf_plans_subscr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `adverts_limit` int(11) NOT NULL,
  `adverts_available` int(11) NOT NULL,
  `date_start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_exp` datetime NOT NULL,
  `plan_params` text NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__djcf_plans_subscr_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscr_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__djcf_shipping_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `published` tinyint(4) NOT NULL,
  `plugin` varchar(255) NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `tax_rate_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `shipping_details` tinyint(4) NOT NULL,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `idx_published` (`published`),
  KEY `idx_name` (`name`),
  KEY `idx_plugin` (`plugin`)
) DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__djcf_tax_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` decimal(10,4) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `#__djcf_items_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

INSERT INTO `#__djcf_items_units` (`id`, `name`, `ordering`, `published`) VALUES
(1, 'pieces', 1, 1),
(2, 'sets', 2, 1),
(3, 'pairs', 3, 1);

	INSERT IGNORE INTO `#__djcf_emails` (`id`, `label`, `title`, `content`) VALUES  
	(16, 'COM_DJCLASSIFIEDS_ET_BUYNOW_BUYER_EMAIL', 'You bought product', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your made the purchase on the page [[advert_title_link]].</p>\r\n<p>Quantity : [[buynow_quantity]].</p>\r\n<p>Price per product: [[buynow_price]].</p>\r\n<p>Total price: [[buynow_price_total]].</p>\r\n<p>Please contact with advert author: </p>\r\n<p>[[advert_author_name]]</p>\r\n<p>[[advert_author_email]]</p>'),
	(17, 'COM_DJCLASSIFIEDS_ET_BUYNOW_AUTHOR_EMAIL', 'New purchase on your advert', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>New offer on your advert [[advert_title_link]].</p>\r\n<p>Quantity : [[buynow_quantity]].</p>\r\n<p>Price per product: [[buynow_price]].</p>\r\n<p>Total price: [[buynow_price_total]].</p>\r\n<p>Buyer [[buyer_name]]</p>\r\n<p>Buyer email [[buyer_email]]</p>'),
	(18, 'COM_DJCLASSIFIEDS_ET_ADVERT_EXPIRE_NOTIFICATION', 'Advert expire notification', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Your advert ''''[[advert_title_link]]'''' will expire in [[advert_expire_days]] days.</p>\r\n<p> </p>\r\n<p>You can renew it on your adverts list.</p>\r\n<p> </p>'),
	(19, 'COM_DJCLASSIFIEDS_ET_ADVERT_STATUS_CHANGE', 'Advert status change', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Status change of your advert ''''[[advert_title_link]]''''.</p>\r\n<p> </p>\r\n<p>New status ''''[[advert_status]]''''.</p>\r\n<p> </p>'),
	(20, 'COM_DJCLASSIFIEDS_ET_PAYMENTS_BANKTRANSFER_PAYMENT_INFO', 'Bank Transfer Payment informations', '<p>Hello</p>\r\n<p>Payment for:  [[payment_item_name]]<br /><br />Price: [[payment_price]]<br /><br />Payment Information:<br /><br />[[payment_info]]<br /><br /><br />Payment ID: [[payment_id]]</p>');
	