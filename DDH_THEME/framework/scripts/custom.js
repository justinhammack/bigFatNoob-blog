jQuery.noConflict();


/* R-Player
 ------------------------------------------------------------------------*/
var active_player = null;

/* Grab a handle to r-player */
function getSwfReference(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName]
	} else {
		return document[movieName]
	}
}

function stop_music() {
	jQuery('#badge_'+active_player).css('display', 'none');
	getSwfReference(active_player).stop_song();
}

function play_music(id) {
	jQuery('#badge_'+id).css('display', 'block');
	if (active_player != null && active_player != id) {
		stop_music();
	}
	active_player = id;
}

function remove_badge(id) {
	jQuery('#badge_'+active_player).css('display', 'none');
}

/* YouTube Slider API */
var yplayers = new Object;
function onYouTubePlayerReady(player_id) {
	var id = player_id;
	var obj = document.getElementById(player_id);
    yplayers[id] = obj;
} 

/* Vimeo Slider API */
var vplayers = new Object;
function vimeo_player_loaded(player_id) {
    var id = player_id;
	var obj = document.getElementById(player_id);
    vplayers[id] = obj;
}


/* jQuery
 ------------------------------------------------------------------------*/
jQuery(document).ready(function () {
		
		
	 /* Sound Manager
  	  ------------------------------------------------------------------------*/
	  if (window.soundManager) {
		  soundManager.url = theme_vars.swf_path;
		  soundManager.HTML5Audio = true;
		  soundManager.flashVersion = 8; // optional: shiny features (default = 8)
		  soundManager.flashLoadTimeout = 1000;
	  }
	  
     /* Add target attributes to 'a' element */
	 jQuery('a.target-blank').attr('target', '_blank')
	 
	 
	/* Tooltip
  	 ------------------------------------------------------------------------*/
	
	/* Image Tooltip */
	if (jQuery.fn.RImageTooltip) { 
		jQuery('.image-tooltip').RImageTooltip({
			'offset_y' : 55
		})
	}
	
	/* Bookmarks Tooltip */
	if (jQuery.fn.RTextTooltip) {
		jQuery('.bookmarks-tip').RTextTooltip({
			'offset_y' : 40
		})
	}
	
	/* Text Tooltip */
	if (jQuery.fn.RTextTooltip) {
		jQuery('.text-tip').RTextTooltip({
			'offset_y' : 40
		})
	}
	
	/* Music Portfolio
  	 ------------------------------------------------------------------------*/
    jQuery('.release-image a').each(
	
		function () {
			var image_path = jQuery(this).attr('rev'),
				l = jQuery(this),
				a = l.parent(),
				img = new Image();
	
			jQuery(img).css('opacity', '0.0').load(
	
			function () {
				l.append(this);
				jQuery(this).css('opacity', '0.0').animate({
					opacity: 1.0
				}, 800, function () {
					l.removeAttr('rev').css('background-image', 'none');
					var player = a.find('.release-content');
					var title = a.find('h2');
					a.bind({
						mouseenter: function () {
							player.stop().animate({bottom: 0}, 400);
						}, mouseleave: function () {
							player.stop().animate({bottom: -70}, 400);
						}
					});
				});
			}).attr({
				'src': image_path
	
			});
    });
	
	
	/* Portfolio 2 Slide effect
  	 ------------------------------------------------------------------------*/
	jQuery('.portfolio2-image').bind({ 
	    mouseenter: function () { 
	        jQuery('.caption p', this).slideDown(400); 
	    }, 
	        mouseleave: function () { 
			jQuery('.caption p', this).slideUp(400); 
	  }
    });
	
	
	/* Theme menu
  	 ------------------------------------------------------------------------*/
	if (jQuery.fn.RMenu) { 
		jQuery('ul#menu').RMenu({
								sub_menu_width	 	: parseInt(jQuery('#menu ul').css('width')),
								menu_height 		: 45, // Menu height px
								fade_effect 		: 'true'
								});
	
		/* IE hack */
		if (jQuery.browser.msie && jQuery.browser.version < '9.0') jQuery('ul#menu li li:last-child').css('border-bottom', 'none');
		//if (jQuery.browser.msie && jQuery.browser.version == '7.0') jQuery('ul#menu').css('width', jQuery('ul#menu').width()+'px');
		
	}

	
	/* Sliders
	------------------------------------------------------------------------*/
	if (jQuery.fn.RSlider) {
		
		
		/* Homepage Slider
		 ------------------------------------------------------------------------*/
		jQuery('#homepage-slider').RSlider({
									   delay			: jQuery('#homepage-slider').data('delay')*1000,
									   duration			: jQuery('#homepage-slider').data('duration'),
									   height			: jQuery('#homepage-slider').data('height'),
									   width			: jQuery('#homepage-slider').data('width'),
									   slices			: 4,
									   easing			: jQuery('#homepage-slider').data('easing'),
									   effect			: 'fade',
									   anim_start		: function(prev, curr, prev_nr, curr_nr) {
															
															var music_id = jQuery('.rs-music a', prev).attr('id');
															if (music_id != undefined && window.soundManager) {
																soundManager.pause(music_id);
																return false;
															}
															var youtube_id = jQuery('.rs-image .youtube-player', prev).attr('id');
															if (youtube_id != undefined && yplayers[youtube_id] != undefined) {
																yplayers[youtube_id].pauseVideo();
																return false;
															}
															var vimeo_id = jQuery('.rs-image .vimeo-player', prev).attr('id');
															if (vimeo_id != undefined && vplayers[vimeo_id] != undefined) {
																vplayers[vimeo_id].api_pause();
																return false;
															}
										   
														  }
									   });
		
		
		/* Intro Slider
		 ------------------------------------------------------------------------*/
		jQuery('#intro-slider').RSlider({
									   delay			: jQuery('#intro-slider').data('delay')*1000,
									   duration			: jQuery('#intro-slider').data('duration'),
									   height			: jQuery('#intro-slider').data('height'),
									   width			: jQuery('#intro-slider').data('width'),
									   slices			: 5,
									   easing			: jQuery('#intro-slider').data('easing'),
									   effect			: 'fade'					 
									   });
		
		
		/* Recent Slider
		 ------------------------------------------------------------------------*/
		jQuery('#recent-slider').RSlider({
									   delay			: jQuery('#recent-slider').data('delay')*1000,
									   duration			: jQuery('#recent-slider').data('duration'),
									   height			: jQuery('#recent-slider').data('height'),
									   width			: jQuery('#recent-slider').data('width'),
									   slices			: 5,
									   easing			: jQuery('#recent-slider').data('easing'),
									   effect			: jQuery('#recent-slider').data('effect'),
									   anim_start		: function(prev, curr, prev_nr, curr_nr) {
															
															 var music_id = jQuery('.rs-recent-music a', prev).attr('id');
															 if (music_id != undefined && window.soundManager) {
																 soundManager.pause(music_id);
																 return false;
															  }
														   }
									   });
		
	
		/* Display sliders navigation */
		jQuery('.navigation .rs-nav').css({opacity : 0, display : 'block'});
		jQuery('.navigation .rs-timer').css({opacity : 0, display : 'block'});
		jQuery('.navigation').hover(function(){
			jQuery('.rs-timer',this).stop().animate({ opacity : 1 }, { queue: false, duration: 450});
			jQuery('.rs-nav',this).stop().animate({ opacity : 1 }, { queue: false, duration: 450});
			jQuery('.rs-next',this).stop().animate({ right : '10px'}, { queue: false, duration: 450, easing: 'easeOutQuint' });
			jQuery('.rs-prev',this).stop().animate({ left : '10px'}, { queue: false, duration: 450, easing: 'easeOutQuint' })
		}, function(){
			var nav_width = jQuery('.rs-next',this).width();
			jQuery('.rs-timer',this).stop().animate({ opacity : 0 }, { queue: false, duration: 450});
			jQuery('.rs-nav',this).stop().animate({ opacity : 0 }, { queue: false, duration: 450});
			jQuery('.rs-next',this).stop().animate({ right : '-'+nav_width+'px'}, { queue: false, duration: 450, easing: 'easeOutQuint' });
			jQuery('.rs-prev',this).stop().animate({ left : '-'+nav_width+'px'}, { queue: false, duration: 450, easing: 'easeOutQuint' })
		})
	
	}
	
	/* Top Button
  	 ------------------------------------------------------------------------*/
    jQuery('a.top').click(function (e) {
        e.preventDefault();
        var target = (window.opera) ? (document.compatMode == 'CSS1Compat' ? jQuery('html') : jQuery('body')) : jQuery('html,body');
        target.animate({
            scrollTop: jQuery(jQuery(this).attr('href')).offset().top
        }, 500);
    });
	
	
	/* Social icons
  	 ------------------------------------------------------------------------*/
    jQuery('#social a span').css('opacity', '0')
    jQuery('#social a').hover(

    function () {
        jQuery('span', this).stop().animate({
            opacity: 1
        }, 400);
    }, function () {
        jQuery('span', this).stop().animate({
            opacity: 0
        }, 400);
    })
	
	
 	/* Prettyphoto
  	 ------------------------------------------------------------------------*/	 
	
    /* Social markup */ 
	var pp_social_tools = '<div class="pp_social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=24" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:24px;" allowTransparency="true"></iframe></div></div>';
	
	var lightbox = false;
	
	/* Lightbox function */
	function lightbox_init() {
		var lightbox = true;
		
		if (theme_vars.lightbox_deeplinking == undefined || theme_vars.lightbox_deeplinking == 'off') theme_vars.lightbox_deeplinking = false;
		else theme_vars.lightbox_deeplinking = true;
		
		if (theme_vars.lightbox_social_tools == undefined || theme_vars.lightbox_social_tools == 'off') pp_social_tools = false;
		
		if (theme_vars.lightbox_gallery == undefined || theme_vars.lightbox_gallery == 'off') theme_vars.lightbox_gallery = false;
		else theme_vars.lightbox_gallery = true;
		
		if (theme_vars.lightbox_style == undefined) theme_vars.lightbox_style = 'pp_default';
		
		jQuery('a[data-gal^="lightbox"]').prettyPhoto({
			show_title: true,
			overlay_gallery: theme_vars.lightbox_gallery,
			deeplinking: theme_vars.lightbox_deeplinking,
			default_width: 600,
			default_height: 360,
			theme: theme_vars.lightbox_style, /* pp_default / facebook / light_rounded / dark_rounded / light_square / dark_square */
			social_tools : pp_social_tools
		});
		
	}
	
    if (theme_vars.lightbox != undefined && theme_vars.lightbox == 'on') {
		lightbox_init();
	}
	
	
    /* Contact Form
	 ------------------------------------------------------------------------*/
	if (jQuery.fn.RForms) {
		jQuery('.r-form').RForms({
			action			: 'r_form',
			url			    : ajax_action.ajaxurl,
			nonce           : ajax_action.ajax_nonce,
			input_class     : '.rf',
			submit : 
				function(form, input) {
					form.find('.rf-message').html('');
					form.find('.req').removeClass('error');
				},
			valid_error : 
				function(form, input) {
					input.addClass('error');
					if (input.is('.valid_asq')) {
						form.find('.rf-message').append(theme_vars.rf_invalid_answer + ' <br/>')
					} else {
						form.find('.rf-message').append(theme_vars.rf_is_not_valid + '<br/>')
					}
				},
			sending : 
				function(form) {
					form.find('.rf-ajax-loader').fadeIn(400)
				},
			complete : 
				function(form) {
					form.find('.rf-ajax-loader').fadeOut(400)
				},
			success : 
				function(form) {
					form.find('.rf input, .rf textarea').val('');
					form.find('.rf-message').html(theme_vars.rf_success);
				},
			error : 
				function(form) {
					form.find('.rf-message').html(theme_vars.rf_error);
				}
		})
	}
	
	
	/* Dynamic Images
  	 ------------------------------------------------------------------------*/
	if (jQuery.fn.RDM) {
		jQuery('.autoload').RDM({
			image_animation : true,
			opacity_level : 0.6
		})
    }
	
	
	/* Dynamic Lists
  	 ------------------------------------------------------------------------*/
	if (jQuery.fn.RDynamicList) { 
		jQuery('.dynamic-list').RDynamicList({
										  display_limit : 'data',
										  element_height : 60,
										  easing : 'easeOutElastic',
										  duration : 1000
										  });
	}
	
	
    /* Helper Functions
  	 ------------------------------------------------------------------------*/
	  
     /* Add target attributes to 'a' element */
	 jQuery('a.target-blank').attr('target', '_blank')


    /*
     * jQuery Highlight plugin
     *
     * Based on highlight v3 by Johann Burkard
     * http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html
     *
     * Code a little bit refactored and cleaned (in my humble opinion).
     * Most important changes:
     *  - has an option to highlight only entire words only (wordsOnly - false by default),
     *  - has an option to be case sensitive (caseSensitive - false by default)
     *  - highlight element tag and class names can be specified in options
     *
     * Usage:
     *   // wrap every occurrance of text 'lorem' in content
     *   // with <span class='highlight'> (default options)
     *   $('#content').highlight('lorem');
     *
     *   // search for and highlight more terms at once
     *   // so you can save some time on traversing DOM
     *   $('#content').highlight(['lorem', 'ipsum']);
     *   $('#content').highlight('lorem ipsum');
     *
     *   // search only for entire word 'lorem'
     *   $('#content').highlight('lorem', { wordsOnly: true });
     *
     *   // don't ignore case during search of term 'lorem'
     *   $('#content').highlight('lorem', { caseSensitive: true });
     *
     *   // wrap every occurrance of term 'ipsum' in content
     *   // with <em class='important'>
     *   $('#content').highlight('ipsum', { element: 'em', className: 'important' });
     *
     *   // remove default highlight
     *   $('#content').unhighlight();
     *
     *   // remove custom highlight
     *   $('#content').unhighlight({ element: 'em', className: 'important' });
     *
     *
     * Copyright (c) 2009 Bartek Szopka
     *
     * Licensed under MIT license.
     *
     */

    jQuery.extend({
        highlight: function (node, re, nodeName, className) {
            if (node.nodeType === 3) {
                var match = node.data.match(re);
                if (match) {
                    var highlight = document.createElement(nodeName || 'span');
                    highlight.className = className || 'highlight';
                    var wordNode = node.splitText(match.index);
                    wordNode.splitText(match[0].length);
                    var wordClone = wordNode.cloneNode(true);
                    highlight.appendChild(wordClone);
                    wordNode.parentNode.replaceChild(highlight, wordNode);
                    return 1; //skip added node in parent
                }
            } else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
            !/(script|style)/i.test(node.tagName) && // ignore script and style nodes
            !(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
                for (var i = 0; i < node.childNodes.length; i++) {
                    i += jQuery.highlight(node.childNodes[i], re, nodeName, className);
                }
            }
            return 0;
        }
    });

    jQuery.fn.unhighlight = function (options) {
        var settings = {
            className: 'highlight',
            element: 'span'
        };
        jQuery.extend(settings, options);

        return this.find(settings.element + "." + settings.className).each(function () {
            var parent = this.parentNode;
            parent.replaceChild(this.firstChild, this);
            parent.normalize();
        }).end();
    };

    jQuery.fn.highlight = function (words, options) {
        var settings = {
            className: 'highlight',
            element: 'span',
            caseSensitive: false,
            wordsOnly: false
        };
        jQuery.extend(settings, options);

        if (words.constructor === String) {
            words = [words];
        }

        var flag = settings.caseSensitive ? "" : "i";
        var pattern = "(" + words.join("|") + ")";
        if (settings.wordsOnly) {
            pattern = "\\b" + pattern + "\\b";
        }
        var re = new RegExp(pattern, flag);

        return this.each(function () {
            jQuery.highlight(this, re, settings.element, settings.className);
        });
    };


}) // End custom scripts