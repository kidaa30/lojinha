<?php
/**
 * @version $Id: view.raw.php 21 2014-10-31 11:56:02Z lukasz $
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

class DJReviewsViewRating extends JViewLegacy {
	
	public $_view_variables = array();
	
	// DJ-Reviews contains "views" in the class' name - it causes problems on J!2.5 when Joomla tries to guess tpl path using getName() method
	protected $_name = 'rating';
	
	public function __construct($config = array()) {
		$app = JFactory::getApplication();
		
		if (isset($config['reviews_variables'])) {
			$this->_view_variables = $config['reviews_variables'];
			foreach ($this->_view_variables as $key=>$var) {
				$app->input->set('djreviews_'.$key, $var);
			}
		}
		
		return parent::__construct($config);
	}
	
	public function display($tpl = null) {
		$app = JFactory::getApplication();
		
		$option = $app->input->get('option');
		
		$object_id = false;
		
		if ($option == 'com_djreviews') {
			$object_id = $app->input->getInt('id');
		} else {
			$object_id = $app->input->getInt('djreviews_id');
		}

		if (!$object_id) {
			throw new Exception(JText::_('COM_DJREVIEWS_ERROR_OBJECT_ID_NOT_SPECIFIED'), 404);
		}

		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		//ob_start();
		parent::display($tpl);
		//$body = ob_get_contents();
		//ob_end_clean();
		
		//return $body;
	}
}