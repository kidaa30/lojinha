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


$par 	    = $this->par;
$app 	    = JFactory::getApplication();
$imglimit   = $par->get('img_limit','3');
$unit_price = $par->get('unit_price','');	
$id 		= JRequest::getVar('id', 0, '', 'int' );
$user 		= JFactory::getUser();

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

$document= JFactory::getDocument();
$config = JFactory::getConfig();
if($config->get('force_ssl',0)==2){
	$document->addScript("https://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
}else{
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
}	

/*if($par->get('region_add_type','1')==1){
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false&language=".$par->get('region_lang','en'));
	$assets=JURI::base(true).'/components/com_djclassifieds/assets/';	
	$document->addScript($assets.'scripts.js');	
}*/
$points_a = $par->get('points',0);
if($points_a){	
	$menus	= $app->getMenu('site');
	$menu_ppackages_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=points',1);
	$user_ppoints_link='index.php?option=com_djclassifieds&view=points';
	if($menu_ppackages_itemid){
		$user_ppoints_link .= '&Itemid='.$menu_ppackages_itemid->id;
	}
}

$token = JRequest::getCMD('token', '' );
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
	
		$modules_djcf = &JModuleHelper::getModules('djcf-additem-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	

	?>	
	
<div class="dj-additem clearfix" >
<form action="index.php" method="post" class="form-validate" name="djForm" id="djForm"  enctype="multipart/form-data">
        <div class="additem_djform">
        
		    <div class="title_top"><?php 
				if(JRequest::getVar('id', 0, '', 'int' )>0){
					echo JText::_('COM_DJCLASSIFIEDS_EDIT_AD');
				}else{
					echo JText::_('COM_DJCLASSIFIEDS_NEW_AD');	
				}
			?></div>
			<div class="additem_djform_in">
        	<center><img src='<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/long_loader.gif' alt='LOADING' style='display: none;' id='upload_loading' /><div id="alercik"></div></center>
            
            <?php if(count($this->plugin_title)){
				foreach($this->plugin_title as $plugin_title){
					echo $plugin_title;
				}
			}?>
            
            <?php if($points_a){ ?>                        
	            <div class="djform_row points_info_row ">
	            	<div class="oints_info_row_in">
	            		<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE_INFO_IN_NEW_ADVERT'); ?>
	            		<a target="_blank" href="<?php echo JRoute::_($user_ppoints_link); ?>"><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES'); ?></a>
	            	</div>
	            </div>
            <?php }?>
            
            <div class="djform_row">
            	<?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="name" id="name-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_TITLE_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');?> *
	                    <?php if($par->get('title_char_limit','0')>0){ ?>
	                    	<span id="title_limit">(<?php echo $par->get('title_char_limit');?>)</span>
	                    <?php } ?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	
				<?php }else{ ?>
            		<label class="label" for="name" id="name-lbl" >
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');?> *
	                    <?php if($par->get('title_char_limit','0')>0){ ?>
	                    	<span id="title_limit">(<?php echo $par->get('title_char_limit');?>)</span>
	                    <?php } ?>	                
	                </label>
            	<?php } ?>                
                <div class="djform_field">                  	              	
                	<?php
                	$title_char_limit = $par->get('title_char_limit','0'); 
                	if($title_char_limit>0){
                		$input_title_limit =' onkeyup="titleLimit('.$title_char_limit.');" ';
                	}else{
                		$input_title_limit ='';
                	} ?>
                    <input class="inputbox required" <?php echo $input_title_limit; ?> type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->item->name; ?>" />
                </div>
                <div class="clear_both"></div>
            </div>
            <?php /* ?>
           	<div class="djform_row">
                <label class="label" for="sp" id="sp-lbl">
                	<?php $prom_price = $par->get('prom_price');
                    echo JText::_('aa');?>
					
                </label>
                <div class="djform_field">
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="1" /><?php echo JText::_('JYES'); ?>
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="2" /><?php echo JText::_('JNO'); ?>
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="3" /><?php echo JText::_('JNO'); ?>		
                </div>
                <div class="clear_both"></div>
            </div>
            <?php */?>
            <div class="djform_row">                
               	<?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="cat_0" id="cat_0-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_CATEGORY_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');?> *
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="cat_0" id="cat_0-lbl">
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY'); ?> *					
	                </label>
            	<?php } ?>
                <div class="djform_field">
                    <?php
				$cat_sel = '<select autocomplete="off" class="cat_sel required validate-djcat" id="cat_0" style="width:210px" name="cats[]" onchange="new_cat(0,this.value,new Array());getFields(this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY').'</option>';
				$parent_id=0;	
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						break;
					}	
					if($l->price>0){
						$l->price = $l->price/100;												
						$l->name .= ' ('.DJClassifiedsTheme::priceFormat($l->price,$unit_price);
							if($l->points>0 && $points_a){
								$l->name .= ' - '.$l->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');		
							}
						$l->name .= ')'; 
					}
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
					$cat_sel .= '</select>';
					echo $cat_sel;				
				
				?><div class="clear_both"></div>
				<div id="after_cat_0"></div>
				<script type="text/javascript">
					var cats=new Array();
					
				<?php
				/*$cat_sel = '<select style="width:210px" class="cat_sel required validate-djcat" name="cats[]" id="cat_0" onchange="new_cat(0,this.value);getFields(this.value);">';
				$parent_id=0;	
								
				$cat_req = array();
				foreach($this->cats as $l){
					if($l->ads_disabled){
						$cat_req[$l->id]=1;
					}
				}
				
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo "cats[$parent_id]='$cat_sel<div id=\"after_cat_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$cl_select = '';
						if($l->ads_disabled || isset($cat_req[$parent_id])){
							$cl_select = ' class="cat_sel required validate-djcat" ';						
						}
						$cat_sel = '<div class="clear_both"></div><select '.$cl_select.' style="width:210px" name="cats[]" id="cat_'.$l->parent_id.'" onchange="new_cat('.$parent_id.',this.value);getFields(this.value);">';
						$cat_sel .= '<option value="p'.$parent_id.'">'.JTEXT::_('COM_DJCLASSIFIEDS_CATEGORY_SELECTOR_EMPTY_VALUE').'</option>';		
					}	
					if($l->price>0){
						$l->price = $l->price/100;						
						$l->name .= ' ('.DJClassifiedsTheme::priceFormat($l->price,$unit_price);
							if($l->points>0 && $points_a){
								$l->name .= ' - '.$l->points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');		
							}	
						$l->name .= ')'; 
					}
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
				$cat_sel .= '</select>';	
				echo "cats[$parent_id]='$cat_sel<div id=\"after_cat_$parent_id\"></div>';";
				*/
				?>	
				var current=0;
				
				function new_cat(parent,a_parent,c_path){


					var myRequest = new Request({
					    url: '<?php echo JURI::base()?>index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'view': 'additem',
					      'task': 'getCategorySelect',
						  'cat_id': a_parent				  
						  },
					    onRequest: function(){
					    	document.id('after_cat_'+parent).innerHTML = '<div style="text-align:center;"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/loading.gif" alt="..." /></div>';
					    	},
					    onSuccess: function(responseText){																
					    	if(responseText){	
								document.id('after_cat_'+parent).innerHTML = responseText; 
								document.id('cat_'+parent).value=a_parent;
							}else{
								document.id('after_cat_'+parent).innerHTML = '';
								document.id('cat_'+parent).value=a_parent;		
							}	
							if(c_path != 'null'){
								if(c_path.length>0){
									var first_path = c_path[0].split(',');												
									c_path.shift();
									new_cat(first_path[0],first_path[1],c_path);												
								}
							}
							document.id('after_cat_'+parent).removeClass('invalid');					
							document.id('after_cat_'+parent).setAttribute("aria-invalid", "false");
					    },
					    onFailure: function(){}
					});
					myRequest.send();	

					
					/*if(cats[a_parent]){
						//alert(cats[v]);	
						document.id('after_cat_'+parent).innerHTML = cats[a_parent]; 
						document.id('cat_'+parent).value=a_parent;
					}else{
						document.id('after_cat_'+parent).innerHTML = '';
						document.id('cat_'+parent).value=a_parent;		
					}
					document.id('after_cat_'+parent).removeClass('invalid');					
					document.id('after_cat_'+parent).setAttribute("aria-invalid", "false");*/
					
				}
					<?php if(count($this->cat_path)){
						$c_path = $this->cat_path;
						echo 'var cat_path = new Array();';
						$cat_path_f = 'new_cat(';
						for($c=count($c_path)-1;$c>-1;$c--){
							if($c<count($c_path)-1){
								$ci = count($c_path) - $c -2;
								echo "cat_path[$ci]='$c_path[$c]';";
							}else{
								$cat_path_f .= $c_path[$c];
							}
						}			
						echo $cat_path_f.',cat_path);';																
					}?>
				</script>
					
                </div>
                <div class="clear_both"></div>
            </div>
            <div class="djform_row extra_fields">        
				<div id="ex_fields"></div>        
                <div class="clear_both"></div>
            </div> 
            <?php
            $types = DJClassifiedsType::getTypesSelect();
            if($par->get('show_types','0') && $types){?>  	
            <div class="djform_row">                
               	<?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="type_id" id="type_id-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_TYPE_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_TYPE');if($par->get('types_required','0')){ echo ' * ';} ?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="type_id" id="type_id-lbl">
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_TYPE');if($par->get('types_required','0')){ echo ' * ';} ?>					
	                </label>
            	<?php } ?>
                <div class="djform_field">
               		<select autocomplete="off" name="type_id" id="type_id" class="inputbox<?php if($par->get('types_required','0')){ echo ' required';} ?>" >
						<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SELECT_TYPE');?></option>
						<?php echo JHtml::_('select.options', $types, 'value', 'text', $this->item->type_id, true);?>
					</select>
					<div class="clear_both"></div>									
                </div>
                <div class="clear_both"></div>
            </div>
            <?php }?>	
            <?php if(count($this->regions) && $par->get('show_regions','1')){?>
    		<div class="djform_row">
                <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="reg_0" id="reg_0-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_LOCALIZATION_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');?> *
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="reg_0" id="cat_0-lbl">
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?> *					
	                </label>
            	<?php } ?>
                <div class="djform_field" id="locations_list">
	                <?php	               		     	                    
							$reg_sel = '<select autocomplete="off" id="reg_0" class="required" style="width:210px" name="regions[]" onchange="new_reg(0,this.value,new Array());"><option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
							$parent_id=0;	
							foreach($this->regions as $l){
								if($parent_id!=$l->parent_id){
									break;
								}	
								$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
								//$ri++;
							}
								$reg_sel .= '</select>';
								echo $reg_sel;
							
							?><div class="clear_both"></div>
							<div id="after_reg_0"></div>
							<script type="text/javascript">
								var regs=new Array();
								
							<?php
							/*$reg_sel = '<select style="width:210px" name="regions[]" id="reg_0" onchange="new_reg(0,this.value);">';
							$parent_id=0;	
							
							foreach($this->regions as $l){
								if($parent_id!=$l->parent_id){
									$reg_sel .= '</select>';
									echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";
									$parent_id=$l->parent_id;
									$reg_sel = '<div class="clear_both"></div><select style="width:210px" name="regions[]" id="reg_'.$l->parent_id.'" onchange="new_reg('.$parent_id.',this.value);">';
									$reg_sel .= '<option value="">'.JTEXT::_('COM_DJCLASSIFIEDS_LOCATION_SELECTOR_EMPTY_VALUE').'</option>';		
								}	
								$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
							}
							$reg_sel .= '</select>';	
							echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";*/
							
							?>	
							var current=0;
							
							function new_reg(parent,a_parent, r_path){

								  	var myRequest = new Request({
									    url: '<?php echo JURI::base()?>index.php',
									    method: 'post',
										data: {
									      'option': 'com_djclassifieds',
									      'view': 'additem',
									      'task': 'getRegionSelect',
										  'reg_id': a_parent				  
										  },
									    onRequest: function(){
									    	document.id('after_reg_'+parent).innerHTML = '<div style="text-align:center;"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/loading.gif" alt="..." /></div>';
									    	},
									    onSuccess: function(responseText){																
									    	if(responseText){	
												document.id('after_reg_'+parent).innerHTML = responseText; 
												document.id('reg_'+parent).value=a_parent;
											}else{
												document.id('after_reg_'+parent).innerHTML = '';
												document.id('reg_'+parent).value=a_parent;		
											}	
											if(r_path != 'null'){
												if(r_path.length>0){
													var first_path = r_path[0].split(',');												
													r_path.shift();
													new_reg(first_path[0],first_path[1],r_path);												
												}
											}
									    },
									    onFailure: function(){}
									});
									myRequest.send();	

								
								/*if(regs[a_parent]){
									//alert(cats[v]);	
									document.id('after_reg_'+parent).innerHTML = regs[a_parent]; 
									document.id('reg_'+parent).value=a_parent;
								}else{
									document.id('after_reg_'+parent).innerHTML = '';
									document.id('reg_'+parent).value=a_parent;		
								}*/
								
							}
							
							<?php if(count($this->reg_path)){
								$r_path = $this->reg_path;
								echo 'var reg_path = new Array();';
								$reg_path_f = 'new_reg(';
								for($r=count($r_path)-1;$r>-1;$r--){
									if($r<count($r_path)-1){
										$ri = count($r_path) - $r -2;
										echo "reg_path[$ri]='$r_path[$r]';";
									}else{
										$reg_path_f .= $r_path[$r];
									}
								}			
								
								echo $reg_path_f.',reg_path);';																
							}?>
						</script>						
                </div>
                <div class="clear_both"></div>
            </div>  
            <?php }else{ ?>
            	<input type="hidden" name="regions[]" value="0" />
            <?php } ?>
            <?php if($par->get('show_address','1')){?>
	            <div class="djform_row">
	                <?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_ADDRESS_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADDRESS');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_ADDRESS'); ?>					
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="address" id="address" size="50" maxlength="250" value="<?php echo $this->item->address; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>
            <?php } ?>      
            <?php if($par->get('show_postcode','0')){?>
            	<div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_POSTCODE_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_POSTCODE');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_POSTCODE'); ?>					
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="post_code" id="post_code" size="50" maxlength="250" value="<?php echo $this->item->post_code; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>             
            <?php
			 }       
			if($par->get('allow_user_lat_lng','0')){?>
            	<div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_LATITUDE_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_LATITUDE');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />		                    
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_LATITUDE'); ?>			
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="latitude" id="latitude" size="50" maxlength="250" value="<?php echo $this->item->latitude; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>   
	            <div class="djform_row">
            		<?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_LONGITUDE_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_LONGITUDE');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_LONGITUDE'); ?>			
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="longitude" id="longitude" size="50" maxlength="250" value="<?php echo $this->item->longitude; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>	            
	            <div class="djform_row">
	            	<div class="djmap_intro">
	            		<div class="djmap_intro_desc"><?php echo JText::_('COM_DJCLASSIFIEDS_ADDITEM_SELECT_ON_MAP'); ?></div>
	            		<span class="button" type="button" id="map_use_my_location"><?php echo JText::_('COM_DJCLASSIFIEDS_USE_MY_LOCATION')?></span>
	            		<span class="button" type="button" id="map_update_latlng"><?php echo JText::_('COM_DJCLASSIFIEDS_UPDATE_USING_ADDRESS')?></span>
	            		<div class="clear_both"></div>
	            		<div id="mapalert"><?php echo JText::_('COM_DJCLASSIFIEDS_WE_CANT_FIND_COORDS_ON_ADDRESS');?></div>
	            	</div>
					<div id="djmap" style="width:100%;height:300px;"></div>
	            </div>
            <?php
			 }       
             $exp_days_list = $par->get('exp_days_list','');
			$exp_days = $par->get('exp_days','');
			if($par->get('durations_list','') && $id==0 && count($this->days)){
				//print_r($this->days);die();				
					?>
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
	            	<?php } ?>
	                <select id="exp_days" name="exp_days">
					<?php 					
						foreach($this->days as $day){
							echo '<option value="'.$day->days.'"';	
								if($day->days==$exp_days){
									echo ' SELECTED ';	
								}							
								echo '>';
								if($day->days==0){
									echo JText::_('COM_DJCLASSIFIEDS_UNLIMITED');
								}else if($day->days==1){
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAY');
								}else{
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAYS');	
								} 
								
								if($day->price !='0.00'){
									//echo '&nbsp;-&nbsp;'.$day->price.'&nbsp;'.$par->get('unit_price');	
									echo '&nbsp;-&nbsp;'.DJClassifiedsTheme::priceFormat($day->price,$par->get('unit_price'));
								}
								if($day->points>0 && $points_a){
									echo '&nbsp;-&nbsp;'.$day->points.'&nbsp;'.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');	
								}							
							echo '</option>';
						}
					?>
					</select>
	                <div class="clear_both"></div>
	            </div>                
            <?php }else if($id>0){
            	echo '<input type="hidden" id="exp_days" value="'.$this->item->exp_days.'" />';
            } ?>
            <?php if($par->get('show_introdesc','1')){?>
    		<div class="djform_row">                
                <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="intro_desc" id="intro_desc-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION');?> *	                    
	                    <span id="introdesc_limit">(<?php echo $par->get('introdesc_char_limit');?>)</span>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />	                    
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="intro_desc" id="intro_desc-lbl">                	
                    	<?php echo JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION');?> * 	
 						<span id="introdesc_limit">(<?php echo $par->get('introdesc_char_limit');?>)</span>	 								
                	</label>
            	<?php } ?>
                <div class="djform_field">
		            <textarea id="intro_desc" name="intro_desc" rows="5" cols="55" class="inputbox required" onkeyup="introdescLimit(<?php echo $par->get('introdesc_char_limit');?>);" ><?php echo $this->item->intro_desc; ?></textarea>
                </div>
                <div class="clear_both"></div>
            </div>
            <?php } ?>
    		<div class="djform_row">
    			<?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_DESCRIPTION_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" >
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION'); ?>					
	                </label>
            	<?php } ?>
                <div class="djform_field">
                	<?php
                	if($par->get('pay_desc_chars','0')){
						$pay_desc_chars_limit = $par->get('pay_desc_chars_limit',0);
						if($pay_desc_chars_limit>0){ ?>
							<div class="desc_info_row"><?php echo JText::_('COM_DJCLASSIFIEDS_CHARS_LIMIT'); ?> : <span ><?php echo $pay_desc_chars_limit ;?></span></div>
						<?php } ?>
						<div class="desc_info_row"><?php echo JText::_('COM_DJCLASSIFIEDS_FREE_CHARS_LIMIT'); ?> : <span ><?php echo $par->get('pay_desc_chars_free_limit')+$this->item->extra_chars;?></span></div>
						<?php 						
						$char_price	= $par->get('desc_char_price','0');
						$char_price_points	= $par->get('desc_char_price_points','0');
						
						if($id==0 && $par->get('durations_list','')){
							$exp_days_def = $par->get('exp_days','7');
							if(isset($this->days[$exp_days_def])){
								if($this->days[$exp_days_def]->char_price_default==0){
									$char_price = $this->days[$exp_days_def]->char_price;
									$char_price_points = $this->days[$exp_days_def]->char_points;
								}
							}						
						}else if(isset($this->days[$this->item->exp_days])){
							if($this->days[$this->item->exp_days]->char_price_default==0){
								$char_price = $this->days[$this->item->exp_days]->char_price;
								$char_price_points = $this->days[$this->item->exp_days]->char_points;
							}
						} 
						
						echo '<div class="desc_info_row">'.JText::_('COM_DJCLASSIFIEDS_PRICE_FOR_ADDITIONAL_CHAR').': <span  id="extra_char_price" >';
							echo DJClassifiedsTheme::priceFormat($char_price,$par->get('unit_price'));
							if($points_a && $char_price_points>0){
								echo '&nbsp-&nbsp'.$char_price_points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
							}										
						echo '</span></div> <br />';					
						?>
						<div class="desc_info_row"><?php echo JText::_('COM_DJCLASSIFIEDS_CHARS_USED'); ?> : <span id="desc_chars"><?php echo strlen($this->item->description);?></span></div>
						<div class="desc_info_row"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_FOR_ADDITIONAL_CHARS'); ?> : <span id="desc_price">0</span></div>
						<textarea id="djdesc" name="description" rows="5" cols="55" class="inputbox" onkeyup="descCharsLimit();" ><?php echo $this->item->description; ?></textarea>                		
                	<?php 
					}else if($par->get('desc_editor','1')){
                		$editor = JFactory::getEditor('tinymce');
						$p = array('mode'=>'0');		 
						echo $editor->display("description",$this->item->description,'100%','250','5','55', false, '', '', '',$p);	
                	}else{
                		$this->item->description = strip_tags($this->item->description); ?>
  				        <textarea id="description" name="description" rows="5" cols="55" class="inputbox" ><?php echo $this->item->description; ?></textarea>
                	<?php }			   
                	?>                  
                </div>
                <div class="clear_both"></div>
            </div>
			<?php if($par->get('seo_metadesc_user_edit','0')){?>
    		<div class="djform_row">                
                <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="meta_desc" id="meta_desc-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_METADESCRIPTION_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_METADESCRIPTION');?>	                    
	                    <span id="metadesc_limit">(<?php echo $par->get('seo_metadesc_char_limit','160');?>)</span>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />	                    
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="meta_desc" id="meta_desc-lbl">                	
                    	<?php echo JText::_('COM_DJCLASSIFIEDS_METADESCRIPTION');?>	
 						<span id="metadesc_limit">(<?php echo $par->get('seo_metadesc_char_limit');?>)</span>	 								
                	</label>
            	<?php } ?>
                <div class="djform_field">
		            <textarea id="meta_desc" name="metadesc" rows="5" cols="55" class="inputbox" onkeyup="metadescLimit(<?php echo $par->get('seo_metadesc_char_limit');?>);" ><?php echo $this->item->metadesc; ?></textarea>
                </div>
                <div class="clear_both"></div>
            </div>
            <?php } ?>    
			<?php if($par->get('seo_keywords_user_edit','0')){?>
    		<div class="djform_row">                
                <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="keywords" id="keywords-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_KEYWORDS_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_KEYWORDS');?>	                    
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />	                    
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="keywords" id="keywords-lbl">                	
                    	<?php echo JText::_('COM_DJCLASSIFIEDS_KEYWORDS');?>		 								
                	</label>
            	<?php } ?>
                <div class="djform_field">
		            <textarea id="keywords" name="metakey" rows="5" cols="55" class="inputbox" ><?php echo $this->item->metakey; ?></textarea>
                </div>
                <div class="clear_both"></div>
            </div>
            <?php } ?>                     
            <?php if($par->get('show_contact','1')==1){?>
    		<div class="djform_row">
               	<?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" for="contact" id="contact-lbl" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_CONTACT_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT');?> *
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" for="contact" id="contact-lbl" >
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT'); ?> *					
	                </label>
            	<?php } ?>
                <div class="djform_field">
		            <textarea id="contact" name="contact" rows="4" cols="55" class="inputbox required"><?php echo str_ireplace("<br />", '', $this->item->contact); ?></textarea>                  
                </div>
                <div class="clear_both"></div>
            </div>
            <?php            	           
             }
             echo  $this->loadTemplate('contactfields');
             if($par->get('email_for_guest','0') && !$user->id && !$this->item->id){ ?>
             	<div class="djform_row">
             		<?php if($par->get('show_tooltips_newad','0')){ ?>
             		   	<label for="guest_email" id="guest_email-lbl" class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_EMAIL_GUEST_TOOLTIP')?>">
             		 	   <?php echo JText::_('COM_DJCLASSIFIEDS_EMAIL');?> <?php if($par->get('email_for_guest','0')==2){echo '*';}?>
             		       <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" /> 
             		    </label>	                               			                	
             		<?php }else{ ?>
             		   	<label for="guest_email" id="guest_email-lbl" class="label">
             		   	   <?php echo JText::_('COM_DJCLASSIFIEDS_EMAIL'); ?> <?php if($par->get('email_for_guest','0')==2){echo '*';}?>
             		    </label>
             	  	<?php } ?>
             	    <div class="djform_field">             	    	
             	    	<input class="text_area validate-djemail <?php if($par->get('email_for_guest','0')==2){echo ' required';}?>" onchange="checkDJEmail(this.value);" type="text" name="email" id="guest_email" size="50" maxlength="250" value="" />
             	    	<span id="guest_email_loader"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/newad_loader.gif" alt="..." /></span>
             	    	<div id="guest_email_info"></div>
             	    </div>
             	    <div class="clear_both"></div> 
             	    </div>             
                <?php
             }                
             if($par->get('show_website','0')){?>
            	<div class="djform_row">
	                <?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_WEBSITE_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_WEBSITE');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_WEBSITE'); ?>					
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="website" id="website" size="50" maxlength="250" value="<?php echo $this->item->website; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>             
            <?php
			 }   
			 if($par->get('show_video','0')){?>
            	<div class="djform_row">
	                 <?php if($par->get('show_tooltips_newad','0')){ ?>
		            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_VIDEO_TOOLTIP')?>">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_VIDEO');?>
		                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
		                    <br /><span><?php echo JText::_('COM_DJCLASSIFIEDS_LINK_TO_YOUTUBE_OR_VIMEO');?></span>
		                </label>	                               			                	
					<?php }else{ ?>
		            	<label class="label">
		                	  <?php echo JText::_('COM_DJCLASSIFIEDS_VIDEO'); ?>	
		                	  <br /><span><?php echo JText::_('COM_DJCLASSIFIEDS_LINK_TO_YOUTUBE_OR_VIMEO');?></span>				
		                </label>
	            	<?php } ?>
	                <div class="djform_field">
	                    <input class="text_area" type="text" name="video" id="video" size="50" maxlength="250" value="<?php echo $this->item->video; ?>" />
	                </div>
	                <div class="clear_both"></div> 
	            </div>             
            <?php
			 }  
			 if($par->get('buynow','0')==1){
			 	echo  $this->loadTemplate('buynow');
			 }
            if($par->get('show_price','1')==1){
				
				$price_lbl_class='';
				$price_lbl = JText::_('COM_DJCLASSIFIEDS_PRICE');
				$price_lbl_tooltip = JTEXT::_('COM_DJCLASSIFIEDS_PRICE_TOOLTIP');
				if($this->item->buynow == 1){
					$price_lbl_class=' label-buynow';
					$price_lbl = JText::_('COM_DJCLASSIFIEDS_PRICE_BUYNOW');
				}else if($this->item->auction == 1){
					$price_lbl_class=' label-auction';
					$price_lbl = JText::_('COM_DJCLASSIFIEDS_START_PRICE');
				}
			?>
    		<div class="djform_row">
              <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1<?php echo $price_lbl_class;?>" id="price-lbl" for="price" title="<?php echo $price_lbl_tooltip; ?>">
	                    <?php echo $price_lbl;?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label<?php echo $price_lbl_class;?>" id="price-lbl" for="price">
	                	  <?php echo $price_lbl ?>					
	                </label>
            	<?php } ?>
                <div class="djform_field">
                	<?php if ($par->get('unit_price_position','0')== 0) {	?>
                    	<input class="text_area<?php if($par->get('price_only_numbers','0')){echo ' validate-numeric';}?>" type="text" name="price" id="price" size="30" maxlength="250" value="<?php echo $this->item->price; ?>" />
                     <?php }
                     
                     if($par->get('unit_price_list','')){
                     	$c_list = explode(';', $par->get('unit_price_list',''));
						 echo '<select name="currency" class="price_currency">';
						 for($cl=0;$cl<count($c_list);$cl++){
						 	if($c_list[$cl]==$this->item->currency){
						 		$csel=' SELECTED ';
						 	}else{
						 		$csel='';
							}
						 	echo '<option '.$csel.' name="'.$c_list[$cl].' ">'.$c_list[$cl].'</option>';
						 }
						 echo '</select>';
                     	
                     }else{
                     	echo $par->get('unit_price','EUR');
						echo '<input type="hidden" name="currency" value="" >';
                     }
                     if ($par->get('unit_price_position','0')== 1) { ?>	
                        <input class="text_area<?php if($par->get('price_only_numbers','0')){echo 'validate-numeric';}?>" type="text" name="price" id="price" size="30" maxlength="250" value="<?php echo $this->item->price; ?>" />
                     <?php }
                     
                     if($par->get('show_price_negotiable','0')){ ?>
                     	<div class="price_neg_box">
                     		<input type="checkbox" autocomplete="off" name="price_negotiable" value="1" <?php if($this->item->price_negotiable){ echo 'checked="CHECKED"';}?> />
                     		<span><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE_NEGOTIABLE')?></span>
                     	</div>
                     <?php  }else{ ?>
                     	<input type="hidden" name="price_negotiable" value="0" />
                     <?php } ?>
                </div>
                <div class="clear_both"></div>
            </div>
			<?php
			}
			if($par->get('auctions','0')==1){
				echo  $this->loadTemplate('auctions');
			}
			
			if(count($this->plugin_rows)){
				foreach($this->plugin_rows as $plugin_row){
					echo $plugin_row;
				}
			}
			
			/*
			$images = array();
			if(JRequest::getVar('id', 0, '', 'int' )>0){
			?>		
    		<div class="djform_row">
                 <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_IMAGES_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES');?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label">
	                	  <?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES'); ?>			
	                </label>
            	<?php } ?>
                <div class="djform_field">
			<?php
				$images_count = 0;
				if(!$image = $this->item->image_url){
					echo JText::_('COM_DJCLASSIFIEDS_NO_IMAGES_INCLUDED');
				}else{
					echo '<input type="hidden" name="image_url" value="'.$this->item->image_url.'" />';
					$images=explode(';', substr($image,0,-1));
					for($i=0; $i<count($images); $i++){
						?>
						<?php 
					      $img_path= JURI::base(true).'/components/com_djclassifieds/images/';
						  $img_path .= $images[$i];
						  ?>
						  <img src="<?php echo $img_path;?>.ths.jpg"/>
						  <input type="checkbox" name="del_img[]" id="del_img[]" value="<?php echo $images[$i];?>"/>
						  <?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); ?>
						  <br/>
						<?php
					}
					echo '<input type="hidden" id="count_images" value="'.count($images).'">';
				}
				?>
                </div>
                <div class="clear_both"></div>
            </div>
				<?php
			}
			if(count($images)<$imglimit){
			?>
    		<div class="djform_row">                
                <?php if($par->get('show_tooltips_newad','0')){ ?>
	            	<label class="label Tips1" title="<?php echo JTEXT::_('COM_DJCLASSIFIEDS_ADD_IMAGE_TOOLTIP')?>">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');?>
	                    <img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/tip.png" alt="?" />
	                    <?php 
	                    $img_maxsize = $par->get('img_maxsize',0);	
						if($img_maxsize>0){
							echo '<br />'.JText::_('COM_DJCLASSIFIEDS_MAX_IMAGE_SIZE').': '.$img_maxsize.' MB';
						}
						echo '<br /><span>'.JText::_('COM_DJCLASSIFIEDS_FIRST_IMAGES_IS_MAIN_IMAGE').'</span>';?>
	                </label>	                               			                	
				<?php }else{ ?>
	            	<label class="label" >
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');
						$img_maxsize = $par->get('img_maxsize',0);	
						if($img_maxsize>0){
							echo '<br />'.JText::_('COM_DJCLASSIFIEDS_MAX_IMAGE_SIZE').': '.$img_maxsize.' MB';
						}
						echo '<br /><span>'.JText::_('COM_DJCLASSIFIEDS_FIRST_IMAGES_IS_MAIN_IMAGE').'</span>';
						?>
	                </label>
            	<?php } ?>
                <div class="djform_field">
                    <?php $image_urls = ""?>
					<div id="uploader">
						<input type="file"  name="image[]" class="inputbox" />
						<?php if($imglimit>1){ ?>
							<a class="add_another_image" href="#" onclick="addImage(<?php echo $imglimit;?>); return false;" ><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEX_IMAGE')?></a>
						<?php }?>
					</div>
                </div>
                <div class="clear_both"></div>
            </div>
             <?php } */?>
 		 	<?php if($par->get('terms',1)>0 && $par->get('terms_article_id',0)>0 && $this->terms_link && JRequest::getVar('id', 0, '', 'int' )==0 && !$token){ ?>				
    		<div class="djform_row terms_and_conditions">
                <label class="label" >&nbsp;</label>
                <div class="djform_field">
                	<fieldset id="terms_and_conditions" class="checkboxes required">
                		<input type="checkbox" name="terms_and_conditions" id="terms_and_conditions0" value="1" class="inputbox" />                	
						<?php 					 
						echo ' <label class="label_terms" for="terms_and_conditions" id="terms_and_conditions-lbl" >'.JText::_('COM_DJCLASSIFIEDS_I_AGREE_TO_THE').' </label>';					
						if($par->get('terms',0)==1){
							echo '<a href="'.$this->terms_link.'" target="_blank">'.JText::_('COM_DJCLASSIFIEDS_TERMS_AND_CONDITIONS').'</a>';
						}else if($par->get('terms',0)==2){
							echo '<a href="'.$this->terms_link.'" rel="{size: {x: 700, y: 500}, handler:\'iframe\'}" class="modal" target="_blank">'.JText::_('COM_DJCLASSIFIEDS_TERMS_AND_CONDITIONS').'</a>';
						}					
						?> *
					</fieldset>
                </div>
                <div class="clear_both"></div>
            </div>
		 <?php } ?>	
 		
 		 </div>
 		 </div>
 		 <?php 
	 		 if($imglimit>0){
	 		 	echo  $this->loadTemplate('images');
	 		 }
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
		 <?php
		 	if(count($this->plugin_sections)){
				foreach($this->plugin_sections as $plugin_section){
					echo $plugin_section;
				}
			}
        ?>
		<label id="verification_alert"  style="display:none;color:red;" >
			<?php echo JText::_('COM_DJCLASSIFIEDS_ENTER_ALL_REQUIRED_FIELDS'); ?>
		</label>
		<div id="verification_alert_system" ></div>
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
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<input type="hidden" name="view" value="additem" />
		<input type="hidden" name="task" value="save" />
		<input type="hidden" name="boxchecked" value="0" />
	</div>
</form>
</div>
</div>
<script type="text/javascript">	
/*	
function addImage(imglimit){

	lim=document.djForm['image[]'].length;
	if(!lim){
		lim=1;
	}
	
	if(document.djForm['del_img[]']){
		lim_old=document.djForm['del_img[]'].length;
		if(!lim_old){
			lim_old=1;
		}
		lim = lim + lim_old;	
	}
	
	
	if(lim==imglimit){
		alert('<?php echo JText::_('COM_DJCLASSIFIEDS_MAXIMUM_NUMBER_OF_IMAGES_IS');?> '+imglimit);
	}else{
		var inputdiv = document.createElement('input');
		inputdiv.setAttribute('name','image[]');
		inputdiv.setAttribute('type','file');
		var ni = document.id('uploader');
		ni.appendChild(document.createElement('br'))
		ni.appendChild(inputdiv);		
	}

} */


	<?php if($par->get('show_introdesc','1')){?>
		function introdescLimit(limit){
			if(document.djForm.intro_desc.value.length<=limit){
				a=document.djForm.intro_desc.value.length;
				b=limit;
				c=b-a;
				document.getElementById('introdesc_limit').innerHTML= '('+c+')';
			}else{
				document.djForm.intro_desc.value = document.djForm.intro_desc.value.substring(0, limit);
			}
		}
	<?php } ?>

	<?php if($par->get('seo_metadesc_user_edit','0')){?>
	function metadescLimit(limit){
		if(document.djForm.meta_desc.value.length<=limit){
			a=document.djForm.meta_desc.value.length;
			b=limit;
			c=b-a;
			document.getElementById('metadesc_limit').innerHTML= '('+c+')';
		}else{
			document.djForm.meta_desc.value = document.djForm.meta_desc.value.substring(0, limit);
		}
	}
	<?php } ?>
	
	<?php if($title_char_limit>0){ ?>
		function titleLimit(limit){
			if(document.djForm.name.value.length<=limit){
				a=document.djForm.name.value.length;
				b=limit;
				c=b-a;
				document.getElementById('title_limit').innerHTML= '('+c+')';
			}else{
				document.djForm.name.value = document.djForm.name.value.substring(0, limit);
			}
		}	
	<?php }?>

	var category_id = 0;
	
	function getFields(cat_id){		
		var el = document.getElementById("ex_fields");
		var before = document.getElementById("ex_fields").innerHTML.trim();	
		category_id = cat_id; 
		
		if(cat_id!=0){
			el.innerHTML = '<div style="text-align:center;margin-bottom:15px;"><img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" /></div>';
			var url = '<?php echo JURI::base()?>index.php?option=com_djclassifieds&view=additem&task=getFields&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?><?php if($this->item->token){echo "+ '&token=".$this->item->token."'";} ?>;
						  var myRequest = new Request({
					    url: '<?php echo JURI::base()?>index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'view': 'additem',
					      'task': 'getFields',
						  'cat_id': cat_id
						  <?php if($this->item->id){echo ",'id':'".$this->item->id."'";} ?>	
						  <?php if($this->item->token){echo ",'token':'".$this->item->token."'";} ?>				  
						  },
					    onRequest: function(){
					        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
					    },
					    onSuccess: function(responseText){																
							el.innerHTML = responseText;
							var JTooltips = new Tips($$('.Tips1'), {	
						      showDelay: 200, hideDelay: 200, className: 'djcf_label', fixed: true
						   });						 	
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
							document.formvalidator.attachToForm(document.id('djForm'));
					    },
					    onFailure: function(){
					        myElement.set('html', 'Sorry, your request failed, please contact to ');
					    }
					});
					myRequest.send();	
	
					<?php if($par->get('buynow','0')==1){?>
						<?php if($this->item->id){ ?>
							getBuynowOptions();
						<?php } ?>
						document.getElementById("buynow_options1").innerHTML = '';
						document.getElementById("buynow_new_options1").setStyle('display','none');
						getBuynowFields(1);
						document.getElementById("buynow_options2").innerHTML = '';
						document.getElementById("buynow_new_options2").setStyle('display','none');
						document.getElementById("buynow_options2_info").setStyle('display','none');				
						getBuynowFields(2);
					<?php }?>
					
		}else{
			el.innerHTML = '';
			//el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
		}
		
	}


	function getBuynowOptions(){

		var bn_el = document.id("buynow_options");		
		var cat_id = category_id; 
		if(cat_id){
			//bn_el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&view=additem&task=getBuynowOptions&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest2 = new Request({
				    url: 'index.php',
				    method: 'post',				    
				    evalResponse: false,					
					data: {
				      'option': 'com_djclassifieds',
				      'view': 'additem',
				      'task': 'getBuynowOptions',
					  'cat_id': cat_id,	
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){				    	
				    	bn_el.innerHTML = responseText; 		         		 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest2.send();
		}else{
			bn_el.innerHTML = '';
			//bn_el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
		}
	}

	function getBuynowFields(type){

		var bn_el = document.id("buynow_options"+type);		
		var cat_id = category_id; 
		if(cat_id){
			//bn_el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
			var url = 'index.php?option=com_djclassifieds&view=additem&task=getBuynowFields&cat_id=' + cat_id + '&type=' + type<?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest2 = new Request({
				    url: 'index.php',
				    method: 'post',				    
				    evalResponse: false,					
					data: {
				      'option': 'com_djclassifieds',
				      'view': 'additem',
				      'task': 'getBuynowFields',
					  'cat_id': cat_id,					  
					  'type': type,
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){				    	
				    	if(responseText){
				    		var respElement = new Element('div');
				    		respElement.innerHTML = responseText; 
				    		respElement.inject(bn_el,'bottom');	
				    		document.getElementById("buynow_new_options"+type).setStyle('display','block');
				    		document.id('buynow_quantity_box').setStyle('display','none');
				    		if(type==2){
				    			document.getElementById("buynow_options2_info").setStyle('display','block');
				    		}
				    	}else{
				    		document.id('buynow_quantity_box').setStyle('display','block');
					    }			         		 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest2.send();
		}else{
			bn_el.innerHTML = '';
			//bn_el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
			document.id('buynow_quantity_box').setStyle('display','block');
		}
	}

	function deleteBuynowField(row_id){
		document.getElementById(row_id).innerHTML = '';
	}
	
	function getCities(region_id){
		var el = document.getElementById("city");
		var before = document.getElementById("city").innerHTML.trim();	
		
		if(region_id>0){
			el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" />';
			var url = '<?php echo JURI::base()?>index.php?option=com_djclassifieds&view=additemtask=getCities&r_id=' + region_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
				var myRequest = new Request({
					    url: '<?php echo JURI::base()?>index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'view': 'additem',
					      'task': 'getCities',
						  'r_id': region_id
						  <?php if($this->item->id){echo ",'id':'".$this->item->id."'";} ?>					  
						  },
					    onRequest: function(){
					        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
					    },
					    onSuccess: function(responseText){																
							el.innerHTML = responseText;						 	
					    },
					    onFailure: function(){
					        myElement.set('html', 'Sorry, your request failed, please contact to ');
					    }
					});
					myRequest.send();	
		}else{
			el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_REGION');?>';
		}
		
	}	


	function checkDJEmail(value){
		document.id('guest_email_loader').setStyle('display','inline-block');	   		
		var myRequest = new Request({
			    url: '<?php echo JURI::base()?>index.php',
		    method: 'post',
			data: {
		      'option': 'com_djclassifieds',
		      'view': 'additem',
		      'task': 'checkEmail',
			  'email': value					  
			  },
		    onRequest: function(){
		        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
		    },
		    onSuccess: function(responseText){			
		    	if(responseText){
		    		document.id('guest_email_info').innerHTML = responseText;
		    		document.id('guest_email_info').setStyle('display','block');
		    		document.id('guest_email').addClass('invalid');
		    		document.id('guest_email').addClass('djinvalid');
		    		document.id('guest_email-lbl').addClass('invalid');
		    		document.id('guest_email').set('aria-invalid','true'); 
		    		document.id('guest_email_loader').setStyle('display','none');			    		
				}else{
					document.id('guest_email_info').innerHTML = '';
					document.id('guest_email_info').setStyle('display','none');
					document.id('guest_email_loader').setStyle('display','none');
					if(document.id('guest_email').hasClass('djinvalid')){
						document.id('guest_email').removeClass('invalid');
						document.id('guest_email').removeClass('djinvalid');
						document.id('guest_email-lbl').removeClass('invalid');
			    		document.id('guest_email').set('aria-invalid','false'); 
				    }
						 						
				}				 	
		    },
		    onFailure: function(){		    
		    }
		});
		myRequest.send();	 	      
	}

	
	<?php if($par->get('allow_user_lat_lng','0')){ 
		if(($id || $token) && $this->item->latitude && $this->item->longitude){
			$lat = $this->item->latitude;
			$lon = $this->item->longitude;
		}else if(isset($_COOKIE["djcf_latlon"])) {
			$lat_lon = explode('_', $_COOKIE["djcf_latlon"]);
			$lat = $lat_lon[0];
			$lon = $lat_lon[1];
		}else{
			$loc_coord = DJClassifiedsGeocode::getLocation($par->get('map_lat_lng_address','England, London'));
			if(is_array($loc_coord)){
				$lat = $loc_coord['lat'];
				$lon = $loc_coord['lng'];
			}else{
			
			}
		}				
		?>	
	    var map         = null;
	    var marker      = null;
	    var start_lat   = '<?php echo $lat; ?>';
	    var start_lon   = '<?php echo $lon; ?>';
	    var start_zoom  = <?php echo $par->get('gm_zoom','10'); ?>;
		var my_lat = start_lat;
		var my_lng = start_lon;
		var geokoder = new google.maps.Geocoder();
		
	    function initDjMap() {
	        //var zoom    = 13;
	        var coord   = new google.maps.LatLng(start_lat, start_lon);
	
	        var mapoptions = {
	            zoom: start_zoom,
	            center: coord,
	            mapTypeControl: true,
	            navigationControl: true,
	            zoomControl: true,        
	            mapTypeId: google.maps.MapTypeId.ROADMAP
	        }
	
	        // create the map
	        map = new google.maps.Map(document.getElementById('djmap'),mapoptions);
	
	        marker  = new google.maps.Marker({
	            position: coord,
	            draggable: true,
	            visible: true,
	            clickable: false,
	            map: map
	        });
	
	        google.maps.event.addListener(marker, 'dragend', function(event) {
	            latlng  = marker.getPosition();
	            my_lat     = latlng.lat();
	            my_lng     = latlng.lng();
	            document.getElementById('latitude').value   = my_lat;
	            document.getElementById('longitude').value  = my_lng;
	        });
	
	        google.maps.event.trigger(map, 'resize');
			map.setCenter(new google.maps.LatLng(my_lat,my_lng));
			
			map.setZoom( map.getZoom() );
	
			document.id('latitude').addEvent('change', function(){
				my_lat = this.value;
				map.setCenter(new google.maps.LatLng(my_lat,my_lng));
				coord   = new google.maps.LatLng(my_lat, my_lng);
			    marker.setPosition(coord);						
			});
			document.id('longitude').addEvent('change', function(){
				my_lng = this.value;			
				map.setCenter(new google.maps.LatLng(my_lat,my_lng));
				coord   = new google.maps.LatLng(my_lat, my_lng);
			    marker.setPosition(coord);	
			});
			document.id('map_use_my_location').addEvent('click', function(){
			  if(navigator.geolocation){
				  navigator.geolocation.getCurrentPosition(showDJPosition);
			   }else{
				   x.innerHTML="<?php echo JText::_('COM_DJCLASSIFIEDS_GEOLOCATION_IS_NOT_SUPPORTED_BY_THIS_BROWSER');?>";}
			});
			document.id('map_update_latlng').addEvent('click', function(){
				updateLatLngFromAddress();
			});		
			<?php if(!$id && $par->get('show_address','1')){ ?>
				document.id('address').addEvent('change', function(){
					updateLatLngFromAddress();
				});
			<?php } ?>													
		}
		
	    function showDJPosition(position){
		  	var exdate=new Date();
		  	exdate.setDate(exdate.getDate() + 1);
			var ll = position.coords.latitude+'_'+position.coords.longitude;
		  	document.cookie = "djcf_latlon=" + ll + "; expires=" + exdate.toUTCString();
		  	my_lat = position.coords.latitude;
		  	my_lng = position.coords.longitude;				
		  	document.getElementById('latitude').value   = my_lat;
            document.getElementById('longitude').value  = my_lng;
			map.setCenter(new google.maps.LatLng(my_lat,my_lng));
			var coord   = new google.maps.LatLng(my_lat, my_lng);
		    marker.setPosition(coord);	
	  	}

	  	function updateLatLngFromAddress(){
		  	var address = '';
		  	$$(document.getElementsByName('regions[]')).each(function(el){
			  	if(el.value){address = address+el.getSelected().get('text')+', ';}
		  	});
		  	address = address+document.id('address').value;
		  		geokoder.geocode(
				  	{address: address}, 
				  	function (results, status){
					    if(status == google.maps.GeocoderStatus.OK){
					    	my_lat = results[0].geometry.location.lat();
						  	my_lng = results[0].geometry.location.lng();				
						  	document.getElementById('latitude').value   = my_lat;
				            document.getElementById('longitude').value  = my_lng;
							map.setCenter(new google.maps.LatLng(my_lat,my_lng));
							var coord   = new google.maps.LatLng(my_lat, my_lng);
						    marker.setPosition(coord);	
						}else{
							document.id('mapalert').setStyle('display','block');
					      	(function() {
							    document.id('mapalert').setStyle('display','none');
							  }).delay(5000);   
						}
				});
		}
    <?php } ?>	


	<?php if($par->get('pay_desc_chars','0') && $par->get('durations_list','') && count($this->days)){ ?>
	var char_label_prices = [];
	var char_prices = [];	
	var char_points = [];		
	<?php if($id==0){ ?>
		document.id('exp_days').addEvent('change', function(){
			document.id('extra_char_price').innerHTML = char_label_prices[document.id('exp_days').value];
			descCharsLimit();
		});
	<?php } 

	 foreach($this->days as $day){
			if($day->char_price_default==0){
				$char_price = DJClassifiedsTheme::priceFormat($day->char_price,$par->get('unit_price'));
				if($points_a && $day->char_points>0){
					$char_price .= ' - '.$day->char_points.JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
				}
				echo 'char_label_prices['.$day->days.']="'.addslashes($char_price).'"; ';
				echo 'char_prices['.$day->days.']="'.addslashes($day->char_price).'"; ';
				echo 'char_points['.$day->days.']="'.addslashes($day->char_points).'"; ';
			}else{
				$char_price = DJClassifiedsTheme::priceFormat($par->get('desc_char_price','0'),$par->get('unit_price'));
				if($points_a && $par->get('desc_char_price_points','0')>0){
					$char_price .= ' - '.$par->get('desc_char_price_points','0').JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');
				}
				echo 'char_label_prices['.$day->days.']="'.addslashes($char_price).'"; ';
				echo 'char_prices['.$day->days.']="'.addslashes($par->get('desc_char_price','0')).'"; ';
				echo 'char_points['.$day->days.']="'.addslashes($par->get('desc_char_price_points','0')).'"; ';				
			}
		}
		?>

		var desc_chars_limit = <?php echo $par->get('pay_desc_chars_limit',0); ?>;
		var desc_chars_free_limit = <?php echo $par->get('pay_desc_chars_free_limit') + $this->item->extra_chars; ?>;
		
		function descCharsLimit(){
			if(document.id("djdesc").value.length<=desc_chars_limit || desc_chars_limit==0){
				var chars_c=document.id("djdesc").value.length;				
				document.id('desc_chars').innerHTML= chars_c; 
				if(chars_c>=desc_chars_free_limit){
					var chars_to_pay = chars_c - desc_chars_free_limit;
					var new_desc_price = 0;
					if(chars_to_pay>0){
						new_desc_price =  <?php echo "'".$par->get('unit_price')." '"; ?>+Math.round(char_prices[document.id('exp_days').value]*chars_to_pay*100)/100;
						<?php if($points_a){ ?>
							new_desc_price = new_desc_price+" - "+Math.round(char_points[document.id('exp_days').value]*chars_to_pay*100)/100+"<?php echo JTEXT::_('COM_DJCLASSIFIEDS_POINTS_SHORT');?>";							
						<?php }	?>
					}
					document.id('desc_price').innerHTML = new_desc_price;
				}else{
					document.id('desc_price').innerHTML =0;
				}									
			}else{
				document.id("djdesc").value = document.id("djdesc").value.substring(0, desc_chars_limit);
			}
		}
		
	<?php } ?> 
    
	

window.addEvent("load", function(){
	<?php if($par->get('show_introdesc','1')){?>
	introdescLimit(<?php echo $par->get('introdesc_char_limit');?>);
	<?php } ?>
	<?php if($par->get('seo_metadesc_user_edit','0')){?>
	metadescLimit(<?php echo $par->get('seo_metadesc_char_limit','160');?>);
	<?php } ?>
	<?php if($title_char_limit>0){?>
	titleLimit(<?php echo $title_char_limit;?>);
	<?php } ?>
	getFields(<?php echo $this->item->cat_id; ?>);	
	<?php if($par->get('allow_user_lat_lng','0')){ ?>
	initDjMap();
	<?php } ?>	
	<?php if($par->get('pay_desc_chars','0') && $par->get('durations_list','') && count($this->days)){ ?>
		descCharsLimit();
	<?php } ?>
});

window.addEvent('domready', function(){ 
   var JTooltips = new Tips($$('.Tips1'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_label', fixed: true
   });
   var JTooltips = new Tips($$('.Tips2'), {
      showDelay: 200, hideDelay: 200, className: 'djcf_prom', fixed: false
   });
   
   /*document.formvalidator.setHandler('djcat', function(value) {
      regex=/^p/;
      return !regex.test(value);
   });*/

   /*document.formvalidator.setHandler('djemail', function(value) {
	   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	   if(re.test(value)){
		   if(document.id('guest_email').hasClass('djinvalid')){
			  	return false;  
			}else{
				return true;
			}
	   }else{
		   return false;
	   }
   });*/
   
   document.id('submit_button').addEvent('click', function(){
   	
	   var cat_list = 	document.id('after_cat_0').getElements('select.cat_sel.required');
	   if(cat_list.length>0){
		   cat_list.each(function(cat_l){
			    var check_s = /^p/;
			    if(check_s.test(cat_l.get('value'))){			   
				   cat_l.addClass('invalid');
				   cat_l.setAttribute("aria-invalid", "true");
				   document.id('cat_0-lbl').addClass('invalid');
				   document.id('cat_0-lbl').setAttribute("aria-invalid", "true");			   
				   //console.log('invalid');		   
				}else{
				   cat_l.removeClass('invalid');
				   cat_l.setAttribute("aria-invalid", "false");
				   document.id('cat_0-lbl').removeClass('invalid');
				   document.id('cat_0-lbl').setAttribute("aria-invalid", "false");
				   //console.log('ok');
				}				   
			})
	   	}
        
       if(document.id('system-message-container')){ 
		   (function() {		   
			   if(document.id('system-message-container').innerHTML){
				   document.id('verification_alert_system').innerHTML = document.id('system-message-container').innerHTML;
				}else{
					 document.id('verification_alert_system').innerHTML = '';
				}
			  }).delay(1000);      	
       }

       
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