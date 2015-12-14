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

class JFormFieldDjcfshipping extends JFormField {
	
	protected $type = 'Djcfshipping';
	
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
		$html[] = '<fieldset id="' . $this->id . '"' . $class . $required . $autofocus . $disabled . ' >';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option)
		{
			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="' . $option->class . '"' : '';

			$disabled = !empty($option->disable) || ($readonly && !$checked);

			$disabled = $disabled ? ' disabled' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';
			$onchange = !empty($option->onchange) ? ' onchange="' . $option->onchange . '"' : '';

			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '" value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $required . $onclick
				. $onchange . $disabled . ' data-shippment="'.(int)$option->shippment.'" />';

			$html[] = '<label for="' . $this->id . $i . '"' . $class . ' >'
				. JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';

			$required = '';
		}

		// End the radio field output.
		$html[] = '</fieldset>';
		
		return implode($html);
	}
	
	protected function getOptions()
	{
		$options = array();
		
		$db = JFactory::getDbo();
		$db->setQuery('select * from #__djcf_shippings where published = 1 order by ordering asc');
		$rows = $db->loadObjectList('id');

		foreach ($rows as $option)
		{
			$disabled = false;
			
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option->id, trim((string) $option->name), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = 'djc_delivery_method';
			
			$tmp->shippment = $option->shipping_details;

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