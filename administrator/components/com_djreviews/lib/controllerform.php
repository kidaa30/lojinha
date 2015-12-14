<?php
/**
 * @version $Id: controllerform.php 8 2014-10-14 10:13:30Z michal $
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

jimport('joomla.application.component.controllerform');

$version = new JVersion;
if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
	abstract class DJReviewsControllerForm extends JControllerForm
	{
		protected function postSaveHook(JModel &$model, $validData = array())
		{
			if (method_exists($this, '_postSaveHook')) {
				return $this->_postSaveHook($model, $validData);
			}
		}

	}
} else {
	abstract class DJReviewsControllerForm extends JControllerForm
	{
		protected function postSaveHook(JModelLegacy $model, $validData = array())
		{
			if (method_exists($this, '_postSaveHook')) {
				return $this->_postSaveHook($model, $validData);
			}
		}

	}
}

