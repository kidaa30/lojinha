<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
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

defined ('_JEXEC') or die('Restricted access');
$par = JComponentHelper::getParams( 'com_djclassifieds' );
$app = JFactory::getApplication();
$item = $this->item;

 if((int)$par->get('showaddetails','1')){?>
	<div class="additional"><h2><?php echo JText::_('COM_DJCLASSIFIEDS_AD_DETAILS'); ?></h2>
		<div class="row">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_ID'); ?>:</span>
			<span class="row_value"><?php echo $item->id; ?></span>
		</div>
		<div class="row">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED'); ?>:</span>
			<span class="row_value"><?php echo $item->display; ?></span>
		</div>
		<?php if($par->get('show_ad_added_date','1')==2){?>
		<div class="row">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_ADDED'); ?></span>
			<span class="row_value">
				<?php echo DJClassifiedsTheme::formatDate(strtotime($item->date_start));  ?>
			</span>
		</div>
		<?php } ?>			
		<div class="row">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_EXPIRES'); ?>:</span>
			<span class="row_value">
				<?php echo DJClassifiedsTheme::formatDate(strtotime($item->date_exp));  ?>
			</span>
		</div>
	</div>
<?php } ?>