<?php
/**
* @version 2.0
* @package DJ Classifieds
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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class DJClassifiedsControllerPayment extends JControllerLegacy {

	function payPoints(){
		$app  = JFactory::getApplication();
		$par  = JComponentHelper::getParams( 'com_djclassifieds' );		
		$user = JFactory::getUser();
		$db   = JFactory::getDBO();
		$id   = JRequest::getInt('id', 0);
		$type = JRequest::getVar('type', '');
		$menus	= $app->getMenu('site');
		

			$query ="SELECT i.*, c.points as c_points, c.autopublish as c_autopublish, c.alias as c_alias FROM #__djcf_items i "
				   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				   ."WHERE i.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			$redirect_a=0;
			if(!$item && $type!='plan'){
				$redirect_a=1;				
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			}			
			if($item->user_id!=$user->id && $type!='plan'){
				$redirect_a=1;
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');				
			}
			if($user->id==0){
				$redirect_a=1;
				$message = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
			}
			
			if($redirect_a){				
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message);
			}
						
			$query = "SELECT SUM(p.points)FROM #__djcf_users_points p "
					."WHERE p.user_id='".$user->id."' ";										
				
			$db->setQuery($query);
			$points_count=$db->loadResult();
																	
			$p_amount = 0;
			
			if($type=='plan'){						
				$query = "SELECT p.*  FROM #__djcf_plans p WHERE p.id='".$id."' ";
				$db->setQuery($query);
				$plan = $db->loadObject();
				
				if($plan){	
					$p_amount= $plan->points;			
					if($points_count>=$p_amount){
						
						

						$registry = new JRegistry();
						$registry->loadString($plan->params);
						$plan_params = $registry->toObject();
							
						//echo '<pre>';print_r($plan_params);die();
						$date_start = date("Y-m-d H:i:s");
						$date_exp = '';
						if($plan_params->days_limit){
							$date_exp_time = time()+$plan_params->days_limit*24*60*60;
							$date_exp = date("Y-m-d H:i:s",$date_exp_time) ;
						}
						$query = "INSERT INTO #__djcf_plans_subscr (`user_id`,`plan_id`,`adverts_limit`,`adverts_available`,`date_start`,`date_exp`,`plan_params`) "
								."VALUES ('".$user->id."','".$plan->id."','".$plan_params->ad_limit."','".$plan_params->ad_limit."','".$date_start."','".$date_exp."','".addslashes($plan->params)."')";
						$db->setQuery($query);
						$db->query();				
						
						$up_description = JText::_('COM_DJCLASSIFIEDS_PAYMENT_FOR_SUBSCRIPTION_PLAN').'<br />'.JText::_('COM_DJCLASSIFIEDS_PLAN_ID').": ".$plan->id.'<br />'.JText::_('COM_DJCLASSIFIEDS_PLAN').": ".$plan->name;
						$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
								."VALUES ('".$user->id."','-".$p_amount."','".addslashes($up_description)."')";
						$db->setQuery($query);
						$db->query();
					
						$message = JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_PLAN_ACTIVATED');						
						
						$menu_userplans_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=userplans',1);
						$userplans_link='index.php?option=com_djclassifieds&view=userplans';
						if($menu_userplans_itemid){
							$userplans_link .= '&Itemid='.$menu_userplans_itemid->id;
						}										
						$redirect = JRoute::_($userplans_link,false);
						$app->redirect($redirect, $message);
					
					}else{
						$message = JText::_('COM_DJCLASSIFIEDS_NOT_ENOUGHT_POINTS');
						$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
						$redirect = JRoute::_($redirect,false);
						$app->redirect($redirect, $message);
					}
				}else{
					$message = JText::_('COM_DJCLASSIFIEDS_WRONG_SUBSCRIPTION_PLAN');
					$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
					$redirect = JRoute::_($redirect,false);
					$app->redirect($redirect, $message);
				}
				
				
			}else if($type=='prom_top'){
				$p_amount= $par->get('promotion_move_top_points',0);
				if($points_count>=$p_amount){
					$up_description = JText::_('COM_DJCLASSIFIEDS_PAYMENT_FOR_PROMOTION_MOVE_TO_TOP').'<br />'.JText::_('COM_DJCLASSIFIEDS_ADVERT_ID').": ".$item->id.'<br />'.JText::_('COM_DJCLASSIFIEDS_TITLE').": ".$item->name;
					$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
							."VALUES ('".$user->id."','-".$p_amount."','".addslashes($up_description)."')";
					$db->setQuery($query);
					$db->query();
				
					$message = JText::_('COM_DJCLASSIFIEDS_POINTS_PAYMENT_CONFIRMED_PROMOTION_MOVE_TO_TOP_ACTIVATED');				
					$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
				
					$date_sort=date("Y-m-d H:i:s");
					$query = "UPDATE #__djcf_items SET date_sort='".$date_sort."' "
							."WHERE id=".$item->id." ";
					$db->setQuery($query);
					$db->query();
				
					$redirect = JRoute::_($redirect,false);
					$app->redirect($redirect, $message);
				
				}else{
					$message = JText::_('COM_DJCLASSIFIEDS_NOT_ENOUGHT_POINTS');
					$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
					$redirect = JRoute::_($redirect,false);
					$app->redirect($redirect, $message);
				}
			}else{		
				if(strstr($item->pay_type, 'cat')){			
					$p_amount += $item->c_points; 
				}
				/*if(strstr($item->pay_type, 'duration_renew')){			
					$query = "SELECT d.points_renew FROM #__djcf_days d "
					."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult()*$item->extra_images_to_pay;
				}else if(strstr($item->pay_type, 'duration')){			
					$query = "SELECT d.points FROM #__djcf_days d "
					."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult()*$item->extra_images_to_pay;
				}
				
				if(strstr($item->pay_type, 'extra_img_renew')){
					$query = "SELECT d.img_points_renew FROM #__djcf_days d "
							."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult();
				}else if(strstr($item->pay_type, 'extra_img')){
					$query = "SELECT d.img_points FROM #__djcf_days d "
							."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult();
				}
				
				if(strstr($item->pay_type, 'extra_chars_renew')){
					$query = "SELECT d.char_points_renew FROM #__djcf_days d "
							."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult();
				}else if(strstr($item->pay_type, 'extra_chars')){
					$query = "SELECT d.char_points FROM #__djcf_days d "
							."WHERE d.days=".$item->exp_days;
					$db->setQuery($query);
					$p_amount += $db->loadResult();
				}*/
				
				$query = "SELECT * FROM #__djcf_days d "
						."WHERE d.days=".$item->exp_days." LIMIT 1";
				$db->setQuery($query);
				$day = $db->loadObject();
				
				
				if(strstr($item->pay_type, 'duration_renew')){
					$p_amount += $day->points_renew;
				}else if(strstr($item->pay_type, 'duration')){
					$p_amount += $day->points;
				}
				
				if(strstr($item->pay_type, 'extra_img_renew')){
					if($day->img_price_default){
						$p_amount += $par->get('img_price_renew_points','0')*$item->extra_images_to_pay;
					}else{
						$p_amount += $day->img_points_renew*$item->extra_images_to_pay;
					}
				}else if(strstr($item->pay_type, 'extra_img')){
					if($day->img_price_default){
						$p_amount += $par->get('img_price_points','0')*$item->extra_images_to_pay;
					}else{
						$p_amount += $day->img_points*$item->extra_images_to_pay;
					}
				}
				
				if(strstr($item->pay_type, 'extra_chars_renew')){
					if($day->char_price_default){
						$p_amount += $par->get('desc_char_price_renew_points','0')*$item->extra_chars_to_pay;
					}else{
						$p_amount += $day->char_points_renew*$item->extra_chars_to_pay;
					}
				}else if(strstr($item->pay_type, 'extra_chars')){
					if($day->char_price_default){
						$p_amount += $par->get('desc_char_price_points','0')*$item->extra_chars_to_pay;
					}else{
						$p_amount += $day->char_points*$item->extra_chars_to_pay;
					}
				}
				
				$query = "SELECT p.* FROM #__djcf_promotions p "
					."WHERE p.published=1 ORDER BY p.id ";
				$db->setQuery($query);
				$promotions=$db->loadObjectList();
				foreach($promotions as $prom){
					if(strstr($item->pay_type, $prom->name)){	
						$p_amount += $prom->points; 
					}	
				}
				
				if($points_count>=$p_amount){
					$up_description = JText::_('COM_DJCLASSIFIEDS_PAYMENT_FOR_ADVERT').'<br />'.JText::_('COM_DJCLASSIFIEDS_ADVERT_ID').": ".$item->id.'<br />'.JText::_('COM_DJCLASSIFIEDS_TITLE').": ".$item->name;
					$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
							."VALUES ('".$user->id."','-".$p_amount."','".addslashes($up_description)."')";					
					$db->setQuery($query);
					$db->query();
						
						$pub=0;
						if(($item->c_autopublish=='1') || ($item->c_autopublish=='0' && $par->get('autopublish')=='1')){						
							$pub = 1;							
							$message = JText::_('COM_DJCLASSIFIEDS_POINTS_PAYMENT_CONFIRMED_ADVERT_PUBLISHED'); 						
						}else{
							$message = JText::_('COM_DJCLASSIFIEDS_POINTS_PAYMENT_CONFIRMED_ADVERT_WAITING_FOR_PUBLISH');
						}
						$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
				
						$query = "UPDATE #__djcf_items SET payed=1, pay_type='',extra_images_to_pay='0',extra_chars_to_pay='0', published='".$pub."' "
								."WHERE id=".$item->id." ";					
						$db->setQuery($query);
						$db->query();	
						
						$redirect = JRoute::_($redirect,false);
						$app->redirect($redirect, $message);
						
				}else{
					$message = JText::_('COM_DJCLASSIFIEDS_NOT_ENOUGHT_POINTS');
					$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
					$redirect = JRoute::_($redirect,false);
					$app->redirect($redirect, $message);
				}
			}		
		

	}

	
}

?>