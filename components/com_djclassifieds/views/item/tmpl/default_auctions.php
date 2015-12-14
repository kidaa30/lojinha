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
if($item->buynow){	
	if(count($this->bids)){
		$min_bid = $this->bids[0]->price;
	}else{
		$min_bid = $item->price_start;		
	}
	
}else{
	$min_bid = $item->price;	
}


$user = JFactory::getUser();
$bid_active = 1;
if($item->quantity==0 && $item->buynow){
	$bid_active = 0;
}
$Itemid= JRequest::getVar('Itemid');
$auction_cl = 'row_gd';
if($par->get('auctions_position','top')=='middle'){
	$auction_cl = 'row_content';	
}
if($par->get('auctions_position','top')=='bottom'){
	$auction_cl = 'row_bottom';
}

?>


<div class="auction <?php echo $auction_cl;?>" id="djauctions">
	<div class="auction_bids">
		<div class="bids_title"><h2><?php echo JText::_('COM_DJCLASSIFIEDS_CURRENT_BIDS'); ?></h2></div>
		<?php 
		if(isset($this->bids[0]) && $item->price_reserve){
			if($this->bids[0]->price<$item->price_reserve){ ?>
				<div class="bids_subtitle"><?php echo JText::_('COM_DJCLASSIFIEDS_RESERVE_PRICE_NOT_REACHED'); ?></div>
		<?php }
		} ?> 
		
		<div class="bids_list">
			<?php if($this->bids){ ?>
				<div class="bids_row bids_row_title">
					<div class="bids_col bids_col_name"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>:</div>
					<div class="bids_col bids_col_date"><?php echo JText::_('COM_DJCLASSIFIEDS_DATE'); ?>:</div>
					<div class="bids_col bids_col_bid"><?php echo JText::_('COM_DJCLASSIFIEDS_BID'); ?>:</div>					
					<div class="clear_both"></div>
				</div>
				<?php foreach($this->bids as $bid){ 
					if($bid->price>$min_bid){$min_bid = $bid->price;}
					if ($par->get('mask_bidder_name','0')== 1) {
						$bid->u_name = substr($bid->u_name, 0, 1).'.....'.substr($bid->u_name, -1, 1);
					}
					?> 
					<div class="bids_row">
						<div class="bids_col bids_col_name">
							<?php if($user->id==$item->user_id && $user->id>0){ ?>
								<a href="<?php echo JURI::base();?>index.php?option=com_djclassifieds&view=contact&id=<?php echo $item->id.'&bid='.$bid->id; ?>&tmpl=component" class="modal"  rel="{ handler:'iframe'}" ><?php echo $bid->u_name; ?></a>
							<?php }else{ 
								 echo $bid->u_name; 
							} ?>
						</div>
						<div class="bids_col bids_col_date"><?php echo DJClassifiedsTheme::formatDate(strtotime($bid->date)); ?></div>
						<div class="bids_col bids_col_bid">
							<?php echo DJClassifiedsTheme::priceFormat($bid->price,$item->currency);?>
							<?php if($user->id==$item->user_id){ 
								echo '<a class="bid_del_icon" title="'.JText::_('COM_DJCLASSIFIEDS_DELETE').'" href="index.php?option=com_djclassifieds&view=item&task=delBid&cid='.$item->cat_id.'&id='.$item->id.'&bid='.$bid->id.'&Itemid='.$Itemid.'" ></a>';	 
							}?>
						</div>
						<div class="clear_both"></div>
					</div>		
				<?php 
					if($bid->win){
						$bid_active = 0;
					}
				}?>			
			<?php }else{ ?>
				<div class="bids_row no_bids_row"><?php echo JText::_('COM_DJCLASSIFIEDS_NO_SUBMITTED_BIDS'); ?></div>	
			<?php }?>
			<div class="clear_both"></div>
		</div>
	</div>
	<div class="bids_form" id="djbids_form">
		<?php if($bid_active){
				if($user->id){					
					if($item->bid_min>0){
						$bid_v = $min_bid + $item->bid_min; 
					}else{
						$bid_v = $min_bid + 1;
					}
					?>
					<div class="bids_box">
						<div class="bids_info">
							<span class="bid_label"><?php echo JText::_('COM_DJCLASSIFIEDS_PLACE_BID'); ?></span>
							<?php if($item->bid_max){
								echo '<span class="bid_max">'.JText::_('COM_DJCLASSIFIEDS_MAX_BIDDING').' '.DJClassifiedsTheme::priceFormat($item->bid_max,$item->currency).'</span>';
							} ?>					
						</div>
						<div class="bids_input">
							<?php if ($par->get('unit_price_position','0')== 1) {
					        	echo ($item->currency) ? $item->currency : $par->get('unit_price');
							} ?>     	
							<input class="inputbox input-small" id="djbid_value" type="text" name="bid_max" id="bid_max" size="30" maxlength="250" value="<?php echo $bid_v; ?>" />
							<?php if ($par->get('unit_price_position','0')== 0) {
					        	echo ($item->currency) ? $item->currency : $par->get('unit_price');
							} ?>				
						</div>
						<div class="bids_button">
							<button class="button" id="bid_submit"><?php echo JText::_('COM_DJCLASSIFIEDS_PLACE_BID');?></button>
						</div>
						<div class="clear_both"></div>
					</div>
			<?php }else{ 
				$bid_active = 0;
				$uri = JFactory::getURI();?>
				<div class="bids_login_info"><a href="<?php echo JRoute::_('index.php?option=com_users&view=login&return='.base64_encode($uri))?>"><?php echo JText::_('COM_DJCLASSIFIEDS_LOGIN'); ?></a> <?php echo JText::_('COM_DJCLASSIFIEDS_TO_POST_BIDS'); ?></div>
			<?php } ?>
		<?php }else{ ?>
			<div class="bids_close_info"><?php echo JText::_('COM_DJCLASSIFIEDS_AUCTION_IS_CLOSED'); ?></div>
		<?php }?>
		<div class="clear_both"></div>
	</div>
	<div id="djbid_alert"></div>
	<div id="djbid_message"></div>
</div>
<?php if($bid_active){ ?>
	<script type="text/javascript">
		window.addEvent('load', function(){
			startBid();
		});

		function startBid(){
			if(document.id("bid_submit")){ 		
				document.id("bid_submit").addEvent('click',function(event){
							
				var bid_form = document.getElementById("djbids_form");
				var bid_box = document.getElementById("djauctions");
				var bid_value = document.getElementById("djbid_value").value;
				
							
				if(bid_value>0){
					var before = document.getElementById("djbids_form").innerHTML.trim();
					bid_form.innerHTML = '<div style="text-align:center;"><img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" /></div>';
					var url = '<?php echo JURI::base()?>index.php ?>';
						var myRequest = new Request({
							    url: '<?php echo JURI::base()?>index.php',
							    method: 'post',
								data: {
							      'option': 'com_djclassifieds',
							      'view': 'item',
							      'task': 'saveBid',
								  'id': '<?php echo $item->id;?>',
								  'bid': bid_value							  					  
								  },
							    onRequest: function(){
							        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
							    },
							    onSuccess: function(responseText){																
							    	bid_box.innerHTML = responseText;
							    	startBid();						 	
							    },
							    onFailure: function(){
							        myElement.set('html', 'Sorry, your request failed, please contact to administrator');
							    }
							});
							myRequest.send();	
				}else{
					document.id('djbid_alert').innerHTML="<?php echo str_ireplace('"',"'",JText::_('COM_DJCLASSIFIEDS_PLEASE_ENTER_BID_VALUE'));?>";
					document.id('djbid_alert').setStyle('display','block');
			      	(function() {
					    document.id('djbid_alert').setStyle('display','none');
					  }).delay(3000);   
				}
			});
		}
		
	}
	</script>
<?php } ?>