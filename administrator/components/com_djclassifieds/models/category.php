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

class DJClassifiedsModelCategory extends JModelAdmin
{

	public function getTable($type = 'Categories', $prefix = 'DJClassifiedsTable', $config = array())
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
    function getCategory() {
        global $option;
        $row = JTable::getInstance('Categories', 'DJClassifiedsTable');
		//print_r($row);die();
        $id = JRequest::getVar('id', '', '0', 'int');
		if($id==0){
			$cid = JRequest::getVar('cid', array(0), '', 'array' );
  			$id = $cid[0];       	
		} 		
        $row->load($id);
        return $row;
    }

   
	function getFields(){
		$id = JRequest::getVar('id', '', '0', 'int');
		$db= JFactory::getDBO();
		$query = "SELECT f.name, f.label, f.type FROM #__djcf_fields_xref fx, #__djcf_fields f "
				." WHERE fx.field_id=f.id AND fx.cat_id=".$id." ORDER BY fx.ordering";

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
	
	function getGroups(){
		$id = JRequest::getVar('id', '', '0', 'int');
		$db= JFactory::getDBO();
		$query = "SELECT ug.*, cg.active as active FROM #__usergroups ug " 
				."LEFT JOIN (SELECT *, '1' as active FROM #__djcf_categories_groups WHERE cat_id=".$id." ) cg ON cg.group_id=ug.id "
				."ORDER BY ug.title ";
		$db->setQuery($query);
		$allelems=$db->loadObjectList();
		//echo '<pre>';print_r($allelems);die();
		return $allelems;
	}
	
	function getImages(){
		$id = JRequest::getVar('id', '', '0', 'int');
		$db= JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_images WHERE item_id=".$id." AND type='category' ORDER BY ordering";
			$db->setQuery($query);
			$images=$db->loadObjectList();
	
		return $images;
	}	
	
	function getViewLevels(){
		$db= JFactory::getDBO();
		$query = "SELECT * FROM #__viewlevels "
				."ORDER BY ordering";
	
		$db->setQuery($query);
		$view_levels=$db->loadObjectList();
	
		return $view_levels;
	}

}