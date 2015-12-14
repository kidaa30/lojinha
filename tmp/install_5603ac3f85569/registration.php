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
defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.plugin.plugin' );
jimport ( 'joomla.utilities.utility' );
if(!defined("DS")){ define('DS',DIRECTORY_SEPARATOR);}
require_once(JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djtheme.php');


class plgDJClassifiedsRegistration extends JPlugin {
	public function __construct(& $subject, $config) {
		parent::__construct ( $subject, $config );
		$this->loadLanguage ();
	}
	
	function onAdminFieldEditFields($field){
		$content = null;
		$st = '';
		if($field->source==2 || $field->id==0){
			if($field->id==0){ $st = ' style="display:none" '; }
			$content = '<div class="control-group" '.$st.' id="in_registration"><div class="control-label">'.JText::_('COM_DJCLASSIFIEDS_IN_REGISTRATION').'</div>';		 
				$content .= '<div class="controls"><input type="radio" name="in_registration" value="1"'; 
					if($field->in_registration==1){$content .= ' checked ';}				
					$content .= '/><span style="float:left;margin:2px 10px 0 5px;">'.JText::_('COM_DJCLASSIFIEDS_YES').'</span>';
					$content .= '<input type="radio" name="in_registration" value="0" ';
					if($field->in_registration==0){$content .= ' checked ';}
					$content .= '/><span style="float:left;margin:2px 10px 0 5px;">'.JText::_('COM_DJCLASSIFIEDS_NO').'</span>';
				$content .= '</div>
			</div>';
		}

		return $content;
	}
	
	function onAdminFieldEditJSSourceType($field,$source){
		if($source==2){
			$content = 'document.id("in_registration").setStyle("display","");';
		}else{
			$content = 'document.id("in_registration").setStyle("display","none");'; 
		}
		return  $content;
	}
	
	function onUserRegistrationForm(){
		$content  = null;
		$art_id = $this->params->get('terms_of_use_art_id',0);
		if($this->params->get('terms_of_use',0) && $art_id){

			$db= JFactory::getDBO();
			$query = "SELECT a.id, a.alias, a.catid, c.alias as c_alias FROM #__content a "
					."LEFT JOIN #__categories c ON c.id=a.catid "
					."WHERE a.state=1 AND a.id=".$art_id;
				
			$db->setQuery($query);
			$terms_article=$db->loadObject();
							
			if($terms_article){					
				require_once JPATH_SITE.'/components/com_content/helpers/route.php';
				$slug = $terms_article->id.':'.$terms_article->alias;
				$cslug = $terms_article->catid.':'.$terms_article->c_alias;
				$article_link = ContentHelperRoute::getArticleRoute($slug,$cslug);					
				$terms_link = JRoute::_($article_link);					
				$content = '<div class="djform_row terms_and_conditions">
					<label class="label">&nbsp;</label>			
	                <div class="djform_field">
						<fieldset id="terms_and_conditions" class="checkboxes required">
							<input type="checkbox" name="terms_and_conditions" id="terms_and_conditions0" value="1" class="inputbox" />
									
							<label class="label_terms" for="terms_and_conditions" id="terms_and_conditions-lbl" >'.JText::_('COM_DJCLASSIFIEDS_I_AGREE_TO_THE').' </label>
							<a href="'.$terms_link.'" target="_blank">'.JText::_('COM_DJCLASSIFIEDS_TERMS_AND_CONDITIONS').'</a>
						</fieldset>												
	                </div>
	                <div class="clear_both"></div>
	            </div> ';																														            				
			}

		}		
		return $content;
	}
	
	
}


