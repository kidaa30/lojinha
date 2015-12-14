<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Stats Module
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

	$app 		= JFactory::getApplication();
	$config		= JFactory::getConfig();
	$document	= JFactory::getDocument();
?>
<div id="mod_djcf_stats<?php echo $module->id;?>" class="djcf_stats">
	<div class="djcf_stats_in">
		<?php if($params->get('txt_before','')){?>
			<div class="djcf_stats_before">
				<?php echo $params->get('txt_before',''); ?>
			</div>
		<?php } ?>
		
		<?php if($params->get('ads_total','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_total">
				<?php if($params->get('ads_total','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADS_TOTAL_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_total']; ?></span>
			</div>
		<?php } ?>
		
		<?php if($params->get('ads_active','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_active">
				<?php if($params->get('ads_active','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADS_ACTIVE_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_active']; ?></span>
			</div>
		<?php } ?>		
		
		<?php if($params->get('ads_added_today','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_today">
				<?php if($params->get('ads_added_today','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_TODAY_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_today']; ?></span>
			</div>
		<?php } ?>		
		
		<?php if($params->get('ads_added_1','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_1d">
				<?php if($params->get('ads_added_1','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_LAST_24H_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_1d']; ?></span>
			</div>
		<?php } ?>	
		
		<?php if($params->get('ads_added_week','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_week">
				<?php if($params->get('ads_added_week','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_CURRENT_WEEK_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_week']; ?></span>
			</div>
		<?php } ?>	
		
		<?php if($params->get('ads_added_7','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_7d">
				<?php if($params->get('ads_added_7','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_LAST_7_DAYS_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_7d']; ?></span>
			</div>
		<?php } ?>	
				
		<?php if($params->get('ads_added_month','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_month">
				<?php if($params->get('ads_added_month','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_CURRENT_MONTH_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_month']; ?></span>
			</div>
		<?php } ?>			
												
		<?php if($params->get('ads_added_30','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_30">
				<?php if($params->get('ads_added_30','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_LAST_30_DAYS_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_30d']; ?></span>
			</div>
		<?php } ?>		
		
		<?php if($params->get('ads_added_year','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_year">
				<?php if($params->get('ads_added_year','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_CURRENT_YEAR_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_year']; ?></span>
			</div>
		<?php } ?>							
		
		<?php if($params->get('ads_added_365','1')){ ?>
			<div class="djcf_stats_row djcf_stats_ads_added_365d">
				<?php if($params->get('ads_added_365','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_ADDED_LAST_365_DAYS_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['ads_365d']; ?></span>
			</div>
		<?php } ?>								
		
		<?php if($params->get('auctions_count','1')){ ?>
			<div class="djcf_stats_row djcf_stats_auctions_count">
				<?php if($params->get('auctions_count','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_AUCTIONS_COUNT_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['auctions_c']; ?></span>
			</div>
		<?php } ?>
		
		<?php if($params->get('cat_count','1')){ ?>
			<div class="djcf_stats_row djcf_stats_cat_count">
				<?php if($params->get('cat_count','1')==1){?>
					<span class="djcf_stats_label"><?php echo JText::_('MOD_DJCLASSIFIEDS_STATS_CATEGORIES_COUNT_LABEL'); ?>:</span>
				<?php } ?>
				<span class="djcf_stats_value"><?php echo $stats['categories_c']; ?></span>
			</div>
		<?php } ?>	
							
		<?php if($params->get('txt_before','')){?>
			<div class="djcf_stats_after">
				<?php echo $params->get('txt_after',''); ?>
			</div>
		<?php } ?>
			
		
		
	</div>
</div>