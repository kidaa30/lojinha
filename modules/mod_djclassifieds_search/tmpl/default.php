<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Search Module
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
JHTML::_('behavior.calendar');
	$app 		= JFactory::getApplication();
	$config		= JFactory::getConfig();
	$document	= JFactory::getDocument();
	$menus		= $app->getMenu('site');
	$menu_item 	= $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
			
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
	
	$menu_custom = '';
	if($params->get('results_itemid',0)){
		$menu_custom = $menus->getItem($params->get('results_itemid',0));		
	}	
			
	$itemid = ''; 
	$itemid_url = '';
	$link_reset='index.php?option=com_djclassifieds&view=items&cid=0';
	
	if($params->get('result_view','0')==1 && $menu_item_blog){
		$itemid=$menu_item_blog->id;
		$link_reset='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$itemid;
		$itemid_url = $menu_item_blog->link;
	}
	if($menu_custom){
		$itemid=$menu_custom->id;
		$link_reset=$menu_custom->link;
		$itemid_url = $menu_custom->link;
	}
		
	if(!$itemid){
		if($menu_item){
			$itemid=$menu_item->id;
			$link_reset .= '&Itemid='.$itemid;
			$itemid_url = $menu_item->link;
		}else if($menu_item_blog){
			$itemid=$menu_item_blog->id;
			$link_reset='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$itemid;
			$itemid_url = $menu_item_blog->link;
		}
	}	

	
	$link_reset .= '&reset=1';
	
	$cid=0;	
	if($params->get('fallow_cat','1')==1 && JRequest::getVar('option') == 'com_djclassifieds'){
		$cid = JRequest::getInt('cid','0');
	}	
	$layout_cl = '';
	if($params->get('search_layout',0)){
		$layout_cl = ' dj_cf_search_horizontal';
	}
	
	if($params->get('show_address','0')==1){ 
		if($config->get('force_ssl',0)==2){
			$document->addScript("https://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
		}else{
			$document->addScript("http://maps.google.com/maps/api/js?sensor=false&amp;v=3.exp&amp;libraries=places");
		}		
	} 
	
?>
<div id="mod_djcf_search<?php echo $module->id;?>" class="dj_cf_search<?php echo $layout_cl;?>">
<form action="<?php echo JRoute::_($itemid_url.'&Itemid='.$itemid.'&se=1');?>" method="get" name="form-search<?php echo $module->id?>" id="form-search<?php echo $module->id?>">
	<?php if($config->get('sef')!=1 || !$itemid){ ?>
		<input type="hidden" name="option" value="com_djclassifieds" />
	   	<input type="hidden" name="view" value="items" />
	    <input type="hidden" name="Itemid" value="<?php echo $itemid;?>" /> 
   	<?php } ?> 
   	<input type="hidden" name="se" value="1" />
   	<input type="hidden" name="task" value="parsesearch" />	   	   
   	<?php
   	if($params->get('result_view','0')==1){
   		echo '<input type="hidden" name="layout" value="blog" />';
   	}   	
   	
   	if($params->get('show_input','1')==1){   ?>
   		<div class="search_word djcf_se_row"> 	
		   	<?php  $s_value = JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'));
		   	 // onblur="if(this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ? >';"
		   	 if($params->get('show_input_label','0')==1){ ?>
		   	 	<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_INPUT_LABEL'); ?></label>
		   	 <?php } ?>
			<input type="text" size="12" name="search" class="inputbox first_input" value="<?php echo $s_value; ?>" onfocus="if(this.value=='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ?>';" />
		</div>
	<?php }
	if(($params->get('show_postcode','0')==1 || $params->get('show_address','0')==1|| $params->get('show_geoloc','0')==1	) && $params->get('radius_list','')){		
			$s_postcode_value = JRequest::getVar('se_postcode',JText::_('COM_DJCLASSIFIEDS_SEARCH_POSTCODE'));
			$s_address_value = JRequest::getVar('se_address',JText::_('COM_DJCLASSIFIEDS_SEARCH_ADDRESS'));
			$radius_l = explode(',', $params->get('radius_list',''));
			$radius_list = array();
			$radius_unit = $params->get('radius_unit','km');
			foreach($radius_l as $radius){
				if($radius_unit=='mile'){
					$radius_label = $radius.' '.JText::_('COM_DJCLASSIFIEDS_SEARCH_MILES');
				}else{
					$radius_label = $radius.' '.JText::_('COM_DJCLASSIFIEDS_SEARCH_KM');
				}
				$radius_list[] = array('value'=>$radius,'text'=>$radius_label,'disabled'=>0);
			}
			
			if($params->get('show_address','0')){
				$se_radius_cl = 'se_radius_address';
			}else{
				$se_radius_cl = 'se_radius_postcode';
			}

			if($params->get('show_geoloc','0')){
				$se_radius_cl .= ' se_radius_geoloc';
			}
			
			?>
			<div class="search_radius djcf_se_row <?php echo $se_radius_cl;?>">
				<?php if($params->get('show_postcode_label','0')==1){ ?>
					<?php if($params->get('show_geoloc','0')==1){ ?>
	   					<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_GEOLOCALIZATION_LABEL'); ?></label>
	   				<?php }else if($params->get('show_address','0')==1){ ?>
	   					<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_ADDRESS_LABEL'); ?></label>
	   				<?php }else{ ?>
	   					<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_POSTCODE_LABEL'); ?></label>
					<?php } ?>	
	   			<?php } ?> 		
	   			<?php if($params->get('show_address','0')==1){ ?>
					<input type="text" size="12" id="se_address<?php echo $module->id;?>" name="se_address" class="inputbox" value="<?php echo $s_address_value; ?>" onfocus="if(this.value=='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_ADDRESS'); ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_ADDRESS'); ?>';" />
				<?php } else if($params->get('show_postcode','0')==1){?>
					<input type="text" size="12" name="se_postcode" id="se_postcode<?php echo $module->id?>" class="inputbox" value="<?php echo $s_postcode_value; ?>" onfocus="if(this.value=='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_POSTCODE'); ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_POSTCODE'); ?>';" />
				<?php } ?>
				
				<?php if($params->get('show_geoloc','0')==1){ ?>
					<?php if($params->get('show_address','0')==1 || $params->get('show_postcode','0')==1){ ?>
						<span class="se_geoloc_or_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_OR'); ?></span>
					<?php } ?>
					<span class="se_geoloc_icon button" id="se_geoloc_icon<?php echo $module->id;?>" title="<?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_GEOLOC_TOOLTIP_INFO'); ?>" ></span>
					<input type="hidden" name="se_geoloc" id="se_geoloc<?php echo $module->id;?>" value="<?php echo JRequest::getVar('se_geoloc',''); ?>" />
					<?php if($user_address){?>
						<div class="se_geoloc_address" >
							<?php echo $user_address; ?>
						</div>
					<?php } ?>
				<?php } ?>
				
				<div class="search_radius_range">				
					<label class="range_label"><?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_SEARCH_RANGE'); ?></label>
					<input type="hidden" name="se_postcode_c"  value="<?php echo $params->get('postcode_country',''); ?>"  />
					<input type="hidden" name="se_radius_unit"  value="<?php echo $radius_unit; ?>"  />
					<select  name="se_radius" class="inputbox" >
						<option value=""><?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH_SEARCH_RANGE');?></option>
						<?php echo JHtml::_('select.options', $radius_list, 'value', 'text', JRequest::getFloat('se_radius',$params->get('default_radius','50')), true);?>
					</select>
				</div>
			</div>
	<?php }
 	if($params->get('show_loc','1')==1){	?>
	<div class="search_regions djcf_se_row">
		<?php if($params->get('show_loc_label','0')==1){ ?>
	   		<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_LOZALIZATION_LABEL'); ?></label>
	   	<?php } ?> 			
		<?php 	
		$reg_sel = '<select  class="inputbox" id="se'.$module->id.'_reg_0" name="se_regs[]" onchange="se'.$module->id.'_new_reg(0,this.value,new Array());"><option value="0">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
				$parent_id=0;	
				$lc=0;
				$lcount = count($regions);
				
				foreach($regions as $l){
					$lc++;
					if($parent_id!=$l->parent_id){
						$reg_sel .= '</select>';
						echo $reg_sel;
						break;
					}	
					$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
					
					if($parent_id==$l->parent_id && $lc==$lcount){
						$reg_sel .= '</select>';
						echo $reg_sel;
						break;
					}
				}
				
				?>

				<div id="se<?php echo $module->id;?>_after_reg_0"></div>
				<script type="text/javascript">
					var se<?php echo $module->id;?>_regs=new Array();
					
				<?php
				/*$reg_sel = '<select class="inputbox" name="se_regs[]" id="se'.$module->id.'_reg_0" onchange="se'.$module->id.'_new_reg(0,this.value);">';
				$parent_id=0;	
				
				foreach($regions as $l){
					if($parent_id!=$l->parent_id){
						$reg_sel .= '</select>';
						echo "se".$module->id."_regs[$parent_id]='$reg_sel<div id=\"se".$module->id."_after_reg_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$reg_sel = '<select class="inputbox" name="se_regs[]" id="se'.$module->id.'_reg_'.$l->parent_id.'" onchange="se'.$module->id.'_new_reg('.$parent_id.',this.value);">';
						$reg_sel .= '<option value="'.$parent_id.'">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_LOCATION_SELECTOR_EMPTY_VALUE').'</option>';		
					}	
					$reg_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
				$reg_sel .= '</select>';	
				echo "se".$module->id."_regs[$parent_id]='$reg_sel<div id=\"se".$module->id."_after_reg_$parent_id\"></div>';";
				*/
				
					/*$se_url = '';
					foreach ($_GET as $k => $v) {						
						if(strstr($k,'se_')){
							$se_url .= '&'.$k.'='.$v;		
						}
					}*/
				?>	
				var se<?php echo $module->id;?>_current=0;
				
				function se<?php echo $module->id;?>_new_reg(parent,a_parent,r_path){

				  	var myRequest = new Request({
					    url: '<?php echo JURI::base()?>index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'view': 'item',
					      'task': 'getRegionSelect',
						  'reg_id': a_parent,
						  'mod_id': <?php echo $module->id;?>				  
						  },
					    onRequest: function(){
					    	document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = '<div style="text-align:center;"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/loading.gif" alt="..." /></div>';
					    	},
					    onSuccess: function(responseText){																
					    	if(responseText){	
								document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = responseText; 
								document.id('se<?php echo $module->id;?>_reg_'+parent).value=a_parent;
							}else{
								document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = '';
								document.id('se<?php echo $module->id;?>_reg_'+parent).value=a_parent;		
							}	
							if(r_path != 'null'){
								if(r_path.length>0){
									var first_path = r_path[0].split(',');												
									r_path.shift();
									se<?php echo $module->id;?>_new_reg(first_path[0],first_path[1],r_path);												
								}
							}
					    },
					    onFailure: function(){}
					});
					myRequest.send();	

					
					/*if(se<?php echo $module->id;?>_regs[a_parent]){
						//alert(cats[v]);
						if(parent==a_parent){
							document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = '';
						}else{
							document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = se<?php echo $module->id;?>_regs[a_parent];
						}													 
						document.id('se<?php echo $module->id;?>_reg_'+parent).value=a_parent;
					}else{
						document.id('se<?php echo $module->id;?>_after_reg_'+parent).innerHTML = '';
						document.id('se<?php echo $module->id;?>_reg_'+parent).value=a_parent;		
					}*/
					
				}				
				</script>

		</div>	
	<?php }
	
	if($params->get('show_cat','1')==1 && count($categories)){	?>
	<div class="search_cats djcf_se_row">
	 <?php if($params->get('show_cat_label','0')==1){ ?>
   	 	<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_CATEGORY_LABEL'); ?></label>
   	 <?php } ?> 
		<?php 
			$cat_sel = '<select  class="inputbox" id="se'.$module->id.'_cat_0" name="se_cats[]" onchange="se'.$module->id.'_new_cat(0,this.value,new Array());se'.$module->id.'_getFields(this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY').'</option>';
				/*$parent_id=0;	
				$lc=0;
				$lcount = count($list);
				
				foreach($list as $l){
					$lc++;
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo $cat_sel;
						break;
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
					
					if($parent_id==$l->parent_id && $lc==$lcount){
						$cat_sel .= '</select>';
						echo $cat_sel;
						break;
					}
				}*/
				foreach($categories[0] as $cat){
					$cat_sel .= '<option value="'.$cat->id.'">'.str_ireplace("'", "&apos;", $cat->name).'</option>';
				}	
				$cat_sel .= '</select>';
				echo $cat_sel;
				
				?>

				<div id="se<?php echo $module->id;?>_after_cat_0"></div>
				<script type="text/javascript">
					var se<?php echo $module->id;?>_cats=new Array();
					
				<?php
				//$cat_sel = '<select class="inputbox" name="se_cats[]" id="se_cat_0" onchange="se_new_cat(0,this.value);se_getFields(this.value);">';
				/*$parent_id=0;	
				
				foreach($list as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo "se_cats[$parent_id]='$cat_sel<div id=\"se_after_cat_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$cat_sel = '<select class="inputbox select_mod" name="se_cats[]" id="se_cat_'.$l->parent_id.'" onchange="se_new_cat('.$parent_id.',this.value);se_getFields(this.value);">';
						$cat_sel .= '<option value="p'.$parent_id.'">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_CATEGORY_SELECTOR_EMPTY_VALUE').'</option>';		
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
				$cat_sel .= '</select>';	
				echo "se_cats[$parent_id]='$cat_sel<div id=\"se_after_cat_$parent_id\"></div>';";*/
				/*$cat_sel = '';
				foreach($categories as $cpar_id => $cat_group){
					$cat_sel = '<select class="inputbox" name="se_cats[]" id="se'.$module->id.'_cat_'.$cpar_id.'" onchange="se'.$module->id.'_new_cat('.$cpar_id.',this.value);se'.$module->id.'_getFields(this.value);">';
					$cat_sel .= '<option value="p'.$cpar_id.'">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_CATEGORY_SELECTOR_EMPTY_VALUE').'</option>';																
					foreach($cat_group as $cat){
						$cat_sel .= '<option value="'.$cat->id.'">'.str_ireplace("'", "&apos;", $cat->name).'</option>';	
					}	
					$cat_sel .= '</select>';
					echo "se".$module->id."_cats[$cpar_id]='$cat_sel<div id=\"se".$module->id."_after_cat_$cpar_id\"></div>';";
				}*/
				
				
					/*$se_url = '';
					foreach ($_GET as $k => $v) {						
						if(strstr($k,'se_')){
							$se_url .= '&'.$k.'='.$v;		
						}
					}*/
				?>	
				var se_current=0;
				
				function se<?php echo $module->id;?>_new_cat(parent,a_parent,c_path){


				  	var myRequest = new Request({
					    url: '<?php echo JURI::base()?>index.php',
					    method: 'post',
						data: {
					      'option': 'com_djclassifieds',
					      'view': 'item',
					      'task': 'getCategorySelect',
						  'cat_id': a_parent,
						  'mod_id': <?php echo $module->id;?>				  
						  },
					    onRequest: function(){
					    	document.id('se<?php echo $module->id;?>_after_cat_'+parent).innerHTML = '<div style="text-align:center;"><img src="<?php echo JURI::base(true) ?>/components/com_djclassifieds/assets/images/loading.gif" alt="..." /></div>';					    	
					    	},
					    onSuccess: function(responseText){																
					    	if(responseText){	
								document.id('se<?php echo $module->id;?>_after_cat_'+parent).innerHTML = responseText; 
								document.id('se<?php echo $module->id;?>_cat_'+parent).value=a_parent;
							}else{
								document.id('se<?php echo $module->id;?>_after_cat_'+parent).innerHTML = '';
								document.id('se<?php echo $module->id;?>_cat_'+parent).value=a_parent;		
							}	
							if(c_path != 'null'){
								if(c_path.length>0){
									var first_path = c_path[0].split(',');												
									c_path.shift();
									se<?php echo $module->id;?>_new_cat(first_path[0],first_path[1],c_path);												
								}
							}
					    },
					    onFailure: function(){}
					});
					myRequest.send();	
				

					
					/*if(se<?php echo $module->id;?>_cats[a_parent]){
						//alert(se_cats[v]);	
						document.id('se<?php echo $module->id;?>_after_cat_'+parent).innerHTML = se<?php echo $module->id;?>_cats[a_parent]; 
						document.id('se<?php echo $module->id;?>_cat_'+parent).value=a_parent;
					}else{
						document.id('se<?php echo $module->id;?>_after_cat_'+parent).innerHTML = '';
						document.id('se<?php echo $module->id;?>_cat_'+parent).value=a_parent;		
					}*/
					
				}
				
			function se<?php echo $module->id;?>_getFields(cat_id){
					
				var el = document.getElementById("search<?php echo $module->id;?>_ex_fields");
				var before = document.getElementById("search<?php echo $module->id;?>_ex_fields").innerHTML.trim();	
				
				if(cat_id!=0){	
					el.innerHTML = '<div style="text-align:center"><img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" /></div>';
					var url = '<?php echo JURI::base();?>index.php?option=com_djclassifieds&view=item&task=getSearchFields&cat_id=' + cat_id;
								 var myRequest = new Request({							    
								 <?php  $lang_code = explode('-', JRequest::getVar('lang','')); ?>   
								url: '<?php echo JURI::base();?>index.php<?php if(JRequest::getVar('lang','')){echo '?lang='.$lang_code[0];}?>',
							    method: 'post',
								data: {
							      'option': 'com_djclassifieds',
							      'view': 'item',
							      'task': 'getSearchFields',
								  'cat_id': cat_id			  
								  },
							    onRequest: function(){
							        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
							    },
							    onSuccess: function(responseText){																
									el.innerHTML = responseText;
									if(responseText){
										el.removeClass('no_fields');
									}else{
										el.addClass('no_fields');
									}
									var djcals = document.getElements('.djsecal');
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
									var djfields_accordion_o = document.getElements('#search<?php echo $module->id;?>_ex_fields .djfields_accordion_o');
									if(djfields_accordion_o){										
										djfields_accordion_o.each(function(djfields_acc_o,index){
											 new Fx.Accordion(djfields_acc_o.getElements('.label'),
													 djfields_acc_o.getElements('.se_checkbox'), {
													alwaysHide : true,
													display : 0,
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
										})										
									}
									
									var djfields_accordion_c = document.getElements('#search<?php echo $module->id;?>_ex_fields .djfields_accordion_c');
									if(djfields_accordion_c){										
										djfields_accordion_c.each(function(djfields_acc_c,index){
											 new Fx.Accordion(djfields_acc_c.getElements('.label'),
													djfields_acc_c.getElements('.se_checkbox'), {
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
										})										
									}	
														 	
							    },
							    onFailure: function(){
							       // myElement.set('html', 'Sorry, your request failed, please contact to ');
							    }
							});
							myRequest.send();	
				}else{
					el.innerHTML = '';
					//el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
				}
					
			}
				<?php // echo $this->cat_path; ?>
								
				
				</script>

		</div>		
		<div style="clear:both"></div>
		<div id="search<?php echo $module->id;?>_ex_fields" class="search_ex_fields no_fields"></div>
		<div style="clear:both"></div>
		<?php }
		if($params->get('show_type','0')==1 && $types){ ?>
			<div class="search_type djcf_se_row">
				<?php if($params->get('show_type_label','0')==1){ ?>
	   	 			<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_TYPE_LABEL'); ?></label>
	   	 		<?php } ?> 				
				<select  name="se_type_id" class="inputbox" >
					<option value=""><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_TYPE');?></option>
					<?php echo JHtml::_('select.options', $types, 'value', 'text', JRequest::getInt('se_type_id'), true);?>
				</select>
			</div>
		<?php }
		if($params->get('show_time','0')==1 && $types){ 
			$days_l = JRequest::getInt('days_l','');?>
			<div class="search_time djcf_se_row">		
				<?php if($params->get('show_time_label','0')==1){ ?>
	   				<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_TIME_LABEL'); ?></label>
	   			<?php } ?> 						
				<select  name="days_l" class="inputbox" >
					<option <?php if($days_l==''){echo ' SELECTED ';}?> value=""><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_ANY_TIME');?></option>
					<option <?php if($days_l=='1'){echo ' SELECTED ';}?> value="1"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PAST_24_HOURS');?></option>
					<option <?php if($days_l=='3'){echo ' SELECTED ';}?> value="3"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PAST_THREE_DAYS');?></option>
					<option <?php if($days_l=='7'){echo ' SELECTED ';}?> value="7"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PAST_WEEK');?></option>
					<option <?php if($days_l=='30'){echo ' SELECTED ';}?> value="30"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PAST_MONTH');?></option>					
				</select>
			</div>
		<?php }  
		if($params->get('show_price','1')==1){	?>
			<div class="search_price djcf_se_row">
				<?php if($params->get('show_price_label','1')==1){ ?>			 	
					<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE').' ( '.$comparams->get('unit_price','EUR').' )'; ?></label>
				<?php } ?>	
				<span class="label1"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE_FROM'); ?></span>
				<input size="5" class="inputbox" type="text" name="se_price_f" value="<?php echo JRequest::getVar('se_price_f',''); ?>"/>
				<span class="label2"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE_TO'); ?></span>
				<input size="5" class="inputbox" type="text" name="se_price_t" value="<?php echo JRequest::getVar('se_price_t',''); ?>"/>
			</div>
	<?php }?>
	<?php if($params->get('show_only_auctions','0')==1){	?>
			<div class="search_only_auctions djcf_se_row_ch">							
				<input  class="inputbox" <?php if(JRequest::getInt('se_only_auctions',0)){echo ' checked="checked" ';}?> type="checkbox" name="se_only_auctions" value="1"/>
				<span class="label"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SHOW_ONLY_AUCTIONS'); ?></span>
			</div>
	<?php }?>
	<?php if($params->get('show_only_images','0')==1){	?>
			<div class="search_only_images djcf_se_row_ch">							
				<input  class="inputbox" <?php if(JRequest::getInt('se_only_img',0)){echo ' checked="checked" ';}?> type="checkbox" name="se_only_img" value="1"/>
				<span class="label"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SHOW_ONLY_WITH_IMAGES'); ?></span>
			</div>
	<?php }?>
	<?php if($params->get('show_only_video','0')==1){	?>
			<div class="search_only_video djcf_se_row_hc">							
				<input  class="inputbox" <?php if(JRequest::getInt('se_only_video',0)){echo ' checked="checked" ';}?> type="checkbox" name="se_only_video" value="1"/>
				<span class="label"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SHOW_ONLY_WITH_VIDEO'); ?></span>
			</div>
	<?php }?>
	<?php if($params->get('show_restriction_18','0')==1){	?>
			<div class="search_also_18 djcf_se_row_ch">							
				<input  class="inputbox" <?php if(JRequest::getInt('se_also_18',0)){echo ' checked="checked" ';}?> type="checkbox" name="se_also_18" value="1"/>
				<span class="label"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SHOW_RESTRICTION_18_LABEL'); ?></span>
			</div>
	<?php }?>
	<div class="search_buttons">										
		<button type="submit" class="button btn"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SEARCH');?></button>		
		<?php 
		if((JRequest::getInt('se',0)==1 || (JRequest::getInt('cid',0)>0 && JRequest::getInt('option','')=='com_djclassifieds')) && ($params->get('show_reset','1')>0) ){ 
			if($params->get('show_reset','1')==1){ ?>
				<a href="<?php echo JRoute::_($link_reset);?>" class="reset_button"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_RESET');?></a>	
			<?php }else{ ?>
				<a href="<?php echo JRoute::_($link_reset);?>" class="button"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_RESET');?></a>
			<?php } ?>
			
				
		<?php } ?>
	</div>
	 
</form>
 
<div style="clear:both"></div>
</div>

<?php
$cat_id_se = 0;
if($params->get('show_cat','1')==1){
	if(JRequest::getVar('se','0','','string')!='0' && isset($_GET['se_cats'])){
		if(is_array($_GET['se_cats'])){
			$cat_id_se= end($_GET['se_cats']);
			if($cat_id_se=='' && count($_GET['se_cats'])>2){
				$cat_id_se =$_GET['se_cats'][count($_GET['se_cats'])-2];
			}
		}else{
			$cat_ids_se = explode(',', JRequest::getVar('se_cats'));
			$cat_id_se = end($cat_ids_se);
		}		
		$cat_id_se = str_ireplace('p', '', $cat_id_se);
		$cat_id_se = (int)$cat_id_se;
	}
	if($cat_id_se=='0'){		
		$cat_id_se = $cid;	
	}	
		
	$se_parents = array();
	
	if($cat_id_se){
		$se_parents[] = $cat_id_se;
	}	
	
	$act_parent = 0;
	if($cat_id_se > 0){
		foreach($list as $c){
			if($cat_id_se == $c->id ){
				$se_parents[] = $c->parent_id.','.$c->id;
				$act_parent = $c->parent_id;
				break;		
			}
		}
		while($act_parent!=0){
			foreach($list as $c){
				if($act_parent == $c->id ){
					$se_parents[] = $c->parent_id.','.$c->id;
					$act_parent = $c->parent_id;
					break;		
				}
			}	
		}
		
	}
}
$reg_id_se = 0;

if($params->get('show_loc','1')==1){
	$act_reg_parent = 0;
	if(JRequest::getVar('se','0','','string')!='0' && isset($_GET['se_regs'])){		
		if(is_array($_GET['se_regs'])){
			$reg_id_se= end($_GET['se_regs']);
			if($reg_id_se=='' && count($_GET['se_regs'])>2){
				$reg_id_se =$_GET['se_regs'][count($_GET['se_regs'])-2];
			}
		}else{
			$reg_ids_se = explode(',', JRequest::getVar('se_regs'));
			$reg_id_se = end($reg_ids_se);
		}
		$reg_id_se=(int)$reg_id_se;		
	}
	
	if($reg_id_se=='0'){
		$reg_id_se = JRequest::getInt('rid','0');	
	}	
	$se_reg_parents = array();
	if($reg_id_se){
		$se_reg_parents[] = $reg_id_se; 	
	}
	
	if($reg_id_se > 0){
		foreach($regions as $r){
			if($reg_id_se == $r->id ){
				$se_reg_parents[] = $r->parent_id.','.$r->id;
				$act_reg_parent = $r->parent_id;
				break;		
			}
		}
		while($act_reg_parent!=0){
			foreach($regions as $r){
				if($act_reg_parent == $r->id ){
					$se_reg_parents[] = $r->parent_id.','.$r->id;
					$act_reg_parent = $r->parent_id;
					break;		
				}
			}	
		}
	}	
}
if($cat_id_se > 0 || $reg_id_se > 0){ 

	?>
	<script type="text/javascript">
		window.addEvent("load", function(){
			<?php 		
			if($cat_id_se>0){
				/*for($sp=count($se_parents)-1;$sp>0 ;$sp--){		
					echo 'se'.$module->id.'_new_cat('.$se_parents[$sp] .','.$se_parents[$sp-1].');';
				} 
				?>
				se<?php echo $module->id;?>_new_cat(<?php echo $se_parents[0]; ?>,<?php echo $cat_id_se; ?>);*/

				$c_path = $se_parents;
					
					echo 'var cat_path = new Array();';
					$cat_path_f = 'se'.$module->id.'_new_cat(';
					for($r=count($c_path)-1;$r>0;$r--){
						if($r<count($c_path)-1){
							$ri = count($c_path) - $r -2;
							echo "cat_path[$ri]='$c_path[$r]';";
						}else{
							$cat_path_f .= $c_path[$r];
						}
					} 
							
					echo $cat_path_f.',cat_path);'; ?>


				se<?php echo $module->id;?>_getFields(<?php echo $cat_id_se; ?>);
				
			<?php } ?>
			<?php
			if($reg_id_se > 0){				
					$r_path = $se_reg_parents; 
					
					echo 'var reg_path = new Array();';
					$reg_path_f = 'se'.$module->id.'_new_reg(';
					for($r=count($r_path)-1;$r>0;$r--){
						if($r<count($r_path)-1){
							$ri = count($r_path) - $r -2;
							echo "reg_path[$ri]='$r_path[$r]';";
						}else{
							$reg_path_f .= $r_path[$r];
						}
					}
					
					echo $reg_path_f.',reg_path);';
				

				//print_r($se_reg_parents);die();
				/*for($sp=count($se_reg_parents)-1;$sp>0 ;$sp--){		
					echo 'se'.$module->id.'_new_reg('.$se_reg_parents[$sp] .','.$se_reg_parents[$sp-1].');';
				} */
				
				/*if($reg_id_se>0){ ?>
				se<?php echo $module->id;?>_new_reg(<?php echo $se_reg_parents[0]; ?>,<?php echo $reg_id_se; ?>);
				<?php }*/
			}	 ?>			
		});
	</script>
	<?php
	
}

	if($cat_id_se==0 && $params->get('show_cat','1')==1 && $params->get('cat_id','0')>0){
		if(JRequest::getInt('option','')!='com_djclassifieds' || (JRequest::getInt('se',0)==0 && JRequest::getInt('option','')=='com_djclassifieds')){
			$cat_id = $params->get('cat_id','0');
			$se_parents = array();
			$se_parents[]=$cat_id;
			$act_parent = 0;
				foreach($list as $c){
					if($cat_id == $c->id ){
						$se_parents[] = $c->parent_id.','.$c->id;
						$act_parent = $c->parent_id;
						break;		
					}
				}
				while($act_parent!=0){
					foreach($list as $c){
						if($act_parent == $c->id ){
							$se_parents[] = $c->parent_id.','.$c->id;
							$act_parent = $c->parent_id;
							break;		
						}
					}	
				}
				
				//print_r($se_parents);die();
		
		?>
			<script type="text/javascript">		
			window.addEvent("load", function(){
				<?php
				/*for($sp=count($se_parents)-1;$sp>0 ;$sp--){
					echo 'se'.$module->id.'_new_cat('.$se_parents[$sp] .','.$se_parents[$sp-1].');';
				}
				?>
				se<?php echo $module->id;?>_new_cat(<?php echo $se_parents[0]; ?>,<?php echo $cat_id; ?>);
				se<?php echo $module->id;?>_getFields(<?php echo $cat_id; ?>);*/

					if($cat_id>0){
					
						$c_path = $se_parents;
							
						echo 'var cat_path = new Array();';
										$cat_path_f = 'se'.$module->id.'_new_cat(';
										for($r=count($c_path)-1;$r>0;$r--){
										if($r<count($c_path)-1){
						$ri = count($c_path) - $r -2;
							echo "cat_path[$ri]='$c_path[$r]';";
						}else{
							$cat_path_f .= $c_path[$r];
						}
					}
						
					echo $cat_path_f.',cat_path);'; ?>


					se<?php echo $module->id;?>_getFields(<?php echo $cat_id; ?>);
					
				<?php } ?>


			});
			</script>
			
			
			
			
		<?php 	
		} 
	}
	if($params->get('show_address','0')==1){ ?>
		<script type="text/javascript">
			window.addEvent('domready', function(){
				djcfmodSearchPlaces<?php echo $module->id;?>();		
			});
			
			function djcfmodSearchPlaces<?php echo $module->id;?>(){
				var input = (document.getElementById('se_address<?php echo $module->id;?>'));								
				var aut_options = '';
				<?php if($params->get('api_country','')!=''){ ?>
					var aut_options = {					
						  componentRestrictions: {country: '<?php echo $params->get('api_country',''); ?>'}
						};
				<?php } ?>
				var autocomplete = new google.maps.places.Autocomplete(input,aut_options);									 
				var infowindow = new google.maps.InfoWindow();
				var last_place = '';
					google.maps.event.addListener(autocomplete, 'places_changed', function() {				
				    /*	var place = autocomplete.getPlaces()[0]; //to get first on enter
				    	if (!place.geometry) {
				      		return;
				    	}
				    	
					    if (place.geometry.viewport) {
					      djmod_map< ?php echo $module->id;?>.fitBounds(place.geometry.viewport);
					    } else {
					      djmod_map< ?php echo $module->id;?>.setCenter(place.geometry.location);
					    }
					    */
				   
				  });
				  
				 
		            // dojo.connect(input, 'onkeydown', function(e) {
		            google.maps.event.addDomListener(input, 'keydown', function(e) {
		                    if (e.keyCode == 13)
		                    {
		                            if (e.preventDefault)
		                            {
		                                    e.preventDefault();
		                            }
		                            else
		                            {
		                                    // Since the google event handler framework does not handle early IE versions, we have to do it by our self. :-(
		                                    e.cancelBubble = true;
		                                    e.returnValue = false;
		                            }
		                    }
		            }); 
				  
			}		
			
			
		</script>
	<?php }
	
	if($params->get('show_geoloc','0')==1){ ?>
		<script type="text/javascript">
			window.addEvent('domready', function(){
				document.id('se_geoloc_icon<?php echo $module->id;?>').addEvent('click',function(event){					
					if(navigator.geolocation){
						navigator.geolocation.getCurrentPosition(modSearchShowDJPosition<?php echo $module->id;?>,
							 function(error){
	         					alert("<?php echo str_ireplace('"', "'",JText::_(''));?>");
	         				
						    }, {
						         timeout: 30000, enableHighAccuracy: true, maximumAge: 90000
						    });
					} 
				})
			});		
	


			function modSearchShowDJPosition<?php echo $module->id;?>(position){
			  	var exdate=new Date();
			  	exdate.setDate(exdate.getDate() + 1);
				var ll = position.coords.latitude+'_'+position.coords.longitude;
			  	document.cookie = "djcf_latlon=" + ll + "; expires=" + exdate.toUTCString();
			  	//document.id('se_postcode<?php echo $module->id?>').value = '00-000';
			  	document.id('se_geoloc<?php echo $module->id?>').value = '1';
			  	document.id('form-search<?php echo $module->id; ?>').submit();				  	
		  	}
		</script>
	
	
	<?php } 
?>