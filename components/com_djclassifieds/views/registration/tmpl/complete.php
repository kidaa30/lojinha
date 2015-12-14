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
$app	 = JFactory::getApplication();
$Itemid = JRequest::getInt('Itemid', 0);

if($Itemid){
	$menu_item = $app->getMenu()->getItem($Itemid);
	if($menu_item){
		$pc_sfx = $menu_item->params->get('pageclass_sfx');
	}
	if($pc_sfx){$pageclass_sfx =' '.$pc_sfx;}
	$active_m = $app->getMenu('site')->getActive();
}
?>
<div id="dj-classifieds" class="clearfix registration-complete djcftheme-<?php echo $this->theme;?><?php echo $pageclass_sfx;?>">
<?php
	if($Itemid){
		if($active_m->params->get('show_page_heading','1')){
			echo '<h1 class="main_cat_title">'; 
				if($active_m->params->get('page_title','')){
					echo $active_m->params->get('page_title','');
				}else{
					echo $active_m->title;
				}
			echo '</h1>';	
		}
	} ?>
</div>

