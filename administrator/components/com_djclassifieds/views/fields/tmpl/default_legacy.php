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

$cid = JRequest::getVar('cid',0,'','int');
defined ('_JEXEC') or die('Restricted access');
/*$limit = JRequest::getVar('limit', 25, '', 'int');
$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
$ord_t = JRequest::getVar('ord_t', 'desc');
$order = JRequest::getVar('order');
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}*/
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$saveOrder	= $listOrder == 'f.label'; 
?>
<form action="index.php?option=com_djclassifieds&task=fields" method="post" name="adminForm">
		<fieldset id="filter-bar">
			<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_source" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_SOURCE');?></option>
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '0', 'COM_DJCLASSIFIEDS_CATEGORY'),JHtml::_('select.option', '1', 'COM_DJCLASSIFIEDS_CONTACT'),JHtml::_('select.option', '2', 'COM_DJCLASSIFIEDS_USER_PROFILE'),JHtml::_('select.option', '3', 'COM_DJCLASSIFIEDS_ASK_SELLER_FORM')), 'value', 'text', $this->state->get('filter.source'), true);?>
			</select>
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED')), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'f.id', $listDirn, $listOrder); ?>
                </th>
                <th width="20%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_NAME'), 'f.name', $listDirn, $listOrder); ?>
                </th>
                <th width="20%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_LABEL'), 'f.label', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_SOURCE_TYPE'), 'f.source', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_TYPE'), 'f.type', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_IN_SEARCH'), 'f.search_type', $listDirn, $listOrder); ?>
				</th>                
				<th width="10%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ORDERING'), 'f.ordering', $listDirn, $listOrder);
					if($this->state->get('filter.source')!='' && $listOrder=='f.ordering'){
						echo JHtml::_('grid.order',  $this->fields, 'filesave.png', 'fields.saveorder');
					} ?>
                </th>           
                <th width="10%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'f.published', $listDirn, $listOrder); ?>
                </th>
        </thead>
        <?php $i=0; 
	foreach($this->fields as $i =>$f){
	?>
        <tr>
            <td>
               <?php echo JHtml::_('grid.id', $i, $f->id); ?>
            </td>
            <td>
                <?php echo $f->id; ?>
            </td>
            <td>
            	<?php if($f->name){?>
				<a href="<?php echo JRoute::_('index.php?option=com_djclassifieds&task=field.edit&id='.(int) $f->id); ?>">
					<?php echo $this->escape($f->name); ?>
				</a>
				<?php
				if($f->name=='price' || $f->name=='contact'){
						echo '<br /><span style="color:red">'.JText::_('COM_DJCLASSIFIEDS_CUSTOM_FIELD_INSTEAD_OF_DEFAULT_FIELD').'</span>';
					}				
				 }else{
					echo '---';
				} ?>
            </td>
			<td>
            	<?php if($f->label){?>
					<?php echo $this->escape($f->label); ?>
				<?php }else{
					echo '---';
				} ?>
            </td>
            <td>
            	<?php  if($f->source==3){
            		echo JText::_('COM_DJCLASSIFIEDS_ASK_SELLER_FORM');
            	}else if($f->source==2){
            		echo JText::_('COM_DJCLASSIFIEDS_USER_PROFILE');
            	}else if($f->source==1){
					echo JText::_('COM_DJCLASSIFIEDS_CONTACT');
            	}else{
					echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');
				} ?>
            </td>
			<td>
            	<?php if($f->type){?>
					<?php echo $this->escape($f->type); ?>
				<?php }else{
					echo '---';
				} ?>
            </td>
    		 <td>
				<?php 
				if($f->in_search){
					echo JText::_('Yes').' - ';
					if($f->search_type=='inputbox'){
						echo JText::_('COM_DJCLASSIFIEDS_INPUTBOX');
					}else if($f->search_type=='select'){
						echo JText::_('COM_DJCLASSIFIEDS_SELECTLIST');
					}else if($f->search_type=='radio'){
						echo JText::_('COM_DJCLASSIFIEDS_RADIOBUTTON');
					}else if($f->search_type=='checkbox'){
						echo JText::_('COM_DJCLASSIFIEDS_CHECKBOX');
					}else if($f->search_type=='inputbox_min_max'){
						echo JText::_('COM_DJCLASSIFIEDS_TWO_INPUTBOX_MIN_MAX');
					}else if($f->search_type=='select_min_max'){
						echo JText::_('COM_DJCLASSIFIEDS_TWO_SELECTLIST_MIN_MAX');
					}
				}else{
					echo JText::_('No');
				}	
				?>
            </td>
            <td align="center">
            	<?php            	
            	if($this->state->get('filter.source')!='' && $listOrder=='f.ordering'){
            		$ordering = 'true'; ?>
	            		<span><?php echo $this->pagination->orderUpIcon($i,true,'fields.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>								
						<span><?php echo $this->pagination->orderDownIcon($i, count($this->fields), true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
						<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $f->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
            		<?php	
            	}else{
					echo '<span class="hasTip" title="'.JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_SOURCE_AND_ORDER_BY_ORDERING').'">'.$f->ordering.'</span>';	
            	}?>					
            </td>   
            <td align="center">
                <?php echo JHtml::_('jgrid.published', $f->published, $i, 'fields.', true, 'cb'	); ?>
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
	<input type="hidden" name="view" value="fields" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php echo DJCFFOOTER; ?>