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
JHTML::_('behavior.framework');
JHTML::_('behavior.formvalidation');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$config = JFactory::getConfig();
$user =  JFactory::getUser();
$document= JFactory::getDocument();
$session = JFactory::getSession();
require_once(JPATH_COMPONENT.DS.'assets'.DS.'recaptchalib.php');
$captcha_type = $par->get('captcha_type','recaptcha');
$publickey = $par->get('captcha_publickey',"6LfzhgkAAAAAAL9RlsE0x-hR2H43IgOFfrt0BxI0");
$privatekey = $par->get('captcha_privatekey',"6LfzhgkAAAAAAOJNzAjPz3vXlX-Bw0l-sqDgipgs");
if($captcha_type=='nocaptcha'){
	$document= JFactory::getDocument();
	$document->addScript("https://www.google.com/recaptcha/api.js");
}
$error='';
$Itemid = JRequest::getVar('Itemid', 0,'', 'int');
$item = $this->item;

if($item->published==2){
	$par->set('ask_seller',0);
}

	
	if(!count($this->item_images) && $par->get('ad_image_default','0')==0 ){ $gd_class=' no_images';
	}else{ $gd_class=''; }
	
	echo '<div class="general_det'.$gd_class.'"  ><div class="general_det_in">'; 
	if($item->price || $item->price_negotiable){ ?>
		<div class="row_gd">
			<div class="price_wrap">
				<?php if($item->price){?>
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE'); ?>:</span>
				<span class="row_value">
					<?php echo DJClassifiedsTheme::priceFormat($item->price,$item->currency);?>
				</span>
				<?php
					}
					if($item->price_negotiable){ ?>		
					<span class="row_negotiable">
						<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_IS_NEGOTIABLE'); ?>
					</span>
				<?php } ?>
				<?php if($par->get('buynow','0') && $item->buynow){
					 echo $this->loadTemplate('buynow');
				} ?>
				<?php if($par->get('auctions_price_link',0)==1 && $par->get('auctions','0') && $item->auction){ ?>
					<a href="#djauctions" class="auctions_link">
						<?php echo JText::_('COM_DJCLASSIFIEDS_CURRENT_BIDS'); ?>
					</a> 
				<?php } ?>												
				<div class="clear_both"></div>								
			</div>
		</div>
		<?php } ?>
		
		<?php			
		if($par->get('auctions','0') && $item->auction && $par->get('auctions_position','top')=='top'){
			echo  $this->loadTemplate('auctions');
		} ?>
		
		<?php					
		if(($par->get('show_contact','1') && $item->contact) || ($par->get('show_website','1') && $item->website) || count($this->fields_contact)){?>
		<div class="row_gd djcf_contact">
			<div class="contact_mainrow">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT'); ?></span>
				<span class="row_value"><?php 
				if($par->get('show_contact_only_registered',0)==1 && $user->id==0){
					$uri = JFactory::getURI();
					echo '<a href="index.php?option=com_users&view=login&return='.base64_encode($uri).'">'.JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN_TO_SEE_CONTACT').'</a>';
				}else{
					echo $item->contact;
					if($par->get('show_website','1') && $item->website){
						if($item->contact){
							echo '<br />';		
						}				
						echo '<a target="_blank" ';
						if($par->get('website_nofallow','1')){
							echo ' rel="nofollow" ';
						}
						echo 'href="';
						if(strstr($item->website, 'http://') || strstr($item->website, 'https://')){
							echo $item->website;
						}else{
							echo 'http://'.$item->website;
						}
						echo '">'.$item->website.'</a>';
					}
				}			
				 ?></span>
			 </div>
			 
			<?php if(count($this->fields_contact)>0){?>
					<?php 
					//echo '<pre>';print_r($this->fields);die(); 
					
					foreach($this->fields_contact as $f){							
						if($par->get('show_empty_cf','1')==0){
							if(!$f->value && ($f->value_date=='' || $f->value_date=='0000-00-00')){
								continue;
							}
						}
						if($f->source!=1){continue;}
						$tel_tag = '';
						if(strstr($f->name, 'tel')){
							$tel_tag='tel:'.$f->value;
						}
						?>
						<div class="contact_row row_<?php echo $f->name;?>">
							<span class="row_label"><?php echo JText::_($f->label); ?>:</span>
							<span class="row_value<?php if($f->hide_on_start){echo ' djsvoc" title="'.htmlentities($f->value); }?>" rel="<?php echo $tel_tag; ?>" >
								<?php 
								if($f->type=='textarea'){							
									if($f->value==''){echo '---'; }
									else{echo $f->value;}								
								}else if($f->type=='checkbox'){
									if($f->value==''){echo '---'; }
									else{
										echo str_ireplace(';', ', ', substr($f->value,1,-1));
									}
								}else if($f->type=='date'){
									if($f->value_date==''){echo '---'; }
									else{
										echo $f->value_date;
									}
								}else if($f->type=='link'){
									if($f->value==''){echo '---'; }
									else{
										if(strstr($f->value, 'http://') || strstr($f->value, 'https://')){
											echo '<a '.$f->params.' href="'.$f->value.'">'.str_ireplace(array("http://","https://"), array('',''), $f->value).'</a>';;
										}else{
											echo '<a '.$f->params.' href="http://'.$f->value.'">'.$f->value.'</a>';;
										}																
									}							
								}else{
									if($par->get('cf_values_to_labels','0')){
										echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($f->value)));
									}else{		
										if($f->hide_on_start){
											echo substr($f->value, 0,2).'..........<a href="javascript:void(0)" class="djsvoc_link">'.JText::_('COM_DJCLASSIFIEDS_SHOW').'</a>';
										}else{
											if($tel_tag){
												echo '<a href="'.$tel_tag.'">'.$f->value.'</a>';
											}else{
												echo $f->value;
											}											
										}
									}
								}
								?>
							</span>
						</div>		
					<?php
					} ?>
			<?php }?>
						 			 			 
		</div>
		<?php } ?>
	<?php if($par->get('show_ad_added_date','1')==1){?>
			<div class="row_gd added">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_ADDED'); ?></span>
				<span class="row_value">
					<?php echo DJClassifiedsTheme::formatDate(strtotime($item->date_start),'',$par->get('date_format_type_item',0)); ?>
				</span>
			</div>
		<?php } ?>
			<?php if($item->date_mod!='0000-00-00 00:00:00'){?>
				<div class="row_gd">
					<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_MODIFIED'); ?>:</span>
					<span class="row_value">
						<?php echo DJClassifiedsTheme::formatDate(strtotime($item->date_mod),'',$par->get('date_format_type_item',0)); ?>
					</span>
				</div>
			<?php  }
		
		if($par->get('showauthor','1')==1){
			echo  $this->loadTemplate('profile');
		}
		/*
		 <div class="row">
		<span><?php echo JText::_('Email:'); ?></span><?php echo $item->email; ?>
		</div>
		*/?>
<?php
			if($par->get('ask_seller_position',0)==1){
				//general details end
			 	echo '</div></div>'; 	
			 }
			 echo '<div class="clear_both"></div>';
						
			if($par->get('ask_seller','0')==1 || ($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!='')){ ?>
				<div class="ask_form_abuse_outer">
			<?php }			 
			if($par->get('ask_seller','0')==1 && ($item->user_id>0 || $item->email)){?>
				<button id="ask_form_button" class="button" type="button" ><?php echo JText::_('COM_DJCLASSIFIEDS_ASK_SELLER'); ?></button>
			<?php }
			if($par->get('abuse_reporting','0')==1){?>
				<button id="abuse_form_button" class="button" type="button" ><?php echo JText::_('COM_DJCLASSIFIEDS_REPORT_ABUSE'); ?></button>
			<?php } ?>
		 <div class="clear_both"></div>
		<?php if($par->get('ask_seller','0')==1 && ($item->user_id>0 || $item->email) && (($par->get('ask_seller_type','0')==1) || ($par->get('ask_seller_type','0')==0 && $user->id>0))){?>
			<div id="ask_form" class="af_hidden" style="display:none;overflow:hidden;">
			<form action="index.php" method="post" name="djForm" id="djForm" class="form-validate" enctype="multipart/form-data" >
				<?php if($par->get('ask_seller_type','0')==0 || $user->id>0){?>
			   		<label for="ask_name" id="ask_name-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_NAME'); ?></label>
			   		<input type="text" class="inputbox required" value="<?php echo $user->name; ?>" name="ask_name" id="ask_name" />
			   		<label for="ask_email" id="ask_email-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_EMAIL'); ?></label>
			   		<input type="text" class="inputbox required validate-email" value="<?php echo $user->email; ?>" name="ask_email" id="ask_email" />
			   		<?php echo $this->loadTemplate('askformfields'); ?>
			   		<label for="ask_message" id="ask_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label>
			   		<textarea id="ask_message" name="ask_message" rows="5" cols="55" class="inputbox required"></textarea>
			   		<?php if($par->get('ask_seller_file','0')==1){ ?>
				   		<label for="ask_file" id="ask_file-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_ATTACHMENT'); ?> <span>(<?php echo $par->get('ask_seller_file_size','2').'MB - '.$par->get('ask_seller_file_types','doc,pdf,zip'); ?>)</span></label>
				   		<input type="file" class="inputbox" value="" name="ask_file" id="ask_file" />	
			   		<?php } ?> 		   			   		
			   	<?php }else{		   						
					?>
					<label for="ask_name" id="ask_name-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_NAME'); ?></label>
			   		<input type="text" class="inputbox required" value="<?php echo $session->get('askform_name',''); ?>" name="ask_name" id="ask_name" />
			   		<label for="ask_email" id="ask_email-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_EMAIL'); ?></label>
			   		<input type="text" class="inputbox required validate-email" value="<?php echo $session->get('askform_email',''); ?>" name="ask_email" id="ask_email" />	   			   		
			   		<?php echo $this->loadTemplate('askformfields'); ?>
			   		<label for="ask_message" id="ask_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label>
			   		<textarea id="ask_message" name="ask_message" rows="5" cols="55" class="inputbox required"><?php echo $session->get('askform_message',''); ?></textarea>			   		
			   		<?php if($par->get('ask_seller_file','0')==1){ ?>
				   		<label for="ask_file" id="ask_file-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_ATTACHMENT'); ?> <span>(<?php echo $par->get('ask_seller_file_size','2').'MB - '.$par->get('ask_seller_file_types','doc,pdf,zip'); ?>)</span></label>
				   		<input type="file" class="inputbox" value="" name="ask_file" id="ask_file" />	
			   		<?php } ?>
			   		<script type="text/javascript">
					 	var RecaptchaOptions = {
					    	theme : '<?php echo $par->get('captcha_theme','red'); ?>'
					 	};
					</script>
					<?php	
						if($captcha_type=='recaptcha'){
							if($config->get('force_ssl',0)==2){
								echo recaptcha_get_html($publickey, $error,true);
							}else{
								echo recaptcha_get_html($publickey, $error);
							}						 
						}else if($captcha_type=='nocaptcha'){ ?>
							<div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
				  		<?php }
				   	}?>			   		
			   <div class="clear_both"></div>		
			   <button class="button validate" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_SEND'); ?></button>
			   <input type="hidden" name="ask_status" id="ask_status" value="0" />
			   <input type="hidden" name="item_id" id="item_id" value="<?php echo $item->id; ?>">
			   <input type="hidden" name="cid" id="cid" value="<?php echo $item->cat_id; ?>">
			   <input type="hidden" name="option" value="com_djclassifieds" />
			   <input type="hidden" name="view" value="item" />
			   <input type="hidden" name="task" value="ask" />
			   <div class="clear_both"></div>
			</form> 	 
			</div>
		<?php } ?>

		<?php if($par->get('abuse_reporting','0')==1 && $user->id>0){?>
			<div id="abuse_form" style="display:none;overflow:hidden;">
			<form action="index.php" method="post" name="djabuseForm" id="djabuseForm" class="form-validate">
			   <label for="abuse_message" id="abuse_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label><br />
			   <textarea id="abuse_message" name="abuse_message" rows="5" cols="55" class="inputbox required"></textarea><br />
			   <button class="button" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_SEND'); ?></button>
			   <input type="hidden" name="abuse_status" id="abuse_status" value="0" />
			   <input type="hidden" name="item_id" id="item_id" value="<?php echo $item->id; ?>">
			   <input type="hidden" name="cid" id="cid" value="<?php echo $item->cat_id; ?>">
			   <input type="hidden" name="option" value="com_djclassifieds" />
			   <input type="hidden" name="view" value="item" />
			   <input type="hidden" name="task" value="abuse" />
			</form> 	 
			</div>
		<?php } ?>
		<?php if($par->get('ask_seller','0')==1 || ($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!='')){ ?>
				</div>
		<?php }	?>
			<div class="clear_both"></div>			
		<?php
			 //general details end
			 if($par->get('ask_seller_position',0)==0){
			 	echo '</div></div>'; 	
			 }
		?>			
		
<script type="text/javascript">

window.addEvent('load', function(){	
	<?php  
		if($par->get('ask_seller','0')==1 && ($item->user_id>0 || $item->email)){
			if($par->get('ask_seller_type','0')==1 || ($user->id>0 && $par->get('ask_seller_type','0')==0)){ ?>
				if (document.id('ask_form_button') && document.id('ask_form')) {
					document.id('ask_form').setStyle('display','block');
					var ask_form_slide = new Fx.Slide('ask_form');				
					document.id('ask_form_button').addEvent('click', function(e){						
						e.stop();
						ask_form_slide.toggle();						
						return false;
					});				
					ask_form_slide.hide();
				}
			
		<?php }else{?>	
				document.id('ask_form_button').addEvent('click', function(){
					alert('<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');?>');						
				});
		<?php }
		  }?>
	
	<?php if($par->get('abuse_reporting','0')==1){
		if($user->id>0){ 
		?>
			if (document.id('abuse_form_button') && document.id('abuse_form')) {
				document.id('abuse_form').setStyle('display','block');
				var formTogglerFx2 = new Fx.Tween('abuse_form' ,{duration: 300});
				var formTogglerHeight2 = document.id('abuse_form').getSize().y;
				
				document.id('abuse_form_button').addEvent('click', function(){
					if(document.id('abuse_form').getStyle('height').toInt() > 0){
						formTogglerFx2.start('height',0);
					}else{
						formTogglerFx2.start('height',formTogglerHeight2);
						
					}
				return false;
				});
			
				document.id('abuse_form').setStyle('height',0);
			}
		
	<?php }else{?>
			document.id('abuse_form_button').addEvent('click', function(){
				alert('<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');?>');						
			});
	<?php }
		}?>
		
	<?php  if(JRequest::getInt('ae',0)){ ?>
			document.id('ask_form_button').fireEvent('click');	
	<?php	}?>
	
});
</script>		