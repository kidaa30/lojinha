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

jimport( 'joomla.application.component.helper');
jimport( 'joomla.application.component.model');


class DJClassifiedsModelCpanel extends JModelLegacy {

	function __construct()
	{
		parent::__construct();
	}	
	
	function checkCFImages(){ 
		$app = JFactory::getApplication();
		$db = JFactory::getDBO();
			
			$query = "SELECT count(id) FROM #__djcf_items WHERE image_url!='' ";
			$db->setQuery($query);
			$item_with_img = $db->loadResult();
			if($item_with_img){
				$app->enqueueMessage(JTExt::_('COM_DJCLASSIFIEDS_YOU_HAVE_OLD_STRUCTURE_OF_ADVERTS_IMAGES').' <a href="index.php?option=com_djclassifieds&task=items.migrateImages">'.JText::_('COM_DJCLASSIFIEDS_MIGRATE_IMAGES').'</a>','error');
			}
			
			$query = "SELECT count(id) FROM #__djcf_categories WHERE icon_url!='' ";
			$db->setQuery($query);
			$item_with_img = $db->loadResult();
			if($item_with_img){
				$app->enqueueMessage(JTExt::_('COM_DJCLASSIFIEDS_YOU_HAVE_OLD_STRUCTURE_OF_CATEGORIES_IMAGES').' <a href="index.php?option=com_djclassifieds&task=items.migrateCatImages">'.JText::_('COM_DJCLASSIFIEDS_MIGRATE_IMAGES').'</a>','error');
			}
		return null; 
	}
}
?>
