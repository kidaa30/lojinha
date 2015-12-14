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
defined('_JEXEC') or die;
if(!defined("DS")){
	define('DS',DIRECTORY_SEPARATOR);
}
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');

function DJClassifiedsBuildRoute(&$query)
{
    $segments = array();
    $app        = JFactory::getApplication();
    $menu       = $app->getMenu('site');
	$par 		= JComponentHelper::getParams( 'com_djclassifieds' );

    if (empty($query['Itemid'])) {
        $menuItem = $menu->getActive();
    } else {
        $menuItem = $menu->getItem($query['Itemid']);
    }
    $option = (empty($menuItem->component)) ? null : $menuItem->component;
    
    $mView  = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
    $mLayout = (empty($menuItem->query['layout'])) ? null : $menuItem->query['layout'];
    $mCatid = (empty($menuItem->query['cid'])) ? null : (int)$menuItem->query['cid'];
    $mId    = (empty($menuItem->query['id'])) ? null : (int)$menuItem->query['id'];
    
    $view = !empty($query['view']) ? $query['view'] : null;
	$layout = !empty($query['layout']) ? $query['layout'] : null;
    $cid = !empty($query['cid']) ? $query['cid'] : null;    
    $id = !empty($query['id']) ? $query['id'] : null;
	$se = !empty($query['se']) ? $query['se'] : null;
	$uid = !empty($query['uid']) ? $query['uid'] : null;
	$bid = !empty($query['bid']) ? $query['bid'] : null;
	$order = !empty($query['order']) ? $query['order'] : null;
	$menuDefault = $menu->getDefault();
    
    // JoomSEF bug workaround
    if (isset($query['start']) && isset($query['limitstart'])) {
    	if ((int)$query['limitstart'] != (int)$query['start'] && (int)$query['start'] > 0) {
    		// let's make it clear - 'limitstart' has higher priority than 'start' parameter, 
    		// however ARTIO JoomSEF doesn't seem to respect that.
    		$query['start'] = $query['limitstart'];
    		unset($query['limitstart']);
    	}
    }
    // JoomSEF workaround - end

    if ($view && $option == 'com_djclassifieds') {      		                
    	if ($view == 'item') {
    		if ($view != $mView) {
				$segments[]=$par->get('seo_view_item','ad');
	        }    		
			unset($query['view']);	
        	if ($view == $mView && intval($id) > 0 && intval($id) == $mId) {
        		unset($query['id']);
        		unset($query['cid']);
        	} else if ($mView == 'items' && intval($id) > 0) {
        		if (intval($cid) != intval($mCatid)) {
					$segments[] =DJClassifiedsSEO::getURLfromSlug($cid);
        		}
        		$segments[] =DJClassifiedsSEO::getURLfromSlug($id);
        		unset($query['id']);
        		unset($query['cid']);
        	}
        }else if ($view == 'items') {
        	if ($view != $mView) {
				$segments[]=$par->get('seo_view_items','ads');
	        }
        	if ($cid === null) {
        		$cid = '0:all'; 
        	}
            if (intval($cid) != intval($mCatid)) {            	
				//$segments[] = $cid;
				$segments[] =DJClassifiedsSEO::getURLfromSlug($cid);
            } 
            unset($query['cid']);			
        }elseif($query['view']=='edititem'){
        	if ($view != $mView) {
				$segments[]=$par->get('seo_view_edititem','edititem');
	        }								
			if(isset($query['id'])){
				$segments[] = $query['id'];
				unset($query['id']);
			}				
		}elseif($query['view']=='additem'){
			if ($view != $mView) {
				$segments[]=$par->get('seo_view_additem','additem');
	        }										
		}elseif($query['view']=='useritems'){
			if ($view != $mView) {
				$segments[]=$par->get('seo_view_useritems','useritems');
	        }								
		}elseif($query['view']=='categories'){
			if ($view != $mView) {
				$segments[]=$par->get('seo_view_categories','categories');
	        }								
		}elseif($query['view']=='payment'){			
			$segments[]='payment';			
		}elseif($query['view']=='points'){
			if ($view != $mView) {			
				$segments[]='points';
			}			
		}elseif($query['view']=='userpoints'){
			if ($view != $mView) {			
				$segments[]='userpoints';
			}			
		}elseif($query['view']=='renewitem'){			
			$segments[]='renewitem';		
			if(isset($query['id'])){
				$segments[] = $query['id'];
				unset($query['id']);
			}	
		}else if ($view == 'profile') {        	
        	$segments[]=$par->get('seo_view_profile','profile');
        	if(isset($query['uid'])){
				$segments[] =DJClassifiedsSEO::getURLfromSlug($uid);
            } 
            unset($query['uid']);			
        }elseif($query['view']=='profileedit'){
        	if ($view != $mView) {
				$segments[]=$par->get('seo_view_profileedit','profileedit');
	        }														
		}elseif($query['view']=='contact'){			
			$segments[]='contact';		
			if(isset($query['id'])){
				$segments[] = $query['id'];
				unset($query['id']);
			}
			if(isset($query['bid'])){
				$segments[] = $query['bid'];
				unset($query['bid']);
			}				
		}elseif($query['view']=='checkout'){			
			$segments[]='checkout';		
			if(isset($query['cid'])){
				$segments[] = $query['cid'];
				unset($query['cid']);
			}	
			if(isset($query['item_id'])){
				$segments[] = $query['item_id'];
				unset($query['item_id']);
			}
			if(isset($query['quantity'])){
				$segments[] = $query['quantity'];
				unset($query['quantity']);
			}
		}elseif($query['view']=='registration'){	
			if ($view != $mView) {			
				$segments[]='registration';
			}						
			/*if(isset($query['layout'])){
				$segments[] = $query['layout'];
			}*/
					
		}
		
		unset($query['view']);
		if($layout!=$mLayout && $layout){
        	$segments[]=$layout;
        }
		unset($query['layout']);
		
		if ($mCatid === null) {
        		$mCatid = '0:all'; 
        }

		if($mView==$view && $mLayout ==  $layout && $mCatid == $cid && ($se || $order || $uid) && $menuDefault->id==$menuItem->id){			
			$segments[]='all'; 
		}
		
    }    
    
    return $segments;
}

function DJClassifiedsParseRoute($segments) {
	
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$activemenu = $menu->getActive();	
	$par = JComponentHelper::getParams( 'com_djclassifieds' );
	
	$catalogViews = array($par->get('seo_view_item','ad'), 
						  $par->get('seo_view_items','ads'), 
						  $par->get('seo_view_edititem','edititem'),
						  $par->get('seo_view_additem','additem'),
						  $par->get('seo_view_useritems','useritems'),
						  $par->get('seo_view_categories','categories'),
						  $par->get('seo_view_profile','profile'),
						  $par->get('seo_view_peofileefit','profileedit'));
	
	$query=array();
	$temp=array();
	if (count($segments)) {

		//if (!in_array($segments[0], $catalogViews)) {
	
	            if ($activemenu) {
	                $temp=array();
	                $temp[0] = $activemenu->query['view'];
	                switch ($temp[0]) {
	                	case 'item' : {
	                        $temp[1] = @$activemenu->query['cid'];
							$temp[2] = @$activemenu->query['id'];
	                        /*foreach ($segments as $k=>$v) {
	                            $temp[$k+1] = $v;
	                        }*/
	                        
	                        break;
	                    }
	                    case 'items' : {
	                        $temp[1] = @$activemenu->query['cid'];
							if(isset($activemenu->query['layout'])){
								$temp[2] = @$activemenu->query['layout'];	
							}
							
	                        /*foreach ($segments as $k=>$v) {
	                            $temp[$k+1] = $v;
	                        }*/
	                        break;
	                    }
	 					case 'edititem' : {
	                        $temp[1] = @$activemenu->query['id'];
	                        /*foreach ($segments as $k=>$v) {
	                            $temp[$k+1] = $v;
	                        }*/
	                        break;
	                    }
	                }
	                
	                //$segments = $temp;
	            }
	       // }
	            
		if (isset($segments[0])) { 
				if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_item','ad')) || $segments[0]=='item') {
					$query['view']='item';
					if(isset($segments[2])){
						$query['id']=DJClassifiedsSEO::getIDfromURL($segments[2]);
						$query['cid']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					}else{					
						if(isset($segments[1])){
							$query['id']=DJClassifiedsSEO::getIDfromURL($segments[1]);
						}
						if(isset($temp[1])){
						   if($temp[0]=='items'){
						       $query['cid']=$temp[1];						   
						   }
						}
						
					} 
				}
				else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_items','ads')) || $segments[0]=='items') {
					$query['view'] = 'items';
					if (isset($segments[1])) {
						$query['cid']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					} 
				}				
				else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_edititem','edititem')) || $segments[0]=='edititem') {
					$query['view'] = 'edititem';
					if (isset($segments[1])) {
						$query['id']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					} 
				}
				else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_additem','additem')) || $segments[0]=='additem') {
					$query['view'] = 'additem';
				}
				else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_useritems','useritems')) || $segments[0]=='useritems') {
					$query['view'] = 'useritems';
				}
				else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_categories','categories')) || $segments[0]=='categories') {
					$query['view'] = 'categories';
				}
				else if($segments[0]=='payment') {
					$query['view'] = 'payment';
					if (isset($segments[1])) {
						$query['id']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					} 
				}else if($segments[0]=='points') {
					$query['view'] = 'points';
				}else if($segments[0]=='userpoints') {
					$query['view'] = 'userpoints';
				}else if($segments[0]=='renewitem') {
					$query['view'] = 'renewitem';
					if (isset($segments[1])) {
						$query['id']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					} 
				}else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_profile','profile')) || $segments[0]=='profile') {
					$query['view'] = 'profile';
					if (isset($segments[1])) {
						$query['uid']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					} 
				}else if($segments[0]==str_ireplace('-', ':', $par->get('seo_view_profileedit','profileedit')) || $segments[0]=='profileedit') {
					$query['view'] = 'profileedit';					 
				}else if($segments[0]=='contact') {
					$query['view'] = 'contact';
					if (isset($segments[1])) {
						$query['id']=DJClassifiedsSEO::getIDfromURL($segments[1]);
					}
					if (isset($segments[2])) {
						$query['bid']=DJClassifiedsSEO::getIDfromURL($segments[2]);
					} 
				}else if(isset($temp[1])){
				   if($temp[0]=='items'){
				   		$query['view'] = 'items';
				        $query['cid']=DJClassifiedsSEO::getIDfromURL($segments[0]);
						if(isset($temp[2])){
							$query['layout'] = $temp[2];	
						}
				   }				   
				}else if($segments[0]=='checkout') {
					$query['view'] = 'checkout';
					if (isset($segments[1])) {
						$query['cid']=$segments[1];
					}
					if (isset($segments[2])) {
						$query['item_id']=DJClassifiedsSEO::getIDfromURL($segments[2]);
					}
					if (isset($segments[3])) {
						$query['quantity']=DJClassifiedsSEO::getIDfromURL($segments[3]);
					}
				}else if($segments[0]=='registration') {

					$query['view'] = 'registration';
					if (isset($segments[1])) {
						$query['layout']=$segments[1];
					}
				}else if(isset($temp[0])){
					if($temp[0]=='registration') {					
						$query['view'] = 'registration';
						if (isset($segments[0])) {
							$query['layout']=$segments[0];
						}
					}						
				}
			
		}
	}
	
	return $query;
}
