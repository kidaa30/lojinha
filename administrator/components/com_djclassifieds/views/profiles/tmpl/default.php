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
JHTML::_('behavior.tooltip');
//$limit = JRequest::getVar('limit', 25, '', 'int');
//$limitstart = JRequest::getVar('limitstart', 0, '', 'int');

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');

$canOrder	= true; //$user->authorise('core.edit.state', 'com_contact.category');
/*
if($listOrder == 'i.ordering' && $this->state->get('filter.category')>0){
	$saveOrder	= true;	
}else{
	$saveOrder	= false;
}*/
$saveOrder	= $listOrder == 'i.ordering'; 
$par = JComponentHelper::getParams( 'com_djclassifieds' );
?>
<form action="index.php?option=com_djclassifieds&view=profiles" method="post" name="adminForm" id="adminForm">
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
	    <table class="table table-striped djcf-items-table" width="100%">
	        <thead>
	            <tr>
	                <th width="5%">
	                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	                </th>
	                <th width="5%">
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'u.id', $listDirn, $listOrder); ?>
	                </th>
	                <th width="20%" >
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_NAME'), 'u.name', $listDirn, $listOrder); ?>
	                </th>
					<th width="20%">
						<?php echo JHtml::_('grid.sort', JText::_('JGLOBAL_USERNAME'), 'u.username', $listDirn, $listOrder); ?>
	                </th>
	                 <th width="30%">
						<?php echo JHtml::_('grid.sort', JText::_('JGLOBAL_EMAIL'), 'u.email', $listDirn, $listOrder); ?>
	                </th>
	                 <th width="10%">
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_POINTS'), 'p.u_points', $listDirn, $listOrder); ?>
	                </th>
					 <th width="10%">
						<?php echo JText::_('COM_DJCLASSIFIEDS_JOOMLA_PROFILE'); ?>
	                </th>
	            </tr>
	        </thead>
	        <?php 
			$n = count($this->items);
		foreach($this->items as $i => $item){	
		?>
	        <tr>
	            <td>
	               <?php echo JHtml::_('grid.id', $i, $item->id); ?>
	            </td>
	            <td>
	                <?php echo $item->id; ?>
	            </td>
	            <td valign="center" >
	                <?php
					echo '<a href="index.php?option=com_djclassifieds&task=profile.edit&id='.(int) $item->id.'">';
						if($item->img_name){
							echo '<img src="'.JURI::root().$item->img_path.$item->img_name.'_ths.'.$item->img_ext.'" width="50px" /> ';	
						}
					echo $item->name.'</a> ';?>
				</td>	
	            <td>
	                <?php echo $item->username; ?>
	            </td> 
	            <td>
	                <?php echo $item->email; ?>
	            </td>
	            <td align="center" >
	                <?php echo ($item->u_points)? $item->u_points : '0' ; ?>
	            </td> 
	            <td valign="center" >
	                <?php
					echo '<a href="index.php?option=com_users&view=user&layout=edit&id='.(int) $item->id.'">'. JText::_('COM_DJCLASSIFIEDS_EDIT_JOOMLA_PROFILE').'</a> ';?>
				</td>	
	        </tr>
	        <?php 
			} ?>
	    
	    <tfoot>
	        <td colspan="12">
	            <?php echo $this->pagination->getListFooter(); ?>
	        </td>
	    </tfoot>
		</table>
		<input type="hidden" name="task" value="profiles" />
		<input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php echo DJCFFOOTER; ?>