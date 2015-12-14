	CREATE TABLE IF NOT EXISTS `#__djcf_categories` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(100) default NULL,
	  `alias` VARCHAR( 255 ) NOT NULL,
	  `parent_id` int(11) NOT NULL,
	  `price` int(11) NOT NULL default '0',
	  `description` text NOT NULL,
	  `icon_url` varchar(255) NOT NULL,
	  `ordering` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL default '1',
	  `autopublish` int(11) NOT NULL default '0',
	  `metakey` text NOT NULL,
	  `metadesc` text NOT NULL,
	  `access` int(11) NOT NULL,
	  `points` int(11) NOT NULL,
	  `ads_disabled` int(11) NOT NULL,
	  `theme` varchar(255) NOT NULL,
	  `access_view` int(11) NOT NULL DEFAULT '1',
	  `access_item_view` int(11) NOT NULL DEFAULT '1',
	  `restriction_18` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;

	
	CREATE TABLE IF NOT EXISTS `#__djcf_categories_groups` (
      `id` INT(11) NOT NULL AUTO_INCREMENT ,
      `cat_id` INT(11) NOT NULL ,
	  `group_id` INT(11) NOT NULL ,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;	
		
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `label` varchar(255) NOT NULL,
	  `type` varchar(255) NOT NULL,
	  `values` text NOT NULL,
	  `default_value` varchar(255) NOT NULL,
	  `params` text NOT NULL,
	  `in_search` int(11) NOT NULL default '0',
	  `search_type` varchar(255) NOT NULL,
	  `search_value1` text NOT NULL,
	  `search_value2` text NOT NULL,
	  `published` int(11) NOT NULL default '0',
	  `required` int(11) NOT NULL,
	  `description` TEXT NOT NULL,
	  `access` int(11) NOT NULL default '0',
	  `in_table` int(11) NOT NULL default '0',
	  `in_blog` int(11) NOT NULL default '0',
	  `in_item` int(11) NOT NULL default '0',
	  `source` int(11) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  `numbers_only` int(11) NOT NULL default '0',
	  `in_registration` int(11) NOT NULL default '0',
	  `in_buynow` INT NOT NULL ,
	  `buynow_values` TEXT NOT NULL,
	  `hide_on_start` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields_values` (
	  `id` int(11) NOT NULL auto_increment,
	  `field_id` int(11) NOT NULL,
	  `item_id` int(11) NOT NULL,
	  `value` text NOT NULL,
	  `value_date` DATE NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_fields_values_profile` (
	  `id` int(11) NOT NULL auto_increment,
	  `field_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `value` text NOT NULL,
	  `value_date` DATE NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;

	CREATE TABLE IF NOT EXISTS `#__djcf_fields_xref` (
	  `id` int(11) NOT NULL auto_increment,
	  `cat_id` int(11) NOT NULL,
	  `field_id` int(11) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_images` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `item_id` int(11) NOT NULL,
	  `type` varchar(55) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `ext` varchar(55) NOT NULL,
	  `path` varchar(512) NOT NULL,
	  `caption` varchar(512) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  `optimized` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) DEFAULT CHARSET=utf8;	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_items` (
	  `id` int(11) NOT NULL auto_increment,
	  `cat_id` int(11) NOT NULL default '0',
	  `type_id` int(11) NOT NULL default '0',
	  `user_id` int(11) NOT NULL,
	  `name` varchar(255) NOT NULL,
	  `alias` VARCHAR( 255 ) NOT NULL,
	  `description` text,
	  `intro_desc` text,
	  `image_url` text,
	  `date_start` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `date_exp` timestamp NOT NULL default '0000-00-00 00:00:00',
	  `date_mod` timestamp NOT NULL default '0000-00-00 00:00:00',
	  `date_sort` timestamp NOT NULL,
	  `display` int(11) NOT NULL default '0',
	  `special` int(11) NOT NULL default '0',
	  `paypal_token` varchar(12) NOT NULL,
	  `payed` int(11) NOT NULL default '0',
	  `notify` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL default '1',
	  `ordering` int(11) NOT NULL default '0',
	  `price` varchar(255) default NULL,
	  `price_negotiable` int(11) default 0,
	  `contact` text,
	  `pay_type` varchar(255) default NULL,
	  `address` varchar(255) NOT NULL,
	  `region_id` int(11) NOT NULL default '0',
	  `exp_days` int(11) NOT NULL,
	  `promotions` text NOT NULL,
	  `post_code` varchar(55) NOT NULL,
	  `video` text NOT NULL, 
      `website` varchar(255) NOT NULL,
      `ip_address` varchar(55) NOT NULL,
      `currency` varchar(55) NOT NULL,
      `metakey` text NOT NULL,
	  `metadesc` text NOT NULL,
	  `latitude` DECIMAL( 18, 15 ) NOT NULL,
	  `longitude` DECIMAL( 18, 15 ) NOT NULL,
	  `email` VARCHAR( 255 ) NOT NULL,
	  `token` VARCHAR( 55 ) NOT NULL, 
	  `access_view` int(11) NOT NULL DEFAULT '0',
	  `extra_images` int(11) NOT NULL DEFAULT '0',
	  `extra_images_to_pay` int(11) NOT NULL DEFAULT '0',
	  `extra_chars` int(11) NOT NULL DEFAULT '0',
	  `extra_chars_to_pay` int(11) NOT NULL DEFAULT '0',	  
	  `auction` INT NOT NULL ,
	  `bid_min` FLOAT( 12, 2 ) NOT NULL ,
	  `bid_max` FLOAT( 12, 2 ) NOT NULL ,
	  `bid_autoclose` INT NOT NULL,
	  `price_reserve` FLOAT( 12, 2 ) NOT NULL ,   
	  `price_start` FLOAT( 12, 2 ) NOT NULL,   
	  `quantity` int(11) NOT NULL,
	  `buynow` int(11) NOT NULL,
	  `unit_id` int(11) NOT NULL,	 	  	  	  
	  PRIMARY KEY  (`id`),
	  KEY `cat_id` (`cat_id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_itemsask` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `ip_address` varchar(50) NOT NULL,
	  `message` text NOT NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `custom_fields` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_items_abuse` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `message` text NOT NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_payments` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `method` varchar(255) NOT NULL,
	  `transaction_id` int(11) NOT NULL,
	  `transaction_hash` varchar(255) NULL,
	  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `status` varchar(255) NOT NULL,
	  `price` float(12,2) NOT NULL,
      `ip_address` varchar(55) NOT NULL,
      `type` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_regions` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) NOT NULL,
	  `parent_id` int(11) NOT NULL default '0',
	  `country` int(11) NOT NULL default '0',
	  `city` int(11) NOT NULL default '0',
	  `published` int(11) NOT NULL,
	  `latitude` varchar(255) NOT NULL,
	  `longitude` varchar(255) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_favourites` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;
	

	CREATE TABLE IF NOT EXISTS `#__djcf_days` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `days` int(11) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
	  `price_renew` FLOAT( 12, 2 ) NOT NULL,
	  `points_renew` int(11) NOT NULL,
	  `img_price` FLOAT( 12, 2 ) NOT NULL ,
	  `img_points` INT(11) NOT NULL ,
	  `img_price_renew` FLOAT( 12, 2 ) NOT NULL ,
	  `img_points_renew` INT(11) NOT NULL ,
	  `img_price_default` INT(11) NOT NULL DEFAULT '1' ,
	  `char_price` FLOAT( 12, 2 ) NOT NULL ,
	  `char_points` INT(11) NOT NULL ,
	  `char_price_renew` FLOAT( 12, 2 ) NOT NULL ,
	  `char_points_renew` INT(11) NOT NULL,
	  `char_price_default` INT(11) NOT NULL DEFAULT '1',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	INSERT INTO `#__djcf_days` (`id`, `days`, `price`, `points`, `published`) VALUES
		(1, 7, 0.00, 0, 1),
		(2, 14, 1.00, 0, 1),
		(3, 21, 2.00, 0, 1),
		(4, 30, 3.00, 0, 1);	
	

	CREATE TABLE IF NOT EXISTS `#__djcf_promotions` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `label` varchar(255) NOT NULL,
	  `description` varchar(255) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	

	

	INSERT IGNORE INTO `#__djcf_promotions` (`id`, `name`, `label`, `description`, `price`, `points`, `published`, `ordering`) VALUES
		(1, 'p_first', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST_DESC', 1.00, 0, 1,1),
		(2, 'p_bold', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD_DESC', 1.00, 0, 1,2),
		(3, 'p_border', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER_DESC', 1.00, 0, 1,3),
		(4, 'p_bg', 'COM_DJCLASSIFIEDS_PROMOTION_BG', 'COM_DJCLASSIFIEDS_PROMOTION_BG_DESC', 1.00, 0, 1,4),
		(5, 'p_special', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL_DESC', 1.00, 0, 1,5);
		

	CREATE TABLE IF NOT EXISTS `#__djcf_types` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` VARCHAR( 255 ) NOT NULL ,
		`ordering` INT(11) NOT NULL ,
		`published` INT(11) NOT NULL ,
		`params` TEXT NOT NULL,
		 PRIMARY KEY  (`id`)
	) ENGINE = MYISAM  DEFAULT CHARSET=utf8 ;
	
	INSERT IGNORE INTO `#__djcf_types` (`id`, `name`, `ordering`, `published`, `params`) VALUES
		(1, 'For Sale', 1, 1, '{"bt_class":"bt_forsale","bt_use_styles":"1","bt_color":"#000000","bt_bg":"#FEC657","bt_border_color":"#A2691B","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;margin:3px 5px;"}'),
		(2, 'Exchange', 2, 1, '{"bt_class":"bt_exchange","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#6D9AD2","bt_border_color":"#14547E","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;;margin:3px 5px;"}'),
		(5, 'Free', 4, 1, '{"bt_class":"","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#CC5092","bt_border_color":"#E32B75","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;margin:3px 5px;"}'),
		(6, 'Urgent', 5, 1, '{"bt_class":"","bt_use_styles":"1","bt_color":"#FFFFFF","bt_bg":"#D14141","bt_border_color":"#FFFFFF","bt_border_size":"1","bt_style":"border-radius:2px;padding:2px 4px;;margin:3px 5px;"}');
		
		
	CREATE TABLE IF NOT EXISTS `#__djcf_points` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `ordering` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;	
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_points_groups` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `points_id` int(11) NOT NULL,
	  `group_id` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	CREATE TABLE IF NOT EXISTS `#__djcf_users_points` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `points` float(12,0) NOT NULL,
	  `description` text NOT NULL,
	  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  UNIQUE KEY `id` (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;		
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_auctions` (
 	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `ip_address` varchar(50) NOT NULL,
	  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `price` float(12,2) NOT NULL,
	  `win` int(11) NOT NULL,
  	PRIMARY KEY (`id`)
	)CHARSET=utf8;
	
	
	CREATE TABLE IF NOT EXISTS `#__djcf_emails` (
	  `id` int(11) NOT NULL auto_increment,
	  `label` varchar(255) NOT NULL,
	  `title` VARCHAR( 255 ) NOT NULL,
	  `content` text,	        	  	  	  
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8;
	
	
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
 		PRIMARY KEY (`id`)
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
(1, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_OFFER_AUTHOR_EMAIL', 'New offer on your auction', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>New offer on your auction [[advert_title_link]].</p>\r\n<p>Bid offer [[bid_value]]</p>\r\n<p>Bidder [[bidder_name]]</p>\r\n<p>Bidder email [[bidder_email]]</p>\r\n<p> </p>'),
(2, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_OFFER_BIDDER_EMAIL', 'You made new offer on your auction', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>You made new offer on auction [[advert_title_link]]</p>\r\n<p>Offer [[bid_value]]</p>\r\n<p> </p>'),
(3, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_OFFER_OUTBID_EMAIL', 'Your bid has been outbid', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your bid has been outbid [[advert_title_link]]</p>\r\n<p>Offer [[bid_value]]</p>\r\n<p>Bidder [[bidder_name]]</p>\r\n<p> </p>'),
(4, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_AUTHOR_EMAIL', 'End of your auction', '<p>Hello [[user_name]],</p>\n<p> </p>\n<p>Your auction [[advert_title_link]] has ended.</p>\n<p>Biggest offer [[bid_value]]</p>\n<p>Bidder [[bidder_name]]</p>\n<p>Bidder email [[bidder_email]]</p>\n<p> </p>'),
(5, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_WINNER_EMAIL', 'You won auction', '<p>Hello [[user_name]],</p>\n<p> </p>\n<p>Your offer won auction [[advert_title_link]].</p>\n<p>Please contact with advert author: </p>\n<p>[[advert_author_name]]</p>\n<p>[[advert_author_email]]</p>'),
(6, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_BIDDER_CONTACT', 'Message from auction owner', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Message from [[contact_author_name]]</p>\r\n<p>owner of advert [[advert_title_link]].</p>\r\n<p> </p>\r\n<p>[[contact_message]]</p>\r\n<p> </p>\r\n<p> </p>'),
(7, 'COM_DJCLASSIFIEDS_ET_ASK_FORM_CONTACT', 'Your advertisement enquiry', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Message from:</p>\r\n<p>User name: [[contact_author_name]]</p>\r\n<p>User email: [[contact_author_email]]</p>\r\n<p></p>\r\n<p>Your advertisement ''''[[advert_title_link]]'''' enquiry:</p>\r\n<p></p>\r\n[[contact_message]]'),
(8, 'COM_DJCLASSIFIEDS_ET_ABUSE_FORM_REPORT', 'Abuse Report', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Message from: [[abuse_author_name]]</p>\r\n<p></p>\r\n<p>Advert: ''''[[advert_title_link]]''''</p>\r\n<p></p>\r\n<p>Abuse reason:<br />\r\n[[abuse_message]]</p>'),
(9, 'COM_DJCLASSIFIEDS_ET_NEW_ADVERT_ADMINISTRATOR', 'New advert', '<p>Hello,</p>\r\n<p> </p>\r\n<p>New advert: ''''[[advert_title_link]]''''</p>\r\n<p></p>\r\n<p>Category: [[advert_category]]</p>\r\n<p></p>\r\n<p>Author name: [[advert_author_name]]</p>\r\n<p>Author email: [[advert_author_email]]</p>\r\n<p></p>\r\n<p>Intro description: [[advert_intro_desc]]</p>\r\n<p></p>\r\n<p>Description:<br /> [[advert_desc]]</p>'),
(10, 'COM_DJCLASSIFIEDS_ET_ADVERT_EDITION_ADMINISTRATOR', 'Advert edition', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Edition of advert: ''''[[advert_title_link]]''''</p>\r\n<p></p>\r\n<p>Category: [[advert_category]]</p>\r\n<p></p>\r\n<p>Author name: [[advert_author_name]]</p>\r\n<p>Author email: [[advert_author_email]]</p>\r\n<p></p>\r\n<p>Intro description: [[advert_intro_desc]]</p>\r\n<p></p>\r\n<p>Description:<br /> [[advert_desc]]</p>'),
(11, 'COM_DJCLASSIFIEDS_ET_NEW_ADVERT_USER', 'New advert', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Your new advert: ''''[[advert_title_link]]''''</p>\r\n<p></p>\r\n<p>Category: [[advert_category]]</p>\r\n<p></p>\r\n<p>Status: [[advert_status]]</p>\r\n<p></p>\r\n<p>Intro description: [[advert_intro_desc]]</p>\r\n'),
(12, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_AUTHOR_NO_BIDS_EMAIL', 'End of your auction', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your auction [[advert_title_link]] has ended.</p>\r\n<p>There was no offers</p>\r\n<p> </p>'),
(13, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_AUTHOR_NO_PRICE_RESERVED_EMAIL', 'End of your auction', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your auction [[advert_title_link]] has ended.</p>\r\n<p>Your reserve price hasn''t been reached.</p>\r\n<p>Biggest offer [[bid_value]]</p>\r\n<p>Bidder [[bidder_name]]</p>\r\n<p>Bidder email [[bidder_email]]</p>\r\n<p> </p>'),
(14, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_WINNER_NO_PRICE_RESERVED_EMAIL', 'You won auction', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your offer won auction [[advert_title_link]].</p>\r\n<p>Unfortunately your offer haven''t  reached reserve price.</p>\r\n<p>You can try to contact advert author: </p>\r\n<p>[[advert_author_name]]</p>\r\n<p>[[advert_author_email]]</p>'),
(15, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_BIDDERS_EMAIL', 'Auctions was ended', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your offer didn''t won the auction [[advert_title_link]].</p>\r\n'),
(16, 'COM_DJCLASSIFIEDS_ET_BUYNOW_BUYER_EMAIL', 'You bought product', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your made the purchase on the page [[advert_title_link]].</p>\r\n<p>Quantity : [[buynow_quantity]].</p>\r\n<p>Price per product: [[buynow_price]].</p>\r\n<p>Total price: [[buynow_price_total]].</p>\r\n<p>Please contact with advert author: </p>\r\n<p>[[advert_author_name]]</p>\r\n<p>[[advert_author_email]]</p>'),
(17, 'COM_DJCLASSIFIEDS_ET_BUYNOW_AUTHOR_EMAIL', 'New purchase on your advert', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>New offer on your advert [[advert_title_link]].</p>\r\n<p>Quantity : [[buynow_quantity]].</p>\r\n<p>Price per product: [[buynow_price]].</p>\r\n<p>Total price: [[buynow_price_total]].</p>\r\n<p>Buyer [[buyer_name]]</p>\r\n<p>Buyer email [[buyer_email]]</p>'),
(18, 'COM_DJCLASSIFIEDS_ET_ADVERT_EXPIRE_NOTIFICATION', 'Advert expire notification', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Your advert ''''[[advert_title_link]]'''' will expire in [[advert_expire_days]] days.</p>\r\n<p> </p>\r\n<p>You can renew it on your adverts list.</p>\r\n<p> </p>'),
(19, 'COM_DJCLASSIFIEDS_ET_ADVERT_STATUS_CHANGE', 'Advert status change', '<p>Hello,</p>\r\n<p> </p>\r\n<p>Status change of your advert ''''[[advert_title_link]]''''.</p>\r\n<p> </p>\r\n<p>New status ''''[[advert_status]]''''.</p>\r\n<p> </p>'),
(20, 'COM_DJCLASSIFIEDS_ET_PAYMENTS_BANKTRANSFER_PAYMENT_INFO', 'Bank Transfer Payment informations', '<p>Hello</p>\r\n<p>Payment for:  [[payment_item_name]]<br /><br />Price: [[payment_price]]<br /><br />Payment Information:<br /><br />[[payment_info]]<br /><br /><br />Payment ID: [[payment_id]]</p>');