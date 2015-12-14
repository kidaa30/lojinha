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


class DjclassifiedsModelItem extends JModelLegacy{	

	function getItem(){
		$par =	JComponentHelper::getParams( 'com_djclassifieds' );
		$id = 	JRequest::getVar('id', 0, '', 'int');	
		$user = JFactory::getUser();
		
		$fav_s='';
		$fav_lj='';
		if($par->get('favourite','1') && $user->id>0){
			$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
			$fav_s = ',f.id as f_id ';
		}
		
		$type_s='';
		$type_lj='';
		if($par->get('show_types','0')){
			$type_lj = "LEFT JOIN #__djcf_types t ON t.id = i.type_id ";
			$type_s = ', t.id as t_id, t.name as t_name,t.params as t_params ';
		}

		if($par->get('authorname','name')=='name'){
			$u_name = 'u.name as username';
		}else{
			$u_name = 'u.username';
		}
		

		$db= JFactory::getDBO();
		$query = "SELECT i.*,c.name as c_name,c.alias as c_alias, c.access_view as c_access_view, c.access_item_view as c_access_item_view,  ".$u_name.", u.email as u_email, iu.name as unit_name ".$fav_s.$type_s." FROM #__djcf_categories c, #__djcf_items i "
				.$fav_lj.$type_lj
				."LEFT JOIN #__users u ON u.id = i.user_id "
				."LEFT JOIN #__djcf_items_units iu ON iu.id = i.unit_id "				
				."WHERE c.id=i.cat_id AND i.id = ".$id." LIMIT 1 ";
		$db->setQuery($query);
		$item=$db->loadObject();
		//echo '<pre>';print_r($db);print_r($item);die();
		
		return $item;
	}
	
	function getCategory($cid){		
		$db= JFactory::getDBO();
		$query = "SELECT c.* FROM #__djcf_categories c WHERE id='".$cid."' LIMIT 1 ";
		$db->setQuery($query);
		$cat=$db->loadObject();
		return $cat;
	}
	
	function getUserItemsCount($uid){		
		$db= JFactory::getDBO();				
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT COUNT(i.id) FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_now."' AND i.user_id  = ".$uid." ";
		$db->setQuery($query);
		$item=$db->loadResult();
		return $item;
	}
	
/*	function makePathway($item_name){
		global $mainframe;
			$db= &JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_categories";
			$db->setQuery($query);
			$list=$db->loadObjectList();
					
		$document	=& JFactory::getDocument();
		$cid	= JRequest::getVar('cid', 0, '', 'int');
		$pathway =& $mainframe->getPathway();				
		$id = Array();
		$name = Array();
		
		if($cid!=0){
			while($cid!=0){	
				foreach($list as $li){
					if($li->id==$cid){
						$cid=$li->parent_id;
						$id[]=$li->id;
						$name[]=$li->name;
						break;
					}
				}
			}
		}

		
		for($i=count($id)-1;$i>-1;$i--){				
			if($i!=0){
				$pathway->addItem($name[$i], 'index.php?option=com_djclassifieds&view=show&cid='.$id[$i]);	
			}else{
				$pathway->addItem($name[$i], 'index.php?option=com_djclassifieds&view=showlist&cid='.$id[$i]);
			}
		}
		$pathway->addItem($item_name);
		
	}
	*/
	
	function getFields($cid){
		
		$id= JRequest::getVar('id', 0, '', 'int');	
		$db= JFactory::getDBO();
		$query ="SELECT f.*, v.value,v.value_date FROM #__djcf_fields f "
			    ."LEFT JOIN #__djcf_fields_xref fx ON f.id=fx.field_id " 
		 		."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
				."ON v.field_id=f.id "
		 		."WHERE fx.cat_id  = ".$cid." AND f.published=1 AND f.access=0 AND f.name!='price' AND f.name!='contact' ORDER BY fx.ordering, f.ordering ";
	     
		$db->setQuery($query);
		$item=$db->loadObjectList('id');
		
		return $item;
	}
	
	function geContactFields(){
	
		$id= JRequest::getVar('id', 0, '', 'int');
		$db= JFactory::getDBO();
		$query ="SELECT f.*, v.value,v.value_date FROM #__djcf_fields f "
				."LEFT JOIN #__djcf_fields_xref fx ON f.id=fx.field_id "
				."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
						."ON v.field_id=f.id "
				."WHERE f.source=1 AND f.published=1 AND f.access=0 AND f.name!='price' AND f.name!='contact' GROUP BY f.id ORDER BY fx.ordering, f.ordering ";
	
		$db->setQuery($query);
		$item=$db->loadObjectList('id');
	
		return $item;
	}
	
	function getRegions(){
		$db= JFactory::getDBO();
		$query = "SELECT r.* FROM #__djcf_regions r "
				."WHERE published=1 ORDER BY r.parent_id ";

		$db->setQuery($query);
		$regions=$db->loadObjectList();

		return $regions;
	}
	
	function getItemPayment($id){
	
		$db= JFactory::getDBO();
		$query ="SELECT COUNT(p.id) FROM #__djcf_payments p "
				."WHERE p.item_id = ".$id." AND p.type=0 AND p.price>0 ";	
		$db->setQuery($query);
		$item=$db->loadResult();				
	
		return $item;
	}
	
	function getProfile($id){
		$db		 = JFactory::getDBO();
		$profile = array();
		
			$query ="SELECT * FROM #__djcf_images WHERE item_id = ".$id." AND type='profile' LIMIT 1 ";
			$db->setQuery($query);
			$profile['img']=$db->loadObject();
			
			$query ="SELECT f.*, v.value, v.value_date FROM #__djcf_fields f "
					."LEFT JOIN (SELECT * FROM #__djcf_fields_values_profile WHERE user_id=".$id.") v "
							."ON v.field_id=f.id "
					."WHERE f.published=1 AND f.source=2 AND f.in_item=1 AND f.access=0 ORDER BY f.ordering";
			$db->setQuery($query);
			$profile['data']= $db->loadObjectList();

		return $profile;
	}

	function getBids($id,$limit=0){
		$bids = '';
		if($id){			
			$db= JFactory::getDBO();
			$query = "SELECT a.*, u.name as u_name FROM #__djcf_auctions a, #__users u "
					." WHERE a.user_id=u.id AND a.item_id=".$id." ORDER BY a.date DESC";
			if($limit){
				$query .= " LIMIT ".$limit; 
			} 
			$db->setQuery($query);
			$bids=$db->loadObjectList();
			//echo '<pre>';print_r($cf);die();
		}
		return $bids;
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
	
	function getCustomAskSellerFields(){
		global $mainframe;
		$id 	= JRequest::getInt('id', '0');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
				
	
		$fields_list='';
		if($id>0){		
			$query ="SELECT f.* FROM #__djcf_fields f "
					."WHERE f.published=1 AND f.source=3 ORDER BY f.name";
			$db->setQuery($query);
			$fields_list =$db->loadObjectList();
			//echo '<pre>'; print_r($db);print_r($fields_list);die();
		}	
		return $fields_list;
	
	}
	
	function getItemOptions($id){
		global $mainframe;
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
	
	
		$options_list='';
		if($id>0){
			$query ="SELECT f.* FROM #__djcf_fields_values_sale f "
					."WHERE f.item_id =".$id." ORDER BY f.id";
			$db->setQuery($query);
			$options_list =$db->loadObjectList();
			foreach($options_list as &$opt){
				$opt->options = json_decode($opt->options);
			}
			//echo '<pre>'; print_r($db);print_r($options_list);die();

			//$options = json_decode($fl->options);
		}
		return $options_list;
	
	}
	
}