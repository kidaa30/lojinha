<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
jimport( 'joomla.application.component.controller' );


class DJClassifiedsNotify {
	
	function __construct(){
	}

	public static function notifyExpired($limit=0,$msg=1){
		$app 	  = JFactory::getApplication();
        $par 	  = JComponentHelper::getParams( 'com_djclassifieds' );				
		$mailfrom = $app->getCfg( 'mailfrom' );
		$config   = JFactory::getConfig();    
		$fromname = $config->get('sitename');			
		$db		  = JFactory::getDBO();
		$mailer   = JFactory::getMailer();
		$notify_days = $par->get('notify_days','0');
		
		if($notify_days>0){			
			$date_exp = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")+$notify_days, date("Y")));
			$lim ='';
			
			if($limit>0){
				$lim = ' LIMIT '.$limit;	
			}
			
			
			/*$query = "SELECT i.id, i.cat_id, i.date_exp, i.name, i.user_id, u.email, u.name as u_name "
					."FROM #__djcf_items i, #__users u WHERE i.user_id = u.id AND i.notify=0 "
					."AND i.date_exp < '".$date_exp."' ".$lim;*/
					
			$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, i.token, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
					."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
					."WHERE i.notify=0 AND i.date_exp < '".$date_exp."' ".$lim;
										
			$db->setQuery($query);
			$items = $db->loadObjectList();
			//echo '<pre>';print_r($db);print_r($items);die();	
		
				$menus	= $app->getMenu('site');
				$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
				
				$itemid = ''; 
				if($menu_item){
					$itemid='&Itemid='.$menu_item->id;
				}
				$renew_link=str_ireplace('administrator/', '', JURI::root()).'index.php?option=com_djclassifieds&view=useritems'.$itemid;
				$renew_link = JRoute::_($renew_link);
				
				$update_id = '';
				$items_c=0;
				
				$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 18 LIMIT 1";
				$db->setQuery($query);
				$email =$db->loadObject();
				
				foreach($items as $i){
					$mailto = '';
					if($i->user_id){
						$mailto = $i->u_email;
					}else{
						$mailto = $i->email;
					}	

					if($mailto){
						/*$subject= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_TITLE' ), $i->name);
						$message = sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_MESSAGE' ), $i->name, $notify_days);
						$message .= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_RENEW' ), $renew_link);
						$message .= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_REGARDS' ), $config->get('sitename'));					
						$mailer = JFactory::getMailer();
						$send =$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message);*/
						
						$subject = $email->title;
						$m_message = self::parseMessageBody($email->content,$email->id,$i);
						$m_message = str_ireplace('[[advert_expire_days]]', $notify_days, $m_message);
						
						$mailer = JFactory::getMailer();
						$send = $mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $m_message,$mode=1);
						
						if (!is_object($send)){
							$update_id .= $i->id.', ';
							$items_c++;
						}
						else {
							$app->enqueueMessage($send->get('message').' - email server might be currently overloaded. Notification emails will be sent later.');
							break;
						}						
					}
				}

				if($items_c>0){
					
					$update_id = substr($update_id, 0,-2);
					$query = "UPDATE `#__djcf_items` SET notify=1 WHERE id in (".$update_id.")";
					$db->setQuery($query);
					$db->query();
					if($msg==1){
						$app->enqueueMessage($items_c.' '.JText::_('COM_DJCLASSIFIEDS_NOTIFICATIONS_SENT'));
					}
				}											
		}
		return null;	
	}
	
	public static function notifyAdmin($item,$cat,$new_ad=1){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );	
		$db  = JFactory::getDBO();
		if($par->get('notify_admin','0')){
			$config = JFactory::getConfig();			

		
			$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
					."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
					."WHERE i.id = ".$item->id." LIMIT 1";
			
			$db->setQuery($query);
			$item =$db->loadObject();
						
			$item->c_name = '';
			$item->c_alias = '';
			if($cat){
				$item->c_name = $cat->name;
				$item->c_alias = $cat->alias;
			}
			
			if($new_ad){
				$email_id = 9;
			}else{
				$email_id = 10;
			}
			
			$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = ".$email_id." LIMIT 1";
			$db->setQuery($query);
			$email =$db->loadObject();
									
			$reciver = '';
			if($par->get('notify_user_email','')!=''){
				$mailto = $par->get('notify_user_email');
			}else{
				$mailto = $app->getCfg( 'mailfrom' );
			}
			
			$mailfrom = $app->getCfg( 'mailfrom' );
			$fromname=$config->get('sitename');
				
			$subject = $email->title;
			$m_message = self::parseMessageBody($email->content,$email->id,$item);
			
												
			/*if($item->promotions){
				$query = "SELECT * FROM #__djcf_promotions ";
				$db = JFactory::getDBO();		
				$db->setQuery($query);
				$promotions =$db->loadObjectList();
				$m_message .= JText::_('COM_DJCLASSIFIEDS_ANEMAIL_PROMOTIONS').': <br />';
				foreach($promotions as $prom){
					if(strstr($item->promotions, $prom->name)){
						$m_message .= JText::_($prom->label).'<br />';
					}
				}
				$m_message .='<br />';								
			}*/
								
			$mailer = JFactory::getMailer();
			$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $m_message,$mode=1);
		}
		return null;		
	}

	public static function notifyNewAdvertUser($item,$cat){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$db  	= JFactory::getDBO();
		$par 	=  JComponentHelper::getParams( 'com_djclassifieds' );	
		$user 	= JFactory::getUser();
		$u 		= JURI::getInstance( JURI::root() );
		
			$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, i.token, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
					."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
					."WHERE i.id = ".$item->id." LIMIT 1";
			
			$db->setQuery($query);
			$item =$db->loadObject();
					
			if($user->id){
				$mailto = $user->email;
			}else{
				$mailto = $item->email;
			}								
			$mailfrom = $app->getCfg( 'mailfrom' );			    
			$fromname=$config->get('sitename');
			
			$item->c_name = '';
			$item->c_alias = '';
			if($cat){
				$item->c_name = $cat->name;
				$item->c_alias = $cat->alias;
			}
						
			$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 11 LIMIT 1";
			$db->setQuery($query);
			$email =$db->loadObject();
			
			$subject = $email->title;
			$m_message = self::parseMessageBody($email->content,$email->id,$item);
			
			if(!$user->id && $item->email && $par->get('guest_can_edit',0)){
				
				if($u->getScheme()){
					$edit_link = $u->getScheme().'://'; }
				else{
					$edit_link = 'http://';
				}
				
				$edit_link .= $u->getHost().JRoute::_(DJClassifiedsSEO::getNewAdLink().'&token='.$item->token);				
				if(strstr($m_message, '[[advert_edit]]')){
					$m_message = str_ireplace('[[advert_edit]]', '<a href="'.$edit_link.'">'.$edit_link.'</a>', $m_message);
				}else{
					$m_message .=JText::_('COM_DJCLASSIFIEDS_EDITION_LINK').': <a href="'.$edit_link.'">'.$edit_link.'</a><br /><br />';
				}
			}else{
				$m_message = str_ireplace('[[advert_edit]]', '', $m_message); 
			}
			
			if(!$user->id && $item->email && $par->get('guest_can_delete',0)){
				if($u->getScheme()){
					$delete_link = $u->getScheme().'://'; }
				else{
					$delete_link = 'http://';
				}
				$delete_link .= $u->getHost().JRoute::_(DJClassifiedsSEO::getUserAdsLink().'&t=delete&token='.$item->token);				
				if(strstr($m_message, '[[advert_delete]]')){
					$m_message = str_ireplace('[[advert_delete]]', '<a href="'.$delete_link.'">'.$delete_link.'</a>', $m_message);
				}else{
					$m_message .=JText::_('COM_DJCLASSIFIEDS_REMOVE_LINK').': <a href="'.$delete_link.'">'.$delete_link.'</a><br /><br />';
				}
			}else{
				$m_message = str_ireplace('[[advert_delete]]', '', $m_message); 
			}
			//echo $m_message;die();
			$mailer = JFactory::getMailer();
			$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $m_message,$mode=1);
			
			/*
				$subject = JText::_('COM_DJCLASSIFIEDS_NAU_EMAIL_TITLE').' '.$config->get('sitename');
				$m_message = JText::_('COM_DJCLASSIFIEDS_NAU_EMAIL_TITLE').' '.$config->get('sitename')."<br /><br />";
				
				$m_message .= JText::_('COM_DJCLASSIFIEDS_TITLE').': '.$item->name."<br /><br />";
				$m_message .= JText::_('COM_DJCLASSIFIEDS_STATUS').': ';
					if($item->published){
						$m_message .= JText::_('COM_DJCLASSIFIEDS_PUBLISHED')."<br /><br />";		
					}else{
						$m_message .= JText::_('COM_DJCLASSIFIEDS_WAITING_FOR_PUBLISH')."<br /><br />";
					}
				$m_message .= JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION').': '.$item->intro_desc."<br /><br />";
				
				$u = JURI::getInstance( JURI::root() );
				if($u->getScheme()){
					$link = $u->getScheme().'://';
				}else{
					$link = 'http://';
				}
				$edit_link = $link;
				$delete_link = $link;
				$link .= $u->getHost().JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$cat->alias));
						
				$m_message .=JText::_('COM_DJCLASSIFIEDS_ADVERT_LINK').': <a href="'.$link.'">'.$link.'</a><br /><br />';
			*/
		
		return null;		
	}
	
	
	
	public static function notifyAuctions(){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	=  JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();		
		$date_now = date("Y-m-d H:i:s");
		$query = "SELECT i.*, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.notify<2 AND i.date_exp > '".$date_now."' AND i.auction=1 ";
		
		$db->setQuery($query);
		$items = $db->loadObjectList();
		
		if($items){
			foreach($items as $item){
				$query = "SELECT a.*, u.name, u.username, u.email FROM #__djcf_auctions a "
						."LEFT JOIN #__users u ON u.id=a.user_id "
						."WHERE a.item_id=".$item->id." "		
						."ORDER BY a.price DESC ";				
				$db->setQuery($query);
				$bids = $db->loadObjectList();
				$win = 0;
				$win_id = 0;
				if(count($bids)){
					foreach($bids as $b => $bid){
						$bidder = new stdClass();
						$bidder->id = $bid->user_id;
						$bidder->name = $bid->name;
						$bidder->username = $bid->username;
						$bidder->email = $bid->email;
						
						if($b>0){
							if($win_id!=$bid->user_id){
								DJClassifiedsNotify::notifyAuctionsWinBidder($item->id,$bidder,$bid->price,15);	
							}
						}else{
							if($bid->price>=$item->price_reserve){
								DJClassifiedsNotify::notifyAuctionsWinBidder($item->id,$bidder,$bid->price);
								$win = 1;
							}else{
								DJClassifiedsNotify::notifyAuctionsWinBidder($item->id,$bidder,$bid->price,14);
							}
							$win_id = $bid->user_id;
						}						
					}
					if($win){						
						self::notifyAuctionsWinAuthor($item->id,$bidder,$bid->price);
					}else{
						self::notifyAuctionsWinAuthor($item->id,$bidder,$bid->price,13);
					}
				}else{
					self::notifyAuctionsWinAuthor($item->id,'','',12);
				}
				
				$query = "UPDATE `#__djcf_items` SET notify=2 WHERE id = ".$item->id." ";
				$db->setQuery($query);
				$db->query();
			}
		}
		return null;		
	}
	

	public static function notifyAuctionsBidAuthor($id,$bidder,$bid){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	=  JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		
			$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
					."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
					."WHERE i.id = ".$id." LIMIT 1";
			
			$db->setQuery($query);
			$item =$db->loadObject();
				
			$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 1 LIMIT 1";
			$db->setQuery($query);
			$email =$db->loadObject();
			$reciver = '';
			if($item->user_id){
				$mailto = $item->u_email;
				$reciver = JFactory::getUser($item->user_id);
			}else{
				$mailto = $item->email;
			}
			$mailfrom = $app->getCfg( 'mailfrom' );
			$fromname=$config->get('sitename');
			
			$subject = $email->title;
			$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid);
			
			$mailer = JFactory::getMailer();
			$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}
	
	
	public static function notifyAuctionsBidBidder($id,$bidder,$bid){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 2 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		$reciver = $bidder;
		
		$mailto = $bidder->email;
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}
	
	
	public static function notifyAuctionsBidOutbid($id,$bidder,$bid,$prev_bid){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 3 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		
		$reciver = JFactory::getUser($prev_bid->user_id);
		$mailto = $reciver->email;				
		
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	
	
	
	public static function notifyAuctionsWinAuthor($id,$bidder,$bid,$email_id=4){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = ".$email_id." LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		$reciver = '';
		if($item->user_id){
			$mailto = $item->u_email;
			$reciver = JFactory::getUser($item->user_id);
		}else{
			$mailto = $item->email;
		}
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}
	
	
	public static function notifyAuctionsWinBidder($id,$bidder,$bid,$email_id=5){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = ".$email_id." LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		$reciver = $bidder;
	
		$mailto = $bidder->email;
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	

	public static function messageAuthorToBidder($id,$bidder,$item,$bid,$owner,$title,$message){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 6 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		
		$reciver = $bidder;
	
		$mailto = $bidder->email;
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,$bidder,$bid,$message,$owner);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	
	
	public static function messageAskFormContact($item,$author,$message,$files,$replyto,$replytoname,$custom_fields_msg){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$dispatcher = JDispatcher::getInstance();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 7 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
	
		$reciver = '';
		if($item->user_id){
			$mailto = $item->u_email;
			$reciver = JFactory::getUser($item->user_id);
		}else{
			$mailto = $item->email;
		}
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname = $config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,'','','','',$message,$author,'','','','',$custom_fields_msg);
			
		
		$add_attachment = 0;
		if($par->get('ask_seller_file','0')==1){
			if($files['ask_file']['name']){
				if(count($files['ask_file']['name'])){
					$file_maxsize = $par->get('ask_seller_file_size',2);
					if($file_maxsize>0){
						$file_maxsize = $file_maxsize*1024*1024;
					}
					
					if($file_maxsize>0 && $files['ask_file']['size']<$file_maxsize){
						$file_ext = end(explode('.', $files['ask_file']['name']));
						if(strstr(','.str_ireplace(' ', '', $par->get('ask_seller_file_types','doc,pdf,zip')).',', ','.$file_ext.',')){
							$add_attachment = 1;
						}
					}
				}
			}					
		}
		
		
		JPluginHelper::importPlugin('djclassifiedsmessage');		
		$dispatcher->trigger('onDJClassifiedsSendMessage', array($item,$author,$mailto,$mailfrom,$fromname,$replyto,$replytoname,$subject,$message,$files,$custom_fields_msg));
				
		$mailer = JFactory::getMailer();
				
		$mailer->setSender(array($mailfrom, $fromname));
		$mailer->setSubject($subject);
		$mailer->setBody($message);		
		$mailer->IsHTML(true);				
		$mailer->addRecipient($mailto);
		$mailer->addReplyTo(array($replyto, $replytoname));
		
		if($par->get('ask_seller_copy_admin',0)>0){
			if($par->get('notify_user_email','')!=''){
				$bcc_email = $par->get('notify_user_email');
			}else{
				$bcc_email = $app->getCfg( 'mailfrom' );
			}	
			$mailer->addBCC($bcc_email);
		}						
		
		if($add_attachment){
			$mailer->addAttachment($files['ask_file']['tmp_name'],$files['ask_file']['name']);
		}
				
		$mailer->Send();
		
		//$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1,$cc = null, $bcc = null, $attachment);
		return true;
	}	
	
	public static function messageAbuseFormContact($item,$author,$message,$mailto){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 8 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
	
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname = $config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,'','','','','','','',$message,$author);
			
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}

	public static function notifyBuynowBuyer($id,$buyer,$quantity,$opt_name){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published,i.price, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		if($opt_name){
			$item->name .= ' ('.$opt_name.')';
		}	
		
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 16 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();		
		
		$reciver = $buyer;
		$mailto = $buyer->email;
		
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,'','','','','','','','',$buyer,$quantity);
					
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}
	
	public static function notifyBuynowAuthor($id,$buyer,$quantity,$opt_name){
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, i.price, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
		
		if($opt_name){
			$item->name .= ' ('.$opt_name.')';
		}
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 17 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		
		$reciver = '';
		if($item->user_id){
			$mailto = $item->u_email;
			$reciver = JFactory::getUser($item->user_id);
		}else{
			$mailto = $item->email;
		}		
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname=$config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,'','','','','','','','',$buyer,$quantity);
		
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	
	
	public static function notifyUserPublication($id,$new_status){
		
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
	
		$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, i.price, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
				."LEFT JOIN #__users u ON u.id=i.user_id "
				."WHERE i.id = ".$id." LIMIT 1";
			
		$db->setQuery($query);
		$item =$db->loadObject();
	
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 19 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
		
		$item->published = $new_status;
	
		$reciver = '';
		if($item->user_id){
			$mailto = $item->u_email;
			$reciver = JFactory::getUser($item->user_id);
		}else{
			$mailto = $item->email;
		}
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname = $config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver);
	
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	
	

	public static function notifyUserPayment($type,$id,$payment_info){
	
		$app 	= JFactory::getApplication();
		$config = JFactory::getConfig();
		$par 	= JComponentHelper::getParams( 'com_djclassifieds' );
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
	
		
		$item = '';
		if($type==''){	
			$query = "SELECT i.id, i.cat_id, i.name, i.alias, i.intro_desc, i.description, i.user_id,i.promotions, i.email, i.published, i.price, c.name as c_name, c.alias as c_alias,u.name as u_name,u.email as u_email, u.username as u_username "
				."FROM #__djcf_items i "
				."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
						."LEFT JOIN #__users u ON u.id=i.user_id "
						."WHERE i.id = ".$id." LIMIT 1";
			
			$db->setQuery($query);
			$item =$db->loadObject();
		}
		
		$query = "SELECT e.* FROM #__djcf_emails e WHERE e.id = 20 LIMIT 1";
		$db->setQuery($query);
		$email =$db->loadObject();
	
		$reciver = '';
		if($item){
			if($item->user_id){
				$mailto = $item->u_email;
				$reciver = JFactory::getUser($item->user_id);
			}else{
				$mailto = $item->email;
			}
		}else{
			$mailto = $user->email;
			$reciver = $user;
		}
		
		$mailfrom = $app->getCfg( 'mailfrom' );
		$fromname = $config->get('sitename');
			
		$subject = $email->title;
		$message = self::parseMessageBody($email->content,$email->id,$item,$reciver,'','','','','','','','','','','',$payment_info);
	
		$mailer = JFactory::getMailer();
		$mailer->sendMail($mailfrom, $fromname, $mailto, $subject, $message,$mode=1);
		return true;
	}	
	
	public static function parseMessageBody($message,$message_id,$item,$reciver='',$bidder='',$bid='',$bcontact_message='',$bowner='',$contact_message='',$contact_author='',$abuse_message='',$abuse_author='',$buyer='',$quantity='',$contact_fields_message='',$payment_info=array()){		
		
		$dispatcher	= JDispatcher::getInstance();
		$dispatcher->trigger('onAdminBeforeParseEmailBody', array (&$message,$message_id,&$item,&$reciver,&$bidder,&$bid,&$bcontact_message,&$bowner,$contact_message,&$contact_author,&$abuse_message,&$abuse_author,&$buyer,&$quantity,&$contact_fields_message));
				
		$u = JURI::getInstance( JURI::root() );				
		if($item){
			
			if($u->getScheme()){
				$link = $u->getScheme().'://';
			}else{
				$link = 'http://';
			}
			$link .= $u->getHost().JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias));
			$link = str_ireplace('administrator/', '', $link);
			
			
			$message = str_ireplace('[[advert_id]]', $item->id, $message);
			$message = str_ireplace('[[advert_link]]', '<a href="'.$link.'">'.$link.'</a>', $message);
			$message = str_ireplace('[[advert_title]]', $item->name, $message);
			$message = str_ireplace('[[advert_title_link]]', '<a href="'.$link.'">'.$item->name.'</a>', $message);
			$message = str_ireplace('[[advert_category]]', $item->c_name, $message);
			$message = str_ireplace('[[advert_intro_desc]]', $item->intro_desc, $message);
			$message = str_ireplace('[[advert_desc]]', $item->description, $message);
			if($item->user_id){
				$message = str_ireplace('[[advert_author_name]]', $item->u_name, $message);
				$message = str_ireplace('[[advert_author_email]]', $item->u_email, $message);
			}else{
				$message = str_ireplace('[[advert_author_name]]', JText::_('COM_DJCLASSIFIEDS_GUEST'), $message);
				$message = str_ireplace('[[advert_author_email]]', $item->email, $message);
			}
			if($item->published){
				$message = str_ireplace('[[advert_status]]', JText::_('COM_DJCLASSIFIEDS_PUBLISHED'), $message);
			}else{
				$message = str_ireplace('[[advert_status]]', JText::_('COM_DJCLASSIFIEDS_WAITING_FOR_PUBLISH'), $message);
			}	
		}
		
		
									
		if($reciver){
			$message = str_ireplace('[[user_id]]', $reciver->id, $message);
			$message = str_ireplace('[[user_name]]', $reciver->name, $message);
			$message = str_ireplace('[[user_username]]', $reciver->username, $message);
			$message = str_ireplace('[[user_email]]', $reciver->email, $message);
		}
		if($bid){
			$message = str_ireplace('[[bid_value]]', $bid, $message);
		}
		if($bidder){
			$message = str_ireplace('[[bidder_id]]', $bidder->id, $message);
			$message = str_ireplace('[[bidder_name]]', $bidder->name, $message);
			$message = str_ireplace('[[bidder_username]]', $bidder->username, $message);
			$message = str_ireplace('[[bidder_email]]', $bidder->email, $message);
		}
		if($bcontact_message){
			$message = str_ireplace('[[bcontact_message]]',$bcontact_message, $message);	
		}
		if($bowner){
			$message = str_ireplace('[[bcontact_author_name]]',$bowner->name, $message);
		}
		if($contact_fields_message){
			$message = str_ireplace('[[contact_custom_fields_message]]',$contact_fields_message, $message);
		}
		if($contact_message){
			$message = str_ireplace('[[contact_message]]',$contact_message, $message);
		}
		if($contact_author){
			$message = str_ireplace('[[contact_author_name]]',$contact_author['name'], $message);
			$message = str_ireplace('[[contact_author_email]]',$contact_author['email'], $message);
		}
		if($abuse_message){
			$message = str_ireplace('[[abuse_message]]',$abuse_message, $message);
		}
		if($abuse_author){
			$message = str_ireplace('[[abuse_author_name]]',$abuse_author->name, $message);
		}
		
		if($buyer){
			$message = str_ireplace('[[buyer_name]]', $buyer->name, $message);
			$message = str_ireplace('[[buyer_email]]', $buyer->email, $message);
		}					
		if($quantity){
			$message = str_ireplace('[[buynow_quantity]]', $quantity, $message);
			$message = str_ireplace('[[buynow_price]]', $item->price, $message);
			$dispatcher->trigger('onAdminPriceParseEmailBody', array (&$message,$message_id,$item,$buyer,&$price_total));
			$price_total = $quantity*$item->price;
			$message = str_ireplace('[[buynow_price_total]]', $price_total, $message);			
		}		
		
		if(count($payment_info)){
			$message = str_ireplace('[[payment_item_name]]', $payment_info['itemname'], $message);
			$message = str_ireplace('[[payment_price]]', $payment_info['amount'], $message);
			$message = str_ireplace('[[payment_info]]', $payment_info['info'], $message);
			$message = str_ireplace('[[payment_id]]', $payment_info['id'], $message);
		}
		 
		$dispatcher->trigger('onAdminParseEmailBody', array (&$message,$message_id,$item,$buyer));
										
		
		return $message;

	}


}
