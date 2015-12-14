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


defined('_JEXEC') or die('Restricted access');
//error_reporting(E_STRICT);
if(!defined("DS")){
	define('DS',DIRECTORY_SEPARATOR);
}
$par = JComponentHelper::getParams( 'com_djclassifieds' );

require_once(JPATH_COMPONENT.DS.'defines.djclassifieds.php');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djimage.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djregion.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djnotify.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djtheme.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djtype.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djseo.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djgeocoder.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djupload.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djsocial.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djparams.php');

if($par->get('date_persian',0)){
	require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'persiancalendar.php');	
}

JPluginHelper::importPlugin('djclassifieds');

/*
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'route.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'theme.php');
*/

$document= JFactory::getDocument();
	
	if(JRequest::getVar('view')=='item'){
		if($par->get('lightbox_type','slimbox')=='picbox'){
			$assets=JURI::base(true).'/components/com_djclassifieds/assets/picbox/';
			$document->addScript($assets.'js/picbox.js');
			$document->addStyleSheet($assets.'css/picbox.css');
		}else{
			$assets=JURI::base(true).'/components/com_djclassifieds/assets/slimbox-1.8/';	
			$document->addScript($assets.'js/slimbox.js');
			$document->addStyleSheet($assets.'css/slimbox.css');
		}
	}
	DJClassifiedsTheme::includeCSSfiles();



	require_once(JPATH_COMPONENT.DS.'controller.php');
	$lang = JFactory::GetLanguage();				
		
	if ($lang->getTag() != 'en-GB') {
		$lang = JFactory::getLanguage();
		$lang->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds', 'en-GB', true, false);
		if($lang->getTag()=='pl-PL'){
			$lang->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds', '', true, false);	
		}else{
			$lang->load('com_djclassifieds', JPATH_SITE, '', true, false);	
		}					
	}

	$view=JRequest::getCmd('view','show');
	if($view=='item' || $view=='items' || $view=='additem' || $view=='payment' || $view=='renewitem' || $view=='profileedit' || $view=='contact' || $view=='checkout' || $view=='registration'){
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php';
		jimport('joomla.filesystem.file');	
		if(JFile::exists($path)){
			require_once($path);
		}else{
			JError::raiseError('500',JText::_('Unknown controller'));
		}
		
		jimport('joomla.utilities.string');
		
		$c = 'DJClassifiedsController'.ucfirst($view);
		$controller = new $c();	
			
	}else{
		$controller = new DJClassifiedsController();
	}	
	
	$controller->execute( JRequest::getCmd('task','display'));
	$controller->redirect();

?>

