/**
 * @version $Id: item.js 42 2014-09-24 12:20:47Z szymon $
 * @package DJ-MediaTools
 * @subpackage DJ-MediaTools galleryGrid layout
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

!(function($){

var timer = null;

function centerImage(){
	
	clearTimeout(timer);
	
	var timer = setTimeout(function(){
	
		var wrapper = document.id('djmediatools').getElement('.dj-album-image');
		var image = wrapper.getElement('div,img');
		if(image) {
			var margin = (wrapper.getSize().y - image.getSize().y) / 2;
			image.set('tween',{duration:'short',transition:'expo:out'});
			if(margin > 0) {
				image.tween('margin-top', margin);
			} else {
				image.tween('margin-top', 0);
			}
		}
	}, 50);
}

window.addEvent('domready', function(){

	var uri = window.location+'';
	var pos = uri.lastIndexOf('=item&');
	if(pos < 0) pos = uri.lastIndexOf('/media/');
	pos += 1;
	
	if(window.parent != window) {
		window.parent.location.hash = uri.substr(pos);
	} else if(!Browser.ie || (Browser.ie && Browser.version >= 9)) { // redirect to open in modal window
		window.stop();
		window.location = $('djmediatools').getProperty('data-album-url') + '#' + uri.substr(pos);
	}
	
	$$('a:not(.dj-album-navi a)').each(function(item){
		item.setProperty('target','_parent');
	});
	$$('a[href^="http"]').each(function(item){
		item.setProperty('target','_blank');
	});
});

window.addEvent('load',function(){
	
	window.addEvent('resize',centerImage);
	window.fireEvent('resize');
	
});

window.addEvent('keydown',function(e){
	var navi = null;
	if(e.key == 'right') {
		var navi = $('djmediatools').getElement('a.dj-next');
	} else if(e.key == 'left') {
		var navi = $('djmediatools').getElement('a.dj-prev');
	}
	if(navi) {
		window.location = navi.getProperty('href');
	}
});

window.focus();

})(document.id);