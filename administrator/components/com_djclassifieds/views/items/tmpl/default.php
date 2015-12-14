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
<form action="index.php?option=com_djclassifieds&view=items" method="post" name="adminForm" id="adminForm">
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
					<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'JPUBLISHED'),JHtml::_('select.option', '0', 'JUNPUBLISHED'),JHtml::_('select.option', '2', 'JARCHIVED')), 'value', 'text', $this->state->get('filter.published'), true);?>
				</select>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<select name="filter_category" class="inputbox" onchange="this.form.submit()">
					<option value="0"><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
					<?php $optionss=DJClassifiedsCategory::getCatSelect();?>			
					<?php echo JHtml::_('select.options', $optionss, 'value', 'text', $this->state->get('filter.category'));?>
				</select>
			</div>								
			<?php 	/*
			<div class="btn-group pull-right hidden-phone">
				<select name="filter_active" class="inputbox" onchange="this.form.submit()">
					<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_ACTIVE');?></option>
					<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '1', 'COM_DJCLASSIFIEDS_ACTIVE'),JHtml::_('select.option', '0', 'COM_DJCLASSIFIEDS_HIDE')), 'value', 'text', $this->state->get('filter.active'), true);?>
				</select>
			</div>	
			<?php */?>		
		</div>
		<div class="clr"> </div>
	    <table class="table table-striped djcf-items-table" width="100%">
	        <thead>
	            <tr>
	                <th width="10%">
	                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
	                </th>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'i.id', $listDirn, $listOrder); ?>
	                </th>
	                <th width="20%" colspan="2">
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_NAME'), 'i.name', $listDirn, $listOrder); ?>
	                </th>
					 <th width="20%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_CATEGORY', 'category_name', $listDirn, $listOrder); ?>
	                </th>
					 <th width="30%">
						<?php echo JText::_( 'COM_DJCLASSIFIEDS_INTRO_DESCRIPTION' ); ?>					
	                </th>
	      			<th width="30%">
						<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_CREATED_BY'), 'u.name', $listDirn, $listOrder); ?>				
	                </th>
	                <?php if($par->get('abuse_reporting','0')==1){?>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_ABUSE', 'a.c_abuse', $listDirn, $listOrder); ?>
	                </th>
	                <?php } ?>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_PROMOTED', 'i.promotions', $listDirn, $listOrder); ?>
	                </th>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_ADDED', 'i.date_start', $listDirn, $listOrder); ?>
	                </th>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'i.published', $listDirn, $listOrder); ?>
	                </th>
	                <th width="10%">
						<?php echo JHtml::_('grid.sort', 'COM_DJCLASSIFIEDS_ACTIVE', 's_active', $listDirn, $listOrder); ?>
	                </th>
	            </tr>
	        </thead>
	        <?php 
			$n = count($this->items);
		foreach($this->items as $i => $item){
	
		//$item->name = JHTML::link('index.php?option=com_djclassifieds&task=editItem&cid[]='.$item->id, $item->name);
	
		//$checked = JHTML::_('grid.id', $i, $item->id );
		
		//$published=JHTML::_('grid.published', $item, $i);
	
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
					if($item->img_name){
						$path= JURI::root().$item->img_path.$item->img_name.'_ths.'.$item->img_ext;					
					}else{
						$path = str_replace('/administrator','',JURI::base());
						$path .= '/components/com_djclassifieds/assets/images/no-image.png';				
					} 
					echo '<a href="index.php?option=com_djclassifieds&task=item.edit&id='.(int) $item->id.'"><img src="'.$path.'" /></a> ';?>
				</td>	
				<td valign="center" >
				<?php
					echo '<a href="index.php?option=com_djclassifieds&task=item.edit&id='.(int) $item->id.'">'.$item->name.'</a>';				
					?>
	            </td>
				<td>
	                <?php echo $item->cat_name.' ( id '.$item->cat_id.' )'; ?>
	            </td>
					<td class="order djcf-items-desc">
	                <?php 
						if(strlen($item->intro_desc) > 75){
						   echo mb_substr($item->intro_desc, 0, 75,'utf-8').' ...';						
						}else{
							echo $item->intro_desc;
						}
					?>
					</td>
	
	            <td>
	            	<?php 
	            	echo $item->user_name.' ('.$item->user_id.')';
	            	?>
	            </td>
	            <?php if($par->get('abuse_reporting','0')==1){?>
	            <td align="center">
	            	<?php 
	            	if($item->c_abuse){
	            		echo $item->c_abuse;	
	            	}else{
	            		echo '0';
	            	}            	
	            	?>
	            </td>
	            <?php } ?>
	            <td align="center">
	                <?php                 
	                if($item->promotions){
	                	echo '<span title="'.$item->promotions.'">'.JText::_('COM_DJCLASSIFIEDS_YES').'</span>';
	                }else{
	                	echo JText::_('COM_DJCLASSIFIEDS_NO');
	                }
	                ?>
	            </td>    
	            <td align="center">
	                <?php echo $item->date_start; ?>
	            </td>                   
	            <td align="center">
	                <?php echo JHtml::_('jgrid.published', $item->published, $i, 'items.', true, 'cb'	); ?>
	            </td>
	             <td align="center">
	                <?php 
	                if($item->s_active){
	                	echo '<span title="'.$item->date_start.' - '.$item->date_exp.'" style="color:#559D01;font-weight:bold;" >'.$item->date_exp.'</span>';
	                }else{
	                	echo '<span title="'.$item->date_start.' - '.$item->date_exp.'" style="color:#C23C00;font-weight:bold;" >'.$item->date_exp.'</span>';
	                }
	                ?>
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
		<input type="hidden" name="task" value="items" />
		<input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<?php echo DJCFFOOTER; ?>