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


class DJClassifiedsControllerProfile extends JControllerLegacy {
	
	public function getModel($name = 'Profile', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Profile', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
        $this->registerTask('edit', 'add');
    }
    
    public function add(){
    	//$data = JFactory::getApplication();
    	$user = JFactory::getUser();
    	if(JRequest::getVar('id',0)){
    		if (!$user->authorise('core.edit', 'com_djclassifieds')) {
    			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'));
    			$this->setMessage($this->getError(), 'error');
    			$this->setRedirect( 'index.php?option=com_djclassifieds&view=profiles' );
    			return false;
    		}
    	}else{
    		if (!$user->authorise('core.create', 'com_djclassifieds')) {
    			$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
    			$this->setMessage($this->getError(), 'error');
    			$this->setRedirect( 'index.php?option=com_djclassifieds&view=profiles' );
    			return false;
    		}
    	}
    	JRequest::setVar('view','profile');
    	parent::display();
    }

	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=profiles' );
	}
	
	public function save(){
    	$app = JFactory::getApplication();		
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$id  = JRequest::getVar('id', '0', '', 'int');
		$db  = JFactory::getDBO();
		
			$del_avatar_id = JRequest::getInt('del_avatar_id',0);
			$del_avatar_path = JRequest::getVar('del_avatar_path','');
			$del_avatar_name = JRequest::getVar('del_avatar_name','');
			$del_avatar_ext = JRequest::getVar('del_avatar_ext','');
			
			if(JRequest::getVar('del_avatar', '0','','int')){
				if($del_avatar_path && $del_avatar_name && $del_avatar_ext){
					$path_to_delete = JPATH_SITE.$del_avatar_path.$del_avatar_name;
					if (JFile::exists($path_to_delete.'.'.$del_avatar_ext)){
						JFile::delete($path_to_delete.'.'.$del_avatar_ext);
					}
					if (JFile::exists($path_to_delete.'_th.'.$del_avatar_ext)){
						JFile::delete($path_to_delete.'_th.'.$del_avatar_ext);
					}
					if (JFile::exists($path_to_delete.'_ths.'.$del_avatar_ext)){
						JFile::delete($path_to_delete.'_ths.'.$del_avatar_ext);
					}
					$query = "DELETE FROM #__djcf_images WHERE type='profile' AND item_id=".$id." AND id=".$del_avatar_id." ";
					$db->setQuery($query);
					$db->query();
				}
			}
			
			$new_icon = $_FILES['new_image'];
			if (substr($new_icon['type'], 0, 5) == "image")
			{
				$path_to_delete = JPATH_SITE.$del_avatar_path.$del_avatar_name;
				if (JFile::exists($path_to_delete.'.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_th.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_th.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_ths.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_ths.'.$del_avatar_ext);
				}
				$query = "DELETE FROM #__djcf_images WHERE type='profile' AND item_id=".$id." AND id=".$del_avatar_id." ";
				$db->setQuery($query);
				$db->query();				
			
				$lang = JFactory::getLanguage();
				$icon_name = str_ireplace(' ', '_',$new_icon['name'] );
				$icon_name = $lang->transliterate($icon_name);
				$icon_name = strtolower($icon_name);
				$icon_name = JFile::makeSafe($icon_name);
			
				$icon_name = $id.'_'.$icon_name;
				$icon_url = $icon_name;
				$path = JPATH_SITE."/components/com_djclassifieds/images/profile/".$icon_name;
				move_uploaded_file($new_icon['tmp_name'], $path);
			
				$nw = $par->get('profth_width',120);
				$nh = $par->get('profth_height',120);
				$nws = $par->get('prof_smallth_width',50);
				$nhs = $par->get('prof_smallth_height',50);
			
				$name_parts = pathinfo($path);
				$img_name = $name_parts['filename'];
				$img_ext = $name_parts['extension'];
				$new_path = JPATH_SITE."/components/com_djclassifieds/images/profile/";
			
				//DJClassifiedsImage::makeThumb($path, $nw, $nh, 'ths');
				DJClassifiedsImage::makeThumb($path,$new_path.$img_name.'_th.'.$img_ext, $nw, $nh);
				DJClassifiedsImage::makeThumb($path,$new_path.$img_name.'_ths.'.$img_ext, $nws, $nhs);
			
				$query = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
				$query .= "('".$id."','profile','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/profile/','','1'); ";
				$db->setQuery($query);
				$db->query();
			}
		
		
		
			 $query = "DELETE FROM #__djcf_fields_values_profile WHERE user_id= ".$id." ";
			 $db->setQuery($query);
			 $db->query();
			
			 $query ="SELECT f.* FROM #__djcf_fields f "
			 		."WHERE f.source=2 ";
		     $db->setQuery($query);
			 $fields_list =$db->loadObjectList();
			//echo '<pre>'; print_r($db);print_r($fields_list);die();
			 
			 $ins=0;
			 if(count($fields_list)>0){
				$query = "INSERT INTO #__djcf_fields_values_profile(`field_id`,`user_id`,`value`,`value_date`) VALUES ";			
				foreach($fields_list as $fl){
					if($fl->type=='checkbox'){
						if(isset($_POST[$fl->name])){
							$field_v = $_POST[$fl->name];
							$f_value=';';
								for($fv=0;$fv<count($field_v);$fv++){
									$f_value .=$field_v[$fv].';'; 
								}

							$query .= "('".$fl->id."','".$id."','".$db->escape($f_value)."',''), ";
							$ins++;	
						}
					}else if($fl->type=='date'){
						if(isset($_POST[$fl->name])){							
							$f_var = JRequest::getVar( $fl->name,'','','string' );							
							$query .= "('".$fl->id."','".$id."','','".$db->escape($f_var)."'), ";
							$ins++;	
						}
					}else{					
						if(isset($_POST[$fl->name])){							
							$f_var = JRequest::getVar( $fl->name,'','','string',JREQUEST_ALLOWRAW );													
							$query .= "('".$fl->id."','".$id."','".$db->escape($f_var)."',''), ";
							$ins++;	
						}
					}
				}
			}
		 
			if($ins){
				$query = substr($query, 0, -2).';';
				$db->setQuery($query);
    			$db->query();	
			}

			
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=profile.edit&id='.$id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_PROFILE_SAVED');
            	break;
        	case 'saveProfile':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=profiles';
            	$msg = JText::_('COM_DJCLASSIFIEDS_PROFILE_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>