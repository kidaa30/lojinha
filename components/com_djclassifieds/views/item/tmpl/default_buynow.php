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
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$item = $this->item;
$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';
$min_bid = $item->price;
$user = JFactory::getUser();
$bid_active = 1;
$Itemid= JRequest::getVar('Itemid');
$buynow_class = '';
		
if($item->quantity==0){
	$buynow_class = 'bn_quantity0';	
}else if($item->quantity==1){
	$buynow_class = 'bn_quantity1';
}
?>

	<div class="buynow_outer <?php echo $buynow_class; ?>">
		<div class="buynow_outer_in">
			<?php if($item->quantity>0){ ?>
				<form action="index.php" method="post" name="djForm" id="djForm" class="form-validate" enctype="multipart/form-data" >
					<?php if(count($this->item_options)){ ?>
						<select name="buynow_option" id="buynow_option" class="inputbox buynow_option" >
						<?php 
						$opt_c = 0;
						foreach($this->item_options as $opt){
							echo '<option value="'.$opt->id.'_'.$opt->quantity.'">';
								$opt_name = '';
								foreach($opt->options as $o){
									if($opt_name){ $opt_name .= ' - ';}
									$opt_name .= $o->label.': '.$o->value; 
								}
								echo $opt_name;
							echo '</option>';
							if(!$opt_c){
								$item->quantity = $opt->quantity;
							}
							$opt_c++;
						}?>
						</select>
					<?php } ?>					
					<?php if($item->quantity==1 && count($this->item_options)==0){ ?>
						<input type="hidden" name="quantity"  id="buynow_quantity" value="1" />
					<?php }else{ ?>
						<input type="text" class="buynow_quantity required validate-numeric" name="quantity" id="buynow_quantity" value="1" />								
						<label id="buynow_quantity-lbl" for="buynow_quantity" >
							<?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW_FROM'); ?>
							<span id="quantity_limit"><?php echo $item->quantity; ?></span>	
							<?php if($item->unit_name){ echo $item->unit_name;} ?>
						</label>
					<?php } ?>									
					<button class="button validate" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW'); ?></button>			    
				    <input type="hidden" name="item_id" id="item_id" value="<?php echo $item->id; ?>">
				    <input type="hidden" name="cid" id="cid" value="<?php echo $item->cat_id; ?>">
				    <input type="hidden" name="option" value="com_djclassifieds" />
				    <input type="hidden" name="view" value="checkout" />
				    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
				   <div class="clear_both"></div>
				</form>
			<?php }else{				
				echo JText::_('COM_DJCLASSIFIEDS_0_AVAILABLE').' ';
				if($item->unit_name){ echo $item->unit_name;}
			} ?> 								
		</div>
	</div>
	<?php if(count($this->item_options)){ ?>
		<script type="text/javascript">
		window.addEvent('load', function(){
			document.id('buynow_option').addEvent('change', function(e){
				var val = this.value.split("_"); 
				document.id('quantity_limit').innerHTML = val[1]; 
			})			
		})
		</script>
	<?php } ?>		
