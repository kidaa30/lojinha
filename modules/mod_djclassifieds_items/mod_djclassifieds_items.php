<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Items Module
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
if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
require_once (dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djimage.php');

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

	$items = modDjClassifiedsItems::getItems($params);
	$cfpar = JComponentHelper::getParams( 'com_djclassifieds' );
	
	$par_id = $params->get('mainid');
	
	if($params->get('show_type','1')){
		$types = modDjClassifiedsItems::getTypes();	
	}
	
	if($params->get('show_default_img','0')){
		$cat_images = modDjClassifiedsItems::getCatImages();
	}
		
	/*$menus	= JSite::getMenu();	
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
			
	$itemid = ''; 
	if($menu_item){
		$itemid='&Itemid='.$menu_item->id;
	}else if($menu_item_blog){
		$itemid='&Itemid='.$menu_item_blog->id;
	}*/		

	//$lang = JFactory::getLanguage();
	//$lang->load('com_djclassifieds', JPATH_SITE, $lang->getTag(), true);
	//$lang->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds/',$lang->getTag(), true);	
	
	if(JRequest::getVar('option')!='com_djclassifieds'){
		DJClassifiedsTheme::includeCSSfiles();
	}

require(JModuleHelper::getLayoutPath('mod_djclassifieds_items', $params->get('layout', 'default')));

?>
