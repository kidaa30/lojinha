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

?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<div class="row-fluid">
			<div class="span12 form-horizontal">
			<fieldset class="adminform">	
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#details" data-toggle="tab"><?php echo empty($this->region->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?></div>
							<div class="controls">	        
								<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->region->name; ?>" />													
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_PARENT_REGION');?></div>
							<div class="controls">	        
								<?php
									$optionss = array();
									$optionss=DJClassifiedsRegion::getRegSelect();
									if($this->region->id>0){
										$reg_list= DJClassifiedsRegion::getSubReg($this->region->id);
										$reg_list_assoc = array();
										$reg_list_assoc[$this->region->id]=1;
										foreach($reg_list as $cl){
											$reg_list_assoc[$cl->id]=1;
										}
										foreach($optionss as $op){
											if(isset($reg_list_assoc[$op->value])){
												$op->disable=1;
											}
										}
									}
									
									$main_tab = array();
									$main_tab[0]= JHTML::_('select.option', '0', JText::_('COM_DJCLASSIFIEDS_MAIN_REGION'));
									$options = array();
									$options = array_merge_recursive ($main_tab, $optionss);
									//print_r($options);die();
									echo JHTML::_('select.genericlist', $options, 'parent_id', null, 'value', 'text', $this->region->parent_id);
								?>				
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_TYPE');?></div>
							<div class="controls">	        
								<input type="radio" name="type" id="type_co" size="50" maxlength="250" value="country" <?php if($this->region->id>0 && $this->region->country=='1'){echo 'CHECKED';}?> /> <span style="float:left; margin:2px 20px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_COUNTRY'); ?></span>
								<input type="radio" name="type" id="type_ci" size="50" maxlength="250" value="city" <?php if($this->region->id>0 && $this->region->city=='1'){echo 'CHECKED';}?>  /> <span style="float:left; margin:2px 20px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_CITY'); ?></span>
								<input type="radio" name="type" id="type_ot" size="50" maxlength="250" value="other" <?php if($this->region->id>0 && $this->region->country=='0' && $this->region->city=='0'){echo 'CHECKED';}?>  /> <span style="float:left; margin:2px 20px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_OTHER'); ?></span>													
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?></div>
							<div class="controls">	        
								<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->region->published==1 || $this->region->id==0){echo "checked";}?> /><span style="float:left; margin:2px 10px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
								<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->region->published==0 && $this->region->id>0){echo "checked";}?> /><span style="float:left; margin:2px 10px 0 5px;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>													
							</div>
						</div>					
					</div>
				</div>				
			</fieldset>
			</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $this->region->id; ?>" />
			<input type="hidden" name="option" value="com_djclassifieds" />
			<input type="hidden" name="task" value="region" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
		
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'region.apply' || task == 'region.save' || task == 'region.save2new' ){
			if(document.adminForm.type[0].checked || document.adminForm.type[1].checked || document.adminForm.type[2].checked){
				Joomla.submitform(task, document.getElementById('adminForm'));
			}else{
				alert('<?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_REGION_TYPE'); ?>');
			}
		}else{
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>
<?php echo DJCFFOOTER; ?>