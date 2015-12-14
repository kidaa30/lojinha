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

JHTML::_('behavior.framework');
JHTML::_('behavior.tooltip');
$toolTipArray = array('className'=>'djcf');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);

$par	 	= JComponentHelper::getParams( 'com_djclassifieds' );
$config  	= JFactory::getConfig();
$app	 	= JFactory::getApplication();
$main_id 	= JRequest::getVar('cid', 0, '', 'int');
$user	 	= JFactory::getUser();
$dispatcher	= JDispatcher::getInstance();
JPluginHelper::importPlugin('djclassifieds');

$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$fav	= JRequest::getVar('fav', 0, '', 'int');
$fav_a	= $par->get('favourite','1');

$Itemid = JRequest::getVar('Itemid', 0, 'int');
$layout='';
if(JRequest::getVar('layout','')=='blog'){	
	$layout='&layout=blog';
}

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

	if($par->get('category_jump',0)){
		$anch = '#dj-classifieds';
	}else{
		$anch='';
	}

		
?>
<div id="dj-classifieds" class="clearfix djcftheme-<?php echo $par->get('theme','default');?>">
	<?php 
		$modules_djcf = &JModuleHelper::getModules('djcf-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}				
		
		
		$modules_djcf = &JModuleHelper::getModules('djcf-profile-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}		?>	
			
				<?php if($this->profile['event']->onBeforeDJClassifiedsDisplayProfile) { ?>
					<div class="djcf_before_profile">
						<?php echo $this->profile['event']->onBeforeDJClassifiedsDisplayProfile; ?>
					</div>
				<?php } ?>
								
			<div class="profile_outer"><?php echo $this->loadTemplate('profile'); ?></div>
			
				<?php if($this->profile['event']->onAfterDJClassifiedsDisplayProfile) { ?>
					<div class="djcf_after_profile">
						<?php echo $this->profile['event']->onAfterDJClassifiedsDisplayProfile; ?>
					</div>
				<?php } ?>
				
			<div class="profile_items">
				<?php
					$items_view = $par->get('profileitems_table_type','');
					if($items_view){$items_view = '_'.$items_view;}
					echo $this->loadTemplate('items'.$items_view); 				
				?>
			</div>						
			
				<?php if($this->profile['event']->onAfterDJClassifiedsDisplayProfileItems) { ?>
					<div class="djcf_after_profile_items">
						<?php echo $this->profile['event']->onAfterDJClassifiedsDisplayProfileItems; ?>
					</div>
				<?php } ?>
			
			
	
	<?php 
		$modules_djcf = &JModuleHelper::getModules('djcf-profile-items');
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-categories clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}

	
?>	 
</div>