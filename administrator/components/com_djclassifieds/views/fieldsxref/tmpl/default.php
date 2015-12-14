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

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$saveOrder	= $listOrder == 'f.name'; 

defined ('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_djclassifieds&view=fieldsxref&tmpl=component" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
	<input type="hidden" name="id" value="<?php echo JRequest::getVar('id'); ?>" />
	<input type="hidden" name="view" value="fieldsxref" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	<div style="text-align:right;margin:0 10px 10px 0;">
		<button onclick="save_fields();" /><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE')?></button>
		<button onclick="SqueezeBox.close(); window.parent.location.reload();" /><?php echo JText::_('COM_DJCLASSIFIEDS_CLOSE')?></button>
	</div>			

	<table class="table table-striped" width="100%">
		<thead> 
			<tr>
	           <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'f.id', $listDirn, $listOrder); ?>
                </th>
                <th width="25%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_NAME'), 'f.name', $listDirn, $listOrder); ?>
                </th>
                <th width="25%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_LABEL'), 'f.label', $listDirn, $listOrder); ?>
                </th>
                <th width="20%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_TYPE'), 'f.type', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ACTIVE'), 'f.active', $listDirn, $listOrder); ?>
                </th>
				<th width="10%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_ORDERING');?>
				</th>
		</tr></thead>
<?php 
	$ord = 0;
	foreach($this->fields as $f){
		echo '<tr><td>';
			echo $f->id;
		echo '</td><td>';
			echo $f->name;
		echo '</td><td>';
			echo $f->label;
		echo '</td><td>';
			echo $f->type;
		echo '</td><td>';
			if($f->active){
				$sel=' CHECKED ';
				$st="";
			}else{
				$sel='';				
				$st="display:none;";
			}
			echo '<input onchange="showOrdering(this,'.$f->id.')" type="checkbox" name="fields_active[]" value="'.$f->id.'" '.$sel.' />';
		echo '</td><td>';
			echo '<input style="'.$st.'width:50px;" type="text" class="fieldsordering" id="fieldsordering_'.$f->id.'" name="fieldsordering_'.$f->id.'" size="3" value="'.$f->ordering.'" />';
		echo '</td><tr>';
		if($ord<$f->ordering){
			$ord=$f->ordering;
		}
	}
	echo '</table>';
	$ord++;
//	<input type="hidden" name="task" value="fieldsxref.save" />	
?>	<div style="text-align:right;margin:10px 10px 0px 0;">		
		<button onclick="save_fields();" /><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE')?></button>
		<button onclick="SqueezeBox.close(); window.parent.location.reload();" /><?php echo JText::_('COM_DJCLASSIFIEDS_CLOSE')?></button>
	</div>	
</form>
<script type="text/javascript">
	function showOrdering(e,id){		
		var ord=0;
		document.getElements(".fieldsordering").each(function(el, i){
			var temp = parseInt(el.value);			
			if(temp>ord){
				ord=temp;		
			}        
    	});
    	ord++;
		if(e.checked){
			$('fieldsordering_'+id).value=ord;
			$('fieldsordering_'+id).setStyle("display","");
			//ord++;			
		}else{
			$('fieldsordering_'+id).setStyle("display","none");
			$('fieldsordering_'+id).value=0;
		}
	}
	function save_fields(){
		Joomla.submitform('fieldsxref.save', document.getElementById('adminForm'));
	}
</script>