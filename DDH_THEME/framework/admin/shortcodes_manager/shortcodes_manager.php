<?php

class r_shortcodes_manager {
	
	var $pluginname = 'shortcodes_manager';
	var $path = '';
	var $version = 100;
	
	function r_shortcodes_manager()  {
		
		/* Set path */
		$this->path = ADMIN_URI . '/shortcodes_manager/';	
		
		/* Modify the version when tinyMCE plugins are changed. */
		add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );

		/* Addd editor button */
		add_action('init', array (&$this, 'add_butons') );
	}
	
	function add_butons() {

		/* Check user permissions */
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) return;
		
		/* Add buttons only in Rich Editor mode */
		if (get_user_option('rich_editing') == 'true') {
		    add_filter('mce_external_plugins', array(&$this, 'add_tinymce_plugin'), 5);
			add_filter('mce_buttons', array(&$this, 'register_button'), 5);
			add_filter('mce_external_languages', array(&$this, 'add_tinymce_langs_path'));	
		}
	}
	
	function register_button($buttons) {
		array_push($buttons, 'separator', $this->pluginname);
		return $buttons;
	}
	
	function add_tinymce_plugin($plugin_array) {
		$plugin_array[$this->pluginname] =  $this->path . 'shortcodes_manager_win.js';
		return $plugin_array;
	}
	
	function add_tinymce_langs_path($plugin_array) {	
		$plugin_array[$this->pluginname] = ADMIN . '/shortcodes_manager/shortcodes_manager_langs.php';
		return $plugin_array;
	}
	
	function change_tinymce_version($version) {
			$version = $version + $this->version;
		return $version;
	}
	
}

$shortcode_manager = new r_shortcodes_manager();