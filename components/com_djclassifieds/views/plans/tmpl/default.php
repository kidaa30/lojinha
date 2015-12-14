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
?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
<div class="pointspackages djcf_outer">
	<div class="title_top"><?php echo JText::_("COM_DJCLASSIFIEDS_PLANS"); ?></div>
	<div class="djcf_outer_in paymentdetails">
		<?php	
			$i = 0;					
			foreach($this->plans as $plan){					
				$registry = new JRegistry();
				$registry->loadString($plan->params);
				$plan_params = $registry->toObject();
				//echo '<pre>';print_r($plan_params);echo '</pre>';	
				?>
					<div class="djcf_prow"><div class="djcf_prow_in">								
						<div class="djcf_prow_col_desc">
							<h3><?php echo $plan->name; ?></h3>
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_ADVERTS_LIMIT");?>:</span>
								<span class="djcf_prow_desc_value" ><?php echo $plan_params->ad_limit; ?></span>
								<div class="clear_both"></div>
							</div>	
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_EXPIRATION_TIME");?>:</span>
								<span class="djcf_prow_desc_value" >
									<?php 
									if($plan_params->days_limit>0){
										echo $plan_params->days_limit.' '.JText::_("COM_DJCLASSIFIEDS_DAYS");
									}else{
										echo JText::_("COM_DJCLASSIFIEDS_UNLIMITED");
									}?>
								</span>
								<div class="clear_both"></div>
							</div>
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_PRICE");?>:</span>
								<span class="djcf_prow_desc_value" >
								<?php echo DJClassifiedsTheme::priceFormat($plan->price,$par->get('unit_price','')); ?>
									<?php if($plan->points>0 && $points_a){
										echo ' - '.$plan->points.' '.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT').'';		
									} ?>								
								</span>
								<div class="clear_both"></div>
							</div>
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_COST_PER_ADVERT");?>:</span>
								<span class="djcf_prow_desc_value" >
								<?php
								$price_per_ad = round($plan->price/$plan_params->ad_limit,2);
								echo DJClassifiedsTheme::priceFormat($price_per_ad,$par->get('unit_price','')); ?>
									<?php if($plan->points>0 && $points_a){
										$points_per_ad = round($plan->points/$plan_params->ad_limit,2);
										echo ' - '.$points_per_ad.' '.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT').'';		
									} ?>								
								</span>
								<div class="clear_both"></div>
							</div>
							<div class="djcf_prow_desc_row djcf_prow_main_desc">
								<?php echo $plan->description; ?>
							</div>							
							
							<h4 class="djcf_prow_details_title"><?php echo JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_DETAILS'); ?><span></span></h4>
							<div class="djcf_prow_details_content" >
								<div class="djcf_prow_desc_row">								
									<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_IMAGES_LIMIT");?>:</span>
									<span class="djcf_prow_desc_value" ><?php echo $plan_params->img_limit; ?></span>
									<div class="clear_both"></div>
								</div>
								<div class="djcf_prow_desc_row">								
									<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_ADVERT_DURATION");?>:</span>
									<span class="djcf_prow_desc_value" ><?php echo $this->durations[$plan_params->duration]->days.' '.JText::_("COM_DJCLASSIFIEDS_DAYS"); ?></span>
									<div class="clear_both"></div>
								</div>
								<?php if($par->get('show_video','0')){?>
									<div class="djcf_prow_desc_row">								
										<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_VIDEO");?>:</span>
										<span class="djcf_prow_desc_value" ><?php echo ($plan_params->video)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
										<div class="clear_both"></div>
									</div>
								<?php } ?>
								<?php if($par->get('show_website','0')){?>
									<div class="djcf_prow_desc_row">								
										<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_WEBSITE");?>:</span>
										<span class="djcf_prow_desc_value" ><?php echo ($plan_params->video)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
										<div class="clear_both"></div>
									</div>
								<?php } ?>									
								<?php if($par->get('buynow','0')){?>
									<div class="djcf_prow_desc_row">								
										<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_BUYNOW");?>:</span>
										<span class="djcf_prow_desc_value" ><?php echo ($plan_params->buynow)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
										<div class="clear_both"></div>
									</div>
								<?php } ?>				
								<?php if($par->get('auctions','0')){?>
									<div class="djcf_prow_desc_row">								
										<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_AUCTIONS");?>:</span>
										<span class="djcf_prow_desc_value" ><?php echo ($plan_params->auction)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
										<div class="clear_both"></div>
									</div>
								<?php } ?>
								<div class="djcf_prow_desc_row">								
									<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_PAID_CATEGORIES");?>:</span>
									<span class="djcf_prow_desc_value" ><?php echo ($plan_params->pay_categories)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
									<div class="clear_both"></div>
								</div>								
								<div class="djcf_prow_desc_row">								
									<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_USER_PROFILE_DETAILS");?>:</span>
									<span class="djcf_prow_desc_value" ><?php echo ($plan_params->user_profile_ad)? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
									<div class="clear_both"></div>
								</div>
								<?php if($par->get('promotion','1')=='1' && count($this->promotions)>0){
									if(!isset($plan_params->promotions)){ $plan_params->promotions=array(); }
									foreach($this->promotions as $promotion){ ?>
										<div class="djcf_prow_desc_row">								
											<span class="djcf_prow_desc_label" ><?php echo JText::_($promotion->label);?>:</span>
											<span class="djcf_prow_desc_value" ><?php echo (in_array($promotion->id, $plan_params->promotions))? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
											<div class="clear_both"></div>
										</div>
									<?php }													
								 } ?>	
							 </div>												
						</div>
						<div class="djcf_prow_col_buynow">							
							<a class="button" href="index.php?option=com_djclassifieds&view=payment&type=plan&id=<?php echo $plan->id?>" style="text-decoration:none;">
								<?php echo JText::_('COM_DJCLASSIFIEDS_BUY_NOW'); ?>
							</a>
						</div>
					</div></div>
				<?php
				$i++;
			}
		?>
	</div>
</div>
</div>
<script type="text/javascript">
window.addEvent('load', function() {

	var djcfplans = document.id('dj-classifieds').getElements('.djcf_prow_col_desc');

	djcfplans.each(function(djcfplan,index){
		new Fx.Slide(djcfplan.getElements('.djcf_prow_details_content')[0],{duration: 300}).toggle();
		djcfplan.getElements('.djcf_prow_details_title')[0].addEvent('click',function(event){
			event = new Event(event);						
			new Fx.Slide(djcfplan.getElements('.djcf_prow_details_content')[0],{duration: 300}).toggle();
			event.stop();
		});
	});
	
	/*var djcfpagebreak_acc = new Fx.Accordion('.djcf_prow .djcf_prow_details_title',
			'.djcf_prow .djcf_prow_details_content', {
				alwaysHide : false,
				display : -1,
				duration : 100,
				onActive : function(toggler, element) {
					toggler.addClass('active');
					element.addClass('in');
				},
				onBackground : function(toggler, element) {
					toggler.removeClass('active');
					element.removeClass('in');
				}
			});*/

	
});
</script>