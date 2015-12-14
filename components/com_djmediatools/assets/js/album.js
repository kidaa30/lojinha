/**
 * @version $Id: item.js 16 2013-07-30 09:59:57Z szymon $
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

window.addEvent('load',function(){
	var hash = window.location.hash.substr(1);
	var fb = hash.indexOf('fb_comment_id');
	if(fb > -1) {
		hash = hash.substring(0,fb-1).replace(/%3A/g,':');		
	}
	//console.log(hash);
	if(hash) {
		var link = $(document.body).getElement('a.djmodal[href$="'+hash+'"]');
		if(link) {
			link.fireEvent('click');
		} else {
			var pos = hash.lastIndexOf('&id=');
			if(pos < 0) pos = hash.lastIndexOf('/');
			//console.log(hash.substr(0, pos));
			link = $(document.body).getElement('a.djmodal[href*="'+hash.substr(0, pos)+'"]');
			if(link) {
				var url = link.getProperty('href');
				var pos2 = url.lastIndexOf('&id=');								
				if(pos2 < 0) pos2 = url.lastIndexOf('/');
				link.setProperty('href', url.substr(0,pos2)+hash.substr(pos));
				link.fireEvent('click');
				link.setProperty('href', url);
			}
		}
	}
});

})(document.id);