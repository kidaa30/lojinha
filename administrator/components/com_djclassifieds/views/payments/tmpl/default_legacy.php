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

$canPayment	= true; //$user->authorise('core.edit.state', 'com_contact.category');
/*
if($listOrder == 'i.paymenting' && $this->state->get('filter.category')>0){
	$savePayment	= true;	
}else{
	$savePayment	= false;
}*/
$savePayment	= $listOrder == 'i.id'; 
$par = JComponentHelper::getParams( 'com_djclassifieds' );
?>
<form action="index.php?option=com_djclassifieds&view=payments" method="post" name="adminForm">
		<fieldset id="filter-bar">
				<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_DJIMAGESLIDER_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">			
			<select name="filter_status" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_STATUS');?></option>
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', 'Start', 'COM_DJCLASSIFIEDS_START'),JHtml::_('select.option', 'Pending', 'COM_DJCLASSIFIEDS_PENDING'),JHtml::_('select.option', 'Completed', 'COM_DJCLASSIFIEDS_COMPLETED'),JHtml::_('select.option', 'Cancelled', 'COM_DJCLASSIFIEDS_CANCELLED')), 'value', 'text', $this->state->get('filter.status'), true);?>
			</select>
			<select name="filter_type" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_TYPE');?></option>
				<?php echo JHtml::_('select.options', array(JHtml::_('select.option', '0', 'COM_DJCLASSIFIEDS_ADVERT'),JHtml::_('select.option', '1', 'COM_DJCLASSIFIEDS_POINTS_PACKAGES'),JHtml::_('select.option', '2', 'COM_DJCLASSIFIEDS_PROMOTION_MOVE_TO_TOP')), 'value', 'text', $this->state->get('filter.type'), true);?>
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
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_ID'), 'p.id', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" >
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_TYPE'), 'p.type', $listDirn, $listOrder); ?>
                </th>       
                <th width="30%">
					<?php echo JText::_( 'COM_DJCLASSIFIEDS_NAME' ); ?>					
                </th>
                <th width="10%" >
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_PRICE'), 'p.price', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
					<?php echo JText::_( 'COM_DJCLASSIFIEDS_PAYMENT_TYPE' ); ?>					
                </th>                                               
      			<th width="10%">
					<?php echo JText::_( 'COM_DJCLASSIFIEDS_USER' ); ?>					
                </th>
                <th width="10%">
					<?php echo JText::_( 'COM_DJCLASSIFIEDS_IP_ADDRESS' ); ?>					
                </th>                
                <th width="10%" >
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_DATE'), 'p.date', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" >
					<?php echo JHtml::_('grid.sort', JText::_('COM_DJCLASSIFIEDS_STATUS'), 'p.status', $listDirn, $listOrder); ?>
                </th>         
             </tr>
        </thead>
        <?php 
		$n = count($this->payments);
	foreach($this->payments as $i => $payment){
	?>
        <tr>
            <td>
               <?php echo JHtml::_('grid.id', $i, $payment->id); ?>
            </td>
            <td>
               <?php echo $payment->id; ?>
            </td>
            <td >
                <?php
                	 if($payment->type==3){					 		
						echo JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_PLAN');
					 }else if($payment->type==2){					 		
						echo JText::_('COM_DJCLASSIFIEDS_PROMOTION_MOVE_TO_TOP');
					 }else if($payment->type==1){					 		
						echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE');
					 }else{
					 	echo JText::_('COM_DJCLASSIFIEDS_ADVERT');
					 }
	                  ?>
			</td>
			<td >
                <?php  
                	if($payment->type==3){
						echo '<a href="index.php?option=com_djclassifieds&task=plan.edit&id='.(int) $payment->item_id.'">'.$payment->plan_name.'</a>';
                	}else if($payment->type==2){
						echo '<a href="index.php?option=com_djclassifieds&task=item.edit&id='.(int) $payment->item_id.'">'.$payment->i_name.'</a>';
                	}else if($payment->type==1){
						echo '<a href="index.php?option=com_djclassifieds&task=point.edit&id='.(int) $payment->item_id.'">'.$payment->pp_name.'</a>';					 		
					}else{
						echo '<a href="index.php?option=com_djclassifieds&task=item.edit&id='.(int) $payment->item_id.'">'.$payment->i_name.'</a>';
					} ?>
			</td>
			<td>
                <?php echo $payment->price; ?>
            </td>
            <td>
                <?php echo $payment->method; ?>
            </td>				
			<td>
                <?php echo $payment->u_name.' ( id '.$payment->user_id.' )'; ?>
            </td>
            <td>
                <?php echo $payment->ip_address; ?>
            </td>	
             <td>
                <?php echo $payment->date; ?>
            </td>
            <td>                                 
                <select name="change_status_<?php echo $payment->id; ?>" class="inputbox" autocomplete="off">			
					<?php echo JHtml::_('select.options', array(JHtml::_('select.option', 'Start', 'COM_DJCLASSIFIEDS_START'),JHtml::_('select.option', 'Pending', 'COM_DJCLASSIFIEDS_PENDING'),JHtml::_('select.option', 'Completed', 'COM_DJCLASSIFIEDS_COMPLETED'),JHtml::_('select.option', 'Cancelled', 'COM_DJCLASSIFIEDS_CANCELLED')), 'value', 'text', $payment->status, true); ?>
				</select>
				<a title="Change status" onclick="return listItemTask('cb<?php echo $i;?>','payments.changeStatus')" href="javascript:void(0);" class="jgrid"><span class="button"><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_CHANGE_STATUS');?></span></a>
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
	<input type="hidden" name="task" value="payments" />
	<input type="hidden" name="option" value="com_djclassifieds" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<?php echo DJCFFOOTER; ?>