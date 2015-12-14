	CREATE TABLE IF NOT EXISTS `#__djcf_favourites` (
	  `id` int(11) NOT NULL auto_increment,
	  `item_id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) DEFAULT CHARSET=utf8 ;