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

class DjclassifiedsModelUserItems extends JModelLegacy{	
	
	
	function getItems(){
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$limit		= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$user 		= JFactory::getUser();
		$db			= JFactory::getDBO();
			 
			 $order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
			 $ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
			
			$ord="i.date_exp ";
			
			if($order=="title"){
				$ord="i.name ";
			}elseif($order=="cat"){
				$ord="c.name ";
			}elseif($order=="loc"){
				$ord="r.name ";
			}elseif($order=="price"){
				$ord="i.price ";
			}elseif($order=="display"){
				$ord="i.display ";
			}elseif($order=="date_a"){
				$ord="i.date_start ";
			}elseif($order=="date_e"){
				$ord="i.date_exp ";
			}elseif($order=="published"){
				$ord="i.published ";
			}		
		
			if($ord_t == 'desc'){
				$ord .= 'DESC';
			}else{
				$ord .= 'ASC';
			}
			
			if($order=="active"){
				if($ord_t == 'desc'){
					$ord="i.published DESC, s_active DESC";					
				}else{
					$ord="i.published ASC, s_active ASC";
				}				
			}
			$date_now = date("Y-m-d H:i:s");
			
			$query = "SELECT i.*,c.id as c_id, c.name AS c_name, c.alias AS c_alias,r.id as r_id, r.name as r_name, i.date_start <= '".$date_now."' AND i.date_exp >= '".$date_now."' AS s_active FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON i.cat_id = c.id "
					."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
					."WHERE i.user_id='".$user->id."' " // AND i.published!=2
					."ORDER BY  ".$ord."";
		
			$items = $this->_getList($query, $limitstart, $limit);	
			
			if(count($items)){
				$id_list= '';
				foreach($items as $item){
					if($id_list){
						$id_list .= ','.$item->id;
					}else{
						$id_list .= $item->id;
					}
				}												
			
				$query = "SELECT img.* FROM #__djcf_images img "
						."WHERE img.item_id IN (".$id_list.") AND img.type='item' "
								."ORDER BY img.item_id, img.ordering";
				$db->setQuery($query);
				$items_img=$db->loadObjectList();
				
				
				if($par->get('authorname','name')=='name'){
					$u_name = 'u.name as username';
				}else{
					$u_name = 'u.username';
				}

				if($par->get('buynow')){ 
					$query = "SELECT o.*, ".$u_name.", u.email FROM #__djcf_orders o, #__users u "
							."WHERE u.id=o.user_id AND o.item_id IN (".$id_list.")  "
							."ORDER BY o.date DESC ";					
					$db->setQuery($query);
					$items_orders=$db->loadObjectList();					
				}
			
				for($i=0;$i<count($items);$i++){										
					$img_found =0;
					$items[$i]->images = array();
					foreach($items_img as $img){
						if($items[$i]->id==$img->item_id){
							$img_found =1;
							$img->thumb_s = $img->path.$img->name.'_ths.'.$img->ext;
							$img->thumb_m = $img->path.$img->name.'_thm.'.$img->ext;
							$img->thumb_b = $img->path.$img->name.'_thb.'.$img->ext;
							$items[$i]->images[]=$img;
						}else if($img_found){
							break;
						}
					}
					if($par->get('buynow')){
						$items[$i]->orders = array();
						foreach($items_orders as $order){
							if($items[$i]->id==$order->item_id){
								$items[$i]->orders[] = $order;
							}	
						}
					}
				}
				
			}
						
				//$db= JFactory::getDBO();$db->setQuery($query);$items=$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($items);echo '<pre>';die();	
			return $items;
	}
	
	function getCountItems(){
					
			$user = JFactory::getUser();
			$query = "SELECT count(i.id)FROM #__djcf_items i "
					."WHERE i.user_id='".$user->id."'  ";	// AND i.published!=2			
						
				$db= JFactory::getDBO();
				$db->setQuery($query);
				$items_count=$db->loadResult();
				
				//echo '<pre>';print_r($db);print_r($items_count);echo '<pre>';die();	
			return $items_count;
	}	
	
	function getItemToken(){
		$app	= JFactory::getApplication();
		$id 	= JRequest::getVar('id', 0, '', 'int' );
		$token 	= JRequest::getCMD('token', '');
		$db		= JFactory::getDBO();
			
			$query = "SELECT i.*,c.alias as c_alias FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."WHERE i.user_id=0 AND i.token=".$db->Quote($db->escape($token));
			$db->setQuery($query);
			$item=$db->loadObject();
			
			if(!$item){
				$message = JText::_("COM_DJCLASSIFIEDS_WRONG_AD");				
				$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');
				$redirect = JRoute::_($redirect);				
				$app->redirect($redirect,$message,'error');
			}	
	
		return $item;
	}

	function getItem(){
		$app	= JFactory::getApplication();
		$id 	= JRequest::getVar('id', 0, '', 'int' );
		$user 	= JFactory::getUser();
		$db		= JFactory::getDBO();
		
			
		$query = "SELECT i.*,c.alias as c_alias FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."WHERE i.user_id=".$user->id." AND i.id=".$id." ";
		$db->setQuery($query);
		$item=$db->loadObject();
			
		if(!$item){
			$message = JText::_("COM_DJCLASSIFIEDS_WRONG_AD");
			$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');
			$redirect = JRoute::_($redirect);
			$app->redirect($redirect,$message,'error');
		}
	
		return $item;
	}
	
}

