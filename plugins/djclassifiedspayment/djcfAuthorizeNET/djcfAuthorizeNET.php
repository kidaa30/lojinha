<?php
/**
* @version		1.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Payment Plugin
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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');
$lang = JFactory::getLanguage();
$lang->load('plg_djclassifiedspayment_djcfAuthorizeNET',JPATH_ADMINISTRATOR);

class plgdjclassifiedspaymentdjcfAuthorizeNET extends JPlugin
{
	function plgdjclassifiedspaymentdjcfAuthorizeNET( &$subject, $config)
	{
		parent::__construct($subject, $config);
	
		$this->loadLanguage('plg_djcfAuthorizeNET');
		$params["plugin_name"] = "djcfAuthorizeNET";
		$params["icon"] = "";
		$params["logo"] = "authorized.jpg";
		$params["description"] = JText::_("PLG_DJCFAUTHORIZENET_PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PLG_DJCFAUTHORIZENET_PAYMENT_METHOD_NAME");

		$params["login_id"] = $this->params->get("login_id");
		$params["transaction_key"] = $this->params->get("transaction_key");
		$params["currency_code"] = $this->params->get('currency_code', "USD");
		$params["account_type"] = $this->params->get('account_type', "test");
		$this->params = $params;

	}
	function onProcessPayment()
	{
		$ptype = JRequest::getVar('ptype','');
		$id = JRequest::getInt('id','');
		$html="";
		if($ptype == $this->params["plugin_name"])
		{
			$action = JRequest::getVar('pactiontype','');
			switch ($action)
			{
				case "process" :
				$html = $this->process($id);
				break;
				case "notify" :
				$html = $this->_notify_url();
				break;
				case "paymentmessage" :
				$html = $this->_paymentsuccess();
				break;
				default :
				$html =  $this->process($id);
				break;
			}
		}
		return $html;
	}
	function _notify_url()
	{
		
		require_once(JPATH_BASE.'/plugins/djclassifiedspayment/djcfAuthorizeNET/djcfAuthorizeNET/anet_php_sdk/AuthorizeNet.php');				
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$account_type=$this->params["account_type"];
		$Itemid = JRequest::getInt("Itemid",'0');
		$merchant_id = $this->params["login_id"];
		$merchant_key = $this->params["transaction_key"];
		$currency = $this->params["currency_code"];
		$user	= JFactory::getUser();
		$id	= JRequest::getInt('id','0');
		$ptype=JRequest::getVar('ptype');
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$type=JRequest::getVar('type','');
		$row = &JTable::getInstance('Payments', 'DJClassifiedsTable');
		//print_r($type);die();
		 if($type=='prom_top'){        	        	
        	$query ="SELECT i.* FROM #__djcf_items i "
        			."WHERE i.id=".$id." LIMIT 1";
        	$db->setQuery($query);
        	$item = $db->loadObject();
        	if(!isset($item)){
        		$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
        		$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
        	}        						 
        					 
       		$row->item_id = $id;
       		$row->user_id = $user->id;
      		$row->method = $ptype;
       		$row->status = 'Start';
      		$row->ip_address = $_SERVER['REMOTE_ADDR'];
       		$row->price = $par->get('promotion_move_top_price',0);
       		$row->type=2;        	
       		$row->store();

       		$amount = $par->get('promotion_move_top_price',0);
      		$itemname = $item->name;
       		$item_id = $row->id;
       		$item_cid = '&cid='.$item->cat_id;        	
        }else if($type=='points'){
			$query ="SELECT p.* FROM #__djcf_points p "
					."WHERE p.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$points = $db->loadObject();
			if(!isset($item)){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_POINTS_PACKAGE');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			}
			$row->item_id = $id;
			$row->user_id = $user->id;
			$row->method = $ptype;
			$row->status = 'Start';
			$row->ip_address = $_SERVER['REMOTE_ADDR'];
			$row->price = $points->price;
			$row->type=1;
		
			$row->store();
				
			$amount = $points->price;
			$itemname = $points->name;
			$item_id = $row->id;
			$item_cid = '';
		}else if($type=='plan'){        	        	
        	$query ="SELECT p.* FROM #__djcf_plans p "
        			."WHERE p.id=".$id." LIMIT 1";
        	$db->setQuery($query);
        	$plan = $db->loadObject();
        	if(!isset($plan)){
        		$message = JText::_('COM_DJCLASSIFIEDS_SUBSCRIPTION_PLAN');
        		$redirect="index.php?option=com_djclassifieds&view=plans";
        	}        						 
        	
        					 
       		$row->item_id = $id;
       		$row->user_id = $user->id;
      		$row->method = $ptype;
       		$row->status = 'Start';
      		$row->ip_address = $_SERVER['REMOTE_ADDR'];
       		$row->price = $plan->price;
       		$row->type=3;        	
       		$row->store();

       		$amount = $plan->price;
      		$itemname = $plan->name;
       		$item_id = $row->id;
       		$item_cid = '';       	
        }else{
			$query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."WHERE i.id=".$id." LIMIT 1";
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!isset($item)){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
			}
				
			$amount = 0;
		
			if(strstr($item->pay_type, 'cat')){
				$amount += $item->c_price/100;
			}

			
			$query = "SELECT * FROM #__djcf_days d "
					."WHERE d.days=".$item->exp_days." LIMIT 1";
			$db->setQuery($query);
			$day = $db->loadObject();
			
			
			if(strstr($item->pay_type, 'duration_renew')){
				$amount += $day->price_renew;
			}else if(strstr($item->pay_type, 'duration')){
				$amount += $day->price;
			}
			
			if(strstr($item->pay_type, 'extra_img_renew')){
				if($day->img_price_default){
					$amount += $par->get('img_price_renew','0')*$item->extra_images_to_pay;
				}else{
					$amount += $day->img_price_renew*$item->extra_images_to_pay;
				}
			}else if(strstr($item->pay_type, 'extra_img')){
				if($day->img_price_default){
					$amount += $par->get('img_price','0')*$item->extra_images_to_pay;
				}else{
					$amount += $day->img_price*$item->extra_images_to_pay;
				}
			}
			
			if(strstr($item->pay_type, 'extra_chars_renew')){
				if($day->char_price_default){
					$amount += $par->get('desc_char_price_renew','0')*$item->extra_chars_to_pay;
				}else{
					$amount += $day->char_price_renew*$item->extra_chars_to_pay;
				}
			}else if(strstr($item->pay_type, 'extra_chars')){
				if($day->char_price_default){
					$amount += $par->get('desc_char_price','0')*$item->extra_chars_to_pay;
				}else{
					$amount += $day->char_price*$item->extra_chars_to_pay;
				}
			}			
			
		
			$query = "SELECT p.* FROM #__djcf_promotions p "
					."WHERE p.published=1 ORDER BY p.id ";
			$db->setQuery($query);
			$promotions=$db->loadObjectList();
			foreach($promotions as $prom){
				if(strstr($item->pay_type, $prom->name)){
					$amount += $prom->price;
				}
			}
				
			/*$query = 'DELETE FROM #__djcf_payments WHERE item_id= "'.$id.'" ';
			 $db->setQuery($query);
			$db->query();
		
		
			$query = 'INSERT INTO #__djcf_payments ( item_id,user_id,method,  status)' .
			' VALUES ( "'.$id.'" ,"'.$user->id.'","'.$ptype.'" ,"Start" )'
			;
			$db->setQuery($query);
			$db->query();*/
		
			$row->item_id = $id;
			$row->user_id = $user->id;
			$row->method = $ptype;
			$row->status = 'Start';
			$row->ip_address = $_SERVER['REMOTE_ADDR'];
			$row->price = $amount;
			$row->type=0;
		
			$row->store();
				
		
		
			$itemname = $item->name;
			$item_id = $row->id;
			$item_cid = '&cid='.$item->cat_id;
		}


		$query = "SELECT p.*  FROM #__djcf_payments p "
        		."WHERE p.id='".$item_id."' ";
        $db->setQuery($query);
        $payment = $db->loadObject();
	
		$login_id = $this->params["login_id"];
		$transaction_key = $this->params["transaction_key"];
		$card_no = JRequest::getVar('card_no');
		$msg_style='';

			//include_once "phpcreditcard.php";
			$card_num    = JRequest::getVar('card_no','0','','string');
			$card_type   = JRequest::getVar('card_type');
			$exp_date    = JRequest::getVar('exp_date','0','','int').'/'.JRequest::getVar('exp_year','0','','int');
			$cvv		 = JRequest::getVar('card_code','0','','int');

			if($card_num==0 || $cvv=='0'){
				$message = JText::_('COM_DJCLASSIFIEDS_ENTER_ALL_VALUES');
				$redirect= 'index.php?option=com_djclassifieds&view=payment&id='.$id.'&type='.$type.'&Itemid='.$Itemid;			
				$app->redirect($redirect, $message,'Error');
			}

			
		    define("AUTHORIZENET_API_LOGIN_ID", $merchant_id);
		    define("AUTHORIZENET_TRANSACTION_KEY", $merchant_key);
				if($account_type=='secure'){
					define("AUTHORIZENET_SANDBOX", false);
				}else{
					define("AUTHORIZENET_SANDBOX", true);	
				}
		    

		    $sale = new AuthorizeNetAIM;
		    $sale->amount = $amount; 
		    $sale->card_num = $card_num;
		    $sale->exp_date = $exp_date;
		    //$sale->card_num = '4007000000027';					
		    //$sale->exp_date = $exp_date;		    	
			//$sale->card_type = 'V';
			$sale->auth_code=JRequest::getVar('auth_code','0','','int');
		    $response = $sale->authorizeAndCapture();
			//echo '<pre>'; print_r($response);die();
		    if ($response->approved) {
					
					$query = "UPDATE #__djcf_payments SET status='Completed' "
							."WHERE id=".$item_id." AND method='djcfAuthorizeNET'";					
					$db->setQuery($query);
					$db->query();
					if($type=='prom_top'){
						$date_sort = date("Y-m-d H:i:s");
						$query = "UPDATE #__djcf_items SET date_sort='".$date_sort."' "
								."WHERE id=".$id." ";
						$db->setQuery($query);
						$db->query();
					}else if($type=='points'){
						
						$query = "SELECT p.points  FROM #__djcf_points p WHERE p.id='".$id."' ";					
						$db->setQuery($query);
						$points = $db->loadResult();
						
						$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
								."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." AuthorizeNET <br />".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').' '.$payment->id."')";					
						$db->setQuery($query);
						$db->query();																		
					}else{
						$query = "SELECT c.*  FROM #__djcf_items i, #__djcf_categories c "
								."WHERE i.cat_id=c.id AND i.id='".$id."' ";					
						$db->setQuery($query);
						$cat = $db->loadObject();
						
						$pub=0;
						if(($cat->autopublish=='1') || ($cat->autopublish=='0' && $par->get('autopublish')=='1')){						
							$pub = 1;							 						
						}
				
						$query = "UPDATE #__djcf_items SET payed=1, pay_type='',extra_images_to_pay='0',extra_chars_to_pay='0', published='".$pub."' "
								."WHERE id=".$id." ";					
						$db->setQuery($query);
						$db->query();			
						
						if($pub){
							DJClassifiedsNotify::notifyUserPublication($payment->item_id,'1');
						}
						
					}

					$payment->status='Completed';
					$dispatcher = JDispatcher::getInstance();
					$dispatcher->trigger('onAfterPaymentStatusChange', array($payment));					
									
				
				$message=JTExt::_('COM_DJCLASSIFIEDS_THANKS_FOR_PAYMENT_WAIT_FOR_CONFIRMATION');
				$redirect= 'index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$Itemid;									
			}else{				
				
				if($response->response_reason_text){
					$message = $response->response_reason_text;	
				}else{
					$message = $response->error_message;
				}
				$redirect= 'index.php?option=com_djclassifieds&view=payment&id='.$id.'&type='.$type.'&Itemid='.$Itemid;				
				$msg_style='Error';
			}
			
			$app->redirect($redirect, $message,$msg_style);
					
	}

	function onPaymentMethodList($val)
	{
		$html='';
		$login_id = $this->params["login_id"];
		$transaction_key = $this->params["transaction_key"];
		
		if($login_id!='' && $transaction_key!=''){
		$user	= JFactory::getUser();
		$Itemid = JRequest::getInt("Itemid",'0');
		$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];

		$action_url = JRoute :: _('index.php?option=com_djclassifieds&task=processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=notify&Itemid='.$Itemid.'&id='.$val["id"],false);
		$ADN_form = "<script language='javascript'>function adotnetSubmitForm(){if(document.addtocart.card_no.value==''){alert('Credit Card Number Field is Empty!');return;}else if(document.addtocart.card_code.value==''){alert('Credit Card Security Code Field is Empty!');return;}else{document.addtocart.submit(); } }</script><form name='addtocart' action='".$action_url."' method='post'>
		<table align='left'>
		<th><b>".JText::_('PLG_DJCFAUTHORIZENET_CREDIT_CART_PAYMENT').":</b></th>
		<tr>
		<td>".JText::_('PLG_DJCFAUTHORIZENET_CREDIT_CARD_TYPE').":</td>
		<td>
		<select name='card_type' id='card_type'>
		<option value='visa'>visa</option>
		<option value='Master_Card'>Master Card</option>
		<option value='American_Express'>American Express</option>
		</select>
		</td>
		</tr>

		<tr>
		<td>".JText::_('PLG_DJCFAUTHORIZENET_CREDIT_CARD_NUMBER').": </td>
		<td><input type='text' name='card_no' /></td>
		</tr>

		<tr>
		<td>".JText::_('PLG_DJCFAUTHORIZENET_CREDIT_CARD_SECURITY_CODE').": </td>
		<td><input type='text' name='card_code' /></td>
		</tr>

		<tr>
		<td>".JText::_('PLG_DJCFAUTHORIZENET_EXPIRATION_DATE').": </td>
		<td><select name='exp_date' id='exp_date'>
		                  <option value='1'>".JText::_('JANUARY')."</option>
						  <option value='02'>".JText::_('FEBRUARY')."</option>
						  <option value='03'>".JText::_('MARCH')."</option>
						  <option value='04'>".JText::_('APRIL')."</option>
						  <option value='05'>".JText::_('MAY')."</option>
						  <option value='06'>".JText::_('JUNE')."</option>
						  <option value='07'>".JText::_('JULY')."</option>
		                  <option value='08'>".JText::_('AUGUST')."</option>
						  <option value='09'>".JText::_('SEPTEMBER')."</option>
						  <option value='10'>".JText::_('OCTOBER')."</option>
						  <option value='11'>".JText::_('NOVEMBER')."</option>
						  <option value='12'>".JText::_('DECEMBER')."</option>


		                  </select>
						  <select name='exp_year' id='exp_year'>
						  <option value='15'>2015</option>
						  <option value='16'>2016</option>
		                  <option value='17'>2017</option>
						  <option value='18'>2018</option>
						  <option value='19'>2019</option>
						  <option value='20'>2020</option>
					  	  <option value='21'>2021</option>
					  	  <option value='22'>2022</option>
					  	  <option value='23'>2023</option>
					  	  <option value='24'>2024</option>
					  	  <option value='25'>2025</option>

		                  </select></td>
						  </tr>
						  <tr><td></td><td>";

						$ADN_form .= "<input type='hidden' name='option' value='com_djclassifieds' /><input type='hidden' name='task' value='processPayment' /><input type='hidden' name='ptype' value='".$this->params["plugin_name"]."' /><input type='hidden' name='pactiontype' value='notify' /><input type='hidden' name='type' value='".JRequest::getCmd('type','')."' />";
						$ADN_form .= "</td></tr> </table>

		</form>";

		$html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
			<tr>';
				if($this->params["logo"] != ""){
			$html .='<td class="td1" width="160" align="center">
					<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
				</td>';
				 }
				$html .='<td class="td2">
					<h2>Authorize.net</h2>
					<p style="text-align:justify;">'.$this->params["description"]."<br/>".$ADN_form.'</p>
				</td>
				<td class="td3" width="130" align="center">
					<a class="button"  style="text-decoration:none;" onclick="adotnetSubmitForm();" href="javascript:void(0);">'.JText::_('COM_DJCLASSIFIEDS_BUY_NOW').'</a>
				</td>
			</tr>
		</table>';
		}
		return $html;
	}


	
}

?>