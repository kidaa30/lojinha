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


class DJClassifiedsControllerRegistration extends JControllerLegacy {	
	
	
	public function checkUsername(){
		
		header("Content-type: text/html; charset=utf-8");
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 		= JFactory::getDBO();
		$username   = $db->Quote($db->escape(JRequest::getVar('username','','','string'), true));
		$language 	= JFactory::getLanguage();
		$language->load('com_users', JPATH_SITE, null, true);
		
		$query ="SELECT count(u.id) FROM #__users u WHERE u.username=".$username." ";
		$db->setQuery($query);
		$u_exist =$db->loadResult();
		if($u_exist){
			echo JText::_('COM_USERS_REGISTER_USERNAME_MESSAGE');
		}
		die();
	}
	
	public function checkEmail(){
		header("Content-type: text/html; charset=utf-8");
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 		= JFactory::getDBO();
		$email 		= $db->Quote($db->escape(JRequest::getVar('email','','','string'), true));
		$language 	= JFactory::getLanguage();
		$language->load('com_users', JPATH_SITE, null, true);
	
		$query ="SELECT count(u.id) FROM #__users u WHERE u.email=".$email." ";
		$db->setQuery($query);
		$u_exist =$db->loadResult();
		if($u_exist){
			echo JText::_('COM_USERS_PROFILE_EMAIL1_MESSAGE');
		}
		die();
	}
	
	
	function save(){
		$app		= JFactory::getApplication();
		$Itemid		= JRequest::getInt('Itemid');
		$db 		= JFactory::getDBO();
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$dispatcher = JDispatcher::getInstance();
		$language 	= JFactory::getLanguage();
		$language->load('com_users', JPATH_SITE, null, true);
		
		// Check for request forgeries.
		//JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		// If registration is disabled - Redirect to login page.
		if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
		{
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));		
			return false;
		}
		
		JModelLegacy::addIncludePath(JPATH_BASE . '/components/com_users/models/','RegistrationModel');
		JForm::addFormPath(JPATH_BASE . '/components/com_users/models/forms');
		JForm::addFieldPath(JPATH_BASE . '/components/com_users/models/fields');
		$users_model = $this->getModel($name = 'Registration', $prefix = 'UsersModel'); 
		
		// Get the user data.
		$requestData = $this->input->post->get('jform', array(), 'array');
		
		// Validate the posted data.
		$form	= $users_model->getForm();
		
		//echo '<pre>';print_r($form);die();
		if (!$form)
		{
			JError::raiseError(500, $users_model->getError());
		
			return false;
		}
		
		$data	= $users_model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $users_model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $requestData);

			// Redirect back to the registration screen.
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=registration', false));

			return false;
		}

		// Attempt to save the data.
		$return	= $users_model->register($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage($users_model->getError(), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_djclassifieds&view=registration&Itemid='.$Itemid, false));

			return false;
		}

		$username = $db->Quote($db->escape($data['username']), true);
		$query ="SELECT id FROM #__users u WHERE u.username=".$username." ";
		$db->setQuery($query);
		$user_id =$db->loadResult();
		
		//echo '<pre>';print_r($user_id);die();
									
			//add data do DJ-Classifieds profile
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
			
							$query .= "('".$fl->id."','".$user_id."','".$db->escape($f_value)."',''), ";
							$ins++;
						}
					}else if($fl->type=='date'){
						if(isset($_POST[$fl->name])){
							$f_var = JRequest::getVar( $fl->name,'','','string' );
							$query .= "('".$fl->id."','".$user_id."','','".$db->escape($f_var)."'), ";
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
							$query .= "('".$fl->id."','".$user_id."','".$db->escape($f_var)."',''), ";
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
				
								
		
		// Flush the data from the session.
		$app->setUserState('com_users.registration.data', null);

		// Redirect to the profile screen.
		if ($return === 'adminactivate')
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
			//$this->setRedirect(JRoute::_('index.php?option=com_djclassifieds&view=registration&layout=complete&Itemid='.$Itemid, false));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
		elseif ($return === 'useractivate')
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
			//$this->setRedirect(JRoute::_('index.php?option=com_djclassifieds&view=registration&layout=complete&Itemid='.$Itemid, false));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
		else
		{
			$this->setMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
			$this->setRedirect(JRoute::_('index.php?option=com_users&view=login', false));
		}


		JPluginHelper::importPlugin('djclassifieds');
		$dispatcher->trigger('onAfterDJClassifiedsSaveUser', array(&$data,$user_id));
		
		return true;
	}
}
?>