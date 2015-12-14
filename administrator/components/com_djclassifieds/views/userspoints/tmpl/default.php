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

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'p.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=userspoints');?>" method="post" name="adminForm" id="adminForm">
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
		</div>		
		
		<div class="clr"> </div>
	
		<table class="table table-striped" width="100%">
			<thead>
				<tr>
					<th width="5%">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>				
					<th width="5%" class="nowrap">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'p.id', $listDirn, $listOrder); ?>
					</th>
					<th width="15%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_USER', 'u.name', $listDirn, $listOrder); ?>
					</th>
					<th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_POINTS', 'p.points', $listDirn, $listOrder); ?>
					</th>
					<th width="15%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_DATE', 'p.date', $listDirn, $listOrder); ?>
					</th>				
					<th width="60%">
						<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION'); ?>
					</th>				
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="15">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering	= ($listOrder == 'p.ordering');
				$canCheckin	= $user->authorise('core.manage','com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
				?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>				
					<td>
						<?php echo $item->id;?>					 
					</td>				
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.$item->user_id);?>">
							<?php echo $item->u_name.' ('.$item->user_id.')'; ?>
						</a>
					</td>	
					<td>
						<?php echo $item->points.' '.JText::_('COM_DJCLASSIFIEDS_POINTS_SHORT'); ?>					 
					</td>
					<td>
						<?php echo $item->date;?>					 
					</td>
					<td>
						<a href="<?php echo JRoute::_('index.php?option=com_djclassifieds&task=userspoint.edit&id='.$item->id);?>">
							<?php echo strip_tags($item->description); ?>
						</a>
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
