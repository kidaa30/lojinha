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
<form action="index.php?option=com_djclassifieds&task=regions" method="post" name="adminForm">
		<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_region" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_REGION');?></option>	
				<?php echo JHtml::_('select.options', DJClassifiedsRegion::getRegSelect(), 'value', 'text', $this->state->get('filter.region'));?>
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
                <th width="45%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>
                </th>
                <th width="20%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_PARENT_REGION'); ?>
                </th>
                <th width="5%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_COUNTRY'); ?>
                </th>
                <th width="5%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_OTHER'); ?>
                </th>
                <th width="5%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_CITY'); ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'r.published', $listDirn, $listOrder); ?>
                </th>
        </thead>
        <?php $i=0; 
	foreach($this->regions as $i =>$r){
	?>
        <tr>
            <td>
               <?php echo JHtml::_('grid.id', $i, $r->id); ?>
            </td>
            <td>
                <?php echo $r->id; ?>
            </td>
            <td>
					<a href="<?php echo JRoute::_('index.php?option=com_djclassifieds&task=region.edit&id='.(int) $r->id); ?>">
					<?php 
		            	echo '&nbsp';
						if(isset($r->level)){
							if($r->level>0){
								echo '&nbsp';
				            	for($ri=0;$ri<$r->level;$ri++){
				            		echo '-&nbsp';
				            	} 
							}
						}
            	
					echo $this->escape($r->name); ?></a>

            </td>          
			<td>
            	<?php 
            	if($r->parent_id>0){
            		echo $r->parent_name.' ('.$r->parent_id.')';	
            	}else{
            		echo '---';
            	}
            	
            	?>
            </td>
            <td align="center">
                <?php if($r->country){
                	echo '<img src="'.JURI::base().'/templates/bluestork/images/admin/tick.png" alt="x" />';
                }?>                
            </td>
            <td align="center">
                <?php if(!$r->country && !$r->city){
                	echo '<img src="'.JURI::base().'/templates/bluestork/images/admin/tick.png" alt="x" />';
                }?>                
            </td>
            <td align="center">
                <?php if($r->city){
                	echo '<img src="'.JURI::base().'/templates/bluestork/images/admin/tick.png" alt="x" />';
                }?>                
            </td>
            <td align="center">
                <?php echo JHtml::_('jgrid.published', $r->published, $i, 'regions.', true, 'cb'	); ?>
            </td>

        </tr>
        <?php  
		} ?>
    
    <tfoot>
        <td colspan="9">
            <?php echo $this->pagination->getListFooter(); ?><br />
        </td>
    </tfoot>
	</table>
    <input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="view" value="regions" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php echo DJCFFOOTER; ?>