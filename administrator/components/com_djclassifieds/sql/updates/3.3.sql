ALTER TABLE `#__djcf_items` 
	ADD `access_view` int(11) NOT NULL DEFAULT '0',
	ADD `extra_images` int(11) NOT NULL DEFAULT '0',
	ADD `extra_images_to_pay` int(11) NOT NULL DEFAULT '0',
	ADD `extra_chars` int(11) NOT NULL DEFAULT '0',
	ADD `extra_chars_to_pay` int(11) NOT NULL DEFAULT '0',
	ADD `auction` INT NOT NULL ,
	ADD `bid_min` FLOAT( 12, 2 ) NOT NULL ,
	ADD `bid_max` FLOAT( 12, 2 ) NOT NULL ,
	ADD `bid_autoclose` INT NOT NULL ,
	ADD `price_reserve` FLOAT( 12, 2 ) NOT NULL,
	ADD `price_start` FLOAT( 12, 2 ) NOT NULL;
	
	
ALTER TABLE `#__djcf_categories` 
	ADD `access_view` int(11) NOT NULL DEFAULT '1',
	ADD `access_item_view` int(11) NOT NULL DEFAULT '1',
	ADD `restriction_18` int(11) NOT NULL ;	
	
	
	
ALTER TABLE `#__djcf_days` 
	ADD `img_price` FLOAT( 12, 2 ) NOT NULL ,
	ADD `img_points` INT(11) NOT NULL ,
	ADD `img_price_renew` FLOAT( 12, 2 ) NOT NULL ,
	ADD `img_points_renew` INT(11) NOT NULL ,
	ADD `img_price_default` INT(11) NOT NULL DEFAULT '1' ,
	ADD `char_price` FLOAT( 12, 2 ) NOT NULL ,
	ADD `char_points` INT(11) NOT NULL ,
	ADD `char_price_renew` FLOAT( 12, 2 ) NOT NULL ,
	ADD `char_points_renew` INT(11) NOT NULL ,
	ADD `char_price_default` INT(11) NOT NULL DEFAULT '1' ;	
	
ALTER TABLE `#__djcf_payments` 
	ADD `transaction_hash` varchar(255) NULL;	
	
	
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
(15, 'COM_DJCLASSIFIEDS_ET_AUCTIONS_END_BIDDERS_EMAIL', 'Auctions was ended', '<p>Hello [[user_name]],</p>\r\n<p> </p>\r\n<p>Your offer didn''t won the auction [[advert_title_link]].</p>\r\n');