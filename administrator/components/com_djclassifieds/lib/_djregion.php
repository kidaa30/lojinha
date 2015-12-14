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
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djcategory.php');

class RegItem{
	var $id;
	var $name;
	var $parent_id;
	var $parent_name;
	var $published;


	function __construct(){
		$id=null;
		$name=null;
		$parent_id=null;
		$parent_name=null;
		$published=null;
	}
	
}

class DJClassifiedsRegion {
	
var $parent_id;
var $id;
var $name;
var $childs = Array();

function __construct(){
$parent_id=null;
$id=null;
$name=null;
$childs[]=null;
$elem[]=null;
}

public function getRegSelect(){
	$regions = DJClassifiedsRegion::getRegions();
	
	$sort_regions = DJClassifiedsRegion::getRegListSelect($regions);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

public function getRegAll(){
	$regions = DJClassifiedsRegion::getRegions();
	
	$sort_regions = DJClassifiedsRegion::getRegListAll($regions);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

	
public	function getRegions(){
		if(empty($this->_allregions)) {						
		
			$db= &JFactory::getDBO();	
			$query = "SELECT r.*, rr.name as parent_name FROM #__djcf_regions r "
					."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
					."ORDER BY r.parent_id,r.name";
			
			$db->setQuery($query);
			$this->_allregions=$db->loadObjectList();
			//echo '<pre>';print_r($db);print_r($this->_allregions);die();
		}
		return $this->_allregions;
	}	
	
public function getRegListSelect(& $lists, $option=Array()){

	$i=0;
	foreach($lists as $i =>$list){

		$op= new DJOptionList;
		$op->text=$list->name;
		$op->value=$list->id;
		$option[]=$op;
			
			unset($lists[$i]);			
			$act_parent=0;			
			$ii=0;		
		   	foreach($lists as $ii=>$li){
				if($li->parent_id==$list->id){		
					$child= new DJOptionList;
					$child->text=' - '.$li->name;
					$child->value=$li->id;					
					$act_parent = $li->parent_id;
					$option[]=$child;	
					unset($lists[$ii]);											
				}else if($act_parent!=0){
					break;
				}				
			}
				
	}
	return($option);		
}

public function getRegListAll(& $lists, $option=Array()){
	$i=0;
	foreach($lists as $i =>$list){
				
		$reg_item =  new RegItem;
		$reg_item->id=$list->id;
		$reg_item->name=$list->name;	
		$reg_item->parent_id=$list->parent_id;
		$reg_item->parent_name=$list->parent_name;
		$reg_item->published=$list->published;
				
		$option[]=$reg_item;
		unset($lists[$i]);
		
			$act_parent=0;			
			$ii=0;		
		   	foreach($lists as $ii=>$li){
				if($li->parent_id==$reg_item->id){		
					$child=new RegItem();
					$child->id=$li->id;
					$child->name=$li->name;	
					$child->parent_id=$li->parent_id;
					$child->parent_name=$li->parent_name;
					$child->published=$li->published;
					$act_parent = $li->parent_id;
					$option[]=$child;	
					unset($lists[$ii]);											
				}else if($act_parent!=0){
					break;
				}				
			}
				
	}
	return($option);		
}

}
