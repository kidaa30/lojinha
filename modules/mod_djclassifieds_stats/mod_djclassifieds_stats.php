<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Stats Module
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/
defined ('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');
JHTML::_('behavior.framework');
$comparams = JComponentHelper::getParams( 'com_djclassifieds' );
	if(JRequest::getVar('option')!='com_djclassifieds'){
		$document= JFactory::getDocument();
		DJClassifiedsTheme::includeCSSfiles();
		
		$language = JFactory::getLanguage();	
		$c_lang = $language->getTag();
			if($c_lang=='pl-PL' || $c_lang=='en-GB'){
				$language->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds', null, true);	
			}else{
				if(!$language->load('com_djclassifieds', JPATH_SITE, null, true)){
					$language->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds', null, true);
				}			
			}		
	}
	
	$stats = array();
	if($params->get('ads_total','1')){
		$stats['ads_total'] = modDjClassifiedsStats::getAdverts(0);
	}
	if($params->get('ads_active','1')){
		$stats['ads_active'] = modDjClassifiedsStats::getAdverts(1);
	}
	if($params->get('ads_added_today','1')){
		$date_from = date("Y-m-d").' 00:00:00';
		$stats['ads_today'] = modDjClassifiedsStats::getAdverts(0,$date_from);		
	}
	if($params->get('ads_added_1','1')){
		$date_form = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")-1, date("Y")));
		$stats['ads_1d'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('ads_added_week','1')){
		$date_form = date("Y-m-d",strtotime('monday this week')).' 00:00:00';
		$stats['ads_week'] = modDjClassifiedsStats::getAdverts(0,0);
	}
	if($params->get('ads_added_7','1')){
		$date_form = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")-7, date("Y")));
		$stats['ads_7d'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('ads_added_month','1')){
		$date_from = date("Y-m").'-01 00:00:00'; 
		$stats['ads_month'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('ads_added_30','1')){
		$date_form = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")-1 , date("d"), date("Y")));
		$stats['ads_30d'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('ads_added_year','1')){
		$date_from = date("Y").'-01-01 00:00:00';
		$stats['ads_year'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('ads_added_365','1')){
		$date_form = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m") , date("d"), date("Y")-1));
		$stats['ads_365d'] = modDjClassifiedsStats::getAdverts(0,$date_from);
	}
	if($params->get('auctions_count','1')){
		$stats['auctions_c'] = modDjClassifiedsStats::getAuctions();
	}
	if($params->get('cat_count','1')){
		$stats['categories_c'] = modDjClassifiedsStats::getCategories();
	}	

require (JModuleHelper::getLayoutPath('mod_djclassifieds_stats'));


