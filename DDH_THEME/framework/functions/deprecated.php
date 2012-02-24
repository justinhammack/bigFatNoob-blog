<?php

/* Deprecated functions
 ------------------------------------------------------------------------*/
 
/* Shortcodes */

/* Line */
function r_line($atts, $content = null) {
   return '<div class="line"></div>';
}
add_shortcode('line', 'r_line');

/* Line top */
function r_line_top($atts, $content = null) {
   return '<div class="line"><a href="#header" class="top">Top</a></div>';
}
add_shortcode('line_top', 'r_line_top');
	
?>