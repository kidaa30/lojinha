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
//require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'modeladmin.php');

class DjclassifiedsModelItemsunit extends JModelAdmin
{
	protected $text_prefix = 'COM_DJCLASSIFIEDS';

	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	protected function prepareTable($table)
	{
		if (method_exists($this, '_prepareTable')) {
			return $this->_prepareTable($table);
		}
	}	

	public function getTable($type = 'Itemsunits', $prefix = 'DjclassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djclassifieds.itemsunit', 'itemsunit', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djclassifieds.edit.itemsunit.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
	protected function preprocessForm(JForm $form, $data, $group = 'djclassifiedsitemsunit')
	{
		return parent::preprocessForm($form, $data, $group);
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$db = JFactory::getDbo();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		
		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db->setQuery('SELECT MAX(ordering) FROM #__djcf_items_units');
				$max = $db->loadResult();
		
				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}

	public function delete(&$cid) {
		if (parent::delete($cid)) {
			if (count( $cid )) {
				/*$cids = implode(',', $cid);
				$this->_db->setQuery("DELETE FROM #__djc2_deliveries_payments WHERE delivery_id IN ( ".$cids." )");
				if (!$this->_db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}*/
			}
			return true;
		}
		return false;
	}
}