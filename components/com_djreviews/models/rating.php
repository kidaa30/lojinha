<?php
/**
 * @version $Id: rating.php 35 2015-04-07 13:23:38Z michal $
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


class DJReviewsModelRating extends JModelLegacy {
	
	protected $_context = 'com_djreviews.rating';
	
	protected function populateState()
	{
		$app = JFactory::getApplication();
		
		$object_id = $app->input->get('id');
		$this->setState('filter.object_id', $object_id);
	}
	
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.object_id');

		return parent::getStoreId($id);
	}
	
	public function getItem($pk = null)
	{
		
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('filter.object_id');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('o.*, count(r.id) as r_count, count(rat.id) as rat_count, g.name as group_name, g.description as group_description');
		$query->from('#__djrevs_objects as o');
		$query->join('inner','#__djrevs_rating_groups as g ON g.id = o.rating_group_id');
		$query->join('left','#__djrevs_reviews as r ON r.object_id = o.id');
		$query->join('left','#__djrevs_reviews as rat ON rat.object_id = o.id AND rat.id= r.id AND rat.avg_rate > 0.0 AND rat.published != 0');
		$query->where('o.id = '.(int)$pk);
		$query->where('r.published != 0');
			
		$db->setQuery($query);
		$item = $db->loadObject();	

		if($item){			
			$query = $db->getQuery(true);				
			$query->select('f.*, i.rating');
			$query->from('#__djrevs_rating_fields as f');
			$query->join('LEFT', '#__djrevs_objects_items as i ON i.field_id = f.id AND i.object_id = '.(int)$item->id);
			$query->where('f.group_id='.(int)$item->rating_group_id);
			$query->order('f.ordering ASC');
	
			$db->setQuery($query);
			$item->rating_fields = $db->loadObjectList('id');
		}					
		
		
		/*$table = $this->getTable('Objects', 'DJReviewsTable');

		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}

		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'stdClass');

		if (property_exists($item, 'params'))
		{
			$registry = new JRegistry;
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
		*/	
		return $item;
	}
}