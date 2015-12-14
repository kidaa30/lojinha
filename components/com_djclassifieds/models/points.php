<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class DjclassifiedsModelPoints extends JModelLegacy{		
	
	function getPoints(){
			$db= JFactory::getDBO();
			$user = JFactory::getUser();
			$g_list = '0';
			if($user->groups){
				$g_list = implode(',',$user->groups);	
			}									
			if (!$g_list){
				$g_list = '0';
			}
				
			$query = "SELECT p.* ,g.g_active, ga.g_all FROM #__djcf_points p "
					."LEFT JOIN (SELECT COUNT(id) as g_active, points_id FROM #__djcf_points_groups g " 
				   				."WHERE group_id in(".$g_list.") GROUP BY points_id ) g ON g.points_id=p.id "
				   	."LEFT JOIN (SELECT COUNT(id) as g_all, points_id FROM #__djcf_points_groups g " 
				   				." GROUP BY points_id ) ga ON ga.points_id=p.id "
					."WHERE p.published=1 AND (g.g_active>0 OR ga.g_all IS NULL) "
					."ORDER BY p.ordering ";
	
			$db->setQuery($query);
			$points=$db->loadObjectList();
			//echo '<pre>';print_r($db);print_r($points);die();
	
			return $points;
	}
		
	
}

