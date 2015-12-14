
/**
 * @version $Id: album.js 46 2014-12-11 15:14:48Z szymon $
 * @package DJ-MediaTools
 * @subpackage DJ-MediaTools galleryGrid layout
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

function startUpload(up,files) {
	
	//up.settings.buttons.start = false;
	up.start();
	//console.log(up);
}
function injectUploaded(up,file,info) {
	
	var response = JSON.decode(info.response); 
	if(response.error) {
		//console.log(file.status);
		file.status = plupload.FAILED;
		file.name += ' - ' + response.error.message;
		document.id(file.id).addClass('ui-state-error');
		document.id(file.id).getElement('td.plupload_file_name').appendText(' - ' + response.error.message);
		//up.removeFile(file);
		return false;
	}
	
	var html = '<img src="../tmp/djupload/'+file.target_name+'" alt="'+file.name+'" />';
	html += '	<div class="itemMask">';
	html += '	<input type="hidden" name="item_id[]" value="0">';
	html += '	<input type="hidden" name="item_image[]" value="'+file.target_name+';'+file.name+'">';
	html += '	<input type="text" class="itemInput editTitle" name="item_title[]" value="'+stripExt(file.name)+'">';
	html += '	<span class="delBtn"></span></div>';
	var item = new Element('div',{'class':'albumItem', html: html});
	initItemEvents(item);
	// add uploaded image to the list and make it sortable
	item.inject(document.id('albumItems'), 'bottom');
	this.album.addItems(item);
	
	return true;
}

window.injectAlbumVideo = function injectVideo(video) {
	
	var html = '<img src="'+video.thumbnail+'" alt="'+video.title+'" />';
	html += '	<span class="video-icon"></span>';
	html += '	<div class="itemMask">';
	html += '	<input type="hidden" name="item_id[]" value="0" />';
	html += '	<input type="hidden" name="item_image[]" value="'+video.thumbnail+';;'+video.embed+'" />';
	html += '	<input type="text" class="itemInput editTitle" name="item_title[]" value="'+video.title+'" />';
	html += '	<span class="delBtn"></span></div>';
	var item = new Element('div',{'class':'albumItem', html: html});
	initItemEvents(item);
	// add video to the list and make it sortable
	item.inject(document.id('albumItems'), 'bottom');
	this.album.addItems(item);
	
};

function initItemEvents(item) {
	
	if(!item) return;
	item.getElement('.delBtn').addEvent('click',function(){
		item.set('tween',{duration:'short',transition:'expo:out'});
		item.tween('width',0);
		(function(){item.dispose();}).delay(250);
		this.deleted = item;
	});
	item.getElements('input').each(function(input){
		input.addEvent('focus',function(){
			item.addClass('active');
		});
		input.addEvent('blur',function(){
			item.removeClass('active');
		});
	});
}

function stripExt(filename) {
	
	var pattern = /\.[^.]+$/;
	return filename.replace(pattern, "");	
}

window.addEvent('domready', function(){

	this.album = new Sortables('albumItems',{
		clone: true,
		revert: {duration:'short',transition:'expo:out'},
		opacity: 0.3
	});
	
	$$('.albumItem').each(function(item){
		initItemEvents(item);
	});
});