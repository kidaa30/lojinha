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

/*Items Model*/

//jimport('joomla.application.component.model');
jimport('joomla.application.component.modellist');

class DjClassifiedsModelFieldsXref extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'f.name',
				'id', 'f.id',				
				'label', 'f.label',
				'label', 'f.label',
				'tooltip', 'f.tooltip',
				'type', 'f.type',
				'in_search', 'f.in_search',
				'search_type', 'f.search_type',
				'published', 'f.published'
			);
		}

		parent::__construct($config);
	}
	
	function getFields(){
		if(empty($this->_fieldsxref)) {
		
			$orderCol	= $this->getState('list.ordering');
			if(!$orderCol){
				$orderCol = 'fx.ordering';
			}
			
			$orderDirn	= $this->getState('list.direction');
		
			$cat_id=JRequest::getVar('id','');
			$db= JFactory::getDBO();
			$query = "SELECT f.*, fx.id as xref,fx.ordering, fx.active FROM #__djcf_fields f "
					." LEFT JOIN (SELECT *, IF((field_id IS NULL), NULL, 1) as active FROM #__djcf_fields_xref WHERE cat_id=".$cat_id.") as fx "
					."ON fx.field_id=f.id "
					." WHERE 1 AND f.source=0 ORDER BY fx.active DESC, ".$orderCol." ".$orderDirn." ";
		
			$db->setQuery($query);
			$this->_fieldsxref=$db->loadObjectList();
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_fieldsxref;
	}
	
	



}
?>