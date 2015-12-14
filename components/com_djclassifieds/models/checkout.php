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

class DjclassifiedsModelCheckout extends JModelLegacy{	
	
	function getItem($id){
		$db   = JFactory::getDBO();
		$app  = JFactory::getApplication();		
		$date_now = date("Y-m-d H:i:s");
		$quantity = JRequest::getInt('quantity', 0);
		$query ="SELECT i.*, c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
			   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
			   ."WHERE i.id=".$id." AND i.published=1 AND c.published=1 AND i.date_start <= '".$date_now."' AND i.date_exp >= '".$date_now."' LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();

		if(!$item){
			$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');
			$message = JText::_("COM_DJCLASSIFIEDS_ITEM_NOT_AVAILABLE");
			$redirect = JRoute::_($redirect);
			$app->redirect($redirect,$message);
		}else if($quantity>$item->quantity){
			$redirect = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
			$message = JText::_("COM_DJCLASSIFIEDS_NUMBER_OF_PRODUCTS_IS_LESS_THEN_SELECTED");
			$redirect = JRoute::_($redirect);
			$app->redirect($redirect,$message);
		}
		
		return $item;
	}	
	
	
	function getItemOptions($id,$opt_id){
		$db   = JFactory::getDBO();
		$app  = JFactory::getApplication();

			$query ="SELECT f.* FROM #__djcf_fields_values_sale f "
					."WHERE f.id=".$opt_id." AND f.item_id =".$id." ORDER BY f.id";
			$db->setQuery($query);
			$item_opt =$db->loadObject();
			
			$opt_name = '';
			if($item_opt){
				$options = json_decode($item_opt->options);							
				foreach($options as $o){
					if($opt_name){ $opt_name .= ' - ';}
					$opt_name .= $o->label.': '.$o->value;
				}
			}
									
		return $opt_name;
	}
	
	
	
}

