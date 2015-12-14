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

class modDjClassifiedsSearch
{
	public static function getCats(){
		$db= JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_categories WHERE published=1 ORDER BY parent_id, ordering";		
		$db->setQuery($query);
		$list=$db->loadObjectList();
		return $list;
	}
	
	public static function getRegions(){
		$db= JFactory::getDBO();
		$query = "SELECT r.* FROM #__djcf_regions r "
				."WHERE r.published=1 "
				."ORDER BY r.parent_id, r.name COLLATE utf8_polish_ci ";

		$db->setQuery($query);
		$regions=$db->loadObjectList();

		return $regions;
	}
	
	public static function getTypes(){
		$db= JFactory::getDBO();
		$query = "SELECT t.id as value, t.name as text, '0' as disabled FROM #__djcf_types t "
				."WHERE t.published=1 "
				."ORDER BY t.ordering";
		
			$db->setQuery($query); 
			$types=$db->loadObjectList();

		return $types;
	}
}