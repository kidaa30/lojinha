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

class modDjClassifiedsMaps
{
	static function getItems($params){
		$app		= JFactory::getApplication();
		$db			= JFactory::getDBO();
		$date_time 	= JFactory::getDate();
		$date_exp	= $date_time->toSQL();
		$user		= JFactory::getUser();
		
		$ord = "i.date_start DESC";
	
		if($params->get('items_ord')==1){
			$ord = "i.display DESC"; 
		}else if($params->get('items_ord')==2){
			$ord = "rand()";
		}	
		
		if($params->get('follow_search',1)==1 && JRequest::getInt('se',0)!=0){
			$where = '';
			$search ='';
			$search_fields='';
			$cat_id = 0;
			$reg_id = 0;
			$search_radius_v = '';
			$search_radius_h = '';
						
				if(JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'),'','string')!=JText::_('COM_DJCLASSIFIEDS_SEARCH')){
					$search_word = $db->Quote('%'.$db->escape(JRequest::getVar('search','','','string'), true).'%');
					$search = " AND (CONCAT(i.name,i.intro_desc,i.description) LIKE ".$search_word." OR c.name LIKE ".$search_word." OR r.name LIKE ".$search_word." ) ";
				}
				if(isset($_GET['se_cats'])){
					if(is_array($_GET['se_cats'])){
						$cat_id= end($_GET['se_cats']);
						if($cat_id=='' && count($_GET['se_cats'])>2){
							$cat_id =$_GET['se_cats'][count($_GET['se_cats'])-2];
						}
					}else{
						$cat_ids = explode(',', JRequest::getVar('se_cats'));
						$cat_id = end($cat_ids);
					}								
				}
					
				$cat_id = str_ireplace('p', '', $cat_id);
				$cat_id = (int)$cat_id;
					
				if($cat_id>0){
					$cats= DJClassifiedsCategory::getSubCat($cat_id,1);
					$catlist= $cat_id;
					foreach($cats as $c){
						$catlist .= ','. $c->id;
					}
					$search .= ' AND i.cat_id IN ('.$catlist.') ';
						
					$search_fields = self::getSearchFields();
				}
					
				if(isset($_GET['se_regs'])){					
					if(is_array($_GET['se_regs'])){
						$reg_id= end($_GET['se_regs']);
						if($reg_id=='' && count($_GET['se_regs'])>2){
							$reg_id =$_GET['se_regs'][count($_GET['se_regs'])-2];
						}						
					}else{
						$reg_ids = explode(',', JRequest::getVar('se_regs'));
						$reg_id = end($reg_ids);
					}
					$reg_id=(int)$reg_id;					
				}
				
				
			
			
				if($reg_id>0){
					$regs= DJClassifiedsRegion::getSubReg($reg_id,1);
			
					$reglist= $reg_id;
					foreach($regs as $r){
						$reglist .= ','. $r->id;
					}
					$search .= ' AND i.region_id IN ('.$reglist.') ';
				}
			
					
				$se_price_from = JRequest::getInt('se_price_f','');
				$se_price_to = JRequest::getInt('se_price_t','');
				if($se_price_from){
					$search .= " AND ABS(i.price) >= ".$se_price_from." ";
				}
					
				if($se_price_to && $se_price_to>$se_price_from){
					$search .= " AND ABS(i.price) <= ".$se_price_to." ";
				}
					
				$type_id=JRequest::getInt('se_type_id','0');
				if($type_id>0){
					$where .= " AND i.type_id=".$type_id." ";
				}
					
				$days_l=JRequest::getInt('days_l','0');
				if($days_l>0){
					$date_limit = date("Y-m-d H:i:s",mktime(date("H"), date("i"), date("s"), date("m"), date("d")-$days_l, date("Y")));
					$where .= " AND i.date_start >= '".$date_limit."' ";
				}
					
				$only_img=JRequest::getInt('se_only_img',0);
				if($only_img==1){
				//	$search .= " AND img.name !='' ";
				}
					
				$only_video=JRequest::getInt('se_only_video',0);
				if($only_video==1){
					$search .= " AND i.video!='' ";
				}
					
				$postcode=JRequest::getVar('se_postcode','');
				$radius=JRequest::getInt('se_radius',0);
				if($postcode!='' && $postcode != JText::_('COM_DJCLASSIFIEDS_SEARCH_MODULE_POSTCODE') && $radius){
					$postcode_country=JRequest::getVar('se_postcode_c','');
					$post_coord = DJClassifiedsGeocode::getLocationPostCode($postcode,$postcode_country);
			
					$radius_unit=JRequest::getCmd('se_radius_unit','km');
					if($radius_unit=='mile'){
						$radius_unit_v = 3959;
					}else{
						$radius_unit_v = 6371;
					}
					if($post_coord){
						$search_radius_v = ', ( '.$radius_unit_v.' * acos( cos( radians('.$post_coord['lat'].') ) * cos( radians( i.latitude ) ) * cos( radians( i.longitude ) - radians('.$post_coord['lng'].') ) + sin( radians('.$post_coord['lat'].') ) * sin( radians( i.latitude ) ) ) ) AS distance ';
						$search_radius_h = 'HAVING distance < '.$radius.' ';
					}else{
						$app->enqueueMessage(JText::_('COM_DJCLASSIFIEDS_SORRY_WE_CANT_FIND_COORDINATES_FROM_POSTCODE_WE_OMIITED_RANGE_RESTRICTION'),'notice');
					}
				}
				
				$groups_acl = '0,'.implode(',', $user->getAuthorisedViewLevels());
				$where .= " AND c.access_view IN (" . $groups_acl . ") ";
				$date_now = date("Y-m-d H:i:s");
				
				$query = "SELECT i.*, c.name AS c_name,c.alias AS c_alias, c.id as c_id, r.name as r_name, r.id as r_id "
								//.$search_radius_v.", img.path as img_path, img.name as img_name, img.ext as img_ext,img.caption as img_caption FROM ".$search_fields." #__djcf_categories c, #__djcf_items i "
								.$search_radius_v." FROM ".$search_fields." #__djcf_categories c, #__djcf_items i "
						."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
						//."LEFT JOIN ( SELECT img.id, img.item_id, img.name, img.path, img.ext, img.ordering, img.caption 
		 				//	  FROM (SELECT * FROM #__djcf_images WHERE type='item' ORDER BY ordering) img GROUP BY img.item_id ) AS img ON img.item_id=i.id "
						."WHERE i.date_exp > '".$date_now."' AND i.published=1 AND i.cat_id = c.id "
						.$where;
					
				if($search_fields){
					$query .=" AND sf.item_id=i.id ";
				}
				$query .= $search_radius_h.$search." ORDER BY i.special DESC, ".$ord."";
				$db->setQuery($query);
				$items=$db->loadObjectList();					

				//echo '<pre>'; print_r($items);die();
			
		}else{		
			$where='';
			$cid = JRequest::getInt('cid','0');
					
			if($params->get('follow_category',1)==1 && $cid>0){
				$djcfcatlib = new DJClassifiedsCategory();
				$cats= $djcfcatlib->getSubCat($cid,1);				
				$catlist= $cid;			
				foreach($cats as $c){
					$catlist .= ','. $c->id;
				}
				$where .= ' AND i.cat_id IN ('.$catlist.') ';	
				
			}
						
			$groups_acl = '0,'.implode(',', $user->getAuthorisedViewLevels());
			$where .= " AND c.access_view IN (" . $groups_acl . ") ";
			
			$query = "SELECT i.*, c.name as c_name, c.alias as c_alias "
					."FROM #__djcf_categories c, #__djcf_items i  "
					//."LEFT JOIN ( SELECT img.id, img.item_id, img.name, img.path, img.ext, img.ordering, img.caption 
		 			//		  FROM (SELECT * FROM #__djcf_images WHERE type='item' ORDER BY ordering) img GROUP BY img.item_id ) AS img ON img.item_id=i.id "
					."WHERE i.date_exp > '".$date_exp."' AND i.published = 1 AND i.cat_id=c.id AND i.latitude!='0.000000000000000' AND i.longitude!='0.000000000000000' "	
					.$where." "
					."ORDER BY ".$ord." limit ".$params->get('items_limit');
			$db->setQuery($query);
			$items=$db->loadObjectList();
		}
		
		if(count($items)){
			$id_list= '';
			foreach($items as $item){
				if($id_list){
					$id_list .= ','.$item->id;
				}else{
					$id_list .= $item->id;
				}
			}
		
			$items_img = DJClassifiedsImage::getAdsImages($id_list);
		
			for($i=0;$i<count($items);$i++){									
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
		
		
		return $items;
	}
	
	static function getRegions(){
		$db= JFactory::getDBO();
		$query = "SELECT r.* FROM #__djcf_regions r "
				."WHERE published=1 ORDER BY r.parent_id ";

		$db->setQuery($query);
		$regions=$db->loadObjectList();

		return $regions;
	}
	
	static function getSearchFields(){
		$search_fields = '';
			$session = JFactory::getSession();	
			
			if(is_array($_GET['se_cats'])){
				$cat_id= end($_GET['se_cats']);
				if($cat_id=='' && count($_GET['se_cats'])>2){
					$cat_id =$_GET['se_cats'][count($_GET['se_cats'])-2];
				}
			}else{
				$cat_ids = explode(',', JRequest::getVar('se_cats'));
				$cat_id = end($cat_ids);
			}						
			$cat_id = str_ireplace('p', '', $cat_id);
			$cat_id = (int)$cat_id;
			
			$db=JFactory::getDBO();
			$query = "SELECT f.* FROM #__djcf_fields f, #__djcf_fields_xref fx "
					."WHERE fx.field_id=f.id AND fx.cat_id=".$cat_id."";
			$db->setQuery($query);
			$fields=$db->loadObjectList();
				
				$search_fields = 'SELECT * FROM (SELECT COUNT( * ) AS c, item_id FROM (';
				$sf_count = 0;
			
				foreach($fields as $f){
					if($f->type=='date'){						
						if($f->search_type=='inputbox_min_max'){
							if(isset($_GET['se_'.$f->id.'_min']) && $_GET['se_'.$f->id.'_max']){	
								$f_v1 =  $db->escape($_GET['se_'.$f->id.'_min']);
								$f_v2 =  $db->escape($_GET['se_'.$f->id.'_max']);
								if($f_v1 && $f_v1){
									$sf_count ++;	
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value_date >= '".$f_v1."'";
									$search_fields .= " AND f.value_date <= '".$f_v2."' ) UNION ";
									$session->set('se_'.$f->id.'_min',$f_v1);
									$session->set('se_'.$f->id.'_max',$f_v2);							
								}
							}
							if(!isset($_GET['se_'.$f->id.'_min'])){
								$session->set('se_'.$f->id.'_min','');
							}
							if(!isset($_GET['se_'.$f->id.'_max'])){
								$session->set('se_'.$f->id.'_max','');
							} 								
						}else{
							if(isset($_GET['se_'.$f->id])){							
								$f_v1 =  $db->escape($_GET['se_'.$f->id]);
								
								if($f_v1!=''){
									$sf_count ++;
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value_date = '".$f_v1."' ) UNION ";
									$session->set('se_'.$f->id,$f_v1);								
								}else{
									$session->set('se_'.$f->id,'');	
								}
							}else{
								$session->set('se_'.$f->id,'');	
							}
						}
					}else{	
						if($f->search_type=='select_min_max' || $f->search_type=='inputbox_min_max'){
							if(isset($_GET['se_'.$f->id.'_min']) && $_GET['se_'.$f->id.'_max']){	
								$f_v1 =  $db->escape($_GET['se_'.$f->id.'_min']);
								$f_v2 =  $db->escape($_GET['se_'.$f->id.'_max']);
								if($f_v1 && $f_v1){
									$sf_count ++;	
									if(is_numeric($f_v1) && is_numeric($f_v2)){
										$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value >= ".$f_v1."";
										$search_fields .= " AND f.value <= ".$f_v2." ) UNION ";	
									}else{
										$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value >= '".$f_v1."'";
										$search_fields .= " AND f.value <= '".$f_v2."' ) UNION ";
									}
									$session->set('se_'.$f->id.'_min',$f_v1);
									$session->set('se_'.$f->id.'_max',$f_v2);
								}
							}
							if(!isset($_GET['se_'.$f->id.'_min'])){
								$session->set('se_'.$f->id.'_min','');
							}
							if(!isset($_GET['se_'.$f->id.'_max'])){
								$session->set('se_'.$f->id.'_max','');
							} 
								
						}elseif($f->search_type=='checkbox'){
							if(isset($_GET['se_'.$f->id])){
								$v_chec = array();
								$v_chec =  $_GET['se_'.$f->id];
								$f_v1 =';';
								//print_R($f);die();
								if(count($v_chec)>0){
									$sf_count ++;
									if($f->type=='checkbox'){
										$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." ";
										if(count($v_chec)){
											$search_fields .= " AND (";											
											for($ch=0;$ch<count($v_chec);$ch++){
												if(!$ch){
												   $search_fields .= " f.value LIKE '%;".$db->escape($v_chec[$ch]).";%' OR f.value LIKE '".$db->escape($v_chec[$ch]).";%' ";
												}else{
												   $search_fields .= " OR f.value LIKE '%;".$db->escape($v_chec[$ch]).";%' OR f.value LIKE '".$db->escape($v_chec[$ch]).";%'  ";
												}
												$f_v1 .= $v_chec[$ch].';';
											}	
											$search_fields .=") ";
										}									
									}else{								
										$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." ";
										for($ch=0;$ch<count($v_chec);$ch++){
											if($ch==0){
												$search_fields .= " AND ( f.value = '".$db->escape($v_chec[$ch])."' ";	
											}else{
												$search_fields .= " OR f.value = '".$db->escape($v_chec[$ch])."' ";
											}																				
											$f_v1 .= $v_chec[$ch].';';
										}
										$search_fields .= ') ';									
									}
										$search_fields .= " ) UNION ";
										$session->set('se_'.$f->id,$f_v1);
								}
								
							}else{
								$session->set('se_'.$f->id,'');	
							}						
						}else{
							if(isset($_GET['se_'.$f->id])){							
								$f_v1 =  $db->escape($_GET['se_'.$f->id]);
								
								if($f_v1!=''){
									$sf_count ++;
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value LIKE '%".$f_v1."%' ) UNION ";
									$session->set('se_'.$f->id,$f_v1);								
								}else{
									$session->set('se_'.$f->id,'');	
								}
							}else{
								$session->set('se_'.$f->id,'');	
							}						
						}
					}						
				}

					if($sf_count>0){
						$search_fields = '('.substr($search_fields, 0, -6);
						$search_fields .= ' ) AS f GROUP BY f.item_id ) f WHERE f.c = '.$sf_count.' ) sf, ';	
					}else{
						$search_fields = '';
					}
					
					//print_r($search_fields);die();

					
					return $search_fields;
				
	}	

	static function getAdvert(){
		$advert_id = JRequest::getInt('id',0);
		$db= JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_items i "
				."WHERE i.published=1 AND i.id=".$advert_id." LIMIT 1 ";	
		$db->setQuery($query);
		$advert=$db->loadObject();
	
		return $advert;
	}	
	
}