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


class DJClassifiedsControllerItem extends JControllerLegacy {
		
	public function display($cachable = false, $urlparams = Array()){
		$app	= JFactory::getApplication();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		
	/*	$menus	= JSite::getMenu();	
		$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
		$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
				
		$itemid = ''; 
		if($menu_item){
			$itemid='&Itemid='.$menu_item->id;
		}else if($menu_item_blog){
			$itemid='&Itemid='.$menu_item_blog->id;
		}
		*/		
		JRequest::setVar('view','item');
		$user 	= JFactory::getUser();		
		$id 	= JRequest::getVar('id', 0, '', 'int');		
		$db		= JFactory::getDBO();
			$query = "SELECT date_exp, user_id, published FROM #__djcf_items WHERE id=".$id;
			$db->setQuery($query);
		$item = $db->loadObject();
		
		if($item->published==0 || ($item->published==2 && $par->get('show_archived','1')==0)){			
			$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');				
			$message = JText::_("COM_DJCLASSIFIEDS_ITEM_NOT_AVAILABLE");
			$redirect = JRoute::_($redirect);
			$app->redirect($redirect,$message);	
		}
			
		$date_now = JFactory::getDate();
		if($date_now<$item->date_exp || $item->user_id==$user->id){		
			$query = "UPDATE `#__djcf_items` SET display=display+1 WHERE id=".$id;
			$db->setQuery($query);
			$db->query();

			if($item->published==2){
				$app->enqueueMessage(JText::_('COM_DJCLASSIFIEDS_ARCHIVE_ADVERT'));
			} 			
			
			parent::display();
		}else{
			$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');				
			$message = JText::_("COM_DJCLASSIFIEDS_ITEM_NOT_AVAILABLE");
			$redirect = JRoute::_($redirect);
			$app->redirect($redirect,$message);	
		}	
		
        
    }


function ask(){
	$app	= JFactory::getApplication();	
	$id		= JRequest::getVar('item_id', 0, '', 'int');
	$cid	= JRequest::getVar('cid', 0, '', 'int');
	$db 	= JFactory::getDBO();
	$user 	= JFactory::getUser();
	$itemid	= JRequest::getVar('Itemid');
	$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
	$session = JFactory::getSession();
	$send_email=0;
	$msg 	= strip_tags(JRequest::getVar('ask_message',''));
	
		$query = "SELECT i.id, i.name, i.alias,i.cat_id,c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."WHERE i.id = ".$id;			
		$db->setQuery($query);
		$item = $db->loadObject();
		if(!$item->alias){
			$item->alias = DJClassifiedsSEO::getAliasName($item->name);
		}
		if(!$item->c_alias){
			$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
		}
		
		$link = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
		$link = JRoute::_($link);
			
	if($par->get('ask_seller_type','0')==0 || $user->id>0){			
			$date_time = JFactory::getDate();
			$date_now  = $date_time->toSQL();
			$date_exp  = mktime();
			
			//echo $par->get('ask_limit_one',5)*60;die();
			$date_last5 = date('Y-m-d H:i:s',mktime(date("H"), date("i")-$par->get('ask_limit_one',5), date("s"), date("m"), date("d"),date("Y")));
			$date_lasth = date('Y-m-d H:i:s',mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"),date("Y")));
			//echo date('H:i:s m-d-Y',$date_lasth);	
		
			$query = "SELECT COUNT(id) FROM #__djcf_itemsask a "
					."WHERE a.user_id = ".$user->id." AND a.item_id=".$id." AND a.date>'".$date_last5."'";
						
			$db->setQuery($query);
			$check = $db->loadResult();
			if($check>0){
	    	 	$msg = JText::_('COM_DJCLASSIFIEDS_ASK_MESSAGE_LIMIT');
				$app->redirect($link,$msg);	
			}
		
			$query = "SELECT COUNT(id) FROM #__djcf_itemsask a "
					."WHERE a.user_id = ".$user->id." AND a.date>'".$date_lasth."'";	
			$db->setQuery($query);
			$check = $db->loadResult();
	
			if($check>$par->get('ask_limit_hour',15)){
		     	//$link = 'index.php?option=com_djclassifieds&view=item&id='.$id.'&Itemid='.$itemid;
	    	 	$msg = JText::_('COM_DJCLASSIFIEDS_ASK_MESSAGE_LIMIT_HOUR');
				$app->redirect($link,$msg);		
			}
			
			$query = "SELECT COUNT(id) FROM #__djcf_itemsask a "
					."WHERE a.user_id = ".$user->id." AND a.date>'".$date_lasth."'";
			$db->setQuery($query);
			$check = $db->loadResult();

			$query ="SELECT f.* FROM #__djcf_fields f "
					."WHERE f.published=1 AND f.source=3 ORDER BY f.name";
			$db->setQuery($query);
			$fields_list =$db->loadObjectList();
			
			$custom_fields_msg='';
			foreach($fields_list as $fl){
				if(JRequest::getVar($fl->name,'')){
					$custom_fields_msg .= $fl->label.": ".JRequest::getVar($fl->name,'')."<br />"; 
				}
			}
			
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$query="INSERT INTO #__djcf_itemsask (`item_id`, `user_id`, `ip_address`, `message`,`custom_fields`)"
			  	." VALUES ( '".$id."', '".$user->id."','".$user_ip."', '".$db->escape($msg)."','".$db->escape($custom_fields_msg)."')"; 
			$db->setQuery($query);
			$db->query();
			$send_email=1;
							
	}else if($par->get('ask_seller_type','0')==0 && $user->id==0){
		$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');	 
	}else{		
		if($par->get('captcha_type','recaptcha')=='nocaptcha'){
			require_once(JPATH_COMPONENT.DS.'assets'.DS.'nocaptchalib.php');
		}else{
			require_once(JPATH_COMPONENT.DS.'assets'.DS.'recaptchalib.php');
		}
				
		$privatekey = $par->get('captcha_privatekey',"6LfzhgkAAAAAAOJNzAjPz3vXlX-Bw0l-sqDgipgs");
		$is_valid = false;
		
			if($par->get('captcha_type','recaptcha')=='nocaptcha'){
				$response = null;
				$reCaptcha = new ReCaptcha($privatekey);
				if ($_POST["g-recaptcha-response"]) {
					$response = $reCaptcha->verifyResponse(
							$_SERVER["REMOTE_ADDR"],
							$_POST["g-recaptcha-response"]
					);
					if ($response != null && $response->success) {
						$is_valid = true;
					}
				}
			}else{		
			  $resp = recaptcha_check_answer ($privatekey,
	                                  $_SERVER["REMOTE_ADDR"],
	                                  $_POST["recaptcha_challenge_field"],
	                                  $_POST["recaptcha_response_field"]);
			  $is_valid = $resp->is_valid;
			}
			
			if ($is_valid) {
				$user_ip = $_SERVER['REMOTE_ADDR'];
			  
				$date_time = JFactory::getDate();
				$date_now  = $date_time->toSQL();
				$date_exp  = mktime();
				
				$date_last5 = date('Y-m-d H:i:s',mktime(date("H"), date("i")-$par->get('ask_limit_one',5), date("s"), date("m"), date("d"),date("Y")));
				$date_lasth = date('Y-m-d H:i:s',mktime(date("H")-1, date("i"), date("s"), date("m"), date("d"),date("Y")));	
			
				$query = "SELECT COUNT(id) FROM #__djcf_itemsask a "
						."WHERE a.ip_address = '".$user_ip."' AND a.item_id=".$id." AND a.date>'".$date_last5."'";
							
				$db->setQuery($query);
				$check = $db->loadResult();
				if($check>0){
			     	//$link = 'index.php?option=com_djclassifieds&view=item&id='.$id.'&Itemid='.$itemid;
		    	 	$msg = JText::_('COM_DJCLASSIFIEDS_ASK_MESSAGE_LIMIT');
					$app->redirect($link,$msg);	
				}
			
				$query = "SELECT COUNT(id) FROM #__djcf_itemsask a "
						."WHERE a.ip_address = '".$user_ip."' AND a.date>'".$date_lasth."'";	
				$db->setQuery($query);
				$check = $db->loadResult();
		
				if($check>$par->get('ask_limit_hour',15)){
			     	//$link = 'index.php?option=com_djclassifieds&view=item&id='.$id.'&Itemid='.$itemid;
		    	 	$msg = JText::_('COM_DJCLASSIFIEDS_ASK_MESSAGE_LIMIT_HOUR');
					$app->redirect($link,$msg);		
				}
			
				$user_ip = $_SERVER['REMOTE_ADDR'];
				
				$query ="SELECT f.* FROM #__djcf_fields f "
						."WHERE f.published=1 AND f.source=3 ORDER BY f.name";
				$db->setQuery($query);
				$fields_list =$db->loadObjectList();
					
				$custom_fields_msg='';
				foreach($fields_list as $fl){
					if(JRequest::getVar($fl->name,'')){
						$custom_fields_msg .= $fl->label.": ".JRequest::getVar($fl->name,'')."<br />";
					}
				}					
				
				$query="INSERT INTO #__djcf_itemsask (`item_id`, `user_id`, `ip_address`, `message`,`custom_fields`)"
				  	." VALUES ( '".$id."', '0','".$user_ip."', '".$db->escape($msg)."','".$db->escape($custom_fields_msg)."')";
				$db->setQuery($query);
				$db->query(); 
				$send_email=1;
	
		  }else {
		  	$session->set('askform_name',$_POST['ask_name']);
			$session->set('askform_email',$_POST['ask_email']);
			$session->set('askform_message',$_POST['ask_message']);								
			$message = JText::_("COM_DJCLASSIFIEDS_INVALID_CODE");	
			//$link = 'index.php?option=com_djclassifieds&view=item&cid='.$cid.'&id='.$id.'&ae=1&Itemid='.$itemid.'#ask_form';
			$link = $link.'?ae=1#ask_form';
		  	$app->redirect($link,$message,'error');			
		  }

	}
	
	if($send_email){
			$query = 'SELECT i.*,c.name as c_name, c.alias as c_alias, u.email as u_email FROM #__djcf_items i '
					.'LEFT JOIN #__djcf_categories c ON c.id=i.cat_id '
					.'LEFT JOIN #__users u ON u.id=i.user_id '
					.'WHERE i.id='.$id.'';
			$db->setQuery($query);
			$item = $db->loadObject();
			
			$author= array();
			$author['name'] = JRequest::getString('ask_name','');
			$author['email'] = JRequest::getString('ask_email','');
			
			$replyto 	= JRequest::getString('ask_email','');
			$replytoname=JRequest::getString('ask_name','');
			
			DJClassifiedsNotify::messageAskFormContact($item,$author,$msg,$_FILES,$replyto,$replytoname,$custom_fields_msg);
		
			/*$query = 'SELECT i.name, i.user_id, u.email, i.email as i_email FROM #__djcf_items i '
					.'LEFT JOIN #__users u  ON u.id=i.user_id '
					.'WHERE i.id='.$id.'';
			$db->setQuery($query);
			$ob = $db->loadObject();
			
			if($ob->email){
				$mailto 	= $ob->email;
			}else{
				$mailto 	= $ob->i_email;
			}
			
			$mailfrom 	= $app->getCfg( 'mailfrom' );
			$replyto 	= JRequest::getString('ask_email','');
			$replytoname=JRequest::getString('ask_name','');
			
			$config = JFactory::getConfig();
	    
			$fromname=$config->get('config.sitename');
			$subject = sprintf ( JText::_( 'COM_DJCLASSIFIEDS_ASK_SELLER_TITLE' ), $ob->name);
		
			$u = JURI::getInstance( JURI::base() );
			$message = JText::_('COM_DJCLASSIFIEDS_FROM_USER').': '.JRequest::getString('ask_name','')."\n";
			$message .= JText::_('COM_DJCLASSIFIEDS_USER_EMAIL').': '.JRequest::getString('ask_email','')."\n\n";		
			$message .= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_ASK_SELLER_MESSAGE' ), $ob->name, $msg)."\n";
			$message .= $u->getHost().JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$cat->alias))."\n\n";
			
			//JUtility::sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=0, $cc=null, $bcc=null, $attachment=null,$replyto,$replytoname);
			$mail = JFactory::getMailer();
			$mail->addRecipient($mailto);
			$mail->addReplyTo(array($replyto, $replytoname));
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($subject);
			$mail->setBody($message);
			$sent = $mail->Send();
			*/
	
		     //$link = 'index.php?option=com_djclassifieds&view=item&cid='.$cid.'&id='.$id.'&Itemid='.$itemid;
	    	 $msg = JText::_('COM_DJCLASSIFIEDS_MESSAGE_SEND');
	}
	$link = JRoute::_($link);
    $app->redirect($link, $msg);				
}

function abuse(){	
	$app	= JFactory::getApplication();
	$id		= JRequest::getVar('item_id', 0, '', 'int');
	$cid	= JRequest::getVar('cid', 0, '', 'int');
	$db 	= JFactory::getDBO();
	$user 	= JFactory::getUser();
	$itemid	= JRequest::getVar('Itemid');
	$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
	$msg 	= strip_tags(JRequest::getVar('abuse_message',''));
	$config = JFactory::getConfig();
	$mailer = JFactory::getMailer();
	
	$query = "SELECT i.id, i.name, i.alias,i.cat_id,c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
			."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
			."WHERE i.id = ".$id;			
		$db->setQuery($query);
		$item = $db->loadObject();
		if(!$item->alias){
			$item->alias = DJClassifiedsSEO::getAliasName($item->name);
		}
		if(!$item->c_alias){
			$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
		}
	
	//$link = 'index.php?option=com_djclassifieds&view=item&cid='.$cid.'&id='.$id.'&Itemid='.$itemid;	 
	//$link = JRoute::_($link);
	$link = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
	$link = JRoute::_($link);
	
	if($user->id>0){
		$query = 'SELECT COUNT(ia.id) FROM #__djcf_items_abuse ia WHERE ia.item_id='.$id.' AND ia.user_id='.$user->id.' ';
		$db->setQuery($query);
		$a_count = $db->loadResult();
		
		if($a_count>0){
			$msg = JText::_('COM_DJCLASSIFIEDS_ALREADY_SEND_ABUSE');
			$app->redirect($link, $msg);
		}		
		
		$query = "INSERT INTO #__djcf_items_abuse(`item_id`,`user_id`,`message`) "
				."VALUES ('".$id."','".$user->id."','".addslashes($msg)."') ";
		$db->setQuery($query);
		$db->query();
			
			if($par->get('notify_user_email','')){
				$mailto = $par->get('notify_user_email');
			}else{
				$mailto = $app->getCfg( 'mailfrom' );
			}								
			
			DJClassifiedsNotify::messageAbuseFormContact($item,$user,$msg,$mailto);
			
			/*			
			if($par->get('notify_user_email','')){
				$mailto = $par->get('notify_user_email');	
			}else{
				$mailto = $app->getCfg( 'mailfrom' );
			}						
			$mailfrom = $app->getCfg( 'mailfrom' );
			
			$fromname=$config->get('config.sitename');
			$subject = JText::_('COM_DJCLASSIFIEDS_ABUSEEMAIL_TITLE').' '.$config->get('config.sitename');			
			$m_message = JText::_('COM_DJCLASSIFIEDS_USER').': '.$user->name.' ('.$user->id.")\n\n";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_ABUSEEMAIL_ABUSE_REASON').":\n".$msg."\n\n";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_AD').":\n".JURI::base().$link."\n\n";
									
			$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $m_message);*/
		$msg = JText::_('COM_DJCLASSIFIEDS_MESSAGE_SEND');
	}else{
		$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
	}
	
		
	$app->redirect($link, $msg);
}

function delete(){
	JPluginHelper::importPlugin('djclassifieds');
	$app  	= JFactory::getApplication();
	$user 	= JFactory::getUser();
	$db   	= JFactory::getDBO();
	$id 	= JRequest::getVar('id', 0, '', 'int' );
	$it 	= JRequest::getVar('Itemid', 0, '', 'int' );
	$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
	$dispatcher = JDispatcher::getInstance();
	
		$query = "SELECT * FROM #__djcf_items WHERE id='".$id."' LIMIT 1";
		$db->setQuery($query);
		$item =$db->loadObject();	
		
		if($user->id!=$item->user_id){
			$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
			$redirect = JRoute::_($redirect,false);
			$app->redirect($redirect, $message,'error');			
		}
	
		
		if($par->get('user_ad_delete',0)==1){
			$query = "UPDATE #__djcf_items SET published=2 WHERE id='".$id."' LIMIT 1";
			$db->setQuery($query);
			$db->query();
			$message = JText::_('COM_DJCLASSIFIEDS_ADVERT_MOVED_TO_ARCHIVE');
			
		}else{
			$query = "SELECT * FROM #__djcf_images WHERE item_id=".$item->id." AND type='item' ";
			$db->setQuery($query);
			$item_images =$db->loadObjectList('id');
			
			
			if($item_images){	
				foreach($item_images as $item_img){			
					$path_to_delete = JPATH_ROOT.$item_img->path.$item_img->name;
	    			if (JFile::exists($path_to_delete.'.'.$item_img->ext)){
	    				JFile::delete($path_to_delete.'.'.$item_img->ext);
	    			}
	    			if (JFile::exists($path_to_delete.'_ths.'.$item_img->ext)){
	    				JFile::delete($path_to_delete.'_ths.'.$item_img->ext);
	    			}
	    			if (JFile::exists($path_to_delete.'_thm.'.$item_img->ext)){
	    				JFile::delete($path_to_delete.'_thm.'.$item_img->ext);
	    			}
	    			if (JFile::exists($path_to_delete.'_thb.'.$item_img->ext)){
	    				JFile::delete($path_to_delete.'_thb.'.$item_img->ext);
	    			}
				}
			}
	 			 			
		        $query = "DELETE FROM #__djcf_items WHERE id = ".$item->id;
		        $db->setQuery($query);
		        $db->query();
				
				$query = "DELETE FROM #__djcf_fields_values WHERE item_id = ".$item->id;
		        $db->setQuery($query);
		        $db->query();
				
				$query = "DELETE FROM #__djcf_payments WHERE item_id = ".$item->id;
		        $db->setQuery($query);
		        $db->query();
		        
		        $query = "DELETE FROM #__djcf_images WHERE item_id=".$item->id." AND type='item' ";
		        $db->setQuery($query);
		        $db->query();
				
		        $dispatcher->trigger('onAfterDJClassifiedsDeleteAdvert', array($item));
		        
		        $message = JText::_('COM_DJCLASSIFIEDS_AD_DELETED');
		}
		
		$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
		$redirect = JRoute::_($redirect,false);
		$app->redirect($redirect, $message);	
	
	}

	function deleteToken(){
		$app  	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		$db   	= JFactory::getDBO();
		$token 	= JRequest::getCmd('token', '' );
	
			$query = "SELECT i.* FROM #__djcf_items i "
					."WHERE i.user_id=0 AND i.token=".$db->Quote($db->escape($token));
			$db->setQuery($query);
			$item=$db->loadObject();
			
			if(!$item){
				$message = JText::_("COM_DJCLASSIFIEDS_WRONG_AD");				
				$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');
				$redirect = JRoute::_($redirect);				
				$app->redirect($redirect,$message,'error');
			}	
	
		$query = "SELECT * FROM #__djcf_images WHERE item_id=".$item->id." AND type='item' ";
		$db->setQuery($query);
		$item_images =$db->loadObjectList('id');
	
	
		if($item_images){
			foreach($item_images as $item_img){
				$path_to_delete = JPATH_ROOT.$item_img->path.$item_img->name;
				if (JFile::exists($path_to_delete.'.'.$item_img->ext)){
					JFile::delete($path_to_delete.'.'.$item_img->ext);
				}
				if (JFile::exists($path_to_delete.'_ths.'.$item_img->ext)){
					JFile::delete($path_to_delete.'_ths.'.$item_img->ext);
				}
				if (JFile::exists($path_to_delete.'_thm.'.$item_img->ext)){
					JFile::delete($path_to_delete.'_thm.'.$item_img->ext);
				}
				if (JFile::exists($path_to_delete.'_thb.'.$item_img->ext)){
					JFile::delete($path_to_delete.'_thb.'.$item_img->ext);
				}
			}
		}
	
		$query = "DELETE FROM #__djcf_items WHERE id = ".$item->id;
		$db->setQuery($query);
		$db->query();
			
		$query = "DELETE FROM #__djcf_fields_values WHERE item_id = ".$item->id;
		$db->setQuery($query);
		$db->query();
			
		$query = "DELETE FROM #__djcf_payments WHERE item_id = ".$item->id;
		$db->setQuery($query);
		$db->query();
		 
		$query = "DELETE FROM #__djcf_images WHERE item_id=".$item->id." AND type='item' ";
		$db->setQuery($query);
		$db->query();
			
		$message = JText::_('COM_DJCLASSIFIEDS_AD_DELETED');
		$redirect=DJClassifiedsSEO::getCategoryRoute('0:all');
		$redirect = JRoute::_($redirect,false);
		$app->redirect($redirect, $message);
	
	}	
	
	function renew(){
		$app = JFactory::getApplication();
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');		
		jimport( 'joomla.database.table' );
		
		$row 	= JTable::getInstance('Items', 'DJClassifiedsTable');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );		
		$user 	= JFactory::getUser();
		$db 	= JFactory::getDBO();
		$id 	= JRequest::getVar('id', 0, '', 'int' );
		$it 	= JRequest::getVar('Itemid', 0, '', 'int' );
		$order 	= JRequest::getCmd('order', $par->get('items_ordering','date_e'));		
		$ord_t 	= JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
		
		
			$query = "SELECT i.*, c.price as c_price FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."WHERE i.id='".$id."' LIMIT 1";
			$db->setQuery($query);
			$item =$db->loadObject();
			
			if($user->id!=$item->user_id){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message,'error');			
			}
			
			$row->load($item->id);			
			
			$renew_date = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")+$par->get('renew_days','3'), date("Y")));
			
			if($renew_date<=$row->date_exp){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message,'error');	
			}
			
			$days_left = strtotime($row->date_exp)-mktime();
			if($days_left<0){$days_left=0;}			
			/*$days_to_add=0;
			if($days_left>86400){
				$days_to_add = round($days_left/86400);
			}*/						
			
			$row->date_exp = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s")+$days_left, date("m")  , date("d")+$row->exp_days, date("Y")));			
			$row->date_sort=date("Y-m-d H:i:s");
						
			$duration_price = 0;
			if($par->get('durations_list','')){
				$query = "SELECT price_renew FROM #__djcf_days WHERE days = ".$row->exp_days;	
				$db->setQuery($query);
				$duration_price = $db->loadResult(); 
			}
			
			if($item->c_price>0 || $row->promotions || $duration_price>0 ){
				$row->pay_type = '';
				if($item->c_price>0){
					$row->pay_type = 'cat,';
				}
				if($duration_price>0){
					$row->pay_type .= 'duration_renew,';
				}
				if($row->promotions){
					$query = "SELECT p.* FROM #__djcf_promotions p WHERE p.published=1 ORDER BY p.id ";	
					$db->setQuery($query);
					$promotions=$db->loadObjectList();
					$prom_to_pay = explode(',', $row->promotions);
					
					for($pp=0;$pp<count($prom_to_pay);$pp++){
						foreach($promotions as $prom){
							if($prom->name==$prom_to_pay[$pp]){
								if($prom->price>0){
									$row->pay_type .= $prom->name.',';				
								}
							}
						}	
					}							
				}
					
				if($row->pay_type){
					$row->published = 0;
					$row->payed = 0;
					$pay_redirect=1;	
				}
				
				$query = "DELETE FROM #__djcf_payments WHERE item_id = ".$item->id;
	        	$db->setQuery($query);
	        	$db->query();
			}
			
			
			$row->store();
		
				if($pay_redirect==1){
					$redirect= 'index.php?option=com_djclassifieds&view=payment&id='.$row->id.'&Itemid='.$it;
					$message=JTExt::_('COM_DJCLASSIFIEDS_AD_RENEWED_SUCCESSFULLY_CHOOSE_PAYMENT');
				}else{
					$redirect= 'index.php?option=com_djclassifieds&view=useritems&Itemid='.$it.'&order='.$order.'&ord_t='.$ord_t;
					$message = JText::_('COM_DJCLASSIFIEDS_AD_RENEWED_SUCCESSFULLY'); 
				}

				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message);

	}

	function archive(){
		$app  	= JFactory::getApplication();
		$user 	= JFactory::getUser();
		$db   	= JFactory::getDBO();
		$id 	= JRequest::getVar('id', 0, '', 'int' );
		$it 	= JRequest::getVar('Itemid', 0, '', 'int' );
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
	
		$query = "SELECT * FROM #__djcf_items WHERE id='".$id."' LIMIT 1";
		$db->setQuery($query);
		$item =$db->loadObject();
	
			if($user->id!=$item->user_id){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
				$redirect = JRoute::_($redirect,false);
				$app->redirect($redirect, $message,'error');
			}
	
			$query = "UPDATE #__djcf_items SET published=2 WHERE id='".$id."' LIMIT 1";
			$db->setQuery($query);
			$db->query();
			$message = JText::_('COM_DJCLASSIFIEDS_ADVERT_MOVED_TO_ARCHIVE');
				
		
		$redirect="index.php?option=com_djclassifieds&view=useritems&Itemid=".$it;
		$redirect = JRoute::_($redirect,false);
		$app->redirect($redirect, $message);
	
	}	
	
	
	function getSearchFields(){
		 header("Content-type: text/html; charset=utf-8");
		 $session = JFactory::getSession();	     		 
	     $db 	  = JFactory::getDBO();
	     $cid 	  = JRequest::getVar('cat_id', '0', '', 'int');
	     $par 	  = JComponentHelper::getParams( 'com_djclassifieds' );

	     $query ="SELECT f.*, fx.ordering FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 AND f.access=0 AND f.search_type!='' AND f.in_search=1 AND f.source<2 "
		 		."ORDER BY fx.ordering";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		 //echo '<pre>'; print_r($db);print_r($fields_list);die(); 
		 

		 if(count($fields_list)==0){
		 	die('');
		 }else{
		 		//echo '<pre>';	print_r($fields_list);echo '</pre>';						 	
		 	foreach($fields_list as $fl){
		 			$fl_class='djseform_field djse_type_'.$fl->type.' djse_field_'.$fl->id;
		 			if($fl->search_type=='checkbox_accordion_o'){
		 				$fl_class .= ' djfields_accordion_o';
		 			}else if($fl->search_type=='checkbox_accordion_c'){
		 				$fl_class .= ' djfields_accordion_c';
		 			}
		 			echo '<div class="'.$fl_class.'">';
					echo '<span style="font-weight:bold;" class="label">'.$fl->label.'</span>';
					if($fl->type=='date'){
						if($fl->search_type=='inputbox'){	
							if($session->get('se_'.$fl->id,'')!=''){
								$value = $session->get('se_'.$fl->id,'');
							}else{
								$value = '';
							}
							echo '<input class="inputbox djsecal" type="text" size="10" maxlenght="19" value="'.$value.'" id="se_'.$fl->id.'" name="se_'.$fl->id.'" />';
							echo ' <img class="calendar" src="'.JURI::base().'/components/com_djclassifieds/assets/images/calendar.png" alt="calendar" id="se_'.$fl->id.'button" />';	
						}else if($fl->search_type=='inputbox_min_max'){
								if($session->get('se_'.$fl->id.'_min','')!=''){
									$value = $session->get('se_'.$fl->id.'_min','');
								}else{
									$value = '';
								}
							echo '<span class="from_class">'.JText::_('COM_DJCLASSIFIEDS_FROM').'</span>'.' ';
							echo '<input class="inputbox djsecal" type="text" size="10" maxlenght="19" value="'.$value.'" id="se_'.$fl->id.'_min" name="se_'.$fl->id.'_min" />';
							echo ' <img class="calendar" src="'.JURI::base().'/components/com_djclassifieds/assets/images/calendar.png" alt="calendar" id="se_'.$fl->id.'_minbutton" />';
							echo '<br />';
								if($session->get('se_'.$fl->id.'_max','')!=''){
									$value = $session->get('se_'.$fl->id.'_max','');
								}else{
									$value = '';
								}
							echo '<span class="to_class">'.JText::_('COM_DJCLASSIFIEDS_TO').'</span>'.' ';
							echo '<input class="inputbox djsecal" type="text" size="10" maxlenght="19" value="'.$value.'" id="se_'.$fl->id.'_max" name="se_'.$fl->id.'_max" />';
							echo ' <img class="calendar" src="'.JURI::base().'/components/com_djclassifieds/assets/images/calendar.png" alt="calendar" id="se_'.$fl->id.'_maxbutton" />';
						}
					}else{
						if($fl->search_type=='inputbox'){	
							if($session->get('se_'.$fl->id,'')!=''){
								$value = $session->get('se_'.$fl->id,'');
							}else{
								$value = '';
							}
							echo '<input class="inputbox" type="text" size="30" value="'.$value.'" name="se_'.$fl->id.'" />';	
						}else if($fl->search_type=='select'){														
							echo '<select class="inputbox" name="se_'.$fl->id.'"  >';
								if(substr($fl->search_value1, -1)==';'){
									$fl->search_value1 = substr($fl->search_value1, 0,-1);
								}
								$val = explode(';', $fl->search_value1);
								$fl_value = $session->get('se_'.$fl->id,'');
								for($i=0;$i<count($val);$i++){
									if($fl_value==$val[$i]){
										$sel="selected";
									}else{
										$sel="";
									}
									if($val[$i]==''){
										echo '<option '.$sel.' value="'.$val[$i].'">'.JText::_('COM_DJCLASSIFIEDS_FILTER_ALL').'</option>';
									}else{
										echo '<option '.$sel.' value="'.$val[$i].'">';
											if($par->get('cf_values_to_labels','0')){
												echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($val[$i])));
											}else{
												echo $val[$i];
											}
										echo '</option>';	
									}
									
								}
								
							echo '</select>';
						}else if($fl->search_type=='radio'){
							if(substr($fl->search_value1, -1)==';'){
									$fl->search_value1 = substr($fl->search_value1, 0,-1);
								}
							$val = explode(';', $fl->search_value1);
							$fl_value = $session->get('se_'.$fl->id,'');
							echo '<div class="radiofield_box">';
								for($i=0;$i<count($val);$i++){
									$checked = '';
										if($fl_value == $val[$i]){
											$checked = 'CHECKED';
										}									 	
									
									echo '<div class="radiofield_box_v"><input type="radio" class="inputbox" '.$checked.' value ="'.$val[$i].'" name="se_'.$fl->id.'" id="se_'.$fl->id.'_'.$i.'"  />';
										echo '<label for="se_'.$fl->id.'_'.$i.'" class="radio_label">';
											if($par->get('cf_values_to_labels','0')){
												echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($val[$i])));
											}else{
												echo $val[$i];	
											}
										echo '</label>';
									echo '</div>';
								}	
								echo '<div class="clear_both"></div>';								
							echo '</div>';	
						}else if($fl->search_type=='checkbox' || $fl->search_type=='checkbox_accordion_o' || $fl->search_type=='checkbox_accordion_c'){
								if(substr($fl->search_value1, -1)==';'){
									$fl->search_value1 = substr($fl->search_value1, 0,-1);
								}							
							$val = explode(';', $fl->search_value1);
							
							echo '<div class="se_checkbox">';
								for($i=0;$i<count($val);$i++){
									$checked = '';
		
									$def_val = explode(',', $session->get('se_'.$fl->id,''));
									
										for($d=0;$d<count($def_val);$d++){
											if($def_val[$d] == $val[$i]){
												$checked = 'CHECKED';
											}											
										}
									
									echo '<div class="se_checkbox_v"><input type="checkbox" '.$checked.' value ="'.$val[$i].'" name="se_'.$fl->id.'[]" id="se_'.$fl->id.'_'.$i.'" />';
										echo '<label for="se_'.$fl->id.'_'.$i.'" class="radio_label">';
											if($par->get('cf_values_to_labels','0')){
												echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($val[$i])));
											}else{
												echo $val[$i];
											}
										echo '</label>';
									echo '</div>';
									
								}
							echo '<div class="clear_both"></div>';
							echo '</div>';	
	
						}else if($fl->search_type=='inputbox_min_max'){
								if($session->get('se_'.$fl->id.'_min','')!=''){
									$value = $session->get('se_'.$fl->id.'_min','');
								}else{
									$value = '';
								}
							echo '<span class="from_class">'.JText::_('COM_DJCLASSIFIEDS_FROM').'</span>'.' '.'<input style="width:30px;" class="inputbox" type="text" size="10" value="'.$value.'" name="se_'.$fl->id.'_min" />';
								if($session->get('se_'.$fl->id.'_max','')!=''){
									$value = $session->get('se_'.$fl->id.'_max','');
								}else{
									$value = '';
								}
							echo '<span class="to_class">'.JText::_('COM_DJCLASSIFIEDS_TO').'</span>'.' '.'<input style="width:30px;" class="inputbox" type="text" size="10" value="'.$value.'" name="se_'.$fl->id.'_max" />';
						}else if($fl->search_type=='select_min_max'){
							echo '<span class="from_class">'.JText::_('COM_DJCLASSIFIEDS_FROM').'</span>';
								if(substr($fl->search_value1, -1)==';'){
									$fl->search_value1 = substr($fl->search_value1, 0,-1);
								}
								$se_v1 = explode(';', $fl->search_value1);
									echo '<select style="width:auto;" name="se_'.$fl->id.'_min" >';
									if($session->get('se_'.$fl->id.'_min','')!=''){
										$value = $session->get('se_'.$fl->id.'_min','');
									}else{
										$value = '';
									}
										for($i=0;$i<count($se_v1);$i++){
											if($value==$se_v1[$i]){
												$sel=' selected="selected"  ';
											}else{
												$sel= '';
											}
											echo '<option '.$sel.' class="inputbox" value="'.$se_v1[$i].'">';
												if($par->get('cf_values_to_labels','0')){
													echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($se_v1[$i])));
												}else{
													echo $se_v1[$i];
												}
											echo '</option>';
										}
									echo '</select>';
							echo '<span class="to_class new">'.JText::_('COM_DJCLASSIFIEDS_TO').'</span>';
									if(substr($fl->search_value2, -1)==';'){
										$fl->search_value2 = substr($fl->search_value2, 0,-1);
									}
									$se_v2 = explode(';', $fl->search_value2);
									echo '<select style="width:auto;" name="se_'.$fl->id.'_max" >';
									if($session->get('se_'.$fl->id.'_max','')!=''){
										$value = $session->get('se_'.$fl->id.'_max','');
									}else{
										if(count($se_v2)){
											$value = end($se_v2);
										}else{
											$value = '';	
										}
										
									}
										for($i=0;$i<count($se_v2);$i++){
											
											if($value==$se_v2[$i]){
												$sel=' selected="selected" ';
											}else{
												$sel= '';
											}
											echo '<option '.$sel.' class="inputbox" value="'.$se_v2[$i].'">';
												if($par->get('cf_values_to_labels','0')){
													echo JText::_('COM_DJCLASSIFIEDS_'.str_ireplace(' ', '_', strtoupper($se_v2[$i])));
												}else{
													echo $se_v2[$i];
												}
											echo '</option>';
										}
									echo '</select>';
						}
					}
					 
				echo '</div>';
		 	}		 				
		 	die();
	 	}	
	}
	
	function addFavourite(){
		$app	= JFactory::getApplication();	
		$id		= JRequest::getInt('id', 0);
		$cid	= JRequest::getVar('cid', 0, '', 'int');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$itemid	= JRequest::getVar('Itemid');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
	
		$m_type = '';
			$query = "SELECT i.id, i.name, i.alias,i.cat_id,c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."WHERE i.id = ".$id;			
			$db->setQuery($query);
			$item = $db->loadObject();
			if(!$item->alias){
				$item->alias = DJClassifiedsSEO::getAliasName($item->name);
			}
			if(!$item->c_alias){
				$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
			}
			
			$link = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
				
		if($par->get('favourite','1')){				
			if($user->id >0){
				$db = & JFactory::getDBO();
			    $query ="SELECT COUNT(id) FROM #__djcf_favourites  "
				 	   ."WHERE item_id='".$id."' AND user_id=".$user->id;
			     $db->setQuery($query);
				 $user_fav =$db->loadResult();				 
				 if($user_fav==0){
				 	$query="INSERT INTO #__djcf_favourites (`item_id`, `user_id`)"
						  ." VALUES ( '".$id."', '".$user->id."')";
					$db->setQuery($query);
					$db->query();
				 }																		
				$msg = JText::_('COM_DJCLASSIFIEDS_AD_ADDED_TO_FAVOURITES');							
			}else{				
		     	$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
				$link = 'index.php?option=com_djclassifieds&view=item&task=addFavourite&cid='.$cid.'&id='.$id.'&Itemid='.$itemid;
				$app->redirect('index.php?option=com_users&view=login&return='.base64_encode($link),$msg);
			}
		}else{
		    $msg = JText::_('COM_DJCLASSIFIEDS_FUNCTION_NOT_AVAILABLE');
			$m_type= 'error';
		}
		//$link = 'index.php?option=com_djclassifieds&view=item&cid='.$cid.'&id='.$id.'&Itemid='.$itemid;
		$link = JRoute::_($link);
		$app->redirect($link,$msg,$m_type);	
	}

	function removeFavourite(){
		$app	= JFactory::getApplication();	
		$id		= JRequest::getInt('id', 0);
		$cid	= JRequest::getVar('cid', 0, '', 'int');
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$itemid	= JRequest::getVar('Itemid');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		
		$query = "SELECT i.id, i.name, i.alias,i.cat_id,c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."WHERE i.id = ".$id;			
			$db->setQuery($query);
			$item = $db->loadObject();			
			if(!$item->alias){
				$item->alias = DJClassifiedsSEO::getAliasName($item->name);
			}
			if(!$item->c_alias){
				$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);					
			}
			
			$link = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);

		$m_type = '';
				
		if($par->get('favourite','1')){				
			if($user->id >0){										
				$query="DELETE FROM #__djcf_favourites WHERE item_id=".$id." AND user_id=".$user->id." ";
				$db->setQuery($query);
				$db->query();
				$msg = JText::_('COM_DJCLASSIFIEDS_AD_REMOVED_FROM_FAVOURITES');							
			}else{				
		     	$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
				$m_type= 'error';
			}
		}else{
		    $msg = JText::_('COM_DJCLASSIFIEDS_FUNCTION_NOT_AVAILABLE');
			$m_type= 'error';
		}
		//$link = 'index.php?option=com_djclassifieds&view=item&cid='.$cid.'&id='.$id.'&Itemid='.$itemid;
		$link = JRoute::_($link);
		$app->redirect($link,$msg,$m_type);	
	}

	function driveDirections(){
		$app	 = JFactory::getApplication();	
		$db 	 = JFactory::getDBO();
		$id		 = JRequest::getInt('id', 0);
		$saddr 	 = JRequest::getVar('saddr');
		$address = JRequest::getVar('address');				
		
		$query = "SELECT i.* FROM #__djcf_items i "
				."WHERE i.id = ".$id." LIMIT 1";			
			$db->setQuery($query);
			$item = $db->loadObject();	
			//echo '<pre>';print_r($item);die();
			
			$loc_coord = DJClassifiedsGeocode::getLocation($address);
			if($item){
				$latitude = explode('.',$item->latitude);				 
				$latitude = $latitude[0].'.'.rtrim($latitude[1],'0');				 
				$longitude = explode('.',$item->longitude);
				$longitude = $longitude[0].'.'.rtrim($longitude[1],'0');
				$daddr = $latitude.','.$longitude;
				
				if(is_array($loc_coord)){										
					if($latitude==$loc_coord['lat'] && $longitude==$loc_coord['lng']){
						$daddr = $address;	
					}	
				} 												
			}else{
				$daddr = $address;
			}
		$app->redirect('http://maps.google.com/maps?saddr='.$saddr.'&daddr='.$daddr);	
	} 

	
	function saveBid(){
		header("Content-type: text/html; charset=utf-8");
		$app	= JFactory::getApplication();
		$id		= JRequest::getInt('id', 0);
		$bid	= JRequest::getFloat('bid',0);
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$itemid	= JRequest::getVar('Itemid');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$bid_error = 0;
		$error_show_form = 0;
		$error_price= 0;
		$bid_message = JText::_('COM_DJCLASSIFIEDS_OFFER_PUBLISHED');
		
		if($user->id>0){			
			$date_now = date("Y-m-d H:i:s");
			$query ="SELECT * FROM #__djcf_items i "
					."WHERE i.id = ".$id." AND i.published=1 AND i.date_start <= '".$date_now."' AND i.date_exp >= '".$date_now."' ";
			$db->setQuery($query);
			$item =$db->loadObject();
			if($item){				
				if($bid>0){
					$query = "SELECT a.*, u.name as u_name FROM #__djcf_auctions a, #__users u "
							." WHERE a.user_id=u.id AND a.item_id=".$id." ORDER BY a.date DESC LIMIT 1";
					$db->setQuery($query);
					$last_bid=$db->loadObject();

					if($item->buynow){						
						$min_bid = $item->price_start;
					}else{
						$min_bid = $item->price;
					}									
					
					if($last_bid){
						if($last_bid->user_id==$user->id){
							$bid_error = 1;
							$bid_message = JText::_('COM_DJCLASSIFIEDS_YOUR_OFFER_IS_LAST_YOU_CAN_BID_ONLY_OTHER_USERS');
						}
						$min_bid = $last_bid->price;					
					}
					
					if($item->user_id==$user->id){
						$bid_error = 1;
						$bid_message = JText::_('COM_DJCLASSIFIEDS_YOU_CANT_BID_YOUR_ADVERT');
					}
					
					if($bid_error==0){
						if(!$item->bid_min){$item->bid_min=1;}
						$min_bid = $min_bid + $item->bid_min;
						if($bid<$min_bid){
							$bid_error = 1;
							$error_show_form = 1;
							$error_price = $min_bid;
							$bid_message = JText::_('COM_DJCLASSIFIEDS_OFFER_SMALLER_THAN_LIMIT').' '.DJClassifiedsTheme::priceFormat($error_price,$item->currency) ;
						}
						
						if($bid_error==0){
							if($item->bid_max>0){
								$max_bid = $min_bid + $item->bid_max;
								
								if($item->bid_max && $bid>$max_bid){
									$bid_error = 1;
									$error_show_form = 1;
									$error_price = $max_bid;
									$bid_message = JText::_('COM_DJCLASSIFIEDS_OFFER_BIGGER_THAN_LIMIT').' '.DJClassifiedsTheme::priceFormat($error_price,$item->currency) ;
								}	
							}
						}
						
						
						if($bid_error==0){						
							$user_ip = $_SERVER['REMOTE_ADDR'];
							$win = 0;
							if($bid>=$item->price_reserve && $item->bid_autoclose==1){
								$win = 1;
							}
							$query="INSERT INTO #__djcf_auctions (`item_id`, `user_id`, `ip_address`, `price`,`win`)"
									." VALUES ( '".$id."', '".$user->id."','".$user_ip."', '".$bid."', '".$win."' )";
							$db->setQuery($query);
							$db->query();
							
							$price_start ='';
							if(!$last_bid){
								$price_start = " price_start='".$item->price."' ";
							}
							$win_notifi ='';
							if($win){
								if($price_start || $item->buynow){
									$win_notifi = ", ";
								}
								$win_notifi .= "notify=2 ";
							}
							
							$price_new = '';
							if($item->buynow==0){
								$price_new = " price='".$bid."' ";
								if($price_start){
									$price_start = ','.$price_start;
								}								
							}
							
							if($price_start || $win_notifi || $price_new){
								$query="UPDATE #__djcf_items SET ".$price_new.$price_start.$win_notifi
								." WHERE id=".$item->id;
								$db->setQuery($query);
								$db->query();
							}
							
								
							
							DJClassifiedsNotify::notifyAuctionsBidAuthor($id,$user,$bid);
							DJClassifiedsNotify::notifyAuctionsBidBidder($id,$user,$bid);
							if($last_bid){
								DJClassifiedsNotify::notifyAuctionsBidOutbid($id,$user,$bid,$last_bid);
							}
							
							if($win){
								DJClassifiedsNotify::notifyAuctionsWinAuthor($id,$user,$bid);
								DJClassifiedsNotify::notifyAuctionsWinBidder($id,$user,$bid);
							}														
						}
					}
															
				}else{
					$bid_error = 1;
					$bid_message = JText::_('COM_DJCLASSIFIEDS_PLEASE_ENTER_PRICE_VALUE');					
				}
				
				
				$query = "SELECT a.*, u.name as u_name FROM #__djcf_auctions a, #__users u "
						." WHERE a.user_id=u.id AND a.item_id=".$id." ORDER BY a.date DESC LIMIT ".$par->get('bids_displayed',5);
				$db->setQuery($query);
				$bids=$db->loadObjectList();
				
				?>
				<div class="auction" id="djauctions">
				<div class="auction_bids">
					<div class="bids_title"><h2><?php echo JText::_('COM_DJCLASSIFIEDS_CURRENT_BIDS'); ?></h2></div>
						<?php
						if(isset($bids[0]) && $item->price_reserve){
							if($bids[0]->price<$item->price_reserve){ ?>
								<div class="bids_subtitle"><?php echo JText::_('COM_DJCLASSIFIEDS_RESERVE_PRICE_NOT_REACHED'); ?></div>
						<?php }
						} ?>
						<div class="bids_list">
							<?php if($bids){ ?>
								<div class="bids_row bids_row_title">
									<div class="bids_col bids_col_name"><?php echo JText::_('COM_DJCLASSIFIEDS_NAME'); ?>:</div>
									<div class="bids_col bids_col_date"><?php echo JText::_('COM_DJCLASSIFIEDS_DATE'); ?>:</div>
									<div class="bids_col bids_col_bid"><?php echo JText::_('COM_DJCLASSIFIEDS_BID'); ?>:</div>
									<div class="clear_both"></div>
								</div>
								<?php foreach($bids as $bid){ 
									if($bid->price>$min_bid){$min_bid = $bid->price;}
									if ($par->get('mask_bidder_name','0')== 1) {
										$bid->u_name = substr($bid->u_name, 0, 1).'.....'.substr($bid->u_name, -1, 1);
									}
									?> 
									<div class="bids_row">
										<div class="bids_col bids_col_name"><?php echo $bid->u_name; ?></div>
										<div class="bids_col bids_col_date"><?php echo DJClassifiedsTheme::formatDate(strtotime($bid->date)); ?></div>
										<div class="bids_col bids_col_bid"><?php echo DJClassifiedsTheme::priceFormat($bid->price,$item->currency);?></div>
										<div class="clear_both"></div>
									</div>		
								<?php }?>			
							<?php }else{ ?>
								<div class="bids_row no_bids_row"><?php echo JText::_('COM_DJCLASSIFIEDS_NO_SUBMITTED_BIDS'); ?></div>	
							<?php }?>
							<div class="clear_both"></div>
						</div>
					</div>
					
					<?php if($error_show_form){?>
					
						<div class="bids_form" id="djbids_form">
							<div class="bids_box">
								<div class="bids_info">
									<span class="bid_label"><?php echo JText::_('COM_DJCLASSIFIEDS_PLACE_BID'); ?></span>					
								</div>
								<div class="bids_input">
									<?php if ($par->get('unit_price_position','0')== 1) {
							        	echo ($item->currency) ? $item->currency : $par->get('unit_price');
									} ?>     	
									<input class="inputbox" id="djbid_value" type="text" name="bid_max" id="bid_max" size="30" maxlength="250" value="<?php echo $error_price; ?>" />
									<?php if ($par->get('unit_price_position','0')== 0) {
							        	echo ($item->currency) ? $item->currency : $par->get('unit_price');
									} ?>				
								</div>
								<div class="bids_button">
									<button class="button" id="bid_submit"><?php echo JText::_('COM_DJCLASSIFIEDS_PLACE_BID');?></button>
								</div>								
								<div class="clear_both"></div>
							</div>
							<div class="clear_both"></div>
						</div>
						
					
					<?php } ?>
					
					
									
					<div id="djbid_alert">
						<?php if($bid_error){echo $bid_message;}?>
					</div>
					<div id="djbid_message">
						<?php if(!$bid_error){echo $bid_message;}?>
					</div>
				</div>
				
				
				
				
				
				
				
				
				
				
				
			<?php	
			}else{
				echo '<div class="auction" id="djauctions"><div id="djbid_alert" >';
					echo JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				echo '</div></div>';
			}
		}else{
			echo '<div class="auction" id="djauctions"><div id="djbid_alert" >';
			echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
			echo '</div></div>';
		}
		
		
		
		die();
		
	}
	
	
	
	function delBid(){

		header("Content-type: text/html; charset=utf-8");
		$app	= JFactory::getApplication();
		$id		= JRequest::getInt('id', 0);
		$cid	= JRequest::getInt('cid', 0);
		$bid	= JRequest::getFloat('bid',0);
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$itemid	= JRequest::getVar('Itemid');
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$m_type = '';
		
		
			$query = "SELECT i.*, c.name as c_name, c.alias as c_alias FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."WHERE i.id = ".$id." ";
			$db->setQuery($query);
			$item = $db->loadObject();
			
			if(!$item->alias){
				$item->alias = DJClassifiedsSEO::getAliasName($item->name);
			}
			if(!$item->c_alias){
				$item->c_alias = DJClassifiedsSEO::getAliasName($item->c_name);
			}
				
			$link = DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias);
	
		if($user->id>0){			
				if($item){
					if($item->user_id==$user->id && $item->user_id>0){
						$query = "DELETE FROM #__djcf_auctions WHERE item_id = ".$item->id." AND id=".$bid;
						$db->setQuery($query);
						$db->query();
						
						$price_start = $item->price_start;						
						$query = "SELECT a.* FROM #__djcf_auctions a "
								." WHERE a.item_id=".$item->id." ORDER BY a.date DESC LIMIT 1";
						$db->setQuery($query);
						$last_bid=$db->loadObject();
												
						if($last_bid){
							$price_start = $last_bid->price;
						}
						
						$query="UPDATE #__djcf_items SET price='".$price_start."' "
							  ." WHERE id=".$item->id;
						$db->setQuery($query);
						$db->query();												
						
						
						$msg =  JText::_('COM_DJCLASSIFIEDS_BID_DELETED');
						
					}else{
						$msg = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
						$m_type= 'error';
					}
				}else{					
					$msg = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
					$m_type= 'error';
				}
			}else{
				$msg = JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');
				$m_type= 'error';
			}
			
			$link = JRoute::_($link);
			$app->redirect($link,$msg,$m_type);
			die('aaaa');											
		}
	
		public function getRegionSelect(){
		
			header("Content-type: text/html; charset=utf-8");
			$id 	= JRequest::getInt('reg_id', '0');
			$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
			$db 	= JFactory::getDBO();
			$user 	= JFactory::getUser();
			$mod_id = JRequest::getInt('mod_id', '0');
			
			$language = JFactory::getLanguage();
			$c_lang = $language->getTag();
			if($c_lang=='pl-PL' || $c_lang=='en-GB'){
				$language->load('mod_djclassifieds_search', JPATH_SITE.'/modules/mod_djclassifieds_search', null, true);
			}else{
				if(!$language->load('mod_djclassifieds_search', JPATH_SITE, null, true)){
					$language->load('mod_djclassifieds_search', JPATH_SITE.'/modules/mod_djclassifieds_search', null, true);
				}
			}
		
			if($id>0){
				$query = "SELECT * FROM #__djcf_regions WHERE parent_id='".$id."' AND published=1 ORDER BY name ";
				$db->setQuery($query);
				$regions =$db->loadObjectList();
				if($regions){
					echo '<div class="clear_both"></div>';
					echo '<select class="inputbox" name="se_regs[]" id="se'.$mod_id.'_reg_'.$id.'" onchange="se'.$mod_id.'_new_reg('.$id.',this.value,new Array());">';
				//	echo '<option value="'.$id.'">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_LOCATION_SELECTOR_EMPTY_VALUE').'</option>';
					echo '<option value="">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_LOCATION_SELECTOR_EMPTY_VALUE').'</option>';
					foreach($regions as $region){
						echo '<option value="'.$region->id.'">'.str_ireplace("'", "&apos;", $region->name).'</option>';
					}
					echo '</select>';
					echo '<div id="se'.$mod_id.'_after_reg_'.$id.'"></div>';
				}
			}
		
			die();
		}
		
		public function getCategorySelect(){		
			header("Content-type: text/html; charset=utf-8");
			$id 	= JRequest::getInt('cat_id', '0');
			$pid 	= JRequest::getVar('cat_id', '0');
			$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
			$db 	= JFactory::getDBO();
			$user 	= JFactory::getUser();	
			$mod_id = JRequest::getInt('mod_id', '0');
			
			$language = JFactory::getLanguage();
			$c_lang = $language->getTag();
			if($c_lang=='pl-PL' || $c_lang=='en-GB'){
				$language->load('mod_djclassifieds_search', JPATH_SITE.'/modules/mod_djclassifieds_search', null, true);
			}else{
				if(!$language->load('mod_djclassifieds_search', JPATH_SITE, null, true)){
					$language->load('mod_djclassifieds_search', JPATH_SITE.'/modules/mod_djclassifieds_search', null, true);
				}
			}
			
				if($id>0 && !strstr($pid, 'p')){
						$query = "SELECT * FROM #__djcf_categories WHERE id='".$id."' AND published=1 ";
						$db->setQuery($query);
						$parent_cat =$db->loadObject();
							
						$query = "SELECT * FROM #__djcf_categories WHERE parent_id='".$id."' AND published=1 ";
						$db->setQuery($query);
						$cats =$db->loadObjectList();
							
						if($cats){						
							echo '<div class="clear_both"></div>';
							echo '<select class="inputbox" name="se_cats[]" id="se'.$mod_id.'_cat_'.$id.'" onchange="se'.$mod_id.'_new_cat('.$id.',this.value,new Array());se'.$mod_id.'_getFields(this.value);">';
					    		echo '<option value="p'.$id.'">'.JTEXT::_('MOD_DJCLASSIFIEDS_SEARCH_CATEGORY_SELECTOR_EMPTY_VALUE').'</option>';
							    foreach($cats as $cat){	
							    	echo '<option value="'.$cat->id.'">'.str_ireplace("'", "&apos;", $cat->name).'</option>';
								}
							echo '</select>';
							echo '<div id="se'.$mod_id.'_after_cat_'.$id.'"></div>';
						}
				}
		
			die();
		}
	
}

?>