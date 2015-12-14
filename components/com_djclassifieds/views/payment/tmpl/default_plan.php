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
$points_total= $this->plan->points;
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
	<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails first">
		<tr>
			<td class="td_title">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_DETAILS');?></h2>
			</td>
		</tr>
		<tr>
			<td class="td_pdetails">
				<?php 																												
					echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_PLAN').':<br />'.$this->plan->name.'</span>';
					echo '<span class="price">'.DJClassifiedsTheme::priceFormat($this->plan->price,$par->get('unit_price','EUR'));
						 if($this->plan->points && $points_a){
							echo '&nbsp-&nbsp'.$this->plan->points.' '.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
						 }
					echo '</span></div>';

					if($par->get('vat_value','-1')>-1){
						$p_net = round($this->plan->price/(1+($par->get('vat_value','-1')*0.01)),2);
						echo '<div class="pd_row_net"><span>'.JText::_('COM_DJCLASSIFIEDS_NET_COST').'</span>';
							echo '<span class="price">'.DJClassifiedsTheme::priceFormat($p_net,$par->get('unit_price','')).'</span>';
						echo '</div>';
						echo '<div class="pd_row_tax"><span>'.JText::_('COM_DJCLASSIFIEDS_TAX').' ('.$par->get('vat_value','-1').'%)</span>';
							echo '<span class="price">'.DJClassifiedsTheme::priceFormat($this->plan->price-$p_net,$par->get('unit_price','')).'</span>';
						echo '</div>';
					}
					
					echo '<div class="pd_row_total"><span>'.JText::_('COM_DJCLASSIFIEDS_PRICE').'</span>';
					echo '<span class="price">'.DJClassifiedsTheme::priceFormat($this->plan->price,$par->get('unit_price','EUR'));
						 if($this->plan->points && $points_a){
							echo '&nbsp-&nbsp'.$this->plan->points.' '.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
						 }
					echo '</span></div>';
					
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
												<img title="<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS')?>" src="components/com_djclassifieds/assets/images/points.png">
											</td>
											<td class="td2">
												<h2><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')?></h2>
												<p style="text-align:left;"><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_AVAILABLE').': '.$this->user_points;?></p>
											</td>
											<td width="130" align="center" class="td3">
												<?php 
												if($this->user_points>=$points_total){ 
													echo '<a class="button" href="index.php?option=com_djclassifieds&view=payment&task=payPoints&id='.$this->plan->id.'&type=plan" style="text-decoration:none;">'.JText::_('COM_DJCLASSIFIEDS_USE_POINTS').'</a>';	
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