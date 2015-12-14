<?php
/**
 * @version $Id: djcatalog2.php 9 2014-10-14 12:24:11Z michal $
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

class plgDJReviewsDJCatalog2 extends JPlugin {
	
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
	}
	
	public function onObjectPrepareName($object_context) {
		$name = false;
		switch ($object_context) {
			case 'com_djcatalog2'			: $name = 'DJ-Catalog2'; break;
			case 'com_djcatalog2.item'		: $name = 'DJ-Catalog2 Item'; break;
			case 'com_djcatalog2.category'	: $name = 'DJ-Catalog2 Category'; break;
			case 'com_djcatalog2.producer'	: $name = 'DJ-Catalog2 Producer'; break;
			default							: break;
		}
		
		return $name;
	}
}