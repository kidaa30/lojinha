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

class DjClassifiedsModelProfiles extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'u.name',
				'username', 'u.id',
				'email', 'u.email',
				'p.u_points'					
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		// List state information.
		parent::populateState('u.id', 'desc');
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
				
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');		
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';				

		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= JFactory::getDBO();
			$search = $db->Quote('%'.$db->escape($search, true).'%');
			$where .= " AND (u.name LIKE ".$search." OR u.username LIKE ".$search." OR u.email LIKE ".$search." )";
		}				
		
		return $where;
	}
	
	function getItems(){
		if(empty($this->_profiles)) {
			$limit = $this->getState('list.limit');
			$limitstart = $this->getState('list.start');			
			
			$orderCol	= $this->state->get('list.ordering');
			$orderDirn	= $this->state->get('list.direction');									
			
			$db= JFactory::getDBO();	
			$query = "SELECT u.*, img.path as img_path, img.name as img_name, img.ext as img_ext, p.u_points  FROM #__users u "
			 		."LEFT JOIN ( SELECT img.id, img.item_id, img.name, img.path, img.ext, img.ordering 
			 					  FROM (SELECT * FROM #__djcf_images WHERE type='profile' ORDER BY ordering) img GROUP BY img.item_id ) AS img ON img.item_id=u.id "
			 		."LEFT JOIN (SELECT SUM(p.points) as u_points, p.user_id 
			 					  FROM #__djcf_users_points p GROUP BY p.user_id) p ON p.user_id=u.id "		
					."  WHERE 1  ".$this->_buildWhere()." order by ".$orderCol." ".$orderDirn." ";
			$this->_profiles = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_profiles;
	}
	
	
	public function getCountItems(){
		if(empty($this->_countProfiles)) {
			$db= JFactory::getDBO();
			$query = "SELECT count(u.id) FROM #__users u WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countProfiles=$db->loadResult();
		}
		return $this->_countProfiles;
	}
	public function _getListQuery(){
		$query = "SELECT u.* FROM #__users u WHERE 1 ".$this->_buildWhere();
		return $query;
	}
}
?>