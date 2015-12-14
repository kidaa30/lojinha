<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Search Module
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
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djgeocoder.php');
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
	
	$djcfcatlib = new DJClassifiedsCategory();
	
	
	$list = modDjClassifiedsSearch::getCats();
	if($params->get('cat_hide_empty','0')){
		$list= $djcfcatlib->getCatAllItemsCount(1,$params->get('cat_ordering','ord'),$params->get('cat_hide_empty','0'));
		$categories = array();
		$categories[0] = array();
		foreach($list as $cat){			
			if(!isset($categories[$cat->parent_id])){
				$categories[$cat->parent_id] = array();
			}
			$categories[$cat->parent_id][] = $cat;
		}
		//echo '<pre>';print_r($categories);die();
	}else{
		$categories = $djcfcatlib->getCategoriesSortParent(1,$params->get('cat_ordering','ord'));		
	}
		
	$user_address = '';
	if(JRequest::getVar('se_geoloc','') && isset($_COOKIE["djcf_latlon"])){
		//$user_latlog = explode('_', $_COOKIE["djcf_latlon"]);
		$user_address = DJClassifiedsGeocode::getAddressLatLon(str_ireplace('_', ',', $_COOKIE["djcf_latlon"]));
	}
	
	$regions = modDjClassifiedsSearch::getRegions();
	$types = modDjClassifiedsSearch::getTypes();

require (JModuleHelper::getLayoutPath('mod_djclassifieds_search'));


