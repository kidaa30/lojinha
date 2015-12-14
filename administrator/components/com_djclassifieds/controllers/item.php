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


class DJClassifiedsControllerItem extends JControllerLegacy {
	
	public function getModel($name = 'Item', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Items', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('save2copy', 'save');
		$this->registerTask('edit', 'add');
    }

	
	public function add(){		
		//$data = JFactory::getApplication();
		$user = JFactory::getUser();
		if(JRequest::getVar('id',0)){
			if (!$user->authorise('core.edit', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_ITEM_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=items' );
				return false;
			}
		}else{
			if (!$user->authorise('core.create', 'com_djclassifieds')) {
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_CREATE_RECORD_NOT_PERMITTED'));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect( 'index.php?option=com_djclassifieds&view=items' );
				return false;
			}
		}
		
		JRequest::setVar('view','item');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=items' );
	}
	public function getCities(){
		 $region_id = JRequest::getVar('r_id', '0', '', 'int');
	     
	     $db = JFactory::getDBO();
	     $query ="SELECT r.name as text, r.id as value "
	     		."FROM #__djcf_regions r WHERE r.parent_id = ".$region_id;			
	     $db->setQuery($query);
		 $cities =$db->loadObjectList();
		 
		 echo '<select name="city" class="inputbox" >';
		 echo '<option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_CITY').'</option>';
		    echo JHtml::_('select.options', $cities, 'value', 'text', '');
		 echo '</select>';
		 die();
	}
	public function getFields(){
		global $mainframe;
		header("Content-type: text/html; charset=utf-8");
	     $cid = JRequest::getVar('cat_id', '0', '', 'int');
		 $id = JRequest::getVar('id', '0', '', 'int');
		// echo $id; 
	     $db = JFactory::getDBO();
	     $query ="SELECT f.*, v.value, v.value_date, fx.ordering FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
				."ON v.field_id=fx.field_id "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 ORDER BY fx.ordering";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		 //echo '<pre>'; print_r($db);print_r($fields_list);die(); 
		 
		 
		 if(count($fields_list)==0){
		 	echo JText::_('COM_DJCLASSIFIEDS_NO_EXTRA_FIELDS_FOR_CAT');die();
		 }else{
		 		//echo '<pre>';	print_r($fields_list);echo '</pre>';		 	
		 	foreach($fields_list as $fl){
		 		if($fl->name=='price' || $fl->name=='contact'){
		 			continue;
		 		}
		 		
				if($fl->type=="inputbox" || $fl->type=="link"){
			 		echo '<div style="margin:2px 0;" >';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<input class="inputbox" type="text" name="'.$fl->name.'" '.$fl->params; 
						if($id>0){
							echo ' value="'.htmlspecialchars($fl->value).'" '; 	
						}else{
							echo ' value="'.htmlspecialchars($fl->default_value).'" ';
						}
						echo ' />';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="textarea"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<textarea name="'.$fl->name.'" '.$fl->params.' />'; 
						if($id>0){
							echo htmlspecialchars($fl->value); 	
						}else{
							echo htmlspecialchars($fl->default_value);
						}
						echo '</textarea>';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="selectlist"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<select name="'.$fl->name.'" '.$fl->params.' >';
							$val = explode(';', $fl->values);
								if($id>0){
									$def_value=$fl->value; 	
								}else{
									$def_value=$fl->default_value;
								}
						//		print_r($fl);die();
							for($i=0;$i<count($val);$i++){
								if($def_value==$val[$i]){
									$sel="selected";
								}else{
									$sel="";
								}
								echo '<option '.$sel.' value="'.$val[$i].'">'.$val[$i].'</option>';
							}
							
						echo '</select>';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="radio"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';						
						$val = explode(';', $fl->values);
						echo '<div class="radiofield_box" style="float:left">';
							for($i=0;$i<count($val);$i++){
								$checked = '';
								if($id>0){
									if($fl->value == $val[$i]){
										$checked = 'CHECKED';
									}									 	
								}else{
									if($fl->default_value == $val[$i]){
										$checked = 'CHECKED';
									}						
								}
								
								echo '<div style="float:left;"><input type="radio" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'" /><span class="radio_label" style="margin:5px 0px 0 10px;">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
						echo '<div style="clear:both"></div>';			
					echo '</div>';	
				}else if($fl->type=="checkbox"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';						
						$val = explode(';', $fl->values);
						echo '<div class="radiofield_box" style="float:left">';
							for($i=0;$i<count($val);$i++){
								$checked = '';
								if($id>0){									
									if(strstr($fl->value,';'.$val[$i].';' )){
										$checked = 'CHECKED';
									}									 	
								}else{
									$def_val = explode(';', $fl->default_value);
									for($d=0;$d<count($def_val);$d++){
										if($def_val[$d] == $val[$i]){
											$checked = 'CHECKED';
										}											
									}
					
								}
								
								echo '<div style="float:left;"><input type="checkbox" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'[]" /><span class="radio_label" style="margin:5px 0px 0 10px;vertical-align:middle;">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
						echo '<div style="clear:both"></div>';			
					echo '</div>';	
				}else if($fl->type=="date"){
			 		echo '<div style="margin:2px 0;" >';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<input class="inputbox djcalendar" type="text" size="10" maxlenght="19" id="'.$fl->name.'" name="'.$fl->name.'" '.$fl->params; 
						if($id>0){
							echo ' value="'.$fl->value_date.'" '; 	
						}else{
							if($fl->default_value=='current_date'){
								echo ' value="'.date("Y-m-d").'" ';
							}else{
								echo ' value="'.$fl->default_value.'" ';	
							}
							
						}
						echo ' />';
						echo ' <img class="calendar" src="templates/bluestork/images/system/calendar.png" alt="calendar" id="'.$fl->name.'button" />';
						
						/*									        
				        echo '<script type="text/javascript">';
				        echo 'var startDate = new Date(2008, 8, 7);
				         Calendar.setup({
				            inputField  : "'.$fl->name.'",
				            ifFormat    : "%Y-%m-%d",                  
				            button      : "'.$fl->name.'button",
				            date      : startDate
				         });';
				        echo '</script>'; */
						/*echo JHTML::calendar('2011-08-30', 'publish_down', 'publish_down', '%Y-%m-%d',
            					array('size'=>'12',
            					'maxlength'=>'10'));*/

					echo '<div style="clear:both"></div></div>';					
				}

		 	}		 				
		 	die();
	 	}	
	}	
	
	
	public function getBuynowOptions(){
		global $mainframe;
		header("Content-type: text/html; charset=utf-8");
		$cid = JRequest::getVar('cat_id', '0', '', 'int');
		$id = JRequest::getVar('id', '0', '', 'int');
		
		if(!$id){die();}
		
		$db = JFactory::getDBO();
		$query ="SELECT f.* FROM #__djcf_fields_values_sale f "
				."WHERE f.item_id =".$id." ORDER BY f.id";
		$db->setQuery($query);
		$fields_list =$db->loadObjectList();
		//echo '<pre>'; print_r($db);print_r($fields_list);die();
			
			
		if(count($fields_list)){
			//echo '<pre>';	print_r($fields_list);echo '</pre>';
			$c_time = time();
			
			foreach($fields_list as $fl){
				echo '<div style="margin:10px 0;" id="bn-field_box'.$fl->id.'-'.$c_time.'">';							
					$options = json_decode($fl->options);
					foreach($options as $opt){
						echo '<div class="bn_field_outer">';
							echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$opt->label.'</span>';
							echo '<input class="inputbox" name="bn-'.$opt->name.'[]" value="'.$opt->value.'" />';
						echo '</div>';	
					}													
				echo '<div class="bn_field_outer bn_quantity">';
					echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.JText::_('COM_DJCLASSIFIEDS_QUANTITY').'</span>';
					echo '<input type="text" value="'.$fl->quantity.'" name="bn-quantity[]" />';
				echo '</div>';
				echo '<span class="button" onclick="deleteBuynowField(\'bn-field_box'.$fl->id.'-'.$c_time.'\')" >'.JText::_('COM_DJCLASSIFIEDS_DELETE').'</span>';
				echo '<div style="clear:both"></div></div>';
			}
			
		}
		die();
	}
	
	
	
	public function getBuynowFields(){
		global $mainframe;
		header("Content-type: text/html; charset=utf-8");
		$cid = JRequest::getVar('cat_id', '0', '', 'int');
		$id = JRequest::getVar('id', '0', '', 'int');
		$type=JRequest::getInt('type',1);
		// echo $id;
		$db = JFactory::getDBO();
		$query ="SELECT f.*, fx.ordering FROM #__djcf_fields f, #__djcf_fields_xref fx "
				."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 AND f.in_buynow ORDER BY f.label";
		$db->setQuery($query);
		$fields_list =$db->loadObjectList();
		//echo '<pre>'; print_r($db);print_r($fields_list);die();
			
			
		if(count($fields_list)){						
			//echo '<pre>';	print_r($fields_list);echo '</pre>';
			$c_time = time();
			echo '<div style="margin:10px 0;" id="bn-field_box'.$type.'-'.$c_time.'">';
				foreach($fields_list as $fl){				 
					echo '<div class="bn_field_outer">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						if($type==1){
							echo '<select name="bn-'.$fl->name.'[]" '.$fl->params.' >';
							if(substr($fl->buynow_values, 0,1)!=';'){
								$fl->buynow_values = ';'.$fl->buynow_values;
							}
							$val = explode(';', $fl->buynow_values);						
							$def_value=$fl->default_value;
							
							for($i=0;$i<count($val);$i++){
								if($def_value==$val[$i]){
									$sel="selected";
								}else{
									$sel="";
								}
								echo '<option '.$sel.' value="'.$val[$i].'">'.$val[$i].'</option>';
							}							
							echo '</select>';	
						}else{
							echo '<input class="inputbox" name="bn-'.$fl->name.'[]" value="" />';
						}			
					echo '</div>';							
				}
				echo '<div class="bn_field_outer bn_quantity">';
					echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.JText::_('COM_DJCLASSIFIEDS_QUANTITY').'</span>';
					echo '<input type="text" value="0" name="bn-quantity[]" />'; 
				echo '</div>';
				echo '<span class="button btn btn-small" onclick="deleteBuynowField(\'bn-field_box'.$type.'-'.$c_time.'\')" >'.JText::_('COM_DJCLASSIFIEDS_DELETE').'</span>';				
			echo '<div style="clear:both"></div></div>';			
		}
		die();
	}	
	
	public function save(){
    	$app 		= JFactory::getApplication();		
		$model 		= $this->getModel('item');
		$row 		= JTable::getInstance('Items', 'DJClassifiedsTable');		
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 		= JFactory::getDBO();		
		$lang 		= JFactory::getLanguage();
		$dispatcher = JDispatcher::getInstance();
		$task 		= JRequest::getVar('task');
		$id 		= JRequest::getInt('id');
		
		
		
    	$row->bind(JRequest::get('post'));
    	
	    	if($task=='save2copy'){
	    		$row->id=0;		
	    	}
    	
		    $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		    $row->intro_desc = JRequest::getVar('intro_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		    $row->contact = nl2br(JRequest::getVar('contact', '', 'post', 'string'));
			if($row->alias){
				$row->alias = DJClassifiedsSEO::getAliasName($row->alias);
			}else{
				$row->alias = DJClassifiedsSEO::getAliasName($row->name);
			}

			$row->image_url = '';
				//$exp_date = explode('-', $_POST['date_expir']);
				//$exp_time = explode(':', $_POST['time_expir']);
			//$row->date_exp = mktime($exp_time[0],$exp_time[1],0,$exp_date[1],$exp_date[2],$exp_date[0]);
			$row->date_exp = $_POST['date_expir'].' '.$_POST['time_expir'].':00';
			
			$is_new=1;
			$old_row = '';
			if($row->id>0){
				$old_date_exp = JRequest::getVar('date_exp_old','');
				if($old_date_exp != $row->date_exp){
					$row->notify = 0;
				}
				$is_new=0;
								
				$query = "SELECT * FROM #__djcf_items WHERE id= ".$row->id." ";
				$db->setQuery($query);
				$old_row = $db->loadObjectList();				
			}
			
			if($row->id==0){
				$row->exp_days = ceil((strtotime($row->date_exp)-time())/(60*60*24));
				$row->date_start = date("Y-m-d H:i:s");
			}
			
			if($row->user_id==0 && $row->id==0){
				$user=JFactory::getUser();
				$row->user_id = $user->id;
				$row->ip_address = $_SERVER['REMOTE_ADDR'];
			}
			
				$row->region_id= end($_POST['regions']);
				if(!$row->region_id){
					$row->region_id =$_POST['regions'][count($_POST['regions'])-2];					
					if(!reset($_POST['regions'])){					
						$row->region_id=0;
					}
				}
		
		
			if($row->id>0){	
				$query = "DELETE FROM #__djcf_fields_values WHERE item_id= ".$row->id." ";
    			$db->setQuery($query);
    			$db->query();
    			
    			$query = "DELETE FROM #__djcf_fields_values_sale WHERE item_id= ".$row->id." ";
    			$db->setQuery($query);
    			$db->query();

				if($row->payed==1){
					$row->pay_type='';
					$query = "UPDATE #__djcf_payments SET status='Completed' WHERE item_id= ".$row->id." AND type=0 ";
	    			$db->setQuery($query);
	    			$db->query();	
				}
			}
	
		$row->promotions='';		
		$query = "SELECT p.* FROM #__djcf_promotions p ORDER BY p.id ";	
		$db->setQuery($query);
		$promotions=$db->loadObjectList();
			foreach($promotions as $prom){
				if(JRequest::getVar($prom->name,'0')){
					$row->promotions .=$prom->name.',';
				}
			}
		if($row->promotions){
			$row->promotions = substr($row->promotions, 0,-1);
		}
		
		if(strstr($row->promotions, 'p_first')){
			$row->special = 1;
		}else{
			$row->special = 0;
		}
		
		if(($row->region_id || $row->address) && (($row->latitude=='0.000000000000000' && $row->longitude=='0.000000000000000') || (!$row->latitude && !$row->longitude))){
			$address= '';
			if($row->region_id){
				$reg_path = DJClassifiedsRegion::getParentPath($row->region_id);
				for($r=count($reg_path)-1;$r>=0;$r--){
					if($reg_path[$r]->country){
						$address = $reg_path[$r]->name; 
					}
					if($reg_path[$r]->city){
						if($address){	$address .= ', ';}					
						$address .= $reg_path[$r]->name;
											 
					}				
				}
			}
			if($address){	$address .= ', ';}
			$address .= $row->address;
			if($row->post_code){
				$address .= ', '.$row->post_code;	
			}
			
			$loc_coord = DJClassifiedsGeocode::getLocation($address);
			if(is_array($loc_coord)){
				$row->latitude = $loc_coord['lat'];
				$row->longitude = $loc_coord['lng'];
			}
		}
		
		
		//echo '<pre>';print_r($_POST);print_r($row);echo '</pre>';die(); 
		
		$dispatcher->trigger('onBeforeDJClassifiedsSaveAdvert', array(&$row,$is_new));
		
		if (!$row->store())
    	{
			echo $row->getError();
        	exit ();	
    	}
    	
    	if($is_new){
    		$query ="UPDATE #__djcf_items SET date_sort=date_start WHERE id=".$row->id." ";
    		$db->setQuery($query);
    		$db->query();
    	}		

    	if($task=='save2copy' && $id){
    		$query = "SELECT * FROM #__djcf_images WHERE item_id=".$id." AND type='item' ";
    		$db->setQuery($query);
    		$base_item_images =$db->loadObjectList('id');
    		if(count($base_item_images)){
    			$query_img = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
    			foreach($base_item_images as $item_img){
    				$path_from_copy = JPATH_ROOT.$item_img->path.$item_img->name;
    				$new_img_name = str_ireplace($id.'_', $row->id.'_',$item_img->name );
    				$path_to_copy = JPATH_ROOT.$item_img->path.$new_img_name;
    				if (JFile::exists($path_from_copy.'.'.$item_img->ext)){
    					JFile::copy($path_from_copy.'.'.$item_img->ext,$path_to_copy.'.'.$item_img->ext);
    				}
    				if (JFile::exists($path_from_copy.'_ths.'.$item_img->ext)){
    					JFile::copy($path_from_copy.'_ths.'.$item_img->ext,$path_to_copy.'_ths.'.$item_img->ext);
    				}
    				if (JFile::exists($path_from_copy.'_thm.'.$item_img->ext)){
    					JFile::copy($path_from_copy.'_thm.'.$item_img->ext,$path_to_copy.'_thm.'.$item_img->ext);
    				}
    				if (JFile::exists($path_from_copy.'_thb.'.$item_img->ext)){
    					JFile::copy($path_from_copy.'_thb.'.$item_img->ext,$path_to_copy.'_thb.'.$item_img->ext);
    				}
    				$query_img .= "('".$row->id."','item','".$new_img_name."','".$item_img->ext."','/components/com_djclassifieds/images/item/','".$db->escape($item_img->caption)."','".$item_img->ordering."'), ";
    			}
    			$query_img = substr($query_img, 0, -2).';';
    			$db->setQuery($query_img);
    			$db->query();
    		}    		
    	}
		
    	
    	$item_images = '';
    	if(!$is_new || $task=='save2copy'){
    		$item_id = $row->id;
    		if($task=='save2copy'){
    			$item_id = $id;
    		}
    		$query = "SELECT * FROM #__djcf_images WHERE item_id=".$item_id." AND type='item' ";
    		$db->setQuery($query);
    		$item_images =$db->loadObjectList('id');
    	}
    		
    	$img_ids = JRequest::getVar('img_id',array(),'post','array');
    	$img_captions = JRequest::getVar('img_caption',array(),'post','array');
    	$img_images = JRequest::getVar('img_image',array(),'post','array');
    	
    	$img_id_to_del='';
    	foreach($item_images as $item_img){
    		$img_to_del = 1;
    		foreach($img_ids as $img_id){
    			if($item_img->id==$img_id){
    				$img_to_del = 0;
    				break;
    			}
    		}
    		if($img_to_del){
    			$path_to_delete = JPATH_ROOT.$item_img->path.$item_img->name;
    			if (JFile::exists($path_to_delete.'.'.$item_img->ext)){
    				JFile::delete($path_to_delete.'.'.$item_img->ext);
    			}
    			if (JFile::exists($path_to_delete.'_ths.'.$item_img->ext)){
    				JFile::delete($path_to_delete.'_ths.'.$item_img->ext);
    			}
    			if (JFile::exists($path_to_delete.'_thm.'.$item_img->ext)){
    				JFile::delete($path_to_delete.'_thm.'.$item_img->ext);
    			}
    			if (JFile::exists($path_to_delete.'_thb.'.$item_img->ext)){
    				JFile::delete($path_to_delete.'_thb.'.$item_img->ext);
    			}
    			$img_id_to_del .= $item_img->id.',';
    		}
    	}
    	if($img_id_to_del){
    		$query = "DELETE FROM #__djcf_images WHERE item_id=".$row->id." AND type='item' AND ID IN (".substr($img_id_to_del, 0, -1).") ";
    		$db->setQuery($query);
    		$db->query();
    	}
    	    	    	
    	$last_id= $row->id;
    	
    	$nw = (int)$par->get('th_width',-1);
    	$nh = (int)$par->get('th_height',-1);
    	$nws = (int)$par->get('smallth_width',-1);
    	$nhs = (int)$par->get('smallth_height',-1);
    	$nwm = (int)$par->get('middleth_width',-1);
    	$nhm = (int)$par->get('middleth_height',-1);
    	$nwb = (int)$par->get('bigth_width',-1);
    	$nhb = (int)$par->get('bigth_height',-1);
    	
    	$img_ord = 1;
    	$img_to_insert = 0;
    	$query_img = "INSERT INTO #__djcf_images(`item_id`,`type`,`name`,`ext`,`path`,`caption`,`ordering`) VALUES ";
    	$new_img_path = JPATH_SITE."/components/com_djclassifieds/images/item/";
    	for($im = 0;$im<count($img_ids);$im++){    		    		
    		if($img_ids[$im]){    			
    			if($item_images[$img_ids[$im]]->ordering!=$img_ord || $item_images[$img_ids[$im]]->caption!=$img_captions[$im]){
    				$query = "UPDATE #__djcf_images SET ordering='".$img_ord."', caption='".$db->escape($img_captions[$im])."' WHERE item_id=".$row->id." AND type='item' AND id=".$img_ids[$im]." ";
    				$db->setQuery($query);
    				$db->query();
    			}
    		}else{    			
    			$new_img_name = explode(';',$img_images[$im]);    			
    			if(is_array($new_img_name)){
    				$new_img_name_u =JPATH_ROOT.'/tmp/djupload/'.$new_img_name[0];
    				if (JFile::exists($new_img_name_u)){
    					if(getimagesize($new_img_name_u)){
    						$new_img_n = $last_id.'_'.str_ireplace(' ', '_',$new_img_name[1]);
    						$new_img_n = $lang->transliterate($new_img_n);
    						$new_img_n = strtolower($new_img_n);
    						$new_img_n = JFile::makeSafe($new_img_n);
    							
    						$new_path_check = $new_img_path.$new_img_n;
    						$nimg= 0;
    						while(JFile::exists($new_path_check)){
    							$nimg++;
    							$new_img_n = $last_id.'_'.$nimg.'_'.str_ireplace(' ', '_',$new_img_name[1]);
    							$new_img_n = $lang->transliterate($new_img_n);
    							$new_img_n = strtolower($new_img_n);
    							$new_img_n = JFile::makeSafe($new_img_n);
    							$new_path_check = $new_img_path.$new_img_n;
    						} 
    							
    						rename($new_img_name_u, $new_img_path.$new_img_n);
    						$name_parts = pathinfo($new_img_n);
    						$img_name = $name_parts['filename'];
    						$img_ext = $name_parts['extension'];
    							DJClassifiedsImage::makeThumb($new_img_path.$new_img_n,$new_img_path.$img_name.'_ths.'.$img_ext, $nws, $nhs);
    							DJClassifiedsImage::makeThumb($new_img_path.$new_img_n,$new_img_path.$img_name.'_thm.'.$img_ext, $nwm, $nhm);
    							DJClassifiedsImage::makeThumb($new_img_path.$new_img_n,$new_img_path.$img_name.'_thb.'.$img_ext, $nwb, $nhb);
    						$query_img .= "('".$row->id."','item','".$img_name."','".$img_ext."','/components/com_djclassifieds/images/item/','".$db->escape($img_captions[$im])."','".$img_ord."'), ";
    						$img_to_insert++;
    						if($par->get('store_org_img','1')==0){
    							JFile::delete($new_img_path.$new_img_n);
    						}
    					}
    				}
    			}
    		}
    		$img_ord++;
    	}
    	if($img_to_insert){
    		$query_img = substr($query_img, 0, -2).';';
    		$db->setQuery($query_img);
    		$db->query();
    	}    	
    	
    	
    	//if($row->cat_id){
    	if($row->cat_id==''){$row->cat_id=0;}
			 $query ="SELECT f.* FROM #__djcf_fields f "
			 		."LEFT JOIN #__djcf_fields_xref fx ON f.id=fx.field_id "
			 		."WHERE (fx.cat_id  = ".$row->cat_id." OR f.source=1) ";
		     $db->setQuery($query);
			 $fields_list =$db->loadObjectList();
			//echo '<pre>'; print_r($db);print_r($fields_list);die();
			
			 $ins=0;
			 if(count($fields_list)>0){
				$query = "INSERT INTO #__djcf_fields_values(`field_id`,`item_id`,`value`,`value_date`) VALUES ";			
				foreach($fields_list as $fl){
					if($fl->type=='checkbox'){
						if(isset($_POST[$fl->name])){
							$field_v = $_POST[$fl->name];
							$f_value=';';
								for($fv=0;$fv<count($field_v);$fv++){
									$f_value .=$field_v[$fv].';'; 
								}

							$query .= "('".$fl->id."','".$row->id."','".$db->escape($f_value)."',''), ";
							$ins++;	
						}
					}else if($fl->type=='date'){
						if(isset($_POST[$fl->name])){							
							$f_var = JRequest::getVar( $fl->name,'','','string' );							
							$query .= "('".$fl->id."','".$row->id."','','".$db->escape($f_var)."'), ";
							$ins++;	
						}
					}else{					
						if(isset($_POST[$fl->name])){							
							$f_var = JRequest::getVar( $fl->name,'','','string',JREQUEST_ALLOWRAW );													
							$query .= "('".$fl->id."','".$row->id."','".$db->escape($f_var)."',''), ";
							$ins++;	
						}
					}
				}
			}
		 //print_r($query);die();
			if($ins){
				$query = substr($query, 0, -2).';';
				$db->setQuery($query);
    			$db->query();	
			}
		//}
			
			$query ="SELECT f.* FROM #__djcf_fields f "
					."LEFT JOIN #__djcf_fields_xref fx ON f.id=fx.field_id "
					."WHERE fx.cat_id  = ".$row->cat_id." AND f.in_buynow=1 ";
			$db->setQuery($query);
			$fields_list =$db->loadObjectList();
			//echo '<pre>'; print_r($_POST);print_r($fields_list);die();
				
			$ins=0;
			if(count($fields_list)>0){
				$query = "INSERT INTO #__djcf_fields_values_sale(`item_id`,`quantity`,`options`) VALUES ";
				$bn_quantity = JRequest::getVar('bn-quantity',array());
				
				foreach($fields_list as &$fl){
					$fl->bn_values = JRequest::getVar('bn-'.$fl->name,array());
				}
				
				$bn_options = array();
				for($q=0;$q<count($bn_quantity);$q++){
					if($bn_quantity[$q]=='' || $bn_quantity[$q]==0){
						continue;
					}
					$bn_option = array();
					$bn_option['quantity'] = $bn_quantity[$q];
					$bn_option['options'] = array();					
					foreach($fields_list as &$fl){
						if($fl->bn_values[$q]){
							$bn_opt = array();
							$bn_opt['id'] = $fl->id;
							$bn_opt['name'] = $fl->name;
							$bn_opt['label'] = $fl->label;
							$bn_opt['value'] = $fl->bn_values[$q];
							$bn_option['options'][]=$bn_opt;
						}
					}
					if(count($bn_option['options'])){
						$bn_options[] = $bn_option;
					}	
				}

				if(count($bn_options)){
					foreach($bn_options as $opt){
						$query .= "('".$row->id."','".$opt['quantity']."','".$db->escape(json_encode($opt['options']))."'), ";
						$ins++;
					}

					if($ins){
						$query = substr($query, 0, -2).';';
						$db->setQuery($query);
						$db->query();
					}
				}				
			}
			
			if($old_row && ($row->user_id || $row->email)){
				if($old_row->published != $row->published){
					DJClassifiedsNotify::notifyUserPublication($row->id,$row->published);
				}				
			}
			
			
			
			
		JPluginHelper::importPlugin('djclassifieds');
		$dispatcher->trigger('onAfterDJClassifiedsSaveAdvert', array($row,$is_new));
			
    	switch($task)
    	{
	        case 'apply':
	        case 'save2copy':
            	$link = 'index.php?option=com_djclassifieds&task=item.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=item.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=items';
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	function deletebid(){
		$app 		= JFactory::getApplication();		
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 		= JFactory::getDBO();		
		$id 		= JRequest::getInt('id',0);
		$bid 		= JRequest::getInt('bid',0);
		
		if($id && $bid){
			$query = "DELETE FROM #__djcf_auctions WHERE id= ".$bid." AND item_id=".$id;
			$db->setQuery($query);
			$db->query();
			
			$query = "SELECT i.* FROM #__djcf_items i "
					."WHERE i.id = ".$id." ";
			$db->setQuery($query);
			$item = $db->loadObject();
			
			$price_start = $item->price_start;
			$query = "SELECT a.* FROM #__djcf_auctions a "
					." WHERE a.item_id=".$item->id." ORDER BY a.date DESC LIMIT 1";
			$db->setQuery($query);
			$last_bid=$db->loadObject();
			
			if($last_bid){
				$price_start = $last_bid->price;
			}
			
			$query="UPDATE #__djcf_items SET price='".$price_start."' "
					." WHERE id=".$item->id;
			$db->setQuery($query);
			$db->query();
			
						
			$msg = JText::_('COM_DJCLASSIFIEDS_BID_DELETED');
		}else{
			$msg = JText::_('COM_DJCLASSIFIEDS_WRONG_BID_ID');
		}
		
		
		$link = 'index.php?option=com_djclassifieds&task=item.edit&id='.$id;
		$app->redirect($link, $msg);
	}
	
	
	function deletebuynow(){
		$app 		= JFactory::getApplication();
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 		= JFactory::getDBO();
		$id 		= JRequest::getInt('id',0);
		$bid 		= JRequest::getInt('bid',0);
	
		if($id && $bid){
			
			$query = "SELECT i.* FROM #__djcf_items i "
					."WHERE i.id = ".$id." ";
			$db->setQuery($query);
			$item = $db->loadObject();
			
			$query = "SELECT b.* FROM #__djcf_orders b "
					." WHERE b.id=".$bid." LIMIT 1";
			$db->setQuery($query);
			$order=$db->loadObject();
			
			$query = "DELETE FROM #__djcf_orders WHERE id= ".$bid." AND item_id=".$id;
			$db->setQuery($query);
			$db->query();
				
			$query="UPDATE #__djcf_items SET quantity=quantity+'".$order->quantity."' "
					." WHERE id=".$item->id;
			$db->setQuery($query);
			$db->query();
				
	
			$msg = JText::_('COM_DJCLASSIFIEDS_ORDER_DELETED');
		}else{
			$msg = JText::_('COM_DJCLASSIFIEDS_WRONG_ORDER_ID');
		}
	
	
		$link = 'index.php?option=com_djclassifieds&task=item.edit&id='.$id;
		$app->redirect($link, $msg);
	}	

	function download(){		
		require_once(JPATH_ROOT.DS.'plugins'.DS.'djclassifieds'.DS.'files'.DS.'helper.php');
		
		$app		= JFactory::getApplication();
		$user		= JFactory::getUser();
		$db			= JFactory::getDbo();
		$file_id = 	$app->input->getInt('fid',0);
		
		
		if (!DJClassifiedsFileHelper::getFile($file_id)){
			throw new Exception('', 404);
			return false;
		}
	
		$app->close();
		//return true;	
	}
	
}

?>