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
$lang->load('plg_djclassifiedspayment_djcfPaypal',JPATH_ADMINISTRATOR);
class plgdjclassifiedspaymentdjcfPaypal extends JPlugin
{
	function plgdjclassifiedspaymentdjcfPaypal( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_djcfPaypal');
		$params["plugin_name"] = "djcfPaypal";
		$params["icon"] = "paypal_icon.png";
		$params["logo"] = "paypal_overview.png";
		$params["description"] = JText::_("PLG_DJCFPAYPAL_PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PLG_DJCFPAYPAL_PAYMENT_METHOD_NAME");
		$params["testmode"] = $this->params->get("test");
		$params["currency_code"] = $this->params->get("currency_code");
		$params["email_id"] = $this->params->get("email_id");
		$this->params = $params;

	}
	function onProcessPayment()
	{
		$ptype = JRequest::getVar('ptype','');
		$id = JRequest::getInt('id','0');
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
		$db = JFactory::getDBO();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$account_type=$this->params["testmode"];
		$user	= JFactory::getUser();
		$id	= JRequest::getInt('id','0');
		$paypal_info = $_POST;
		
		/*$fil = fopen('ppraport/pp_raport.txt', 'a');
		fwrite($fil, "\n\n--------------------post_first-----------------\n");
		$post = $_POST;
		foreach ($post as $key => $value) {
		fwrite($fil, $key.' - '.$value."\n");
		}
		fclose($fil);*/

		$paypal_ipn = new paypal_ipn($paypal_info);
		foreach ($paypal_ipn->paypal_post_vars as $key=>$value)
		{
			if (getType($key)=="string")
			{
				eval("\$$key=\$value;");
			}
		}
		$paypal_ipn->send_response($account_type);
		if (!$paypal_ipn->is_verified())
		{
			die();
		}
		$paymentstatus=0;

			$status = $paypal_ipn->get_payment_status();
			$txn_id=$paypal_ipn->paypal_post_vars['txn_id'];
			
			if(($status=='Completed') || ($status=='Pending' && $account_type==1)){				
				
				$query = "SELECT p.*  FROM #__djcf_payments p "
						."WHERE p.id='".$id."' ";					
				$db->setQuery($query);
				$payment = $db->loadObject();
				
				if($payment){	

					if(JRequest::getVar('mc_gross') != $payment->price){
						die('Wrong amount');
					}
					
					$query = "UPDATE #__djcf_payments SET status='Completed',transaction_id='".$txn_id."' "
							."WHERE id=".$id." AND method='djcfPaypal'";					
					$db->setQuery($query);
					$db->query();
					
					
					if($row->type==3){ //subscription plans			
						$query = "SELECT p.*  FROM #__djcf_plans p WHERE p.id='".$row->item_id."' ";					
						$db->setQuery($query);
						$plan = $db->loadObject();
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
									."VALUES ('".$row->user_id."','".$plan->id."','".$plan_params->ad_limit."','".$plan_params->ad_limit."','".$date_start."','".$date_exp."','".addslashes($plan->params)."')";					
							$db->setQuery($query);
							$db->query();						
							$message = JText::_('COM_DJCLASSIFIEDS_STATUS_CHANGED_SUBSCRIPTION_PLAN_ADDED');	
																						
					}else if($payment->type==2){										
						$date_sort = date("Y-m-d H:i:s");
						$query = "UPDATE #__djcf_items SET date_sort='".$date_sort."' "
								."WHERE id=".$payment->item_id." ";
						$db->setQuery($query);
						$db->query();
					}else if($payment->type==1){
						
						$query = "SELECT p.points  FROM #__djcf_points p WHERE p.id='".$payment->item_id."' ";					
						$db->setQuery($query);
						$points = $db->loadResult();
						
						$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
								."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." PayPal <br />".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').' '.$payment->id."')";					
						$db->setQuery($query);
						$db->query();																		
					}else{
						$query = "SELECT c.*  FROM #__djcf_items i, #__djcf_categories c "
								."WHERE i.cat_id=c.id AND i.id='".$payment->item_id."' ";					
						$db->setQuery($query);
						$cat = $db->loadObject();
						
						$pub=0;
						if(($cat->autopublish=='1') || ($cat->autopublish=='0' && $par->get('autopublish')=='1')){						
							$pub = 1;							 						
						}
				
						$query = "UPDATE #__djcf_items SET payed=1, pay_type='',extra_images_to_pay='0',extra_chars_to_pay='0', published='".$pub."' "
								."WHERE id=".$payment->item_id." ";					
						$db->setQuery($query);
						$db->query();			
						
						if($pub){
							DJClassifiedsNotify::notifyUserPublication($payment->item_id,'1');
						}
					}		
					$payment->status='Completed';
					$dispatcher = JDispatcher::getInstance();
					$dispatcher->trigger('onAfterPaymentStatusChange', array($payment));									
				}									
			}else{
				$query = "UPDATE #__djcf_payments SET status='".$status."',transaction_id='".$txn_id."' "
						."WHERE id=".$id." AND method='djcfPaypal'";					
				$db->setQuery($query);
				$db->query();	
			}
				
		
		
		
	}
	
	function process($id)
	{
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');		
		jimport( 'joomla.database.table' );
		$db 	= JFactory::getDBO();
		$app 	= JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid",'0');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$user 	= JFactory::getUser();
		$ptype	= JRequest::getVar('ptype');
		$type	= JRequest::getVar('type','');
		$row 	= JTable::getInstance('Payments', 'DJClassifiedsTable');	

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

		$urlpaypal="";
		if ($this->params["testmode"]=="1")
		{
			$urlpaypal="https://www.sandbox.paypal.com/cgi-bin/webscr";
		}
		elseif ($this->params["testmode"]=="0")
		{
			$urlpaypal="https://www.paypal.com/cgi-bin/webscr";
		}
		header("Content-type: text/html; charset=utf-8");
		echo JText::_('PLG_DJCFPAYPAL_REDIRECTING_PLEASE_WAIT');
		$form ='<form id="paypalform" action="'.$urlpaypal.'" method="post">';
		$form .='<input type="hidden" name="cmd" value="_xclick">';
		$form .='<input id="custom" type="hidden" name="custom" value="'.$item_id.'">';
		$form .='<input type="hidden" name="business" value="'.$this->params["email_id"].'">';
		$form .='<input type="hidden" name="currency_code" value="'.$this->params["currency_code"].'">';
		$form .='<input type="hidden" name="item_name" value="'.$itemname.'">';
		$form .='<input type="hidden" name="amount" value="'.$amount.'">';
		$form .='<input TYPE="hidden" name="charset" value="utf-8">';
		$form .='<input type="hidden" name="cancel_return" value="'.JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=error&id='.$item_id.$item_cid.'&Itemid='.$Itemid).'">';
		$form .='<input type="hidden" name="notify_url" value="'.JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=notify&id='.$item_id).'">';
		$form .='<input type="hidden" name="return" value="'.JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=ok&id='.$item_id.$item_cid.'&Itemid='.$Itemid).'">';
		$form .='</form>';
		echo $form;
	?>
		<script type="text/javascript">
			callpayment()
			function callpayment(){
				var id = document.getElementById('custom').value ;
				if ( id > 0 && id != '' ) {
					document.getElementById('paypalform').submit();
				}
			}
		</script>
	<?php
	}

	function onPaymentMethodList($val)
	{
		$type='';
		if($val['type']){
			$type='&type='.$val['type'];	
		}		
		$html ='';
		if($this->params["email_id"]!=''){
			$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
			//$form_action = JRoute :: _("index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type, false);
			$form_action = JURI::root()."index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type;
			$html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
				<tr>';
					if($this->params["logo"] != ""){
				$html .='<td class="td1" width="160" align="center">
						<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
					</td>';
					 }
					$html .='<td class="td2">
						<h2>PAYPAL</h2>
						<p style="text-align:justify;">'.$this->params["description"].'</p>
					</td>
					<td class="td3" width="130" align="center">
						<a class="button" style="text-decoration:none;" href="'.$form_action.'">'.JText::_('COM_DJCLASSIFIEDS_BUY_NOW').'</a>
					</td>
				</tr>
			</table>';
		}
		return $html;
	}
}
class paypal_ipn
{
	var $paypal_post_vars;
	var $paypal_response;
	var $timeout;
	var $error_email;
	function paypal_ipn($paypal_post_vars) {
		$this->paypal_post_vars = $paypal_post_vars;
		$this->timeout = 120;
	}
	function send_response($account_type)
	{
		$fp  = '';
		if($account_type == '1')
		{
			$fp = @fsockopen( "www.sandbox.paypal.com", 80, $errno, $errstr, 120 );
		}else if($account_type == '0')
		{
			$fp = @fsockopen( "www.paypal.com", 80, $errno, $errstr, 120 );
		}
		if (!$fp) {
			$this->error_out("PHP fsockopen() error: " . $errstr , "");
		} else {
			foreach($this->paypal_post_vars AS $key => $value) {
				if (@get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}
				$values[] = "$key" . "=" . urlencode($value);
			}
			$response = @implode("&", $values);
			$response .= "&cmd=_notify-validate";
			fputs( $fp, "POST /cgi-bin/webscr HTTP/1.0\r\n" );
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" );
			fputs( $fp, "Content-length: " . strlen($response) . "\r\n\n" );
			fputs( $fp, "$response\n\r" );
			fputs( $fp, "\r\n" );
			$this->send_time = time();
			$this->paypal_response = "";

			while (!feof($fp)) {
				$this->paypal_response .= fgets( $fp, 1024 );

				if ($this->send_time < time() - $this->timeout) {
					$this->error_out("Timed out waiting for a response from PayPal. ($this->timeout seconds)" , "");
				}
			}
			fclose( $fp );
		}
	}
	function is_verified() {
		if( strstr($this->paypal_response,"VERIFIED") )
			return true;
		else
			return false;
	}
	function get_payment_status() {
		return $this->paypal_post_vars['payment_status'];
	}
	function error_out($message)
	{

	}
}
?>