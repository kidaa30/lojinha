<?php
/**
* @version 2.0
* @package DJ Classifieds Category Tree Module
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
$app	= JFactory::getApplication();

$level_limit = $params->get('cattree_levels','0');
$cols_limit = $params->get('cattree_cols','3');
$items_in_c_type = $params->get('cattree_ic','1');
$img_level = $params->get('cattree_img',0);
$ll_type = $params->get('cattree_last_level_type',0);

$col_limit = 0;
$cats_c=0;
$cats_to_display = array();
if($cols_limit>1){
	if($level_limit>0){
		foreach($cats as $c ){
			if($c->level<$level_limit){
				$cats_c++;
				$cats_to_display[]=$c;
			}
		}
		$cats =$cats_to_display;
	}else{
		$cats_c=count($cats);
	}

	$col_limit = ceil($cats_c/$cols_limit);
}

$ct_type  = $params->get('cattree_type',null);

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';
if($col_limit==0){
	$col_limit = 1;
}

$ct_type_class = ' ct_type_ec';
if($ct_type=='cat'){
	$ct_type_class = ' ct_type_mc';
}

?>
<div class="mod_djclassifieds_cattree clearfix">
	<div class="dj-cattree-box cols<?php echo $cols_limit.$ct_type_class; ?>" >	
		<div class="dj-cattree-wrap">
		<ul class="dj-cattree col1 cat0 lvl0" >
			<?php 
				$c_count=0;
				$prev_l = 0;
				$col_n=1;
				$prev_id=0;
				$level_corect = 0;
				//echo '<li>';
				foreach($cats as $ci => $c){
					if($c->id==$params->get('cat_id',0)){
						$level_corect = $c->level + 1;
						$c_count++;
						continue;
					}
					$c->level = $c->level-$level_corect;
					 
					if($level_limit>0 && $c->level>=$level_limit){
						continue;
					}
										
					if($prev_l>$c->level){
						for($i=0;$i<$prev_l-$c->level;$i++){
							echo '</ul>';	
						}
						
					}else if($prev_l<$c->level){
						$cl_ll='';	
						if($ll_type && $level_limit>1 && $c->level==$level_limit-1){
							$cl_ll=' last_level';
						}
						echo '<ul class="cat'.$prev_id.' lvl'.$c->level.$cl_ll.'">';
					}else if($c_count>0){
						echo '</li>';
					}										
					
						if($c->level==0 && $c_count>0){
							$prev_id = 0;							
							if($ct_type){							
								$col_number = $col_n%$cols_limit;
								if($col_number==0){
									$col_number++;
									echo '</ul><div class="clear_both"></div><ul class="dj-cattree col'.$col_number.' cat'.$prev_id.' lvl'.$c->level.'">';
								}else{
									$col_number++;
									echo '</ul><ul class="dj-cattree col'.$col_number.' cat'.$prev_id.' lvl'.$c->level.'">';
								}							
								$col_n++;	
								
							}else{							
								if(floor($c_count/$col_limit)==$col_n){
									$col_number = $col_n%$cols_limit;
									$col_number++;
									echo '</ul><ul class="dj-cattree col'.$col_number.' cat'.$prev_id.' lvl'.$c->level.'">';
									$col_n++;	
								}								
							}
						}
					
					
					$prev_l = $c->level;
					$prev_id = $c->id;
					/*if(strstr($cat_path,','.$c->id.',')){
						if($cid==$c->id){
							$cl='class="active current"';
						}else{
							$cl='class="active"';
						}
					}else{*/
						$cl='';
				//	}
					
					
					$items_in_c = '';
					
					if($c->items_count){						
						if($items_in_c_type==2){
							$items_in_c = ' <span>('.$c->items_count.')</span>';		
						}else if($items_in_c_type==1 && $c->level==0){
							$items_in_c = ' <span>('.$c->items_count.')</span>';
						}
					}		
					
					$cl.='cat'.$c->id.' lvl'.$c->level;
					
					$cat_img = '';
					if($c->level<$img_level && isset($cat_images[$c->id])){						
						$cat_img = '<img class="cat_tree_icon" alt="'.$c->name.'" src="'.JURI::base(true).$cat_images[$c->id]->path.$cat_images[$c->id]->name.'_ths.'.$cat_images[$c->id]->ext.'" />';	
					}
					
					$ll_coma='';
					if($ll_type && $level_limit>1 && $c->level==$level_limit-1){
						if(isset($cats[$ci+1])){
							$ll_coma = ($c->level==$cats[$ci+1]->level ? ',' : ''); 
						}	
					}
					if($c->level==0 && $params->get('cattree_first_level_type','0') == 1 ){
						$cl .= ' items_cat_type'; 						
						echo '<li class="'.$cl.'" >';
						echo '<div class="title">'; 
							echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($c->id.':'.$c->alias).'">'.$cat_img.'</a>';						
							echo '<div class="cat_title_desc">';
								echo '<h2><a href="'.DJClassifiedsSEO::getCategoryRoute($c->id.':'.$c->alias).'">'.$c->name.$items_in_c.'</a></h2>';
								if($c->description){
									echo '<div class="cat_desc">'.$c->description.'</div>';
								}
							echo '</div>';
						echo '</div>';
					}else{
						if($cl){
							$cl= 'class="'.$cl.'"';
						}
						echo '<li '.$cl.'>';
							echo '<a href="'.DJClassifiedsSEO::getCategoryRoute($c->id.':'.$c->alias).'">';
								echo $cat_img.'<span class="cat_name">'.$c->name.$items_in_c.'</span>';
							echo '</a>';
						echo $ll_coma;
					}	
					$c_count++;
				}		
				if($prev_l>0){
					for($i=0;$i<$prev_l;$i++){
						echo '</li></ul>';	
					}					
				}	
			?>			
		</ul>
		<div class="clear_both"></div>
		</div>
	</div>	
	<?php 
	 	$modules_djcf = &JModuleHelper::getModules('djcf-cattree-bottom');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-cattree-bottom clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}
	?>	
</div>