<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class DjClassifiedsViewItems extends JViewLegacy
{
	protected $items;
	protected $pagination;
	
	function display($tpl = null)
	{
		$this->categories	= $this->get('Categories');		
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->countItems	= $this->get('CountItems');
		$this->pagination	= $this->get('Pagination');
		

		/*
		jimport('joomla.html.pagination');		
		$limit = JRequest::getVar('limit', '25', '', 'int');
		$limitstart = JRequest::getVar('limitstart', '0', '', 'int');		
		$pagination = new JPagination($this->countItems, $limitstart, $limit);
		$this->pagination  = $pagination;*/
				


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();		
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$tpl = 'legacy';
		}else{			  									
			/*JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_CATEGORY'),
				'filter_category',
				JHtml::_('select.options', DJClassifiedsCategory::getCatSelect(), 'value', 'text', $this->state->get('filter.category'), true)
			);*/
			
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED')), 'value', 'text', $this->state->get('filter.published'), true)
			);
			
			JHtmlSidebar::addFilter(
				JText::_('COM_DJCLASSIFIEDS_SELECT_ACTIVE'),
				'filter_active',
				JHtml::_('select.options', array(JHtml::_('select.option', '1', 'COM_DJCLASSIFIEDS_ACTIVE'),JHtml::_('select.option', '0', 'COM_DJCLASSIFIEDS_HIDE')), 'value', 'text', $this->state->get('filter.active'), true)
			);
			
			$this->sidebar = JHtmlSidebar::render();		
		}
		
		parent::display($tpl);
	}
		
	
	protected function addToolbar()
	{
		$user = JFactory::getUser();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'generic.png');

		if ($user->authorise('core.create', 'com_djclassifieds')) {
			JToolBarHelper::addNew('item.add','JTOOLBAR_NEW');
		}
		if ($user->authorise('core.edit', 'com_djclassifieds')) {
			JToolBarHelper::editList('item.edit','JTOOLBAR_EDIT');
		}
		JToolBarHelper::divider();
		if ($user->authorise('core.admin', 'com_djclassifieds')) {
			if($par->get('store_org_img','1')==1){
				JToolBarHelper::custom('items.recreateThumbnails','move','move',JText::_('COM_DJCLASSIFIEDS_RECREATE_THUMBNAILS'),true,true);
			}
			JToolBarHelper::custom('items.resmushitThumbnails','loop','loop',JText::_('COM_DJCLASSIFIEDS_OPTIMIZE_THUMBNAILS'),true,true);
			JToolBarHelper::custom('items.generateCoordinates','refresh','refresh',JText::_('COM_DJCLASSIFIEDS_GENERATE_COORDINATES'),false,true);
			JToolBarHelper::divider();
		}
		if ($user->authorise('core.edit.state', 'com_djclassifieds')) {		
			JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom('items.archive', 'archive.png', 'archive_f2.png', 'JTOOLBAR_ARCHIVE', true);
		}
		if ($user->authorise('core.delete', 'com_djclassifieds')) {
			JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_DELETE');
		}
		JToolBarHelper::divider();
		if ($user->authorise('core.admin', 'com_djclassifieds')) {
			JToolBarHelper::preferences('com_djclassifieds', 450, 800);
			JToolBarHelper::divider();
		}
	}
}
?>