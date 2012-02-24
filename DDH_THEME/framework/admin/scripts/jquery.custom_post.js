jQuery.noConflict();

jQuery(document).ready(function () {
	
	/* Ajax Sortable
	 ------------------------------------------------------------------------*/
	var item_list = jQuery('#r-sortable');
	
	/* Get ajax handle */
 	var ajax_handle = jQuery('#loading-animation').attr('alt');
	
	item_list.sortable({
		handle: jQuery('.r-drag-item'),
		update: function(event, ui) {
			jQuery('#loading-animation').show(); 
 
			opts = {
				url: ajaxurl, 
				type: 'POST',
				async: true,
				cache: false,
				dataType: 'json',
				data:{
					action: ajax_handle, 
					order: item_list.sortable('toArray').toString()
				},
				success: function(response) {
					jQuery('#loading-animation').hide(); 
					return; 
				},
				error: function(xhr,textStatus,e) {  
					alert('There was an error saving the updates');
					jQuery('#loading-animation').hide(); 
					return; 
				}
			};
			jQuery.ajax(opts);
		}
	});	
	
	
});