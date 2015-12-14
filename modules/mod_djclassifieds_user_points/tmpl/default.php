<?php
/**
* @version 2.0
* @package DJ Flyer
* @subpackage DJ Flyer Module
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Flyer is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Flyer is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Flyer. If not, see <http://www.gnu.org/licenses/>.
*
*/
	
	$user_ppoints_link='index.php?option=com_djclassifieds&view=points';
	if($menu_ppackages_itemid){
		$user_ppoints_link .= '&Itemid='.$menu_ppackages_itemid->id;
	}

	$user_upoints_link='index.php?option=com_djclassifieds&view=userpoints';
	if($menu_upoints_itemid){
		$user_upoints_link .= '&Itemid='.$menu_upoints_itemid->id;
	}

	?>
	<div class="djcf_user_points djcf_menu">
		<?php if($params->get('show_points','1')==1 && $user->id>0){
				if($user_points==''){$user_points=0;} 
				echo '<div class="djcf_upoints_box">';
					echo '<span class="djcf_upoints_label">'.JText::_('MOD_DJCLASSIFIEDS_USER_POINTS_USER_POINTS').':</span>';
					echo '<span class="djcf_upoints" >'.$user_points.'</span>';
				echo '</div>';
			}?> 			
		
		<ul class="menu nav <?php echo $params->get('moduleclass_sfx',''); ?>">
		<?php 			
			if($params->get('points_packages_link','0')==1){
				echo '<li><a href="'.JRoute::_($user_ppoints_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_POINTS_POINTS_PACKAGES').'</a></li>';
			}
			if($params->get('user_points_link','0')==1){
				echo '<li><a href="'.JRoute::_($user_upoints_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_POINTS_POINTS_HISTORY').'</a></li>';
			}?>
		</ul>
	</div>	
	 