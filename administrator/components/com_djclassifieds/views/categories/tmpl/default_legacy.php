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
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
?>
<form action="index.php?option=com_djclassifieds&task=categories" method="post" name="adminForm" id="adminForm" >
		<fieldset id="filter-bar">
		<div class="filter-select fltrt">
			<select name="filter_category" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<option <?php if($this->state->get('filter.category')==-1){ echo 'SELECTED'; }?> value="-1"><?php echo JText::_('COM_DJCLASSIFIEDS_MAIN_CATEGORY');?></option>
				<?php $optionss=DJClassifiedsCategory::getCatSelect();?>			
				<?php echo JHtml::_('select.options', $optionss, 'value', 'text', $this->state->get('filter.category'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="5%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
                </th>
                <th width="5%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_ID'); ?>
                </th>
                <th width="25%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>
                </th>
                <th width="30%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION'); ?>
                </th>                
                <th width="7%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_ORDERING'); ?>
				
						<?php
						if($this->state->get('filter.category')!=''){
						 echo JHtml::_('grid.order',  $this->categories, 'filesave.png', 'categories.saveorder'); ?>
					<?php }; ?>
                </th>
                <th width="11%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_PARENT_CATEGORY'); ?>
                </th>
                <th width="7%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_ACCESS_RESTRICTIONS'); ?>
                </th>
                <th width="5%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE'); ?>
                </th>
                <th width="7%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_AUTOPUBLISH'); ?>
                </th>                               
                <th width="5%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'f.published', $listDirn, $listOrder); ?>
                </th>
        </thead>
        <?php $i=0; 
	foreach($this->categories as $i =>$c){
	?>
        <tr>
            <td>
               <?php echo JHtml::_('grid.id', $i, $c->id); ?>
            </td>
            <td>
                <?php echo $c->id; ?>
            </td>
            <td>
            	<?php
            	echo '&nbsp';
				if(isset($c->level)){
					if($c->level>0){
						echo '&nbsp';
		            	for($ci=0;$ci<$c->level;$ci++){
		            		echo '-&nbsp';
		            	} 
					}
				}
            	?>
					<a href="<?php echo JRoute::_('index.php?option=com_djclassifieds&task=category.edit&id='.(int) $c->id); ?>">
					<?php echo $this->escape($c->name); ?></a>

            </td>
            <td>
            	<?php 
          			if(strlen(strip_tags($c->description)) > 130){
					   echo mb_substr(strip_tags($c->description), 0, 130,'utf-8').' ...';					
					}else{
						echo $c->description;
					}	
            	?>
            </td>
				<td class="order">
					<?php if($this->state->get('filter.category')!=''){
					$ordering = 'true';				
					?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($c->parent_id == @$this->categories[$i-1]->parent_id),'categories.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>								
								<span><?php echo $this->pagination->orderDownIcon($i, count($this->categories), ($c->parent_id == @$this->categories[$i+1]->parent_id), 'categories.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $c->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php }else{ ?>
						<?php echo $c->ordering; ?>
					<?php } ?>
				</td>           
			<td>
            	<?php 
            	if($c->parent_id>0){
            		echo $c->parent_name.' ('.$c->parent_id.')';	
            	}else{
            		echo '---';
            	}
            	
            	?>
            </td>
             <td>
            	<?php
            	if($c->access == '0'){
					echo JText::_('COM_DJCLASIFIEDS_DEFAULT_INHERIT');
				}elseif($c->access == '1'){
					echo JText::_('COM_DJCLASIFIEDS_RESTRICTED');
				}
            	 ?>
            </td>            
            <td>
            	<?php 
            		if($c->price){
            			echo $c->price/100;
            			echo ' ('.$c->points.JText::_('COM_DJCLASSIFIEDS_POINTS_SHORT').')';
            		}else{
            			echo JText::_('COM_DJCLASSIFIEDS_FREE');
            		}
            	?>
            </td>
            <td>
            	<?php
            	if($c->autopublish == '0'){
					echo JText::_('COM_DJCLASSIFIEDS_GLOBAL');
				}elseif($c->autopublish == '1'){
					echo JText::_('COM_DJCLASSIFIEDS_YES');
				}elseif($c->autopublish == '2'){
					echo JText::_('COM_DJCLASSIFIEDS_NO');
				}
            	 ?>
            </td>
            <td align="center">
                <?php echo JHtml::_('jgrid.published', $c->published, $i, 'categories.', true, 'cb'	); ?>
            </td>

        </tr>
        <?php  
		} ?>
    
    <tfoot>
        <td colspan="9">
            <?php echo $this->pagination->getListFooter(); ?>
        </td>
    </tfoot>
	</table>
    <input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="categories" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php echo DJCFFOOTER; ?>