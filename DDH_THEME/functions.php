<?php

/* Theme Configuration
 ------------------------------------------------------------------------*/

/* Set global options variable */
global $r_option;

/* Get theme data */
$theme_data = get_theme_data(TEMPLATEPATH . '/style.css');

/* Set theme constants */
define('THEME_NAME', $theme_data['Name']);
define('SHORT_NAME', 'pendulum');
define('THEME_VERSION', $theme_data['Version']);
define('RPANEL_VERSION', '3.2');
define('FRAMEWORK', 'R-Frame 1.4');
define('COPYRIGHT', 'Copyright &copy; 2011 Rascals Labs. Powered by R-Panel.');

/* Set path constants */
define('THEME', TEMPLATEPATH);
define('ADMIN', TEMPLATEPATH . '/framework/admin');
define('THEME_SCRIPTS', TEMPLATEPATH . '/framework/scripts');

/* Set URI path constants */
define('THEME_URI', get_template_directory_uri());
define('ADMIN_URI', get_template_directory_uri() . '/framework/admin');
define('THEME_SCRIPTS_URI', get_template_directory_uri() . '/framework/scripts');


/* Translate
 ------------------------------------------------------------------------*/
 
/* Make theme available for translation
   Translations can be filed in the /languages/ directory */
load_theme_textdomain(SHORT_NAME, TEMPLATEPATH . '/languages' );


/* Set global options
 ------------------------------------------------------------------------*/

/* Theme options */
$r_option = get_option(SHORT_NAME . '_general_settings');

/* Set images constants */
if (isset($r_option['curl'])) define('CURL', $r_option['curl']);
else define('CURL', 'off');

/* Show activation message */
define('SHOW_ACTIVATION', true);


/* Include Rascals Framework
 ------------------------------------------------------------------------*/
include_once(THEME . '/framework/init.php');


/* Register Theme Menu
 ------------------------------------------------------------------------*/
function r_register_menus() {
	
	register_nav_menus(
		array(
			'main' => __('Main Menu')
			)
	);
}

add_action('init', 'r_register_menus');


/* User Custom Functions
 ------------------------------------------------------------------------*/

/* Exclude custom posts from search */
function search_filter($query) {
	if (!is_admin()) {
		if ($query->is_search) $query->set('post_type', array('post', 'page', 'wp_portfolio'));
		return $query;
	}
	
}
add_filter('pre_get_posts', 'search_filter');

/* Add theme support */
add_theme_support('automatic-feed-links');

/* Set feed cache lifetime in secounds */
add_filter('wp_feed_cache_transient_lifetime', create_function( '$a', 'return 600;' )); 

/* Set content width */
if (!isset($content_width)) $content_width = 960;

?>