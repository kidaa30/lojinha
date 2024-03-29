<?php
/**
* @version 2.0
* @package DJ Classifieds
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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class DjclassifiedsModelContact extends JModelLegacy{	
	
	function getItem(){
		$app	= JFactory::getApplication();
		$id 	= JRequest::getInt('id', 0);
		$db		= JFactory::getDBO();

			$query = "SELECT i.* FROM #__djcf_items i "
					."WHERE i.id= ".$id." LIMIT 1 ";
	
			$db->setQuery($query);
			$item=$db->loadObject();
	
		return $item;
	}
	
	function getBid(){
		$app	= JFactory::getApplication();
		$bid 	= JRequest::getInt('bid', 0);
		$id 	= JRequest::getInt('id', 0);
		$db		= JFactory::getDBO();
	
		$query = "SELECT a.* FROM #__djcf_auctions a "
				."WHERE a.id= ".$bid." AND a.item_id= ".$id." LIMIT 1 ";
	
		$db->setQuery($query);
		$bid=$db->loadObject();
	
		return $bid;
	}
	
	
}

