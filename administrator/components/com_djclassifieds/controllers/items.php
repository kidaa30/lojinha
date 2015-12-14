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

// No direct access.
defined('_JEXEC') or die;
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'tables');
jimport('joomla.application.component.controlleradmin');

class DJClassifiedsControllerItems extends JControllerAdmin
{
	public function getModel($name = 'Item', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	function recreateThumbnails(){	
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_RECREATING_THUMBNAILS'), 'generic.png');
	    
		$cid = JRequest::getVar( 'cid', array(), 'default', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_DJCLASSIFIEDS_SELECT_ITEM_TO_RECREATE_THUMBS ' ) );
		}
		
		$tmp = array();
		$tmp[0] = $cid[0];
		unset($cid[0]);		
			$db =  JFactory::getDBO();	
	        $query = "SELECT * FROM #__djcf_images WHERE item_id =  ".$tmp[0] ." AND type='item' ";
			$db->setQuery($query);
			$images = $db->loadObjectList();
			if($images){				
				$nw = (int)$par->get('th_width',-1);
				$nh = (int)$par->get('th_height',-1);
				$nws = $par->get('smallth_width',-1);
				$nhs = $par->get('smallth_height',-1);
				$nwm = $par->get('middleth_width',-1);
				$nhm = $par->get('middleth_height',-1);
				$nwb = $par->get('bigth_width',-1);
				$nhb = $par->get('bigth_height',-1);
				foreach($images as $image){
					$path = JPATH_SITE.$image->path.$image->name;	
					if (JFile::exists($path.'.'.$image->ext)){ 
        				if (JFile::exists($path.'_thb.'.$image->ext)){
            				JFile::delete($path.'_thb.'.$image->ext);
  						}
						if (JFile::exists($path.'_th.'.$image->ext)){
            				JFile::delete($path.'_th.'.$image->ext);
        				}
						if (JFile::exists($path.'_thm.'.$image->ext)){
            				JFile::delete($path.'_thm.'.$image->ext);
        				}
        				if (JFile::exists($path.'_ths.'.$image->ext)){
            				JFile::delete($path.'_ths.'.$image->ext);
        				}
						
				 		//DJClassifiedsImage::makeThumb($path.$images[$ii], $nws, $nhs, 'ths');
				 		DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_ths.'.$image->ext, $nws, $nhs);					
						DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_thm.'.$image->ext, $nwm, $nhm);
						DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_thb.'.$image->ext, $nwb, $nhb);
					}
				}
			}
		
	    
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED') );	
		} else {	        
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value; 
			}
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_RESIZING_ITEM').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=items.recreateThumbnails'.$cids);				        
	    }
	    //$redirect = 'index.php?option=com_djclassifieds&view=items';
	    //$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}

	
	function resmushitThumbnails(){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_OPTIMIZING_THUMBNAILS'), 'generic.png');
		 
		$cid = JRequest::getVar( 'cid', array(), 'default', 'array' );
		JArrayHelper::toInteger($cid);
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_DJCLASSIFIEDS_SELECT_ITEM_TO_OPTIMIZE_THUMBS ' ) );
		}
	
		$tmp = array();
		$tmp[0] = $cid[0];
		unset($cid[0]);
		$db =  JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_images WHERE item_id =  ".$tmp[0] ." AND type='item' AND optimized=0 ";
		$db->setQuery($query);
		$images = $db->loadObjectList();
		if($images){
			foreach($images as $image){
				$path = JPATH_SITE.$image->path.$image->name;
				if (JFile::exists($path.'.'.$image->ext)){
					if (JFile::exists($path.'_thb.'.$image->ext)){
						DJClassifiedsImage::resmushitThumbnails($path.'_thb.'.$image->ext);
					}
					if (JFile::exists($path.'_th.'.$image->ext)){
						DJClassifiedsImage::resmushitThumbnails($path.'_th.'.$image->ext);
					}
					if (JFile::exists($path.'_thm.'.$image->ext)){
						DJClassifiedsImage::resmushitThumbnails($path.'_thm.'.$image->ext);
					}
					if (JFile::exists($path.'_ths.'.$image->ext)){
						DJClassifiedsImage::resmushitThumbnails($path.'_ths.'.$image->ext);
					}						
				}
				$query = "UPDATE #__djcf_images SET optimized=1 WHERE id =  ".$image->id." ";
				$db->setQuery($query);
				$db->query();
			}						
		}
	
		 
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_OPTIMIZED') );
		} else {
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value;
			}
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_OPTIMALIZING_ITEM').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=items.resmushitThumbnails'.$cids);
		}
		//$redirect = 'index.php?option=com_djclassifieds&view=items';
		//$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}
	
	
	function migrateImages(){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_MIGRATING_IMAGES'), 'generic.png');		 
	
		$db =  JFactory::getDBO();
		$query = "SELECT id, image_url FROM #__djcf_items WHERE image_url IS NOT NULL AND image_url!='' ORDER BY id LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		
		if($item){	
			$path = JPATH_BASE."/../components/com_djclassifieds/images/";
			$nw = (int)$par->get('th_width',-1);
			$nh = (int)$par->get('th_height',-1);
			$nws = $par->get('smallth_width',-1);
			$nhs = $par->get('smallth_height',-1);
			$nwm = $par->get('middleth_width',-1);
			$nhm = $par->get('middleth_height',-1);
			$nwb = $par->get('bigth_width',-1);
			$nhb = $par->get('bigth_height',-1);
			$query = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
			$ord=1;
				$images = explode(";",$item->image_url);
				for($ii=0; $ii<count($images)-1;$ii++ ){
					if (JFile::exists($path.$images[$ii].'.thb.jpg')){
						JFile::delete($path.$images[$ii].'.thb.jpg');
					}
					if (JFile::exists($path.$images[$ii].'.th.jpg')){
						JFile::delete($path.$images[$ii].'.th.jpg');
					}
					if (JFile::exists($path.$images[$ii].'.thm.jpg')){
						JFile::delete($path.$images[$ii].'.thm.jpg');
					}
					if (JFile::exists($path.$images[$ii].'.ths.jpg')){
						JFile::delete($path.$images[$ii].'.ths.jpg');
					}

					$new_path = $path.'item/';
					rename($path.$images[$ii], $new_path.$images[$ii]);
					$name_parts = pathinfo($images[$ii]);
					$img_name = $name_parts['filename'];
					$img_ext = $name_parts['extension'];									
					
					//DJClassifiedsImage::makeThumb($path.$images[$ii], $nw, $nh, 'th');
					DJClassifiedsImage::makeThumb($new_path.$images[$ii],$new_path.$img_name.'_ths.'.$img_ext, $nws, $nhs);
					DJClassifiedsImage::makeThumb($new_path.$images[$ii],$new_path.$img_name.'_thm.'.$img_ext, $nwm, $nhm);
					DJClassifiedsImage::makeThumb($new_path.$images[$ii],$new_path.$img_name.'_thb.'.$img_ext, $nwb, $nhb);
					$query .= "('".$item->id."','item','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/item/','','".$ord."'), ";
					$ord++;
				}
				if($ord>1){
					$query = substr($query, 0, -2).';';
					$db->setQuery($query);
					$db->query();
					
					$query = "UPDATE #__djcf_items SET image_url='' WHERE id=".$item->id;
					$db->setQuery($query);
					$db->query();
				}
				
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_MIGRATING_IMAGES_FROM_ITEM').' [id = '.$item->id.']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=items.migrateImages');				
		}else{
			$this->setRedirect( 'index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_IMAGES_MIGRATED') );
		}
		 
		//$redirect = 'index.php?option=com_djclassifieds&view=items';
		//$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}	

	function migrateCatImages(){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_MIGRATING_IMAGES'), 'generic.png');
	
		$db =  JFactory::getDBO();
		$query = "SELECT id, icon_url FROM #__djcf_categories WHERE icon_url IS NOT NULL AND icon_url!='' ORDER BY id LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
	
		if($item){
			$path = JPATH_BASE."/../components/com_djclassifieds/images/";
			$nw = (int)$par->get('catth_width',-1);
			$nh = (int)$par->get('catth_height',-1);			
						
				$image = $item->icon_url;
							
				if (JFile::exists($path.$image.'.ths.jpg')){
					JFile::delete($path.$image.'.ths.jpg');
				}
	
				$new_path = $path.'category/';
				rename($path.$image, $new_path.$image);
				$name_parts = pathinfo($image);
				$img_name = $name_parts['filename'];
				$img_ext = $name_parts['extension'];
					
				DJClassifiedsImage::makeThumb($new_path.$image,$new_path.$img_name.'_ths.'.$img_ext, $nw, $nh);
				$query = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
				$query .= "('".$item->id."','category','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/category/','','1'); ";
				$db->setQuery($query);
				$db->query();
						
				$query = "UPDATE #__djcf_categories SET icon_url='' WHERE id=".$item->id;
				$db->setQuery($query);
				$db->query();			
	
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_MIGRATING_IMAGES_FROM_CATEGORY').' [id = '.$item->id.']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=items.migrateCatImages');
		}else{
			$this->setRedirect( 'index.php?option=com_djclassifieds&view=categories', JText::_('COM_DJCLASSIFIEDS_IMAGES_MIGRATED') );
		}
			
		//$redirect = 'index.php?option=com_djclassifieds&view=items';
		//$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}
	
	function recreateThumbnails_all(){	
		$app = JFactory::getApplication();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
	    $cid = JRequest::getVar('cid', array (), '', 'array');
		
	    $db = & JFactory::getDBO();
	    if (count($cid))
	    {
	        $cids = implode(',', $cid);
	        $query = "SELECT id, image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
			$db->setQuery($query);
			$items = $db->loadObjectList();
			$path = JPATH_BASE."/../components/com_djclassifieds/images/";
				$nw = (int)$par->get('th_width',-1);
	    		$nh = (int)$par->get('th_height',-1);
				$nws = $par->get('smallth_width',-1);
	    		$nhs = $par->get('smallth_height',-1);
				$nwm = $par->get('middleth_width',-1);
	    		$nhm = $par->get('middleth_height',-1);
				$nwb = $par->get('bigth_width',-1);
	    		$nhb = $par->get('bigth_height',-1);							
		
			foreach($items as $i){
				if($i->image_url){				
					$images = explode(";",$i->image_url);
					for($ii=0; $ii<count($images)-1;$ii++ ){												
	        				if (JFile::exists($path.$images[$ii].'.thb.jpg')){
	            				JFile::delete($path.$images[$ii].'.thb.jpg');
	  						}
							if (JFile::exists($path.$images[$ii].'.th.jpg')){
	            				JFile::delete($path.$images[$ii].'.th.jpg');
	        				}
							if (JFile::exists($path.$images[$ii].'.thm.jpg')){
	            				JFile::delete($path.$images[$ii].'.thm.jpg');
	        				}
	        				if (JFile::exists($path.$images[$ii].'.ths.jpg')){
	            				JFile::delete($path.$images[$ii].'.ths.jpg');
	        				}
							
						//DJClassifiedsImage::makeThumb($path.$images[$ii], $nw, $nh, 'th');
				 		DJClassifiedsImage::makeThumb($path.$images[$ii], $nws, $nhs, 'ths');
						DJClassifiedsImage::makeThumb($path.$images[$ii], $nwm, $nhm, 'thm');
						DJClassifiedsImage::makeThumb($path.$images[$ii], $nwb, $nhb, 'thb');				
					}
				}
			}				        
	    }
	    $redirect = 'index.php?option=com_djclassifieds&view=items';
	    $app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}
	
	function generateCoordinates(){	
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$db  = JFactory::getDBO();
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_GENERATING_COORDINATES'), 'generic.png');
	    
		
		$id_checked = JRequest::getVar('idc','');
		$id_checked_s='';
		if($id_checked){
			$id_checked_s = 'AND id NOT IN ('.$id_checked.')';	
		}
			
	    $query = "SELECT * FROM #__djcf_items WHERE (region_id>0 OR address!='') "
	    		."AND latitude=0.000000000000000 AND longitude=0.000000000000000 "
	    		.$id_checked_s." LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		//echo '<pre>';print_r($db);die();				
		
		if($item){
			if($id_checked){
				$id_checked .=','.$item->id;
			}else{
				$id_checked .= $item->id;
			}
			$address= '';
			
			if($item->region_id){
				$reg_path = DJClassifiedsRegion::getParentPath($item->region_id);			
				for($r=count($reg_path)-1;$r>=0;$r--){
					if($reg_path[$r]->country){
						$address = $reg_path[$r]->name; 
					}
					if($reg_path[$r]->city){
						if($address){	$address .= ', ';}					
						$address .= $reg_path[$r]->name;										 
					}				
				}
			}
			if($address){	$address .= ', ';}
			$address .= $item->address;
			if($item->post_code){
				$address .= ', '.$item->post_code;	
			}
			
			$loc_coord = DJClassifiedsGeocode::getLocation($address);
			if(is_array($loc_coord)){
				$query = "UPDATE #__djcf_items SET latitude=".$loc_coord['lat'].",longitude=".$loc_coord['lng']."  WHERE id=".$item->id;
				$db->setQuery($query);
				$db->query();
				//echo '<pre>';print_r($db);die();
			}
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_GENERATING_COORDINATES').' [id = '.$item->id.']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';			
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=items.generateCoordinates&idc='.$id_checked);			
		}else{
			$redirect = 'index.php?option=com_djclassifieds&view=items';
	    	$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_COORDINATES_REGENERATED'));
			
		}				
	}
	
	function delete()
	{
		JPluginHelper::importPlugin('djclassifieds');
		$app  = JFactory::getApplication();
	    $cid  = JRequest::getVar('cid', array (), '', 'array');
	    $db   = JFactory::getDBO();
	    $user = JFactory::getUser();
	    $dispatcher = JDispatcher::getInstance();
	    
	    
	    if (!$user->authorise('core.delete', 'com_djclassifieds')) {
	    	$this->setError(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
	    	$this->setMessage($this->getError(), 'error');
	    	$this->setRedirect( 'index.php?option=com_djclassifieds&view=items' );
	    	return false;
	    }
	    
	    if (count($cid))
	    {
	        $cids = implode(',', $cid);
	        $query = "SELECT * FROM #__djcf_items WHERE id IN ( ".$cids." )";
			$db->setQuery($query);
			$items = $db->loadObjectList();
			
			$query = "SELECT * FROM #__djcf_images WHERE item_id IN ( ".$cids." ) AND type='item' ";
			$db->setQuery($query);
			$items_images =$db->loadObjectList('id');
			
			
			if($items_images){
				foreach($items_images as $item_img){
					$path_to_delete = JPATH_ROOT.$item_img->path.$item_img->name;
					if (JFile::exists($path_to_delete.'.'.$item_img->ext)){
						JFile::delete($path_to_delete.'.'.$item_img->ext);
					}
					if (JFile::exists($path_to_delete.'_ths.'.$item_img->ext)){
						JFile::delete($path_to_delete.'_ths.'.$item_img->ext);
					}
					if (JFile::exists($path_to_delete.'_thm.'.$item_img->ext)){
						JFile::delete($path_to_delete.'_thm.'.$item_img->ext);
					}
					if (JFile::exists($path_to_delete.'_thb.'.$item_img->ext)){
						JFile::delete($path_to_delete.'_thb.'.$item_img->ext);
					}
				}
			}
			
	        $cids = implode(',', $cid);
	        $query = "DELETE FROM #__djcf_items WHERE id IN ( ".$cids." )";
	        $db->setQuery($query);
	        if (!$db->query())
	        {
				echo $row->getError();
        		exit ();	
	        }
			
			$query = "DELETE FROM #__djcf_fields_values WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
			
			$query = "DELETE FROM #__djcf_payments WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
	        
	        $query = "DELETE FROM #__djcf_images WHERE item_id IN ( ".$cids." ) AND type='item' ";
	        $db->setQuery($query);
	        $db->query();
	        
	        foreach($items as $item){
	        	$dispatcher->trigger('onAfterDJClassifiedsDeleteAdvert', array($item));
	        }
	        
	    }
	    $app->redirect('index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_ITEMS_DELETED'));
	}
	
	
	public function publish(){
		$app  = JFactory::getApplication();		
		$data = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2, 'report' => -3);		
		$task = $this->getTask();
		$new_status = JArrayHelper::getValue($data, $task, 0, 'int');
		$cid  = JRequest::getVar('cid', array (), '', 'array');						
		
			foreach($cid as $id){
				DJClassifiedsNotify::notifyUserPublication($id,$new_status);
			}
				
		$publish = parent::publish(); 		
	
		return $publish;
	}

}