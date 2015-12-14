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

class DjClassifiedsModelRegions extends JModelList{
	
	public function __construct($config = array())
	{
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

		$region = $this->getUserStateFromRequest($this->context.'.filter.region', 'filter_region', '');
		$this->setState('filter.region', $region);
		
		// List state information.
		parent::populateState('r.name', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.region');
		$id	.= ':'.$this->getState('filter.published');
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';

		$region = $this->getState('filter.region');		
		if (is_numeric($region) && $region != 0) {
			$where = ' AND r.parent_id = ' . (int) $region;
		}
		
		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= JFactory::getDBO();
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$where .= " AND r.name LIKE ".$search." ";
		}
		
		$published = $this->getState('filter.published'); 
		if (is_numeric($published)) {
			$where .= ' AND r.published = ' . (int) $published;
		}
		
		return $where;
	}	

	function getRegions(){
		
			$search = $this->getState('filter.search');
			if($this->getState('filter.region')!='' || $search!='' || $this->getState('filter.published')!=''){				
				$db= JFactory::getDBO();
				$query = "SELECT r.*, rr.name as parent_name FROM #__djcf_regions r "
						."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
						."WHERE 1  ".$this->_buildWhere()." ORDER BY r.name";
				$db->setQuery($query);
				$regions=$db->loadObjectList();
			}else{
				$regions = DJClassifiedsRegion::getRegAll();							
			}
			
			$limitstart = $this->getState('list.start', 0);
			$limit = $this->getState('list.limit', 0);
			
			if ($limit > 0) {
				$regions = array_slice($regions, $limitstart, $limit);
			} else {
				$regions = array_slice($regions, 0);
			}
			
		return $regions;
	}	
	
	public function getPagination()
	{
		jimport('joomla.html.pagination');
		// Get a storage key.
		$store = $this->getStoreId('getPagination');
	
		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}
		
		$limitstart = $this->getState('list.start', 0);
		$limit = $this->getState('list.limit', 0);
	
		// Create the pagination object.
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page = new JPagination($this->getCountRegions(), $limitstart, $limit);
	
		// Add the object to the internal cache.
		$this->cache[$store] = $page;
	
		return $page;
	}
		
	
	public function getCountRegions(){		
			$db= JFactory::getDBO();
			$query = "SELECT count(r.id) FROM #__djcf_regions r WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$countRegions=$db->loadResult();		
		return $countRegions;
	}
	
	function getMainRegions(){
		if(empty($this->_mainregions)) {
			$db= JFactory::getDBO();
			$query = "SELECT r.id as value, r.name as text FROM #__djcf_regions r "
					."WHERE r.parent_id=0 ORDER BY r.name";
			$db->setQuery($query);
			$this->_mainregions=$db->loadObjectList();	
		}
		return $this->_mainregions;
	}	



}
?>