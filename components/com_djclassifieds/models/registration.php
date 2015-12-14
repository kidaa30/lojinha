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

class DjclassifiedsModelRegistration extends JModelLegacy{	
	
	function getTermsLink($id){
			$db= JFactory::getDBO();
			$query = "SELECT a.id, a.alias, a.catid, c.alias as c_alias FROM #__content a "
					."LEFT JOIN #__categories c ON c.id=a.catid "
					."WHERE a.state=1 AND a.id=".$id;
			
			$db->setQuery($query);
			$article=$db->loadObject();
			
			return $article;	
	}



	
	function getCustomContactFields(){
		global $mainframe;
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();		
		
		$query ="SELECT f.* FROM #__djcf_fields f "
				."WHERE f.published=1 AND f.source=2 AND f.in_registration=1 ORDER BY f.name";
		$db->setQuery($query);
		$fields_list =$db->loadObjectList();
		//echo '<pre>'; print_r($db);print_r($fields_list);die();
			
		return $fields_list;
		
	}	
	
	
}

