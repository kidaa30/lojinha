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

?>


	 <div class="djform_row">
        <?php if($par->get('show_tooltips_newad','0')){ ?>
           	<label class="label label-auction Tips1" id="auction-lbl" for="auction" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_AUCTION_ACTIVE_TOOLTIP')?>">
               <?php echo JText::_('COM_DJCLASSIFIEDS_AUCTION_ACTIVE');?>
               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
            </label>	                               			                	
		<?php }else{ ?>
           	<label class="label label-auction" id="auction-lbl" for="auction">
               	<?php echo JText::_('COM_DJCLASSIFIEDS_AUCTION_ACTIVE'); ?>					
	        </label>
        <?php } ?>
        <div class="djform_field">
        	<select id="auction" name="auction" autocomplete="off" >
				<option value="0"><?php echo JText::_('JNO');?></option>
				<option value="1" <?php if($this->item->auction){echo 'SELECTED'; }?> ><?php echo JText::_('JYES');?></option>
			</select>
        </div>
        <div class="clear_both"></div>
     </div>
     <div id="auction_config" <?php if($this->item->auction != 1){ echo 'style="display:none"';}?>>
		 <div class="djform_row" id="price_start_outer" <?php if($this->item->buynow != 1){ echo 'style="display:none"';}?> >
	        <?php if($par->get('show_tooltips_newad','0')){ ?>
	           	<label class="label label-auction Tips1" id="price_start-lbl" for="price_start" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_START_PRICE_TOOLTIP')?>">
	               <?php echo JText::_('COM_DJCLASSIFIEDS_START_PRICE');?>
	               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	            </label>	                               			                	
			<?php }else{ ?>
	           	<label class="label label-auction" id="price_start-lbl" for="price_start">
	               	<?php echo JText::_('COM_DJCLASSIFIEDS_START_PRICE'); ?>					
		        </label>
	        <?php } ?>
	        <div class="djform_field">
		        <?php if ($par->get('unit_price_position','0')== 1) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>     		        
				<input class="text_area validate-numeric" type="text" name="price_start" id="price_start" size="30" maxlength="250" value="<?php echo $this->item->price_start; ?>" />
				<?php if ($par->get('unit_price_position','0')== 0) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>				
	        </div>
	        <div class="clear_both"></div>
	     </div>
		 <div class="djform_row">
	        <?php if($par->get('show_tooltips_newad','0')){ ?>
	           	<label class="label label-auction Tips1" id="price_reserve-lbl" for="price_reserve" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_RESERVE_PRICE_TOOLTIP')?>">
	               <?php echo JText::_('COM_DJCLASSIFIEDS_RESERVE_PRICE');?>
	               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	            </label>	                               			                	
			<?php }else{ ?>
	           	<label class="label label-auction" id="price_reserve-lbl" for="price_reserve">
	               	<?php echo JText::_('COM_DJCLASSIFIEDS_RESERVE_PRICE'); ?>					
		        </label>
	        <?php } ?>
	        <div class="djform_field">
		        <?php if ($par->get('unit_price_position','0')== 1) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>     		        
				<input class="text_area validate-numeric<?php if($this->item->bid_autoclose==1){echo ' required';}?>" type="text" name="price_reserve" id="price_reserve" size="30" maxlength="250" value="<?php echo $this->item->price_reserve; ?>" />
				<?php if ($par->get('unit_price_position','0')== 0) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>				
	        </div>
	        <div class="clear_both"></div>
	     </div>     
     	<div class="djform_row">
	        <?php if($par->get('show_tooltips_newad','0')){ ?>
	           	<label class="label label-auction Tips1" id="bid_max-lbl" for="bid_min" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_MIN_BID_INCREASE_TOOLTIP')?>">
	               <?php echo JText::_('COM_DJCLASSIFIEDS_MIN_BID_INCREASE');?>
	               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	            </label>	                               			                	
			<?php }else{ ?>
	           	<label class="label label-auction" id="bid_min-lbl" for="bid_min">
	               	<?php echo JText::_('COM_DJCLASSIFIEDS_Min_BID_INCREASE'); ?>					
		        </label>
	        <?php } ?>
	        <div class="djform_field">	   
		        <?php if ($par->get('unit_price_position','0')== 1) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>     	
				<input class="text_area validate-numeric" type="text" name="bid_min" id="bid_min" size="30" maxlength="250" value="<?php echo $this->item->bid_min; ?>" />
				<?php if ($par->get('unit_price_position','0')== 0) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>				
	        </div>
	        <div class="clear_both"></div>
	     </div>
		 <div class="djform_row">
	        <?php if($par->get('show_tooltips_newad','0')){ ?>
	           	<label class="label label-auction Tips1" id="bid_max-lbl" for="bid_max" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_MAX_BID_INCREASE_TOOLTIP')?>">
	               <?php echo JText::_('COM_DJCLASSIFIEDS_MAX_BID_INCREASE');?>
	               <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	            </label>	                               			                	
			<?php }else{ ?>
	           	<label class="label label-auction" id="bid_max-lbl" for="bid_max">
	               	<?php echo JText::_('COM_DJCLASSIFIEDS_MAX_BID_INCREASE'); ?>					
		        </label>
	        <?php } ?>
	        <div class="djform_field">	   
		        <?php if ($par->get('unit_price_position','0')== 1) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>     	
				<input class="text_area validate-numeric" type="text" name="bid_max" id="bid_max" size="30" maxlength="250" value="<?php echo $this->item->bid_max; ?>" />
				<?php if ($par->get('unit_price_position','0')== 0) {
		        	echo ($this->item->currency) ? $this->item->currency : $par->get('unit_price');
				} ?>				
	        </div>
	        <div class="clear_both"></div>
	     </div>    
	     <?php /* 	
		 <div class="djform_row no_label_row autoclose_binding">
			<label class="label" ></label>
	        <div class="djform_field" id="bid_autoclose">
	        	<span><?php echo JText::_('COM_DJCLASSIFIEDS_BID_AUTOCLOSE_INFO'); ?></span>
		        <input type="radio" name="bid_autoclose" id="bid_autoclose1" value="1" <?php  if($this->item->bid_autoclose==1){echo "checked";}?> /><label for="bid_autoclose1" id="bid_autoclose1-lbl" ><?php echo JText::_('JYES'); ?></label>
				<input type="radio" name="bid_autoclose" id="bid_autoclose0" value="0" <?php  if($this->item->bid_autoclose!=1){echo "checked";}?> /><label for="bid_autoclose0" id="bid_autoclose0-lbl" ><?php echo JText::_('JNO'); ?></label>
	        </div>
	        <div class="clear_both"></div>
	     </div> 
	     */ ?>	           
     </div>
     
     
     
     
<script type="text/javascript">
window.addEvent('domready', function(){

	/*document.id('bid_autoclose1').addEvent('change', function(){
		var ba = document.id("bid_autoclose").getElements('input[name=bid_autoclose]:checked')[0].get('value');
		if(ba==1){ document.id('price_reserve').addClass('required');
		}else{ document.id('price_reserve').removeClass('required'); }		
	});
	document.id('bid_autoclose0').addEvent('change', function(){
		var ba = document.id("bid_autoclose").getElements('input[name=bid_autoclose]:checked')[0].get('value');
		if(ba==1){ document.id('price_reserve').addClass('required');
		}else{ document.id('price_reserve').removeClass('required'); }
	});*/
	if(document.id('price_reserve') && document.id('price')){
		document.id('price_reserve').addEvent('change', function(){
			var price_r = document.id('price_reserve').get('value');
			var price_b = document.id('price').get('value');
			if(!isNaN(price_r) && !isNaN(price_b)){		
				if(price_r>0 && price_b>0){
					if(parseFloat(price_r)<parseFloat(price_b)){
						alert("<?php echo addslashes(JText::_('COM_DJCLASSIFIEDS_WRONG_RESERVED_PRICE'));?>");
					}
				}
			}
		});
	
		document.id('price').addEvent('change', function(){
			var price_r = document.id('price_reserve').get('value');
			var price_b = document.id('price').get('value');
			if(!isNaN(price_r) && !isNaN(price_b)){		
				if(price_r>0 && price_b>0){
					if(parseFloat(price_r)<parseFloat(price_b)){
						alert("<?php echo addslashes(JText::_('COM_DJCLASSIFIEDS_WRONG_RESERVED_PRICE'));?>");
					}
				}
			}
		});	
	}
	
	document.id('auction').addEvent('change', function(){
			if(document.id('auction').value==1){
				document.id('auction_config').setStyle('display','block');
				if(document.id('buynow')){
					if(document.id('buynow').value==1){
						document.id('price_start_outer').setStyle('display','block');
					}else{
						document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_START_PRICE'));?>");
						document.id('price_start_outer').setStyle('display','none');
						document.id('price-lbl').addClass('label-auction');
						document.id('price-lbl').removeClass('label-buynow');
					}
				}
			}else{
				document.id('auction_config').setStyle('display','none');
				document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_PRICE'));?>")
				document.id('price-lbl').removeClass('label-auction');
				if(document.id('buynow')){
					if(document.id('buynow').value==1){
						document.id('price-lbl').set("html","<?php echo str_replace("'","\'",JText::_('COM_DJCLASSIFIEDS_PRICE_BUYNOW'));?>")
						document.id('price-lbl').addClass('label-buynow');
					}
				}
			}
		});

	});

</script>