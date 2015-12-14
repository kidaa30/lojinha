<?php
/**
* @version 2.0
* @package DJ Classifieds Menu Module
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');

	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$active = $menu->getActive();
	
	if(JRequest::getVar('option')!='com_djclassifieds'){
		DJClassifiedsTheme::includeCSSfiles();
	}

	if($params->get('cat_id',0) > 0){		
		$cats= DJClassifiedsCategory::getSubCatIemsCount($params->get('cat_id',0),1,'c.name');
	}else{
		$cats= DJClassifiedsCategory::getCatAllItemsCount(1,'c.name');
	}
	
	
	//	echo '<pre>';print_r($cats);die();
	
	$cat_images='';
	if($params->get('cattree_img',0)){
		$cat_images = modDjClassifiedsCatTree::getCatImages();
	}

require(JModuleHelper::getLayoutPath('mod_djclassifieds_cattree'));
?>



