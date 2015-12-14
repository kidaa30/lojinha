<?php
/**
 * @version $Id: djreviewsratinggroup.php 8 2014-10-14 10:13:30Z michal $
 * @package DJ-Reviews
 * @copyright Copyright (C) 2014 DJ-Extensions.com LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 *
 * DJ-Reviews is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Reviews is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Reviews. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html');
jimport('joomla.form.formfield');


class JFormFieldDJReviewsRatingGroup extends JFormField {

	protected $type = 'DJReviewsRatingGroup';

	protected function getInput()
	{
		$readonly = false;
		if (!empty($this->element['readonly']) && $this->element['readonly'] == 'true') {
			$readonly = true;
			$this->element['class'] = $this->element['class'].' readonly';
		}
		
		$attr = '';
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->required ? ' required="true" aria-required="true"' : '';
		

		$user = JFactory::getUser();
		$db	= JFactory::getDBO();

		$query = "SELECT * FROM #__djrevs_rating_groups ORDER BY name ASC";

		$db->setQuery($query);
		$groups = $db->loadObjectList();

		$options = array();
		$text_value = '';
		$id_value = 0;
		$options[] = JHTML::_('select.option', '','- '.JText::_('COM_DJREVIEWS_SELECT_GROUP').' -' );
		foreach($groups as $group){
			if ($group->id == $this->value) {
				$text_value = $group->name;
				$id_value = $group->id;
			}
			$options[] = JHTML::_('select.option', $group->id, $group->name);

		}
		$out = '';
		if ($readonly && $this->value > 0) {
			$out = '<input type="text" '.trim($attr).' value="'.$text_value.'" id="'.$this->id.'" readonly="readonly" />';
			$out .= '<input type="hidden" name="'.$this->name.'" value="'.$id_value.'"/>';
		} else {
			$out = JHTML::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
		}
		
		return $out;
	}
}