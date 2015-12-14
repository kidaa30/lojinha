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

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class DjclassifiedsModelItemsunits extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id', 'a.name', 'a.published', 'a.ordering'
			);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.ordering', 'asc');
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
		
		$item_id = $this->getUserStateFromRequest($this->context.'.filter.item_id', 'item_id', '');
		$this->setState('filter.item_id', $item_id);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_djclassifieds');
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.item_id');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		$item_id =$this->getState('filter.item_id', false);

		// Select the required fields from the table.
		$select_default='a.*';
		
		$query->select($this->getState('list.select', $select_default));
		
		$query->from('#__djcf_items_units AS a');
		
		/*if ((int)$item_id > 0) {
			$query->select('rc.related_count');
			$query->join('LEFT', ' (SELECT COUNT(payment_id) as related_count, delivery_id FROM #__djc2_deliveries_payments WHERE payment_id=\''.$item_id.'\' GROUP BY delivery_id) AS rc ON a.id=rc.delivery_id ');
		}*/
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = ' . (int) $published);
		}
		else if ($published === '') {
			//$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.ordering');
		$orderDirn	= $this->state->get('list.direction', 'asc');
	
		$query->order($db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	
}