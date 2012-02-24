<?php

/* Shared Scripts
 ------------------------------------------------------------------------*/
function r_shared_scripts() {
	
	if (is_admin()) {
		
		/* Admin Stylesheet */
		wp_enqueue_style('r_admin', ADMIN_URI . '/css/admin.css', false, '1.0.0', 'screen');
		wp_enqueue_style('r_ui_theme', ADMIN_URI . '/css/r-ui-theme.css', false, '1.0.0', 'screen');
		wp_enqueue_style('r_colorpicker', ADMIN_URI . '/css/colorpicker.css', false, '1.0.0', 'screen');

		/* Javascripts */
		wp_enqueue_script('r_admin', ADMIN_URI . '/scripts/jquery.admin.js', array('jquery'), '1.0.0');
		wp_enqueue_script('color-picker', ADMIN_URI . '/scripts/colorpicker.js', array('jquery'), '1.0.0');
		wp_enqueue_script('jquery-tools-rangeinput', ADMIN_URI . '/scripts/rangeinput.js', array('jquery'),'1.2.5');
		wp_enqueue_script('jquery.ui.effects.core', ADMIN_URI . '/scripts/jquery.effects.core.min.js', array('jquery'), '1.8.7');
		wp_enqueue_script('jquery.ui.datepicker', ADMIN_URI . '/scripts/jquery.ui.datepicker.min.js', array('jquery'), '1.7.3');
		wp_enqueue_script('jquery.ui.effects.fade', ADMIN_URI . '/scripts/jquery.effects.fade.min.js', array('jquery'), '1.8.7');
        wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-draggable');
		
	}
	
}
add_action('admin_enqueue_scripts', 'r_shared_scripts');

?>