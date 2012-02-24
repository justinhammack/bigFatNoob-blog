/* 
 *  R-Text Tooltip ver. 1.0
 *  Copyright (c) 2011 Rascals Labs. 
 *	All Right Reserved.
 *  You may not modify and/or redistribute this file.
 *  http://www.rascals.eu
 *  rascals@rascals.eu
 */

;(function ($) {

    jQuery.fn.RTextTooltip = function(options) {
		
		return this.each(function() {
								  
			 var opts = jQuery.extend({
				'offset_y' : 55
			}, options);
			 
			var element_width;
			 
			$(this).hover(function (e) {
				tooltip_title = this.title;
				this.title = '';
				$('body').append('<div id="tooltip-text"><p>'+tooltip_title+'</p></div>');
				element_width = $('#tooltip-text').width();
				$('#tooltip-text').css('top', (e.pageY - opts.offset_y) + 'px').css('left', (e.pageX - element_width/2) + 'px').fadeIn('fast');
			}, function () {
				this.title = tooltip_title;
				$('#tooltip-text').remove();
			});
			
			/* Move Tooltip */
			$(this).mousemove(function (e) {
				$('#tooltip-text').css('top', (e.pageY - opts.offset_y) + 'px').css('left', (e.pageX - element_width/2) + 'px');
			})
		})
    }

})(jQuery);


/* 
 *  R-Image Tooltip ver. 1.0
 *  Copyright (c) 2011 Rascals Labs. 
 *	All Right Reserved.
 *  You may not modify and/or redistribute this file.
 *  http://www.rascals.eu
 *  rascals@rascals.eu
*/

;(function ($) {

    jQuery.fn.RImageTooltip = function(options) {
		
		return this.each(function() {
								  
			 var opts = jQuery.extend({
				'offset_x' : 25,
				'offset_y' : 55
			}, options);
			 
			$(this).hover(function (e) {
				tooltip_title = this.title;
				this.title = '';
				image_path = this.rel;
				$('body').append('<div id="tooltip"></div>');
				$('#tooltip').css('top', (e.pageY - opts.offset_y) + 'px').css('left', (e.pageX + opts.offset_x) + 'px').fadeIn('fast');
		
				/* Load Image */
				var img = new Image();
				$(img).load(function () {
					var image = $(this);
					opts.offset_y = img.height + 20;
					opts.offset_x = -img.width / 2;
					$('#tooltip').animate({ width : img.width, height : img.height }, 400, function () {
						$('#tooltip').html(image);
						image.css('opacity', '0.0').stop().animate({ opacity : 1.0 }, 800)});
				}).attr('src', image_path);
				
			}, function () {
				this.title = tooltip_title;
				$('#tooltip').remove();
			});
			
			/* Move Tooltip */
			$(this).mousemove(function (e) {
				$('#tooltip').css('top', (e.pageY - opts.offset_y) + 'px').css('left', (e.pageX + opts.offset_x) + 'px');
			})
		})
    }

})(jQuery);


/* 
 *  R-Dynamic List ver. 1.1
 *  Copyright (c) 2011 Rascals Labs. 
 *	All Right Reserved.
 *  You may not modify and/or redistribute this file.
 *  http://www.rascals.eu
 *  rascals@rascals.eu
*/

;(function ($) {
			
jQuery.fn.RDynamicList = function(options) {
	
	return this.each(function() {		  
		var opts = jQuery.extend({
			display_limit     : 4,
			element_height    : 60,
			easing            : 'swing',
			duration          : 400
		}, options);
		   
		/* List variables */ 
		var container = $('ul', this);
		
		/* Element height */
		var margin = parseInt($('ul li:first', this).css('margin-top'), 10) + parseInt($('ul li:first', this).css('margin-bottom'), 10);
		var element_height = $('ul li', this).css('height', opts.element_height+'px');
		var	element_num = $('li' ,this).size();
		element_height = $('ul li:first', this).outerHeight();
		element_height = element_height + margin;
		
		if (opts.display_limit == 'data') {
		    var display_limit = $(this).data('display_limit');
		} else {
			var display_limit = opts.display_limit;
		}
		if (display_limit == '-1') display_limit = element_num;
		
		var	list_height = display_limit * element_height;
		
		var	total = element_num - display_limit;
		var	current = 0;
		
		/* Bulid list */
		$('li' ,this).css('height', opts.element_height+'px');
		$('.dynamic-container', this).css('height', list_height+'px');
		
		/* Display navigation list */
		if (element_num > display_limit ) {
			
			/* Add navigation arrows */
			$(this).append('<a href="#" class="nav-prev"></a> <a href="#" class="nav-next"></a>');
			
			/* Bind click functions */
			$('a.nav-next', this).click(function () {
				if (current == total) current = total;
				else current++;
				container.animate({ top: (-current) * element_height }, { duration: opts.duration, easing: opts.easing, queue: false });
				return false;
			});
			
			$('a.nav-prev', this).click(function () {
				if (current == 0) current = 0;
				else current--;
				container.animate({ top: (-current) * element_height }, { duration: opts.duration, easing: opts.easing, queue: false });
				return false;
			});
		}
	})
}

})(jQuery);
