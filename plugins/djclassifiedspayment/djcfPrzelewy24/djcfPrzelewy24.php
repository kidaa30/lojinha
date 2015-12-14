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
$lang->load('plg_djclassifiedspayment_djcfPrzelewy24',JPATH_ADMINISTRATOR);
class plgdjclassifiedspaymentdjcfPrzelewy24 extends JPlugin
{
	function plgdjclassifiedspaymentdjcfPrzelewy24( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_djcfPrzelewy24');
		$params["plugin_name"] = "djcfPrzelewy24";
		$params["icon"] = "przelewy24_icon.png";
		$params["logo"] = "przelewy24_overview.png";
		$params["description"] = JText::_("PLG_DJCFPRZELEWY24_PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PLG_DJCFPRZELEWY24_PAYMENT_METHOD_NAME");
		$params["testmode"] = $this->params->get("test");
		$params["currency_code"] = $this->params->get("currency_code");
		$params["p24_id"] = $this->params->get("p24_id");
		$params["p24_crc"] = $this->params->get("p24_crc");
		$this->params = $params;

	}
	
	function onPaymentMethodList($val)
	{
		$html ='';
		$user = JFactory::getUser();
		$type='';
		$type_i = '';
		if($val['type']){
			$type='&type='.$val['type'];
			$type_i = '<input type="hidden" name="type" value="'.$val['type'].'" />';
		}
		if($this->params["p24_id"]!='' && $this->params["p24_crc"]!=''){
			$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
			//$form_action = JRoute :: _("index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type, false);
			$form_action = JURI::root()."index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type;
			$html ='
				<form action="index.php" method="get" class="form-validate" id="djcf_p24" name="djcf_p24" >
					<input type="hidden" name="option" value="com_djclassifieds" />
					<input type="hidden" name="task" value="processPayment" />
					<input type="hidden" name="ptype" value="'.$this->params["plugin_name"].'" />
					<input type="hidden" name="pactiontype" value="process" />
					<input type="hidden" name="id" value="'.$val["id"].'" />
					'.$type_i.'
					<table cellpadding="5" cellspacing="0" width="100%" border="0">
					<tr>';					
					if($this->params["logo"] != ""){
					$html .='<td class="td1" width="160" align="center">
							<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
						</td>';
						 }
						$html .='<td class="td2">
							<h2>Przelewy 24</h2>
							<p style="text-align:justify;">'.$this->params["description"].'</p>';
							if($user->id==0){
								$html .='<div class="email_box"><span>'.JText::_('JGLOBAL_EMAIL').':*</span> <input size="50" class="validate-email required" type="text" name="email" value=""></div>';
							}							
						$html.='</td>
						<td  class="td3" width="130" align="center">														
							<input type="submit" class="button" border="0" value="'.JText::_('COM_DJCLASSIFIEDS_BUY_NOW').'" />
						</td>
					</tr>
				</table>
			</form>';
			//<a class="button" style="text-decoration:none;" href="'.$form_action.'">'.JText::_('COM_DJCLASSIFIEDS_BUY_NOW').'</a>
		}
		return $html;
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
		$par = JComponentHelper::getParams( 'com_djclassifieds' );

		$user	= JFactory::getUser();
		$id	= JRequest::getInt('id','0');
		$app = JFactory::getApplication();
		$itemid = JRequest::getInt("Itemid","");	
		
		$przelewy24_info = $_POST;
		$przelewy24_ipn = new przelewy24_ipn();		
		
		$p24_session_id = $_POST["p24_session_id"];
		$p24_order_id = $_POST["p24_order_id"];
		$p24_id_sprzedawcy = $this->params["p24_id"];
		
		$query = "SELECT p.*  FROM #__djcf_payments p "
				."WHERE p.id='".$id."' ";
		$db->setQuery($query);
		$payment = $db->loadObject();
		$p24_kwota = $payment->price*100; 
		
		//		$p24_kwota = WYNIK POBRANY Z TWOJEJ BAZY (w groszach)

		$res =	$przelewy24_ipn->send_response($p24_id_sprzedawcy, $p24_session_id, $p24_order_id, $p24_kwota, $this->params);

		//print_R($res);die();
		if ($res[0]!='TRUE'){
			$message=JTExt::_('PLG_DJCFPRZELEWY24_AFTER_ERROR_MSG');
			$redirect= 'index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$itemid;
			$app->redirect($redirect, $message);
			die();
		} else {
			if($payment){
				$query = "UPDATE #__djcf_payments SET status='Completed',transaction_id='".$p24_session_id."' "
						."WHERE id=".$id." AND method='djcfPrzelewy24'";
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
							."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." Przelewy24 <br />".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').' '.$payment->id."')";
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

		}	
				
		$message=JTExt::_('PLG_DJCFPRZELEWY24_AFTER_SUCCESSFULL_MSG');
		$redirect= 'index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$itemid;
		$app->redirect($redirect, $message);
		
	}
	
	function process($id)
	{
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid",'0');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$user = JFactory::getUser();
		$ptype=JRequest::getVar('ptype');
		$type=JRequest::getVar('type','');
		$row = JTable::getInstance('Payments', 'DJClassifiedsTable');
		
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
			

		if($user->id>0){
			$email = $user->email;
		}else{
			$email = JRequest::getVar('email','');
		}
							
		$przelewy24URL = 'https://secure.przelewy24.pl/index.php';
		if ($this->params["testmode"]=="1"){
			$itemname = 'TEST_OK';
			$przelewy24URL = 'https://sandbox.przelewy24.pl/index.php';
			//$itemname = 'TEST_ERR';
		}
		$amount = $amount*100;
		$crc_hash = md5($item_id.'|'.$this->params["p24_id"].'|'.$amount.'|'.$this->params["p24_crc"]);

		echo JText::_('PLG_DJCFPRZELEWY24_REDIRECTING_PLEASE_WAIT');
			
			$form ='<form id="przelewy24form" action="'.$przelewy24URL.'" method="post">';
			$form .='<br /><input type="hidden" id="p24_session_id" name="p24_session_id" value="'.$item_id.'">';
			$form .='<br /><input type="hidden" name="p24_id_sprzedawcy" value="'.$this->params["p24_id"].'">';
			$form .='<br /><input type="hidden" name="p24_kwota" value="'.$amount.'">';
			$form .='<br /><input type="hidden" name="p24_email" value="'.$email.'">';
	
			$form .='<br /><input type="hidden" name="p24_return_url_ok" value="'.JRoute::_(JURI::base().'index.php?option=com_djclassifieds&task=processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=notify&id='.$item_id.$item_cid.'&Itemid='.$Itemid).'">';
			$form .='<br /><input type="hidden" name="p24_return_url_error" value="'.JRoute::_(JURI::base().'index.php?option=com_djclassifieds&task=paymentReturn&r=error&id='.$item_id.$item_cid.'&Itemid='.$Itemid).'">';
			
			$form .='<br /><input type="hidden" name="p24_metoda" value="">';
			$form .='<br /><input type="hidden" name="p24_opis" value="'.$itemname.'">';
			$form .='<br /><input type="hidden" name="p24_language" value="pl">';
			
			$form .='<br /><input type="hidden" name="p24_crc" value="'.$crc_hash.'">';
			$form .='</form>';
		echo $form;
		
	?>
		<script type="text/javascript">
			callpayment()
			function callpayment(){
				var id = document.getElementById('p24_session_id').value ;
				if ( id > 0 && id != '' ) {
					document.getElementById('przelewy24form').submit();
				}
			}
		</script>
	<?php
	die();
	}


}
class przelewy24_ipn{
	
	
	function send_response($p24_id_sprzedawcy, $p24_session_id, $p24_order_id, $p24_kwota="", $plgParams){
		
		$testmode = $plgParams['testmode'];
		$p24_crc = $plgParams['p24_crc'];
		
		$P = array(); $RET = array();
		if($testmode) $url = "https://sandbox.przelewy24.pl/transakcja.php";
		else $url = "https://secure.przelewy24.pl/transakcja.php";
		$P[] = "p24_id_sprzedawcy=".$p24_id_sprzedawcy;
		$P[] = "p24_session_id=".$p24_session_id;
		$P[] = "p24_order_id=".$p24_order_id;
		$P[] = "p24_kwota=".$p24_kwota;
		$P[] = "p24_crc=".md5($p24_session_id."|". $p24_order_id."|". $p24_kwota."|".$p24_crc);
		$user_agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST,1);
		if(count($P)) curl_setopt($ch, CURLOPT_POSTFIELDS,join("&",$P));
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$result=curl_exec ($ch);
		curl_close ($ch);
		$T = explode(chr(13).chr(10),$result);
		$res = false;
		
		foreach($T as $line){
			$line = ereg_replace("[\n\r]","",$line);
			if($line != "RESULT" and !$res) continue;
			if($res) $RET[] = $line;
			else $res = true;
		}
		return $RET;
	}

	
}
?>