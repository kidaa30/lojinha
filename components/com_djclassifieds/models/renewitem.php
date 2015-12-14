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
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class DjclassifiedsModelRenewItem extends JModelLegacy{	

	function getItem()
	{
		$app	= JFactory::getApplication();
		$id 	= JRequest::getVar('id', 0, '', 'int' );	       
        $row 	= JTable::getInstance('Items', 'DJClassifiedsTable');
		if($id>0){						
			$user=JFactory::getUser();			
			$row->load($id);
			
			if($user->id!=$row->user_id || $user->id==0){
				$message = JText::_("COM_DJCLASSIFIEDS_WRONG_AD");
				$redirect= 'index.php?option=com_djclassifieds&view=additem' ;
				$app->redirect($redirect,$message,'error');		
			}
		}
	  	
        return $row;
	}
	

	
	function getCategory($id){
			$db	= JFactory::getDBO();
			$query = "SELECT c.* FROM #__djcf_categories c "
					."WHERE c.id= ".$id." LIMIT 1 ";
	
			$db->setQuery($query);
			$category=$db->loadObject();
	
			return $category;
	}
	

	function getDays(){
			$db= JFactory::getDBO();
			$query = "SELECT d.* FROM #__djcf_days d "
					."WHERE d.published=1 "
					."ORDER BY d.days ";
	
			$db->setQuery($query);
			$days=$db->loadObjectList();
	
			return $days;
	}	

	function getPromotions(){
			$db= JFactory::getDBO();
			$query = "SELECT p.* FROM #__djcf_promotions p "
					."WHERE p.published=1 "
					."ORDER BY p.ordering,p.id ";
	
			$db->setQuery($query);
			$promotions=$db->loadObjectList();
	
			return $promotions;
	}	
	
	
	
	
}

