jQuery.noConflict();

jQuery(document).ready(function () {


	/* Browser Fix
	 ------------------------------------------------------------------------*/
	  
	/* <= IE 8 */
	if (jQuery.browser.msie && jQuery.browser.version <= '8') {
		jQuery('.r-type:last-child').css('background', 'none');
	}


    /* Range
	 ------------------------------------------------------------------------*/
	jQuery(':range').rangeinput();
	
	
	/* Upload
	 ------------------------------------------------------------------------*/
	var target_input;
	var target_type;
	var image_container;
	
	window.original_send_to_editor = window.send_to_editor;
	
	upload_item = function () {
		
		window.send_to_editor = function(html) {
			
		    var target_url;
			
			if (target_type == 'image') {
				target_url = jQuery('img', html).attr('src');
				target_input.val(target_url);
				tb_remove();
				image_container.r_thumb_generator();
			} else {
				target_url = jQuery(html).attr('href');
				target_input.val(target_url);
				tb_remove();
			}
			window.send_to_editor = window.original_send_to_editor;
			return false;
		};
		
		image_container = jQuery(this).parent();
		target_input = image_container.find('input');
		target_type = jQuery(this).attr('rel');
		
		tb_show('', 'media-upload.php?post_id=&amp;type=image&amp;TB_iframe=true');
		return false;
	};
	
	jQuery('.r-upload').bind('click', upload_item);
	
	
	/* Easy Link
	 ------------------------------------------------------------------------*/
	jQuery('.r-easy-link').click(function () {
	    jQuery(this).r_easy_link();
		return false;
	});
	
	
	/* Add Menu Link
	 ------------------------------------------------------------------------*/
	jQuery('.r-add-menu-link').click(function () {
	    jQuery(this).r_add_menu_link();
		return false;
	});
	
	/* Crop
	 ------------------------------------------------------------------------*/
	jQuery('.r-meta-crop').change(function(){
											 
		var crop_value = jQuery(this).val(),
		    image_container = jQuery(this).parent();
		
		/* Update crop value */
		jQuery('.r-set-crop', image_container).text(crop_value);
		
	    /* Generate new thumbnail */
		image_container.r_thumb_generator();
	
	})
	
	 
	/* Datepicker
 	 ------------------------------------------------------------------------*/
	jQuery('.r-datepicker').datepicker({'dateFormat' : 'yy-mm-dd'});
	
	
	/* Select Image
	 ------------------------------------------------------------------------*/
	 
	/* Click image function */
	jQuery('ul.r-select-image li img').click(function () {
											
		/* Variables */											
		var images = jQuery(this).parent().parent();
		var id = jQuery(this).attr('alt');
		
		/* Remove icon */
		images.find('.r-selected-image').remove();
		jQuery(this).parent().append('<span class="r-selected-image"></span>');
		images.prev().val(id);
		return false;
	});
	 
	 
	/* Switch buttons
	 ------------------------------------------------------------------------*/
	 
	/* Set switch buttons */
	jQuery('.r-input-switch').each(function () {
		if (jQuery(this).val() == 'on') jQuery(this).prev().addClass('r-switch-on');
	});
	
	/* Switch buttons */
    switch_button = function () {
        if (jQuery(this).is('.r-switch-on')) jQuery(this).removeClass('r-switch-on').next().val('off');
        else jQuery(this).addClass('r-switch-on').next().val('on');
		return false;
    };
	jQuery('.r-switch').bind('click', switch_button);
	

	/* Color Picker
	 ------------------------------------------------------------------------*/
    if (jQuery('.color-picker').size() > 0) {
        jQuery('.color-picker').ColorPicker({
            onSubmit: function (hsb, hex, rgb, el) {
                jQuery(el).val('#' + hex);
                jQuery(el).ColorPickerHide();
            },
            onBeforeShow: function () {
                jQuery(this).ColorPickerSetColor(this.value);
            }
        }).bind('keyup', function () {
            jQuery(this).ColorPickerSetColor(this.value);
        });
    }


	/* Item preview
	 ------------------------------------------------------------------------*/
    item_preview = function () {
		
		var image_width = 0,
			image_height = 0,
			image_src = jQuery(this).attr('alt'),
			image = new Image(),
			title = image_src.substring(image_src.lastIndexOf("/") + 1).substring(image_src.lastIndexOf("\\") + 1);
			
			/* Destroy dialog */
			jQuery('#dialog-preview').remove();
			jQuery('#dialog-preview').dialog('destroy');
		
			/* Dialog content */
			jQuery('body').append('<div id="dialog-preview" title="Preview Image"><p></p></div>');
			
			/* Add Notyfication */
			jQuery('body').append('<div class="r-notyfication r-loading-image">Please wait...</div>');
			jQuery('.r-loading-image').center();
			
			/* Load image */
			function load_image() {
				
					jQuery(image).load(function() {
												 
						image_width = image.width;
						image_height = image.height;
						jQuery('.r-loading-image').fadeOut(400, function() {show_image()});
					}).attr({
					src : image_src
				})
			}
			
			/* Show image */
			function show_image() {
				jQuery('.r-loading-image').remove();
					jQuery('#dialog-preview').dialog({
						autoOpen: true,
						resizable: true,
						draggable: true,
						title: title,
						modal: false,
						width: image_width+30,
						height: image_height+80,
						open: function() {jQuery('p', this).html(image)}
					});
			} 
			jQuery('.r-loading-image').fadeIn(400, function() {load_image()});
    };
	
	jQuery('.r-preview').live('click', item_preview);
	
})


/* Add Menu Link
 ------------------------------------------------------------------------*/
jQuery.fn.r_add_menu_link = function(absolute) {
	return this.each(function () {
		var  target_container = jQuery(this).parent().parent().parent();
			
		var data = {
				action: 'r_add_menu_link',
				menu_id: jQuery('.r-menu-id', target_container).val(),
				menu_label : jQuery('.r-menu-label', target_container).val(),
				menu_link : jQuery('.r-menu-link', target_container).val()
				};
				
		jQuery('.r-ajax-warning', target_container).addClass('r-hidden');
		jQuery('.r-ajax-loading', target_container).show();
		
		jQuery.ajax({
					url: ajaxurl,
					data: data,
					type: 'POST',
					success: function(response) {
						
						jQuery('.r-ajax-loading', target_container).hide();
						
						if (response == 'error - empty fields') {
							jQuery('.r-ajax-warning', target_container).removeClass('r-hidden');
							return;
						}
						
						jQuery('.r-remove', target_container).slideUp(400);
						jQuery('.r-meta-success', target_container).removeClass('r-hidden');
						return;
						
					}
		});
	
	});
};


/* Easy Link
 ------------------------------------------------------------------------*/
jQuery.fn.r_easy_link = function(absolute) {
	return this.each(function () {
		var  target_container = jQuery(this).parent(),
		     target_input = jQuery('.r-link-input', target_container),
			 box = jQuery('#r-link'),
			 ul_wrap = jQuery('#r-link-results', box),
			 ul = jQuery('ul', box),
			 ajax_timeout,
			 timeout = 500,
			 pagenum,
			 s;
		
		
		/* Display links */
		var _display_links = function() {
			
			var data = {
					action: 'r_easy_link',
					page_num : pagenum,
					s : s
					};
					
			jQuery('.r-link-ajax', box).show();
			
			jQuery.ajax({
						url: ajaxurl,
						data: data,
						type: 'POST',
						success: function(response) {
							
							if (response == 'end pages') {
								jQuery('.r-link-ajax', box).hide();
							    ul_wrap.unbind('scroll', _scroll_box);
								return;
							}
						    ul.append(response);
							jQuery('.r-link-ajax', box).hide();
							pagenum ++;
							_get_link();
							ul_wrap.bind('scroll', _scroll_box);
							
						}
			});
		}
		
		/* Scroll Box */
		function _scroll_box(e){
            var elem = jQuery(e.currentTarget);
            if (elem[0].scrollHeight - elem.scrollTop() == elem.outerHeight()-2) {
				
				if (ajax_timeout !== undefined) 
                    clearTimeout(ajax_timeout);
					
				ul_wrap.unbind('scroll', _scroll_box);
                ajax_timeout = setTimeout(_display_links, timeout);
				
				return false;
			}
		}
		
	    /* Search */
		function _search() {
		    jQuery('#r-link-search').keyup(function(){
													
				if (ajax_timeout !== undefined) 
                    clearTimeout(ajax_timeout);									
				s = jQuery(this).val();
				
				if (s === undefined) return;

				ul.html('');
				pagenum = 1;
                ajax_timeout = setTimeout(_display_links, timeout);
	        })
		}
		
		/* Get Link */
		function _get_link() {
			if (jQuery('li', ul).length > 0) {
				jQuery('li', ul).each(function(){
				    jQuery(this).click(function() {
						var permalink = jQuery('.r-permalink', this).text();
						jQuery('li', ul).removeClass('selected');
						jQuery(this).addClass('selected');
						jQuery('#r-link-target', box).val(permalink);
						return false;
					})
				
				})
			}	
		}
		
	    /* Dialog */
		jQuery('#r-link').dialog({
						autoOpen: true,
						resizable: true,
						title: 'Insert Link',
						modal: false,
						width: 500,
						height: 360,
						buttons: {
								  'Insert Link': function() { 
									   var target_val = jQuery('#r-link-target', box).val();
									   if (target_val != '') target_input.val(target_val);
						               jQuery(this).dialog('close');
					             },
					              Cancel: function() {
						               jQuery(this).dialog('close');
					             }
				        },
						open: function() {
							pagenum = 1;
							_search();
							_display_links();
							
						},
						close: function() { 
						    ul.html('');
							jQuery('#r-link-search').val('');
							_display_links = null;
						
						}
					});
		
	});
};



/* Center Function
 ------------------------------------------------------------------------*/
jQuery.fn.center = function(absolute) {
	return this.each(function () {
		var t = jQuery(this);

		t.css({
			position:	absolute ? 'absolute' : 'fixed', 
			left:		'50%', 
			top:		'50%', 
			zIndex:		'99'
		}).css({
			marginLeft:	'-' + (t.outerWidth() / 2) + 'px', 
			marginTop:	'-' + (t.outerHeight() / 2) + 'px'
		});

		if (absolute) {
			t.css({
				marginTop:	parseInt(t.css('marginTop'), 10) + jQuery(window).scrollTop(), 
				marginLeft:	parseInt(t.css('marginLeft'), 10) + jQuery(window).scrollLeft()
			});
		}
	});
};


/* Thumb Generator
 ------------------------------------------------------------------------*/
jQuery.fn.r_thumb_generator = function(absolute) {
	
    return this.each(function () {
		var image_container = jQuery(this),
		    image_src = jQuery('.r-input-image', image_container).val(),
		    image_wrap = jQuery('.r-image', image_container),
		    crop = jQuery('.r-set-crop', image_container).text(),
		    width = jQuery('.r-set-width', image_container).text(),
		    height = jQuery('.r-set-height', image_container).text()
		
		jQuery('.r-ajax-warning', image_container).addClass('r-hidden');
		
		/* Check if image alraedy exists and inputs is empty */
		if (image_src == '') {
			if (jQuery('img', image_wrap).length > 0) jQuery('img', image_wrap).remove();
			return;
		}
		
		/* Check if the image already exists */
		if (jQuery('img', image_wrap).length > 0) {
			var temp_img = jQuery('img', image_wrap).attr('src');
			var temp_string = 'src='+  image_src +'&a='+ crop +'&w='+ width +'&h='+ height;
			
			if (temp_img.search('src=') >= 0) {
				if (temp_img.search(temp_string) >= 0)
			        return;
			} else if (temp_img.search(image_src) >= 0) {
				    return
			}
		}
		
		var data = {
					action: 'r_ajax_thumbs',
					src : image_src,
					crop : crop,
					width : width,
					height : height
					};
					
		jQuery('.r-ajax-loading', image_container).fadeIn(400);
		
		/* If the image doesn't exists */
		jQuery.ajax({
					url: ajaxurl,
					data: data,
					type: 'POST',
					success: function(response) {
											
						if (response == 'error') {
							jQuery('.r-ajax-loading', image_container).hide();
							jQuery('.r-ajax-warning', image_container).removeClass('r-hidden');
							if (jQuery('img', image_wrap).length > 0) jQuery('img', image_wrap).remove();
							return;
						}
							
						var image = new Image();
						response = response.replace(/\&amp;/g, '&');
						if (jQuery('img', image_wrap).length > 0) {
							jQuery('img', image_wrap).fadeOut(400, function(){
								jQuery(this).remove();										
							});
						}
						
						/* Loading new image */
						jQuery(image).load(function() {
							image_wrap.html(image);
							jQuery('.r-ajax-loading', image_container).hide();
							jQuery(this).addClass('r-preview').hide().fadeIn(400);
						
						}).attr({
								src : response,
								alt : image_src
						});
		            }
				});
		
		return;
	});
};