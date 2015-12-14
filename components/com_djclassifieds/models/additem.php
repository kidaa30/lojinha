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

class DjclassifiedsModelAddItem extends JModelLegacy{	

	function getItem()
	{
		$app	= JFactory::getApplication();
		$id 	= JRequest::getVar('id', 0, '', 'int' );
		$token 	= JRequest::getCMD('token', '');
        $row 	= JTable::getInstance('Items', 'DJClassifiedsTable');
        $db		= JFactory::getDBO();
        
		if($id>0){						
			$user=JFactory::getUser();			
			$row->load($id);
			
			if($user->id!=$row->user_id || $user->id==0){
				$message = JText::_("COM_DJCLASSIFIEDS_WRONG_AD");
				$redirect= 'index.php?option=com_djclassifieds&view=additem' ;
				$app->redirect($redirect,$message,'error');		
			}
		}else if($token){
			$query = "SELECT i.id FROM #__djcf_items i "
					."WHERE i.user_id=0 AND i.token=".$db->Quote($db->escape($token));			
			$db->setQuery($query);
			$id=$db->loadResult();
			if($id){
				$row->load($id);
			}			
		}
	  	
        return $row;
	}
	
		function getCategories(){
			$db		= JFactory::getDBO();
			$user 	= JFactory::getUser();
			
			$lj = '';
			$ls = '';						
			$g_list = '0';
			if($user->groups){
				$g_list = implode(',',$user->groups);	
			}									
			if (!$g_list){
				$g_list = '0';
			}
			if($user->id){
				$ls=',g.g_active';
				$lj="LEFT JOIN (SELECT COUNT(id) as g_active, cat_id FROM #__djcf_categories_groups " 
				   ."WHERE group_id in(".$g_list.") GROUP BY cat_id ) g ON g.cat_id=c.id ";
				$lj_where = ' AND (c.access=0 OR (c.access=1 AND g.g_active>0 ))';
			}else{
				$lj_where = ' AND c.access=0 ';	
			}
			$query = "SELECT c.* ".$ls." FROM #__djcf_categories c "
					.$lj
					."WHERE c.published=1 ".$lj_where
					."ORDER BY c.parent_id, c.ordering ";
	
			$db->setQuery($query);
			$cats=$db->loadObjectList();
			//echo '<pre>';print_r($db);print_r($cats);die();
	
			return $cats;
	}
	
	function getRegions(){
			$db	= JFactory::getDBO();
			$query = "SELECT r.* FROM #__djcf_regions r "
					."WHERE r.published=1 "
					."ORDER BY r.parent_id, r.name COLLATE utf8_polish_ci ";
	
			$db->setQuery($query);
			$regions=$db->loadObjectList();
	
			return $regions;
	}
	
	function getTermsLink($id){
			$db= JFactory::getDBO();
			$query = "SELECT a.id, a.alias, a.catid, c.alias as c_alias FROM #__content a "
					."LEFT JOIN #__categories c ON c.id=a.catid "
					."WHERE a.state=1 AND a.id=".$id;
			
			$db->setQuery($query);
			$article=$db->loadObject();
			
			return $article;	
	}
	

	function getDays(){
			$db= JFactory::getDBO();
			$query = "SELECT d.* FROM #__djcf_days d "
					."WHERE d.published=1 "
					."ORDER BY d.days ";
	
			$db->setQuery($query);
			$days=$db->loadObjectList('days');
	
			return $days;
	}	

	function getPromotions(){
			$db= JFactory::getDBO();
			$query = "SELECT p.* FROM #__djcf_promotions p "
					."WHERE p.published=1 "
					."ORDER BY p.ordering,p.id ";
	
			$db->setQuery($query);
			$promotions=$db->loadObjectList();
	
			return $promotions;
	}	
	
	function getCustomContactFields(){
		global $mainframe;
		$id 	= JRequest::getInt('id', '0');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$token  = JRequest::getCMD('token', '' );
			
		if($user->id==0){
			$id=0;
		}
		
		$item='';
		if($id>0){
			$query = "SELECT * FROM #__djcf_items WHERE id='".$id."' LIMIT 1";
			$db->setQuery($query);
			$item =$db->loadObject();
			if($item->user_id!=$user->id){
				$id=0;
			}
		}else if($token){
		 	$query = "SELECT * FROM #__djcf_items WHERE token='".addslashes($token)."' AND user_id=0 LIMIT 1";
		 	$db->setQuery($query);
		 	$item =$db->loadObject();	
			if($item){
				$id=$item->id;
			}
			
		 }
		$query ="SELECT f.*, v.value, v.value_date FROM #__djcf_fields f "
				."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
						."ON v.field_id=f.id "
				."WHERE f.published=1 AND f.source=1 ORDER BY f.ordering";
		$db->setQuery($query);
		$fields_list =$db->loadObjectList();
		//echo '<pre>'; print_r($db);print_r($fields_list);die();
			
		return $fields_list;
		
	}	
	
	function getUserItemsCount(){
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$query = "SELECT COUNT(id) FROM #__djcf_items WHERE user_id='".$user->id."' ";
			$db->setQuery($query);
			$user_itesms_c =$db->loadResult();
		
		return $user_itesms_c;
	}
	
	function getItemImages($item_id)
	{
		$images = array();
		if($item_id){
			$db 	= JFactory::getDBO();
			$query  = "SELECT * FROM #__djcf_images i "
					."WHERE i.type='item' AND i.item_id=".$item_id." ORDER BY ordering";
			$db->setQuery($query);
			$images=$db->loadObjectList();
		}								
		
		return $images;
	}
	
	function getItemsUnits(){
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$query = "SELECT id, name FROM #__djcf_items_units WHERE published= 1 ORDER BY ordering  ";
		$db->setQuery($query);
		$units =$db->loadObjectList();
		
		return $units;
	}
	
}

