<?php
/**
* @version		1.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Payment Plugin
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

defined('_JEXEC') or die;

class plgQuickiconDjcfquickicon extends JPlugin
{
	public function onGetIcons($context)
	{	
		if ($context != $this->params->get('context', 'mod_quickicon')) {
			return;
		}
		
		$icons = array();
		
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$icons[] = array(
					'link' => 'index.php?option=com_djclassifieds',
					'image' => '../../../../plugins/quickicon/djcfquickicon/images/dj-classifieds.png',
					'text' => JText::_('DJ-Classifieds'),
					'id' => 'plg_djcf_quickicon'
			);
		} else {
			$icons[] = array(
					'link' => 'index.php?option=com_djclassifieds',
					'image' => 'list',
					'text' => JText::_('DJ-Classifieds'),
					'id' => 'plg_djcf_quickicon'
			);
		}
		
		return $icons;		
		
	}
}
