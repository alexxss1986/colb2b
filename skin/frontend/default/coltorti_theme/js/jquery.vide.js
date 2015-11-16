/**
 * @preserve Copyright 2011 Syd Lawrence ( www.sydlawrence.com ).
 * Version: 0.2
 *
 * Licensed under MIT and GPLv2.
 *
 * Usage: $('body').videoBG(options);
 *
 */

(function( jQuery ){

	jQuery.fn.videoBG = function( selector, options ) { 
	
		// if mobile safari
		if (jQuery.fn.isMobileSafari())
			return;
		
		// if doesn't support position fixed
		if (!jQuery.fn.canPositionFixed())
			return
		
		var options = {};
		if (typeof selector == "object") {
			options = jQuery.extend({}, jQuery.fn.videoBG.defaults, selector);
		}
		else if (!selector) {
			options = jQuery.fn.videoBG.defaults;
		}
		else {
			return jQuery(selector).videoBG(options);		
		}
		
		var container = jQuery(this);
		
		// check if elements available otherwise it will cause issues
		if (!container.length)
			return;
		
		// container to be at least relative
		if (container.css('position') == 'static' || !container.css('position'))
			container.css('position','relative');
		
		// we need a width
		if (options.width == 0)
			options.width = container.width();
		
		// we need a height
		if (options.height == 0)
			options.height = container.height();	
		
		// get the wrapper
		var wrap = jQuery.fn.videoBG.wrapper();
		wrap.height(options.height)
			.width(options.width);
		
		// if is a text replacement
		if (options.textReplacement) {
		
			// force sizes
			options.scale = true;
			
			// set sizes and forcing text out
			container.width(options.width)
				.height(options.height)
				.css('text-indent','-9999px');
		}
		else {
		
			// set the wrapper above the video
			wrap.css('z-index',options.zIndex+1);
		}
		
		// move the contents into the wrapper
		wrap.html(container.html());
		
		// get the video
		var video = jQuery.fn.videoBG.video(options);
		
		// if we are forcing width / height
		if (options.scale) {
			
			// overlay wrapper
			wrap.height(options.height)
				.width(options.width);
			
			// video
			video.height(options.height)
				.width(options.width);
		}
		
		// add it all to the container
		container.html(wrap);
		container.append(video);
		
		return this;
	}

	// get the formatted video element
	jQuery.fn.videoBG.video = function(options) {
		
		// video container
		var jQuerydiv = jQuery('<div/>');
		jQuerydiv.addClass('videoBG')
			.css('position',options.position)
			.css('z-index',options.zIndex)
			.css('top',0)
			.css('left',0)
			.css('height',options.height)
			.css('width',options.width)
			.css('opacity',options.opacity)
			.css('overflow','hidden');
		
		// video element
		var jQueryvideo = jQuery('<video/>');
		jQueryvideo.css('position','absolute')
			.css('z-index',options.zIndex)
			.attr('poster',options.poster)
			.css('top',0)
			.css('left',0)
			.css('min-width','100%')
			.css('min-height','100%');
		
		if (options.autoplay) {
			jQueryvideo.attr('autoplay',options.autoplay);
		}
			
		
		// video standard element
		var v = jQueryvideo[0];
		
		
		
		// if meant to loop
		if (options.loop) {
			loops_left = options.loop;
		
			// cant use the loop attribute as firefox doesnt support it
			jQueryvideo.bind('ended', function(){
				
				// if we have some loops to throw
				if (loops_left)
					// replay that bad boy
					v.play();
				
				// if not forever
				if (loops_left !== true)
					// one less loop
					loops_left--;
  			});
		}
		
		// when can play, play
		jQueryvideo.bind('canplay', function(){
			
			if (options.autoplay)
				// replay that bad boy
				v.play();
				
		});
		
		
		// if supports video
		if (jQuery.fn.videoBG.supportsVideo()) {

		  	// supports webm
		  	if (jQuery.fn.videoBG.supportType('webm')){
		  		
		  		// play webm
		  		jQueryvideo.attr('src',options.webm);
		  	}
		  	// supports mp4
		  	else if (jQuery.fn.videoBG.supportType('mp4')) {	  	
		  		
		  		// play mp4
		  		jQueryvideo.attr('src',options.mp4);
		  		
		  	//	jQueryvideo.html('<source src="'.options.mp4.'" />');
		  		
		  	}
		  	// throw ogv at it then
		  	else {
		  		
		  		// play ogv
		  		jQueryvideo.attr('src',options.ogv);
		  	}
	  	
	  	}
	  	
	  	
		
		// image for those that dont support the video	
		var jQueryimg = jQuery('<img/>');
		jQueryimg.attr('src',options.poster)
			.css('position','absolute')
			.css('z-index',options.zIndex)
			.css('top',0)
			.css('left',0)
			.css('min-width','100%')
			.css('min-height','100%');
		
		// add the image to the video
		// if suuports video
		if (jQuery.fn.videoBG.supportsVideo()) {
			// add the video to the wrapper
			jQuerydiv.html(jQueryvideo);
		}
		
		// nope - whoa old skool
		else {
			
			// add the image instead
			jQuerydiv.html(jQueryimg);
		}
		// am I really doing this again?
		if (options.scale) {
			jQuerydiv.css('height','100%').css('width','100%');
			jQueryvideo.css('height','100%').css('width','100%');
			jQueryimg.css('height','100%').css('width','100%');
		}
		
		// if text replacement
		if (options.textReplacement) {
	
			// force the heights and widths
			jQuerydiv.css('min-height',1).css('min-width',1);
			jQueryvideo.css('min-height',1).css('min-width',1);
			jQueryimg.css('min-height',1).css('min-width',1);
			
			jQuerydiv.height(options.height).width(options.width);
			jQueryvideo.height(options.height).width(options.width);
			jQueryimg.height(options.height).width(options.width);	
		}
		
		if (jQuery.fn.videoBG.supportsVideo()) {
			v.play();
		}
		return jQuerydiv;
	}
	
	// check if suuports video
	jQuery.fn.videoBG.supportsVideo = function() {
		return (document.createElement('video').canPlayType);
	}
	
	// check which type is supported
	jQuery.fn.videoBG.supportType = function(str) {
		
		// if not at all supported
		if (!jQuery.fn.videoBG.supportsVideo())
			return false;
		
		// create video
		var v = document.createElement('video');
		
		// check which?
		switch (str) {
			case 'webm' :
				return (v.canPlayType('video/webm; codecs="vp8, vorbis"'));
				break;
			case 'mp4' :
				return (v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"'));
				break;
			case 'ogv' :
				return (v.canPlayType('video/ogg; codecs="theora, vorbis"'));
				break;			
		}
		// nope
		return false;	
	}
	
	// get the overlay wrapper
	jQuery.fn.videoBG.wrapper = function() {
		var jQuerywrap = jQuery('<div/>');
		jQuerywrap.addClass('videoBG_wrapper')
			.css('position','absolute')
			.css('top',0)
			.css('left',0);
		return jQuerywrap;
	}
	
	// these are the defaults
	jQuery.fn.videoBG.defaults = {
			mp4:'',
			ogv:'',
			webm:'',
			poster:'',
			autoplay:true,
			loop:5,
			sclae:false,
			position:"absolute",
			opacity:1,
			textReplacement:false,
			zIndex:0,
			width:0,
			height:0
		}

})( jQuery );

jQuery.fn.isMobileSafari = function (){
	return (navigator.userAgent.match( /(iPod|iPhone|iPad)/ ) );
};

jQuery.fn.canPositionFixed = function (){
  var container = document.body;
  
  if (document.createElement && container && container.appendChild && container.removeChild) {
    var el = document.createElement('div');
    
    if (!el.getBoundingClientRect) return null;
        
    el.innerHTML = 'x';
    el.style.cssText = 'position:fixed;top:100px;';
    container.appendChild(el);

    var originalHeight = container.style.height,
        originalScrollTop = container.scrollTop;

    container.style.height = '3000px';
    container.scrollTop = 500;

    var elementTop = el.getBoundingClientRect().top;
    container.style.height = originalHeight;
    
    var isSupported = (elementTop === 100);
    container.removeChild(el);
    container.scrollTop = originalScrollTop;

    return isSupported;
  }
  return null;
}