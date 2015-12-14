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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport( 'joomla.database.table' );


class DJClassifiedsControllerCategory extends JControllerLegacy {
	
	public function getModel($name = 'Category', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Category', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('edit', 'add');
    }
	
	public function add(){
		//$data = JFactory::getApplication();
		$user = JFactory::getUser();
		if(JRequest::getVar('id',0)){
			if (!$user->authorise('core.edit', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=categories' );
				return false;
			}
		}else{
			if (!$user->authorise('core.create', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=categories' );
				return false;
			}
		}
		JRequest::setVar('view','category');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=categories' );
	}
	
	public function save(){
    	$app 	= JFactory::getApplication();		
		$model 	= $this->getModel('category');
		$row 	= JTable::getInstance('Categories', 'DJClassifiedsTable');		
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
						
    	$row->bind(JRequest::get('post'));
    	$icon_url = $row->icon_url;
		$row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		if($row->alias){
			$row->alias = DJClassifiedsSEO::getAliasName($row->alias);
		}else{
			$row->alias = DJClassifiedsSEO::getAliasName($row->name);
		}

		$del_icon_id = JRequest::getInt('del_icon_id',0);
		$del_icon_path = JRequest::getVar('del_icon_path','');
		$del_icon_name = JRequest::getVar('del_icon_name','');
		$del_icon_ext = JRequest::getVar('del_icon_ext','');		
		
			if(JRequest::getVar('del_icon', '0','','int')){
				if($del_icon_path && $del_icon_name && $del_icon_ext){
				    $path_to_delete = JPATH_SITE.$del_icon_path.$del_icon_name;
			        if (JFile::exists($path_to_delete.'.'.$del_icon_ext)){
			           	JFile::delete($path_to_delete.'.'.$del_icon_ext);
				    }		
			       	if (JFile::exists($path_to_delete.'_ths.'.$del_icon_ext)){
			           	JFile::delete($path_to_delete.'_ths.'.$del_icon_ext);
				    }
				    $query = "DELETE FROM #__djcf_images WHERE type='category' AND item_id=".$row->id." AND id=".$del_icon_id." ";				    
				    $db->setQuery($query);
				    $db->query();
				}
			}			
		
			$row->price=$row->price*100;
		//echo '<pre>';print_r($row);die();
			if(!$row->ordering){
				$query = "SELECT ordering FROM #__djcf_categories WHERE parent_id = ".$row->parent_id." ORDER BY ordering DESC LIMIT 1";
				$db = JFactory::getDBO();		
				$db->setQuery($query);
				$order =$db->loadObject();
				$row->ordering = $order->ordering + 1;
			}
		
		if (!$row->store()){
			echo $row->getError();
        	exit ();	
    	}
    	

	    	$new_icon = $_FILES['icon'];
	    	if (substr($new_icon['type'], 0, 5) == "image")
	    	{
	    		$path_to_delete = JPATH_SITE.$del_icon_path.$del_icon_name;
	    		if (JFile::exists($path_to_delete.'.'.$del_icon_ext)){
	    			JFile::delete($path_to_delete.'.'.$del_icon_ext);
	    		}
	    		if (JFile::exists($path_to_delete.'_ths.'.$del_icon_ext)){
	    			JFile::delete($path_to_delete.'_ths.'.$del_icon_ext);
	    		}
	    		$query = "DELETE FROM #__djcf_images WHERE type='category' AND item_id=".$row->id." AND id=".$del_icon_id." ";
	    		$db->setQuery($query);
	    		$db->query();
	    				    		
	    		$last_id= $row->id;	    		
	    			
	    		$lang = JFactory::getLanguage();
	    		$icon_name = str_ireplace(' ', '_',$new_icon['name'] );
	    		$icon_name = $lang->transliterate($icon_name);
	    		$icon_name = strtolower($icon_name);
	    		$icon_name = JFile::makeSafe($icon_name);
	    			
	    		$icon_name = $last_id.'_'.$icon_name;
	    		$icon_url = $icon_name;
	    		$path = JPATH_SITE."/components/com_djclassifieds/images/category/".$icon_name;
	    		move_uploaded_file($new_icon['tmp_name'], $path);
	    	
	    		$nw = $par->get('catth_width',-1);
	    		$nh = $par->get('catth_height',-1);
	    	
	    		$name_parts = pathinfo($path);
	    		$img_name = $name_parts['filename'];
	    		$img_ext = $name_parts['extension'];
	    		$new_path = JPATH_SITE."/components/com_djclassifieds/images/category/";
	    		 
	    		//DJClassifiedsImage::makeThumb($path, $nw, $nh, 'ths');
	    		DJClassifiedsImage::makeThumb($path,$new_path.$img_name.'_ths.'.$img_ext, $nw, $nh, false, true, 0);
	    			
	    		$query = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
	    		$query .= "('".$row->id."','category','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/category/','','1'); ";
	    		$db->setQuery($query);
	    		$db->query();
	    	}    	
		 
			$db = JFactory::getDBO();
			$query = "DELETE FROM #__djcf_categories_groups WHERE cat_id= ".$row->id." ";
		    $db->setQuery($query);
		    $db->query();
			
				if(isset($_POST['cat_groups'])){
					$query = "INSERT INTO #__djcf_categories_groups(`cat_id`,`group_id`) VALUES ";
					for($i=0;$i<count($_POST['cat_groups']);$i++){
						$group_id = $_POST['cat_groups'][$i];
						$query .= "('".$row->id."','".$group_id."'), ";	
					}
					$query = substr($query, 0,-2).';';
					$db->setQuery($query);
					$db->query();
				}
				
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=category.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=category.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=categories';
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>