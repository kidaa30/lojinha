	ALTER TABLE `#__djcf_items` 
		ADD `promotions` TEXT NOT NULL; 

	CREATE TABLE IF NOT EXISTS `#__djcf_days` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `days` int(11) NOT NULL,
	  `price` float(12,2) NOT NULL,
	  `points` int(11) NOT NULL,
	  `published` int(11) NOT NULL,
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
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
	
	INSERT INTO `#__djcf_promotions` (`id`, `name`, `label`, `description`, `price`, `points`, `published`) VALUES
		(1, 'p_first', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST', 'COM_DJCLASSIFIEDS_PROMOTION_FIRST_DESC', 1.00, 0, 1),
		(2, 'p_bold', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD', 'COM_DJCLASSIFIEDS_PROMOTION_BOLD_DESC', 1.00, 0, 1),
		(3, 'p_border', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER', 'COM_DJCLASSIFIEDS_PROMOTION_BORDER_DESC', 1.00, 0, 1),
		(4, 'p_bg', 'COM_DJCLASSIFIEDS_PROMOTION_BG', 'COM_DJCLASSIFIEDS_PROMOTION_BG_DESC', 1.00, 0, 1),
		(5, 'p_special', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL', 'COM_DJCLASSIFIEDS_PROMOTION_SPECIAL_DESC', 1.00, 0, 1);
		
