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

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<table class="table table-striped" width="100%">
			<tr>
				<td width="100%" valign="top">
					<div class="djc_control_panel clearfix">
						<div class="cpanel-left">
							<div class="cpanel">
								<div style="float:left;">
									<div class="icon"> 
										<a href="index.php?option=com_djclassifieds&amp;view=categories">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORIES'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-category.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORIES'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=items">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_ITEMS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-article.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_ITEMS'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=fields">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_EXTRA_FIELDS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-levels.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_EXTRA_FIELDS'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=regions">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-language.png" />								
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=days">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_DURATIONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-calendar.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_DURATIONS'); ?></span>
										</a> 
									</div>
								</div>
								<div style="clear: both;"></div>					 
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=category.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_NEW_CATEGORY'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-category-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_NEW_CATEGORY'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=item.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_NEW_ITEM'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-article-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_NEW_ITEM'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=field.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_NEW_EXTRA_FIELD'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-levels-add.png" />								
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_NEW_EXTRA_FIELD'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=region.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_NEW_LOCATION'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-language-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_NEW_LOCATION'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=day.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_ADD_DURATIONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-calendar-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_DURATIONS'); ?></span>
										</a>
									</div>
								</div>
								<div style="clear: both;"></div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=types">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_TYPES'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-types.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_TYPES'); ?></span>
										</a> 
									</div>
								</div>	
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=type.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_ADD_TYPE'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-types-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_TYPE'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=points">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-pp.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_POINTS_PACKAGES'); ?></span>
										</a> 
									</div>
								</div>	
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;task=point.add">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_ADD_POINTS_PACKAGE'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-pp-add.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_POINTS_PACKAGE'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=userspoints">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_USERS_POINTS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-userpoints.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_USERS_POINTS'); ?></span>
										</a> 
									</div>
								</div>
								<div style="clear: both;"></div>
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=payments">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENTS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-payments.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENTS'); ?></span>
										</a>
									</div>
								</div>									
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_plugins&view=plugins&filter_folder=djclassifiedspayment">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_PLUGINS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-plugin.png" />								
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_PLUGINS'); ?></span>
										</a>
									</div>
								</div>	
								<div style="float:left;">
									<div class="icon">
										<a href="index.php?option=com_djclassifieds&amp;view=promotions">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-notice.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'); ?></span>
										</a>
									</div>
								</div>				
								<div style="float:left;">
									<div class="icon">
										<a href="http://dj-extensions.com/dj-classifieds" target="_blank">
											<img alt="<?php echo JText::_('COM_DJCLASSIFIEDS_DOCUMENTATION'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-help_header.png" />
											<span><?php echo JText::_('COM_DJCLASSIFIEDS_DOCUMENTATION'); ?></span>
										</a>
									</div>
								</div>
								<div style="float:left;">
									<div class="icon">
										<a  href="index.php?option=com_config&view=component&component=com_djclassifieds&path=&return=<?php echo base64_encode('index.php?option=com_djclassifieds')?>">
										<img alt="<?php echo JText::_('JOPTIONS'); ?>" src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/icon-48-cpanel.png" />
											<span><?php echo JText::_('JOPTIONS'); ?></span>
										</a>
									</div>
								</div>
							</div>					
						</div>	
						<div class="cpanel-right">
							<div class="cpanel">
								<div style="float:right;">
									<?php echo DJLicense::getSubscription(); ?>
								</div>
								<div style="clear: both;" ></div>
							</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
</div>
<input type="hidden" name="option" value="com_djclassifieds" />
<input type="hidden" name="c" value="cpanel" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="cpanel" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php echo DJCFFOOTER; ?>