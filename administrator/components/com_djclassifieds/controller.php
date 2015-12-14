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
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class DJClassifiedsController extends JControllerLegacy
{
	
		protected $default_view = 'cpanel';
	
	public function display($cachable = false, $urlparams = false)
	{
		//require_once JPATH_COMPONENT.'/helpers/djimageslider.php';
		//DJClassifiedsHelper::addSubmenu(JRequest::getCmd('view', 'items'));
		
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$version = new JVersion;
		$vName = JFactory::getApplication()->input->getCmd('view', 'cpanel');					
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin('djclassifieds');
		
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			JSubMenuHelper::addEntry(JText::_('com_djclassifieds_CPANEL'), 'index.php?option=com_djclassifieds&view=cpanel', $vName=='cpanel');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_CATEGORIES'), 'index.php?option=com_djclassifieds&view=categories', $vName=='categories');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'index.php?option=com_djclassifieds&view=items', $vName=='items');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_FIELDS'), 'index.php?option=com_djclassifieds&view=fields', $vName=='fields');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'), 'index.php?option=com_djclassifieds&view=regions', $vName=='regions');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'), 'index.php?option=com_djclassifieds&view=promotions', $vName=='promotions');
			if($par->get('durations_list','')){
				JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_DURATIONS'), 'index.php?option=com_djclassifieds&view=days', $vName=='days');
			}			
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_TYPES'), 'index.php?option=com_djclassifieds&view=types', $vName=='types');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES'), 'index.php?option=com_djclassifieds&view=points', $vName=='points');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_USERS_POINTS'), 'index.php?option=com_djclassifieds&view=userspoints', $vName=='userspoints');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_USERS_PROFILES'), 'index.php?option=com_djclassifieds&view=profiles', $vName=='profiles');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_PAYMENTS'), 'index.php?option=com_djclassifieds&view=payments', $vName=='payments');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_EMAILS_TEMPLATES'), 'index.php?option=com_djclassifieds&view=emails', $vName=='emails');
			JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS_UNITS'), 'index.php?option=com_djclassifieds&view=itemsunits', $vName=='itemsunits');
			$sidebars = $dispatcher->trigger('onAdminPrepareSidebar', array ());
			if(count($sidebars)){
				foreach($sidebars as $sidebar){
					if(is_array($sidebar)){
						foreach($sidebar as $entry){
							JSubMenuHelper::addEntry($entry['label'], $entry['link'], $vName==$entry['view']);
						}
					}
				}	
			}
		} else {
			JHtmlSidebar::addEntry(JText::_('com_djclassifieds_CPANEL'), 'index.php?option=com_djclassifieds&view=cpanel', $vName=='cpanel');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_CATEGORIES'), 'index.php?option=com_djclassifieds&view=categories', $vName=='categories');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'index.php?option=com_djclassifieds&view=items', $vName=='items');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_FIELDS'), 'index.php?option=com_djclassifieds&view=fields', $vName=='fields');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'), 'index.php?option=com_djclassifieds&view=regions', $vName=='regions');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'), 'index.php?option=com_djclassifieds&view=promotions', $vName=='promotions');
			if($par->get('durations_list','')){
				JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_DURATIONS'), 'index.php?option=com_djclassifieds&view=days', $vName=='days');
			}
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_TYPES'), 'index.php?option=com_djclassifieds&view=types', $vName=='types');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES'), 'index.php?option=com_djclassifieds&view=points', $vName=='points');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_USERS_POINTS'), 'index.php?option=com_djclassifieds&view=userspoints', $vName=='userspoints');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_USERS_PROFILES'), 'index.php?option=com_djclassifieds&view=profiles', $vName=='profiles');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_PAYMENTS'), 'index.php?option=com_djclassifieds&view=payments', $vName=='payments');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_EMAILS_TEMPLATES'), 'index.php?option=com_djclassifieds&view=emails', $vName=='emails');
			JHtmlSidebar::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS_UNITS'), 'index.php?option=com_djclassifieds&view=itemsunits', $vName=='itemsunits');
			$sidebars = $dispatcher->trigger('onAdminPrepareSidebar', array ());
			if(count($sidebars)){
				foreach($sidebars as $sidebar){
					if(is_array($sidebar)){
						foreach($sidebar as $entry){
							JHtmlSidebar::addEntry($entry['label'], $entry['link'], $vName==$entry['view']);	
						}
					}
				}	
			}
		}
				
		parent::display();

		return $this;
	}
	
	public function upload() {
	
		// todo: secure upload from injections
		$user = JFactory::getUser();
		/*if (!$user->authorise('core.manage', 'com_djmediatools')){
			echo JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN');
			exit(0);
		}*/
	
		DJUploadHelper::upload();
	
		return true;
	}
	
	
}