<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

class DJClassifiedsSocial
{
	
	public static function getUserAvatar($user_id, $source = '',$size='S')
	{
		if($source=='easysocial'){
			require_once( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php' );
			$suser   = Foundry::user( $user_id );
			if($size=='S'){
				$user_avatar = $suser->getAvatar('small');
			}else if($size=='M'){
				$user_avatar = $suser->getAvatar('medium');
			}else if($size=='L'){
				$user_avatar = $suser->getAvatar('large');
			}			 
			echo '<img src="'.$user_avatar.'" alt="" />';
		}else if($source=='joomsocial'){
			include_once (JPATH_ROOT.'/components/com_community/libraries/core.php');
			$suser = CFactory::getUser($user_id);
			if($size=='S'){
				$user_avatar = $suser->getThumbAvatar();
			}else{
				$user_avatar = $suser->getAvatar();
			}			 
			echo '<img src="'.$user_avatar.'" alt="" />';
		}else if($source=='cb'){
			global $_CB_framework, $mainframe;
		 
			if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
				if ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) {
			    echo 'CB not installed!';
			    return;
				}
				include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
			} else {
				if ( ! file_exists( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' ) ) {
			    echo 'CB not installed!';
			    return;
				} 
				include_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' );
			}
		
		    $cbUser    =&  CBuser::getInstance( $user_id );
		    if ( $cbUser ) {
		      $avatar = $cbUser->getField( 'avatar', null, 'csv', 'none', 'list' );
				if($avatar){
					echo '<img src="'.$avatar.'" alt="" />';
				}  
		    }				    
		}
		return null;
	}
	
	public static function getUserProfileLink($user_id, $source = '')
	{
		if($source=='easysocial'){
			if(JFile::exists(JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php')){
				require_once( JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php' );
				$suser   = Foundry::user( $user_id );
				$sconfig  = Foundry::config();
				
				$link = '';
				
				$name = $sconfig->get('users.aliasName') == 'realname' ? $suser->name : $suser->username;
				$name = $user_id . ':' . $name;
				// Check if the permalink is set
				if ($suser->permalink && !empty($suser->permalink)) {
					$name = $suser->permalink;
				}
				// If alias exists and permalink doesn't we use the alias
				if ($suser->alias && !empty($suser->alias) && !$suser->permalink) {
					$name = $suser->alias;
				}
				// Ensure that the name is a safe url.
				$name = JFilterOutput::stringURLSafe($name);
				
				$options = array('id' => $name);
				$link = FRoute::profile($options);
			}
						
		}else if($source=='joomsocial'){
			if(JFile::exists(JPATH_ROOT.'/components/com_community/libraries/core.php')){							
				include_once (JPATH_ROOT.'/components/com_community/libraries/core.php');
				$link = CRoute::_('index.php?option=com_community&view=profile&userid='.$user_id);
			}						
		}else if($source=='cb'){
			global $_CB_framework, $mainframe;
		 
			if ( defined( 'JPATH_ADMINISTRATOR' ) ) {
				if ( ! file_exists( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' ) ) {
			    echo 'CB not installed!';
			    return;
				}
				include_once( JPATH_ADMINISTRATOR . '/components/com_comprofiler/plugin.foundation.php' );
			} else {
				if ( ! file_exists( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' ) ) {
		    	echo 'CB not installed!';
		    	return;
				} 
				include_once( $mainframe->getCfg( 'absolute_path' ) . '/administrator/components/com_comprofiler/plugin.foundation.php' );
			}
		    $cbUser    =&  CBuser::getInstance( $user_id );
		    if ($cbUser ) {
		    	$xhtml ='';
		    	$link = cbSef('index.php?option=com_comprofiler&amp;task=userProfile&amp;user='.$user_id. getCBprofileItemid(), $xhtml);
		    }
		}
		
		return $link;
	}
	
	public static function getItemRoute($id, $catid = 0)
	{
		$needles = array(
			'item'  => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_djclassifieds&view=item';
		if ((int)$catid >= 0)
		{
			$cat_path = DJClassifiedsCategory::getSEOParentPath((int)$catid);			
			if($cat_path)
			{
				$path = $cat_path;
				$path[] = '0';
				$needles['items'] = ($path);
				$link .= '&cid='.$catid;
			}
		}
		 

		$link .= '&id='. $id;

		if ($item = self::_findItem($needles)) {
		
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	public static function getCategoryRoute($catid)
	{
		$needles = array(
			'items'  => array((int) $catid)
		);
		
		//Create the link
		$link = 'index.php?option=com_djclassifieds&view=items';		
		if ((int)$catid >= 0)
		{
			$cat_path = DJClassifiedsCategory::getSEOParentPath((int)$catid);			
			if($cat_path)
			{
				$path = $cat_path;
				$path[] = '0:all';			
				$needles['items'] = ($path);
				$link .= '&cid='.$catid;
			}
		}

		if ($item = self::_findItem($needles)) {
			$link .= '&Itemid='.$item;
		}
		return $link;
	}
	
	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');

		// Prepare the reverse lookup array.
		if (self::$lookup === null)
		{
			self::$lookup = array();

			$component	= JComponentHelper::getComponent('com_djclassifieds');
			$items		= $menus->getItems('component_id', $component->id);

			if (count($items)) {
				foreach ($items as $item)
				{
					if (isset($item->query) && isset($item->query['view']))
					{
						$parameter = 'id';
						if ($item->query['view'] == 'items') {
							$parameter = 'cid';
						}											
						$view = $item->query['view'];
						if (isset($item->query['layout'])) {
							if ($item->query['layout'] == 'blog') {
								$view= $view."_blog";
							}elseif ($item->query['layout'] == 'favourites') {
								$view= $view."_favourites";
							}
						}						
						
						if (isset($item->query[$parameter])) {
							self::$lookup[$view][$item->query[$parameter]] = $item->id;
						}else if (!isset(self::$lookup[$view])) {
							self::$lookup[$view] = array($item->id);
						}
					}
				}
			}
		}
		//echo '<pre>';print_r($needles);print_R(self::$lookup);die();
		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				//if (isset(self::$lookup[$view])){
					foreach($ids as $id)
					{
					   if (isset(self::$lookup[$view][(int)$id])) {
						return self::$lookup[$view][(int)$id];
					   }else if (isset(self::$lookup[$view.'_blog'][(int)$id])) {
						return self::$lookup[$view.'_blog'][(int)$id];
					   }
					}
				//}
			}
			
			if(isset($needles['items']) || isset($needles['items'])){
				if (isset(self::$lookup['categories'][0])) {
					return self::$lookup['categories'][0];
			   	}
			}
			
			
		}
		//else {
		$active = $menus->getActive();
		if ($active && $active->component == 'com_djclassifieds') {
			return $active->id;
		} else {
			$default = $menus->getDefault();
			return $default->id;
		}
		//}

		return null;
	}

	public static function getAliasName($name='')
	{
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$alias = mb_strtolower($name);
        $alias = strip_tags($alias);
		if($par->get('seo_alias_urlsafe','1')){
			$alias = JFilterOutput::stringURLSafe($alias);
		}else{
			$alias = JFilterOutput::stringURLUnicodeSlug($alias);							
		}
        $alias=str_ireplace(' ', '_', $alias);       
		return $alias;
	}
	
	public static function getNewAdLink()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');	
		$menu_newad_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=additem',1);
		$new_ad_link='index.php?option=com_djclassifieds&view=additem';
		if($menu_newad_itemid){
			$new_ad_link .= '&Itemid='.$menu_newad_itemid->id;
		}       
		return $new_ad_link;
	}

	public static function getUserAdsLink(){
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$menu_userads_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=useritems',1);
		$menu_userads_link = 'index.php?option=com_djclassifieds&view=useritems';	
		if($menu_userads_itemid){
			$menu_userads_link .= '&Itemid='.$menu_userads_itemid->id;
		}       
		return $menu_userads_link;
	}
	
	public static function getUserFavAdsLink(){		
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$menu_fav_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=favourites',1);	
		$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
		$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
				
		if($menu_fav_item){
			$fav_ads_link='index.php?option=com_djclassifieds&view=items&layout=favourites&Itemid='.$menu_fav_item->id;
		}else if($menu_item){
			$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$menu_item->id.'&fav=1';
		}else if($menu_item_blog){
			$fav_ads_link='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$menu_item_blog->id.'&fav=1';
		}else{
			$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&fav=1';
		}
						
		return $fav_ads_link;
	}
	
	public static function getURLfromSlug($slug){		
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$slug_a = explode(':',$slug);
		$url = '';
			if($par->get('seo_alias_in_url','1')){
				if(isset($slug_a[1])){
					if($par->get('seo_id_position','1')){		
						$url .= $slug_a[1].$par->get('seo_link_separator',',').$slug_a[0];		 	
					}else{
						$url .= $slug_a[0].$par->get('seo_link_separator',',').$slug_a[1];
					}	
				}else{
					$url = $slug_a[0];	
				}		
			}else{
				$url = $slug_a[0];
			}
						
		return $url;
	}

	public static function getIDfromURL($url){		
		$par = JComponentHelper::getParams( 'com_djclassifieds' );		
		$url_a = explode($par->get('seo_link_separator',','),str_ireplace(':', $par->get('seo_link_separator',','), $url));
			if(isset($url_a[1])){				
				if($par->get('seo_id_position','1')){		
					$id = end($url_a);
					unset($url_a[count($url_a)-1]);		 	
				}else{
					$id = (int)$url_a[0];
					unset($url_a[0]);
				}	
			}else{
				$id = (int)$url;	
			}		
			
			$slug = '';			
			if (count($url_a) > 0) {
				$slug = ':';
				$slug .= implode('-',$url_a);
			}
			
		return $id.$slug;
	}
	
	public static function getMainAdvertsItemid(){
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
		$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
	
		$itemid = '';
		if($menu_item){
			$itemid='&Itemid='.$menu_item->id;
		}else if($menu_item_blog){
			$itemid='&Itemid='.$menu_item_blog->id;
		}
	
		return $itemid;
	}
	

}
?>
