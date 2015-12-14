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
	$app = JFactory::getApplication();
	$menus	= $app->getMenu('site');	
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
	$menu_newad_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=additem',1);
	$menu_uads_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=useritems',1);
	$menu_favads_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=favourites',1);
	$menu_upoints_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=userpoints',1);
	$menu_ppackages_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=points',1);
	$menu_profileedit_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=profileedit',1);
		
	if(JRequest::getVar('option')!='com_djclassifieds'){
		DJClassifiedsTheme::includeCSSfiles();
	}

require(JModuleHelper::getLayoutPath('mod_djclassifieds_user_menu'));

?>
