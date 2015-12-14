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
JHTML::_('behavior.tooltip');
$toolTipArray = array('className'=>'djcf');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);

//$par	 = JComponentHelper::getParams( 'com_djclassifieds' );
$par = DJClassifiedsParams::getParams();
$config  = JFactory::getConfig();
$app	 = JFactory::getApplication();
$main_id = JRequest::getVar('cid', 0, '', 'int');
$user	 = JFactory::getUser();

$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$se = JRequest::getVar('se', '0', '', 'int');
$re = JRequest::getVar('re', '0', '', 'int');
$uid	= JRequest::getVar('uid', 0, '', 'int');
$fav	= JRequest::getVar('fav', 0, '', 'int');
$fav_a	= $par->get('favourite','1');

$Itemid = JRequest::getInt('Itemid', 0);

$pageclass_sfx ='';
if($Itemid){		
	$menu_item = $app->getMenu()->getItem($Itemid);
	if($menu_item){
		$pc_sfx = $menu_item->params->get('pageclass_sfx');
	}	
	if($pc_sfx){$pageclass_sfx =' '.$pc_sfx;}	
}


$layout='';
if(JRequest::getVar('layout','')=='blog'){	
	$layout='&layout=blog';
}

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

	if($par->get('category_jump',0)){
		$anch = '#dj-classifieds';
	}else{
		$anch='';
	}
	
	if($par->get('rss_feed', 1)==1){
		$rss_feed ='rss';	
	}else if($par->get('rss_feed', 1)==2){
		$rss_feed ='atom';
	}else{
		$rss_feed ='';
	}	
	
	if($rss_feed){
		if($config->get('sef')){
			$rss_feed ='?format=feed&amp;type='.$rss_feed;
		}else{
			$rss_feed ='&format=feed&type='.$rss_feed;
		} 
	}
	
	$cat_id_se = 0;	
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
		$cat_id_se=(int)$cat_id_se;
	}
		
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
			
			$modules_djcf_cat=array();
			if($main_id){
				$modules_djcf_cat = &JModuleHelper::getModules('djcf-top-cat'.$main_id);
			}else if($cat_id_se){
				$modules_djcf_cat = &JModuleHelper::getModules('djcf-top-cat'.$cat_id_se);								
			}				
				if(count($modules_djcf_cat)>0){
					echo '<div class="djcf-ad-top-cat clearfix">';
					foreach (array_keys($modules_djcf_cat) as $m){
						echo JModuleHelper::renderModule($modules_djcf_cat[$m],$mod_attribs);
					}
					echo'</div>';		
				}	
				
			$modules_djcf = null;
			if($main_id){
				$modules_djcf = &JModuleHelper::getModules('djcf-top-items-cat'.$main_id);
			}else if($cat_id_se){
				$modules_djcf = &JModuleHelper::getModules('djcf-top-items-cat'.$cat_id_se);								
			}				
				if(count($modules_djcf)>0){
					echo '<div class="djcf-ad-top-cat clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';		
				}
			
			
			$modules_djcf = &JModuleHelper::getModules('djcf-items-top');			
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-items-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}

			if($fav){
				$modules_djcf = &JModuleHelper::getModules('djcf-items-top-fav');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-ad-items-top clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';
				}	
			}
			
			if($se){
				$modules_djcf = &JModuleHelper::getModules('djcf-items-top-search');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-ad-items-top clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';
				}
			}
			
				
	if($se>0){
		if($this->main_reg){
			$modules_djcf = &JModuleHelper::getModules('djcf-items-top-region');
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-items-top-region clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';
			}
			
			$modules_djcf = &JModuleHelper::getModules('djcf-items-top-region'.$this->main_reg->id);
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-items-top-region clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';
			}
		}
			
		if($re && $this->main_reg){				
			echo '<h1 class="main_cat_title">'.$this->main_reg->name.'</h1>';	
		}else{
			echo '<h1 class="main_cat_title">'.JText::_('COM_DJCLASSIFIEDS_SEARCH_RESULTS').'</h1>';
			if($this->countitems){
				echo '<div class="search_res_details">';
					if($this->countitems>1){
						echo JText::_('COM_DJCLASSIFIEDS_SR_WE_FOUND').' '.$this->countitems.' '.JText::_('COM_DJCLASSIFIEDS_SR_RESULTS').' ';
					}else{
						echo JText::_('COM_DJCLASSIFIEDS_SR_WE_FOUND').' '.$this->countitems.' '.JText::_('COM_DJCLASSIFIEDS_SR_RESULT').' ';
					}
										
					$search_word = JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'),'','string');
					if($search_word!=JText::_('COM_DJCLASSIFIEDS_SEARCH')){
						echo JText::_('COM_DJCLASSIFIEDS_SR_FOR_PHRASE').' "'.$search_word.'" ';											
					}
					if($this->main_cat){
						echo JText::_('COM_DJCLASSIFIEDS_SR_IN_CATEGORY').' "<a href="'.DJClassifiedsSEO::getCategoryRoute($this->main_cat->id.':'.$this->main_cat->alias).$anch.'">'.$this->main_cat->name.'</a>" ';
					}
					if($this->main_reg){
						echo JText::_('COM_DJCLASSIFIEDS_SR_AT_LOCATION').' "'.$this->main_reg->name.'" ';
					}
				echo '</div>';
			}	
		}
	}else if($uid>0){
		echo '<h1 class="main_cat_title">'.$this->u_name.' - '.JText::_('COM_DJCLASSIFIEDS_ADS').'</h1>';
	}else if($fav>0 && $user->id>0 && $fav_a){
		echo '<h1 class="main_cat_title">'.JText::_('COM_DJCLASSIFIEDS_FAVOURITES_ADS').'</h1>';
	}else{
		if($par->get('title_in_items','1')){				
			if($main_id>0){
				echo '<h1 class="main_cat_title">'.$this->main_cat->name;
					if ($rss_feed){			 
						echo '<a class="rss_icon" href="'.JRoute::_(DJClassifiedsSEO::getCategoryRoute($this->main_cat->id.':'.$this->main_cat->alias)).$rss_feed.'"><img src="'.JURI::base().'components/com_djclassifieds/assets/images/rss.png"  alt="rss" /></a>';
					}	
				if(count($this->cat_path)>1 && $par->get('title_breadcrumb','1')){
					echo '<br /><span class="main_cat_title_path">';
						for($ii=count($this->cat_path)-1;$ii>0;$ii--){						
							echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($this->cat_path[$ii]->id.':'.$this->cat_path[$ii]->alias).'">'.$this->cat_path[$ii]->name.'</a>';
							if($ii>1){ echo ' / '; }			
						}		
					echo '</span>';
				}			
				echo '</h1>';
				if($par->get('main_cat_desc','0') && $this->main_cat->description){
					echo '<div class="main_cat_desc">'.$this->main_cat->description.'</div>';	
				}				
			}else if($Itemid>0){
				$active_m = $app->getMenu('site')->getActive();
				if($active_m->params->get('show_page_heading','1')){
					echo '<h1 class="main_cat_title">'; 
						if($active_m->params->get('page_title','')){
							echo $active_m->params->get('page_title','');
						}else{
							echo $active_m->title;
						}
					echo '</h1>';	
				}			
			}	
		}
		
		if($par->get('show_subcats','1')==1 || ($main_id==0 && $par->get('show_subcats','1')==2)){		
			if(count($this->cats)>0){				
				$row=0;
				$ii = 0;
				$subcats = array();
				$subc = array();
				$sub_dir = $par->get('subcats_dir',0);
				$cats_count=0;
				$si=0;
					foreach($this->cats as $c){
						if($c->parent_id==$main_id){
							if($sub_dir){
								$subc[$si][]=$c;
								if($si==2){$si=0; }else{ $si++;}
							}else{
								$subcats[] = $c;	
							}
							$cats_count++;
						}
					}
	
				$cols = $par->get('subcats_columns','3');
				if($cats_count<$cols){
					$cols=$cats_count;				  
				}	
			if($subcats || $subc){		
			?>			
				<div class="dj-category cat_cols<?php echo $cols;  ?>">
					<div class="cat_row_title">
							<?php 
							if($main_id){
								echo JText::_('COM_DJCLASSIFIEDS_SUBCATEGORIES');
							}else{
								echo JText::_('COM_DJCLASSIFIEDS_CATEGORIES');	
								if ($rss_feed){
									echo '<a class="rss_icon" href="'.JRoute::_(DJClassifiedsSEO::getCategoryRoute('0:all')).$rss_feed.'"><img src="'.JURI::base().'components/com_djclassifieds/assets/images/rss.png" alt="rss" /></a>';
								}								
							} ?>		 
					</div>
					<div class="cat_row cat_row0">						
						<?php						
						//echo '<pre>';print_R($subc);die();
						if($sub_dir){							
							for($sc=0;$sc<count($subc[0]);$sc++){
								for($sci=0;$sci<$cols;$sci++){
									if(isset($subc[$sci][$sc])){
										$subcats[] = $subc[$sci][$sc];	
									}	
								}									
							}							
						}
						foreach($subcats as $c){		
								if($ii%$cols==0 && $ii!=0){
									$row==0 ? $row=1 : $row=0;
									echo '<div class="clear_both"></div></div><div class="cat_row cat_row'.$row.'"><div class="cat_col" ><div class="cat_col_in" >';	
								}else{
									echo '<div class="cat_col" ><div class="cat_col_in" >';
								}
									echo '<div class="title">';
										if(isset($this->cat_images[$c->id])){
											echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($c->id.':'.$c->alias).$anch.'">';											
												echo '<img src="'.JURI::base(true).$this->cat_images[$c->id]->path.$this->cat_images[$c->id]->name.'_ths.'.$this->cat_images[$c->id]->ext.'" alt="'.$c->name.'" />';
											echo '</a>';
										}
										
										echo '<div class="cat_title_desc"><h2><a href="'.DJClassifiedsSEO::getCategoryRoute($c->id.':'.$c->alias).$anch.'">';								
											if($par->get('show_adsn_cat')){
												if(!$c->items_count){
													$c->items_count=0;
												}
												echo $c->name.' <span>('.$c->items_count.')</span></a>';												
											}else{
												echo $c->name.'</a>';	
											}	
											echo '</h2>';
											if($par->get('sub_cat_desc','1') && $c->description){
												echo '<div class="cat_desc">'.$c->description.'</div>';													
											}	
										echo '</div>';	
									echo '</div>';									
								echo '</div></div>';					
								$ii++;	
						}
						
						/*if($ii%3==1){
							echo '<div class="cat" ></td><td class="cat" ></td>';
						}
						if($ii%3==2){
							echo '<td class="cat" ></td>';
						}*/
					?>
					<div class="clear_both"></div></div>
				</div>
				<?php		
				}		
			}
		}
	}
	
	
		$modules_djcf = &JModuleHelper::getModules('djcf-items-categories');
	if(count($modules_djcf)>0){
		echo '<div class="djcf-ad-items-categories clearfix">';
		foreach (array_keys($modules_djcf) as $m){
			echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
		}
		echo'</div>';		
	}	
	 






/*






if($sw){?>
<div class="searchword">
	<?php echo JText::_('Results for').' : <span>'.$sw.'</span>'; ?>
</div>
<?php } 
if($uid!='0'){	?>
<div class="user_items">
	<?php echo JText::_('Results for').' : <span>'.$this->model->getUserName($uid).' ('.$lista_count_items.')</span>'; ?>
</div>
<?php } 
 * 
 * 
 */
?>