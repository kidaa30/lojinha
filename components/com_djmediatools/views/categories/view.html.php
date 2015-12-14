<?php
/**
 * @version $Id: view.html.php 58 2015-06-10 12:15:24Z szymon $
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
 
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class DJMediatoolsViewCategories extends JViewLegacy {
	
	protected $params = null;
	protected $category = null;
	protected $categories = null;
	protected $pagination = null;
	
	function display($tpl = null) {
		
		// Initialise variables
		$category	= $this->get('Item');
		$categories	= $this->get('Items');		
		$pagination = $this->get('Pagination');
		$params		= $this->get('Params');
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		
		if ($category === false)
		{
			JError::raiseError(404, JText::_('COM_DJMEDIATOOLS_ERROR_CATEGORY_NOT_FOUND'));
			return false;
		}
		
		foreach($categories as $item) {			
			if(!$item->thumb = DJImageResizer::createThumbnail($item->image, 'media/djmediatools/cache', $params->get('cwidth', 200), $params->get('cheight', 150), $params->get('cresizing', 'crop'), $params->get('cquality', 80))) {
				$item->thumb = 'administrator/components/com_djmediatools/assets/icon-album.png';
			}
			if(strcasecmp(substr($item->thumb, 0, 4), 'http') != 0 && !empty($item->thumb)) {
				$item->thumb = JURI::root(true).'/'.$item->thumb;
			}
		}
		
		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		
		$this->assignRef('params', $params);
		$this->assignRef('category', $category);
		$this->assignRef('categories', $categories);
		$this->assignRef('pagination', $pagination);
		
		$this->_prepareDocument();
		
        parent::display($tpl);
	}
	
	protected function _prepareDocument() {
			
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$pathway= $app->getPathway();
		$title	= null;
		
		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('COM_DJMEDIATOOLS'));
		}
		$title = $this->params->get('page_title', '');
		
		if ($menu && ($menu->query['option'] != 'com_djmediatools' || $menu->query['view'] != 'categories'))
		{
			$pathway->addItem(JText::_('COM_DJMEDIATOOLS_CATEGORIES'), '');
		}
		
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

	}

}




