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

// No direct access.
defined('_JEXEC') or die;
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'tables');
jimport('joomla.application.component.controlleradmin');

class DJClassifiedsControllerProfiles extends JControllerAdmin
{
	public function getModel($name = 'Profile', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	
	function recreateAvatarts(){
		$app = JFactory::getApplication();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_RECREATING_AVATARS'), 'generic.png');
		 
		$cid = JRequest::getVar( 'cid', array(), 'default', 'array' );
		JArrayHelper::toInteger($cid);
	
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_DJCLASSIFIEDS_SELECT_ITEM_TO_RECREATE_AVATARS' ) );
		}
	
		$tmp = array();
		$tmp[0] = $cid[0];
		unset($cid[0]);
		$db =  JFactory::getDBO();
		$query = "SELECT * FROM #__djcf_images WHERE item_id =  ".$tmp[0] ." AND type='profile' ";
		$db->setQuery($query);
		$images = $db->loadObjectList();
		if($images){
			$nw = $par->get('profth_width',120);
			$nh = $par->get('profth_height',120);
			$nws = $par->get('prof_smallth_width',50);
			$nhs = $par->get('prof_smallth_height',50);
		
			foreach($images as $image){
				$path = JPATH_SITE.$image->path.$image->name;
				if (JFile::exists($path.'.'.$image->ext)){
					if (JFile::exists($path.'_th.'.$image->ext)){
						JFile::delete($path.'_th.'.$image->ext);
					}					
					if (JFile::exists($path.'_ths.'.$image->ext)){
						JFile::delete($path.'_ths.'.$image->ext);
					}
	
					//DJClassifiedsImage::makeThumb($path.$images[$ii], $nws, $nhs, 'ths');
					DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_th.'.$image->ext, $nw, $nh);
					DJClassifiedsImage::makeThumb($path.'.'.$image->ext,$path.'_ths.'.$image->ext, $nws, $nhs);					
				}
			}
		}
	
		 
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djclassifieds&view=profiles', JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED') );
		} else {
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value;
			}
			echo '<h3>'.JTEXT::_('COM_DJCLASSIFIEDS_RESIZING_ITEM').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCLASSIFIEDS_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djclassifieds&task=profiles.recreateAvatarts'.$cids);
		}
		//$redirect = 'index.php?option=com_djclassifieds&view=items';
		//$app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}
	
}