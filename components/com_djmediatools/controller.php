<?php
/**
 * @version $Id: controller.php 21 2013-11-06 08:14:17Z szymon $
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
 
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class DJMediatoolsController extends JControllerLegacy
{
	
	public function display($cachable = true, $urlparams = false)
	{
		$vName	= JRequest::getCmd('view', 'categories');
		JRequest::setVar('view', $vName);
		
		$document= JFactory::getDocument();
		if($vName=='item') {
			$document->addStyleSheet('components/com_djmediatools/assets/css/item.css');
		} else {
			$document->addStyleSheet('components/com_djmediatools/assets/css/default.css');
		}
		
		$urlparams = array(
				'id' => 'STRING',
				'cid' => 'STRING',
				'Itemid' => 'INT',
				'limit' => 'UINT',
				'limitstart' => 'UINT',
				'start' => 'UINT',
				'lang' => 'CMD',
				'tmpl' => 'CMD',
		);
		
		return parent::display($cachable, $urlparams);
	}
	
	public function getcss(){
	
		$app = JFactory::getApplication();
		$layout = JRequest::getCmd('layout','slideshow');
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/css');
		
		$options = explode('&', base64_decode(JRequest::getVar('params')));
		
		if($options) foreach($options as $option) {
			$option = explode('=', $option);
			$_GET[$option[0]] = isset($option[1]) ? $option[1] : '';
		}
		
		// Get the css file path.
		$path = JPATH_ROOT.DS.'components'.DS.'com_djmediatools'.DS.'layouts'.DS.'slideshow'.DS.'css'.DS.$layout.'.css.php';
		$ipath = JURI::root(true).'/components/com_djmediatools/layouts/slideshow';
		if(file_exists(JPATH_ROOT.DS.'templates'.DS.$app->getTemplate().DS.'css'.DS.$layout.'.css.php')) {
			$path = JPATH_ROOT.DS.'templates'.DS.$app->getTemplate().DS.'css'.DS.$layout.'.css.php';
			$ipath = JURI::root(true).'/templates/'.$app->getTemplate();
		} else if(file_exists(JPATH_ROOT.DS.'components'.DS.'com_djmediatools'.DS.'layouts'.DS.$layout.DS.'css'.DS.$layout.'.css.php')) {
			$path = JPATH_ROOT.DS.'components'.DS.'com_djmediatools'.DS.'layouts'.DS.$layout.DS.'css'.DS.$layout.'.css.php';
			$ipath = JURI::root(true).'/components/com_djmediatools/layouts/'.$layout;
		}
		
		include($path);	
	}
	
}
