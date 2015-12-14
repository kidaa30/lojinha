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
JHTML::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHTML::_('behavior.calendar');
$toolTipArray = array('className'=>'djcf_label');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = $this->par;
$id = JRequest::getVar('id', 0, '', 'int' );
$token = JRequest::getCMD('token', '' );
if($id==0 && $token==''){
	$this->item->quantity = 1;
}
?>


	 <div class="djform_row">
        <?php if($par->get('show_tooltips_newad','0')){ ?>
           	<label class="label label-buynow Tips1" id="buynow-lbl" for="buynow" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_BUYNOW_ACTIVE_TOOLTIP')?>">
               <?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW_ACTIVE');?>
               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
            </label>	                               			                	
		<?php }else{ ?>
           	<label class="label label-buynow" id="buynow-lbl" for="buynow">
               	<?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW_ACTIVE'); ?>					
	        </label>
        <?php } ?>
        <div class="djform_field">
        	<select id="buynow" name="buynow" autocomplete="off" >
				<option value="0"><?php echo JText::_('JNO');?></option>
				<option value="1" <?php if($this->item->buynow){echo 'SELECTED'; }?> ><?php echo JText::_('JYES');?></option>
			</select>
        </div>
        <div class="clear_both"></div>
     </div>
     <div id="buynow_config" <?php if($this->item->buynow != 1){ echo 'style="display:none"';}?>>
    
		 <div class="djform_row" id="buynow_quantity_box">
	        <?php if($par->get('show_tooltips_newad','0')){ ?>
	           	<label class="label label-buynow Tips1" id="quantity-lbl" for="quantity" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_QUANTITY_TOOLTIP')?>">
	               <?php echo JText::_('COM_DJCLASSIFIEDS_QUANTITY');?>
	               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	            </label>	                               			                	
			<?php }else{ ?>
	           	<label class="label label-buynow" id="quantity-lbl" for="quantity">
	               	<?php echo JText::_('COM_DJCLASSIFIEDS_QUANTITY'); ?>					
		        </label>
	        <?php } ?>
	        <div class="djform_field">    		        
				<input class="text_area validate-numeric<?php if($this->item->buynow==1){echo ' required';}?>" type="text" name="quantity" id="quantity" size="30" maxlength="250" value="<?php echo $this->item->quantity; ?>" />
				<select name="unit_id">					                	
					<?php echo JHtml::_('select.options', $this->item_units, 'id', 'name', $this->item->unit_id, true);?>										
				</select>
	        </div>
	        <div class="clear_both"></div>
	     </div>   
	     
	     
	    <div id="buynow_options"></div>
		<div id="buynow_options1"></div>
		<div id="buynow_new_options1" style="display:none">
			<span class="button" onclick="getBuynowFields(1)"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEW_ITEM') ?> </span>
			<div class="clear_both"></div>
		</div>
		<div id="buynow_options2_info"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_YOUR_OWN_ITEM_SPECIFIC')?></div>
		<div id="buynow_options2"></div>	
		<div id="buynow_new_options2" style="display:none">
			<span class="button" onclick="getBuynowFields(2)"><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEW_ITEM') ?> </span>
			<div class="clear_both"></div>
		</div>
	             
     </div>
     
     
     
     
<script type="text/javascript">
window.addEvent('domready', function(){
	
	document.id('buynow').addEvent('change', function(){
			if(document.id('buynow').value==1){
				document.id('buynow_config').setStyle('display','block');
				if(document.id('price-lbl')){					
					document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_PRICE_BUYNOW'));?>");
					document.id('price-lbl').addClass('label-buynow');
					document.id('price-lbl').removeClass('label-auction');
				}
				if(document.id('auction')){
					if(document.id('auction').value==1){
						document.id('price_start_outer').setStyle('display','block');
					}
				}	
			}else{
				document.id('buynow_config').setStyle('display','none');
				if(document.id('price-lbl')){
					document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_PRICE'));?>")
					document.id('price-lbl').removeClass('label-buynow');
				}
				if(document.id('auction')){
					if(document.id('auction').value==1){
						document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_START_PRICE'));?>");
						document.id('price_start_outer').setStyle('display','none');
						document.id('price-lbl').addClass('label-auction');					
					}
				}						
			}
		});
	});


				

</script>