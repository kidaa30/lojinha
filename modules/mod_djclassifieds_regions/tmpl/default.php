<?php
/**
* @version 2.0
* @package DJ Classifieds Menu Module
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

defined ('_JEXEC') or die('Restricted access');

$par = JComponentHelper::getParams( 'com_djclassifieds' );
$max_level = $params->get('max_level','0'); 
$config  = JFactory::getConfig();


if($params->get('results_itemid',0)){
	$se_results_link = 'index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$params->get('results_itemid',0);
	$se_results_link = JRoute::_($se_results_link);
}else{
	$se_results_link = JRoute::_(DJClassifiedsSEO::getCategoryRoute('0:all'));
}

	if($config->get('sef')){
		$se_results_link .='?se=1&amp;re=1&amp;se_regs=';
	}else{
		$se_results_link .='&se=1&re=1&se_regs=';
	} 

	?>
		<div class="djcf_menu djcf_regions">
		<?php 	
		if($regs){ ?>	
		<ul class="menu nav">
			<?php 
				$r_count=0;
				$prev_l = 0;
				$start_l = 0;
				//echo '<li>';
				
				
				
				foreach($regs as $r){
					if($max_level>0 && $r->level>=$max_level){
						continue;
					}
					if($r_count==0){
						$prev_l = $r->level;
						$start_l= $r->level;
					}
											
					if($prev_l>$r->level){
						for($i=0;$i<$prev_l-$r->level;$i++){
							echo '</ul>';	
						}
						
					}else if($prev_l<$r->level){
						echo '<ul>';
					}else if($r_count>0){
						echo '</li>';
					}
					
					$prev_l = $r->level;
					if(strstr($reg_path,','.$r->id.',')){
						if($reg_id==$r->id){
							$cl='class="active current"';
						}else{
							$cl='class="active"';
						}
					}else{
						$cl='';
					}
					if($show_items_c){
						$r->name .= ' <span class="items_count">('.$r->items_count.')</span>';
					}
					echo '<li '.$cl.'><a href="'.$se_results_link.$r->id.'">'.$r->name.'</a>';	
					$r_count++;
				}		
				$prev_l = $prev_l-$start_l;
				if($prev_l>0){					
					for($i=0;$i<$prev_l;$i++){						
						echo '</li></ul>';												
					}					
				}	
				if($r_count>0){
					echo '</li>';
				}
			?>			
		</ul>
		<?php
		} 
		if($params->get('new_ad_link','0')==2){
			echo '<div class="newad_link_bottom"><a class="button" href="'.$new_ad_link.'">'.JText::_('MOD_DJCLASSIFIEDS_MENU_NEW_ADD').'</a></div>';
		}?>
	</div>	
	 