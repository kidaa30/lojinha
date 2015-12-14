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

class DJClassifiedsControllerFieldsXref extends JControllerAdmin
{
	public function getModel($name = 'FieldsXref', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
		function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
    }
	
	
	function display($cachable = false){
        JRequest::setVar('view', JRequest::getCmd('view', 'fieldsxref'));
        parent::display($cachable);
        }
	
	public function save(){
    	$app = JFactory::getApplication();
		$cat_id = JRequest::getVar('id','0');
	
		$db = & JFactory::getDBO();
		$query = "DELETE FROM #__djcf_fields_xref WHERE cat_id= ".$cat_id." ";
	    $db->setQuery($query);
	    $db->query();
			if(count($_POST['fields_active'])>0){
				$query = "INSERT INTO #__djcf_fields_xref(`cat_id`,`field_id`,`ordering`) VALUES ";
				for($i=0;$i<count($_POST['fields_active']);$i++){
					$field_id = $_POST['fields_active'][$i];
					$ord = $_POST['fieldsordering_'.$field_id];
					$query .= "('".$cat_id."','".$field_id."','".$ord."'), ";	
				}
				$query = substr($query, 0,-2).';';
				$db->setQuery($query);
				$db->query();
			}			
		$app->redirect('index.php?option=com_djclassifieds&view=fieldsxref&id='.$cat_id.'&tmpl=component', JText::_('COM_DJCLASSIFIEDS_CATEGORY_FIELDS_SAVED'));	
	}
	
	/*
	 * 
	 * 	$cat_id = JRequest::getVar('cat_id','0');
	
	$db = & JFactory::getDBO();
	$query = "DELETE FROM #__djcf_fields_xref WHERE cat_id= ".$cat_id." ";
    $db->setQuery($query);
    $db->query();
	
		$query = "INSERT INTO #__djcf_fields_xref(`cat_id`,`field_id`,`ordering`) VALUES ";
		for($i=0;$i<count($_POST['fields_active']);$i++){
			$field_id = $_POST['fields_active'][$i];
			$ord = $_POST['fieldsordering_'.$field_id];
			$query .= "('".$cat_id."','".$field_id."','".$ord."'), ";	
		}
		$query = substr($query, 0,-2).';';
		$db->setQuery($query);
		$db->query();
		$mainframe->redirect('index.php?option=com_djclassifieds&task=fieldsxref&id='.$cat_id.'&tmpl=component', JText::_('Saved'));
			
	 * */
	 
	
}