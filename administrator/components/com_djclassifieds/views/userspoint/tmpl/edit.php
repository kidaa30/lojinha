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

<form action="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=userspoint&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	<div class="row-fluid">
		<div class="span12 form-horizontal">
			<fieldset class="adminform">	
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
					</li>
				</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="details">
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
								<div class="controls">		                			
		                    		<?php echo $this->form->getInput('user_id'); ?>						
								</div>
							</div>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('points'); ?></div>
								<div class="controls">		                			
		                    		<?php echo $this->form->getInput('points'); ?>						
								</div>
							</div>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
								<div class="controls">		                			
		                    		<?php echo $this->form->getInput('description'); ?>						
								</div>
							</div>
							<div class="control-group">
								<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
								<div class="controls">		                			
		                    		<?php echo $this->form->getInput('id'); ?>						
								</div>
							</div>																										
						</div>
					</div>
			</fieldset>
		</div>	
	</div>	
	<div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>