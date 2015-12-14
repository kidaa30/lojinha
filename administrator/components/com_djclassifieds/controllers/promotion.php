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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport( 'joomla.database.table' );


class DJClassifiedsControllerPromotion extends JControllerLegacy {
	
	public function getModel($name = 'Promotion', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Promotion', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('edit', 'add');
    }

	
	public function add(){
		//$data = JFactory::getApplication();		
		$user = JFactory::getUser();
		if(JRequest::getVar('id',0)){
			if (!$user->authorise('core.edit', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=promotions' );
				return false;
			}
		}else{
			if (!$user->authorise('core.create', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=promotions' );
				return false;
			}
		}
		JRequest::setVar('view','promotion');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=promotions' );
	}
	
	public function save(){
    	$app = JFactory::getApplication();
		
		$model = $this->getModel('promotion');
		$row = &JTable::getInstance('Promotions', 'DJClassifiedsTable');
		
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
				
    	if (!$row->bind(JRequest::get('post')))
    	{
	        echo "<script> alert('".$row->getError()."');
				window.history.go(-1); </script>\n";
        	exit ();
    	}

		if (!$row->store())
    	{
        	echo "<script> alert('".$row->getError()."');
				window.history.go(-1); </script>\n";
        	exit ();	
    	}
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=promotion.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_PROMOTION_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=promotion.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_PROMOTION_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=promotions';
            	$msg = JText::_('COM_DJCLASSIFIEDS_PROMOTION_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>