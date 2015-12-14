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

class DjClassifiedsModelFields extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'f.name',
				'id', 'f.id',				
				'label', 'f.label',
				'label', 'f.label',
				'tooltip', 'f.tooltip',
				'source', 'f.source',
				'type', 'f.type',
				'in_search', 'f.in_search',
				'search_type', 'f.search_type',
				'published', 'f.published',
				'ordering','f.ordering'
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

		$source = $this->getUserStateFromRequest($this->context.'.filter.source', 'filter_source', '');
		$this->setState('filter.source', $source);		
		
		//$category = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '');
		//$this->setState('filter.category', $category);
		
		// List state information.
		parent::populateState('f.label', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.source');
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
			$where .= " AND f.name LIKE ".$search." ";
		}
		
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$where .= ' AND f.published = ' . (int) $published;
		}
		
		$source = $this->getState('filter.source');
		if (is_numeric($source)) {
			$where .= ' AND f.source = ' . (int) $source;
		}


		return $where;
	}
	
	function getFields(){
		if(empty($this->_fields)) {
			$limit = JRequest::getVar('limit', '25', '', 'int');
			$limitstart = JRequest::getVar('limitstart', '0', '', 'int');
			
			$orderCol	= $this->getState('list.ordering');
			if($orderCol=='f.id'){
				$orderCol = 'f.id';	
			}elseif($orderCol=='f.name'){
				$orderCol = 'f.name';
			}elseif($orderCol=='f.label'){
				$orderCol = 'f.label';
			}elseif($orderCol=='f.type'){
				$orderCol = 'f.type';
			}elseif($orderCol=='f.in_search'){
				$orderCol = 'f.in_search';
			}elseif($orderCol=='f.search_type'){
				$orderCol = 'f.search_type';
			}elseif($orderCol=='f.source'){
				$orderCol = 'f.source';
			}elseif($orderCol=='f.ordering'){
				$orderCol = 'f.ordering';
			}else{
				$orderCol = 'f.label';
			}
			
			$orderDirn	= $this->getState('list.direction');
			
			
		
			$db= JFactory::getDBO();	
			$query = "SELECT f.* FROM #__djcf_fields f "
					."  WHERE 1  ".$this->_buildWhere()." order by ".$orderCol." ".$orderDirn." ";
			$this->_fields = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_fields;
	}
	
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
	
	public function getCountFields(){
		if(empty($this->_countFields)) {
			$db= JFactory::getDBO();
			$query = "SELECT count(f.id) FROM #__djcf_fields f WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countFields=$db->loadResult();
		}
		return $this->_countFields;
	}
	



}
?>