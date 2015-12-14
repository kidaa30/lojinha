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

$user	= JFactory::getUser();
$par	= JComponentHelper::getParams( 'com_djclassifieds' );

?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $par->get('theme','default');?>">
	<div class="djcontact_outer clearfix" >
	<?php if($this->e_type){ ?>
		<div class="djc_error djc_error_<?php echo $this->e_type?>">
			<div class="djcontact_error_in">
				<?php echo $this->e_mesage;?>
			</div>
		</div>
		<div class="djc_error_button_box">
			<input class="button" type="button" value="<?php echo JText::_('COM_DJCLASSIFIEDS_CLOSE')?>" onclick="parent.SqueezeBox.close();" />
		</div>
	<?php }else{ ?>
		<form action="index.php" method="post" name="djForm" id="djForm" class="form-validate">
	   		<div class="djc_row">
	   			<label for="c_name" id="c_name-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_NAME'); ?></label>
	   			<input type="text" readonly="readonly" class="inputbox" value="<?php echo $user->name; ?>" name="c_name" id="c_name" />
	   		</div>
	   		<div class="djc_row">		   
	   			<label for="c_title" id="c_title"><?php echo JText::_('COM_DJCLASSIFIEDS_TITLE'); ?></label>
	   			<input type="text" class="inputbox required" value="" name="c_title" id="c_title" />
	   		</div>
	   		<div class="djc_row">		   
	   			<label for="c_message" id="c_message"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label>
	   			<textarea id="c_message" name="c_message" rows="5" cols="55" class="inputbox required"></textarea>
	   		</div>	   		
		   <div class="clear_both"></div>
		   <div class="djc_row_buttons">		
		   	<button class="button validate" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_SEND'); ?></button>
			   <input type="hidden" name="id" id="id" value="<?php echo $this->item->id; ?>">
			   <input type="hidden" name="bid" id="bid" value="<?php echo $this->bid->id; ?>">
			   <input type="hidden" name="option" value="com_djclassifieds" />
			   <input type="hidden" name="view" value="contact" />
			   <input type="hidden" name="task" value="bidderMessage" />
			   <input class="button" type="button" value="<?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL')?>" onclick="parent.SqueezeBox.close();" />
		   </div>
		   <div class="clear_both"></div>					   
		   
		</form>
	<?php } ?>
	
	</div>
</div>
