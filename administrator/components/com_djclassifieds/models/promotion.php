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

class DJClassifiedsModelPromotion extends JModelAdmin
{

	public function getTable($type = 'Promotions', $prefix = 'DJClassifiedsTable', $config = array())
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
    function getPromotion() {
        global $option;
        $row = JTable::getInstance('Promotions', 'DJClassifiedsTable');
		//print_r($row);die();
        $id = JRequest::getVar('id', '', '0', 'int');  
		if($id==0){
			$cid = JRequest::getVar('cid', array(0), '', 'array' );
  			$id = $cid[0];       	
		} 	      
        $row->load($id);
        return $row;
    }

   
    
    function getElement() {
            $id = JRequest::getVar('id', '', '', 'int');
            $query = "SELECT * FROM #__djcf_promotions WHERE id='$id'";
            $el = $this->_getList($query, 0, 0);
        return $el[0];
    }
	

	protected function getReorderConditions($table)
	{
		$condition = array();
		//$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	

}