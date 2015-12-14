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

$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
$editor 	= JFactory::getEditor();
$document	= JFactory::getDocument();
$dispatcher	= JDispatcher::getInstance();

if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->item->latitude!='0.000000000000000' && $this->item->longitude!='0.000000000000000'){
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false");		
}
if($this->item->id>0){
	$exp_date_time = explode(' ', $this->item->date_exp);
	//print_r($e_date);die(); 
	$date_exp = $exp_date_time[0];
	$time_exp = substr($exp_date_time[1],0,-3);
	 
}else{
	$exp_days = (int)$par->get('exp_days');
	$time_exp = date("H:i");
	$date_exp = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+$exp_days, date("Y")));
}	


?> 
		<form action="index.php?option=com_djclassifieds" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>		
			<div class="row-fluid">
				<div class="span12 form-horizontal">
					<fieldset class="adminform">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#details" data-toggle="tab"><?php echo empty($this->category->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
							</li>
							<li>
								<a href="#category_custom_fields" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY_AND_CUSTOM_FIELDS');?></a>
							</li>
							<li>
								<a href="#location" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_LOCATION');?></a>
							</li>
							<li>
								<a href="#publishing" data-toggle="tab"><?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING');?></a>
							</li>
							<li>
								<a href="#promotions" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS');?></a>
							</li>
							<li>
								<a href="#images" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES'); ?></a>
							</li>
							<li>
								<a href="#auctions" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_AUCTIONS'); ?></a>
							</li>
							<li>
								<a href="#buynow" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW'); ?></a>
							</li>
							<?php
								$tab_titles = $dispatcher->trigger('onAdminItemEditTabTitle', array ($this->item));
								if(count($tab_titles)){
									foreach($tab_titles as $tab_title){
										echo $tab_title;
									}
								}
							?>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="details">
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo htmlentities($this->item->name, ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ALIAS');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="alias" id="alias" size="50" maxlength="250" value="<?php echo $this->item->alias; ?>" />
									</div>
								</div>															
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_TYPE');?></div>
									<div class="controls">
										<select autocomplete="off" name="type_id" class="inputbox" >
											<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_TYPE');?></option>
											<?php echo JHtml::_('select.options', DJClassifiedsType::getTypesSelect(), 'value', 'text', $this->item->type_id, true);?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="price" id="price" size="50" maxlength="250" value="<?php echo $this->item->price; ?>" />
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_NEGOTIABLE');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="price_negotiable" value="1" <?php  if($this->item->price_negotiable==1 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="price_negotiable" value="0" <?php  if($this->item->price_negotiable==0 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CURRENCY');?></div>
									<div class="controls">
										 <?php 
					                	 if($par->get('unit_price_list','')){
					                     	$c_list = explode(';', $par->get('unit_price_list',''));
											 echo '<select name="currency">';
											 for($cl=0;$cl<count($c_list);$cl++){
											 	if($c_list[$cl]==$this->item->currency){
											 		$csel=' SELECTED ';
											 	}else{
											 		$csel='';
												}
											 	echo '<option '.$csel.' name="'.$c_list[$cl].' ">'.$c_list[$cl].'</option>';
											 }
											 echo '</select>';
					                     	
					                     }else{
					                	?>
					                    <input class="text_area" type="text" name="currency" id="currency" size="50" maxlength="250" value="<?php echo $this->item->currency; ?>" />
					                    <?php } ?>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_WEBSITE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="website" id="website" size="50" maxlength="250" value="<?php echo $this->item->website; ?>" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_VIDEO');?>
										<br /><span style="color:#666;font-size:11px;"><?php echo JText::_('COM_DJCLASSIFIEDS_LINK_TO_YOUTUBE_OR_VIMEO');?></span>
									</div>
									<div class="controls">
										<input class="text_area" type="text" name="video" id="video" size="50" maxlength="250" value="<?php echo $this->item->video; ?>" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT');?></div>
									<div class="controls">
										<textarea id="contact" name="contact" rows="4" cols="55" class="inputbox" ><?php echo $this->item->contact; ?></textarea>
									</div>
								</div>
								<?php foreach($this->custom_contact as $fl){			
									echo '<div class="control-group" >';
										echo '<div class="control-label">'.$fl->label.'</div>';	
										echo '<div class="controls">';
											if($fl->type=="inputbox" || $fl->type=="link"){
												echo '<input class="inputbox" type="text" name="'.$fl->name.'" '.$fl->params; 
												if($this->item->id>0){
													echo ' value="'.htmlspecialchars($fl->value).'" '; 	
												}else{
													echo ' value="'.htmlspecialchars($fl->default_value).'" ';
												}
												echo ' />';					
											}else if($fl->type=="textarea"){
												echo '<textarea name="'.$fl->name.'" '.$fl->params.' />'; 
												if($this->item->id>0){
													echo htmlspecialchars($fl->value); 	
												}else{
													echo htmlspecialchars($fl->default_value);
												}
												echo '</textarea>';
									
											}else if($fl->type=="selectlist"){
												echo '<select name="'.$fl->name.'" '.$fl->params.' >';
													$val = explode(';', $fl->values);
														if($this->item->id>0){
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
														if($this->item->id>0){
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
														if($this->item->id>0){									
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
												if($this->item->id>0){
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
										echo '</div>';	
									echo '</div>';
							 	} ?>								
								
								
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION');?>
										<div id="ile">(<?php echo $par->get('introdesc_char_limit')-strlen($this->item->intro_desc);?>)</div>
									</div>
									<div class="controls">
										<textarea id="intro_desc" name="intro_desc" rows="5" cols="55" class="inputbox" onkeyup="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);" onkeydown="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);"><?php echo $this->item->intro_desc; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?></div>
									<div class="controls">
										<?php echo $editor->display( 'description', $this->item->description, '100%', '350', '50', '20',true ); ?>
									</div>
								</div>																																				
							</div>
							
							<div class="tab-pane" id="category_custom_fields">
								<div class="control-group">
									<div class="control-group">
										<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');?></div>
										<div class="controls">
											<select autocomplete="off" name="cat_id" class="inputbox" onchange="getFields(this.value)" >
												<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
												<?php echo JHtml::_('select.options', DJClassifiedsCategory::getCatSelect(), 'value', 'text', $this->item->cat_id, true);?>
											</select>
										</div>
									</div>	
									<div class="control-group">
										<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_EXTRA_FIELDS');?></div>
										<div class="controls">
											<div id="ex_fields"><?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?></div>
										</div>
									</div>										
								</div>
							</div>
							
							<div class="tab-pane" id="location">
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');?></div>
									<div class="controls">
				                    	<?php
										$reg_sel = '<select id="reg_0" style="width:210px" name="regions[]" onchange="new_reg(0,this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
										$parent_id=0;	
										foreach($this->regions as $l){
											if($parent_id!=$l->parent_id){
												break;
											}	
											$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';							
											//$ri++;
										}
											$reg_sel .= '</select>';
											echo $reg_sel;
										
										?><div style="clear:both"></div>
										<div id="after_reg_0"></div>
										<script type="text/javascript">
											var regs=new Array();
											
										<?php
										$reg_sel = '<select style="width:210px" name="regions[]" id="reg_0" onchange="new_reg(0,this.value);">';
										$parent_id=0;	
										
										foreach($this->regions as $l){
											if($parent_id!=$l->parent_id){
												$reg_sel .= '</select>';
												echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";
												$parent_id=$l->parent_id;
												$reg_sel = '<div style="clear:both"></div><select style="width:210px" name="regions[]" id="reg_'.$l->parent_id.'" onchange="new_reg('.$parent_id.',this.value);">';
												$reg_sel .= '<option value=""> - - - </option>';		
											}	
											$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
										}
										$reg_sel .= '</select>';	
										echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";
										
										?>	
										var current=0;
										
										function new_reg(parent,a_parent){
											if(regs[a_parent]){
												//alert(cats[v]);	
												$('after_reg_'+parent).innerHTML = regs[a_parent]; 
												$('reg_'+parent).value=a_parent;
											}else{
												$('after_reg_'+parent).innerHTML = '';
												$('reg_'+parent).value=a_parent;		
											}
											
										}
										<?php echo $this->reg_path;?>
										</script>										
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ADDRESS');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="address" id="address" size="50" maxlength="250" value="<?php echo $this->item->address; ?>" />
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_POSTCODE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="post_code" id="post_code" size="50" maxlength="250" value="<?php echo $this->item->post_code; ?>" />
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"></div>
									<div class="controls">
										<span style="color:#666"><?php echo JText::_('COM_DJCLASSIFIEDS_LAT_LONG_LEAVE_BLANK_TO_GENERATE');?></span>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_LATITUDE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="latitude" id="latitude" size="50" maxlength="250" value="<?php echo $this->item->latitude; ?>" />
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_LONGITUDE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="longitude" id="longitude" size="50" maxlength="250" value="<?php echo $this->item->longitude; ?>" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"></div>
									<div class="controls">
										<?php 
										if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->item->latitude!='0.000000000000000' && $this->item->longitude!='0.000000000000000'){ ?>
											<fieldset class="adminform">
												<legend><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?></legend>
													<div id="google_map_box" style="display:none;">
														 <div id='map' style='width: 470px; height: 400px; border: 1px solid #666;'>						  
														 </div>      
													</div>
											</fieldset>	
										<?php }
										?>
									</div>
								</div>
							</div>
																												
							<div class="tab-pane" id="publishing">
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->item->published==1 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->item->published==0 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>										
									</div>
								</div>		
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PAID');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="payed" value="1" <?php  if($this->item->payed==1 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="payed" value="0" <?php  if($this->item->payed==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>										
									</div>
								</div>
								<?php if($this->payment){ ?>
									<div class="control-group">
										<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_TYPE');?></div>
										<div class="controls">
											<?php echo $this->payment->method; ?>										
										</div>
									</div>
									<div class="control-group">
										<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_STATUS');?></div>
										<div class="controls">
											<?php echo $this->payment->status; ?> 											
										</div>
									</div>				
								<?php } ?>														
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_DATE');?></div>
									<div class="controls">
										<input class="inputbox" type="text" name="date_expir" id="date_expir" size="10" maxlenght="19" value = "<?php echo $date_exp;?>"/>
								        <img class="calendar" src="components/com_djclassifieds/assets/images/calendar.png" alt="calendar" id="showArrivalCalendar" />
								        <script type="text/javascript">
								         var startDate = new Date(2008, 8, 7);
								         Calendar.setup({
								            inputField  : "date_expir",
								            ifFormat    : "%Y-%m-%d",                  
								            button      : "showArrivalCalendar",
								            date      : startDate
								         });
								        </script>
								        <input class="inputbox" type="hidden" name="date_exp_old" size="10" maxlenght="19" value = "<?php echo $date_exp.' '.$time_exp.':00'; ?>"/>								
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_TIME');?>
										<br /><span style="color:#666">(<?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_TIME_FORMAT');?>)</span>
									</div>
									<div class="controls">
	                					<input type="text" name="time_expir" value="<?php echo $time_exp; ?>" size="10" />										
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CREATED_BY');?></div>
									<div class="controls">
										<?php echo $this->selusers; ?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS_VIEWING');?></div>
									<div class="controls">
										<select autocomplete="off" name="access_view" class="inputbox" >
											<option value="0"><?php echo JText::_('COM_DJCLASSIFIEDS_DEFAULT_FROM_CATEGORY');?></option>
											<?php echo JHtml::_('select.options', $this->view_levels, 'id', 'title', $this->item->access_view, true);?>
										</select>  
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_IP_ADDRESS');?></div>
									<div class="controls">
										<?php 
										if($this->item->ip_address){
											echo $this->item->ip_address;	
										}else{
											echo '---';
										}							 
										?>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS');?></div>
									<div class="controls">
					                	<?php 
					                	$c_abuse = $this->abuse;						
										if($c_abuse>0 && $this->item->id>0){
											echo '<a href="index.php?option=com_djclassifieds&view=abuse&id='.$this->item->id.'&tmpl=component" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">';								
											echo $c_abuse.' '.JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS').'</a>';
										}else{
											echo $c_abuse.' '.JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS');	
										}
					                	?>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ADDED');?></div>
									<div class="controls">
										<?php echo $this->item->date_start; ?>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_DURATION');?></div>
									<div class="controls">
					                	<select autocomplete="off" name="exp_days" class="inputbox" >
											<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_DAYS');?></option>
											<?php echo JHtml::_('select.options', $this->durations, 'days', 'days', $this->item->exp_days, true);?>
										</select>
									</div>
								</div>	
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_METAKEY');?></div>
									<div class="controls">
										<textarea id="metakey" name="metakey" rows="5" cols="55" class="inputbox"><?php echo $this->item->metakey; ?></textarea>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_METADESC');?></div>
									<div class="controls">
            	    					<textarea id="metadesc" name="metadesc" rows="5" cols="55" class="inputbox"><?php echo $this->item->metadesc; ?></textarea>										
									</div>
								</div>		
							</div>
														
							<div class="tab-pane" id="promotions">								
								<?php foreach($this->promotions as $prom){ ?>
									<div class="control-group">
										<div class="control-label"><?php echo JText::_($prom->label);?></div>
										<div class="controls">
											<input autocomplete="off" type="radio" name="<?php echo $prom->name;?>" value="1" <?php  if(strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
											<input autocomplete="off" type="radio" name="<?php echo $prom->name;?>" value="0" <?php  if(!strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>											
										</div>
									</div>
								<?php }?>	
							</div>
							
							<div class="tab-pane" id="images">
							<div class="control-group">
								<div id="itemImagesWrap">
									<div id="itemImages">
										<?php  if($this->images){ 
										foreach($this->images as $img) { ?>
											<div class="itemImage">
												<img src="<?php echo JURI::root().$img->path.$img->name.'_thb.'.$img->ext; ?>" alt="<?php echo $this->escape($img->caption); ?>" />
												<div class="imgMask">
													<input type="hidden" name="img_id[]" value="<?php echo $this->escape($img->id); ?>">
													<input type="hidden" name="img_image[]" value="">
													<input type="text" class="itemInput editTitle" name="img_caption[]" value="<?php echo $this->escape($img->caption); ?>">
													
													<span class="delBtn"></span>
												</div>
											</div>
										<?php }}  ?>
									</div>
									<div class="clearfix"></div>
								</div>
								<?php echo $this->uploader;?>																														
							</div>
								<div class="control-group">
									<div class="control-group">
										<div>
											<?php echo JText::_('COM_DJCLASSIFIEDS_FIRST_IMAGES_IS_MAIN_IMAGE'); ?>
										</div>
									</div>	
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES_INCLUDED');?></div>
									<div class="controls">
					                	<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->item->image_url ?>" />
					                    <?php
												$images_count = 0;
												$images = array();
												if(!$image = $this->item->image_url){
													echo JText::_('COM_DJCLASSIFIEDS_NO_IMAGES_INCLUDED');
												}else{
													$images=explode(';', substr($image,0,-1));
														$path = str_replace('/administrator','',JURI::base());
														$path .= '/components/com_djclassifieds/images/';
													for($i=0; $i<count($images); $i++){
																  ?>
																  <img src="<?php echo $path.$images[$i];?>.ths.jpg" alt="" />
																  <input type="checkbox" name="del_image[]" id="del_image[]" value="<?php echo $images[$i];?>"/>
																  <?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); ?>
																  <div style="clear:both"></div>
														<?php
													}
												}
											?>										
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');?></div>
									<div class="controls">
	                    				<?php $image_urls = ""?>
										<div id="uploader">
											<input type="file"  name="image[]" />						
										</div><div style="clear:both" ></div>
										<a href="#" onclick="addImage(); return false;" ><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMG_LINK')?></a>										
									</div>
								</div>				
							</div>
							
							<div class="tab-pane" id="auctions">								
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_AUCTION');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="auction" value="1" <?php  if($this->item->auction==1 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="auction" value="0" <?php  if($this->item->auction==0 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>										
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_MIN_BID_INCREASE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="bid_min" id="bid_min" size="50" maxlength="250" value="<?php echo $this->item->bid_min; ?>" />
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_MAX_BID_INCREASE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="bid_max" id="bid_max" size="50" maxlength="250" value="<?php echo $this->item->bid_max; ?>" />
									</div>
								</div>		
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_RESERVE_PRICE');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="price_reserve" id="price_reserve" size="50" maxlength="250" value="<?php echo $this->item->price_reserve; ?>" />
									</div>
								</div>
								<?php /*
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_BID_AUTOCLOSE');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="bid_autoclose" value="1" <?php  if($this->item->bid_autoclose==1 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="bid_autoclose" value="0" <?php  if($this->item->bid_autoclose==0 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>										
									</div>
								</div>	
								*/ ?>																
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CURRENT_BIDS');?></div>
									<div class="controls">										
										<div class="bids_list">
											<?php if($this->bids){ ?>
												<div class="bids_row bids_row_title">
													<div class="bids_col bids_col_name"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>:</div>
													<div class="bids_col bids_col_date"><?php echo JText::_('COM_DJCLASSIFIEDS_DATE'); ?>:</div>
													<div class="bids_col bids_col_bid"><?php echo JText::_('COM_DJCLASSIFIEDS_BID'); ?>:</div>
													<div class="bids_col bids_col_del"><?php echo JText::_('COM_DJCLASSIFIEDS_DELETE'); ?>:</div>
													<div style="clear:both"></div>
												</div>
												<?php foreach($this->bids as $bid){ 
													?> 
													<div class="bids_row">
														<div class="bids_col bids_col_name"><?php echo $bid->u_name; ?></div>
														<div class="bids_col bids_col_date"><?php echo $bid->date; ?></div>
														<div class="bids_col bids_col_bid"><?php echo $bid->price.' '.$this->item->currency;?></div>
														<div class="bids_col bids_col_del">
															<a class="button delete" href="javascript:void(0)" onclick="confirm_delete_bid('<?php echo $this->item->id."','".$bid->id;?>')" >															
																<span title="<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE'); ?>" class="icon-delete">&nbsp;</span>
															</a>																												
														</div> 
														<div style="clear:both"></div>
													</div>		
												<?php 													
												}?>			
											<?php }else{ ?>
												<div class="bids_row no_bids_row"><?php echo JText::_('COM_DJCLASSIFIEDS_NO_SUBMITTED_BIDS'); ?></div>	
											<?php }?>
											<div style="clear:both"></div>
										</div>
									</div>
								</div>																									
							</div>

							
							
							<div class="tab-pane" id="buynow">								
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW');?></div>
									<div class="controls">
										<input autocomplete="off" type="radio" name="buynow" value="1" <?php  if($this->item->buynow==1 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
										<input autocomplete="off" type="radio" name="buynow" value="0" <?php  if($this->item->buynow==0 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:2px 15px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>										
									</div>
								</div>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_QUANTITY');?></div>
									<div class="controls">
										<input class="text_area" type="text" name="quantity" id="quantity" size="50" maxlength="250" value="<?php echo $this->item->quantity; ?>" />
										<select name="unit_id">					                	
											<?php echo JHtml::_('select.options', $this->units, 'id', 'name', $this->item->unit_id, true);?>										
										</select>
										<div id="buynow_options"></div>
										<div id="buynow_options1"></div>
										<div id="buynow_new_options1" style="display:none"><span class="button btn btn-small" onclick="getBuynowFields(1)"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEW_ITEM') ?> </span></div>
										<div id="buynow_options2"></div>	
										<div id="buynow_new_options2" style="display:none"><span class="button btn btn-small" onclick="getBuynowFields(2)"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEW_ITEM') ?> </span></div>
									</div>
								</div>
																								
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_CURRENT_BIDS');?></div>
									<div class="controls">										
										<div class="bids_list">
											<?php if($this->buynow){ ?>
												<div class="bids_row bids_row_title">
													<div class="bids_col bids_col_name"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>:</div>
													<div class="bids_col bids_col_date"><?php echo JText::_('COM_DJCLASSIFIEDS_DATE'); ?>:</div>
													<div class="bids_col bids_col_bid"><?php echo JText::_('COM_DJCLASSIFIEDS_QUANTITY'); ?>:</div>
													<div class="bids_col bids_col_del"><?php echo JText::_('COM_DJCLASSIFIEDS_DELETE'); ?>:</div>
													<div style="clear:both"></div>
												</div>
												<?php foreach($this->buynow as $bid){ 
													?> 
													<div class="bids_row">
														<div class="bids_col bids_col_name"><?php echo $bid->u_name; ?></div>
														<div class="bids_col bids_col_date"><?php echo $bid->date; ?></div>
														<div class="bids_col bids_col_bid"><?php echo $bid->quantity;?></div>
														<div class="bids_col bids_col_del">
															<a class="button delete" href="javascript:void(0)" onclick="confirm_delete_buynow('<?php echo $this->item->id."','".$bid->id;?>')" >															
																<span title="<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE'); ?>" class="icon-delete">&nbsp;</span>
															</a>																												
														</div> 
														<div style="clear:both"></div>
														<?php if(isset($bid->extra_info)){ ?>
															<div class="bids_extra_info">
																<?php echo $bid->extra_info; ?>
															</div>	
														<?php } ?>
													</div>		
												<?php 													
												}?>			
											<?php }else{ ?>
												<div class="bids_row no_bids_row"><?php echo JText::_('COM_DJCLASSIFIEDS_NO_SUBMITTED_BIDS'); ?></div>	
											<?php }?>
											<div style="clear:both"></div>
										</div>
									</div>
								</div>																									
							</div>
							<?php
								$tab_contents = $dispatcher->trigger('onAdminItemEditTabContent', array ($this->item));
								if(count($tab_contents)){
									foreach($tab_contents as $tab_content){
										echo $tab_content;
									}
								}
							?>
														
						</div>
					</fieldset>
				</div>
			</div>	
			<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
			<input type="hidden" name="ordering" value="<?php echo $this->item->ordering; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
<script language="javascript" type="text/javascript">

	function submitbutton(pressbutton) {
	alert('a');
		var form = document.adminForm;
		if (pressbutton == 'cancelItem') {
			submitform( pressbutton );
			return;
		}
		
        var wal = 0;
		if (form.name.value == ""){
			alert( "<?php echo JText::_( 'Item must have name', true ); ?>" );
			wal=1;
		}		

		if(wal==0){
			submitform( pressbutton );
		}
	}
	function check(){
	if(document.adminForm.price.value.search(/^[0-9]+(\,{1}[0-9]{2})?$/i)){
				document.adminForm.price.style.backgroundColor='#F00000';
				$('price_alert').innerHTML = "<?php echo JText::_('ALERT_PRICE')?>";
				$('price_alert').setStyle('background','#f00000');
				$('price_alert').setStyle('color','#ffffff');
				$('price_alert').setStyle('font-weight','bold');
			}
			else{
				document.adminForm.price.style.backgroundColor='';
				$('price_alert').innerHTML = '';
				$('price_alert').setStyle('background','none');
			}
	}
		
		
	function addImage(){
		var inputdiv = document.createElement('input');
		inputdiv.setAttribute('name','image[]');
		inputdiv.setAttribute('type','file');
		
		var div = document.createElement('div');
		div.setAttribute('style','clear:both');
	
		var ni = $('uploader');
		
		ni.appendChild(document.createElement('br'));
		ni.appendChild(div);
		ni.appendChild(inputdiv);
		
	}


	
	function checkt(my_form,limit){
		if(my_form.intro_desc.value.length<=limit)
		{
			a=my_form.intro_desc.value.length;
			b=limit;
			c=b-a;
			document.getElementById('ile').innerHTML= '('+c+')';
		}
		else
		{
			my_form.intro_desc.value = my_form.intro_desc.value.substring(0, limit);
		}
	}


	var category_id = 0;
	
	function getFields(cat_id){
		var el = document.getElementById("ex_fields");
		var before = document.getElementById("ex_fields").innerHTML.trim();
		category_id = cat_id; 
		if(cat_id>0){
			el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&task=getFields&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
						  var myRequest = new Request({
				    url: 'index.php',
				    method: 'post',				    
				    evalResponse: false,					
					data: {
				      'option': 'com_djclassifieds',
				      'task': 'item.getFields',
					  'cat_id': cat_id,					  
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){	
				    														
						el.innerHTML = responseText;		
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
								
						
				         		 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest.send();
				
				<?php if($this->item->id){ ?>
					getBuynowOptions();
				<?php } ?>
				document.getElementById("buynow_options1").innerHTML = '';
				document.getElementById("buynow_new_options1").setStyle('display','none');
				getBuynowFields(1);
				document.getElementById("buynow_options2").innerHTML = '';
				document.getElementById("buynow_new_options2").setStyle('display','none');				
				getBuynowFields(2);
			/*var reque = new Ajax(url, {
				method: 'post',
				onComplete: function(request){
					//alert(request);
					el.innerHTML = request; 			
				}
			}).request();*/	

		}else{
			el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
		}
		
	}

	function getBuynowOptions(){

		var bn_el = document.id("buynow_options");		
		var cat_id = category_id; 
		if(cat_id){
			//bn_el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&task=getBuynowOptions&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest2 = new Request({
				    url: 'index.php',
				    method: 'post',				    
				    evalResponse: false,					
					data: {
				      'option': 'com_djclassifieds',
				      'task': 'item.getBuynowOptions',
					  'cat_id': cat_id,	
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){				    	
				    	bn_el.innerHTML = responseText; 		         		 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest2.send();
		}else{
			//bn_el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
		}
	}

	function getBuynowFields(type){

		var bn_el = document.id("buynow_options"+type);		
		var cat_id = category_id; 
		if(cat_id){
			//bn_el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&task=getBuynowFields&cat_id=' + cat_id + '&type=' + type<?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest2 = new Request({
				    url: 'index.php',
				    method: 'post',				    
				    evalResponse: false,					
					data: {
				      'option': 'com_djclassifieds',
				      'task': 'item.getBuynowFields',
					  'cat_id': cat_id,					  
					  'type': type,
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){				    	
				    	if(responseText){
				    		var respElement = new Element('div');
				    		respElement.innerHTML = responseText; 
				    		respElement.inject(bn_el,'bottom');	
				    		document.getElementById("buynow_new_options"+type).setStyle('display','block');
				    	}			         		 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest2.send();
		}else{
			//bn_el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
		}
	}

	function deleteBuynowField(row_id){
		document.getElementById(row_id).innerHTML = '';
	}

	function getCities(region_id){
		var el = document.getElementById("city");
		var before = document.getElementById("city").innerHTML.trim();	
		
		if(region_id>0){
			el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&task=getCities&r_id=' + region_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
				var myRequest = new Request({
					    url: 'index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'task': 'item.getCities',
						  'r_id': region_id,
						  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
						  },
					    onRequest: function(){
					        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
					    },
					    onSuccess: function(responseText){																
							el.innerHTML = responseText;						 	
					    },
					    onFailure: function(){
					        myElement.set('html', 'Sorry, your request failed, please contact to ');
					    }
					});
					myRequest.send();	
		}else{
			el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_REGION');?>';
		}
		
	}

	window.addEvent("load", function(){
		getFields(<?php echo $this->item->cat_id; ?>);
		<?php if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->item->latitude!='0.000000000000000' && $this->item->longitude!='0.000000000000000'){ ?>
			mapaStart();
		<?php }?>
	});


	function confirm_delete_bid(id,bid){
		
		var answer = confirm ('<?php echo addslashes(JText::_('COM_DJCLASSIFIEDS_CONFIRM_DELETE_BID'));?>');
		if (answer){
			window.location="index.php?option=com_djclassifieds&task=item.deletebid&id="+id+"&bid="+bid+"";	
		}
	}

	function confirm_delete_buynow(id,bid){
		
		var answer = confirm ('<?php echo addslashes(JText::_('COM_DJCLASSIFIEDS_CONFIRM_DELETE_PURCHASE'));?>');
		if (answer){
			window.location="index.php?option=com_djclassifieds&task=item.deletebuynow&id="+id+"&bid="+bid+"";	
		}
	}


	
</script>

<?php if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->item->latitude && $this->item->longitude){ 
	$marker_txt = '<div style="width:200px;"><div style="margin-bottom:5px;"><strong>'.$this->item->name.'</strong></div>'; 
	$marker_txt .= str_ireplace("\r\n", '<br />', $this->item->intro_desc).'<br />'; 
//	$marker_txt .= '<strong>'.JText::_('Type').'</strong> : '.$i->type_name.'<br />';
//	$marker_txt .= '<strong>'.JText::_('Price').'</strong> : '.$i->price.'<br />';
//	$marker_txt .= '<strong>'.JText::_('Address').'</strong> : '.$i->country.", ".$i->city.'<br />';
//	if($i->street!='' && $this->subsc==1){
//		$marker_txt .= $i->street.'<br />';
//	}

	$marker_txt .= '<div style="margin-top:10px;">';
	

									
	if($this->item->image_url!=''){
		$images=explode(';', substr($this->item->image_url,0,-1));
		
		$path = str_replace('/administrator','',JURI::base());
		$path .= '/components/com_djclassifieds/images/';
		for($ii=0; $ii<count($images); $ii++){
			$marker_txt .= '<div class="display:inline;width:60px;"><img width="60px" src="'.$path.$images[$ii].'.ths.jpg" /></div> ';
			if($ii==3){
				break;
			}
		}
	}
	$marker_txt .='</div></div>';	
?>
 <script type='text/javascript'>    

	var map;
	var adLatlng = new google.maps.LatLng(<?php echo $this->item->latitude.','.$this->item->longitude; ?>);
	var marker = '';
	
		window.addEvent('scroll', function(){
			google.maps.event.trigger(document.getElementById('map'), 'resize');
			map.setCenter(adLatlng); 	
			google.maps.event.trigger(marker,'click');	
		} );

        var map_marker = new google.maps.InfoWindow();
        var geokoder = new google.maps.Geocoder();
        
		function addMarker(position,txt,icon)
		{
		    var MarkerOpt =  
		    { 
		        position: position,
		        icon: icon,	 	
		        map: map
		    } 
		    var marker = new google.maps.Marker(MarkerOpt);
		    marker.txt=txt;
		     
		    google.maps.event.addListener(marker,"click",function()
		    {
		        map_marker.setContent(marker.txt);
		        map_marker.open(map,marker);
		    });
		    return marker;
		}
		    	
		 function mapaStart()    
		 {   			
	    	document.getElementById("google_map_box").style.display='block';	    	
		    var opcjeMapy = {
		       zoom: <?php echo $par->get('gm_zoom','10'); ?>,
		  		center:adLatlng,
		  		mapTypeId: google.maps.MapTypeId.<?php echo $par->get('gm_type','ROADMAP'); ?>,
		  		navigationControl: true
		    };
		    map = new google.maps.Map(document.getElementById("map"), opcjeMapy);
		    <?php
             	 $icon_img = ''; 
				 $icon_size='';
             	 if($par->get('gm_icon',1)==1 && file_exists(JPATH_ROOT.'/images/djcf_gmicon_'.$this->item->cat_id.'.png')){ 
             		$icon_size = getimagesize(JPATH_ROOT.'/images/djcf_gmicon_'.$this->item->cat_id.'.png');
             		$icon_img = str_ireplace('administrator/','', JURI::base()).'images/djcf_gmicon_'.$this->item->cat_id.'.png';             		
        		 }else if($par->get('gm_icon',1)==1 && file_exists(JPATH_ROOT.'/images/djcf_gmicon.png')){
        			 $icon_size = getimagesize(JPATH_ROOT.'/images/djcf_gmicon.png');
                	 $icon_img = str_ireplace('administrator/','', JURI::base())."images/djcf_gmicon.png";
                 }elseif($par->get('gm_icon',1)==1){ 
                	 $icon_size = getimagesize(JPATH_ROOT.'/components/com_djclassifieds/assets/images/djcf_gmicon.png');
                	 $icon_img = str_ireplace('administrator/','', JURI::base())."components/com_djclassifieds/assets/images/djcf_gmicon.png";
                 }
                 //$icon_img = ''; 
                 if($icon_img && is_array($icon_size)){ 
                 	 $anchor_w = $icon_size[0]/2;?>
		             var size = new google.maps.Size(<?php echo $icon_size[0].','.$icon_size[1];?>);
		             var start_point = new google.maps.Point(0,0);
		             var anchor_point = new google.maps.Point(<?php echo $anchor_w.','.$icon_size[1];?>);   
		             var icon = new google.maps.MarkerImage("<?php echo $icon_img;?>", size, start_point, anchor_point);                
                <?php }else{ ?>
              		 var icon = '';  	
                <?php }?>
                
	    	marker = addMarker(adLatlng,'<?php echo addslashes(nl2br($marker_txt)); ?>',icon);
			google.maps.event.trigger(marker,'click');	      
		 }

</script>
<?php }?>
<?php echo '<div style="clear:both"></div>'.DJCFFOOTER; ?>