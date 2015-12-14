<?php
/**
* @version      1.0
* @package      DJ Classifieds
* @subpackage   DJ Classifieds Payment Plugin
* @copyright    Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license      http://www.gnu.org/licenses GNU/GPL
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

class plgdjclassifiedspaymentdjcf2co extends JPlugin
{
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        
        $this->loadLanguage();
        $lang = JFactory::getLanguage();
        $lang->load('plg_djclassifiedspayment_djcf2co', JPATH_ROOT.DS.'plugins'.DS.'djclassifiedspayment'.DS.'djcf2co', 'en-GB', false, false);
        $lang->load('plg_djclassifiedspayment_djcf2co', JPATH_ADMINISTRATOR, 'en-GB', false, false );
        $lang->load('plg_djclassifiedspayment_djcf2co', JPATH_ROOT.DS.'plugins'.DS.'djclassifiedspayment'.DS.'djcf2co', null, true, false);
        $lang->load('plg_djclassifiedspayment_djcf2co', JPATH_ADMINISTRATOR, null, true, false );
        
        $params["plugin_name"] = "djcf2co";
        $params["icon"] = "2checkout-logo.png";
        $params["logo"] = "2checkout-logo.png";
        $params["description"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_PAYMENT_METHOD_DESC");
        $params["payment_method"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_PAYMENT_METHOD_NAME");
        $params["testmode"] = $this->params->get("test", "1");
        $params["currency_code"] = $this->params->get("currency_code", "USD");
        $params["sid"] = $this->params->get("sid", null);
        $params["secret_word"] = $this->params->get("secret_word", null);
        $params["2co_url"] = 'https://www.2checkout.com/checkout/purchase';
        $params["debug"] = $this->params->get('debug', null);
        
        if ($this->params->get('debug', null) == '1') {
            JLog::addLogger(array('logger' => 'formattedtext', 'text_file' => 'djcf2co.log.php'), JLog::ALL, array('djcf2co'));
        }
        
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
        $db             = JFactory::getDBO();
        $par            = JComponentHelper::getParams( 'com_djclassifieds' );
        $account_type   = $this->params["testmode"];
        $order_id       = (int)JRequest::getVar('vendor_order_id',null);
        
        $passback = array(); 
        $request_fields = array('sale_id',
                                'md5_hash', 
                                'vendor_id', 
                                'vendor_order_id', 
                                'invoice_id', 
                                'fraud_status',
                                'invoice_status', 
                                'order_number', 
                                'invoice_list_amount',
                                'list_currency', 
                                'message_type',
                                'message_description',
                                'message_id'
                                );
        
        foreach ($request_fields as $field) {
            $passback[$field] = (isset($_REQUEST[$field])) ? $_REQUEST[$field] : false;
        }
        
        $this->_log($order_id, print_r($passback, true));
        
        if (!$this->_checkHash($passback, $this->params["secret_word"])) {
            $this->_log($order_id, 'wrong hash');
            die('Wrong hash');
        }
        
        if(!$order_id){
            $this->_log($order_id, 'wrong order ID');
            die('Wrong order ID');
        }
        
        $query = "SELECT p.*  FROM #__djcf_payments p "
        		."WHERE p.id='".$order_id."' ";
        $db->setQuery($query);
        $payment = $db->loadObject();
        if(empty($payment)){
        	$this->_log($order_id, 'wrong item');
        	die('Wrong item');
        }
        
      /*  $query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
           ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
           ."WHERE i.id=".$order_id." LIMIT 1";
        $db->setQuery($query);
        $item = $db->loadObject();
        
        if(empty($item)){
            $this->_log($order_id, 'wrong item');
            die('Wrong item');
        }*/
        
        //$amount = $this->_getOrderAmount($item);
        $amount = $payment->price;
        
        $this->_log($order_id, 'amount: '.$amount);
        
        $transaction_id = (int)$passback['invoice_id'];
        $transaction_amount = $passback['invoice_list_amount'];
        $transaction_currency = $passback['list_currency'];
        
        $status = $passback['fraud_status'];
        $invoice_status = $passback['invoice_status'];
        
        if(floatval($transaction_amount) != floatval($amount)) {
            $status .= ' - wrong amount';
            $this->_log($order_id, 'wrong amount');
        }
        
        if($transaction_currency != $this->params['currency_code']) {
            $status .= ' - wrong currency';
            $this->_log($order_id, 'wrong currency');
        }

        if($status=='pass' || ($status != 'fail' && $invoice_status == 'approved')) {
            $this->_log($order_id, 'payment OK, publishing the advert');
            if($payment->type==2){										
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
            			."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." 2Checkout ".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').' '.$payment->id."')";
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
            
            	$query = "UPDATE #__djcf_items SET payed=1, pay_type='', published='".$pub."' "
            			."WHERE id=".$payment->item_id." ";
            	$db->setQuery($query);
            	$db->query();
            }
            
            $query = "UPDATE #__djcf_payments SET status='Completed', transaction_id=".$transaction_id." "
                    ."WHERE id=".$order_id." AND method='djcf2co'";                   
            $db->setQuery($query);
            $db->query();
            
            /*$query = "UPDATE #__djcf_payments SET status='Completed', transaction_id=".$transaction_id." "
                    ."WHERE item_id=".$order_id." AND method='djcf2co'";                   
            $db->setQuery($query);
            $db->query();
            
            $query = "SELECT c.*  FROM #__djcf_items i, #__djcf_categories c "
                    ."WHERE i.cat_id=c.id AND i.id='".$order_id."' ";                 
            $db->setQuery($query);
            $cat = $db->loadObject();
            
            $pub=0;
            if(($cat->autopublish=='1') || ($cat->autopublish=='0' && $par->get('autopublish')=='1')){                      
                $pub = 1;                                                   
            }
    
            $query = "UPDATE #__djcf_items SET payed=1, pay_type='', published='".$pub."' "
                    ."WHERE id=".$order_id." ";                   
            $db->setQuery($query);
            $db->query();*/
        
        } else {
            if ($status == 'fail' || $invoice_status == 'declined' || $passback['message_type'] == 'REFUND_ISSUED') {
                $this->_log($order_id, 'payment declined, unpublishing the advert');
                $query = "UPDATE #__djcf_items SET payed=0, published=0 WHERE id=".$payments->item_id;                   
                $db->setQuery($query);
                $db->query();
            }
            
            $this->_log($order_id, 'updating payment status');
            $status .= ' '.$passback['message_description'];
            
            $query = "UPDATE #__djcf_payments SET status='".$status."', transaction_id=".$transaction_id." WHERE id=".$order_id." AND method='djcf2co'";                   
            $db->setQuery($query);
            $db->query();   
        }
        echo 'Success - Hash Matched';
        exit();
    }
    
    function process($id)
    {
    	JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
    	jimport( 'joomla.database.table' );
        $db     = JFactory::getDBO();
        $app    = JFactory::getApplication();
        $Itemid = JRequest::getInt("Itemid",'0');
        $par    = JComponentHelper::getParams( 'com_djclassifieds' );
        $user   = JFactory::getUser();
        $ptype  = JRequest::getVar('ptype');
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
							
				$amount = $this->_getOrderAmount($item);
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
         
        //$itemname = $item->name;
        
        $post_variables = array();
        $post_variables['sid'] = $this->params['sid'];
        $post_variables['mode'] = '2CO';
        if ($this->params['testmode']) {
            $post_variables['demo'] = 'Y';
        }
        $post_variables['currency_code'] = $this->params['currency_code'];
        $post_variables['li_0_type'] = 'product';
        $post_variables['li_0_name'] = JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_AD_PUBLICATION').' - '.$itemname;
        $post_variables['li_0_price'] = number_format($amount, 2, '.', '');
        $post_variables['li_0_quantity'] = '1';
        $post_variables['li_0_tangible'] = 'N';
        $post_variables['merchant_order_id'] = $item_id; 
        
        $html = '<html><head><title>'.JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_REDIRECTION').'</title></head><body><div style="margin: auto; text-align: center;">';
        $html .= '<p>'.JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_REDIRECTION').'</p>';
        $html .= '<form action="' . $this->params['2co_url'] . '" method="post" name="tco_form" >';
        $html.= '<noscript><input type="submit"  value="' . JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCF2CO_REDIRECT_BUTTON') . '" /></noscript>';
        foreach ($post_variables as $name => $value) {
            $html.= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
        }
        $html.= '</form></div>';
        $html.= ' <script type="text/javascript">';
        $html.= ' document.tco_form.submit();';
        $html.= ' </script></body></html>';
        
        echo $html;
        
        jexit();
    }

    function onPaymentMethodList($val)
    {
    	$type='';
    	if($val['type']){
    		$type='&type='.$val['type'];
    	}
        $html ='';
        if ($this->params['sid'] != '' && $this->params['currency_code'] != '') {
            $paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
            $form_action = JRoute :: _("index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type, false);
            $html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
                <tr>';
                    if($this->params["logo"] != ""){
                $html .='<td class="td1" width="160" align="center">
                        <img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
                    </td>';
                     }
                    $html .='<td class="td2">
                        <h2>'.$this->params['payment_method'].'</h2>
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
    function _getOrderAmount($item) {
        $db = JFactory::getDbo();
        
        $amount = 0;
            
        if(strstr($item->pay_type, 'cat')){         
            $amount += $item->c_price/100; 
        }
        if(strstr($item->pay_type, 'duration_renew')){          
            $query = "SELECT d.price_renew FROM #__djcf_days d "
            ."WHERE d.days=".$item->exp_days;
            $db->setQuery($query);
            $amount += $db->loadResult();
        }else if(strstr($item->pay_type, 'duration')){          
            $query = "SELECT d.price FROM #__djcf_days d "
            ."WHERE d.days=".$item->exp_days;
            $db->setQuery($query);
            $amount += $db->loadResult();
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
        
        return $amount;
    }
    
    function _checkHash($insMessage, $secretWord)
    {
        $hashSid = $insMessage['vendor_id'];
        $hashOrder = $insMessage['sale_id'];
        $hashInvoice = $insMessage['invoice_id'];
        $StringToHash = strtoupper(md5($hashOrder . $hashSid . $hashInvoice . $secretWord));
        if ($StringToHash != $insMessage['md5_hash']) {
            return false;
        } else {
            return true;
        }
        return false;
    }
    
    function _log($order_id, $message) {
        if ($this->params["debug"] == '1') {
            JLog::add('DJCF 2Checkout - Order ID: '.$order_id.' - '.$message, JLog::INFO, 'djcf2co');    
        }
    }
}
?>