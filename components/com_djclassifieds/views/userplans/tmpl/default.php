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

$menu_newad_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=additem',1);
$new_ad_link='index.php?option=com_djclassifieds&view=additem';
if($menu_newad_itemid){
	$new_ad_link .= '&Itemid='.$menu_newad_itemid->id;
}

?>
<div id="dj-classifieds" class="djcftheme-<?php echo $par->get('theme','default');?>">
<div class="pointspackages djcf_outer">
	<div class="title_top"><?php echo JText::_("COM_DJCLASSIFIEDS_YOUR_SUBSCRIPTION_PLANS"); ?></div>
	<div class="djcf_outer_in paymentdetails">
		<?php	
			$i = 0;					
			foreach($this->plans as $plan){					
				$registry = new JRegistry();
				$registry->loadString($plan->plan_params);
				$plan_params = $registry->toObject();
				if(!isset($plan_params->promotions)){
					$plan_params->promotions = array();
				}
				//echo '<pre>';print_r($plan);print_r($plan_params);echo '</pre>';	
				?>
					<div class="djcf_prow"><div class="djcf_prow_in">								
						<div class="djcf_prow_col_desc">
							<h3><?php echo $plan->plan_name; ?></h3>
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_ADVERTS_AVAILABLE");?>:</span>
								<span class="djcf_prow_desc_value" ><?php echo $plan->adverts_available; ?></span>
								<div class="clear_both"></div>
							</div>	
							<div class="djcf_prow_desc_row">								
								<span class="djcf_prow_desc_label" ><?php echo JText::_("COM_DJCLASSIFIEDS_EXPIRATION_TIME");?>:</span>
								<span class="djcf_prow_desc_value" >
									<?php 
									if($plan_params->days_limit>0){
										echo DJClassifiedsTheme::formatDate(strtotime($plan->date_exp));  
									}else{
										echo JText::_("COM_DJCLASSIFIEDS_UNLIMITED");
									}?>
								</span>
								<div class="clear_both"></div>
							</div>						
							<div class="djcf_prow_desc_row djcf_prow_main_desc">
								<?php echo $plan->plan_description; ?>
							</div>							
							
							<h4 class="djcf_prow_details_title"><?php echo JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_DETAILS'); ?><span></span></h4>
							<div class="djcf_prow_details_content">
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
									foreach($this->promotions as $promotion){ ?>
										<div class="djcf_prow_desc_row">								
											<span class="djcf_prow_desc_label" ><?php echo JText::_($promotion->label);?>:</span>
											<span class="djcf_prow_desc_value" ><?php echo (in_array($promotion->id, $plan_params->promotions))? '<span class="icon_active"></span>': '<span class="icon_unactive"></span>' ;; ?></span>
											<div class="clear_both"></div>
										</div>
									<?php }													
								 } ?>	
							 </div>	
							 <?php if(count($plan->items)){ ?>
							 	<h4 class="djcf_prow_details_title"><?php echo JText::_('COM_DJCLASSIFIEDS_ADS'); ?><span></span></h4>
								<div class="djcf_prow_details_content">
									<?php foreach($plan->items as $item){ ?>
								 		<div class="djcf_prow_desc_row">
								 			<?php if($item->i_name){
								 				echo '<a href="'.DJClassifiedsSEO::getItemRoute($item->id.':'.$item->i_alias,$item->cat_id.':'.$item->c_alias).'">'.$item->i_name.' ('.JText::_('COM_DJCLASSIFIEDS_AD_ID').': '.$item->id.')'.'</a>';
											 }else{
											 	echo $item->item_name.' ('.JText::_('COM_DJCLASSIFIEDS_AD_ID').': '.$item->id.')';
											 }?>
								 		</div>
									<?php }?>
								</div>								
							 <?php }?>
							 
							 
							 										
						</div>
						<div class="djcf_prow_col_buynow">							
							<a class="button" href="<?php echo JRoute::_($new_ad_link.'&subscr_id='.$plan->id); ?>" style="text-decoration:none;">
								<?php echo JText::_('COM_DJCLASSIFIEDS_NEW_AD'); ?>
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
	var djcfpagebreak_acc = new Fx.Accordion('.djcf_prow .djcf_prow_details_title',
			'.djcf_prow .djcf_prow_details_content', {
				alwaysHide : true,
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
			});
});
</script>	