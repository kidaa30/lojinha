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
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);


$par 	    = JComponentHelper::getParams( 'com_djclassifieds' );
$app 	    = JFactory::getApplication();
$user 		= JFactory::getUser();
$document	= JFactory::getDocument();
$config 	= JFactory::getConfig();
$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

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
	
		$modules_djcf = &JModuleHelper::getModules('djcf-registration-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	

	?>	
<div class="djcf_registration_outer" >	
	<div class="dj-additem clearfix" >
	<form action="index.php" method="post" class="form-validate" name="djForm" id="djForm"  enctype="multipart/form-data">
        <div class="additem_djform">
        	<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_REGISTRATION'); ?></div>
			<div class="additem_djform_in">        	            		               			           	            	
            	<div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_name-lbl" for="jform_name" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_REGISTER_NAME_DESC')?>">
		                    <?php echo JText::_('COM_USERS_REGISTER_NAME_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_name-lbl" for="jform_name" class="label"><?php echo JText::_('COM_USERS_REGISTER_NAME_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required" type="text" name="jform[name]" id="jform_name" size="50" maxlength="250" value="" />
	                    <span id="jform_name_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>
	                </div>
	                <div class="clear_both"></div> 
	                    <div id="jform_name_info" class="reg_info"></div>
	                <div class="clear_both"></div> 
	            </div>    

            	<div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_username-lbl" for="jform_username" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_DESIRED_USERNAME')?>">
		                    <?php echo JText::_('COM_USERS_REGISTER_USERNAME_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_username-lbl" for="jform_username" class="label"><?php echo JText::_('COM_USERS_REGISTER_USERNAME_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required" type="text" name="jform[username]" id="jform_username" size="50" maxlength="250" value="" />
	                    <span id="jform_username_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>             	    	
	                </div>
	                <div class="clear_both"></div>
	                	<div id="jform_username_info" class="reg_info"></div>
	                <div class="clear_both"></div> 
	            </div>    	
	            
	            <div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_password1-lbl" for="jform_password1" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_DESIRED_PASSWORD')?>">
		                    <?php echo JText::_('COM_USERS_PROFILE_PASSWORD1_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_password1-lbl" for="jform_password1" class="label"><?php echo JText::_('COM_USERS_PROFILE_PASSWORD1_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required validate-password" type="password" name="jform[password1]" id="jform_password1" size="50" maxlength="250" value="" />
	                    <span id="jform_password1_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>             	    	
	                </div>
	                <div class="clear_both"></div>
	                	<div id="jform_password1_info" class="reg_info"></div>
	                <div class="clear_both"></div> 
	            </div> 	            
	            
			    <div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_password2-lbl" for="jform_password2" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_PROFILE_PASSWORD2_DESC')?>">
		                    <?php echo JText::_('COM_USERS_PROFILE_PASSWORD2_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_password2-lbl" for="jform_password2" class="label"><?php echo JText::_('COM_USERS_PROFILE_PASSWORD2_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required validate-password" type="password" name="jform[password2]" id="jform_password2" size="50" maxlength="250" value="" />
	                    <span id="jform_password2_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>
	                </div>
	                <div class="clear_both"></div> 
             	    	<div id="jform_password2_info" class="reg_info"></div>	                
	                <div class="clear_both"></div> 
	            </div> 	            
	            
			    <div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_email1-lbl" for="jform_email1" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_REGISTER_EMAIL1_DESC')?>">
		                    <?php echo JText::_('COM_USERS_REGISTER_EMAIL1_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_email1-lbl" for="jform_email1" class="label"><?php echo JText::_('COM_USERS_REGISTER_EMAIL1_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required validate-email" type="email" name="jform[email1]" id="jform_email1" size="50" maxlength="250" value="" />
	                    <span id="jform_email1_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>             	    	
	                </div>
	                <div class="clear_both"></div> 
	                	<div id="jform_email1_info" class="reg_info"></div>
	                <div class="clear_both"></div>
	            </div> 	 		            
            
			    <div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label id="jform_email2-lbl" for="jform_email2" class="label Tips1" title="<?php echo JTEXT::_('COM_USERS_REGISTER_EMAIL2_DESC')?>">
		                    <?php echo JText::_('COM_USERS_REGISTER_EMAIL2_LABEL');?> * <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label id="jform_email2-lbl" for="jform_email2" class="label"><?php echo JText::_('COM_USERS_REGISTER_EMAIL2_LABEL'); ?> *</label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area required validate-email" type="email" name="jform[email2]" id="jform_email2" size="50" maxlength="250" value="" />
	                    <span id="jform_email2_loader" class="reg_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>
	                </div>
	                <div class="clear_both"></div> 
             	    	<div id="jform_email2_info" class="reg_info"></div>	                
	                <div class="clear_both"></div> 
	            </div> 	 
	            
	            <?php 
	            if(count($this->extra_rows)){
					foreach($this->extra_rows as $plugin_row){
						echo $plugin_row;
					}
				} ?>
	            				    				                    
			 </div>
 		 </div>
 		    
	 	<?php if(count($this->custom_contact_fields)){?>
	 		 <div class="additem_djform profile_fields">
	        	<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_PROFILE'); ?></div>
				<div class="additem_djform_in">        	    
				        <?php echo  $this->loadTemplate('contactfields'); ?> 		
	 		 	</div>
	 		 </div>
	 	 <?php } ?>

 	
		<label id="verification_alert"  style="display:none;color:red;" >
			<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ALL_REQUIRED_FIELDS'); ?>
		</label>
     <div class="classifieds_buttons">     	
	    <button class="button validate" type="submit" id="submit_button"  ><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE'); ?></button>	     
		<input type="hidden" name="option" value="com_djclassifieds" />
		<input type="hidden" name="view" value="registration" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="Itemid" value="<?php echo  JRequest::getInt('Itemid'); ?>" />
	</div>
</form>
</div></div>
</div>
<script type="text/javascript">	

	window.addEvent('domready', function(){ 
		document.id('jform_username').addEvent('change', function(){checkUsername(this.value);});
		document.id('jform_password1').addEvent('change', function(){checkPassword();});
		document.id('jform_password2').addEvent('change', function(){checkPasswordEqual(this.value);});
		document.id('jform_email1').addEvent('change', function(){checkEmail(this.value);});
		document.id('jform_email2').addEvent('change', function(){checkEmailEqual(this.value);});

		 var djcals = document.getElements('.djcalendar');
			if(djcals){
				var startDate = new Date(2008, 8, 7);
				djcals.each(function(djcla,index){
					Calendar.setup({
			            inputField  : djcla.id,
			            ifFormat    : "%Y-%m-%d",                  
			            button      : djcla.id+"button",
			            date      : startDate
			         });
				});
			}
		
	})

	function checkUsername(value){
		document.id('jform_username_loader').setStyle('display','inline-block');	   		
		var myRequest = new Request({
			url: '<?php echo JURI::base()?>index.php',
		    method: 'post',
			data: {
		      'option': 'com_djclassifieds',
		      'view': 'registration',
		      'task': 'checkUsername',
			  'username': value					  
			  },
		    onRequest: function(){
		        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
		    },
		    onSuccess: function(responseText){			
		    	if(responseText){
		    		document.id('jform_username_info').innerHTML = responseText;
		    		document.id('jform_username_info').setStyle('display','block');
		    		document.id('jform_username').addClass('invalid');
		    		document.id('jform_username').addClass('djinvalid');
		    		document.id('jform_username-lbl').addClass('invalid');
		    		document.id('jform_username').set('aria-invalid','true'); 
		    		document.id('jform_username_loader').setStyle('display','none');			    		
				}else{
					document.id('jform_username_info').innerHTML = '';
					document.id('jform_username_info').setStyle('display','none');
					document.id('jform_username_loader').setStyle('display','none');
					if(document.id('jform_username').hasClass('djinvalid')){
						document.id('jform_username').removeClass('invalid');
						document.id('jform_username').removeClass('djinvalid');
						document.id('jform_username-lbl').removeClass('invalid');
			    		document.id('jform_username').set('aria-invalid','false'); 
				    }						 					
				}				 	
		    },
		    onFailure: function(){		    
		    }
		});
		myRequest.send();	 	  
	}

	function checkPassword(){

	}

	function checkPasswordEqual(pass2_val){
		var pass1_val = document.id('jform_password1').value;

		if(pass1_val!=pass2_val){
			document.id('jform_password2_info').innerHTML = '<?php echo addslashes(JText::_('COM_USERS_REGISTER_PASSWORD1_MESSAGE')); ?>';
    		document.id('jform_password2_info').setStyle('display','block');
    		document.id('jform_password2').addClass('invalid');
    		document.id('jform_password2').addClass('djinvalid');
    		document.id('jform_password2-lbl').addClass('invalid');
    		document.id('jform_password2').set('aria-invalid','true'); 
    		document.id('jform_password2_loader').setStyle('display','none');			    		
		}else{
			document.id('jform_password2_info').innerHTML = '';
			document.id('jform_password2_info').setStyle('display','none');
			document.id('jform_password2_loader').setStyle('display','none');
			if(document.id('jform_password2').hasClass('djinvalid')){
				document.id('jform_password2').removeClass('invalid');
				document.id('jform_password2').removeClass('djinvalid');
				document.id('jform_password2-lbl').removeClass('invalid');
	    		document.id('jform_password2').set('aria-invalid','false'); 
		    }						 					
		}
		return null;
	}
	
	function checkEmail(value){
		document.id('jform_email1_loader').setStyle('display','inline-block');	   		
		var myRequest = new Request({
			url: '<?php echo JURI::base()?>index.php',
		    method: 'post',
			data: {
		      'option': 'com_djclassifieds',
		      'view': 'registration',
		      'task': 'checkEmail',
			  'email': value					  
			  },
		    onRequest: function(){
		        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
		    },
		    onSuccess: function(responseText){			
		    	if(responseText){
		    		document.id('jform_email1_info').innerHTML = responseText;
		    		document.id('jform_email1_info').setStyle('display','block');
		    		document.id('jform_email1').addClass('invalid');
		    		document.id('jform_email1').addClass('djinvalid');
		    		document.id('jform_email1-lbl').addClass('invalid');
		    		document.id('jform_email1').set('aria-invalid','true'); 
		    		document.id('jform_email1_loader').setStyle('display','none');			    		
				}else{
					document.id('jform_email1_info').innerHTML = '';
					document.id('jform_email1_info').setStyle('display','none');
					document.id('jform_email1_loader').setStyle('display','none');
					if(document.id('jform_email1').hasClass('djinvalid')){
						document.id('jform_email1').removeClass('invalid');
						document.id('jform_email1').removeClass('djinvalid');
						document.id('jform_email1-lbl').removeClass('invalid');
			    		document.id('jform_email1').set('aria-invalid','false'); 
				    }						 					
				}				 	
		    },
		    onFailure: function(){		    
		    }
		});
		myRequest.send();	 	
	}

	function checkEmailEqual(email2_val){
		var email1_val = document.id('jform_email1').value;
		if(email1_val!=email2_val){
			document.id('jform_email2_info').innerHTML = '<?php echo addslashes(JText::_('COM_USERS_REGISTER_EMAIL2_MESSAGE')); ?>';
    		document.id('jform_email2_info').setStyle('display','block');
    		document.id('jform_email2').addClass('invalid');
    		document.id('jform_email2').addClass('djinvalid');
    		document.id('jform_email2-lbl').addClass('invalid');
    		document.id('jform_email2').set('aria-invalid','true'); 
    		document.id('jform_email2_loader').setStyle('display','none');			    		
		}else{
			document.id('jform_email2_info').innerHTML = '';
			document.id('jform_email2_info').setStyle('display','none');
			document.id('jform_email2_loader').setStyle('display','none');
			if(document.id('jform_email2').hasClass('djinvalid')){
				document.id('jform_email2').removeClass('invalid');
				document.id('jform_email2').removeClass('djinvalid');
				document.id('jform_email2-lbl').removeClass('invalid');
	    		document.id('jform_email2').set('aria-invalid','false'); 
		    }						 					
		}
		return null;
	}	



	

    
	
window.addEvent('domready', function(){ 
   var JTooltips = new Tips($$('.Tips1'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_label', fixed: true
   });
   var JTooltips = new Tips($$('.Tips2'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_prom', fixed: false
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