/**
 * @version $Id: djoptiongroups.js 4 2012-12-06 13:48:32Z szymon $
 * @package DJ-MediaTools
 * @subpackage DJ-MediaTools galleryGrid layout
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

var OptionGroups = new Class({
	
	name: null,
	value: null,
	control: null,
	
	initialize: function(fieldName, controlName, value) {
		this.name = fieldName;
		this.value = value;
		this.control = controlName;
		
		this.groups = document.id(this.name).getElements('option');
		
		if ((this.groups.length == 0)) {
			return;
		}
		
		this.groupFields = [];
		this.groupNames = [];
		this.groups.each(function(el, index){
			var elements = el.value.split(';');
			if (elements.length > 0) {
				var value = elements[0];
				this.groupFields[value] = [];
				this.groupNames[index] = value;
				for (var i = 1; i < elements.length; i++) {
					this.groupFields[value][i-1] = elements[i];
					//var label = document.id(this.control + '_' + this.groupFields[value][i-1] + '-lbl');
					//var input = document.id(this.control + '_' + this.groupFields[value][i-1]);
					var inputId = document.id(this.control + '_' + this.groupFields[value][i-1]);
					var labelId = document.id(this.control + '_' + this.groupFields[value][i-1] + '-lbl');
					if (inputId) {
						inputId.getParent('li, .control-group').setStyle('display', 'none');
					} else if (labelId) {
						labelId.getParent('li, .control-group').setStyle('display', 'none');
					}
				}
			}
		}.bind(this));
		
		document.id(this.name).getParent('li, .control-group').addEvent('click', function(evt){
			var elements = document.id(this.name).value.split(';');
			var value = elements[0];
			for (var i = 0; i < this.groupNames.length; i++) {
				var group = (this.groupNames[i]);
				for (var j = 0; j < this.groupFields[group].length; j++) {
					//var label = document.id(this.control + '_' + this.groupFields[group][j] + '-lbl');
					//var input = document.id(this.control + '_' + this.groupFields[group][j]);
					var inputId = document.id(this.control + '_' + this.groupFields[group][j]);
					var labelId = document.id(this.control + '_' + this.groupFields[group][j] + '-lbl');
					//console.log(group);
					if (group == value) {
						if (inputId) {
							inputId.getParent('li, .control-group').setStyle('display', 'block');
						} else if (labelId) {
							labelId.getParent('li, .control-group').setStyle('display', 'block');
						}
					} else {
						if (inputId) {
							inputId.getParent('li, .control-group').setStyle('display', 'none');
						} else if (labelId) {
							labelId.getParent('li, .control-group').setStyle('display', 'none');
						}
					}
				}
			}
		}.bind(this)); 
		
		document.id(this.name).getParent('li, .control-group').fireEvent('click',document.id(this.name));
	}	
});