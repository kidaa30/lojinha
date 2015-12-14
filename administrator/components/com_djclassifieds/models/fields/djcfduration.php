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

class JFormFieldDjcfduration extends JFormField {
	
	protected $type = 'Djcduration';
	
	protected function getInput()
	{
		$html = array();

		// Initialize some field attributes.
		$class     = !empty($this->class) ? ' class="radio ' . $this->class . '"' : ' class="radio"';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$readonly  = $this->readonly;

		// Start the radio field output.
		//$html[] = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . $disabled . ' >';

		// Get the field options.
		$options = $this->getOptions();

		$attr = '';
		
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->element['multiple']=='true' ? ' multiple="multiple"' : '';
		
		$html = JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value);
		
		return $html;
	}
	
	protected function getOptions()
	{
		$options = array();
		
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__djcf_days where published = 1 order by days asc');
		$rows = $db->loadObjectList('id');

		foreach ($rows as $option)
		{
			$disabled = false;
			
			// Create a new option object based on the <option /> element.
			if($option->days==0){
				$option->days = JText::_('COM_DJCLASSIFIEDS_UNLIMITED');
			}
			$tmp = JHtml::_(
				'select.option', (string) $option->id, trim((string) $option->days), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = 'djcf_duration';
			
			//$tmp->shippment = $option->shipping_details;

			// Set some JavaScript option attributes.
			//$tmp->onclick = (string) $option['onclick'];
			//$tmp->onchange = (string) $option['onchange'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
?>