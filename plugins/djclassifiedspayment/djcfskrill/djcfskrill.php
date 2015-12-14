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

class plgdjclassifiedspaymentdjcfSkrill extends JPlugin
{
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        
        $this->loadLanguage();
        $lang = JFactory::getLanguage();
        $lang->load('plg_djclassifiedspayment_djcfSkrill', JPATH_ROOT.DS.'plugins'.DS.'djclassifiedspayment'.DS.'djcfSkrill', 'en-GB', false, false);
        $lang->load('plg_djclassifiedspayment_djcfSkrill', JPATH_ADMINISTRATOR, 'en-GB', false, false );
        $lang->load('plg_djclassifiedspayment_djcfSkrill', JPATH_ROOT.DS.'plugins'.DS.'djclassifiedspayment'.DS.'djcfSkrill', null, true, false);
        $lang->load('plg_djclassifiedspayment_djcfSkrill', JPATH_ADMINISTRATOR, null, true, false );
        
        $params["plugin_name"] = "djcfskrill";
        $params["icon"] = "skrill_logo.png";
        $params["logo"] = "skrill_logo.png";
        $params["description"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_PAYMENT_METHOD_DESC");
        $params["payment_method"] = JText::_("PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_PAYMENT_METHOD_NAME");
        //$params["testmode"] = $this->params->get("test", "1");
        $params["currency_code"] = $this->params->get("currency_code", "USD");
        $params["merchant_id"] = $this->params->get("merchant_id", null);
        $params["pay_to_email"] = $this->params->get("pay_to_email", null);
        $params["secret_word"] = $this->params->get("secret_word", null);
        $params["skrill_url"] = 'https://www.moneybookers.com/app/payment.pl';
        $params["skrill_test_url"] = 'https://www.moneybookers.com/app/test_payment.pl';
        $params["debug"] = $this->params->get('debug', null);
        
        $params["languages"] = array('EN', 'DE', 'ES', 'FR', 'IT', 'PL', 'GR', 'RO', 'RU', 'TR', 'CN', 'CZ', 'NL', 'DA', 'SV', 'FI');
        
        if ($this->params->get('debug', null) == '1') {
            JLog::addLogger(array('logger' => 'formattedtext', 'text_file' => 'djcfskrill.log.php'), JLog::ALL, array('djcfskrill'));
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
        $order_id       = (int)JRequest::getVar('transaction_id',null);
        
        $passback = array(); 
        $request_fields = array('pay_to_email',
                                'pay_from_email', 
                                'merchant_id', 
                                'customer_id', 
                                'transaction_id', 
                                'mb_transaction_id',
                                'mb_amount', 
                                'mb_currency', 
                                'status', 
                                'amount',
                                'currency',
                                'md5sig',
                                'sha2sig',
                                'failed_reason_code',
                                'payment_type',
                                'merchant_fields'
                                );
        
        foreach ($request_fields as $field) {
            $passback[$field] = (isset($_POST[$field])) ? $_POST[$field] : false;
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
        
       /* $query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
           ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
           ."WHERE i.id=".$order_id." LIMIT 1";
        $db->setQuery($query);
        $item = $db->loadObject();
        
        if(empty($item)){
            $this->_log($order_id, 'wrong item');
            die('Wrong item');
        }
        
        $amount = $this->_getOrderAmount($item);*/
        
        $query = "SELECT p.*  FROM #__djcf_payments p "
        		."WHERE p.id='".$order_id."' ";
        $db->setQuery($query);
        $payment = $db->loadObject();
        
        if(empty($payment)){
        	$this->_log($order_id, 'wrong item');
        	die('Wrong item');
        }
        
        $amount = $payment->price;
        
        $this->_log($order_id, 'amount: '.$amount);
        
        $transaction_id = (int)$passback['transaction_id'];
        $transaction_amount = $passback['amount'];
        $transaction_currency = $passback['currency'];
        $status = $this->_getStatusName($passback['status']);
        
        if(floatval($transaction_amount) != floatval($amount)) {
            $status .= ' - wrong amount';
            $this->_log($order_id, 'wrong amount');
        }
        
        if($transaction_currency != $this->params['currency_code']) {
            $status .= ' - wrong currency';
            $this->_log($order_id, 'wrong currency');
        }

        if($status == 'processed') {
            $this->_log($order_id, 'payment OK, publishing the advert');
            
            if($payment->type==1){
            	$query = "SELECT p.points  FROM #__djcf_points p WHERE p.id='".$payment->item_id."' ";
            	$db->setQuery($query);
            	$points = $db->loadResult();
            
            	$query = "INSERT INTO #__djcf_users_points (`user_id`,`points`,`description`) "
            			."VALUES ('".$payment->user_id."','".$points."','".JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGE')." Skrill <br />".JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID').' '.$payment->id."')";
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
            		."WHERE id=".$order_id." AND method='djcfSkrill'";
            $db->setQuery($query);
            $db->query();
            
            /*$query = "UPDATE #__djcf_payments SET status='Completed', transaction_id=".$transaction_id." "
                    ."WHERE item_id=".$order_id." AND method='djcfSkrill'";                   
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
            $db->query(); */
        
        } else {
            if ($status == 'cancelled' || $status == 'failed') {
                $this->_log($order_id, 'payment declined or cancelled, unpublishing the advert');
                $query = "UPDATE #__djcf_items SET payed=0, published=0 WHERE id=".$payments->item_id;                  
                $db->setQuery($query);
                $db->query();
            }
            
            $this->_log($order_id, 'updating payment status: '.$status);
            
            $query = "UPDATE #__djcf_payments SET status='".$status."', transaction_id=".$transaction_id." WHERE id=".$order_id." AND method='djcfSkrill'";                   
            $db->setQuery($query);
            $db->query();   
        }
        echo 'OK';
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
        $lang 	= JFactory::getLanguage();
        $type	= JRequest::getVar('type','');
        $row 	= JTable::getInstance('Payments', 'DJClassifiedsTable');
        
        $lang_tag = strtoupper(substr($lang->getTag(), 0,2));

        if($type=='points'){
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
	        
	        $query = 'DELETE FROM #__djcf_payments WHERE item_id= "'.$id.'" ';
	        $db->setQuery($query);
	        $db->query();
	        
	        
	        $query = 'INSERT INTO #__djcf_payments ( item_id,user_id,method,  status)' .
	                ' VALUES ( "'.$id.'" ,"'.$user->id.'","'.$ptype.'" ,"Start" )'
	                ;
	        $db->setQuery($query);
	        $db->query();
	            
	        
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
	        
        $post_variables = array();
        $post_variables['merchant_id'] = $this->params['merchant_id'];
        $post_variables['pay_to_email'] = $this->params['pay_to_email'];
        
        $post_variables['cancel_url'] = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=error&id='.$item_id.$item_cid.'&Itemid='.$Itemid);
        $post_variables['cancel_url_target'] = '3';
        
        $post_variables['return_url'] = JRoute::_(JURI::root().'index.php?option=com_djclassifieds&task=paymentReturn&r=ok&id='.$item_id.$item_cid.'&Itemid='.$Itemid);
        $post_variables['return_url_target'] = '3';
        
        $post_variables['status_url'] = JURI::root().'index.php?option=com_djclassifieds&task=processPayment&ptype='.$this->params["plugin_name"].'&pactiontype=notify&id='.$item_id;
        
        $post_variables['transaction_id'] = $item_id;
        
        $post_variables['language'] = (in_array($lang_tag, $this->params['languages'])) ? $lang_tag : 'EN';
        
        $post_variables['currency'] = $this->params['currency_code'];
        $post_variables['detail1_description'] = JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_AD_PUBLICATION');
        $post_variables['detail1_text'] = $itemname;
        $post_variables['amount'] = $amount;
        
        //$post_url = $this->params['testmode'] == '1' ? $this->params['skrill_test_url'] : $this->params['skrill_url'];
        $post_url = $this->params['skrill_url'];
        
        $html = '<html><head><title>'.JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_REDIRECTION').'</title></head><body><div style="margin: auto; text-align: center;">';
        $html .= '<p>'.JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_REDIRECTION').'</p>';
        $html .= '<form action="' . $post_url . '" method="post" name="mb_form" >';
        $html.= '<noscript><input type="submit"  value="' . JText::_('PLG_DJCLASSIFIEDSPAYMENT_DJCFSKRILL_REDIRECT_BUTTON') . '" /></noscript>';
        foreach ($post_variables as $name => $value) {
            $html.= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
        }
        $html.= '</form></div>';
        $html.= ' <script type="text/javascript">';
        $html.= ' document.mb_form.submit();';
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
        if ($this->params['merchant_id'] != '' && $this->params['pay_to_email'] != '' && $this->params['currency_code'] != '') {
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
        
        $amount = $this->_formatPrice($amount);
        
        return $amount;
    }
    
    function _checkHash($post, $secretWord) {
        $StringToHash   = $post['merchant_id'] 
                        . $post['transaction_id'] 
                        . strtoupper(md5($secretWord)) 
                        . $post['mb_amount'] 
                        . $post['mb_currency'] 
                        . $post['status']; 
        
        $hash = strtoupper(md5($StringToHash));     
        
        if (strcmp($hash, $post['md5sig']) != 0) {
            return false;
        } else {
            return true;
        }
        return false;
    }
    
    /*
     * The total amount should skip the trailing zeroes in case the amount is a natural number 
     */
    function _formatPrice($amount) {
        $amount = number_format($amount, 2, '.', '');
        
        $amount_parts = explode('.',$amount,2);
        
        if ((int)$amount_parts[1] > 0) {
            $amount_parts[1] = rtrim($amount_parts[1], '0');
            $amount = $amount_parts[0].'.'.$amount_parts[1];
        } else {
            $amount = $amount_parts[0];
        }
        
        return $amount;
    }
    
    function _log($order_id, $message) {
        if ($this->params["debug"] == '1') {
            JLog::add('DJCF Skrill - Order ID: '.$order_id.' - '.$message, JLog::INFO, 'djcfSkrill');    
        }
    }
    
    function _getStatusName($status) {
        $status_name = null;    
        switch($status) {
            case '-2' : $status_name = 'failed'; break;
            case '-1' : $status_name = 'cancelled'; break;
            case '0'  : $status_name = 'pending'; break;
            case '2'  : $status_name = 'processed'; break;
            default   : $status_name = 'unknown'; break;
        }
        
        return $status_name;
    }
}
?>