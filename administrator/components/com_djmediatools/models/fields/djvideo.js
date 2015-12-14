/**
 * @version $Id: djvideo.js 52 2015-04-03 13:04:09Z szymon $
 * @package DJ-MediaTools
 * @subpackage DJ-MediaTools galleryGrid layout
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license DJ-Extensions.com Proprietary Use License
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Szymon Woronowski - szymon.woronowski@design-joomla.eu
 *
 */

function parseVideo(video_id, image, callback) {
				
	var videoField = document.id(video_id);
	var loader = new Element('img', { src: 'components/com_djmediatools/assets/ajax-loader.gif', 'class': 'ajax-loader' });
	var imageField = document.id(image);
	var preview = document.id(videoField.get('id')+'_preview');
	videoField.blur();
	preview.empty();
	
	var videoRequest = new Request({
		url: 'index.php?option=com_djmediatools&view=item&tmpl=component',
		method: 'post',
		data: 'task=getvideo&video='+encodeURIComponent(videoField.value),
		onRequest: function(){
			loader.inject(videoField, 'after');
		},
		onSuccess: function(responseText){
			loader.dispose();
			if(responseText) {
				var video = JSON.decode(responseText);
				//console.log(video);
				if(!video.error){
					videoField.value = video.embed;
					// put video preview
					new Element('iframe', { src: video.embed.replace('autoplay=1',''), height: 180, width: 288, frameborder: 0, allowfullscreen: ''}).inject(preview);
					// if callback function is set pass the video object, otherwise do default action
					if(callback) {
						callback(video);
					} else {
						if(imageField && (!imageField.get('value') || confirm(COM_DJMEDIATOOLS_CONFIRM_UPDATE_IMAGE_FIELD))) {
							imageField.value = video.thumbnail;
							// set thumbnail preview
							new Element('img', { src: video.thumbnail, 'style': 'height: 180px;' }).inject(preview, 'bottom');
						}
					}
				} else {
					videoField.value = '';
	        		alert(video.error);
	        	}
			}
		},
		onFailure: function(){
			loader.dispose();
			alert('connection error');
		}
	});
	
	videoRequest.send();
}