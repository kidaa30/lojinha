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

class DjClassifiedsModelDays extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_days'])) {
			$config['filter_days'] = array(				
				'id', 'd.id',				
				'days', 'd.days',
				'price', 'd.price',
				'price_renew', 'd.price_renew',
				'points', 'd.points',
				'published', 'd.published'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		//$category = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '');
		//$this->setState('filter.category', $category);
		
		// List state information.
		parent::populateState('d.days', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		//$id	.= ':'.$this->getState('filter.category');
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';
		
		/*$category = $this->getState('filter.category');		
		if (is_numeric($category) && $category != 0) {
			$where = ' AND i.cat_id = ' . (int) $category;
		}*/

		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= JFactory::getDBO();
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$where .= " AND d.days LIKE ".$search." ";
		}
		
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$where .= ' AND d.published = ' . (int) $published;
		}


		return $where;
	}
	
	function getDays(){
		if(empty($this->_days)) {
			$limit = JRequest::getVar('limit', '25', '', 'int');
			$limitstart = JRequest::getVar('limitstart', '0', '', 'int');
			
			$orderCol	= $this->getState('list.ordering');
			if($orderCol=='d.id'){
				$orderCol = 'd.id';	
			}elseif($orderCol=='d.days'){
				$orderCol = 'd.days';
			}elseif($orderCol=='d.price'){
				$orderCol = 'd.price';
			}elseif($orderCol=='d.points'){
				$orderCol = 'd.points';
			}elseif($orderCol=='d.published'){
				$orderCol = 'd.published';
			}else{
				$orderCol = 'd.points';
			}
			
			$orderDirn	= $this->getState('list.direction');
			
			
		
			$db= JFactory::getDBO();	
			$query = "SELECT d.* FROM #__djcf_days d "
					."  WHERE 1  ".$this->_buildWhere()." order by ".$orderCol." ".$orderDirn." ";
			$this->_days = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_days;
	}
	
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
	
	public function getCountDays(){
		if(empty($this->_countDays)) {
			$db= JFactory::getDBO();
			$query = "SELECT count(d.id) FROM #__djcf_days d WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countDays=$db->loadResult();
		}
		return $this->_countDays;
	}
	
}
?>