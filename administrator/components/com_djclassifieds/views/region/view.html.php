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


class DJClassifiedsViewRegion extends JViewLegacy
{

	function display($tpl = null)
	{				
	    $this->region = $this->get('Region');		
		$this->mainregions = $this->get('MainRegions');
		
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		
		$version = new JVersion;
		if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
			$tpl = 'legacy';
		}
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu',1);
		
		$user		= JFactory::getUser();
		$isNew		= ($this->region->id == 0);

		$text2 = $isNew ? JText::_( 'COM_DJCLASSIFIEDS_NEW' ) : JText::_( 'COM_DJCLASSIFIEDS_EDIT' );
		if(!$isNew){
			if($this->region->parent_id>0){
				$text = JText::_( 'COM_DJCLASSIFIEDS_CITY' );
			}else{
				$text = JText::_( 'COM_DJCLASSIFIEDS_REGION' );	
			}
		}else{
			$text = JText::_( 'COM_DJCLASSIFIEDS_REGION_CITY' );
		}
		JToolBarHelper::title(   $text.': <small><small>[ ' . $text2.' ]</small></small>', 'generic.png' );

		JToolBarHelper::apply('region.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('region.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('region.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		//JToolBarHelper::custom('item.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		JToolBarHelper::cancel('region.cancel', 'JTOOLBAR_CANCEL');
	}

}