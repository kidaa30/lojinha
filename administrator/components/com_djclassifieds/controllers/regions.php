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

class DJClassifiedsControllerRegions extends JControllerAdmin
{
	public function getModel($name = 'Region', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
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
	    	$this->setRedirect( 'index.php?option=com_djclassifieds&view=regions' );
	    	return false;
	    }
		
	    if (count($cid))
	    {	    	
	        $cids = implode(',', $cid);
	        $query = "DELETE FROM #__djcf_regions WHERE ID IN ( ".$cids." )";
	        $db->setQuery($query);
	        if (!$db->query())
	        {
	            echo "script alert('".$db->getErrorMsg()."');
					window.history.go(-1); </script>\n";
	        }
	    }
	    $app->redirect('index.php?option=com_djclassifieds&view=regions', JText::_('COM_DJCLASSIFIEDS_REGIONS_DELETED'));
	}

}