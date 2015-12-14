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

defined('_JEXEC') or die();
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
if(!defined("DS")){
	define('DS',DIRECTORY_SEPARATOR);
}

//require_once(JPATH_COMPONENT.DS.'lib'.DS.'djcategory.php');
require_once(dirname(__FILE__).DS.'..'.DS.'..'.DS.'lib'.DS.'djcategory.php');

class JFormFieldDjcfcategorySingle extends JFormField {
	
	protected $type = 'Djcfcategorysingle';
	
	protected function getInput()
	{
		
		$language = JFactory::getLanguage();	
		$c_lang = $language->getTag();
		if($c_lang=='pl-PL' || $c_lang=='en-GB'){
			$language->load('com_djclassifieds', JPATH_SITE.'/components/com_djclassifieds', null, true);	
		}else{
			$language->load('com_djclassifieds', JPATH_SITE, null, true);	
		}		
		
		$attr = ''; 

		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->element['multiple']=='true' ? ' multiple="multiple"' : '';
		
		$default_name = ($this->element['default_name']) ? '- '.JText::_($this->element['default_name']).' -':null;
			$optionss = array();
			$optionss=DJClassifiedsCategory::getCatSelect();						
							
			$main_tab = array();
			$main_tab[0]= JHTML::_('select.option', '0', JText::_('COM_DJCLASSIFIEDS_ALL_CATEGORIES'));
			$options = array();
			$options = array_merge_recursive ($main_tab, $optionss);
		$html = JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value);
		
		return ($html);
		
	}
}
?>