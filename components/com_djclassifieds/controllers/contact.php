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


class DJClassifiedsControllerContact extends JControllerLegacy {
	
	function bidderMessage(){
		$app 		= JFactory::getApplication();		
		$user 		= JFactory::getUser();				
		$db 		= JFactory::getDBO();
		$bid_id	 	= JRequest::getInt('bid', 0);
		$id	 		= JRequest::getInt('id', 0);
		$db			= JFactory::getDBO();
		$m_title	= JRequest::getVar('c_title', 0);
		$m_message  = JRequest::getVar('c_message', 0);
		
		$e_mesage = '';
		$e_type =  '';
		$ms = 0;
		
		if($user->id>0){
			
			$query = "SELECT i.*, c.name as c_name,c.alias as c_alias, u.name as u_name, u.email as u_email FROM #__djcf_items i, #__djcf_categories c, #__users u  "
					."WHERE c.id=i.cat_id AND u.id=i.user_id AND i.id= ".$id." LIMIT 1 ";
			
			$db->setQuery($query);
			$item=$db->loadObject();
			//echo '<pre>';print_r($db);print_r($item);die();
			if($item->user_id==$user->id){
				
				$query = "SELECT a.*, u.email FROM #__djcf_auctions a, #__users u "
						."WHERE a.user_id=u.id AND a.id= ".$bid_id." AND a.item_id= ".$id." LIMIT 1 ";
				
				$db->setQuery($query);
				$bid=$db->loadObject();
				
				if($bid){
					$bidder = JFactory::getUser($bid->user_id);
					DJClassifiedsNotify::messageAuthorToBidder($id,$bidder,$item,$bid->price,$user,$m_title,$m_message);
					$ms = 1;					
				}else{
					$e_mesage = JText::_('COM_DJCLASSIFIEDS_WRONG_BID');
					$e_type = 'error';
				}
			}else{
				$e_mesage = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$e_type = 'error';
			}
		}else{
			$e_mesage = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
			$e_type = 'error';
		}


		$redirect="index.php?option=com_djclassifieds&view=contact&id=".$id."&bid=".$bid->id."&ms=".$ms."&tmpl=component";	
		$redirect = JRoute::_($redirect,false);		
		$app->redirect($redirect, $e_mesage);

	}
}

?>