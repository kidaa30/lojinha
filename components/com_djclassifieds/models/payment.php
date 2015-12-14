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

class DjclassifiedsModelPayment extends JModelLegacy{	
	function getUserItem($id){
		$db = JFactory::getDBO();
		
		$query ="SELECT i.*, c.price as c_price, c.points as c_points FROM #__djcf_items i "
			   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
			   ."WHERE i.id=".$id." LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		
		return $item;
	}	
	
	function getDuration($day){
		$db= JFactory::getDBO();
		$query = "SELECT d.* FROM #__djcf_days d "
				."WHERE d.days=".$day;
		$db->setQuery($query);
		$day=$db->loadObject();

		return $day;
	}	

	function getPromotions(){
		$db= JFactory::getDBO();
		$query = "SELECT p.* FROM #__djcf_promotions p "
				."WHERE p.published=1 ORDER BY p.id ";
		$db->setQuery($query);
		$promotions=$db->loadObjectList();
		return $promotions;
	}	
	
	
	function getPoints($id){
		$app 	= JFactory::getApplication();
		$db		= JFactory::getDBO();
		$user   = JFactory::getUser();		
		$menus	= $app->getMenu('site');
		
		$query = "SELECT p.* FROM #__djcf_points p "
				."WHERE p.published=1 AND p.id=".$id.' LIMIT 1';
		$db->setQuery($query);
		$points=$db->loadObject();
		if($points){
			if($points->price==0 && $user->id){
				$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
						."VALUES ('".$user->id."','".$points->points."','\"".$points->name."\" - ".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')."')";
				$db->setQuery($query);
				$db->query();
				
				$menu_ppackages_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=points',1);
				$user_upoints_link='index.php?option=com_djclassifieds&view=userpoints';
				if($menu_upoints_itemid){
					$user_upoints_link .= '&Itemid='.$menu_upoints_itemid->id;
				}
				$app->redirect($user_upoints_link,JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES_ADDED'));
			}
		}
		return $points;
	}
	
		function getUserPoints(){				
			$user = JFactory::getUser();
			$db= JFactory::getDBO();
			
			$query = "SELECT SUM(p.points)FROM #__djcf_users_points p "
					."WHERE p.user_id='".$user->id."' ";										
				
			$db->setQuery($query);
			$points_count=$db->loadResult();			
			//echo '<pre>';print_r($db);print_r($points_count);echo '<pre>';die();	
			return $points_count;
		}	
		
		function getPlan($id){
			$db= JFactory::getDBO();
			$query = "SELECT p.* FROM #__djcf_plans p "
					."WHERE p.published=1 AND p.id=".$id;
			$db->setQuery($query);
			$plan=$db->loadObject();
			return $plan;
		}
		
		
		function activateMoveToTopPromotion($id){
			
			$app  = JFactory::getApplication();
			$par  = JComponentHelper::getParams( 'com_djclassifieds' );
			$user = JFactory::getUser();
			$db   = JFactory::getDBO();
			$id   = JRequest::getInt('id', 0);
			
			
			$query ="SELECT i.*, c.points as c_points, c.autopublish as c_autopublish, c.alias as c_alias FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."WHERE i.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			$redirect_a=0;
			if(!$item){
				$redirect_a=1;
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			}
			if($item->user_id!=$user->id){
				$redirect_a=1;
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			}
			if($user->id==0){
				$redirect_a=1;
				$message = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
			}
				
			if($redirect_a){
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message);
			}
			
			$query = "SELECT SUM(p.points)FROM #__djcf_users_points p "
					."WHERE p.user_id='".$user->id."' ";
			
			$db->setQuery($query);
									
				$message = JText::_('COM_DJCLASSIFIEDS_POINTS_PAYMENT_CONFIRMED_PROMOTION_MOVE_TO_TOP_ACTIVATED');
				$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
		
				$date_sort=date("Y-m-d H:i:s");
				$query = "UPDATE #__djcf_items SET date_sort='".$date_sort."' "
						."WHERE id=".$item->id." ";
				$db->setQuery($query);
				$db->query();
		
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message);									
		}
	
}

