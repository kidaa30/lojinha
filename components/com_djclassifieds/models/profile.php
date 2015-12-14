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

class DjclassifiedsModelProfile extends JModelLegacy{	
	
	
	function getItems($uid){
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$limit		= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$user 		= JFactory::getUser();
		$db			= JFactory::getDBO();
		$app		= JFactory::getApplication();
		//$uid		= JRequest::getVar('uid','0','','int');						
		
			$where = " AND i.user_id=".$uid." ";
			
			$fav_s='';
			$fav_lj='';
			if($par->get('favourite','1') && $user->id>0){
				$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
				$fav_s = ',f.id as f_id ';
				$fav=JRequest::getVar('fav','0','','int');				
				if($fav>0){
					$where .= " AND f.id IS NOT NULL ";
				}
			}

			$distance_v = '';	
			if($par->get('column_distance','0') && isset($_COOKIE["djcf_latlon"])){
				$distance_unit=JRequest::getCmd('column_distance_unit','km');
				$user_latlog = explode('_', $_COOKIE["djcf_latlon"]);									
				if($distance_unit=='mile'){
					$distance_unit_v = 3959;
				}else{
					$distance_unit_v = 6371;					
				}
				$distance_v = ', ( '.$distance_unit_v.' * acos( cos( radians('.$user_latlog[0].') ) * cos( radians( i.latitude ) ) * cos( radians( i.longitude ) - radians('.$user_latlog[1].') ) + sin( radians('.$user_latlog[0].') ) * sin( radians( i.latitude ) ) ) ) AS distance_latlon ';
			}	
				
			 
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
				$ord="ABS(i.price) ";
			}elseif($order=="display"){
				$ord="i.display ";
			}elseif($order=="date_a"){
				$ord="i.date_start ";
			}elseif($order=="date_e"){
				$ord="i.date_exp ";
			}elseif($order=="date_sort"){
				$ord="i.date_sort ";
			}elseif($order=="distance"){
				$ord = ($distance_v ? "distance_latlon " : "i.date_exp ");
			}elseif($order=="random"){
				$ord="RAND() ";
			}
		
			if($ord_t == 'desc'){
				$ord .= 'DESC';
			}else{
				$ord .= 'ASC';
			}
			
			$date_time = JFactory::getDate();
			$date_exp=$date_time->toSQL();
			$date_now = date("Y-m-d H:i:s");

			$query = "SELECT i.*, c.name AS c_name,c.alias AS c_alias, c.id as c_id, r.name as r_name, r.id as r_id, img.img_c ".$fav_s.$distance_v." FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON i.cat_id = c.id "
					."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
					."LEFT JOIN ( SELECT COUNT(img.id) as img_c, img.item_id FROM #__djcf_images img
								 WHERE img.type='item' GROUP BY item_id ) img ON i.id=img.item_id "
					.$fav_lj
					."WHERE i.date_exp > '".$date_now."' AND i.published=1 "
					.$where;		

				$query .= " ORDER BY i.special DESC, ".$ord."";
				$items = $this->_getList($query, $limitstart, $limit);													
			
			//echo '<pre>';print_r($db);print_r($items);echo '<pre>';die();
			
			if(count($items)){
				$id_list= '';
				foreach($items as $item){
					if($id_list){
						$id_list .= ','.$item->id;
					}else{
						$id_list .= $item->id;
					}
				}
					
				
				$query = "SELECT fv.item_id, fv.field_id, fv.value, fv.value_date,f.label, f.type FROM #__djcf_fields_values fv,#__djcf_fields f "
						."WHERE fv.item_id IN (".$id_list.") AND f.id=fv.field_id AND (f.in_table>0 OR f.in_blog=1) AND f.access=0 AND f.published=1 "
						."ORDER BY fv.item_id, f.label";
				$db->setQuery($query);
				$custom_fields=$db->loadObjectList();
				//echo '<pre>';print_r($custom_fields);die();
								
				$items_img = DJClassifiedsImage::getAdsImages($id_list);
				
				for($i=0;$i<count($items);$i++){
					$cf_found =0;
					$items[$i]->fields = array();
					foreach($custom_fields as $cf){
						if($items[$i]->id==$cf->item_id){
							$cf_found =1;
							if($cf->type=='date'){
								if($cf->value_date!='0000-00-00'){
									$items[$i]->fields[$cf->field_id] = $cf->value_date;
								}							
							}else{
								$items[$i]->fields[$cf->field_id] = $cf->value;
							}
							
						}else if($cf_found){
							break;
						}
					}
					
					$img_found =0;
					$items[$i]->images = array();
					foreach($items_img as $img){
						if($items[$i]->id==$img->item_id){
							$img_found =1;							
							$items[$i]->images[]=$img;								
						}else if($img_found){
							break;
						}
					}					
				}
			}
			
			//$db= &JFactory::getDBO();$db->setQuery($query);$items=$db->loadObjectList();
			//echo '<pre>';print_r($db);print_r($items);echo '<pre>';die();				
		return $items;
	}
	
	function getCountItems($uid){
			$par =	JComponentHelper::getParams( 'com_djclassifieds' );
			$user =	JFactory::getUser();
			$db = 	JFactory::getDBO();
			
			$where = " AND i.user_id=".$uid." ";

			$fav_lj='';
			if($par->get('favourite','1') && $user->id>0){
				$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
				$fav=JRequest::getVar('fav','0','','int');				
				if($fav>0){
					$where .= " AND f.id IS NOT NULL ";
				}
			}
					
			$date_time = JFactory::getDate();
			$date_exp=$date_time->toSQL();
			$date_now = date("Y-m-d H:i:s");
			
			$query = "SELECT count(i.id) FROM (SELECT i.id FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON i.cat_id = c.id "
					."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
					."LEFT JOIN ( SELECT COUNT(img.id) as img_c, img.item_id FROM #__djcf_images img
								WHERE img.type='item' GROUP BY item_id ) img ON i.id=img.item_id "
					.$fav_lj
					."WHERE i.date_exp > '".$date_now."' AND i.published=1 "
					.$where;		
										
					$query .=" ) as i ";
					
						
				
				$db->setQuery($query);
				$items_count=$db->loadResult();
				
				//echo '<pre>';print_r($db);print_r($items_count);echo '<pre>';die();	
			return $items_count;
	}	
	
	function getProfile($uid){						
			$par = JComponentHelper::getParams( 'com_djclassifieds' );
			$db= JFactory::getDBO();
			$profile = array();
			$profile['id'] = $uid;
						
				if($par->get('authorname','name')=='name'){
					$u_name = 'name ';
				}else{
					$u_name = 'username as name';
				}
												
				$query = "SELECT ".$u_name." FROM #__users WHERE id=".$uid." LIMIT 1";
				$db->setQuery($query);
				$profile['name'] = $db->loadResult();
				
				$query ="SELECT * FROM #__djcf_images WHERE item_id = ".$uid." AND type='profile' LIMIT 1 ";
				$db->setQuery($query);
				$profile['img']=$db->loadObject();
				
				$query ="SELECT f.*, v.value, v.value_date FROM #__djcf_fields f "
						."LEFT JOIN (SELECT * FROM #__djcf_fields_values_profile WHERE user_id=".$uid.") v "
								."ON v.field_id=f.id "
						."WHERE f.published=1 AND f.source=2 AND f.access=0 ORDER BY f.ordering";
				$db->setQuery($query);
				$profile['data']= $db->loadObjectList();
				
			return $profile;
	}
	
	
	
	
	
	
	function getTypes(){						
		$db= JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_types WHERE published=1";
		$db->setQuery($query);
		$types=$db->loadObjectList('id');			
			foreach($types as $type){
				$registry = new JRegistry();		
				$registry->loadString($type->params);
				$type->params = $registry->toObject();
			}
		//echo '<pre>';print_r($types);die();		
		return $types;
	}	
	
	function getCustomFields(){
		$db= JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_fields WHERE published=1 AND (in_table>0 OR in_blog=1) ORDER BY name";
		$db->setQuery($query);
		$cf=$db->loadObjectList('id');
		//echo '<pre>';print_r($cf);die();
		return $cf;
	}

}

