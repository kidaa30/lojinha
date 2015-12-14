<?php
/**
* @version 2.0
* @package DJ Classifieds
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
jimport('joomla.html.pagination');

class DJClassifiedsViewUserpoints extends JViewLegacy{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/userpoints');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$theme = $par->get('theme','default');
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/userpoints');
		}
	}	
	
	public function display($tpl = null)
	{
		global $mainframe;
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		if($user->id=='0'){
			$uri = JFactory::getURI();
			$app->redirect('index.php?option=com_users&view=login&return='.base64_encode($uri),JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN'));
		}else{
			$model = $this->getModel();
			$points= $model->getPoints();
			$count_points = $model->getCountPoints();
			$user_points = $model->getUserPoints();
			
				$limit	= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
				$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
				$pagination = new JPagination( $count_points, $limitstart, $limit );
	
			$this->assignRef('points', $points);
			$this->assignRef('user_points', $user_points);				
			$this->assignRef('pagination', $pagination);
			
		}
		  parent::display();		  
	}

}




