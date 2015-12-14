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

class DJClassifiedsControllerCheckout extends JControllerLegacy {

	
	public function display($cachable = false, $urlparams = Array()){
		$app	= JFactory::getApplication();
		$id		= JRequest::getInt('item_id', 0);
		$cid	= JRequest::getInt('cid', 0);
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$itemid	= JRequest::getVar('Itemid');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$quantity = JRequest::getInt('quantity', 0);
		
		if($user->id){
			parent::display();
		}else{
			$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
			$link = 'index.php?option=com_djclassifieds&view=checkout&cid='.$cid.'&item_id='.$id.'&quantity='.$quantity;
			$app->redirect('index.php?option=com_users&view=login&return='.base64_encode($link),$msg);
		}				
	}	
	
	function saveCheckout(){
		$app  	  = JFactory::getApplication();
		$par  	  = JComponentHelper::getParams( 'com_djclassifieds' );
		$user 	  = JFactory::getUser();
		$db   	  = JFactory::getDBO();
		$id   	  = JRequest::getInt('item_id', 0);		
		$quantity = JRequest::getInt('quantity', 0);
		$opt_id   = JRequest::getInt('buynow_option', 0);
		$dispatcher	= JDispatcher::getInstance();
		
		
		$query ="SELECT i.*, c.points as c_points, c.autopublish as c_autopublish, c.alias as c_alias FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."WHERE i.id=".$id." LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		$redirect_a=0;
		
		if(!$item){
			$redirect_a=1;
			$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
		}else{				
			$opt_name = '';
			if($opt_id){
				$query ="SELECT f.* FROM #__djcf_fields_values_sale f "
						."WHERE f.id=".$opt_id." AND f.item_id =".$id." ORDER BY f.id";
				$db->setQuery($query);
				$item_opt =$db->loadObject();
					
				if($item_opt){
					$options = json_decode($item_opt->options);
					foreach($options as $o){
						if($opt_name){ $opt_name .= ' - ';}
						$opt_name .= $o->label.': '.$o->value;
					}
					$item->quantity = $item_opt->quantity; 
				}
			}
		}
		
		if($item->quantity<$quantity){
			$redirect_a=1;
			$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
			$message = JText::_('COM_DJCLASSIFIEDS_NUMBER_OF_PRODUCTS_IS_LESS_THEN_SELECTED');
		}
		
		if($user->id==0){
			$redirect_a=1;
			$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
			$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			$message = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
		}
			
		if($redirect_a==0){
			$user_ip = $_SERVER['REMOTE_ADDR'];
			
			$query="INSERT INTO #__djcf_orders (`item_id`, `user_id`, `ip_address`, `price`,`currency`, `quantity`, `status`,`item_name`,`item_option`)"
					." VALUES ( '".$item->id."','".$user->id."','".$user_ip."', '".$item->price."', '".$item->currency."','".$quantity."','1','".addslashes($item->name)."','".addslashes($opt_name)."')";
			$db->setQuery($query);
			$db->query();	
			
			$query = "SELECT * FROM #__djcf_orders WHERE item_id='".$item->id."' AND user_id='".$user->id."' "
					." ORDER BY id DESC LIMIT 1";
			$db->setQuery($query);
			$order = $db->loadObject();
				
			$dispatcher->trigger('onAfterCheckoutSave', array (& $item, & $user, $order));
			
			$new_quantity = $item->quantity - $quantity;
			$query="UPDATE #__djcf_items SET quantity='".$new_quantity."' "
					." WHERE id=".$item->id;
			$db->setQuery($query);
			$db->query();
			
			if($opt_id){
				$query="UPDATE #__djcf_fields_values_sale SET quantity='".$new_quantity."' "
						." WHERE id=".$opt_id;
				$db->setQuery($query);
				$db->query();					
			}
			
			
			DJClassifiedsNotify::notifyBuynowBuyer($id,$user,$quantity,$opt_name);
			DJClassifiedsNotify::notifyBuynowAuthor($id,$user,$quantity,$opt_name);
			$redirect=DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
			$message = JText::_('COM_DJCLASSIFIEDS_ORDER_PLACED_SUCCESSFULLY');
		}
		
		
		$redirect = JRoute::_($redirect,false);
		$app->redirect($redirect, $message);
		
	}
	
	function payPoints(){
		$app  = JFactory::getApplication();
		$par  = JComponentHelper::getParams( 'com_djclassifieds' );		
		$user = JFactory::getUser();
		$db   = JFactory::getDBO();
		$id   = JRequest::getInt('id', 0);
		$type = JRequest::getVar('type', '');
		

			$query ="SELECT i.*, c.points as c_points, c.autopublish as c_autopublish, c.alias as c_alias FROM #__djcf_items i "
				   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				   ."WHERE i.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			$redirect_a=0;
			if(!$item){
				$redirect_a=1;				
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			}			
			if($item->user_id!=$user->id){
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
			if($type=='prom_top'){
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