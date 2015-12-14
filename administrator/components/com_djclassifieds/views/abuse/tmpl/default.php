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
<form action="index.php?option=com_djclassifieds&view=abuse&tmpl=component" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
	<div style="text-align:right;margin:0 10px 10px 0;">
		<button onclick="deleteReports();" /><?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_REPORTS')?></button>
		<button onclick="SqueezeBox.close();window.parent.location.reload();" /><?php echo JText::_('COM_DJCLASSIFIEDS_CLOSE')?></button>
	</div>			
	<h3><?php echo JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS')?></h3>
	<?php
	if(count($this->abuse)){
		echo '<table class="adminlist">';
			 foreach($this->abuse as $a){
				echo '<tr><td style=" background: none repeat scroll 0 0 #F7F7F7;border-bottom: 1px solid #CCCCCC;border-left: 1px solid #FFFFFF;color: #666666;text-align: left;">';
				echo $a->u_name.' ('.$a->user_id.')';
				echo '<span style="margin-left:30px; font-size:11px;">'.$a->date.'</span>';
				echo '</td></tr>';
				echo '<tr><td>';
				echo $a->message;
				echo '</td></tr>';
			 }
		echo '</table>';
	  }else{
	  	echo JText::_('COM_DJCLASSIFIEDS_NO_ABUSE_REPORTS');
	  }?>	
	<div style="text-align:right;margin:10px 10px 0px 0;">		
		<button onclick="deleteReports();" /><?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_REPORTS')?></button>
		<button onclick="SqueezeBox.close();window.parent.location.reload();" /><?php echo JText::_('COM_DJCLASSIFIEDS_CLOSE')?></button>
	</div>
	<input type="hidden" name="id" value="<?php echo JRequest::getVar('id'); ?>" />
	<input type="hidden" name="view" value="abuse" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
	function deleteReports(){
		var answer = confirm ('<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_ABUSE_REPORTS');?>');
		if (answer){
			Joomla.submitform('abuse.delete', document.getElementById('adminForm'));
		}
	}
</script>