<?php
/**
 * @version $Id: fields.php 8 2014-10-14 10:13:30Z michal $
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class DJReviewsModelFields extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'a.id', 'a.name', 'a.group_id', 'a.ordering', 'a.published', 'a.weight', 'g.name'
			);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null)
	{
		// List state information.
		parent::populateState('a.name', 'asc');

		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$group_id = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_group_id');
		$this->setState('filter.group_id', $group_id);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_djreviews');
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.group_id');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$select_default = 'a.*, g.name as group_name, uc.name AS editor';

		$query->select($this->getState('list.select', $select_default));

		$query->from('#__djrevs_rating_fields AS a');

		// Join over the users for the checked out user.
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over groups
		$query->join('LEFT', '#__djrevs_rating_groups AS g ON g.id=a.group_id');

		// Filter by search in title.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else {
				$search = $db->quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.description LIKE '.$search.')');
			}
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.name');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.name');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		
		if ($orderCol == 'a.ordering' || $orderCol == 'g.name') {
			$orderCol = 'g.name '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));
		return $query;
	}
}