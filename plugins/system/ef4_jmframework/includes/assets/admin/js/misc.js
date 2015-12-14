/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

// adding the span tag to the specified field class
window.addEvent('domready', function(){
	$$('.unit-px').each(function(el){
		el.getParent().innerHTML = el.getParent().innerHTML + "<span class=\"unit\">px</span>";
	});
	$$('.unit-percent').each(function(el){
		el.getParent().innerHTML = el.getParent().innerHTML + "<span class=\"unit\">%</span>";
	});
});