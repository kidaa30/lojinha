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
defined ('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.editor' );
$editor = JFactory::getEditor();
$dispatcher	= JDispatcher::getInstance();
?>
		<form action="index.php" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<div class="row-fluid">
			<div class="span12 form-horizontal">
			<fieldset class="adminform">	
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#details" data-toggle="tab"><?php echo empty($this->email->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_LABEL');?></div>
							<div class="controls">
	                			<?php echo JText::_($this->email->label); ?>
	                    		<input type="hidden" name="label" id="label" value="<?php echo $this->email->label; ?>" />						
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');?></div>
							<div class="controls">
	                    		<input name="title" id="title" value="<?php echo $this->email->title; ?>" />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label"><?php echo JText::_('COM_DJCLASSIFIEDS_EMAIL_BODY');?></div>
							<div class="controls">
								<?php echo $editor->display( 'email_body', $this->email->content, '100%', '350', '50', '20',true ); ?>
							</div>
						</div>
						<br /><br /><br />
						<h3><?php echo JText::_('COM_DJCLASSIFIEDS_AVAILABLE_TAGS');?></h3>
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT');?></h5>
						<div class="control-group">
							<div class="control-label">[[advert_id]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_ID');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[advert_link]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_LINK');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[advert_title]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_TITLE');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[advert_title_link]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_TITLE_LINKED');?></div>
						</div>						
						<div class="control-group">
							<div class="control-label" style="float:left;margin-right:10px;">[[advert_category]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_CATEGORY');?></div>
						</div>
						<div class="control-group">
							<div class="control-label" style="float:left;margin-right:10px;">[[advert_intro_desc]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_INTRO_DESCRIPTION');?></div>
						</div>
						<div class="control-group">
							<div class="control-label" style="float:left;margin-right:10px;">[[advert_desc]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_DESCRIPTION');?></div>
						</div>
						<div class="control-group">
							<div class="control-label" >[[advert_status]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_STATUS');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[advert_author_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_AUTHOR_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[advert_author_email]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_AUTHOR_EMAIL');?></div>
						</div>					
						<div class="control-group">
							<div class="control-label">[[advert_expire_days]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ADVERT_EXPIRE_DAYS');?></div>
						</div>			
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_RECIPIENT');?></h5>
						<div class="control-group">
							<div class="control-label">[[user_id]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_USER_ID');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[user_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_USER_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[user_username]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_USER_USERNAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[user_email]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_USER_EMAIL');?></div>
						</div>
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ASK_FORM_CONTACT');?></h5>
						<div class="control-group">
							<div class="control-label">[[contact_author_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_CONTACT_AUTHOR_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[contact_author_email]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_CONTACT_AUTHOR_EMAIL');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[contact_custom_fields_message]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_CONTACT_CUSTOM_FIELDS_MESSAGE');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[contact_message]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_CONTACT_MESSAGE');?></div>
						</div>
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ABUSE_FORM_REPORT');?></h5>
						<div class="control-group">
							<div class="control-label">[[abuse_author_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ABUSE_AUTHOR_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[abuse_message]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_ABUSE_MESSAGE');?></div>
						</div>						
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER');?></h5>
						<div class="control-group">
							<div class="control-label">[[bidder_id]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_ID');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[bidder_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[bidder_username]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_USERNAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[bidder_email]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_EMAIL');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[bid_value]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BID_VALUE');?></div>
						</div>						
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_CONTACT');?></h5>
						<div class="control-group">
							<div class="control-label">[[bcontact_author_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_CONTACT_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[bcontact_message]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BIDDER_CONTACT_MESSAGE');?></div>
						</div>
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_BUYNOW');?></h5>
						<div class="control-group">
							<div class="control-label">[[buynow_quantity]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BUYNOW_QUANTITY');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[buynow_price]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BUYNOW_PRICE');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[buynow_price_total]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BUYNOW_PRICE_TOTAL');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[buyer_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BUYER_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[buyer_email]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_BUYER_EMAIL');?></div>
						</div>
						<h5><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENTS');?></h5>
						<div class="control-group">
							<div class="control-label">[[payment_item_name]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_PAYMENT_ITEM_NAME');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[payment_price]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_PAYMENT_PRICE');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[payment_info]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_ET_PAYMENT_INFO');?></div>
						</div>
						<div class="control-group">
							<div class="control-label">[[payment_id]]</div>
							<div class="controls"><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_ID');?></div>
						</div>
						<?php 
							$tags_list = $dispatcher->trigger('onAdminEditEmailTemplate', array ($this->email->id));
							
							if(count($tags_list)){								
								foreach($tags_list as $tags){
									echo $tags;
								}	
							}
						?>												
					</div>
				</div>
				</fieldset>
			</div>
			</div>
			<input type="hidden" name="id" value="<?php echo $this->email->id; ?>" />
			<input type="hidden" name="option" value="com_djclassifieds" />
			<input type="hidden" name="task" value="email" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>			
<?php echo DJCFFOOTER; ?>