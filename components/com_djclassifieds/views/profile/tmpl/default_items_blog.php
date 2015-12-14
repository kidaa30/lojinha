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
	<div class="dj-items-blog">
	<?php
		$blog_sort_v = $par->get('blog_sorting_fields',array());
		if($par->get('blog_sorting',0) && count($blog_sort_v)){			
			$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
			$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
			$current_uri = JFactory::getURI();
			?>
			<div class="blog_sorting_box">
				<form action="<?php echo $current_uri; ?>" method="get" name="djblogsort" id="djblogsort_form" >
					<select id="blogorder_select" class="inputbox" >
						<?php 				
							foreach($blog_sort_v as $sort_v){
								$option_selected = '';
								if($order==$sort_v && $ord_t=='asc'){
									$option_selected = 'selected="SELECTED"';
								}						
								echo '<option value="'.$sort_v.'-asc" '.$option_selected.' >';
									echo JText::_('COM_DJCLASSIFIEDS_SORT_BY').' '; 
									if($sort_v=='date_a'){ echo JText::_('COM_DJCLASSIFIEDS_DATE_ADDED');
									}else if($sort_v=='date_sort'){ echo JText::_('COM_DJCLASSIFIEDS_DATE_EXPIRATION');
									}else if($sort_v=='title'){ echo JText::_('COM_DJCLASSIFIEDS_TITLE');
									}else if($sort_v=='cat'){ echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');
									}else if($sort_v=='loc'){ echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');
									}else if($sort_v=='price'){ echo JText::_('COM_DJCLASSIFIEDS_PRICE');
									}else if($sort_v=='display'){ echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED'); 
									}else if($sort_v=='distance'){ echo JText::_('COM_DJCLASSIFIEDS_DISTANCE'); }
									else{echo $sort_v;}
									echo ' '.JText::_('COM_DJCLASSIFIEDS_SORT_BY_ASC');
								echo  '</option>';
								
								$option_selected = '';
								if($order==$sort_v && $ord_t=='desc'){
									$option_selected = 'selected="SELECTED"';
								}
								
								echo '<option value="'.$sort_v.'-desc" '.$option_selected.' >';
									echo JText::_('COM_DJCLASSIFIEDS_SORT_BY').' '; 
									if($sort_v=='date_a'){ echo JText::_('COM_DJCLASSIFIEDS_DATE_ADDED');
									}else if($sort_v=='date_sort'){ echo JText::_('COM_DJCLASSIFIEDS_DATE_EXPIRATION');
									}else if($sort_v=='title'){ echo JText::_('COM_DJCLASSIFIEDS_TITLE');
									}else if($sort_v=='cat'){ echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');
									}else if($sort_v=='loc'){ echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');
									}else if($sort_v=='price'){ echo JText::_('COM_DJCLASSIFIEDS_PRICE');
									}else if($sort_v=='display'){ echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED'); 
									}else if($sort_v=='distance'){ echo JText::_('COM_DJCLASSIFIEDS_DISTANCE'); }
									else{echo $sort_v;}
									echo ' '.JText::_('COM_DJCLASSIFIEDS_SORT_BY_DESC');
								echo  '</option>';
							}	
						?>
					</select> 
					<input type="hidden" name="order" id="blogorder_v" value="<?php echo $order;?>" />
					<input type="hidden" name="ord_t" id="blogorder_t_v" value="<?php echo $ord_t;?>" />
					<script type="text/javascript">
						window.addEvent('load', function(){		
							var slider_box = document.id('blogorder_select');
							slider_box.addEvent('change',function(event){
								var order_v = this.value.toString().split('-');
								document.id('blogorder_v').value=order_v[0];
								document.id('blogorder_t_v').value=order_v[1];
								document.id('djblogsort_form').submit();
							})
						});
					</script>
					<?php
					if($se){
						echo '<input type="hidden" name="se" value="1" />';							
						if($sw){ echo '<input type="hidden" name="search" value="'.$sw.'" />';}
						foreach($_GET as $key=>$get_v){
							if(strstr($key, 'se_')){
								if(is_array($get_v)){
									for($gvi=0;$gvi<count($get_v);$gvi++){
										echo '<input type="hidden" name="'.$key.'[]" value="'.$get_v[$gvi].'" />';
									}
								}else{
									echo '<input type="hidden" name="'.$key.'" value="'.$get_v.'" />';
								}
							}
						}
					}
					?>					
				</form>	
			</div>		
			<?php 	
		} ?>
	
	<div class="djcf_items_blog">
	<?php 		
		$r=TRUE;
		
		if($par->get('showitem_jump',0)){
			$anch = '#dj-classifieds';
		}else{
			$anch='';
		}
		//$img_w = $par->get("middleth_width")+15;
		$col_n = 100 / $par->get('blog_columns_number',2) - 0.1;
		$col_limit = $par->get('blog_columns_number',2);	
		$ii=0;	
		
		foreach($this->items as $i){
			if(!$i->alias){
				$i->alias = DJClassifiedsSEO::getAliasName($i->name);
			}
			if(!$i->c_alias){
				$i->c_alias = DJClassifiedsSEO::getAliasName($i->c_name);					
			}			
			$cn= $ii%$par->get('blog_columns_number');
			//echo $col_limit.' '.$cn;
			if($cn==$col_limit-1){
				$cn .= ' last_col';
			}
			$ii++;
			//if($i->special==1){$row=' special special_first';}else{$row='';}
			$row = '';
			if($i->promotions){
				$row .=' promotion '.str_ireplace(',', ' ', $i->promotions);
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
			
			echo '<div class="item_box'.$row.'" style="width:'.$col_n.'%;"><div class="item_box_bg'.$cn.'"><div class="item_box_in"><div class="item_box_in2 clearfix">';
			echo '<div class="title">';
				echo '<h2><a href="'.DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch.'" >'.$i->name.'</a></h2>';			
				if($par->get('show_types','0') && $i->type_id>0){
					if(isset($this->types[$i->type_id])){
						$type = $this->types[$i->type_id];
						if($type->params->bt_class){
							$bt_class = ' '.$type->params->bt_class;
						}else{
							$bt_class = '';
						}	
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
					}
				}								
				if($icon_fav){
					//echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/themes/'.$this->theme.'/images/fav_a.png" class="fav_ico"/>';
					echo '<span class="fav_icon fav_icon_a" ></span>';
				}			
				if($icon_new){
					echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
				}				
			echo '</div>';
			if($i->event->afterDJClassifiedsDisplayTitle) { ?>
				<div class="djcf_after_title">
					<?php echo $i->event->afterDJClassifiedsDisplayTitle; ?>
				</div>
			<?php } 			
			echo '<div class="blog_det">';	
				
				if($par->get('blog_desc_position','right')=='right'){
					echo '<div class="item_box_right">';
				}									
					if(($par->get('blog_image','1')==1 && count($i->images)) || $par->get('blog_image','1')==2){
						echo '<div class="item_img">';						
						echo '<a href="'.DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch.'">';
							if(count($i->images)){
								echo '<img src="'.JURI::base(true).$i->images[0]->thumb_m.'" alt="'.str_ireplace('"', "'", $i->images[0]->caption).'"  />';
							}else{
								echo '<img style="width:'.$par->get("middleth_width",'150').'px;" src="'.JURI::base(true).$par->get('blank_img_path','/components/com_djclassifieds/assets/images/').'no-image-big.png" alt=""  />';
							}												 
						echo '</a>';
						echo '</div>';
					}																						
								//echo '<div class="cat_name"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_CATEGORY').'</span>'.$i->c_name.'</div>';								
				if($par->get('blog_desc_position','right')=='right'){
					if($i->event->beforeDJClassifiedsDisplayContent) { ?>
						<div class="djcf_before_content">
							<?php echo $i->event->beforeDJClassifiedsDisplayContent; ?>
						</div>
					<?php }	
					echo '<div class="item_desc"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</span>';
						echo '<span class="desc_info">'.mb_substr(strip_tags($i->intro_desc), 0,$par->get('introdesc_char_limit','120'),'UTF-8');
					echo '</span></div>';
					echo '<div class="clear_both"></div>';	
					echo '</div>';
					if($i->event->afterDJClassifiedsDisplayContent) { ?>
						<div class="djcf_after_content">
							<?php echo $i->event->afterDJClassifiedsDisplayContent; ?>
						</div>
					<?php }	
				}
					if($par->get('blog_category','0')){
						echo '<div class="category"><span class="label_title"></span>';
						if($par->get('blog_category','0')==2){
							echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($i->cat_id.':'.$i->c_alias).'" >'.$i->c_name.'</a>';
						}else{
							echo $i->c_name;	
						}						
						echo '</div>';
					}
					if($par->get('blog_location','1') && $i->r_name && $par->get('show_regions','1')){
						echo '<div class="region"><span class="label_title"></span>'.$i->r_name.'</div>';
					}
					if($par->get('blog_price','1') && $i->price && $par->get('show_price','1')){
						echo '<div class="price"><span class="label_title"></span>';
							echo DJClassifiedsTheme::priceFormat($i->price,$i->currency);
						echo '</div>';	
					}
					/*if($par->get('column_date_a','1')){
						echo '<div class="date_start"><span class="label_title"></span>'.$i->date_start.'</div>';
					}*/							
					if($par->get('blog_desc_position','right')=='bottom'){	
						if($i->event->beforeDJClassifiedsDisplayContent) { ?>
							<div class="djcf_before_content">
								<?php echo $i->event->beforeDJClassifiedsDisplayContent; ?>
							</div>
						<?php }		
						echo '<div class="item_box_bottom">';		
							echo '<div class="item_desc"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</span>';
								echo '<span class="desc_info">'.mb_substr(strip_tags($i->intro_desc), 0,$par->get('introdesc_char_limit','120'),'UTF-8');
							echo '</span></div>';					
						echo '</div>';
						if($i->event->afterDJClassifiedsDisplayContent) { ?>
							<div class="djcf_after_content">
								<?php echo $i->event->afterDJClassifiedsDisplayContent; ?>
							</div>
						<?php }		
					}										
					foreach($i->fields as $f_id => $field){
						if($this->custom_fields[$f_id]->in_blog && $field!=''){
							echo '<div class="cf_box">';
								echo '<span class="label_title">'.$this->custom_fields[$f_id]->label.' : </span>';
								if($this->custom_fields[$f_id]->type=='checkbox'){
									echo str_ireplace(';', ', ', substr($field,1,-1));
								}else if($this->custom_fields[$f_id]->type=='link'){
									if($field==''){echo '---'; }
									else{
										if(strstr($field, 'http://') || strstr($field, 'https://')){
											echo '<a '.$this->custom_fields[$f_id]->params.' href="'.$field.'">'.str_ireplace(array("http://","https://"), array('',''), $field).'</a>';;
										}else{
											echo '<a '.$this->custom_fields[$f_id]->params.' href="http://'.$field.'">'.$field.'</a>';;
										}
									}
								}else{
									echo $field;	
								}								
							echo '</div>';
						}
					}										
					if($par->get('blog_readmore','1')){					
						echo '<div class="see_details_box">';
							echo '<a class="see_details" href="'.DJClassifiedsSEO::getItemRoute($i->id.':'.$i->alias,$i->cat_id.':'.$i->c_alias).$anch.'" >'.JText::_('COM_DJCLASSIFIEDS_SEE_DETAILS').'</a>';
							if($i->auction){
								echo '<span class="auction_icon" ></span>';
							}
						echo '</div>';
					}			
		
			echo '</div>';
			if(strstr($i->promotions, 'p_special')){
				echo '<span class="p_special_img">&nbsp;</span>';
			} 
			echo '</div></div></div></div>';
		}
		?>	
		<?php
			echo '<div class="clear_both" ></div>';			
			
		if(count($this->items)==0){
			echo '<div class="no_results" style="padding-left:30px;">';
				if($se>0){
					echo JText::_('COM_DJCLASSIFIEDS_NO_RESULTS');	
				}else if($main_id){
					echo JText::_('COM_DJCLASSIFIEDS_NO_CATEGORY_RESULTS');
				}
				
			echo '</div>';
		}
		?>
		</div>
		<?php 
		if($this->pagination->getPagesLinks()){
			echo '<div class="pagination" >';
			echo $this->pagination->getPagesLinks();
			echo '</div>';
		}		
		?>
	</div>	
	
	<script type="text/javascript">
	function DJCatMatchModules(className){
		var maxHeight = 0;
		var divs = null;
		if (typeof(className) == 'string') {
			divs = document.id(document.body).getElements(className);
		} else {
			divs = className;
		}
		if (divs.length > 1) {						
			divs.each(function(element) {
				//maxHeight = Math.max(maxHeight, parseInt(element.getStyle('height')));
				maxHeight = Math.max(maxHeight, parseInt(element.getSize().y));
			});
			
			divs.setStyle('height', maxHeight);
			
		}
	}
	
	window.addEvent('load', function(){
		DJCatMatchModules('.item_box_in2');
	});
	
	</script>