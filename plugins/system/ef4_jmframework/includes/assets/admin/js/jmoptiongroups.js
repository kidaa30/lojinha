/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

var JMOptionGroups = function(fieldName, controlName, value) {
	
	this.name = fieldName;
	this.value = value;
	this.control = controlName;
	
	this.initialise = function() {
		this.groups = jQuery('#'+this.name).find('option');
		
		var rel = jQuery('#'+this.name).attr('data-target') || false;
		
		this.related = (rel ? jQuery('#'+this.name+'_target') : false) || false;
		
		if ((this.groups.length == 0)) {
			return;
		}
		this.groupFields = [];
		this.groupNames = [];
		this.groups.each(function(index, el){
			var elements = el.value.split(';');
			
			if (elements.length > 0) {
				var value = elements[0];
				this.groupFields[value] = [];
				this.groupNames[index] = value;
				for (var i = 1; i < elements.length; i++) {
					this.groupFields[value][i-1] = elements[i];
					var inputId = jQuery('#'+this.control + '_' + this.groupFields[value][i-1]);
					var labelId = jQuery('#'+this.control + '_' + this.groupFields[value][i-1] + '-lbl');
					if (inputId) {
						inputId.parents('.control-group').css('display', 'none');
					} else if (labelId) {
						labelId.parents('.control-group').css('display', 'none');
					}
					
				}
			}
		}.bind(this));
		
		jQuery('#'+this.name).on('change', function(){
			this.setFields();
		}.bind(this)); 
		
		if (this.related){
			for (var i = 0; i < this.groupNames.length; i++) {
				var group = (this.groupNames[i]);
				for (var j = 0; j < this.groupFields[group].length; j++) {
					var inputId = jQuery('#'+this.control + '_' + this.groupFields[group][j]);
					if (inputId && inputId.hasClass('src-option')) {
						inputId.on('change', function(evt){
							if (jQuery(evt.target).val()) {
								this.related.val(jQuery(evt.target).val());
							}
						}.bind(this));
					}
				}
			}
		}
		
		jQuery('#'+this.name).trigger('change');
	};
	
	this.setFields = function() {
		var elements = jQuery('#' + this.name).val().split(';');
		var value = elements[0];
		for (var i = 0; i < this.groupNames.length; i++) {
			var group = (this.groupNames[i]);
			for (var j = 0; j < this.groupFields[group].length; j++) {
				var inputId = jQuery('#'+this.control + '_' + this.groupFields[group][j]);
				var labelId = jQuery('#'+this.control + '_' + this.groupFields[group][j] + '-lbl');
				if (group == value) {
					if (inputId) {
						if (inputId.hasClass('src-option') && inputId.val() && this.related) {
							this.related.val(inputId.val());
						}
						//inputId.attr('required', 'required');
						inputId.prop('required', true);
						inputId.parents('.control-group').css('display', '');
					} else if (labelId) {
						labelId.parents('.control-group').css('display', '');
					}
				} else {
					if (inputId) {
						//inputId.removeAttr('required');
						inputId.prop('required', false);
						inputId.parents('.control-group').css('display', 'none');
					} else if (labelId) {
						labelId.parents('.control-group').css('display', 'none');
					}
				}
			}
		}
	}
	
	this.initialise();
};