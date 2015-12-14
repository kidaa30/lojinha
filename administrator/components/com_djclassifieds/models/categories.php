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

class DjClassifiedsModelCategories extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'c.name',
				'id', 'c.id',				
				'price', 'c.price',				
				'published', 'c.published',
				'ordering', 'c.ordering'
			);
		}
		parent::__construct($config);
	}
		
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$category = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '');
		
		$this->setState('filter.category', $category);
		
		// List state information.
		parent::populateState('ordering', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.category');
		
		return parent::getStoreId($id);
	}

	function getCategories(){
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		//echo $orderCol.' '.$orderDirn.'<br /><br />'; 
		if(empty($this->_categories)) {
			
			if($this->getState('filter.category')!=''){
				if($this->getState('filter.category')==-1){
					$cat_f = 0;
				}else{
					$cat_f = $this->getState('filter.category');
				}
				 
				$db= JFactory::getDBO();
				$query = "SELECT c.*, cc.name as parent_name FROM #__djcf_categories c "
						."LEFT JOIN #__djcf_categories cc ON c.parent_id=cc.id "
						."WHERE c.parent_id=".$cat_f." ORDER BY c.".$orderCol.' '.$orderDirn;
				$db->setQuery($query);
				$this->_categories=$db->loadObjectList();
			}else{
				if($orderCol=='ordering'){
					$orderCol = 'ord';
				}
				
				$this->_categories = DJClassifiedsCategory::getCatAll(0,$orderCol,$orderDirn);	
			}
			
		}
		return $this->_categories;
	}
	
	
	public function getCountCategories(){
		if(empty($this->_countCategories)) {
			
			$db= JFactory::getDBO();
			$query_where = '';
			if($this->getState('filter.category')!=''){
				$query_where = " WHERE c.parent_id=".$this->getState('filter.category');	
			}
			$query = "SELECT count(c.id) FROM #__djcf_categories c ".$query_where;
			$db->setQuery($query);
			$this->_countCategories=$db->loadResult();
		}
		return $this->_countCategories;
	}



}
?>