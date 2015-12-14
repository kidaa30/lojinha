/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

//Set Module's Height script

function setModulesHeight() {
	var regexp = new RegExp("_mod([0-9]+)$");

	var jmmodules = jQuery(document).find('.jm-module') || [];
	if (jmmodules.length) {
		jmmodules.each(function(index,element){
			var match = regexp.exec(element.className) || [];
			if (match.length > 1) {
				var modHeight = parseInt(match[1]);
				jQuery(element).find('.jm-module-in').css('height', modHeight + 'px');
			}
		});
	}
}

//jQuery 'Back to Top' script

jQuery(document).ready(function(){
	
	setModulesHeight();
	
    // hide #jm-back-top first
    jQuery("#jm-back-top").hide();
    // fade in #jm-back-top
    jQuery(function () {
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 100) {
                jQuery('#jm-back-top').fadeIn();
            } else {
                jQuery('#jm-back-top').fadeOut();
            }
        });
        // scroll body to 0px on click
        jQuery('#jm-back-top a').click(function () {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
});


//Document Text Resizer script (May 14th, 08'): By JavaScript Kit: http://www.javascriptkit.com

var documenttextsizer = {

	prevcontrol : '', //remember last control clicked on/ selected
	existingclasses : '',

	setpageclass : function(control, newclass) {
		if (this.prevcontrol != '')
			this.css(this.prevcontrol, 'selectedtoggler', 'remove')
		//de-select previous control, by removing 'selectedtoggler' from it
		document.documentElement.className = this.existingclasses + ' ' + newclass//apply new class to document
		this.css(control, 'selectedtoggler', 'add')//select current control
		this.setCookie('pagesetting', newclass, 5)//remember new class added to document for 5 days
		this.prevcontrol = control
	},

	css : function(el, targetclass, action) {
		var needle = new RegExp("(^|\\s+)" + targetclass + "($|\\s+)", "ig")
		if (action == "check")
			return needle.test(el.className)
		else if (action == "remove")
			el.className = el.className.replace(needle, "")
		else if (action == "add")
			el.className += " " + targetclass
	},

	getCookie : function(Name) {
		var re = new RegExp(Name + "=[^;]+", "i");
		//construct RE to search for target name/value pair
		if (document.cookie.match(re))//if cookie found
			return document.cookie.match(re)[0].split("=")[1]
		//return its value
		return null
	},

	setCookie : function(name, value, days) {
		if ( typeof days != "undefined") {//if set persistent cookie
			var expireDate = new Date()
			var expstring = expireDate.setDate(expireDate.getDate() + days)
			document.cookie = name + "=" + value + "; path=/; expires=" + expireDate.toGMTString()
		} else//else if this is a session only cookie
			document.cookie = name + "=" + value
	},

	setup : function(targetclass) {
		this.existingclasses = document.documentElement.className//store existing CSS classes on HTML element, if any
		var persistedsetting = this.getCookie('pagesetting')
		var alllinks = document.getElementsByTagName("a")
		for (var i = 0; i < alllinks.length; i++) {
			if (this.css(alllinks[i], targetclass, "check")) {
				if (alllinks[i].getAttribute("rel") == persistedsetting)//if this control's rel attribute matches persisted doc CSS class name
					this.setpageclass(alllinks[i], alllinks[i].getAttribute("rel"))
				//apply persisted class to document
				alllinks[i].onclick = function() {
					documenttextsizer.setpageclass(this, this.getAttribute("rel"))
					return false
				}
			}
		}
	}
}

//jQuery Off-Canvas
var scrollsize;

jQuery(function() {
    // Toggle Nav on Click
    jQuery('.toggle-nav').click(function() {
    	// Get scroll size on offcanvas open
    	if(!jQuery('body').hasClass('off-canvas')) scrollsize = jQuery(window).scrollTop();
        // Calling a function
        toggleNav();
    });
});

function toggleNav() {
	var y = jQuery(window).scrollTop();
    if (jQuery('body').hasClass('off-canvas')) {
        // Do things on Nav Close
        jQuery('body').removeClass('off-canvas');
        setTimeout(function() {
	        jQuery('.sticky-bar #jm-top-bar').removeAttr('style');
	        jQuery('.dj-megamenu-sticky').removeAttr('style');
	        jQuery('html').removeClass('no-scroll').removeAttr('style');
	        jQuery(window).scrollTop(scrollsize);
        }, 300);
    } else {
        // Do things on Nav Open
        jQuery('body').addClass('off-canvas');
		jQuery('.sticky-bar #jm-top-bar').css({'position':'absolute','top':y});
		jQuery('.dj-megamenu-sticky').css({'position':'absolute','top':y});
        setTimeout(function() {
			jQuery('html').addClass('no-scroll').css('top',-y);
        }, 300);
    }
}

//search
jQuery(document).ready(function(){
  var searchTarget = jQuery('.search-ms');
	var header = jQuery('#jm-header');
  if (searchTarget.length > 0) {
		searchTarget.addClass('visible');
    var searchModule = searchTarget.find('.dj_cf_search');
    var searchModuleAdvanced = jQuery('.jm-advanced');
    var searchForm = searchModule.find('form');
    var searchCategorysuffix = jQuery('.search-ms.category-ms');
		
    if (searchModule.length > 0) {
			if (searchModuleAdvanced.length > 0) {
				searchTarget.addClass('advanced');
			}
			if (searchCategorysuffix.length > 0) {
				//without category
				var elems = searchForm.children('.search_radius, .search_regions, .search_ex_fields, .search_type, .search_time, .search_price, .search_only_images, .search_only_video, .search_also_18, .search_only_auctions');
			} else {
				//with category
				var elems = searchForm.children('.search_radius, .search_regions, .search_ex_fields, .search_type, .search_time, .search_price, .search_only_images, .search_only_video, .search_also_18, .search_only_auctions, .search_cats');
			}
			var wrapper = jQuery('<div class="search-wrapper clearfix" />');
			searchForm.append(wrapper);
			wrapper.append(elems);
			
			searchModuleAdvanced.click(function() {
				wrapper.slideToggle('fast');
				if(header.length > 0) {
					header.find('.slogan-ms .jm-text').slideToggle('fast');
				}
				searchModule.toggleClass('open');
				searchModuleAdvanced.toggleClass('open');
			});
    }
  }  
});

// Sticky Bar
jQuery(window).load(function(){
		
		// var searchTarget = jQuery('.search-ms.category-ms');
		// var isChosen = searchTarget.find('.chzn-container');
		// if(isChosen.length > 0) {
			// var categoryInput = searchTarget.find('.search_cats > .inputbox');
			// var categoryID = categoryInput.attr('id');
			
			// setTimeout(function() 
			// {
				// alert(categoryID);
				// categoryID.trigger("chosen:updated");
			// }, 5000);
			
		// }
	
    var resizeTimer;

    function resizeFunction() {
        var body = jQuery('body');
		var allpage = jQuery('#jm-allpage');
		  
		if(body.hasClass('sticky-bar')) {
		  var bar = allpage.find('#jm-top-bar');
	      if (bar.length > 0) {
		      var offset = bar.outerHeight();
		      allpage.css('padding-top', (offset) + 'px');
	      }
	    }

    };

    jQuery(window).resize(function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(resizeFunction, 50);
    });
    resizeFunction();
});