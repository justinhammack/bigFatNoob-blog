<?php

/* Register Sidebars
 ------------------------------------------------------------------------*/
function r_widgets_init() {
	
	global $r_option;
	
	if (function_exists('register_sidebar')) {
		register_sidebar(array(
							   'name' => 'Default',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3 class="sidebar">',
							   'after_title' => '</h3>'
		));
		register_sidebar(array(
							   'name' => 'Footer Left',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3>',
							   'after_title' => '</h3>'
		));
		register_sidebar(array(
							   'name' => 'Footer Center',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3>',
							   'after_title' => '</h3>'
		));
		register_sidebar(array(
							   'name' => 'Footer Right',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3>',
							   'after_title' => '</h3>'
		));
		register_sidebar(array(
							   'name' => 'Category',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3 class="sidebar">',
							   'after_title' => '</h3>'
		));
		register_sidebar(array(
							   'name' => 'Archive',
							   'before_widget' => '<div class="widget %2$s">',
							   'after_widget' => '</div>',
							   'before_title' => '<h3 class="sidebar">',
							   'after_title' => '</h3>'
		));
		if (isset($r_option['custom_sidebars'])) {
			
			foreach($r_option['custom_sidebars'] as $sidebar) {
				
				register_sidebar(array(
									   'name' => $sidebar['name'],
									   'before_widget' => '<div class="widget %2$s">',
									   'after_widget' => '</div>',
									   'before_title' => '<h3 class="sidebar">',
									   'after_title' => '</h3>'
				));
			}
		}
	}
}
add_action('widgets_init', 'r_widgets_init');
?>