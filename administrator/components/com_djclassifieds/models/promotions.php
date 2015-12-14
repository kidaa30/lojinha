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

class DjClassifiedsModelPromotions extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'p.name',
				'id', 'p.id',				
				'label', 'p.label',
				'description', 'p.description',
				'price', 'p.price',
				'points', 'p.points',
				'published', 'p.published',
				'ordering', 'p.ordering'
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
		parent::populateState('p.ordering', 'asc');
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
			$where .= " AND p.name LIKE ".$search." ";
		}
		
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$where .= ' AND p.published = ' . (int) $published;
		}


		return $where;
	}
	
	function getPromotions(){
		if(empty($this->_promotions)) {
			$limit = JRequest::getVar('limit', '25', '', 'int');
			$limitstart = JRequest::getVar('limitstart', '0', '', 'int');
			
			$orderCol	= $this->getState('list.ordering');
			if($orderCol=='p.id'){
				$orderCol = 'p.id';	
			}elseif($orderCol=='p.name'){
				$orderCol = 'p.name';
			}elseif($orderCol=='p.label'){
				$orderCol = 'p.label';
			}elseif($orderCol=='p.price'){
				$orderCol = 'p.price';
			}elseif($orderCol=='p.points'){
				$orderCol = 'p.points';
			}elseif($orderCol=='p.ordering'){
				$orderCol = 'p.ordering';
			}else{
				$orderCol = 'p.ordering';
			}
			
			$orderDirn	= $this->getState('list.direction');
			
			
		
			$db= JFactory::getDBO();	
			$query = "SELECT p.* FROM #__djcf_promotions p "
					."  WHERE 1  ".$this->_buildWhere()." order by ".$orderCol." ".$orderDirn." ";
			$this->_promotions = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_promotions;
	}
	
	protected function getReorderConditions($table)
	{
		$condition = array();
		//$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
	
	public function getCountPromotions(){
		if(empty($this->_countPromotions)) {
			$db= JFactory::getDBO();
			$query = "SELECT count(p.id) FROM #__djcf_promotions p WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countPromotions=$db->loadResult();
		}
		return $this->_countPromotions;
	}
	



}
?>