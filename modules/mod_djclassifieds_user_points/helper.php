<?php
/**
* @version 2.0
* @package DJ Classifieds User Points Module
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

class modDjClassifiedsUserPoints{
	public static function getUserPoints(){
		$db		= JFactory::getDBO();	
		$user 	= JFactory::getUser();
		
			$query = "SELECT SUM(p.points)FROM #__djcf_users_points p "
					."WHERE p.user_id='".$user->id."' ";				
						
			$db= JFactory::getDBO();
			$db->setQuery($query);
			$user_points = $db->loadResult();
				
				//echo '<pre>';print_r($db);print_r($user_points);echo '<pre>';die();	
		//echo '<pre>';print_r($db);die();
		return $user_points;
	}

}
?>
