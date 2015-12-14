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


class DJclassifiedsViewItem extends JViewLegacy{
		
	public function __construct($config = array())
	{
		parent::__construct($config);				
		
		$this->_addPath('template', JPATH_COMPONENT.  '/themes/default/views/item');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$theme = $par->get('theme','default');
		if ($theme && $theme != 'default') {
			$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$theme.'/views/item');
		}
	}	
	
	function display($tpl = null){		
		$model 	    = $this->getModel();
		$par 	    = JComponentHelper::getParams( 'com_djclassifieds' );
		$document   = JFactory::getDocument();
		$app	    = JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$theme 		= $par->get('theme','default');
		$user 		= JFactory::getUser();
		
		$item=$model->getItem();

			
		if($item->user_id!=$user->id){		
			$groups_acl = ','.implode(',', $user->getAuthorisedViewLevels()).',';
			if(!strstr($groups_acl,','.$item->c_access_view.',') || !$item){
				DJClassifiedsTheme::djAccessRestriction('category');
			}else if($item->access_view==0){			
				if(!strstr($groups_acl,','.$item->c_access_item_view.',')){
					DJClassifiedsTheme::djAccessRestriction();
				}							
			}else if(!strstr($groups_acl,','.$item->access_view.',')){
				DJClassifiedsTheme::djAccessRestriction();
			}
		}
		
		$item_images = DJClassifiedsImage::getAdsImages($item->id);
		 
		$category=$model->getCategory($item->cat_id); 
		$fields=$model->getFields($item->cat_id);
		$fields_contact=$model->geContactFields();
		$item_payments=$model->getItemPayment($item->id);
		$item_options=$model->getItemOptions($item->id);
		$bids=$model->getBids($item->id,$par->get('bids_displayed',5));
		if($item->user_id!=0){
			$user_items_c=$model->getUserItemsCount($item->user_id);
			$this->assignRef('user_items_c',$user_items_c);
		}							 			
			$menus	= $app->getMenu('site');	
			$m_active = $menus->getActive();
			$cat_menu_path= array();
			$cid_menu=0;
			if($m_active){
				if(strstr($m_active->link,'com_djclassifieds') && strstr($m_active->link,'items')){
				  	$cid_menu = $m_active->query['cid'];
					if($cid_menu>0){
						$cat_menu_path= DJClassifiedsCategory::getParentPath(1,$cid_menu);	
					}						
				}
			}
			
			$warning18 = '';
			$main_cat_id = $item->cat_id;
			$pathway =$app->getPathway();
			$cat_path = array();
			$cat_theme ='';
			if($category->id!=0){	
				$cat_path= DJClassifiedsCategory::getParentPath(1,$category->id);	
				$main_cat_id = $cat_path[count($cat_path)-1]->id; 
				for($c=count($cat_path);$c>0;$c--){					
					$to_b = 1;
					if(count($cat_menu_path)){
						foreach($cat_menu_path as $cm){
							if($cm->id==$cat_path[$c-1]->id){
								$to_b = 0;
								break;			
							}
						}
					}
					if($to_b){
						$pathway->addItem($cat_path[$c-1]->name, DJClassifiedsSEO::getCategoryRoute($cat_path[$c-1]->id.':'.$cat_path[$c-1]->alias));
					}			
				}
				
				foreach($cat_path as $cp){
					if($cp->theme){
						$cat_theme = $cp->theme;
					}		
					if($cp->restriction_18){
						$warning18 = 1;
					}			 
				}					
			}
			
			DJClassifiedsTheme::includeCSSfiles($cat_theme);
			if($cat_theme){
				$this->_addPath('template', JPATH_COMPONENT.  '/themes/'.$cat_theme.'/views/item');
				$theme=$cat_theme;
			}
			
			$regions=$model->getRegions();
			$country='';
			$city='';
			$region_name = '';
			
			if($item->region_id!=0 && $par->get('show_regions','1')){
				$address='';
				$rid = $item->region_id;
				if($rid!=0){
					while($rid!=0){
						foreach($regions as $li){
							if($li->id==$rid){
								$rid=$li->parent_id;
								$address.=$li->name.', ';
								if($li->country){
									$country =$li->name;
								}
								if($li->city){
									$city =$li->name;
								}
								if(!$region_name){
									$region_name =$li->name;
								}
								break;
							}
						}
						if($rid==$item->region_id){break;}
					}
				}
				$address = substr($address, 0, -2);
			}			
			
			$profile='';
			if($item->user_id){
				$profile =$model->getProfile($item->user_id);				
			}
			
			$custom_ask_seller = $model->getCustomAskSellerFields();
			
			if($item->metakey!=''){
				$document->setMetaData('keywords',$item->metakey);
			}else if($category->metakey!=''){
				$document->setMetaData('keywords',$category->metakey);
			}else if($m_active){
				if($m_active->params->get('menu-meta_description')){
					$document->setMetaData('keywords',$m_active->params->get('menu-meta_description'));
				}
			}			
						
			if($item->metadesc!=''){
				$document->setDescription($item->metadesc);
			}else if($par->get('seo_item_metadesc', '0')==0){
				$document->setDescription($item->intro_desc);
			}else if($category->metadesc!=''){
				$document->setDescription($category->metadesc);
			}else if($m_active){
				if($m_active->params->get('menu-meta_keywords')){
					$document->setDescription($m_active->params->get('menu-meta_keywords'));
				}
			}			
			
			$c_title = $document->getTitle();
			$cat_name = $category->name;
			$item_name = $item->name;
			$seo_cat_path = ''; 
			$seo_title_separator = $par->get('seo_title_separator', ' - ');
			foreach($cat_path as $cp){
				if($seo_cat_path){
					$seo_cat_path .= $seo_title_separator;
				}
				$seo_cat_path .= $cp->name;					
			}
			
			$seo_title_from = array('|','<default_title>','<category_name>','<category_path>','<item_name>','<region_name>');
			$seo_title_to = array($seo_title_separator,$c_title,$cat_name,$seo_cat_path,$item_name,$region_name);
			$seo_title = str_ireplace($seo_title_from, $seo_title_to, $par->get('seo_title_item', '<item_name>|<category_name>|<default_title>'));
			$document->setTitle($seo_title);										
			
			$document->setMetaData('og:title',$item->name);
			$document->setMetaData('og:description',$item->intro_desc);
			if($item_images){									
				$document->setMetaData('og:image',JURI::base().$item_images[0]->thumb_b);
			} 				
			
			if($par->get('comments','0') == 1 && $par->get('fb_comments_admin','') != ''){
				$document->addCustomTag('<meta property="fb:admins" content="'.$par->get('fb_comments_admin','').'" />');
			}
						
			$anch = ($par->get('showitem_jump',0)) ? '#dj-classifieds' : '';
			$correct_link = JRoute::_(DJClassifiedsSEO::getItemRoute($item->id.':'.$item->alias,$item->cat_id.':'.$item->c_alias)).$anch;
			$document->setMetaData('canonical',$correct_link);

			/* plugins */
			if($category){
				$item->c_alias = $category->alias;
			}
			
			$results = $dispatcher->trigger('onPrepareItemDescription', array (& $item, & $par, 'item'));

			$item->event = new stdClass();
			$resultsAfterTitle = $dispatcher->trigger('onAfterDJClassifiedsDisplayTitle', array (&$item, & $par, 'item'));
			$item->event->afterDJClassifiedsDisplayTitle = trim(implode("\n", $resultsAfterTitle));
			
			$resultsBeforeContent = $dispatcher->trigger('onBeforeDJClassifiedsDisplayContent', array (&$item, & $par, 'item'));
			$item->event->beforeDJClassifiedsDisplayContent = trim(implode("\n", $resultsBeforeContent));
			
			$resultsAfterContent = $dispatcher->trigger('onAfterDJClassifiedsDisplayContent', array (&$item, & $par, 'item'));
			$item->event->afterDJClassifiedsDisplayContent = trim(implode("\n", $resultsAfterContent));
			
			$resultsAfterAuthorProfile = $dispatcher->trigger('onAfterDJClassifiedsDisplayAdvertAuthor', array (&$item, & $par, 'item'));
			$item->event->onAfterDJClassifiedsDisplayAdvertAuthor = trim(implode("\n", $resultsAfterAuthorProfile));

		$pathway->addItem($item->name);
		$this->assignRef('item',$item);
		$this->assignRef('item_images',$item_images);
		$this->assignRef('item_options',$item_options);
		$this->assignRef('fields',$fields);
		$this->assignRef('fields_contact',$fields_contact);
		$this->assignRef('country',$country);
		$this->assignRef('city',$city);
		$this->assignRef('address',$address);
		$this->assignRef('main_cat_id',$main_cat_id);
		$this->assignRef('item_payments',$item_payments);
		$this->assignRef('category',$category);
		$this->assignRef('profile',$profile);
		$this->assignRef('theme',$theme);
		$this->assignRef('bids',$bids);
		$this->assignRef('custom_ask_seller',$custom_ask_seller);
		
		
		if($warning18 && !isset($_COOKIE["djcf_warning18"])){
			$warning18_link='';
			if($par->get('restriction_18_art_id', 0)){
				require_once JPATH_SITE.'/components/com_content/helpers/route.php';
				$terms_article = $model->getTermsLink($par->get('restriction_18_art_id',0));
				if($terms_article){
					$slug = $terms_article->id.':'.$terms_article->alias;
					$cslug = $terms_article->catid.':'.$terms_article->c_alias;
					$warning18_link = ContentHelperRoute::getArticleRoute($slug,$cslug);
					if($par->get('restriction_18_art',0)==2){
						$warning18_link .='&tmpl=component';
					}
					$warning18_link = JRoute::_($warning18_link);
				}
			}
			$this->assignRef('terms_link',$warning18_link);
			parent::display('terms');			
		}else{				
        	parent::display($tpl);
		}
	}

}




