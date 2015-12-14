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
defined ('_JEXEC') or die('Restricted access');

/*Items Model*/

//jimport('joomla.application.component.model');
jimport('joomla.application.component.modellist');

class DjClassifiedsModelAbuse extends JModelList{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	function getAbuseRaports(){
			$id = JRequest::getVar('id', '0', '', 'int');
			if($id>0){
				$db= &JFactory::getDBO();
				$query = "SELECT a.*, u.name as u_name FROM #__djcf_items_abuse a "
						."LEFT JOIN #__users u ON u.id=a.user_id "
						."WHERE a.item_id = ".$id." ";
	
				$db->setQuery($query);
				$abuse=$db->loadObjectList();
			}else{
				$abuse='';
			}

			return $abuse;
	}	
	



}
?>