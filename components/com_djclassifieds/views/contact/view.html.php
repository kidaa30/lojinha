<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
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
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class DJClassifiedsViewContact extends JViewLegacy{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/contact');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$theme = $par->get('theme','default');
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/contact');
		}
	}
	
	function display($tpl = NULL){
		global $mainframe;
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );		
		$user 		= JFactory::getUser();
		$app 		= JFactory::getApplication();
		$message_s 	= JRequest::getInt('ms', 0);

		//echo $val;
		$e_mesage = '';
		$e_type = '';
		
			if($message_s){
				$e_mesage = JText::_('COM_DJCLASSIFIEDS_MESSAGE_SEND');
				$e_type = 'message';
			}else if($user->id>0){			
				$model 		= $this->getModel();				
				$item 		= $model->getItem();
				$bid 		= $model->getBid();	
				
				if($item->user_id==$user->id){
					if($bid){					
						$this->assignRef('item',$item);
						$this->assignRef('bid',$bid);
					}else{
						$e_mesage = JText::_('COM_DJCLASSIFIEDS_WRONG_BID');
						$e_type = 'error';
					}																													
					$this->assignRef('item',$item);
					$this->assignRef('bid',$bid);								
				}else{
					$e_mesage = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
					$e_type = 'error';
				}									
			}else{
				$e_mesage = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
				$e_type = 'error';
			}
		
		$this->assignRef('e_mesage',$e_mesage);
		$this->assignRef('e_type',$e_type);
		
		parent::display();
	}

}




