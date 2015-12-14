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

$app 	= JFactory::getApplication();
$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
$menus	= $app->getMenu('site');
$icon_col_w = $par->get('smallth_width','56')+20;

$r=TRUE;


?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
<div class="orders_history">
	<div class="title_top"><h1><?php echo JText::_("COM_DJCLASSIFIEDS_ORDERS_HISTORY"); ?></h1></div>
	<div class="dj-items-table2">
	<?php /*?>
		<div class="item_row item_header main_title">								
			<div class="item_col icon first" > </div>
			<div class="item_col name normal" style="text-align:left;"><?php echo JText::_('COM_DJCLASSIFIEDS_ADVERT') ?></div>
			<div class="item_col normal" style="text-align:center;"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE') ?></div>
			<div class="item_col normal" style="text-align:center;"><?php echo JText::_('COM_DJCLASSIFIEDS_SELLER') ?></div>				
		</div>	
		<?php */
		
		foreach($this->orders as $order){
			
			$row = $r==TRUE ? '0' : '1';
			$r=!$r;
			echo '<div class="item_row row'.$row.'">';
				
				if($order->i_name){
					echo '<div class="item_col icon first"  style="width:'.$icon_col_w.'px"  >';						
						echo '<a href="'.DJClassifiedsSEO::getItemRoute($order->item_id.':'.$order->i_alias,$order->cat_id.':'.$order->c_alias).'">';
						if(count($order->images)){
							echo '<img src="'.JURI::base().$order->images[0]->thumb_s.'"';
							echo ' alt ="'.str_ireplace('"', "'", $order->images[0]->caption).'" ';
							echo  '/>';
						}else{
							echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/no-image.png" ';						
							echo ' alt ="'.str_ireplace('"', "'", $order->i_name).'" ';
							echo '/>';
						}
						echo '</a>';
					echo '</div>';
					echo '<div class="item_col name">';					
						echo '<h3><a class="title" href="'.DJClassifiedsSEO::getItemRoute($order->item_id.':'.$order->i_alias,$order->cat_id.':'.$order->c_alias).'" >'.$order->i_name.'</a></h3>';
					echo '</div>';
				}else{
					echo '<div class="item_col icon first"  style="width:'.$icon_col_w.'px"  >';
						echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/no-image.png" ';
						echo ' alt ="'.str_ireplace('"', "'", $order->item_name).'" ';
						echo '/>';
					echo '</div>';
					echo '<div class="item_col name">';
						echo '<h3>'.$order->item_name.'</a></h3>';
					echo '</div>';
				} ?>
				<div class="item_col"> 
					<div class="djcf_prow_desc_row djcf_prow_price">
						<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_PRICE");?>:</span>
						<span class="djcf_prow_desc_value" ><?php echo DJClassifiedsTheme::priceFormat($order->price,$order->currency);?></span>
						<div class="clear_both"></div>
					</div>
					<div class="djcf_prow_desc_row djcf_prow_quantity">
						<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_QUANTITY");?>:</span>
						<span class="djcf_prow_desc_value" ><?php echo $order->quantity;?></span>
						<div class="clear_both"></div>
					</div>
					<div class="djcf_prow_desc_row djcf_prow_date">
						<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_ORDER_DATE");?>:</span>
						<span class="djcf_prow_desc_value" ><?php echo DJClassifiedsTheme::formatDate(strtotime($order->date));?></span>
						<div class="clear_both"></div>
					</div>
				</div>	
				<div class="item_col"> 
					<?php 
						$uid_slug = $order->user_id.':'.DJClassifiedsSEO::getAliasName($order->username);
						echo '<a class="profile_name" href="index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.DJClassifiedsSEO::getMainAdvertsItemid().'">'.$order->username;
							if($order->i_user_items_count){
								echo ' <span>('.$order->i_user_items_count.')</span>';
							}
						echo '</a>';
						echo '<div class="profile_email_outer"><a class="profile_email button" href="mailto:'.$order->email.'">'.JText::_('COM_DJCLASSIFIEDS_CONTACT_SELLER').'</a></div>';
					?>						
				</div>
				<?php 
			echo '</div>';
		}?>
	</div>
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?> 
	</div>	
</div>
</div>