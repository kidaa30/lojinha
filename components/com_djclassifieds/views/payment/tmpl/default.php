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
$points_a = $par->get('points',0);
$app = JFactory::getApplication();
$user = JFactory::getUser();
$menus	= $app->getMenu('site');
$menu_points = $menus->getItems('link','index.php?option=com_djclassifieds&view=points',1);
if($menu_points){
	$itemid = '&Itemid='.$menu_points->id;
}else{$itemid='';}

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
	<?php	$modules_djcf = &JModuleHelper::getModules('djcf-payment-top');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-payment-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	?>
		<?php
		if($this->item->user_id && $user->id){
			echo '<div class="payment_back_to_edit">';
				echo '<a class="back_to_edit" href="index.php?option=com_djclassifieds&view=additem&id='.$this->item->id.$itemid.'">'.JText::_('COM_DJCLASSIFIEDS_BACK_TO_EDITION').'</a>';
			echo '</div>';
		}else if($this->item->token && $par->get('guest_can_edit',0)==1){
			echo '<div class="payment_back_to_edit">';
				echo '<a class="back_to_edit" href="index.php?option=com_djclassifieds&view=additem&id=0&token='.$this->item->token.$itemid.'">'.JText::_('COM_DJCLASSIFIEDS_BACK_TO_EDITION').'</a>';
			echo '</div>';
		}
		
		?>
	<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails first">
		<tr>
			<td class="td_title">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_DETAILS');?></h2>
			</td>
		</tr>
		<tr>
			<td class="td_pdetails">
				<?php 
					$p_count =0;
					$p_total=0;
					$points_total=0;
					if(strstr($this->item->pay_type, 'cat')){
						$c_price = $this->item->c_price/100;
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_CATEGORY').'</span><span class="price">'.DJClassifiedsTheme::priceFormat($c_price,$par->get('unit_price',''));
						if($points_a && $this->item->c_points){
							echo ' / '.$this->item->c_points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
						}
						echo '</span></div>';
						$p_total+=$c_price;
						$points_total+=$this->item->c_points;
						$p_count++;
					}													
					if(strstr($this->item->pay_type, 'duration')){
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_DURATION').' ';
							if($this->item->exp_days==0){
								echo JText::_('COM_DJCLASSIFIEDS_UNLIMITED');
							}else if($this->item->exp_days==1){
								echo $this->item->exp_days.' '.JText::_('COM_DJCLASSIFIEDS_DAY');
							}else{
								echo $this->item->exp_days.' '.JText::_('COM_DJCLASSIFIEDS_DAYS');
							}
							if(strstr($this->item->pay_type, 'duration_renew')){								
								echo '</span><span class="price">'.DJClassifiedsTheme::priceFormat($this->duration->price_renew,$par->get('unit_price',''));
								if($points_a && $this->duration->points_renew){
									echo ' / '.$this->duration->points_renew.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
								}
								echo '</span></div>';
								$p_total+=$this->duration->price_renew;
								$points_total+=$this->duration->points_renew;		
							}else{
								echo '</span><span class="price">'.DJClassifiedsTheme::priceFormat($this->duration->price,$par->get('unit_price',''));
								if($points_a && $this->duration->points){
									echo ' / '.$this->duration->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
								}
								echo '</span></div>';
								$p_total+=$this->duration->price;
								$points_total+=$this->duration->points;
							}						
						
						$p_count++;			
					}

					foreach($this->promotions as $prom){
						if(strstr($this->item->pay_type, $prom->name)){
							echo '<div class="pd_row"><span>'.JText::_($prom->label).'</span>';
							echo '<span class="price">'.DJClassifiedsTheme::priceFormat($prom->price,$par->get('unit_price',''));
								if($points_a && $prom->points){
									echo ' / '.$prom->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
								}
							echo '</span></div>';
							$p_total+=$prom->price;
							$points_total+=$prom->points;
							$p_count++;			
						}	
					}
					
					if(strstr($this->item->pay_type, 'extra_img')){
						$extraimg = $this->item->extra_images_to_pay;
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_ADDITIONAL_IMAGES').' '.$this->item->extra_images_to_pay.' ';
							if(strstr($this->item->pay_type, 'extra_img_renew')){
								$img_price	= $par->get('img_price_renew','0');
								$img_points	= $par->get('img_price_renew_points','0');
								if(isset($this->duration->img_price_default)){
									if($this->duration->img_price_default==0){
										$img_price = $this->duration->img_price_renew;
										$img_points = $this->duration->img_points_renew;
									}	
								}
																							
							}else{
								$img_price	= $par->get('img_price','0');
								$img_points	= $par->get('img_price_points','0');
								if(isset($this->duration->img_price_default)){
									if($this->duration->img_price_default==0){
										$img_price = $this->duration->img_price;
										$img_points = $this->duration->img_points;
									}	
								}
								
							}

							
							echo '</span><span class="price">'.DJClassifiedsTheme::priceFormat($img_price*$extraimg,$par->get('unit_price',''));
							if($points_a && $img_points){
								echo ' / '.$img_points*$extraimg.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
							}
							echo '</span></div>';
							$p_total+=$img_price*$extraimg;
							$points_total+=$img_points*$extraimg;							
						
						$p_count++;			
					}
					
					if(strstr($this->item->pay_type, 'extra_chars')){
						$extrachar = $this->item->extra_chars_to_pay;
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_ADDITIONAL_CHARS').' '.$this->item->extra_chars_to_pay.' ';
							if(strstr($this->item->pay_type, 'extra_chars_renew')){
								if($this->duration->char_price_default==0){																	
									$char_price = $this->duration->char_price_renew;
									$char_points = $this->duration->char_points_renew;
								}else{
									$char_price	= $par->get('desc_char_price_renew','0');
									$char_points	= $par->get('desc_char_price_renew_points','0');
								}																
							}else{
								if($this->duration->char_price_default==0){																	
									$char_price = $this->duration->char_price;
									$char_points = $this->duration->char_points;
								}else{
									$char_price	= $par->get('desc_char_price','0');
									$char_points	= $par->get('desc_char_price_points','0');
								}
							}

							
							echo '</span><span class="price">'.DJClassifiedsTheme::priceFormat($char_price*$extrachar,$par->get('unit_price',''));
							if($points_a && $char_points){
								echo ' / '.$char_points*$extrachar.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
							}
							echo '</span></div>';
							$p_total+=$char_price*$extrachar;
							$points_total+=$char_points*$extrachar;							
						
						$p_count++;
					}					
					
					if($p_count>1 || $par->get('vat_value','-1')>-1){
						if($par->get('vat_value','-1')>-1){
							$p_net = round($p_total/(1+($par->get('vat_value','-1')*0.01)),2);
							echo '<div class="pd_row_net"><span>'.JText::_('COM_DJCLASSIFIEDS_NET_COST').'</span>';
								echo '<span class="price">'.DJClassifiedsTheme::priceFormat($p_net,$par->get('unit_price','')).'</span>';							
							echo '</div>';
							echo '<div class="pd_row_tax"><span>'.JText::_('COM_DJCLASSIFIEDS_TAX').' ('.$par->get('vat_value','-1').'%)</span>';
								echo '<span class="price">'.DJClassifiedsTheme::priceFormat($p_total-$p_net,$par->get('unit_price','')).'</span>';
							echo '</div>';
						}
						$points_total = round($points_total);
						echo '<div class="pd_row_total"><span>'.JText::_('COM_DJCLASSIFIEDS_TOTAL').'</span>';
						echo '<span class="price">'.DJClassifiedsTheme::priceFormat($p_total,$par->get('unit_price',''));
							if($points_a && $points_total){
								echo ' / '.$points_total.' '.JTEXT::_('COM_DJCLASSIFIEDS_POINTS');
							}
						echo '</span></div>';
					}
				?>
			</td>
		</tr>			
	</table>
	<?php	$modules_djcf = &JModuleHelper::getModules('djcf-payment-middle');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-payment-middle clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	?>
	<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails">
		<tr>
			<td class="td_title">
				<h2><?php echo JText::_("COM_DJCLASSIFIEDS_PAYMENT_METHODS"); ?></h2>
			</td>
		</tr>
		<tr>
			<td class="table_payment">
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<?php
						if($points_a && $points_total){ ?>
							<tr>
								<td class="payment_td">
									<table width="100%" cellspacing="0" cellpadding="5" border="0">
										<tr>
											<td width="160" align="center" class="td1">
												<img title="<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS')?>" src="<?php echo JURI::base();?>components/com_djclassifieds/assets/images/points.png">
											</td>
											<td class="td2">
												<h2><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')?></h2>
												<p style="text-align:left;"><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_AVAILABLE').': '.$this->user_points;?></p>
											</td>
											<td width="130" align="center" class="td3">
												<?php 
												if($this->user_points>=$points_total){ 
													echo '<a class="button" href="index.php?option=com_djclassifieds&view=payment&task=payPoints&id='.$this->item->id.'" style="text-decoration:none;">'.JText::_('COM_DJCLASSIFIEDS_USE_POINTS').'</a>';	
												}else{ 
													echo '<a target="_blank" class="button" href="'.JRoute::_('index.php?option=com_djclassifieds&view=points'.$itemid).'" style="text-decoration:none;">'.JText::_('COM_DJCLASSIFIEDS_BUY_POINTS').'</a>';	
												} ?>
												
											</td>
									</tr>
									</table>
								</td>
							</tr>
						<?php }
						$i = 0;					
						foreach($this->PaymentMethodDetails AS $pminfo)
						{
							if($pminfo==''){
								continue;
							}
							//$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->plugin_info[$i]->name."/images/".$pminfo["logo"];
							?>
								<tr>
									<td class="payment_td">
										<?php echo $pminfo; ?>
									</td>
								</tr>
							<?php
							$i++;
						}
					?>
				</table>
			</td>
		</tr>
	</table>
		<?php	$modules_djcf = &JModuleHelper::getModules('djcf-payment-bottom');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-payment-bottom clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	?>
</div>