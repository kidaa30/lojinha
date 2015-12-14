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

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$params = JComponentHelper::getParams('com_djclassifieds');

$net_prices = (bool)((int)$params->get('price_including_tax', 1) == 0);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'shipping.cancel' || document.formvalidator.isValid(document.id('edit-form'))) {
			Joomla.submitform(task, document.getElementById('edit-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_djclassifieds&view=shipping&layout=edit&id='.(int) $this->item->id); ?>"
	method="post" name="adminForm" id="edit-form" class="form-validate"
	enctype="multipart/form-data">
	<fieldset>
		<legend>
		<?php echo empty($this->item->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?>
		</legend>
		<div class="row-fluid">
			<div class="span12 form-horizontal">
			
				<ul class="nav nav-tabs">
					<li class="active"><a href="#details" data-toggle="tab"><?php echo empty($this->item->id) ? JText::_('COM_DJCLASSIFIEDS_NEW') : JText::_('COM_DJCLASSIFIEDS_EDIT'); ?></a></li>
					<?php /*?><li ><a href="#params" data-toggle="tab"><?php echo JText::_('COM_DJCLASSIFIEDS_PARAMS'); ?></a></li><?php */?>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="details">
						<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('name'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('name'); ?>
						</div>
					</div>
	
					<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('id'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('id'); ?>
						</div>
					</div>
	
					<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('plugin'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('plugin'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('price'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('price'); ?>
						</div>
					</div>					
					<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('published'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('published'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<div class="control-label">
						<?php echo $this->form->getLabel('description'); ?>
						</div>
						<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
						</div>
					</div>
					</div>
				
				<div class="tab-pane" id="params">
				<?php if (empty($this->item->plugin)) {
					echo JText::_('COM_DJCLASSIFIEDS_PLUGIN_SAVE_FIRST');
				} else {
					$fieldSets = $this->form->getFieldsets('params');
					if (empty($fieldSets)) {
						echo JText::_('COM_DJCLASSIFIEDS_PLUGIN_EMPTY_CONFIG');
					} else {
						foreach ($fieldSets as $name => $fieldSet) {
							if (isset($fieldSet->description) && trim($fieldSet->description)) {
								echo '<p class="alert alert-info">'.$this->escape(JText::_($fieldSet->description)).'</p>';
							}
							?>
							<?php foreach ($this->form->getFieldset($name) as $field) { ?>
								<div class="control-group">
									<div class="control-label"><?php echo $field->label; ?></div>
									<div class="controls"><?php echo $field->input; ?></div>
								</div>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="clr"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script type="text/javascript">
function djValidatePrice(priceInput) {
	//var r = new RegExp("\,", "i");
	//var t = new RegExp("[^0-9\,\.]+", "i");
	//priceInput.setProperty('value', priceInput.getProperty('value').replace(r, "."));
	//priceInput.setProperty('value', priceInput.getProperty('value').replace(t, ""));


	var price = priceInput.getProperty('value');
	
	// valid format
	var valid_price = new RegExp(/^(\d+|\d+\.\d+)$/);
	
	// comma instead of dot
	var wrong_decimal = new RegExp(/\,/g);
	
	// non allowed characters
	var restricted = new RegExp(/[^\d+\.]/g);
	
	// replace comma with a dot
	price = price.replace(wrong_decimal, ".");
	
	if (valid_price.test(price) == false) {
		// remove illegal chars
		price = price.replace(restricted, '');
	}
	
	if (valid_price.test(price) == false) {
		// too many dots in here
		parts = price.split('.');
		if (parts.length > 2 ) {
			price = parts[0] + '.' + parts[1];
		}
	}
	
	priceInput.setProperty('value', price);
	
	taxInput = document.id(priceInput.getProperty('id') + '_tax');
	if(!taxInput) {
		return;
	}
	
	rateInput = document.id('jform_tax_rate_id');
	
	if (!rateInput.length) {
		return;
	}
	
	var inputType = taxInput.getProperty('data-type');
	var taxRateOption = rateInput.getSelected().get('text')[0];
	
	parser = new RegExp(/.*\[(.+)\]$/);
	
	if (parser.test(taxRateOption)) {
		taxRate = parseFloat(parser.exec(taxRateOption)[1]);
		if (inputType == 'gross') {
			djPriceFromGross(taxInput, price, taxRate);
		} else if (inputType == 'net') {
			djPriceFromNet(taxInput, price, taxRate);
		}
	}
}

function djPriceFromGross(element, price, taxrate) {
	price = parseFloat(price);
	taxrate = parseFloat(taxrate);
	if (!price || !(taxrate >= 0)) {
		element.value = '';
		return;
}

var netPrice = (price * 100) / (100 + taxrate);
	element.value = netPrice.toFixed(2);
}

function djPriceFromNet(element, price, taxrate) {
	price = parseFloat(price);
	taxrate = parseFloat(taxrate);
	
	if (!price || !(taxrate >= 0)) {
		element.value = '';
		return;
	}
	
	var grossPrice = price * ((100 + taxrate)/100) ;
	element.value = grossPrice.toFixed(2);
}


window.addEvent('domready', function() {
	var djItemPriceInput = document.id('jform_price');
	djItemPriceInput.addEvents({
		'keyup' : function(e){djValidatePrice(djItemPriceInput);},
		'change' : function(e){djValidatePrice(djItemPriceInput);},
		'click' : function(e){djValidatePrice(djItemPriceInput);}
	});
	
	if (document.id('jform_tax_rate_id')) {
		document.id('jform_tax_rate_id').onchange = (function(evt) {
			if (typeof(jQuery) != 'undefined') {
				jQuery('#jform_tax_rate_id').trigger("liszt:updated");
			}
			djValidatePrice(djItemPriceInput);
		});
		
		document.id('jform_tax_rate_id').onchange();
	}
});
</script>
