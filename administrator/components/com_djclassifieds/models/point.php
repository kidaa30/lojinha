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

class DJClassifiedsModelPoint extends JModelAdmin
{
	protected $text_prefix = 'COM_DJCLASSIFIEDS';

	public function __construct($config = array()) {
		$config['event_after_save'] = 'onProducerAfterSave';
		$config['event_after_delete'] = 'onProducerAfterDelete';
		parent::__construct($config);
	}

	public function getTable($point = 'Points', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($point, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djclassifieds.point', 'point', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djclassifieds.edit.point.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		if($data->id){
			$db = JFactory::getDBO();
			$db->setQuery('SELECT group_id FROM #__djcf_points_groups WHERE points_id='.$data->id);			
			$data->points_groups=$db->loadResultArray();	
		}
		
		//echo '<pre>';print_r($data);die();

		return $data;
	}

	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		
		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__djcf_points');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		if($table->id>0){
			$db =  JFactory::getDBO();
			$query = "DELETE FROM #__djcf_points_groups WHERE points_id= ".$table->id." ";
		    $db->setQuery($query);
		    $db->query();
			
			if(count($_POST['jform']['points_groups'])){
				$query = "INSERT INTO #__djcf_points_groups(`points_id`,`group_id`) VALUES ";
				for($i=0;$i<count($_POST['jform']['points_groups']);$i++){
					$group_id = $_POST['jform']['points_groups'][$i];
					$query .= "('".$table->id."','".$group_id."'), ";	
				}
				$query = substr($query, 0,-2).';';
				$db->setQuery($query);
				$db->query();
				//print_r($db);die();
			}
		}
		//echo '<pre>';print_r($_POST);die('aaa');
	}



	protected function getReorderConditions($table = null)
	{
		$condition = array();
		return $condition;
	}

}