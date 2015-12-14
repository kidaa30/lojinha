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

if (!JFactory::getUser()->authorise('core.manage', 'com_djclassifieds')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(!defined("DS")){
	define('DS',DIRECTORY_SEPARATOR);
}
define ('DJCFATTFOLDER', JPATH_SITE.DS.'components'.DS.'com_djclassifieds'.DS.'files');
// Include dependancies
JPluginHelper::importPlugin('djclassifieds');
jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djimage.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djregion.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djnotify.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djlicense.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djtype.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djseo.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djgeocoder.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djupload.php'); 


	$par = JComponentHelper::getParams( 'com_djclassifieds' );
	$db =  JFactory::getDBO();
	$query = "SELECT name, type, element, manifest_cache "
			."FROM #__extensions WHERE element = 'com_djclassifieds' and type='component' ";	
	$db->setQuery($query);
	$ext = $db->loadObject();
	$mc = json_decode($ext->manifest_cache);
	$c_version = $mc->version;

define('DJCFVERSION', $c_version);
define('DJCFFOOTER', '<div style="text-align: center; margin: 10px 0;clear:both;">DJ-Classifieds (ver. '.DJCFVERSION.'), &copy; 2009-2015 Copyright by <a target="_blank" href="http://dj-extensions.com">dj-extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djclassifieds/images/djextensions.png" alt="dj-extensions.com" style="margin-top: 20px;"/></a></div>');

$document= JFactory::getDocument(); 

	$version = new JVersion;
	if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
		$cs = JURI::base().'components/com_djclassifieds/assets/style_legacy.css';
	}else{
		$cs = JURI::base().'components/com_djclassifieds/assets/style.css';
	}
	
	$document->addStyleSheet($cs);
		
	/*if($par->get('include_awesome_font','1')){
		$cs = JURI::base().'components/com_djclassifieds/assets/fontawesome/css/font-awesome.css';
		$document->addStyleSheet($cs);
	}*/

	$lang = JFactory::GetLanguage();				
	if ($lang->getTag() != 'en-GB') {
		$lang = JFactory::getLanguage();
		$lang->load('com_djclassifieds', JPATH_COMPONENT_ADMINISTRATOR, 'en-GB', true, false);
		if($lang->getTag()=='pl-PL'){
			$lang->load('com_djclassifieds', JPATH_COMPONENT_ADMINISTRATOR, '', true, false);	
		}else{
			$lang->load('com_djclassifieds', JPATH_ADMINISTRATOR, '', true, false);	
		}					
	}

	
// Perform the Request task
$controller = JControllerLegacy::getInstance('djclassifieds');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>