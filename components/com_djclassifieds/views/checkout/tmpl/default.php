<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
JHTML::_('behavior.framework' );
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$dispatcher	= JDispatcher::getInstance();

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';


$quantity = JRequest::getInt('quantity',1);
$Itemid = JRequest::getInt('Itemid',0);
$item = $this->item;

?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
	<?php	$modules_djcf = &JModuleHelper::getModules('djcf-checkout-top');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-checkout-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	?>
		
		<div class="djcf_checkout_outer clearfix">
			<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_CHECKOUT_DETAILS');?></div>
			<div class="djcf_checkout_det">
				<div class="ch_row">
					<span class="ch_label">
						<?php echo JText::_('COM_DJCLASSIFIEDS_ADVERT'); ?>:
					</span>
					<span class="ch_value">
						<?php echo '<a href="'.DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias).'" >'.$item->name; 
							if($this->item_options){
								echo '<div class="item_option">'.$this->item_options.'</div>';
							}
						echo '</a>';	?>
					</span>
				</div>		
				<div class="ch_row">
					<span class="ch_label">
						<?php echo JText::_('COM_DJCLASSIFIEDS_QUANTITY'); ?>:
					</span>
					<span class="ch_value">
						<?php echo $quantity; ?>
					</span>
				</div>
				<div class="ch_row">
					<span class="ch_label">
						<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE'); ?>:
					</span>
					<span class="ch_value">
						<?php echo DJClassifiedsTheme::priceFormat($item->price,$item->currency); ?>																													
					</span>
				</div>	
				<?php if(count($this->extra_payments)){
					foreach($this->extra_payments as $extra_p){
						echo $extra_p;
					}
				}?>
				<div class="ch_row_total">
					<span class="ch_label">
						<?php echo JText::_('COM_DJCLASSIFIEDS_TOTAL'); ?>:
					</span>
					<span class="ch_value">
						<?php $total = $item->price*$quantity; 
						$dispatcher->trigger('onPrepareCheckouPaymentTotal', array (& $item, & $par, $quantity, & $total ));
						echo DJClassifiedsTheme::priceFormat($total,$item->currency); ?>
					</span>	
				</div>
				<div class="ch_row_button">	
				<a class="button btn button_cancel"  href="<?php echo DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias); ?>">
					<?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL'); ?>
				</a>			
					<form action="index.php" method="post" name="djForm" id="djForm" class="form-validate" enctype="multipart/form-data" >															
						<button class="button validate" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW'); ?></button>			    
					    <input type="hidden" name="item_id" id="item_id" value="<?php echo $item->id; ?>">
					    <input type="hidden" name="cid" id="cid" value="<?php echo $item->cat_id; ?>">
					    <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">
					    <input type="hidden" name="buynow_option" value="<?php echo JRequest::getInt("buynow_option","0"); ?>">
					    <input type="hidden" name="option" value="com_djclassifieds" />
					    <input type="hidden" name="view" value="checkout" />
					    <input type="hidden" name="task" value="saveCheckout" />
					    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
					    <?php if(count($this->extra_form)){
							foreach($this->extra_form as $extra_input){
								echo $extra_input;
							}
						}?>
					   <div class="clear_both"></div>
					</form> 
					<div class="clear_both"></div>
				</div>					
			</div>										
		</div>

		<?php	$modules_djcf = &JModuleHelper::getModules('djcf-checkout-bottom');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-checkout-bottom clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	?>
</div>