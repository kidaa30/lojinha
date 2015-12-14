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

$par	 = JComponentHelper::getParams( 'com_djclassifieds' );
$config  = JFactory::getConfig();
$app	 = JFactory::getApplication();
$user	 = JFactory::getUser();
$id 	 = JRequest::getInt('id', 0);
$token	 = JRequest::getCmd('token','');
$itemid  = JRequest::getVar('Itemid', 0, '', 'int');

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';


?>
<div id="dj-classifieds" class="djcf_warning_delete clearfix">
	<div class="djcf_warning_outer clearfix">
		<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_CONFIRMATION');?></div>
		<div class="djcf_warning_outer_in">
			<?php $modules_djcf = &JModuleHelper::getModules('djcf-delete-top');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-war-top clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';		
				}	?>
			<div class="djcf_war_content">
				<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_CONFIRM');?>
				<?php 
					echo ' "<a class="" href="'.DJClassifiedsSEO::getItemRoute($this->item->id.':'.$this->item->alias,$this->item->cat_id.':'.$this->item->c_alias).'">';
					 echo $this->item->name;
					echo '</a>"';?>
			</div>                	
			<?php $modules_djcf = &JModuleHelper::getModules('djcf-delete-center');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-war-center clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';		
				}	?>						
			<div class="djcf_war_buttons">
				<a class="djcf_war_cancel button" href="<?php echo JURI::base();?>">
					<?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL'); ?>
				</a>
				<?php if($user->id>0 && $id>0){
					$delete_link = 'index.php?option=com_djclassifieds&view=item&task=delete&id='.$id.'&Itemid='.$itemid ;
				}else{
					$delete_link = 'index.php?option=com_djclassifieds&view=item&task=deletetoken&token='.$token; 
				}?>
				<a href="<?php echo $delete_link ; ?>" class="djcf_war_accept button" >
					<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE');?>
				</a>
				<div class="clear_both"></div>
			</div>
			<?php $modules_djcf = &JModuleHelper::getModules('djcf-delete-bottom');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-war-bottom clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';		
				}	?>		      
			<div class="clear_both"></div>        	 					 					
		</div>	
	</div> 
</div>