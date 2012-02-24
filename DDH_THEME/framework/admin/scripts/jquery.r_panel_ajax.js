jQuery.noConflict();

jQuery(document).ready(function () {
	
	
	/* Tabs
	 ------------------------------------------------------------------------*/
	
	/* First menu item active */
	jQuery('#r-menu li a:first').addClass('active');
	
	/* Display first tab */
    jQuery('.r-tab:first').css('display', 'block');
	
	/* Display first sub tab and add active class to the sub menu */
	jQuery('.r-tab').each(function() {
	    jQuery('.r-sub-tab:first', this).css('display', 'block');
		jQuery('.r-sub-menu li a:first', this).addClass('active');
	
	});
	
	/* Menu */
	var this_tab = jQuery('.r-tab:first');
    jQuery('#r-menu li a').click(function () {

        var current_id = jQuery(this).attr('rel');
		this_tab = jQuery('#' + current_id);
        jQuery('#r-menu li a').removeClass('active');
        jQuery('.r-tab').css('display', 'none');
        jQuery(this).addClass('active');
        this_tab.css('display', 'block');
		return false;
    });
	
	/* Sub Menu */
	jQuery('.r-sub-menu li a').click(function () {
											   
        var current_id = jQuery(this).attr('rel');
        jQuery('.r-sub-menu li a', this_tab).removeClass('active');
        jQuery('.r-sub-tab', this_tab).css('display', 'none');
        jQuery(this).addClass('active');
        jQuery('#' + current_id).css('display', 'block');
		return false;
    }) 
	 

	/* Save Settings
 	 ------------------------------------------------------------------------*/
	jQuery('#r-save').click(function() {
									 
		 /* Add notyfication */
         jQuery('.r-ajax-messages').center();
		 jQuery('.r-ajax-messages .r-ajax-success').hide();
		 jQuery('.r-ajax-messages .r-ajax-sending').show();
		 jQuery('.r-ajax-messages').fadeIn(400);
		 
		 /* Cufon fonts 
		 if (jQuery('#cufon-list li.selected').size() <= 0) var default_cufon = true;
		 else var default_cufon = false;*/
		 
		 /* Update editor content */
        jQuery('.r-tiny-editor').each(function(){
											   
			/* Only for visual editor */
			if (jQuery(this).children().hasClass('tmce-active')) {
				var editor_id = jQuery(this).data('id');
				editor_id = '#'+editor_id;
				var editor_content = jQuery(editor_id+'_ifr', this).contents().find('body').html();
				if (editor_content) {
				   if (editor_content == '' || editor_content == '<p><br></p>' || editor_content == '<p><br data-mce-bogus="1"></p>') editor_content = '';
				   jQuery('textarea'+editor_id, this).val(editor_content);
				}
			}
		});
		var data = {
			action: 'r_panel_save',
			data : jQuery('#r_panel_form').serializePost()
	    };
        jQuery.ajax({
					url: ajaxurl,
					data: data,
					type: 'POST',
					success: function(response) {
			
						/* Update Export */
						jQuery('#r_panel_form textarea[name="export"]').val(response);
						
						/* Parse JSON object */
						response = JSON.parse(response);
						var is_import = false;
						
						/* If import */
						if (jQuery('#r-data-import-wrap .r-input').length > 0 && jQuery('#r-data-import-wrap .r-input').val() != '') is_import = true;
						
						jQuery('#r_panel_form :input').each(function(i){
																	 
							var input_name = jQuery(this).attr('name');
							var input_val = jQuery(this).val();
							var response_val = response[input_name];
							
							/* Inputs */
							if (response_val != undefined & response_val != input_val) {
								jQuery(this).val(response[input_name]);
							}
							
							/* Images */
							if (jQuery(this).hasClass('r-input-image') && is_import == false) {				
								var image_container = jQuery(this).parent().parent();
								image_container.r_thumb_generator();
							}
						
						});
						
						/* TinyMCE update content */
						jQuery('.r-tiny-editor').each(function(){
							var editor_id = jQuery(this).data('id');
							editor_id = '#'+editor_id;
							var saved_content = jQuery('textarea'+editor_id, this).val();
							jQuery(editor_id+'_ifr', this).contents().find('body').html(saved_content);
						});
						
						/* Set switch buttons */
						jQuery('.r-input-switch').each(function () {
						  if (jQuery(this).val() == 'on') jQuery(this).prev().addClass('r-switch-on');
						});
						
						/* Show message */
						jQuery('.r-ajax-messages .r-ajax-sending').hide();
						jQuery('.r-ajax-messages .r-ajax-success').fadeIn(400);
						jQuery('.r-ajax-messages').delay(1000).fadeOut(400);
						
						/* Cufon Fonts 
						if (default_cufon == true) {
						  var cufon_fonts = jQuery('#cufon-fonts').val();
						  cufon_fonts = cufon_fonts.split('|');
						  jQuery('#cufon-list li').each(function(i){
								for(i = 0; i < cufon_fonts.length; i++){
									if (jQuery('.cufon-file-name', this).text() == cufon_fonts[i]) {
										jQuery(this).addClass('selected');
									}
								}
						  });
						  
						}*/
						
						/* If import */
						if (is_import) location.reload();
		          }
		 });
		 return false;
	});
	
	
	/* Cufon Fonts
	 ------------------------------------------------------------------------*/
	var cufon_id = jQuery('#cufon-id').text();
	
	/* Build fonts array */
	function build_fonts() {
		jQuery('#cufon-fonts').val('');
		var fonts = '';
		var fonts_length = jQuery('#cufon-list li.selected').size();
		
		jQuery('#cufon-list li.selected').each(function(i){
		    var font_file_name = jQuery('.cufon-file-name', this).text();
		    fonts += font_file_name;
		    if (i < fonts_length-1) fonts += '|'
		});
		
		jQuery('#cufon-fonts').val(fonts);
		return false;
	}
	
	/* Build fonts helpers tags */
    function build_fonts_tags() {
		jQuery('#cufon-tags span').remove();
		
		jQuery('#cufon-list li.selected').each(function(i){
		    var font_name = jQuery('.cufon-font-name', this).text();
		    jQuery('#cufon-tags').append('<span class="cufon-tag">' + font_name + '</span>');
		});
		
		/* Add click functions */
		jQuery('#cufon-tags span').click(function(){
			var font_name = jQuery(this).text();
			var code = 'Cufon.replace("HTML elements to replace", {fontFamily : "' + font_name + '", hover: "true"});'
		    var txt = jQuery('#cufon-code');
			if (txt.val() == '') txt.val(txt.val() + code);
			else txt.val(txt.val() + '\n' + code);
			return false;
		});
		
		return false;
	}
	
	function insert_text(element,valor){
	    var element_dom = document.getElementsByName(element)[0];
		if (document.selection){
			element_dom.focus();
			sel = document.selection.createRange();
			sel.text=valor;
			return;
		}
		if (element_dom.selectionStart || element_dom.selectionStart == '0'){
			var t_start=element_dom.selectionStart;
			var t_end=element_dom.selectionEnd;
			var val_start=element_dom.value.substring(0,t_start);
			var val_end=element_dom.value.substring(t_end,element_dom.value.length);
			element_dom.value=val_start+valor+val_end;
		} else {
		    element_dom.value+=valor;
		}
	}
	
	/* Click function*/
	jQuery('#cufon-list li').click(function(){
		if(jQuery(this).is('.selected')){
			if (jQuery('#cufon-list li.selected').size() > 1)
		    jQuery(this).removeClass('selected');
		} else {
		    jQuery(this).addClass('selected');
		}
		build_fonts();
		build_fonts_tags();
		return false;
		
	});
	
	build_fonts_tags();
	
	
	/* Dynamic List
	 ------------------------------------------------------------------------*/
	jQuery('.r-sortable').sortable({
		handle : jQuery('.dynamic-list .r-drag-item'),
		axis : 'y'
	});
	
	/* Add new static item */
    jQuery('.r-add-new-item').click(function () {  
        var new_item = jQuery(this).parents('.r-type').find('.r-new-item ul').html();
        jQuery(this).parents('.r-type').find('.dynamic-list').append(new_item);
		jQuery(this).parents('.r-type').find('.r-sortable').sortable({
		    handle : jQuery('.dynamic-list .r-drag-item'),
		    axis : 'y'
	    });
		return false;
    });
	
	/* Delete item */
	delete_item = function () {
		
		var current_item = jQuery(this);

		/* Destroy dialog */
		jQuery('#dialog-delete').remove();
		jQuery('#dialog-delete').dialog('destroy');
		
		/* Dialog content */
		jQuery('body').append('<div id="dialog-delete" title="Delete Item"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>These item will be permanently deleted and cannot be recovered. Are you sure?</p></div>');

		jQuery('#dialog-delete').dialog({
			show: {effect: 'fade', options: {}, speed: 400},
			resizable: false,
			autoOpen: true,
			modal: false,
			height:180,
			buttons: {
					'Delete item': function() {
						current_item.parents('li:eq(0)').fadeOut(400, function () {
							jQuery(this).remove();
						});
						jQuery(this).dialog('close');
					},
					Cancel: function() {
						jQuery(this).dialog('close');
					}
				}
			});

		
    };

	/* Bind click function (delete row) */
    jQuery('.dynamic-list .r-delete-item').live('click', delete_item);
	
	
	/* Import Data
	 ------------------------------------------------------------------------*/	
	jQuery('.r-data-import').toggle(function () {
		$textarea = '<textarea name="import" style="height:200px;overflow:auto" cols="" rows="" class="r-input r-textarea"></textarea>';									        jQuery('#r-data-import-wrap .r-input-wrap').append($textarea);
		jQuery('#r-data-import-wrap').slideDown(400);
	    return false;
											  
	}, function () {
		jQuery('#r-data-import-wrap .r-input-wrap .r-input').remove();
		jQuery('#r-data-import-wrap').slideUp(400);
	    return false;
		
	});

	/* Helper functions
	 ------------------------------------------------------------------------*/
	 
	/* Move sidebar */
	jQuery('#r-sidebar').detach().appendTo('#wpcontent').show();


});


/* Helper functions
 ------------------------------------------------------------------------*/	
;(function($) {
        $.fn.serializePost = function() {  
            var data = {};  
            var formData = this.serializeArray();  
            for (var i = formData.length; i--;) {  
                var name = formData[i].name;  
                var value = formData[i].value;  
                var index = name.indexOf('[]');  
                if (index > -1) {  
                    name = name.substring(0, index);  
                    if (!(name in data)) {  
                        data[name] = [];  
                    }  
                    data[name].push(value);  
                }  
                else  
                    data[name] = value;  
            }  
            return data;  
        };  
    })(jQuery);  