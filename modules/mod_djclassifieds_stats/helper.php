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

class modDjClassifiedsStats
{
	public static function getAdverts($pub=1,$date_from=''){
		$db= JFactory::getDBO();
		
		$date_now = date("Y-m-d H:i:s");
		$pub_w = '';
		if($pub){
			$pub_w = " AND i.published=1 AND i.date_exp > '".$date_now."' ";
		}
		
		$date_from_w = '';
		if($date_from){
			$date_from_w = " AND i.date_start>='".$date_from."' ";
		}
		
		$query = "SELECT COUNT(id) FROM #__djcf_items i WHERE 1 ".$pub_w.$date_from_w;		
		$db->setQuery($query);
		$total=$db->loadResult();
		
		return $total;
	}
	
	
	public static function getAuctions(){
		$db= JFactory::getDBO();	
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT COUNT(id) FROM #__djcf_items i "
				."WHERE i.date_exp > '".$date_now."' AND i.auction=1 AND i.published=1";
		$db->setQuery($query);
		$total=$db->loadResult();
	
		return $total;
	}
	
	public static function getCategories(){
		$db= JFactory::getDBO();
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT COUNT(id) FROM #__djcf_categories "
				."WHERE published=1";
		$db->setQuery($query);
		$total=$db->loadResult();
	
		return $total;
	}
}