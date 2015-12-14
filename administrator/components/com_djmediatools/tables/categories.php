<?php
/**
 * @version $Id: categories.php 40 2014-09-08 14:28:34Z szymon $
 * @package DJ-MediaTools
 * @copyright Copyright (C) 2012 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 * DJ-MediaTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-MediaTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-MediaTools. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class DJMediatoolsTableCategories extends JTable
{
	public function __construct(&$db) {
		parent::__construct('#__djmt_albums', 'id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}
		
		if(empty($array['alias'])) {
			$array['alias'] = $array['title'];
		}
		$array['alias'] = JFilterOutput::stringURLSafe($array['alias']);
		if(trim(str_replace('-','',$array['alias'])) == '') {
			$array['alias'] = JFactory::getDate()->format("Y-m-d-H-i-s");
		}
		
		return parent::bind($array, $ignore);
	}
	
	public function store($updateNulls = false)
	{		
		$table = JTable::getInstance('Categories', 'DJMediatoolsTable');
		if ($table->load(array('alias'=>$this->alias,'parent_id'=>$this->parent_id)) && ($table->id != $this->id || $this->id==0)) {
			$alias = end(explode('-', $this->alias));
			if(preg_match('/\d{14}/', $alias)) $this->alias = substr($string, 0, -15);
			$this->alias .= '-'.JFactory::getDate()->format("YmdHis");
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_DJMEDIATOOLS_ALBUM_SAVED_UNIQUE_ALIAS', $this->alias));
		}
		$isNew = ($this->id==0 ? true : false);
		$success = parent::store($updateNulls);
		if($isNew && $success) {
			$this->reorder('parent_id = '.$this->parent_id);
		}
		
		return $success;
	}
	
	public function delete($pk = null) {
		
		$table = JTable::getInstance('Categories', 'DJMediatoolsTable');
		if (!empty($pk) && $table->load(array('parent_id'=>$pk))) {
			JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_DJMEDIATOOLS_ALBUM_DELETE_FAILD_CONTAINS_SUBALBUMS', $pk), 'error');
			return false;
		}
		
		return parent::delete($pk);
	}
}
