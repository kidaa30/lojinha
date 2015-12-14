<?php
/**
 * @version $Id: default.php 8 2014-10-14 10:13:30Z michal $
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_djreviews&view=fields');?>" method="post" name="adminForm" id="adminForm">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else: ?>
	<div id="j-main-container">
	<?php endif;?>	
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label class="element-invisible" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
				<button type="button" class="btn" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
		</div>
		<div class="clearfix"> </div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th>
						<?php echo JHtml::_('grid.sort', 'COM_DJREVIEWS_RATING_FIELD', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJREVIEWS_RATING_GROUP', 'g.name', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort', 'COM_DJREVIEWS_WEIGHT', 'a.weight', $listDirn, $listOrder); ?>
					</th>
					<th width="5%">
						<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
						<?php if ($saveOrder) :?>
							<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'fields.saveorder'); ?>
						<?php endif; ?>
					</th>
					<th width="1%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$item->max_ordering = 0; //??
				$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out==0;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'fields.', $canCheckin); ?>
						<?php endif; ?>
						<?php if (!$canCheckin): ?>
							<?php echo $this->escape($item->name); ?>
						<?php else: ?>
							<span class="editlinktip hasTip" title="<?php echo JText::_( 'COM_DJREVIEWS_EDIT_TOOLTIP' );?>::<?php echo $this->escape($item->name); ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_djreviews&task=field.edit&id='.$item->id);?>">
								<?php echo $this->escape($item->name); ?></a>
							</span>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo $item->group_name; ?>
					</td>
					<td class="center">
						<?php echo $item->weight; ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'fields.', true, 'cb'	); ?>
					</td>
					<td class="order">
						<div class="input-prepend">
							<?php $disabled = ''; ?>
							<?php if ($saveOrder && $listDirn == 'asc') :?>
									<span class="add-on"><?php echo $this->pagination->orderUpIcon($i, ($item->group_id == @$this->items[$i-1]->group_id), 'fields.orderup', 'JLIB_HTML_MOVE_UP', $saveOrder); ?></span><span class="add-on"><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->group_id == @$this->items[$i+1]->group_id), 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $saveOrder); ?></span>
							<?php else: $disabled = 'disabled="disabled"'; echo "<span class=\"add-on tip\" title=\"".JText::_('JDISABLED')."\"><i class=\"icon-ban-circle\"></i></span>"; ?>
							<?php endif; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order width-20" />
						</div>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</form>
