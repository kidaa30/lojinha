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

jimport('joomla.application.component.view');


class DJClassifiedsViewRegistration extends JViewLegacy{

	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/registration');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$theme = $par->get('theme','default');
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/registration');
		}
	}
	
	function display($tpl = NULL){
		global $mainframe;
		$par 		= JComponentHelper::getParams( 'com_djclassifieds' );
		$session 	= JFactory::getSession();		
		$user 		= JFactory::getUser();
		$app 		= JFactory::getApplication();		
		$config  	= JFactory::getConfig();
		$dispatcher	= JDispatcher::getInstance();		
		$menus		= $app->getMenu('site');	
		$language 	= JFactory::getLanguage();
		
		if($user->id>0){			
			$menu_profileedit_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=profileedit',1);
			$user_edit_profile='index.php?option=com_djclassifieds&view=profileedit';
			if($menu_profileedit_itemid){
				$user_edit_profile .= '&Itemid='.$menu_profileedit_itemid->id;
			}
			$app->redirect(JRoute::_($user_edit_profile));
		}else{		 			
				$model 		= $this->getModel();								
				$language->load('com_users', JPATH_SITE, null, true);
				
				/*$terms_link='';
				if($par->get('terms',1)>0 && $par->get('terms_article_id',0)>0 && JRequest::getVar('id', 0, '', 'int' )==0){
 					require_once JPATH_SITE.'/components/com_content/helpers/route.php';
					$terms_article = $model->getTermsLink($par->get('terms_article_id',0));					
					if($terms_article){
						$slug = $terms_article->id.':'.$terms_article->alias;
						$cslug = $terms_article->catid.':'.$terms_article->c_alias;
						$article_link = ContentHelperRoute::getArticleRoute($slug,$cslug);
						if($par->get('terms',0)==2){
							$article_link .='&tmpl=component';
						}
						$terms_link = JRoute::_($article_link);											
					}					
				}*/	
			

				$userparams = JComponentHelper::getParams( 'com_users' );
				if($userparams->get('allowUserRegistration')==0){
					$app->redirect(JURI::base(),JText::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'));
				}

				$custom_contact_fields = $model->getCustomContactFields();
				$extra_rows = $dispatcher->trigger('onUserRegistrationForm', array ());
												
				//$this->assignRef('terms_link',$terms_link);
				$this->assignRef('custom_contact_fields',$custom_contact_fields);
				$this->assignRef('extra_rows',$extra_rows);
        		parent::display();			
			
		}
	}

}




