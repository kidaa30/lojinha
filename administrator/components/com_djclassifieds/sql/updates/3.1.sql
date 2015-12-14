ALTER TABLE `#__djcf_items` 
	ADD `date_sort` TIMESTAMP NOT NULL;
	
UPDATE `#__djcf_items` SET date_sort=date_start;	

ALTER TABLE `#__djcf_fields` 
	ADD `access` int(11) NOT NULL,
	ADD `in_table` int(11) NOT NULL,
	ADD `in_blog` int(11) NOT NULL;
	
ALTER TABLE `#__djcf_categories` 
	ADD `ads_disabled` int(11) NOT NULL;	