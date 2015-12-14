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

jimport('joomla.application.component.modeladmin');

class DJClassifiedsModelRegion extends JModelAdmin
{

	public function getTable($type = 'Regions', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		
		// Initialise variables.
		/*$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djcatalog2.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}*/

		return $form;
	}
    function getRegion() {
        global $option;
        $row = JTable::getInstance('Regions', 'DJClassifiedsTable');
		//print_r($row);die();
        $id = JRequest::getVar('id', '', '0', 'int');
		if($id==0){
			$cid = JRequest::getVar('cid', array(0), '', 'array' );
  			$id = $cid[0];       	
		} 		
        $row->load($id);
        return $row;
    }

   
		function getMainRegions(){
			$id = JRequest::getVar('id', '', '0', 'int');
			$db= JFactory::getDBO();
			$query = "SELECT r.id as value, r.name as text FROM #__djcf_regions r "
					." WHERE r.parent_id=0 ORDER BY r.name ";
	
			$db->setQuery($query);
			$allelems=$db->loadObjectList();
	
			return $allelems;
	}
		
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'parent_id = '.(int) $table->parent_id;
		return $condition;
	}


}