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
jimport('joomla.html.pagination');

class DJClassifiedsViewCategories extends JViewLegacy{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/categories');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$theme = $par->get('theme','default');
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/categories');
		}
	}	
	
	function display($tpl = null){
					
		$document = JFactory::getDocument();
		//$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$app	= JFactory::getApplication();
		$model = $this->getModel();
		$par = $model->getParams();
				
		$cats= DJClassifiedsCategory::getCatAllItemsCount(1,'c.name');
	//	echo '<pre>';print_r($cats);die();
	
		$cat_images='';
		if($par->get('cattree_img',0)){
			$cat_images = $model->getCatImages();
		}
		
		$this->assignRef('cats',$cats);
		$this->assignRef('cat_images',$cat_images);
		$this->assignRef('par',$par);

        parent::display($tpl);
	}

}




