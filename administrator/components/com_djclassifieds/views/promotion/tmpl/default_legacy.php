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
			<div class="width-100">
			<fieldset class="adminform">	
			<legend><?php echo JText::_('Details'); ?></legend>
				<table class="admintable">	
				<tr>
	                <td width="200" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_LABEL');?>
	                </td>
	                <td>
	                	<?php echo JText::_($this->promotion->label); ?>
	                    <input type="hidden" name="label" id="label" value="<?php echo $this->promotion->label; ?>" />
	                </td>
	            </tr>
	            <tr>
	                <td width="200" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>
	                </td>
	                <td>
	                	<?php echo JText::_($this->promotion->description); ?>
	                    <input type="hidden" name="description" id="description" value="<?php echo $this->promotion->description; ?>" />
	                </td>
	            </tr>
	            <tr>
	                <td width="200" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?>
	                </td>
	                <td>
	                	<?php echo $this->promotion->name; ?>
	                    <input type="hidden" name="name" id="name" value="<?php echo $this->promotion->name; ?>" />
	                </td>
	            </tr>		 
	             <tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?><br />
						(11.22)
					</td>
					<td>
						<input class="text_area" type="text" name="price" id="price" size="20" value="<?php echo $this->promotion->price; ?>" maxlength="250" />									
					</td>
				</tr>  	
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_POINTS');?>
					</td>
					<td>
						<input class="text_area" type="text" name="points" id="points" size="20" maxlength="250" value="<?php echo $this->promotion->points; ?>" />
					</td>
				</tr>
				<tr>
					<td width="200" align="right" class="key">
						<?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?>
					</td>
					<td>
						<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->promotion->published==1 || $this->promotion->id==0){echo "checked";}?> /><span style="float:left;margin:5px 10px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
						<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->promotion->published==0 && $this->promotion->id>0){echo "checked";}?> /><span style="float:left;margin:5px 10px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span>
					</td>
				</tr>						
				</table>
			</fieldset>
			</div>
			<input type="hidden" name="id" value="<?php echo $this->promotion->id; ?>" />
			<input type="hidden" name="option" value="com_djclassifieds" />
			<input type="hidden" name="task" value="promotion" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>			
<?php echo DJCFFOOTER; ?>