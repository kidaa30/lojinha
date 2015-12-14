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


class DJClassifiedsControllerField extends JControllerLegacy {
	
	public function getModel($name = 'Field', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Field', $prefix = 'DJClassifiedsTable', $config = array())
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
		$user = JFactory::getUser();
		if(JRequest::getVar('id',0)){
			if (!$user->authorise('core.edit', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=fields' );
				return false;
			}
		}else{
			if (!$user->authorise('core.create', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=fields' );
				return false;
			}
		}
		//$data = JFactory::getApplication();		
		JRequest::setVar('view','field');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=fields' );
	}
	
	public function save(){
    	$app = JFactory::getApplication();
		
		$model = $this->getModel('field');
		$row = &JTable::getInstance('Fields', 'DJClassifiedsTable');
		
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
				
    	if (!$row->bind(JRequest::get('post')))
    	{
	        echo "<script> alert('".$row->getError()."');
				window.history.go(-1); </script>\n";
        	exit ();
    	}

			$row->name=strtolower($row->name);
			$row->name=str_ireplace(' ', '_', $row->name); 
			$row->name=ereg_replace("[^a-z0-9_]", "", $row->name );
		   	$name=$row->name;
			$next_name=1;
				$query = "SELECT count(id) FROM #__djcf_fields WHERE name='".$name."' ";
				if($row->id>0){	
					$query .= " AND id!='".$row->id."' ";
				}
				$db =& JFactory::getDBO();		
				$db->setQuery($query);
				$exist =$db->loadResult();
				while($exist>0){
					$name = $row->name.'_'.$next_name; 
					$query = "SELECT count(id) FROM #__djcf_fields WHERE name='".$name."' ";
					if($row->id>0){	
						$query .= " AND id!='".$row->id."' ";
					}
					$db =& JFactory::getDBO();		
					$db->setQuery($query);
					$exist =$db->loadResult();	
					$next_name++;
				}
				$row->name = $name;
				$row->values = preg_replace('/;[\r\n\s]+/', ';', $row->values);
		$row->params = JRequest::getVar('params', '', 'post', 'string', JREQUEST_ALLOWRAW);
		if(!$row->source){
			$row->source = 0;
		}
		if(!$row->ordering){
			$query = "SELECT ordering FROM #__djcf_fields WHERE source = ".$row->source." ORDER BY ordering DESC LIMIT 1";
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$order =$db->loadObject();
			$row->ordering = $order->ordering + 1;
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
            	$link = 'index.php?option=com_djclassifieds&task=field.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=field.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=fields';
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	/*
	function addtocategories()
	{
	    $app = JFactory::getApplication();
	    $id = JRequest::getInt('id', '0');
	    $db = & JFactory::getDBO();
	    		
			$query = "DELETE FROM #__djcf_fields_values WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
			
			$query = "DELETE FROM #__djcf_payments WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
	    
	    $app->redirect('index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_ITEMS_DELETED'));
	}*/
	
	function addtocategories()
	{
		
	    $app = JFactory::getApplication();
	    $id = JRequest::getInt('id', '0');
	    $db = & JFactory::getDBO();
		
			$query = "SELECT c.id, IFNULL(fx.ord,0)+1 as ordering, IFNULL(f.active,0) as active FROM #__djcf_categories c "
					."LEFT JOIN (SELECT count(fx.id) as ord, fx.cat_id FROM #__djcf_fields_xref fx GROUP BY fx.cat_id ) fx ON fx.cat_id=c.id "
					."LEFT JOIN (SELECT count(fx.id) as active, fx.cat_id FROM #__djcf_fields_xref fx WHERE fx.field_id=".$id." GROUP BY fx.cat_id ) f ON f.cat_id=c.id ";			
			$db =& JFactory::getDBO();		
			$db->setQuery($query);
			$cats =$db->loadObjectList(); 
			//echo '<pre>';print_r($db);print_r($cats);die();
			$cat_c = 0;
			if(count($cats)){
				$query = "INSERT INTO #__djcf_fields_xref(`cat_id`,`field_id`,`ordering`) VALUES ";
				foreach($cats as $cat){					
					if(!$cat->active){
						$query .= "('".$cat->id."','".$id."','".$cat->ordering."'), ";
						$cat_c++;
					}			
						
				}
				if($cat_c){
					$query = substr($query, 0,-2).';';
					//echo '<pre>';print_r($query);die();
					$db->setQuery($query);
					$db->query();	
				}				
			}						
	    
	    $app->redirect('index.php?option=com_djclassifieds&task=field.edit&id='.$id, JText::_('COM_DJCLASSIFIEDS_FIELD_ASSIGNED_TO_ALL_CATEGORIES'));
	}
	
	function addtosubcategories()
	{
	
		$app = JFactory::getApplication();
		$id = JRequest::getInt('id', '0');
		$cid = JRequest::getInt('cid', '0');
		$db =  JFactory::getDBO();
	
		if($cid){
			$cats = DJClassifiedsCategory::getSubCat($cid);
			$id_list= '';
			foreach($cats as $cat){
				$id_list .= ($id_list) ? ','.$cat->id : $cat->id;
			}
			//echo '<pre>';print_r($cats);die();
			if($id_list){
				$query = "SELECT c.id, IFNULL(fx.ord,0)+1 as ordering, IFNULL(f.active,0) as active FROM #__djcf_categories c "
						."LEFT JOIN (SELECT count(fx.id) as ord, fx.cat_id FROM #__djcf_fields_xref fx GROUP BY fx.cat_id ) fx ON fx.cat_id=c.id "
						."LEFT JOIN (SELECT count(fx.id) as active, fx.cat_id FROM #__djcf_fields_xref fx WHERE fx.field_id=".$id." GROUP BY fx.cat_id ) f ON f.cat_id=c.id "
						."WHERE c.id IN (".$id_list.") ";
				$db =& JFactory::getDBO();
				$db->setQuery($query);
				$cats =$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($cats);die();
				$cat_c = 0;
				if(count($cats)){
					$query = "INSERT INTO #__djcf_fields_xref(`cat_id`,`field_id`,`ordering`) VALUES ";
					foreach($cats as $cat){
						if(!$cat->active){
							$query .= "('".$cat->id."','".$id."','".$cat->ordering."'), ";
							$cat_c++;
						}
			
					}
					if($cat_c){
						$query = substr($query, 0,-2).';';
						//echo '<pre>';print_r($query);die();
						$db->setQuery($query);
						$db->query();
					}
				}
			}
		} 
		$app->redirect('index.php?option=com_djclassifieds&task=field.edit&id='.$id, JText::_('COM_DJCLASSIFIEDS_FIELD_ASSIGNED_TO_SELECTED_CATEGORIES'));
	}	

	function deletefromcategories(){
		
	    $app = JFactory::getApplication();
	    $id = JRequest::getInt('id', '0');
	    $db = & JFactory::getDBO();
			$query = "DELETE FROM #__djcf_fields_xref WHERE field_id=".$id;
			$db->setQuery($query);
			$db->query();								
	    
	    $app->redirect('index.php?option=com_djclassifieds&task=field.edit&id='.$id, JText::_('COM_DJCLASSIFIEDS_DELETED_FROM_ALL_CATEGORIES'));
	}
}

?>