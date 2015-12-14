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

//jimport('joomla.media.images');
JHTML::_('behavior.framework','More');
JHTML::_('behavior.keepalive');
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
JHTML::_('behavior.calendar');
$toolTipArray = array('className'=>'djcf_label');
//JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = JComponentHelper::getParams( 'com_djclassifieds' );

$imglimit = $par->get('img_limit','3');
$unit_price = $par->get('unit_price','');	
$id = JRequest::getVar('id', 0, '', 'int' );
$user = JFactory::getUser();

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

$document= JFactory::getDocument();
/*if($par->get('region_add_type','1')==1){
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false&language=".$par->get('region_lang','en'));
	$assets=JURI::base(true).'/components/com_djclassifieds/assets/';	
	$document->addScript($assets.'scripts.js');	
}*/
$points_a = $par->get('points',0);
?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $par->get('theme','default');?>">
	<?php 
		$modules_djcf = &JModuleHelper::getModules('djcf-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}		
	
		$modules_djcf = &JModuleHelper::getModules('djcf-renewitem-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-renew-item-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	

	?>	
	
<div class="dj-additem dj-renewitem clearfix" >
<form action="index.php" method="post" class="form-validate" name="djForm" id="djForm"  enctype="multipart/form-data">
        <div class="additem_djform">
        
		    <div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_ADVERT');?></div>
			<div class="additem_djform_in">
        	<center><img src='<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/long_loader.gif' alt='LOADING' style='display: none;' id='upload_loading' /><div id="alercik"></div></center>
            <?php    
            	$exp_days_list = $par->get('exp_days_list','');
				$exp_days = $par->get('exp_days','');
				if($this->item->exp_days==0){
					$this->item->exp_days = $exp_days;
				}
				if($par->get('durations_list','') && count($this->days)){
				//print_r($this->days);die();				
					?>
					
				<div class="djform_row">
		            	<label class="label" for="category" id="category-lbl" >
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY'); ?>					
		                </label>
		                <div class="djform_field djform_field_category">		                
		                <?php echo $this->category->name;
		                if($this->category->price>0){
		                	echo  ' ('.DJClassifiedsTheme::priceFormat(($this->category->price/100),$unit_price);
							if($this->category->points>0 && $points_a){
								echo ' - '.$this->category->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');		
							} 
							echo ')';
						}
		                ?>		                
		                </div>
		                <div class="clear_both"></div>
	            </div>	
					
	    		<div class="djform_row">
	                <?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" for="exp_days" id="exp_days-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_EXPIRE_AFTER_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRE_AFTER');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label" for="exp_days" id="exp_days-lbl" >
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRE_AFTER'); ?>					
		                </label>
	            	<?php } 
	            	?>
	                <select id="exp_days" name="exp_days">
					<?php 					
						foreach($this->days as $day){
							echo '<option value="'.$day->days.'"';	
								if($day->days==$this->item->exp_days){ echo ' SELECTED ';}							
							echo '>';
							
								if($day->days==1){
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAY');
								}else{
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAYS');	
								} 
								
								if($day->price_renew !='0.00'){	
									echo '&nbsp;-&nbsp;'.DJClassifiedsTheme::priceFormat($day->price_renew,$par->get('unit_price'));
								}
								if($day->points_renew>0 && $points_a){
									echo '&nbsp;-&nbsp;'.$day->points_renew.'&nbsp;'.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');	
								}							
							echo '</option>';
						}
					?>
					</select>
	                <div class="clear_both"></div>
	            </div>                
            <?php } ?>
            </div>
 		 </div>
 
 		 <?php 
			if($par->get('promotion','1')=='1' && count($this->promotions)>0){ ?>							
				<div class="prom_rows additem_djform">
				<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS');	?>
					<?php if(count($this->promotions)>1){ ?>
						<div class="promotions_info">
							<?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_EACH_PROMOTION_YOU_WISH_TO_USE')?>
						</div>
					<?php } ?>								
				</div>
				<div class="additem_djform_in">				
				<?php foreach($this->promotions as $prom){ ?>	
	    		<div class="djform_row">
	                <label class="label" >
	                	<?php 
	                		echo JText::_($prom->label).'<br /><span>'.JText::_('COM_DJCLASSIFIEDS_PRICE').'&nbsp;';
	                		echo DJClassifiedsTheme::priceFormat($prom->price,$par->get('unit_price'));
							if($points_a && $prom->points>0){
								echo '&nbsp-&nbsp'.$prom->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
							}
	                		echo '</span>';
	                	?>						
	                </label>
	                <div class="djform_field">
						<div class="djform_prom_v" >
							<div class="djform_prom_v_in" >
							<input type="radio" name="<?php echo $prom->name;?>" value="1" <?php  if(strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><label><?php echo JText::_('JYES'); ?></label>
							<input type="radio" name="<?php echo $prom->name;?>" value="0" <?php  if(!strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><label><?php echo JText::_('JNO'); ?></label>
							</div>
						</div>
						<div class="djform_prom_img" >							
							<div class="djform_prom_img_in" >
								<?php 
									$tip_content = '<img src=\''.JURI::base(true).'/components/com_djclassifieds/assets/images/'.$prom->name.'_h.png\' />'; 
									echo '<img class="Tips2" title="'.$tip_content.'" src="'.JURI::base(true).'/components/com_djclassifieds/assets/images/'.$prom->name.'.png" />';
								 ?>
							</div>
						</div>
						<div class="djform_prom_desc" >
							<div class="djform_prom_desc_in" >
							<?php echo JText::_($prom->description); ?>
							</div>
						</div>
							
	                </div>
	                <div class="clear_both"></div>
	            </div>
	            <?php } ?>
	            </div>
            </div>
		 <?php } ?>		
		<label id="verification_alert"  style="display:none;color:red;" />
			<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ALL_REQUIRED_FIELDS'); ?>
		</label>
     <div class="classifieds_buttons">
     	<?php if($user->id>0){
	     	$cancel_link = JRoute::_('index.php?option=com_djclassifieds&view=useritems&Itemid='.JRequest::getVar('Itemid','0'));
	     }else{
	     	$cancel_link = JRoute::_('index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.JRequest::getVar('Itemid','0'));
	     } 	     
	     ?>
	     <a class="button" href="<?php echo $cancel_link;?>"><?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL')?></a>
	     <button class="button validate" type="submit" id="submit_button"  ><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE'); ?></button>	     
		 <input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="id" value="<?php echo JRequest::getVar('id', 0, '', 'int' ); ?>" />
		<input type="hidden" name="view" value="renewitem" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid','0');?>" /> 
		<input type="hidden" name="boxchecked" value="0" />
	</div>
</form>
</div>
</div>
<script type="text/javascript">	
	




window.addEvent('domready', function(){ 
   var JTooltips = new Tips($$('.Tips1'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_label', fixed: true
   });
   var JTooltips = new Tips($$('.Tips2'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_prom', fixed: false
   });
   
   document.formvalidator.setHandler('djcat', function(value) {
      regex=/^p/;
      return !regex.test(value);
   });  
   
   document.id('submit_button').addEvent('click', function(){

        
      if(document.getElements('#djForm .invalid').length>0){
      	document.id('verification_alert').setStyle('display','block');
      	(function() {
		    document.id('verification_alert').setStyle('display','none');
		  }).delay(3000);      	
      	  return false;
      }else{
      	  return true;
      }             
	});
});

</script>