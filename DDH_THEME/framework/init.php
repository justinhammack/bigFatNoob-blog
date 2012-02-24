<?php

/* Initialize Rascals Framework
 ------------------------------------------------------------------------*/
global $r_option;


/* Theme functions
 ------------------------------------------------------------------------*/

/* WP-pagenavi */
include_once(THEME . '/framework/functions/wp_pagenavi.php');

/* Small helpers functions */
include_once(THEME . '/framework/functions/functions.php');

/* Shortcodes */
include_once(THEME . '/framework/functions/shortcodes.php');

/* Deprecated functions */
include_once(THEME . '/framework/functions/deprecated.php');


/* Theme Scripts
 ------------------------------------------------------------------------*/
include_once(THEME_SCRIPTS . '/scripts.php');


/* Admin
 ------------------------------------------------------------------------*/
 
/* Classes */
include_once(ADMIN . '/classes/custom_post_type.php');
include_once(ADMIN . '/classes/metabox.php');

/* Custom posts */

/* Homepage Slider */
include_once(ADMIN . '/options/slider.php');
	
/* Portfolio */
include_once(ADMIN . '/options/portfolio.php');

/* Events Manager */
include_once(ADMIN . '/options/events_manager.php');

if (is_admin()) {
	
	/* Load Shared Scripts */
	include_once(ADMIN . '/scripts/scripts.php');
	
	/* Admin functions */
    include_once(ADMIN . '/functions/admin_functions.php');
	
	/* Shortcode manager */
    include_once(ADMIN . '/shortcodes_manager/shortcodes_manager.php');
	
	/* Admin Classes */
	if (!class_exists('Services_JSON')) include_once(ADMIN . '/classes/json.php');
	include_once(ADMIN . '/classes/r_panel.php');
	
	/* General Settings */
	include_once(ADMIN . '/options/theme_settings.php');
	
	/* Metaboxes */
	include_once(ADMIN . '/options/metaboxes.php');
	
}
 	

/* Widgets
 ------------------------------------------------------------------------*/

/* Register sidebars */
include_once(THEME . '/framework/widgets/sidebars.php');

/* Rascals widgets array */
$rascals_widgets = array('r_cat', 'r_comments', 'r_flickr', 'r_posts', 'r_twitter');

/* Includes rascals widgets */
foreach ($rascals_widgets as $widget) {
	include_once(THEME . '/framework/widgets/' . $widget . '.php');	
}
?>