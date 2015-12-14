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
$toolTipArray = array('className'=>'djcf');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$user= JFactory::getUser();
$app = JFactory::getApplication();

$main_id= JRequest::getVar('cid', 0, '', 'int');
$fav_a	= $par->get('favourite','1');
$icon_new_a	= $par->get('icon_new','1');
$icon_new_date = mktime(date("G"), date("i"), date("s"), date("m"), date("d")-$par->get('icon_new_time','3'), date("Y"));
$icon_col_w = $par->get('smallth_width','56')+20;
$columns_a=2;

$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$sw = JRequest::getVar('search', '', '', 'string');
$uid	= JRequest::getVar('uid', 0, '', 'int');
$se = JRequest::getVar('se', '0', '', 'int');

$Itemid = JRequest::getVar('Itemid', 0, 'int');

$layout  = JRequest::getVar('layout','');
	if($layout=='favourites'){
		$menus	= $app->getMenu('site');	
		$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
		$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
						
		if($menu_item){
			$Itemid = $menu_item->id;
		}else if($menu_item_blog){
			$Itemid = $menu_item_blog->id;
		}		
	}

$se_link='';
if($se){
	$se_link='&se=1';	
	if($sw){
		$se_link .= '&search='.$sw; 
	}
	foreach($_GET as $key=>$get_v){
		if(strstr($key, 'se_')){
			if(is_array($get_v)){
				for($gvi=0;$gvi<count($get_v);$gvi++){
					$se_link .= '&'.$key.'[]='.$get_v[$gvi];	
				}
			}else{
				$se_link .= '&'.$key.'='.$get_v;		
			}
		}
	}
}
if(JRequest::getInt('fav','0')){
	$se_link.='&fav=1';
}?>

<div class="dj-items">
<div class="dj-items-table-smart">
<?php
$r=TRUE;
if($par->get('showitem_jump',0)){
	$anch = '#dj-classifieds';
}else{
	$anch='';
}
	$ad_position = floor(JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int')/2);
	$modules_djcf_table = &JModuleHelper::getModules('djcf-items-table');
	$mod_attribs=array();$mod_attribs['style'] = 'xhtml';
	$uid_slug = $this->profile['id'].':'.DJClassifiedsSEO::getAliasName($this->profile['name']);
	
			?>
	
			<div class="dj-items_order_by">
				<div class="dj-items_order_by_in">
					<div class="dj-items_order_by_label"><?php echo JText::_('COM_DJCLASSIFIEDS_SORT_BY')?></div>															
						<div class="dj-items_order_by_values">
						<a class="<?php if($order=="title"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=title&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_TITLE'); ?>						
						</a><span class="item_orderby_separator"></span>					
						<a class="<?php if($order=="cat"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=cat&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY'); ?>
						</a><span class="item_orderby_separator"></span> 	
						<a class="<?php if($order=="loc"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=loc&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');?>
						</a><span class="item_orderby_separator"></span>
						<a class="<?php if($order=="price"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=price&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_PRICE'); ?>
						</a><span class="item_orderby_separator"></span>
						<a class="<?php if($order=="date_a"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=date_a&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_ADDED'); ?>
						</a><span class="item_orderby_separator"></span>	
						<a class="<?php if($order=="date_e"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=date_e&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_EXPIRATION'); ?>
						</a><span class="item_orderby_separator"></span>
						<a class="<?php if($order=="display"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=display&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
							<?php echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED'); ?>
						</a>	
						<?php if($par->get('column_distance','0')){ ?>
							<span class="item_orderby_separator"></span>
							<a class="<?php if($order=="distance"){ echo "active";}?>" href="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=profile&uid='.$uid_slug.'&order=distance&ord_t='.$ord_t.'&Itemid='.$Itemid);?>">
								<?php echo JText::_('COM_DJCLASSIFIEDS_DISTANCE'); ?>
							</a>
						<?php } ?>			
					</div>									
				</div>		
			</div>
			<div class="dj-items-rows">						
			<?php 
				$irow=0;
				foreach($this->items as $i){
					if(!$i->alias){
						$i->alias = DJClassifiedsSEO::getAliasName($i->name);
					}
					if(!$i->c_alias){
						$i->c_alias = DJClassifiedsSEO::getAliasName($i->c_name);
					}
					$row = $r==TRUE ? '0' : '1';
					$r=!$r;
					if(count($modules_djcf_table)>0){
						if($irow==$ad_position){
							echo '<div class="item_row item_row'.$row.'"><div class="item_row_in">';
								foreach (array_keys($modules_djcf_table) as $m){
									echo JModuleHelper::renderModule($modules_djcf_table[$m],$mod_attribs);
								}
							echo '</div></div>';
							$row = $r==TRUE ? '0' : '1';
							$r=!$r;
						}
					}
						
					//if($i->special==1){$row.=' special special_first';}
					if($i->promotions){
						$row.=' promotion '.str_ireplace(',', ' ', $i->promotions);
					}
					$icon_fav=0;
					if($user->id>0 && $fav_a){
						if($i->f_id){
							$icon_fav=1;
							$row .= ' item_fav';
						}
					}
					$icon_new=0;
					$date_start = strtotime($i->date_start);
					if($date_start>$icon_new_date && $icon_new_a){
						$icon_new=1;
						$row .= ' item_new';
					}
					if(!$par->get('column_image','1')){
						$row .= ' no_img_column';
					}
					?>
					<div class="item_row item_row<?php echo $row; ?>"><div class="item_row_in" > 
					
							<?php 
							if($par->get('tooltip_img','1') || $par->get('tooltip_title','1')){
								$tip_title=str_ireplace('"',"'",$i->name);
								$tip_cont = '<div class=\'tp_desc\'>'.str_ireplace('"',"''",strip_tags(mb_substr($i->description,0,500,"UTF-8").'...')).'</div>';
								if($par->get('tooltip_location','1') && ($i->r_name || $i->address)){
									$tip_cont .= '<div class=\'row_location\'><div class=\'row_title\'>'.JText::_('COM_DJCLASSIFIEDS_LOCALIZATION').'</div><div class=\'tp_location\'>';
									$tip_cont .= $i->r_name.'<br />'.$i->address;
									$tip_cont .= '</div></div>';
								}
								if($par->get('tooltip_contact','1') && $i->contact && ($par->get('show_contact_only_registered',0)==0 || ($par->get('show_contact_only_registered',0)==1 && $user->id>0))){
									$tip_cont .= '<div class=\'row_contact\'><div class=\'row_title\'>'.JText::_('COM_DJCLASSIFIEDS_CONTACT').'</div><div class=\'tp_contact\'>'.str_ireplace('"',"''",strip_tags($i->contact)).'</div></div>';
								}
								if($par->get('tooltip_price','1') && $i->price && $par->get('show_price','1')){
									$tip_cont .= '<div class=\'row_price\'><div class=\'row_title\'>'.JText::_('COM_DJCLASSIFIEDS_PRICE').'</div><div class=\'tp_price\'>';
									$tip_cont .= DJClassifiedsTheme::priceFormat($i->price,$i->currency);
									$tip_cont .= '</div></div>';
								}
								$timg_limit = $par->get('tooltip_images','3');
								if(count($i->images) && $timg_limit>0){
									$tip_cont .= '<div style=\'clear:both\'></div><div class=\'title\'>'.JText::_('COM_DJCLASSIFIEDS_IMAGES').'</div><div class=\'images_box\'>';
									for($ii=0; $ii<count($i->images);$ii++ ){
										if($timg_limit==$ii){break;}
										$tip_cont .= '<img src=\''.JURI::base(true).$i->images[$ii]->thumb_s.'\' />';
									}
									$tip_cont .= '</div>';
								}
								$tip_cont .= '<div style=\'clear:both\'></div>';
							} ?>										
							<div class="item_outer">
								<div class="item_outer_in">
								<?php if($par->get('column_image','1')){ ?>
										<div class="item_img_box" >
											<div class="item_img_box_in">					
												<a href="<?php echo DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch; ?>">
													<?php 
													if(count($i->images)){
														echo '<img src="'.JURI::base(true).$i->images[0]->thumb_s.'"';
														if((int)$par->get('tooltip_img','1')){
															echo ' class="Tips1" title="'.$tip_title.'" rel="'.$tip_cont.'"';
														}
														echo ' alt ="'.str_ireplace('"', "'", $i->images[0]->caption).'" ';
														echo  '/>';
													}else{
														echo '<img style="width:'.$par->get("smallth_width",'56').'px;" src="'.JURI::base().$par->get('blank_img_path','/components/com_djclassifieds/assets/images/').'no-image.png" ';
														if((int)$par->get('tooltip_img','1')){
															echo ' class="Tips1" title="'.$tip_title.'" rel="'.$tip_cont.'"';
														}
														echo ' alt ="'.str_ireplace('"', "'", $i->name).'" ';
														echo '/>';
													} ?>
												</a>
											</div>
										</div>				
									<?php } ?>
									<div class="item_content">
										<div class="item_content_in">
											<div class="item_title">
												<?php 
												if((int)$par->get('tooltip_title','1')){
													echo '<h3><a class="title Tips1" href="'.DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch.'" title="'.$tip_title.'" rel="'.$tip_cont.'" >'.$i->name.'</a></h3>';
												}else{
													echo '<h3><a class="title" href="'.DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch.'" >'.$i->name.'</a></h3>';
												} 
												
												if($par->get('show_types','0') && $i->type_id>0){
													if(isset($this->types[$i->type_id])){
														$type = $this->types[$i->type_id];
														if($type->params->bt_class){
															$bt_class = ' '.$type->params->bt_class;
														}else{
															$bt_class = '';
														}
														echo '<div class="item_type" style="text-align:center" >';
														if($type->params->bt_use_styles){
															$style='style="display:inline-block;
															 			border:'.(int)$type->params->bt_border_size.'px solid '.$type->params->bt_border_color.';'
																	 		   .'background:'.$type->params->bt_bg.';'
																	 		   		.'color:'.$type->params->bt_color.';'
																	 		   				.$type->params->bt_style.'"';
															echo '<span class="type_button'.$bt_class.'" '.$style.' >'.$type->name.'</span>';
														}else{
															echo '<span class="type_label'.$bt_class.'" >'.$type->name.'</span>';
														}
														echo '</div>';
													}
												}
										
												if($icon_fav){
													//echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/themes/'.$this->theme.'/images/fav_a.png" class="fav_ico"/>';
													echo '<span class="fav_icon fav_icon_a" ></span>';
												}
												if($icon_new){
													echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
												}
											
												if(strstr($i->promotions, 'p_special')){
													//echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/themes/'.$this->theme.'/images/promo_star.png" class="prom_ico" alt="special" />';
													echo '<span class="prom_ico" ></span>';
												} 											
												if($i->auction){
													echo '<span class="auction_icon" ></span>';
												} ?> 
											</div>	
											<?php if($i->event->afterDJClassifiedsDisplayTitle) { ?>
												<div class="djcf_after_title">
													<?php echo $i->event->afterDJClassifiedsDisplayTitle; ?>
												</div>
											<?php } ?>	
											<?php if($par->get('column_category','1') || ($par->get('column_loc','1') && $par->get('show_regions','1'))){ ?>
												<div class="item_cat_region_outer">
													<?php if($par->get('column_category','1')){ ?>
														<div class="item_category">
															<?php echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($i->cat_id.':'.$i->c_alias).'" >'.$i->c_name.'</a>'; ?>
														</div>
													<?php } ?>
													<?php if($par->get('column_category','1') && ($par->get('column_loc','1') && $par->get('show_regions','1'))){ ?>
														<span class="item_cat_region_separator"></span>
													<?php }?>
													<?php if(($par->get('column_loc','1') && $par->get('show_regions','1')) || $par->get('column_distance','0')){ ?>
														<div class="item_region">
															<?php
																if($par->get('column_loc','1') && $par->get('show_regions','1')){
																	echo '<a href="'.$this->se_results_link.$i->region_id.'">'.$i->r_name.'</a>';
																}															 										
																if($par->get('column_distance','0')){ ?>
																	<div class="item_distance">
																		<?php 
																		if($i->latitude && $i->longitude){
																			if(isset($_COOKIE["djcf_latlon"])) {
																				echo '<span title="'.JText::_('COM_DJCLASSIFIEDS_IN_A_STRAIGHT_LINE').'">';
																				echo round($i->distance_latlon).' ';
																				if($par->get('column_distance_unit','km')=='km'){ echo JText::_('COM_DJCLASSIFIEDS_KM');
																				}else{ echo JText::_('COM_DJCLASSIFIEDS_MI');}
																				echo '</span>';
																				$daddr = $i->latitude.','.$i->longitude;
																				echo ' <a class="show_on_map" target="_blank" href="http://maps.google.com/maps?saddr='.str_ireplace('_', ',', $_COOKIE["djcf_latlon"]).'&daddr='.$daddr.'" >';
																				echo '<span data-hover="'.JText::_('COM_DJCLASSIFIEDS_SHOW_ON_MAP').'"></span>';
																				echo '</a>';
																			}else{
																				echo '<span onclick="getDJLocation()" class="show_distance" >'.JText::_('COM_DJCLASSIFIEDS_SHOW_DISTANCE').'</span>';
																			}
																		}else{
																			echo '---';
																		} ?>
																	</div>
																<?php } ?>
														</div>
													<?php } ?>		
												</div>	
											<?php }?>																	
											<?php if($i->event->beforeDJClassifiedsDisplayContent) { ?>
												<div class="djcf_before_content">
													<?php echo $i->event->beforeDJClassifiedsDisplayContent; ?>
												</div>
											<?php } ?>					
											<?php if($par->get('column_desc','1')){ ?>
												<div class="item_desc">
													<a href="<?php echo DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch; ?>" >
														<?php echo mb_substr(strip_tags($i->intro_desc), 0,$par->get('introdesc_char_limit','120'),'UTF-8'); ?>
													</a>
												</div>
											<?php } ?>
											<?php if($i->event->afterDJClassifiedsDisplayContent) { ?>
												<div class="djcf_after_content">
													<?php echo $i->event->afterDJClassifiedsDisplayContent; ?>
												</div>
											<?php } ?>																	
										</div>
									</div>
									<div class="clear_both"></div>							
								</div>
							</div>
							<div class="item_details">
								<div class="item_details_in">
									<?php 								
									if($par->get('column_price','1') && $par->get('show_price','1')){ 
										echo '<div class="item_price">';
											if($i->price){
												echo DJClassifiedsTheme::priceFormat($i->price,$i->currency);
											}
											if($i->price_negotiable){ 		
												echo '<span class="row_negotiable">';
													echo JText::_('COM_DJCLASSIFIEDS_PRICE_IS_NEGOTIABLE'); 
												echo '</span>';
											} 
										echo '</div>';	
									} 
									if($par->get('column_date_a','1')){
										echo '<div class="item_date_start">';
											echo DJClassifiedsTheme::formatDate(strtotime($i->date_start),'',$par->get('date_format_type',0));
										echo '</div>';
									}
									if($par->get('column_date_e','1')){
										echo '<div class="item_date_exp">';
											echo DJClassifiedsTheme::formatDate(strtotime($i->date_exp),'',$par->get('date_format_type',0));
										echo '</div>';
									}
									if($par->get('column_displayed','1')){
										echo '<div class="item_display" >'.$i->display.'</div>';
									} ?>
								</div>
							</div>
						<div class="clear_both"></div>							
						</div>
					</div>						
					<?php 
					$irow++;
				} ?>			
			</div>			
			<?php if($this->pagination->getPagesLinks()){ ?>
				<div class="pagination">
					<?php echo $this->pagination->getPagesLinks(); ?> 
				</div>
			<?php } ?>
			<?php 
			if($se>0 && count($this->items)==0){
				echo '<div class="no_results">';
					echo JText::_('COM_DJCLASSIFIEDS_NO_RESULTS');
				echo '</div>';
			}else if(!$se && count($this->items)==0 && $main_id){
				echo '<div class="no_results">';
					echo JText::_('COM_DJCLASSIFIEDS_NO_CATEGORY_RESULTS');
				echo '</div>';
			}
			?>
		</div>
	</div>	
	<?php if($par->get('column_distance','0')){ ?>
		<script type="text/javascript">
			function getDJLocation(){
			  if(navigator.geolocation){
				  navigator.geolocation.getCurrentPosition(showDJPosition);
			   }else{
				   x.innerHTML="<?php echo JText::_('COM_DJCLASSIFIEDS_GEOLOCATION_IS_NOT_SUPPORTED_BY_THIS_BROWSER');?>";}
			 }
			function showDJPosition(position){
			  	var exdate=new Date();
			  	exdate.setDate(exdate.getDate() + 1);
				var ll = position.coords.latitude+'_'+position.coords.longitude;
			  	document.cookie = "djcf_latlon=" + ll + "; expires=" + exdate.toUTCString();
			  	location.reload();
		  	}
		</script>
	<?php }?>		