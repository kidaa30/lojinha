<?php
/**
* @version 2.0
* @package DJ Classifieds Menu Module
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

defined ('_JEXEC') or die('Restricted access');

require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djregion.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djseo.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');

$reg_id = 0;
	if(isset($_GET['se_regs'])){
		if(is_array($_GET['se_regs'])){
			$reg_id= end($_GET['se_regs']);
			if($reg_id=='' && count($_GET['se_regs'])>2){
				$reg_id =$_GET['se_regs'][count($_GET['se_regs'])-2];
			}						
		}else{
			$reg_ids = explode(',', JRequest::getVar('se_regs'));
			$reg_id = end($reg_ids);
		}
		$reg_id=(int)$reg_id;	
	}
	
$djcfreglib = new DJClassifiedsRegion();
    $show_items_c = $params->get('items_count',0);
	$ret= $djcfreglib->getMenuRegions($reg_id,$show_items_c);
	//echo '<pre>';print_r($ret);die();
	if($params->get('expand_type','0')==1){
		if($params->get('region_id','0')){
			$regs= $djcfreglib->getSubReg($params->get('region_id','0'),$show_items_c);
			//echo '<pre>';print_r($regs);die();
		}else{
			$regs = $ret[2];
		}
		
	}else{
		$regs = $ret[0];
	}
	
	$reg_path = $ret[1]; 
	
	$menus		= $app->getMenu('site');	
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);

			
	$itemid = ''; 
	if($menu_item){
		$itemid='&Itemid='.$menu_item->id;
	}else if($menu_item_blog){
		$itemid='&Itemid='.$menu_item_blog->id;
	}	
	
if(JRequest::getVar('option')!='com_djclassifieds'){
	DJClassifiedsTheme::includeCSSfiles();
}	

require(JModuleHelper::getLayoutPath('mod_djclassifieds_regions'));
?>



