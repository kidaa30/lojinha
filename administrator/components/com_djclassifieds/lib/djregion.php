<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djcategory.php');

JHTML::_('behavior.modal');

class RegionItem{
	var $id;
	var $name;
	var $parent_id;
	var $parent_name;
	var $city;
	var $country;	
	var $published;
	var $level;

	function __construct(){
		$id=null;
		$name=null;
		$parent_id=null;
		$parent_name=null;
		$city=null;
		$country=null;	
		$published=null;
		$level=0;
		$items_count;
	}
	
}

class DJClassifiedsRegion {
	
var $parent_id;
var $id;
var $name;
var $childs = Array();
var $level;

function __construct(){
	$parent_id=null;
	$id=null;
	$name=null;
	$childs[]=null;
	$elem[]=null;
	$level=0;
	$items_count=0;
}

public static function getRegSelect(){
	//$regions = DJClassifiedsRegion::getRegions();
	$regions_main = DJClassifiedsRegion::getRegionsMain();
	$regions_sort = DJClassifiedsRegion::getRegionsSortParent();
	
	$sort_regions = DJClassifiedsRegion::getListSelect($regions_main,$regions_sort);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

public static function getRegAll(){
	$regions_main = DJClassifiedsRegion::getRegionsMain();
	$regions_sort = DJClassifiedsRegion::getRegionsSortParent();
	
	$sort_regions = DJClassifiedsRegion::getListAll($regions_main,$regions_sort);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

public static function getSubReg($id,$show_count='0'){
	$regions_main = DJClassifiedsRegion::getRegionsMain();
	$regions_sort = DJClassifiedsRegion::getRegionsSortParent();
		
	$sort_regs = DJClassifiedsRegion::getListSubreg($regions_main,$regions_sort,$id);
	
	
	if($show_count){
		$max_level = '0';
		foreach ($sort_regs as $r){
			if($r->level>$max_level){
				$max_level = $r->level;
			}
		}
	
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;
			for($r=count($sort_regs);$r>0;$r--){
				if($parent_value>0 && $level>$sort_regs[$r-1]->level){
					$sort_regs[$r-1]->items_count = $sort_regs[$r-1]->items_count + $parent_value;
					$parent_value=0;
				}
				if($level==$sort_regs[$r-1]->level){
					$parent_value =$parent_value + $sort_regs[$r-1]->items_count;
				}
			}
		}
		//echo '<pre>';print_r($sort_regs);echo '</pre>';die();
	}
	return $sort_regs;
	
}
	
public static function getMenuRegions($rid='0',$show_count='0'){
	$regions_main = DJClassifiedsRegion::getRegionsMain();
	$regions_sort = DJClassifiedsRegion::getRegionsSortParent();
	$sort_regs = DJClassifiedsRegion::getListAll($regions_main,$regions_sort);
	
	//$sort_regs = DJClassifiedsRegion::getListAll($regs,$regs);
	if($show_count){			
		$max_level = '0';			
		foreach ($sort_regs as $r){		
			if($r->level>$max_level){
				$max_level = $r->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($r=count($sort_regs);$r>0;$r--){
				if($parent_value>0 && $level>$sort_regs[$r-1]->level){
					$sort_regs[$r-1]->items_count = $sort_regs[$r-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_regs[$r-1]->level){		
					$parent_value =$parent_value + $sort_regs[$r-1]->items_count;
				}
			}		
		}
	}
	
	$reg_path=','.$rid.',';		
	if($rid>0){								
		$reg_id = $rid;
		while($reg_id!=0){	
			foreach($sort_regs as $r){
				if($r->id==$reg_id){
					$reg_id=$r->parent_id;
					$reg_path .= $reg_id.',';
					break;
				}
			}
		}			
	}
	
	$menu_regs = array();
	for($i=0;$i<count($sort_regs);$i++){		
		if(strstr($reg_path,','.$sort_regs[$i]->id.',') || strstr($reg_path,','.$sort_regs[$i]->parent_id.',')){
			$menu_regs[] = $sort_regs[$i]; 
		}
		
	}
	$ret = array();
	$ret[]= $menu_regs;
	$ret[]= $reg_path;
	$ret[]= $sort_regs;
	
	return $ret;
}	

public static function getParentPath($rid='0'){	
	$regs = DJClassifiedsRegion::getRegions();
	$reg_path=Array();
	
	if(count($regs)){		
		while($rid!=0){	
			foreach($regs as $r){
				if($r->id==$rid){
					$rid=$r->parent_id;
					$reg_path[] = $r; 					
					break;
				}
			}
		}
	}		

	//echo '<pre>';print_r($reg_path);echo '</pre>';die();
	
	return $reg_path;
	
}

private static $_regions =null;
private static $_regions_sort_parent =null;

	public static function getRegions(){
		
		if(!self::$_regions){
			self::$_regions = array();
		}		
		if(self::$_regions){
			return self::$_regions;
		}								
		
			$db= JFactory::getDBO();	
			$date_now = date("Y-m-d H:i:s");
			
			$query = "SELECT r.*, rr.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_regions r "
					."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
					."LEFT JOIN (SELECT i.region_id, count(i.id) as items_count "
								."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.region_id) i ON i.region_id=r.id "
					."ORDER BY r.parent_id, r.name COLLATE utf8_polish_ci";
			
				$db->setQuery($query); 
				$allregions=$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($allregions);die();
				self::$_regions = $allregions;
		return self::$_regions ;
	}	
	
	
	public static function getRegionsMain(){
		$db= JFactory::getDBO();
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT r.*, rr.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_regions r "
				."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
				."LEFT JOIN (SELECT i.region_id, count(i.id) as items_count "
							."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.region_id) i ON i.region_id=r.id "						
				."WHERE r.parent_id=0 ORDER BY r.parent_id, r.name COLLATE utf8_polish_ci";
			
		$db->setQuery($query);
		$allregions=$db->loadObjectList('id');
		//echo '<pre>';print_r($db);print_r($this->_allregions);die();
		//echo '<pre>';print_r($this->_allregions);
	
		//echo '<pre>';print_r($regions);die();
	
		return $allregions;
	}
	
	public static function getRegionsSortParent(){
		if(!self::$_regions_sort_parent){
			self::$_regions_sort_parent = array();
		}
		if(self::$_regions_sort_parent){
			return self::$_regions_sort_parent;
		}
		$db= JFactory::getDBO();
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT r.*, rr.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_regions r "
				."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
				."LEFT JOIN (SELECT i.region_id, count(i.id) as items_count "
							."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.region_id) i ON i.region_id=r.id "
				."ORDER BY r.parent_id, r.name COLLATE utf8_polish_ci ";
			
		$db->setQuery($query);
		$allregions=$db->loadObjectList('id');
		//echo '<pre>';print_r($db);print_r($this->_allregions);die();
		//echo '<pre>';print_r($allregions);die();
		$regions = array();
		foreach($allregions as $reg){
			if(!isset($regions[$reg->parent_id])){
				$regions[$reg->parent_id] = array();
			}
			$regions[$reg->parent_id][] = $reg;
		}
		self::$_regions_sort_parent = $regions;
		//echo '<pre>';print_r($regions);die();
	
		return $regions;
	}	
	
	
	public static function getListSelect(& $lists,& $lists_const,& $option=Array()){
		
		foreach($lists as $list){
			$op= new DJOptionList;
				$op->text=$list->name;
				$op->value= $list->id;
				$option[]=$op;
			$childs=Array();
				
			if(isset($lists_const[$list->id])){
				for($i=0;$i<count($lists_const[$list->id]);$i++){
					$child=new RegionItem();
					$child->id=$lists_const[$list->id][$i]->id;
					$child->parent_id=$lists_const[$list->id][$i]->parent_id;
					if(isset($list->level)){
						$child->level=$list->level+1;	
					}else{
						$child->level=1;
					}
					
					$new_name=$lists_const[$list->id][$i]->name;
						for($lev=0;$lev<$child->level;$lev++){
							$new_name="- ".$new_name;
						}
					$child->name=$new_name;
					$childs[]=$child;
				}
				DJClassifiedsRegion::getListSelect($childs,$lists_const,$option);
				//echo count($lists_const).' ';
				unset($lists_const[$list->id]);
			}
				
		}
		
		return($option);		
	}

	public static function getListAll(& $lists,& $lists_const,& $option=Array()){
	
		foreach($lists as $list){			
				$cat_item =  new RegionItem();
				$cat_item->id=$list->id;
				$cat_item->name=$list->name;
				$cat_item->parent_id=$list->parent_id;
				$cat_item->parent_name=$list->parent_name;
				$cat_item->country=$list->country;
				$cat_item->city=$list->city;
				$cat_item->published=$list->published;
				$cat_item->items_count= $list->items_count;
				
				if(isset($list->level)){
					$cat_item->level= $list->level;	
				}else{
					$cat_item->level= 0;
				}
				
						
				$option[]=$cat_item;				
				$childs=Array();
											
				if(isset($lists_const[$list->id])){					
					for($i=0;$i<count($lists_const[$list->id]);$i++){						
						$child=new RegionItem();
						$child->id=$lists_const[$list->id][$i]->id;
						$child->name=$lists_const[$list->id][$i]->name;						
						$child->parent_id=$lists_const[$list->id][$i]->parent_id;
						$child->parent_name=$lists_const[$list->id][$i]->parent_name;
						$child->country=$lists_const[$list->id][$i]->country;
						$child->city=$lists_const[$list->id][$i]->city;
						$child->published=$lists_const[$list->id][$i]->published;
						$child->items_count=$lists_const[$list->id][$i]->items_count;
						
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}												
						$childs[]=$child;
					}
					DJClassifiedsRegion::getListAll($childs,$lists_const,$option);
					//echo count($lists_const).' ';
					unset($lists_const[$list->id]);
				}
									
		}
					   
		return($option);		
	}

	
	public static function getListSubreg(& $lists,& $lists_const, $main_id=0, $main_level=0,$main_f =0 , & $option=Array()){
	
		$liczba = count($lists_const);
		foreach($lists as $list){									
				if(isset($list->level)){
					$current_level= $list->level;	
				}else{
					$current_level= 0;
				}
								
				if($main_f==1 && ($main_level>$current_level || $current_level==$main_level)){
					break;
				}
				
				if($main_id==$list->id){
					$main_f=1;	
					$main_level = $current_level;
				}
				
		
				
				if($main_f==1 && $main_level<$current_level){
					$cat_item =  new CatItem;
					$cat_item->id=$list->id;
					$cat_item->name=$list->name;
					$cat_item->parent_id=$list->parent_id;
					$cat_item->parent_name=$list->parent_name;
					$cat_item->country=$list->country;
					$cat_item->city=$list->city;
					$cat_item->published=$list->published;					
					$cat_item->level= $current_level;
					$cat_item->items_count= $list->items_count;
							
					$option[]=$cat_item;
				}
					$childs=Array();			

					if(isset($lists_const[$list->id])){
						for($i=0;$i<count($lists_const[$list->id]);$i++){
							$child=new RegionItem();
							$child->id=$lists_const[$list->id][$i]->id;
							$child->name=$lists_const[$list->id][$i]->name;
							$child->parent_id=$lists_const[$list->id][$i]->parent_id;
							$child->parent_name=$lists_const[$list->id][$i]->parent_name;
							$child->country=$lists_const[$list->id][$i]->country;
							$child->city=$lists_const[$list->id][$i]->city;
							$child->published=$lists_const[$list->id][$i]->published;
							$child->items_count=$lists_const[$list->id][$i]->items_count;
					
							if(isset($list->level)){
								$child->level=$list->level+1;
							}else{
								$child->level=1;
							}
							$childs[]=$child;
						}
						
						DJClassifiedsRegion::getListSubreg($childs,$lists_const,$main_id,$main_level,$main_f,$option);
						//echo count($lists_const).' ';
						unset($lists_const[$list->id]);
					}
																		
		}
		return($option);		
	}
}
