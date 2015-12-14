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

class DjClassifiedsModelPayments extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'i_name', 'i.name',
				'pp_name', 'pp.name',
				'id', 'p.id',				
				'method', 'p.method',
				'date', 'p.date',				
				'status', 'p.status',
				'type', 'p.type'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		// List state information.
		parent::populateState('p.id', 'desc');
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$status = $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', '');
		$this->setState('filter.status', $status);
		
		$type = $this->getUserStateFromRequest($this->context.'.filter.type', 'filter_type', '');
		$this->setState('filter.type', $type);
				
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.status');
		$id	.= ':'.$this->getState('filter.type');
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';
		
		$status = $this->getState('filter.status');
		if ($status) {
			$where .= " AND p.status = '".$status."' ";
		}
		
		$type = $this->getState('filter.type');
		if (is_numeric($type)) {
			$where .= ' AND p.type = ' . (int) $type;
		}

		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= JFactory::getDBO();
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$where .= " AND (i.name LIKE ".$search." OR pp.name LIKE ".$search." OR u.name LIKE ".$search." OR u.email LIKE ".$search." )";
		}
		
		return $where;
	}
	
	function getPayments(){
		if(empty($this->_items)) {
			//$limit = JRequest::getVar('limit', '25', '', 'int');
			//$limitstart = JRequest::getVar('limitstart', '0', '', 'int');
			$limit = $this->getState('list.limit');
			$limitstart = $this->getState('list.start');
			
			$orderCol	= $this->state->get('list.ordering');
			$orderDirn	= $this->state->get('list.direction');								
			
			$db= JFactory::getDBO();	
			$query = "SELECT p.*, i.name as i_name,pp.name as pp_name, plan.name as plan_name, u.name as u_name FROM #__djcf_payments p "
					."LEFT JOIN #__djcf_items i ON i.id=p.item_id "	
					."LEFT JOIN #__djcf_points pp ON pp.id=p.item_id "
					."LEFT JOIN #__djcf_plans plan ON plan.id=p.item_id "		
					."LEFT JOIN #__users u ON u.id=p.user_id "		
					."  WHERE 1  ".$this->_buildWhere()." ORDER BY ".$orderCol." ".$orderDirn." ";
			$this->_items = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_items;
	}
	
	/*protected function getRepaymentConditions($table)
	{
		$condition = array();
		$condition[] = 'item_id = '.(int) $table->cat_id;
		return $condition;
	}*/

	
	public function getPaymentsItems(){
		if(empty($this->_countItems)) {
			$db= JFactory::getDBO();
			$query = "SELECT count(p.id) FROM #__djcf_payments p WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countItems=$db->loadResult();
		}
		return $this->_countItems;
	}
	public function _getListQuery(){
		$query = "SELECT p.*, i.name as i_name,pp.name as pp_name, plan.name as plan_name, u.name as u_name FROM #__djcf_payments p "
			."LEFT JOIN #__djcf_items i ON i.id=p.item_id "	
			."LEFT JOIN #__djcf_points pp ON pp.id=p.item_id "
			."LEFT JOIN #__djcf_plans plan ON plan.id=p.item_id "		
			."LEFT JOIN #__users u ON u.id=p.user_id "		
			."  WHERE 1  ".$this->_buildWhere();
		return $query;
	}


}
?>