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

//jimport('joomla.media.images');
JHTML::_('behavior.framework','More');
JHTML::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHTML::_('behavior.calendar');
$toolTipArray = array('className'=>'djcf_label');
//JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$user = JFactory::getUser();

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';
$document= JFactory::getDocument();

$token = JRequest::getCMD('token', '' );
?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $par->get('theme','default');?>">
	<?php 
		$modules_djcf = &JModuleHelper::getModules('djcf-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}		
	
		$modules_djcf = &JModuleHelper::getModules('djcf-profileedit-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	

	?>	
	
<div class="dj-additem clearfix" >
<form action="index.php" method="post" class="form-validate" name="djForm" id="djForm"  enctype="multipart/form-data">
        <div class="additem_djform profile_edit_djform">        
		    <div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_PROFILE_EDITION');	?></div>
			<div class="additem_djform_in">
			<div class="djform_row">
				<label class="label" for="cat_0" id="cat_0-lbl">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_PROFILE_IMAGE');?>						
	            </label>
	            <div class="djform_field">
                    <?php if(!$this->avatar){
							//echo JText::_('COM_DJCLASSIFIEDS_NO_PROFILE_IMAGE_INCLUDED');
	                    	echo '<img style="width:'.$par->get('profth_width','120').'px" src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/default_profile.png" />';
						}else{
							echo '<img src="'.JURI::root().$this->avatar->path.$this->avatar->name.'_th.'.$this->avatar->ext.'" />';?>
							<input type="checkbox" name="del_avatar" id="del_avatar" value="<?php echo $this->avatar->id;?>"/>
							<input type="hidden" name="del_avatar_id" value="<?php echo $this->avatar->id;?>"/>
							<?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); 
						}?>
                </div>
                <div class="clear_both"></div>
	        </div>
			<div class="djform_row">
				<label class="label" for="cat_0" id="cat_0-lbl">
	            	<?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');?><br />
                    <span><?php echo JText::_('COM_DJCLASSIFIEDS_NEW_IMAGES_OVERWRITE_EXISTING_ONE');?></span>	            					
	            </label>
	            <div class="djform_field">
                    <input type="file"  name="new_avatar" />
                </div>
                <div class="clear_both"></div>
			</div>	 																	
 		 	<?php 

	 		 	foreach($this->custom_fields as $fl){
	 		 		echo '<div class="djform_row">';
	 		 		if($fl->type=="inputbox"){
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value = $fl->value;
	 		 			}else{
	 		 				$fl_value = $fl->default_value;
	 		 			}
	 		 			$fl_value = htmlspecialchars($fl_value);
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			$fl_cl = '';
						if($fl->required){
							$fl_cl = 'inputbox required';
							$req = ' * ';
						}else{
							$fl_cl = 'inputbox';
						}
						
						if($fl->numbers_only){
							$fl_cl .= ' validate-numeric';
						}
						$cl = 'class="'.$fl_cl.'" ';
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req.'</label>';
	 		 			}
	 		 	
	 		 			echo '<div class="djform_field">';
	 		 	
	 		 			echo '<input '.$cl.' type="text" id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params;
	 		 			echo ' value="'.$fl_value.'" ';
	 		 			echo ' />';
	 		 		}else if($fl->type=="textarea"){
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value = $fl->value;
	 		 			}else{
	 		 				$fl_value = $fl->default_value;
	 		 			}
	 		 			$fl_value = htmlspecialchars($fl_value);
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($fl->required){
	 		 				$cl = 'class="inputbox required" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class="inputbox"';
	 		 			}
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
	 		 			}
	 		 			echo '<div class="djform_field">';
	 		 			echo '<textarea '.$cl.' id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params.' />';
	 		 			echo $fl_value;
	 		 			echo '</textarea>';
	 		 		}else if($fl->type=="selectlist"){
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value=$fl->value;
	 		 			}else{
	 		 				$fl_value=$fl->default_value;
	 		 			}
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($fl->required){
	 		 				$cl = 'class="inputbox required" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class="inputbox"';
	 		 			}
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
	 		 			}
	 		 			echo '<div class="djform_field">';
	 		 			echo '<select '.$cl.' id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params.' >';
	 		 			if(substr($fl->values, -1)==';'){
	 		 				$fl->values = substr($fl->values, 0,-1);
	 		 			}
	 		 			$val = explode(';', $fl->values);
	 		 			for($i=0;$i<count($val);$i++){
	 		 				if($fl_value==$val[$i]){
	 		 					$sel="selected";
	 		 				}else{
	 		 					$sel="";
	 		 				}
	 		 				echo '<option '.$sel.' value="'.$val[$i].'">'.$val[$i].'</option>';
	 		 			}
	 		 	
	 		 			echo '</select>';
	 		 		}else if($fl->type=="radio"){
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value=$fl->value;
	 		 			}else{
	 		 				$fl_value=$fl->default_value;
	 		 			}
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($fl->required){
	 		 				$cl = 'class="required validate-radio" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class=""';
	 		 			}
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
	 		 			}
	 		 			echo '<div class="djform_field">';
	 		 			if(substr($fl->values, -1)==';'){
	 		 				$fl->values = substr($fl->values, 0,-1);
	 		 			}
	 		 			$val = explode(';', $fl->values);
	 		 			echo '<div class="radiofield_box" style="float:left">';
	 		 			for($i=0;$i<count($val);$i++){
	 		 				$checked = '';
	 		 				if($fl_value == $val[$i]){
	 		 					$checked = 'CHECKED';
	 		 				}
	 		 	
	 		 				echo '<div style="float:left;"><input type="radio" '.$cl.'  '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'" /><span class="radio_label">'.$val[$i].'</span></div>';
	 		 				echo '<div class="clear_both"></div>';
	 		 			}
	 		 			echo '</div>';
	 		 		}else if($fl->type=="checkbox"){
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value = $fl->value;
	 		 			}else{
	 		 				$fl_value = $fl->default_value;
	 		 			}
	 		 	
	 		 			if($fl->required){
	 		 				$cl = 'class="checkboxes required" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class=""';
	 		 			}
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
	 		 			}
	 		 			echo '<div class="djform_field">';
	 		 			if(substr($fl->values, -1)==';'){
	 		 				$fl->values = substr($fl->values, 0,-1);
	 		 			}
	 		 			$val = explode(';', $fl->values);
	 		 			echo '<div class="radiofield_box" style="float:left">';
	 		 			echo '<fieldset id="dj'.$fl->name.'" '.$cl.' >';
	 		 			for($i=0;$i<count($val);$i++){
	 		 				$checked = '';
	 		 				if($this->custom_values_c>0){
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
	 		 	
	 		 				echo '<div style="float:left;"><input type="checkbox" id="dj'.$fl->name.$i.'" class="checkbox" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'[]" /><span class="radio_label">'.$val[$i].'</span></div>';
	 		 				echo '<div class="clear_both"></div>';
	 		 			}
	 		 			echo '</fieldset>';
	 		 			echo '</div>';
	 		 		}else if($fl->type=="date"){
	 		 	
	 		 	
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value = $fl->value_date;
	 		 			}else{
	 		 				if($fl->default_value=='current_date'){
	 		 					$fl_value = date("Y-m-d");
	 		 				}else{
	 		 					$fl_value = $fl->default_value;
	 		 				}
	 		 			}
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($fl->required){
	 		 				$cl = 'class="inputbox required djcalendar" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class="inputbox djcalendar"';
	 		 			}
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req.'</label>';
	 		 			}
	 		 	
	 		 			echo '<div class="djform_field">';
	 		 	
	 		 			echo '<input '.$cl.' type="text" size="10" maxlenght="19" id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params;
	 		 			echo ' value="'.$fl_value.'" ';
	 		 			echo ' />';
	 		 			echo ' <img class="calendar" src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/calendar.png" alt="calendar" id="dj'.$fl->name.'button" />';
	 		 	
	 		 	
	 		 		}else if($fl->type=="link"){
	 		 			if($this->custom_values_c>0){
	 		 				$fl_value = $fl->value;
	 		 			}else{
	 		 				$fl_value = $fl->default_value;
	 		 			}
	 		 			$fl_value = htmlspecialchars($fl_value);
	 		 	
	 		 			$val_class='';
	 		 			$req = '';
	 		 			if($fl->required){
	 		 				$cl = 'class="inputbox required" ';
	 		 				$req = ' * ';
	 		 			}else{
	 		 				$cl = 'class="inputbox"';
	 		 			}
	 		 	
	 		 			if($par->get('show_tooltips_newad','0') && $fl->description){
	 		 				echo '<label class="label Tips1" for="dj'.$fl->name.'" title="'.$fl->description.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req;
	 		 				echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/tip.png" alt="?" />';
	 		 				echo '</label>';
	 		 			}else{
	 		 				echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req.'</label>';
	 		 			}
	 		 	
	 		 			echo '<div class="djform_field">';
	 		 	
	 		 			echo '<input '.$cl.' type="text" id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params;
	 		 			echo ' value="'.$fl_value.'" ';
	 		 			echo ' />';
	 		 		}
	 		 	
	 		 		echo '</div><div class="clear_both"></div>';
	 		 		echo '</div>';
	 		 	}
 		 	
 		 	?>
 		 	</div>
 		 </div>        
		<label id="verification_alert"  style="display:none;color:red;" />
			<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ALL_REQUIRED_FIELDS'); ?>
		</label>
     <div class="classifieds_buttons">
     	<?php
	     	$cancel_link = JRoute::_('index.php?option=com_djclassifieds&view=useritems&Itemid='.JRequest::getVar('Itemid','0'));	        
	     ?>
	     <a class="button" href="<?php echo $cancel_link;?>"><?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL')?></a>
	     <button class="button validate" type="submit" id="submit_button"  ><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE'); ?></button>	     
		 <input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<input type="hidden" name="view" value="profileedit" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="0" />
	</div>
</form>
</div>
</div>
<script type="text/javascript">	
	window.addEvent('domready', function(){
		var JTooltips = new Tips($$('.Tips1'), {	
	      showDelay: 200, hideDelay: 200, className: 'djcf_label', fixed: true
	   	});						 	
	   	var djcals = document.getElements('.djcalendar');
		if(djcals){
			var startDate = new Date(2008, 8, 7);
			djcals.each(function(djcla,index){
				Calendar.setup({
		            inputField  : djcla.id,
		            ifFormat    : "%Y-%m-%d",                  
		            button      : djcla.id+"button",
		            date      : startDate
		         });
			});
		}
	}) 
	     
   document.id('submit_button').addEvent('click', function(){        
      if(document.getElements('#djForm .invalid').length>0){
      	document.id('verification_alert').setStyle('display','block');
      	(function() {
		    document.id('verification_alert').setStyle('display','none');
		  }).delay(3000);      	
      	  return false;
      }else{
      	  return true;
      }             
	});
</script>