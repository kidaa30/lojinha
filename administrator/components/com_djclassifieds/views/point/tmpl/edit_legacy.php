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

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<form action="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=point&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminform" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></legend>
			<ul class="adminformlist">
			<li><?php echo $this->form->getLabel('name'); ?>
			<?php echo $this->form->getInput('name'); ?></li>
			
			<li><?php echo $this->form->getLabel('points'); ?>
			<?php echo $this->form->getInput('points'); ?></li>
			
			<li><?php echo $this->form->getLabel('price'); ?>
			<?php echo $this->form->getInput('price'); ?></li>

			<li><?php echo $this->form->getLabel('published'); ?>
			<?php echo $this->form->getInput('published'); ?></li>

			<li><?php echo $this->form->getLabel('id'); ?>
			<?php echo $this->form->getInput('id'); ?></li>
			
			<li><div style="clear:both"></div><?php
			if(empty($this->item->id)){
				echo JText::_('COM_DJCLASSIFIEDS_PLEASE');
				echo ' <button style="float:none" onclick="save_to_manage();">'.JText::_('COM_DJCLASSIFIEDS_SAVE').'</button>';
				echo JText::_('COM_DJCLASSIFIEDS_POINTS_TO_SET_ACCESS_RESTRICTIONS');
				echo '<input type="hidden" name="access" value="0" />';				 
			}else{
				echo $this->form->getLabel('points_groups'); 
				echo $this->form->getInput('points_groups');	
			}
			
			?></li>			
						
			</ul>			
		</fieldset>
		
	</div>
	<div class="clr"></div>
	<input type="hidden" id="djtask" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
	<script type="text/javascript">	
	
	function save_to_manage(){
		document.getElementById("djtask").value="point.apply";
		Joomla.submitform('point.apply', document.getElementById('adminForm'));
	}			
	</script>