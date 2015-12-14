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
if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
$lang = JFactory::getLanguage();
$lang->load('plg_djclassifiedspayment_djcfBankTransfer',JPATH_ADMINISTRATOR);
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djnotify.php');


class plgdjclassifiedspaymentdjcfBankTransfer extends JPlugin
{
	function plgdjclassifiedspaymentdjcfBankTransfer( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_djcfBankTransfer');
		$params["plugin_name"] = "djcfBankTransfer";
		$params["icon"] = "banktransfer_icon.jpg";
		$params["logo"] = "banktransfer_overview.jpg";
		$params["description"] = JText::_("PLG_DJCFBANKTRANSFER_PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PLG_DJCFBANKTRANSFER_PAYMENT_METHOD_NAME");		
		$params["pay_info"] = $this->params->get("pay_info");
		$this->params = $params;

	}
	
	function onPaymentMethodList($val)
	{
		$type='';
		if($val['type']){
			$type='&type='.$val['type'];	
		}	
		$html ='';
		$user = JFactory::getUser();
		
		if($this->params["pay_info"]!=''){
			
		$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
			$form_action = JRoute :: _("index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"].$type, false);
			$html ='<table cellpadding="5" cellspacing="0" width="100%" border="0">
				<tr>';
					if($this->params["logo"] != ""){
				$html .='<td class="td1" width="160" align="center">
						<img src="'.$paymentLogoPath.'" title="'.$this->params["payment_method"].'"/>
					</td>';
					 }
					$html .='<td class="td2">
						<h2>'. $this->params["payment_method"].'</h2>
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


	
	function process($id)
	{
		$db 	= JFactory::getDBO();
		$app 	= JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid",'0');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$user 	= JFactory::getUser();
		$config = JFactory::getConfig();
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
				$query ="SELECT i.*, c.price as c_price, c.alias as c_alias FROM #__djcf_items i "
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
				$item_slug = $item->id.':'.$item->alias;
				$cat_slug = $item->cat_id.':'.$item->c_alias;
			}
		/*
		$query ="SELECT i.*, c.price as c_price, c.alias as c_alias FROM #__djcf_items i "
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
			*/								
			
			if($user->id){				
				/*$mailto = $user->email;			
				$mailfrom = $app->getCfg( 'mailfrom' );			    
				$fromname=$config->get('config.sitename').' - '.str_ireplace('administrator/', '', JURI::base());
	
				$subject = JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_INFRORMATIONS').' '.$config->get('config.sitename');
				$m_message = JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_INFRORMATIONS').' '.$config->get('config.sitename')."<br /><br />";
				
				if($type=='points'){
					$m_message .= JText::_('PLG_DJCFBANKTRANSFER_POINTS_PACKAGE').': '.$itemname."<br /><br />";
				}else{
					$m_message .= JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_AD_NAME').': '.$itemname."<br /><br />";	
				}
				
				$m_message .= JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_PRICE_TO_PAY').': '.$amount.' '.$par->get('unit_price','')."<br /><br />";
				$m_message .= JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_PAY_INFORMATION').': <br /><br />'.JHTML::_('content.prepare',nl2br($this->params["pay_info"]))."<br /><br />";
				
				if($type==''){
					$u = JURI::getInstance( JURI::base() );
					$link=  $u->getHost().JRoute::_(DJClassifiedsSEO::getItemRoute($item_slug,$cat_slug));						
					$m_message .=JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_AD_LINK').': <a href="'.$link.'">'.$link.'</a><br /><br />';
					$m_message .=JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_AD_ID').': '.$id.'<br /><br />';				
				}
				$m_message .=JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_ID').': '.$item_id;
				
				$mailer = JFactory::getMailer();
				$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $m_message,$mode=1);*/
				
				$payment_info = array();
				$payment_info['id'] = $item_id;
				$payment_info['itemname'] = $itemname;
				$payment_info['amount'] = DJClassifiedsTheme::priceFormat($amount,$par->get('unit_price',''));
				$payment_info['info'] = $this->params["pay_info"];
				
				DJClassifiedsNotify::notifyUserPayment($type,$id,$payment_info);
			
			}
				
	
			echo '<div id="dj-classifieds" class="clearfix">';
				echo '<table width="98%" cellspacing="0" cellpadding="0" border="0" class="paymentdetails first">';
				echo '<tr><td class="td_title"><h2>'.$this->params["payment_method"].'</h2></td></tr>';
					echo '<tr><td class="td_pdetails">';
						echo '<div class="pd_row">';
							if($type=='points'){
								echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_POINTS_PACKAGE').':</span>';
							}else{
								echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_AD_NAME').':</span>';
							}
							echo '<span class="djcfpay_value">'.$itemname.'</span>';
						echo '</div>';
						echo '<div class="pd_row">';
							echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_PRICE_TO_PAY').':</span>';
							echo '<span class="djcfpay_value">'.$amount.' '.$par->get('unit_price','').'</span>';
						echo '</div>';
						echo '<div class="pd_row">';
							if($type=='points'){
								echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_POINTS_ID').':</span>';
							}else{								
								echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_AD_ID').':</span>';
							}
							
							echo '<span class="djcfpay_value">'.$id.'</span>';
						echo '</div>';						
						echo '<div class="pd_row">';
							echo '<span class="djcfpay_label">'.JText::_('PLG_DJCFBANKTRANSFER_PAYMENT_PAY_INFORMATION').': </span><br /><br />';
							echo '<span class="djcfpay_value">'.JHTML::_('content.prepare',nl2br($this->params["pay_info"])).'</span>';							
						echo '</div>';	
					echo '</td></tr>';							
				echo '</table>';
			echo '</div>';
		
	}
}

?>