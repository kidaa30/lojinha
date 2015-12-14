<?php
/**
 * @version $Id: field.php 8 2014-10-14 10:13:30Z michal $
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

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'modeladmin.php');

class DJReviewsModelField extends DJReviewsModelAdmin
{
	protected $text_prefix = 'COM_DJREVIEWS';
	protected $form_name = 'field';

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	public function getTable($type = 'Fields', $prefix = 'DJReviewsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djreviews.'.$this->form_name, $this->form_name, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djreviews.edit.'.$this->form_name.'.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function _prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		$db = JFactory::getDbo();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		
		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db->setQuery('SELECT MAX(ordering) FROM #__djrevs_rating_fields WHERE group_id = '.$table->group_id);
				$max = $db->loadResult();
		
				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'group_id = '.(int) $table->group_id;
		return $condition;
	}
	
	public function publish(&$pks, $value = 1) {
		$published = parent::publish($pks, $value);
		if (!$published) {
			return false;
		}
		
		// mark to recalculate
		$query = ' UPDATE #__djrevs_reviews AS r '
				.' INNER JOIN #__djrevs_reviews_items as ri ON ri.review_id = r.id AND ri.field_id IN ('.implode(',', $pks).')'
				.' SET r.recalculate = 1 '
		;
		$this->_db->setQuery($query);
		$this->_db->query();
		
		return true;
	}
	
	public function save($data)
	{
		$saved = parent::save($data);
		if (!$saved) {
			return false;
		}
		
		if (!empty($data['group_id'])) {
			// mark to recalculate
			$query = ' UPDATE #__djrevs_reviews '
					.' SET recalculate = 1 '
					.' WHERE rating_group_id='.(int)$data['group_id']
			;
			$this->_db->setQuery($query);
			$this->_db->query();
		}
		
		return true;
	}

	public function delete(&$cid) {
		$deleted = parent::delete($cid);
		
		if (!$deleted) {
			return false;
		}
		
		// mark to recalculate first
		$query = ' UPDATE #__djrevs_reviews AS r '
				.' INNER JOIN #__djrevs_reviews_items as ri ON ri.review_id = r.id AND ri.field_id IN ('.implode(',', $cid).')'
				.' SET r.recalculate = 1 '
		;
		
		$this->_db->setQuery($query);
		$this->_db->query();

		$this->_db->setQuery('DELETE FROM #__djrevs_reviews_items WHERE field_id IN ('.implode(',', $cid).')');
		if ($this->_db->query() == false) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$this->_db->setQuery('DELETE FROM #__djrevs_objects_items WHERE field_id IN ('.implode(',', $cid).')');
		if ($this->_db->query() == false) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		return true;
		
	}
}