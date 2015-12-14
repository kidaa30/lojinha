<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

jimport('joomla.application.component.controllerform');


class DJClassifiedsControllerProfileedit extends JControllerLegacy {
	
	function save(){
		$app 	= JFactory::getApplication();
		$user 	= JFactory::getUser();				
		$db 	= JFactory::getDBO();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		
		if($user->id=='0'){
			$uri=DJClassifiedsSEO::getCategoryRoute('0:all');
			$app->redirect('index.php?option=com_users&view=login&return='.base64_encode($uri),JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN'));
		}				
				
		$del_avatar_id = JRequest::getInt('del_avatar',0);		
		if($del_avatar_id){
			$query = "SELECT * FROM #__djcf_images WHERE item_id=".$user->id." AND type='profile' ORDER BY ordering LIMIT 1";
			$db->setQuery($query);
			$avatar=$db->loadObject();
			if($avatar){
				$path_to_delete = JPATH_SITE.$avatar->path.$avatar->name;
				$del_avatar_ext = $avatar->ext;
				if (JFile::exists($path_to_delete.'.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_th.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_th.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_ths.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_ths.'.$del_avatar_ext);
				}
				$query = "DELETE FROM #__djcf_images WHERE type='profile' AND item_id=".$user->id." AND id=".$avatar->id." ";
				$db->setQuery($query);
				$db->query();
			}
		}
			
		$new_avatar = $_FILES['new_avatar'];
		if (substr($new_avatar['type'], 0, 5) == "image")
		{
			$query = "SELECT * FROM #__djcf_images WHERE item_id=".$user->id." AND type='profile' ORDER BY ordering LIMIT 1";
			$db->setQuery($query);
			$avatar=$db->loadObject();
			if($avatar){
				$path_to_delete = JPATH_SITE.$avatar->path.$avatar->name;
				$del_avatar_ext = $avatar->ext;
				if (JFile::exists($path_to_delete.'.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_th.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_th.'.$del_avatar_ext);
				}
				if (JFile::exists($path_to_delete.'_ths.'.$del_avatar_ext)){
					JFile::delete($path_to_delete.'_ths.'.$del_avatar_ext);
				}
				$query = "DELETE FROM #__djcf_images WHERE type='profile' AND item_id=".$user->id." AND id=".$avatar->id." ";
				$db->setQuery($query);
				$db->query();
			}
				
			$lang = JFactory::getLanguage();
			$icon_name = str_ireplace(' ', '_',$new_avatar['name'] );
			$icon_name = $lang->transliterate($icon_name);
			$icon_name = strtolower($icon_name);
			$icon_name = JFile::makeSafe($icon_name);
				
			$icon_name = $user->id.'_'.$icon_name;
			$icon_url = $icon_name;
			$path = JPATH_SITE."/components/com_djclassifieds/images/profile/".$icon_name;
			move_uploaded_file($new_avatar['tmp_name'], $path);
				
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
			$query .= "('".$user->id."','profile','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/profile/','','1'); ";
			$db->setQuery($query);
			$db->query();
		}
				
			 		
		$query = "DELETE FROM #__djcf_fields_values_profile WHERE user_id= ".$user->id." ";
		$db->setQuery($query);
		$db->query();
		
		$query = "SELECT f.* FROM #__djcf_fields f WHERE f.source=2 ";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		 //echo '<pre>'; print_r($db);print_r($fields_list);die();
		
		$a_tags_cf = '';
		if((int)$par->get('allow_htmltags_cf','0')){						
			$allowed_tags_cf = explode(';', $par->get('allowed_htmltags_cf',''));
			for($a = 0;$a<count($allowed_tags_cf);$a++){				
				$a_tags_cf .= '<'.$allowed_tags_cf[$a].'>';	
			}			
		}
		
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

							$query .= "('".$fl->id."','".$user->id."','".$db->escape($f_value)."',''), ";
							$ins++;	
						}
					}else if($fl->type=='date'){
						if(isset($_POST[$fl->name])){							
							$f_var = JRequest::getVar( $fl->name,'','','string' );							
							$query .= "('".$fl->id."','".$user->id."','','".$db->escape($f_var)."'), ";
							$ins++;	
						}
					}else{					
						if(isset($_POST[$fl->name])){
							if($a_tags_cf){
								$f_var = JRequest::getVar( $fl->name,'','','string',JREQUEST_ALLOWRAW );	
								$f_var = strip_tags($f_var, $a_tags_cf);								
							}else{
								$f_var = JRequest::getVar( $fl->name,'','','string' );
							}																			
							$query .= "('".$fl->id."','".$user->id."','".$db->escape($f_var)."',''), ";
							$ins++;	
						}
					}
				}
			}
		  //print_r($query);die();
			if($ins>0){
				$query = substr($query, 0, -2).';';
				$db->setQuery($query);
    			$db->query();	
			}
						 
			$menus	= JSite::getMenu();
			$menu_profile = $menus->getItems('link','index.php?option=com_djclassifieds&view=profile',1);
			$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
			$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
			$redirect= 'index.php?option=com_djclassifieds&view=profile';
			if($menu_profile){
				$redirect .= '&Itemid='.$menu_profile->id;
			}else if($menu_item){
				$redirect .= '&Itemid='.$menu_item->id;
			}else if($menu_item_blog){
				$redirect .= '&Itemid='.$menu_item_blog->id;
			}			
			$message = JTExt::_('COM_DJCLASSIFIEDS_PROFILE_SAVED_SUCCESSFULLY');							
		
		$redirect = JRoute::_($redirect,false);		
		$app->redirect($redirect, $message);

	}
}

?>