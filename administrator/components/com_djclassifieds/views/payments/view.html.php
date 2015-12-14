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


class DjClassifiedsViewPayments extends JViewLegacy
{
	protected $payments;
	protected $pagination;
	
	function display($tpl = null)
	{			
		
		$this->state		= $this->get('State');
		$this->payments		= $this->get('Payments');
		$this->countPayments	= $this->get('CountPayments');
		$this->pagination	= $this->get('Pagination');

		$dispatcher			= JDispatcher::getInstance();
		$dispatcher->trigger('onAdminPreparePaymentsHistory', array (&$this->payments));

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		if (class_exists('JHtmlSidebar')){
			$this->sidebar = JHtmlSidebar::render();
		}
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$tpl = 'legacy';
		}
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_PAYMENTS'), 'generic.png');
		$user = JFactory::getUser();
		//JToolBarHelper::addNew('payment.add','JTOOLBAR_NEW');
		//JToolBarHelper::editList('payment.edit','JTOOLBAR_EDIT');
		//JToolBarHelper::divider();
		//JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_DELETE');
		if ($user->authorise('core.admin', 'com_djclassifieds')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_djclassifieds', 450, 800);
			JToolBarHelper::divider();
		}
	}
}
?>