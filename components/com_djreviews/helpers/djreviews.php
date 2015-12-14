<?php
/**
 * @version $Id: djreviews.php 30 2015-02-25 16:01:31Z michal $
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

class DJReviewsHelper {
	
	protected static $params = array();
	
	public static function getParams($group_id = 0) {
		$group_id = (int)$group_id;
		if (!isset(self::$params[$group_id])) {
			if ($group_id == 0) {
				self::$params[$group_id] = JComponentHelper::getParams('com_djreviews');
			} else {
				$globalParams = JComponentHelper::getParams('com_djreviews');
				$db = JFactory::getDbo();
				$db->setQuery('SELECT params FROM #__djrevs_rating_groups WHERE id='.$group_id);
				$groupParams = $db->loadResult();
				if (!empty($groupParams)) {
					$groupParams = new JRegistry($groupParams);
					$globalParams->merge($groupParams); 
				}
				self::$params[$group_id] = $globalParams;
			}
		}
		return self::$params[$group_id];
	}
}