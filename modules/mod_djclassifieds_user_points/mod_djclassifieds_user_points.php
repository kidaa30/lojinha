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
	require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');
	require_once (dirname(__FILE__).DS.'helper.php');
	
	$app = JFactory::getApplication();
	$user = JFactory::getUser();
	$menus	= $app->getMenu('site');		
	$menu_upoints_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=userpoints',1);
	$menu_ppackages_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=points',1);
		
	if(JRequest::getVar('option')!='com_djclassifieds'){
		DJClassifiedsTheme::includeCSSfiles();
	}
	$user_points = 0;
	if($user->id){
		$user_points = modDjClassifiedsUserPoints::getUserPoints();
	}

require(JModuleHelper::getLayoutPath('mod_djclassifieds_user_points'));

?>
