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
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');
JHTML::_('behavior.modal');

class DJOptionList{
var $text;
var $value;
var $disable;	

function __construct(){
$text=null;
$value=null;			
$disable=null;
}
	
}

class CatItem{
	var $id;
	var $name;
	var $alias;
	var $price;
	var $points;
	var $description;
	var $parent_id;
	var $parent_name;
	var $icon_url;
	var $ordering;	
	var $published;
	var $autopublish;
	var $theme;
	var $level;	
	var $items_count;
	var $access;
	var $ads_disabled;
	var $restriction_18;

	function __construct(){
		$id=null;
		$name=null;
		$alias=null;
		$price=null;
		$points=null;
		$description=null;
		$parent_id=null;
		$parent_name=null;
		$icon_url=null;
		$ordering=null;	
		$published=null;
		$autopublish=null;
		$theme=null;
		$access=null;
		$ads_disabled=null;
		$restriction_18=null;
		$items_count=0;
		$level=0;
	}
	
}

class DJClassifiedsCategory {
	
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
}

public static function getCatSelect($pub='0',$ord='ord'){
	$cats = DJClassifiedsCategory::getCategoriesSortParent($pub,$ord);
	
	if(isset($cats[0])){
		$sort_cats = DJClassifiedsCategory::getListSelect($cats[0],$cats);
	}else{
		$sort_cats = array();
	}
	//echo '<pre>';print_r($cats);echo '</pre>';die();
	
	return $sort_cats;
	
}

public static function getCatAll($pub='0',$ord='ord',$ord_dir='ASC'){	
	
	$cats = DJClassifiedsCategory::getCategoriesSortParent($pub,$ord,$ord_dir);
	
	
		if(isset($cats[0])){
			$sort_cats = DJClassifiedsCategory::getListAll($cats[0],$cats);
		}else{
			$sort_cats = array();
		}
			
	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	
	return $sort_cats;
	
}

public static function getCatAllItemsCount($pub='0',$ord='ord',$hide_empty='0'){
	$cats = DJClassifiedsCategory::getCategoriesSortParent($pub,$ord);
	
	if(isset($cats[0])){
		$sort_cats = DJClassifiedsCategory::getListAll($cats[0],$cats);
	}else{
		$sort_cats = array();
	}
	
	$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}		
		
		//echo '<pre>';print_r($sort_cats);die();
		if($hide_empty){
			$cat_items = array();
			for($i=0;$i<count($sort_cats);$i++){
				if($sort_cats[$i]->items_count){					
					$cat_items[]=$sort_cats[$i];
				}	
			}
			return $cat_items; 	
		}else{						
			return $sort_cats;			
		}
	
}

public static function getSubCat($id,$pub='0',$ord='ord'){
	
	$cats = DJClassifiedsCategory::getCategoriesSortParent($pub,$ord);
	
	if(isset($cats[0])){
		$sort_cats = DJClassifiedsCategory::getListSubcat($cats[0],$cats,$id);
	}else{
		$sort_cats = array();
	}
	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	
	return $sort_cats;
	
}
public static function getSubCatIemsCount($id,$pub='0',$ord='ord',$hide_empty='0'){

	//$cats = DJClassifiedsCategory::getCategories($pub,$ord);
	//$sort_cats = DJClassifiedsCategory::getListSubcat($cats,$cats,$id);
		
	$cats = DJClassifiedsCategory::getCategoriesSortParent($pub,$ord);
	
	if(isset($cats[0])){
		$sort_cats = DJClassifiedsCategory::getListSubcat($cats[0],$cats,$id);
	}else{
		$sort_cats = array();
	}

	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}	
		
		
		if($hide_empty){
			$cat_items = array();
			for($i=0;$i<count($sort_cats);$i++){
				if($sort_cats[$i]->items_count){					
					$cat_items[]=$sort_cats[$i];
				}	
			}
			return $cat_items; 	
		}else{						
			return $sort_cats;			
		}
	
}
	
public static function getParentPath($pub='0',$cid='0',$ord='ord'){
	$cats = DJClassifiedsCategory::getCategories($pub,$ord);
	$cat_path=Array();
	
	if(count($cats)){		
		while($cid!=0){			
			if(isset($cats[$cid])){
				$subcat=new DJClassifiedsCategory();
				$subcat->id=$cats[$cid]->id;
				$subcat->name=$cats[$cid]->name;
				$subcat->alias=$cats[$cid]->alias;
				$subcat->parent_id=$cats[$cid]->parent_id;
				$subcat->theme=$cats[$cid]->theme;
				$subcat->restriction_18=$cats[$cid]->restriction_18;
				$cat_path[]=$subcat;
				$cid=$cats[$cid]->parent_id;
			}else{
				break;
			}
		}
	}		
	//echo '<pre>';print_r($cat_path);echo '</pre>';die();
	
	return $cat_path;
	
}	

	public static function getSEOParentPath($cid='0'){
		$cats = DJClassifiedsCategory::getCategories('1','ord');			
		$cat_path=Array();
			
			while($cid!=0){
				if(isset($cats[$cid])){					
					$cat_path[] = $cats[$cid]->id.':'.$cats[$cid]->alias;
					$cid=$cats[$cid]->parent_id;
				}else{
					break;
				}				
			}
	
		//echo '<pre>';print_r($cat_path);echo '</pre>';die();				
		//return array_reverse($cat_path);
		return $cat_path;
	
	} 


public static function getMenuCategories($cid='0',$show_count='1',$ord='ord',$hide_empty='0',$parent_id='0', $only_current_lvl = '0'){
	$cats = DJClassifiedsCategory::getCategoriesSortParent(1,$ord);
	$cats_all = $cats;
	if(isset($cats[0])){
		$sort_cats = DJClassifiedsCategory::getListAll($cats[0],$cats);
	}else{
		$sort_cats = array();
	}

	
	 
	if($show_count){			
		$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}
	}
	
	$cat_path=','.$cid.',';		
	if($cid>0){								
		$cat_id = $cid;
		while($cat_id!=0){	
			foreach($sort_cats as $c){
				if($c->id==$cat_id){
					$cat_id=$c->parent_id;
					$cat_path .= $cat_id.',';
					break;
				}
			}
		}			
	}
	$menu_cats = array();
	$empty_cat_level = 0;
	for($i=0;$i<count($sort_cats);$i++){	
		$sort_cats[$i]->have_childs = 0;
		if(strstr($cat_path,','.$sort_cats[$i]->id.',') || strstr($cat_path,','.$sort_cats[$i]->parent_id.',')){
			if(isset($cats_all[$sort_cats[$i]->id])){
				$sort_cats[$i]->have_childs = 1;
			}else{
				$sort_cats[$i]->have_childs = 0;
			}			
			if($hide_empty){
				if($sort_cats[$i]->items_count>0){
					$menu_cats[] = $sort_cats[$i];	
				}				 
			}else{
				$menu_cats[] = $sort_cats[$i]; 	
			}			
		}				
	}
	if($only_current_lvl){
		$cats_lvl = array();
		foreach($menu_cats as $cat){
			if($cat->parent_id==$parent_id){
				$cat->level=0;
				$cats_lvl[] = $cat;
			}
		}
		$menu_cats = $cats_lvl;
		$cat_path=','.$cid.',';	
		
	}
	//echo '<pre>';print_R($menu_cats);die();
	
	$ret = array();
	$ret[] = $menu_cats;
	$ret[] = $cat_path;
	$ret[] = $sort_cats;
	
	return $ret;
}

private static $_categories =null;
private static $_categories_sparent =null;

	
public static function getCategories($p='0',$ord='ord'){
		$user = JFactory::getUser();
	
		if(!self::$_categories){
			self::$_categories = array();			
		}
		
		if(isset(self::$_categories[$p.'_'.$ord])){
			return self::$_categories[$p.'_'.$ord];
		}
		
			if($p){
				$pub = 'WHERE c.published=1 ';
				$groups_acl = implode(',', $user->getAuthorisedViewLevels());
				$access_view = " AND c.access_view IN (" . $groups_acl . ") ";
			}else{
				$pub ='';
				$access_view = '';
			}
			
			if($ord=='name'){
				$order = 'c.name';
			}else{
				$order = 'c.ordering';
			}						
			
			
			$db= JFactory::getDBO();	
			$date_now = date("Y-m-d H:i:s");
			
			$query = "SELECT c.*, cc.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_categories c "
					."LEFT JOIN #__djcf_categories cc ON c.parent_id=cc.id "
					."LEFT JOIN (SELECT i.cat_id, count(i.id) as items_count "
								."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.cat_id) i ON i.cat_id=c.id "
					.$pub.$access_view
					."ORDER BY c.parent_id, ".$order;
			
				$db->setQuery($query);
				$allcategories=$db->loadObjectList('id');
				foreach($allcategories as $cat){
					if(!$cat->alias){
						$cat->alias = DJClassifiedsSEO::getAliasName($cat->name);
					}
				}
				//echo '<pre>';print_r($db);print_r($allcategories);die();
			self::$_categories[$p.'_'.$ord] = $allcategories;
		return self::$_categories[$p.'_'.$ord];
	}	
	
	public static function getCategory($cid, $p='0'){
		$user = JFactory::getUser();
	
		if($p){
			$pub = ' AND c.published=1 ';
			$groups_acl = implode(',', $user->getAuthorisedViewLevels());
			$access_view = " AND c.access_view IN (" . $groups_acl . ") ";
		}else{
			$pub ='';
			$access_view = '';
		}
	
			
			
		$db= JFactory::getDBO();
		$date_now = date("Y-m-d H:i:s");
			
		$query = "SELECT c.*, cc.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_categories c "
				."LEFT JOIN #__djcf_categories cc ON c.parent_id=cc.id "
				."LEFT JOIN (SELECT i.cat_id, count(i.id) as items_count "
						."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.cat_id) i ON i.cat_id=c.id "
								."WHERE c.id= ".$cid." ".$pub.$access_view." LIMIT 1";
			
		$db->setQuery($query);
		$category=$db->loadObject();
		return $category;
	}
	
	
	public static function getCategoriesSortParent($p='0',$ord='ord',$ord_dir='ASC'){
		$user = JFactory::getUser();
		
		if(!self::$_categories_sparent){
			self::$_categories_sparent = array();
		}
	
		if(isset(self::$_categories_sparent[$p.'_'.$ord])){
			return self::$_categories_sparent[$p.'_'.$ord];
		}
		if($p){
			$pub = 'WHERE c.published=1 ';
			$groups_acl = implode(',', $user->getAuthorisedViewLevels());
			$access_view = " AND c.access_view IN (" . $groups_acl . ") ";
		}else{
			$pub ='';
			$access_view = '';
		}
			
		if($ord=='name'){
			$order = 'c.name';
		}else if($ord=='id'){
			$order = 'c.id';
		}else if($ord=='price'){
			$order = 'c.price';
		}else if($ord=='pub'){
			$order = 'c.published';
		}else{
			$order = 'c.ordering';
		}				
			
			
		$db= JFactory::getDBO();
		$date_now = date("Y-m-d H:i:s");
			
		$query = "SELECT c.*, cc.name as parent_name,IFNULL(i.items_count,0) items_count FROM #__djcf_categories c "
				."LEFT JOIN #__djcf_categories cc ON c.parent_id=cc.id "
				."LEFT JOIN (SELECT i.cat_id, count(i.id) as items_count "
						."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' GROUP BY i.cat_id) i ON i.cat_id=c.id "
								.$pub.$access_view
								."ORDER BY c.parent_id, ".$order." ".$ord_dir;
			
		$db->setQuery($query);
		$allcategories=$db->loadObjectList();
		
		$categories = array();
		foreach($allcategories as $cat){
			if(!$cat->alias){
				$cat->alias = DJClassifiedsSEO::getAliasName($cat->name);
			}
			if(!isset($categories[$cat->parent_id])){
				$categories[$cat->parent_id] = array();
			}
			$categories[$cat->parent_id][] = $cat;
		}
				
		
		//echo '<pre>';print_r($db);print_r($allcategories);die();
		self::$_categories_sparent[$p.'_'.$ord] = $categories;
		return self::$_categories_sparent[$p.'_'.$ord];
	}
	

	
	
	public static function getListSelect(& $lists,& $lists_const,& $option=Array()){
	
		foreach($lists as $list){
	
			$op= new DJOptionList;
			$op->text=$list->name;;
			$op->value=$list->id;
			
				$option[]=$op;
				$childs=Array();

				if(isset($lists_const[$list->id])){
					for($i=0;$i<count($lists_const[$list->id]);$i++){
						$child=new DJOptionList();
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
					DJClassifiedsCategory::getListSelect($childs,$lists_const,$option);
					//echo count($lists_const).' ';
					unset($lists_const[$list->id]);
				}														
		}
		return($option);		
	}

	public static function getListAll(& $lists,& $lists_const,& $option=Array()){
	
		foreach($lists as $list){
				
				$cat_item =  new CatItem;
				$cat_item->id=$list->id;
				$cat_item->name=$list->name;
				$cat_item->alias=$list->alias;
				$cat_item->price=$list->price;
				$cat_item->points=$list->points;
				$cat_item->description=$list->description;
				$cat_item->parent_id=$list->parent_id;
				$cat_item->parent_name=$list->parent_name;
				$cat_item->icon_url=$list->icon_url;
				$cat_item->ordering=$list->ordering;
				$cat_item->published=$list->published;
				$cat_item->autopublish=$list->autopublish;
				$cat_item->theme=$list->theme;
				$cat_item->access=$list->access;
				$cat_item->ads_disabled=$list->ads_disabled;
				$cat_item->items_count= $list->items_count;
				$cat_item->restriction_18= $list->restriction_18;
				
				
				if(isset($list->level)){
					$cat_item->level= $list->level;	
				}else{
					$cat_item->level= 0;
				}
						
				$option[]=$cat_item;			
				$childs=Array();	
							
				if(isset($lists_const[$list->id])){
					for($i=0;$i<count($lists_const[$list->id]);$i++){					
						$child=new CatItem();
						$child->id=$lists_const[$list->id][$i]->id;
						$child->name=$lists_const[$list->id][$i]->name;
						$child->alias=$lists_const[$list->id][$i]->alias;
						$child->parent_id=$lists_const[$list->id][$i]->parent_id;
						$child->price=$lists_const[$list->id][$i]->price;
						$child->points=$lists_const[$list->id][$i]->points;
						$child->description=$lists_const[$list->id][$i]->description;
						$child->parent_id=$lists_const[$list->id][$i]->parent_id;
						$child->parent_name=$lists_const[$list->id][$i]->parent_name;
						$child->icon_url=$lists_const[$list->id][$i]->icon_url;
						$child->ordering=$lists_const[$list->id][$i]->ordering;
						$child->published=$lists_const[$list->id][$i]->published;
						$child->autopublish=$lists_const[$list->id][$i]->autopublish;
						$child->theme=$lists_const[$list->id][$i]->theme;
						$child->access=$lists_const[$list->id][$i]->access;
						$child->ads_disabled=$lists_const[$list->id][$i]->ads_disabled;
						$child->items_count=$lists_const[$list->id][$i]->items_count;
						$child->restriction_18=$lists_const[$list->id][$i]->restriction_18;
						
						if(isset($list->level)){
							$child->level=$list->level+1;
						}else{
							$child->level=1;
						}
						$childs[]=$child;
					}
					DJClassifiedsCategory::getListAll($childs,$lists_const,$option);
					//echo count($lists_const).' ';
					unset($lists_const[$list->id]);
				}				
		}
		return($option);		
	}

	public static function getListSubcat(& $lists,& $lists_const, $main_id=0, $main_level=0,$main_f =0 , & $option=Array()){

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
			
			
			if($main_f==1 && ($main_level<$current_level || $main_id==$list->id)){
				$cat_item =  new CatItem;
				$cat_item->id=$list->id;
				$cat_item->name=$list->name;
				$cat_item->alias=$list->alias;
				$cat_item->price=$list->price;
				$cat_item->points=$list->points;
				$cat_item->description=$list->description;
				$cat_item->parent_id=$list->parent_id;
				$cat_item->parent_name=$list->parent_name;
				$cat_item->icon_url=$list->icon_url;
				$cat_item->ordering=$list->ordering;
				$cat_item->published=$list->published;
				$cat_item->autopublish=$list->autopublish;
				$cat_item->theme=$list->theme;
				$cat_item->access=$list->access;
				$cat_item->ads_disabled=$list->ads_disabled;				
				$cat_item->items_count= $list->items_count;
				$cat_item->restriction_18= $list->restriction_18;
				$cat_item->level= $current_level;										
				$option[]=$cat_item;
			}
			
				$childs=Array();					
				   
			   if(isset($lists_const[$list->id])){
				   	for($i=0;$i<count($lists_const[$list->id]);$i++){
				   		$child=new CatItem();
				   		$child->id=$lists_const[$list->id][$i]->id;
				   		$child->name=$lists_const[$list->id][$i]->name;
				   		$child->alias=$lists_const[$list->id][$i]->alias;
				   		$child->parent_id=$lists_const[$list->id][$i]->parent_id;
				   		$child->price=$lists_const[$list->id][$i]->price;
				   		$child->points=$lists_const[$list->id][$i]->points;
				   		$child->description=$lists_const[$list->id][$i]->description;
				   		$child->parent_id=$lists_const[$list->id][$i]->parent_id;
				   		$child->parent_name=$lists_const[$list->id][$i]->parent_name;
				   		$child->icon_url=$lists_const[$list->id][$i]->icon_url;
				   		$child->ordering=$lists_const[$list->id][$i]->ordering; 
				   		$child->published=$lists_const[$list->id][$i]->published;
				   		$child->autopublish=$lists_const[$list->id][$i]->autopublish;
				   		$child->theme=$lists_const[$list->id][$i]->theme;
				   		$child->access=$lists_const[$list->id][$i]->access;
				   		$child->ads_disabled=$lists_const[$list->id][$i]->ads_disabled;				   		
				   		$child->items_count=$lists_const[$list->id][$i]->items_count;
				   		$child->restriction_18=$lists_const[$list->id][$i]->restriction_18;
				   
				   		if(isset($list->level)){
				   			$child->level=$list->level+1;
				   		}else{
				   			$child->level=1;
				   		}
				   		$childs[]=$child;
				   	}
				   	DJClassifiedsCategory::getListSubcat($childs,$lists_const,$main_id,$main_level,$main_f,$option);				   	
				   	//echo count($lists_const).' ';
				   	unset($lists_const[$list->id]);
				}				   
			}
		return($option);		
	}
}
