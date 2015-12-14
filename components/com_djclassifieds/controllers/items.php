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


class DJClassifiedsControllerItems extends JControllerLegacy {
	function parsesearch(){
		$app	=   JFactory::getApplication();		
		$Itemid = JRequest::getInt('Itemid',0);
		if($Itemid){
			$menus	= $app->getMenu('site');
			$result_page = $menus->getItem($Itemid);
			$link = JRoute::_($result_page->link.'&Itemid='.$Itemid.'&se=1',false);
		}else{
			$link = 'index.php?option=com_djclassifieds&view=items&cid=0&se=1';
			$link = JRoute::_($link,false);
		}
		
		
		
		
		//$link = 'index.php?option=com_djclassifieds&view=items';
		//$link .= '&Itemid='.$Itemid;
		/*if(JRequest::getVar('layout','')=='blog'){
			$link .= '&layout=blog';
		}*/
		
				
		foreach($_GET as $key=>$input){
			if($key=='task' || $key=='se'){
				continue;
			}
			if(is_array($input)){
				if(end($input)==''){
					array_pop($input);
				}
				$link .='&'.$key.'='.implode(',', $input);
			}else{
				$link .='&'.$key.'='.$input;
			}
								 
		}
		//echo $link; die();
		/*echo '<pre>';
		echo $link;
		print_r($_POST);
		print_r($_GET);die();
		if(isset($_GET['se_regs'])){
			$reg_id= end($_GET['se_regs']);
			if($reg_id=='' && count($_GET['se_regs'])>2){
				$reg_id =$_GET['se_regs'][count($_GET['se_regs'])-2];
			}
			$reg_id=(int)$reg_id;
		}*/
		//$link = JRoute::_($link,false);
		$app->redirect($link);
		die();
	}
	
}