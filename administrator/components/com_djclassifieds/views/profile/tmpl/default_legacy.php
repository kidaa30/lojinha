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
//JHTML::_( 'behavior.Mootools' );
jimport( 'joomla.html.editor' );
JHTML::_('behavior.calendar');
JHTML::_('behavior.modal');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$document= JFactory::getDocument();
$id = JRequest::getVar('id', '0', '', 'int');

?> 
	<form action="index.php?option=com_djclassifieds" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>	
		<div class="width-50 fltlft">	
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCLASSIFIEDS_BASIC_INFORMATIONS'); ?></legend>	
			<table class="admintable">
				<tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_PROFILE_IMAGE');?>
	                </td>
	                <td>                	
	                    <?php
							if(!$this->images){
								echo JText::_('COM_DJCLASSIFIEDS_NO_PROFILE_IMAGE_INCLUDED');
							}else{
								$avatar_img = $this->images[0];
								echo '<img src="'.JURI::root().$avatar_img->path.$avatar_img->name.'_th.'.$avatar_img->ext.'" />';?>
								<input type="checkbox" name="del_avatar" id="del_avatar" value="<?php echo $avatar_img->id;?>"/>
								<input type="hidden" name="del_avatar_id" value="<?php echo $avatar_img->id;?>"/>
								<input type="hidden" name="del_avatar_path" value="<?php echo $avatar_img->path;?>"/>
								<input type="hidden" name="del_avatar_name" value="<?php echo $avatar_img->name;?>"/>
								<input type="hidden" name="del_avatar_ext" value="<?php echo $avatar_img->ext;?>"/>
								<?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); 
							}
						?>
	                </td>
	            </tr>
				<tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');?><br />
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_NEW_IMAGES_OVERWRITE_EXISTING_ONE');?>					
	                </td>
	                <td>
						<input type="file"  name="new_image" />
	                </td>
	            </tr>	
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?></td>
					<td><?php echo $this->jprofile->name; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('JGLOBAL_USERNAME');?></td>
					<td><?php echo $this->jprofile->username; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key"><?php echo JText::_('JGLOBAL_EMAIL');?></td>
					<td><?php echo $this->jprofile->email; ?></td>
				</tr>
			</table>
			</fieldset>
		</div>
		<div class="width-50 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_DJCLASSIFIEDS_PROFILE_CUSTOM_FIELDS'); ?></legend>		
			<table class="admintable">
				<?php foreach($this->profile as $fl){			
					echo '<tr>';
						echo '<td width="100" align="right" class="key">'.$fl->label.'</td>';	
						echo '<td>';
							if($fl->type=="inputbox" || $fl->type=="link"){
								echo '<input class="inputbox" size="50" type="text" name="'.$fl->name.'" '.$fl->params; 
								if($id>0){
									echo ' value="'.htmlspecialchars($fl->value).'" '; 	
								}else{
									echo ' value="'.htmlspecialchars($fl->default_value).'" ';
								}
								echo ' />';					
							}else if($fl->type=="textarea"){
								echo '<textarea name="'.$fl->name.'" '.$fl->params.' />'; 
								if($id>0){
									echo htmlspecialchars($fl->value); 	
								}else{
									echo htmlspecialchars($fl->default_value);
								}
								echo '</textarea>';
					
							}else if($fl->type=="selectlist"){
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
							}else if($fl->type=="radio"){						
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
							}else if($fl->type=="checkbox"){						
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
							
							}else if($fl->type=="date"){
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
							}
						echo '</td>';	
					echo '</tr>';
			 	}
				if(!count($this->profile)){
					echo '<tr><td colspan="2"><br />'.JText::_('COM_DJCLASSIFIEDS_NO_PROFILE_FIELDS_SPECIFIED');
						echo ' <a href="index.php?option=com_djclassifieds&view=fields">'.JText::_('COM_DJCLASSIFIEDS_ADD_CUSTOM_FIELDS').'</a>';
					echo '</td></tr>';
				} ?> 														
			</table>
		</fieldset>
		</div>
		<input type="hidden" name="id" value="<?php echo $id; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		
	</form>
<script language="javascript" type="text/javascript">
	window.addEvent("load", function(){
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
</script>
<?php echo '<div style="clear:both"></div>'.DJCFFOOTER; ?>