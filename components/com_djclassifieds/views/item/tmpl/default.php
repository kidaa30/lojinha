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
JHTML::_('behavior.framework',true);
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.calendar');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$config = JFactory::getConfig();
$user =  JFactory::getUser();
$Itemid = JRequest::getVar('Itemid', 0,'', 'int');
$item = $this->item;
$item_class='';

$icon_new_a	= $par->get('icon_new','1');
$icon_new_date = mktime(date("G"), date("i"), date("s"), date("m"), date("d")-$par->get('icon_new_time','3'), date("Y"));
$date_start = strtotime($item->date_start);
$icon_new=0;
	if($item->promotions){
		$item_class .=' promotion '.str_ireplace(',', ' ', $item->promotions);
	}
	if($date_start>$icon_new_date && $icon_new_a){
		$icon_new=1;
		$item_class .= ' item_new';  
	}
	
	if($item->auction){
		$item_class .=' item_auction';
	}
	
	if($par->get('favourite','1') && $user->id>0){
		if($item->f_id){ $item_class .= ' item_fav'; }
	}	

	$menus	= $app->getMenu('site');
	$menu_newad_m = $menus->getItems('link','index.php?option=com_djclassifieds&view=additem',1);
	$menu_newad_itemid='';
	if($menu_newad_m){
		$menu_newad_itemid = '&Itemid='.$menu_newad_m->id;
	}
	
	$pageclass_sfx ='';
	if($Itemid){
		$menu_item = $app->getMenu()->getItem($Itemid);
		$pc_sfx = $menu_item->params->get('pageclass_sfx');
		if($pc_sfx){$pageclass_sfx =' '.$pc_sfx;}
	}
	
$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';
?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $this->theme;?><?php echo $pageclass_sfx;?>">
	<?php
		$modules_djcf = &JModuleHelper::getModules('djcf-top');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	
		
		$modules_djcf = &JModuleHelper::getModules('djcf-top-cat'.$item->cat_id);
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top-cat clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	

		 $modules_djcf = &JModuleHelper::getModules('djcf-item-top');
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-item-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}
			
		 $modules_djcf = &JModuleHelper::getModules('djcf-item-top'.$item->id);
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-item-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}	

		if($this->item_payments==0){
		 $modules_djcf = &JModuleHelper::getModules('djcf-item-top-free');
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-item-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';
			}			
		}
			
			?>
			
<div class="dj-item<?php echo $item_class; ?>">
<?php	
	echo '<div class="title_top info"><h2>'.$item->name.'</h2>';
		if($user->id==$item->user_id && $user->id>0 && $item->published!=2){
			echo '<a href="index.php?option=com_djclassifieds&view=additem&id='.$item->id.$menu_newad_itemid.'" class="title_edit button">'.JText::_('COM_DJCLASSIFIEDS_EDIT').'</a>';
			echo '<a href="index.php?option=com_djclassifieds&view=useritems&t=delete&id='.$item->id.$menu_newad_itemid.'" class="title_delete button">'.JText::_('COM_DJCLASSIFIEDS_DELETE').'</a>';
		}
								
		if($par->get('show_types','0') && $item->type_id>0){
			$registry = new JRegistry();			
			$registry->loadString($item->t_params);			
			$item->t_params = $registry->toObject();
			if($item->t_params->bt_class){
				$bt_class = ' '.$item->t_params->bt_class;
			}else{
				$bt_class = '';
			}			
			if($item->t_params->bt_use_styles){
			 	$style='style="display:inline-block;
			 			border:'.(int)$item->t_params->bt_border_size.'px solid '.$item->t_params->bt_border_color.';'
			 		   .'background:'.$item->t_params->bt_bg.';'
			 		   .'color:'.$item->t_params->bt_color.';'
			 		   .$item->t_params->bt_style.'"';
					   echo '<span class="type_button'.$bt_class.'" '.$style.' >'.$item->t_name.'</span>';							
			}else{
				echo '<span class="type_label'.$bt_class.'" >'.$item->t_name.'</span>';	
			}
		}
		
		if($par->get('favourite','1')){
			if($user->id>0 && $item->f_id){
				echo '<a class="fav_icon_link fav_icon_link_a" title="'.JText::_('COM_DJCLASSIFIEDS_DELETE_FROM_FAVOURITES').'" href="index.php?option=com_djclassifieds&view=item&task=removeFavourite&cid='.$item->cat_id.'&id='.$item->id.'&Itemid='.$Itemid.'">';
					//echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/themes/'.$this->theme.'/images/fav_a.png" class="fav_ico"/>';
					echo '<span class="fav_icon fav_icon_a" ></span>';
					echo '<span class="fav_label">'.JText::_('COM_DJCLASSIFIEDS_FAVOURITE').'</span>'; 
				echo '</a>';
			}else{
				echo '<a class="fav_icon_link fav_icon_link_na" title="'.JText::_('COM_DJCLASSIFIEDS_ADD_TO_FAVOURITES').'" href="index.php?option=com_djclassifieds&view=item&task=addFavourite&cid='.$item->cat_id.'&id='.$item->id.'&Itemid='.$Itemid.'">';
					//echo ' <img src="'.JURI::base(true).'/components/com_djclassifieds/themes/'.$this->theme.'/images/fav_na.png" class="fav_ico"/>';
					echo '<span class="fav_icon fav_icon_na" ></span>';
					echo '<span class="nfav_label">'.JText::_('COM_DJCLASSIFIEDS_ADD_TO_FAVOURITES').'</span>';
				echo '</a>';
			}	
						
		}
		if($icon_new){
			echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
		} 
		
		if($par->get('sb_position','0')=='top' && $par->get('sb_code','')!=''){
			echo '<span class="sb_top">'.$par->get('sb_code','').'</span>';
		 }
		
	echo '</div>'; ?>
	<?php if($item->event->afterDJClassifiedsDisplayTitle) { ?>
		<div class="djcf_after_title">
			<?php echo $this->item->event->afterDJClassifiedsDisplayTitle; ?>
		</div>
	<?php } ?>
	<div class="dj-item-in">
	
			<div class="djcf_images_generaldet_box">	
				<?php if(count($this->item_images) || $par->get('ad_image_default','0')==1 ){ echo  $this->loadTemplate('images');  }?>			
				<?php echo  $this->loadTemplate('generaldetails'); ?>
			</div>
			<?php			
			if($par->get('auctions','0') && $item->auction && $par->get('auctions_position','top')=='middle'){
				echo  $this->loadTemplate('auctions');
			} ?>
			<?php echo  $this->loadTemplate('description'); ?>
			<?php echo  $this->loadTemplate('customdetails'); ?>
			<?php
			if(($par->get('show_regions','1') && $item->region_id) || ($par->get('show_address','1') && $item->address)){
				echo  $this->loadTemplate('localization');	
			}?>	 
			<?php echo  $this->loadTemplate('video'); ?>
			<?php echo  $this->loadTemplate('adddetails'); ?>
			<?php			
			if($par->get('auctions','0') && $item->auction && $par->get('auctions_position','top')=='bottom'){
				echo  $this->loadTemplate('auctions');
			} ?>									
			<?php if($par->get('sb_position','0')=='bottom' && $par->get('sb_code','')!=''){
				echo '<span class="sb_bottom">'.$par->get('sb_code','').'</span>';
			}?>
									 
		<div class="clear_both" ></div>
		<?php if($this->item->event->afterDJClassifiedsDisplayContent) { ?>
			<div class="djcf_after_desc">
				<?php echo $item->event->afterDJClassifiedsDisplayContent; ?>
			</div>
		<?php } ?>
		<?php  echo $this->loadTemplate('comments'); ?>
		<div class="clear_both" ></div>
	</div>
	</div>	
	<?php 
	 $modules_djcf = &JModuleHelper::getModules('djcf-item-bottom');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-item-bottom clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}
				
		$modules_djcf = &JModuleHelper::getModules('djcf-bottom');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-bottom clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';
		}
		
		$modules_djcf = &JModuleHelper::getModules('djcf-bottom-cat'.$item->cat_id);
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-bottom-cat clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';
		}
	?>
</div>

	<script type="text/javascript">
		this.DJCFShowValueOnClick = function (){
			var fields = document.id('dj-classifieds').getElements('.djsvoc');
			if(fields) {
				fields.each(function(field,index){
					field.addEvent('click', function(evt) {
						var f_rel = field.getProperty('rel');
						if(f_rel){
							field.innerHTML = '<a href="'+f_rel+'">'+field.title+'</a>';
						}else{
							field.innerHTML = field.title;
						}							
						return true;
					});
				});				
			}			
		}; 
								 
		window.addEvent('domready', function(){		
			DJCFShowValueOnClick();
		});
	</script>	