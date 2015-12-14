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

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$saveOrder	= $listOrder == 'p.ordering'; 
?>
<form action="index.php?option=com_djclassifieds&task=promotions" method="post" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<select name="filter_published" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
					<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED')), 'value', 'text', $this->state->get('filter.published'), true);?>
				</select>
			</div>			
		</div>
	<div class="clr"> </div>
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th width="5%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th width="5%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'p.id', $listDirn, $listOrder); ?>
                </th>                
                <th width="20%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_LABEL'), 'p.label', $listDirn, $listOrder); ?>
                </th>
                <th width="40%">
					<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION'); ?>
                </th>
                <th width="9%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ORDERING'), 'p.ordering', $listDirn, $listOrder);
					echo JHtml::_('grid.order',  $this->promotions, 'filesave.png', 'promotions.saveorder'); ?>
                </th>
                <th width="7%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_NAME'), 'p.name', $listDirn, $listOrder); ?>
                </th>
                <th width="7%">
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_PRICE'), 'p.price', $listDirn, $listOrder); ?>
				</th>                
                <th width="7%">
					<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'p.published', $listDirn, $listOrder); ?>
                </th>
        </thead>
        <?php $i=0; 
	foreach($this->promotions as $i =>$p){
	?>
        <tr>
            <td>
               <?php echo JHtml::_('grid.id', $i, $p->id); ?>
            </td>
            <td>
                <?php echo $p->id; ?>
            </td>
            <td>
				<a href="<?php echo JRoute::_('index.php?option=com_djclassifieds&task=promotion.edit&id='.(int) $p->id); ?>">
					<?php echo JText::_($p->label); ?>
				</a>				 
            </td>
			<td>
            	<?php echo JText::_($p->description); ?>
            </td>			
            <td>
            	<?php $ordering = 'true'; ?>
					<span><?php echo $this->pagination->orderUpIcon($i, true,'promotions.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>								
					<span><?php echo $this->pagination->orderDownIcon($i, count($this->promotions), true, 'promotions.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $p->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
            </td>            
    		<td>
				<?php echo $p->name; ?>
            </td>  
			 <td>
				<?php echo $p->price.' ('.$p->points.JText::_('COM_DJCLASSIFIEDS_POINTS_SHORT').')'; ?>
            </td>
            <td align="center">
                <?php echo JHtml::_('jgrid.published', $p->published, $i, 'promotions.', true, 'cb'	); ?>
            </td>
                      
        </tr>
        <?php  
		} ?>
    
	    <tfoot>
	        <td colspan="8">
	            <?php echo $this->pagination->getListFooter(); ?>
	        </td>
	    </tfoot>
		</table>
		<input type="hidden" name="view" value="promotions" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>	
</form>
<?php echo DJCFFOOTER; ?>