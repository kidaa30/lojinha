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

class DJClassifiedsControllerCategories extends JControllerAdmin
{
	public function getModel($name = 'Category', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	
	function delete()
	{
	    $app  = JFactory::getApplication();
	    $cid  = JRequest::getVar('cid', array (), '', 'array');
	    $db   = JFactory::getDBO();
	    $user = JFactory::getUser();
	    
	    if (!$user->authorise('core.delete', 'com_djclassifieds')) {
	    	$this->setError(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));
	    	$this->setMessage($this->getError(), 'error');
	    	$this->setRedirect( 'index.php?option=com_djclassifieds&view=categories' );
	    	return false;
	    }
	    if (count($cid))
	    {	    	
	        $cids = implode(',', $cid);
			
			$query = "SELECT count(id) FROM #__djcf_items WHERE cat_id IN ( ".$cids." )";
			$db->setQuery($query);
			$items_count = $db->loadResult();
			if($items_count>0){
				$app->redirect('index.php?option=com_djclassifieds&view=categories', JText::_('COM_DJCLASSIFIEDS_DELETE_ADS_BEFORE_DELETE_BEFORE_CATEGORY'),'error');
			}		

			$query = "SELECT count(id) FROM #__djcf_categories WHERE id NOT IN ( ".$cids." ) AND parent_id IN ( ".$cids." )";
			$db->setQuery($query);
			$cats_count = $db->loadResult();
			if($cats_count>0){
				$app->redirect('index.php?option=com_djclassifieds&view=categories', JText::_('COM_DJCLASSIFIEDS_DELETE_CHILD_CATEGORIES_BEFORE_DELETE_PARENT_CATEGORY'),'error');
			}
			
			$query = "SELECT * FROM #__djcf_images WHERE item_id IN ( ".$cids." ) AND type='category' ";
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
				}
			}
			
	        $query = "DELETE FROM #__djcf_categories WHERE ID IN ( ".$cids." )";
	        $db->setQuery($query);
	        if (!$db->query())
	        {
	            echo "script alert('".$db->getErrorMsg()."');
					window.history.go(-1); </script>\n";
	        }
			
			$query = "DELETE FROM #__djcf_fields_xref WHERE cat_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        if (!$db->query())
	        {
	            echo "script alert('".$db->getErrorMsg()."');
					window.history.go(-1); </script>\n";
	        }
	        
	        $query = "DELETE FROM #__djcf_images WHERE item_id IN ( ".$cids." ) AND type='category' ";
	        $db->setQuery($query);
	        $db->query();
	    }
	    $app->redirect('index.php?option=com_djclassifieds&view=categories', JText::_('COM_DJCLASSIFIEDS_CATEGORIES_DELETED'));
	}

	function recreateThumbnails(){	
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
	    $cid = JRequest::getVar('cid', array (), '', 'array');		
	    $db = JFactory::getDBO();
	    
	    if (count($cid))
	    {
	        $cids = implode(',', $cid);
	        /*$query = "SELECT id, icon_url FROM #__djcf_categories WHERE id IN ( ".$cids." )";
			$db->setQuery($query);
			$items = $db->loadObjectList();
			
			$path = JPATH_BASE."/../components/com_djclassifieds/images/";
				$nw = $par->get('catth_width',-1);
		        $nh = $par->get('catth_height',-1);
							
		
			foreach($items as $i){
				if($i->icon_url){									
        			if (JFile::exists($path.$i->icon_url.'.ths.jpg')){
            			JFile::delete($path.$i->icon_url.'.ths.jpg');
        			}						
			 		DJClassifiedsImage::makeThumb($path.$i->icon_url, $nw, $nh, 'ths');										
				}
			} */
	        $query = "SELECT * FROM #__djcf_images WHERE item_id IN  ( ".$cids." ) AND type='category' ";
	        $db->setQuery($query);
	        $images = $db->loadObjectList();
	        if($images){
	        	$nw = $par->get('catth_width',-1);
	        	$nh = $par->get('catth_height',-1);
	        	foreach($images as $image){
	        		$path = JPATH_SITE.$image->path.$image->name;
	        		 
	        		if (JFile::exists($path.'_ths.'.$image->ext)){
	        			JFile::delete($path.'_ths.'.$image->ext);
	        		}
	        
	        		DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_ths.'.$image->ext, $nw, $nh,false, true,0);
	        	}
	        }				        
	    }
	    $redirect = 'index.php?option=com_djclassifieds&view=categories';
	    $app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}

	function regenerateAliases(){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );	    		
	    $db = JFactory::getDBO();
			    	    
	        $query = "SELECT * FROM #__djcf_categories";
			$db->setQuery($query);
			$cats = $db->loadObjectList();							
		
			foreach($cats as $c){									
        		$alias = DJClassifiedsSEO::getAliasName($c->name);
				$query = "UPDATE #__djcf_categories SET alias='".$alias."' WHERE id=".$c->id;
				$db->setQuery($query);
				$db->query();
			}				        
	    
	    $redirect = 'index.php?option=com_djclassifieds&view=categories';
	    $app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_ALIASES_RECREATED'));
	}

}