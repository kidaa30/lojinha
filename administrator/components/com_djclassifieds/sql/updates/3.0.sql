ALTER TABLE `#__djcf_categories` 
	ADD `points` int(11) NOT NULL;
	
ALTER TABLE `#__djcf_days` 
	ADD `points_renew` int(11) NOT NULL;
	
ALTER TABLE `#__djcf_regions` 
	ADD `latitude` varchar(255) NOT NULL,
	ADD `longitude` varchar(255) NOT NULL;	
	
ALTER TABLE `#__djcf_payments` 
  ADD `price` float(12,2) NOT NULL,
  ADD `ip_address` varchar(55) NOT NULL,
  ADD `type` int(11) NOT NULL;		
	
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