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

$user = JFactory::getUser();

	if($menu_favads_itemid){
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&layout=favourites&Itemid='.$menu_favads_itemid->id;	
	}else if($menu_item){
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$menu_item->id.'&fav=1';
	}else if($menu_item_blog){
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$menu_item_blog->id.'&fav=1';
	}else{
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&fav=1';
	}

	$new_ad_link='index.php?option=com_djclassifieds&view=additem';
	if($menu_newad_itemid){
		$new_ad_link .= '&Itemid='.$menu_newad_itemid->id;
	}

	$user_ads_link='index.php?option=com_djclassifieds&view=useritems';
	if($menu_uads_itemid){
		$user_ads_link .= '&Itemid='.$menu_uads_itemid->id;
	}
	
	$user_ppoints_link='index.php?option=com_djclassifieds&view=points';
	if($menu_ppackages_itemid){
		$user_ppoints_link .= '&Itemid='.$menu_ppackages_itemid->id;
	}

	$user_upoints_link='index.php?option=com_djclassifieds&view=userpoints';
	if($menu_upoints_itemid){
		$user_upoints_link .= '&Itemid='.$menu_upoints_itemid->id;
	}
	
	$user_edit_profile='index.php?option=com_djclassifieds&view=profileedit';
	if($menu_profileedit_itemid){
		$user_edit_profile .= '&Itemid='.$menu_profileedit_itemid->id;
	}

	?>
	<div class="djcf_user_menu djcf_menu">
		<ul class="menu nav <?php echo $params->get('moduleclass_sfx',''); ?>">
		<?php 
			if($params->get('new_ad_link','1')==1){
				echo '<li><a href="'.JRoute::_($new_ad_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_NEW_ADD').'</a></li>';
			}			
			if($params->get('user_ads_link','1')==1){
				echo '<li><a href="'.JRoute::_($user_ads_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_USER_ADS').'</a></li>';
			}
			if($params->get('user_edit_profile','0')==1){
				echo '<li><a href="'.JRoute::_($user_edit_profile).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_EDIT_PROFILE').'</a></li>';
			}
			if($params->get('user_fav_link','1')==1 && $user->id>0){
				echo '<li><a href="'.JRoute::_($fav_ads_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_FAVOURITE_ADS').'</a></li>';
			}
			if($params->get('points_packages_link','0')==1){
				echo '<li><a href="'.JRoute::_($user_ppoints_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_POINTS_PACKAGES').'</a></li>';
			}
			if($params->get('user_points_link','0')==1){
				echo '<li><a href="'.JRoute::_($user_upoints_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_USER_POINTS').'</a></li>';
			}
			?>
		</ul>
	</div>	
	 