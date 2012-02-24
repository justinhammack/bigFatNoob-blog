<?php

/* Rascals Custom Post Type Class
 ------------------------------------------------------------------------*/
class r_custom_post_type {
	
	var $args;
	var $options;
	
	/* Constructor */
	function r_custom_post_type($args, $options) {

		/* Set options and page variables */
		$this->args = $args;
		$this->options = $options;
		
		/* Register Post Type */
		register_post_type($this->args['post_name'], $this->options);
		
		/* Add ajax sortable function */
		if ($this->args['sortable']) {
			
			/* Add Admin scripts */
			add_action('admin_enqueue_scripts', array(&$this, 'r_custom_post_scripts'));
			
			add_action('wp_ajax_' . $this->args['post_name'], array(&$this, 'save_order'));
			
			/* Call method to create the sidebar menu items */
			add_action('admin_menu', array(&$this, 'add_admin_menu'));
			
			/* Set admin order */
			add_filter('pre_get_posts', array(&$this, 'set_admin_order'));
			
		}

	}

	/* Create the sidebar menu */
	function add_admin_menu() {	
		add_submenu_page('edit.php?post_type=' . $this->args['post_name'], $this->args['post_name'] . '_sort', _x('Sort Items', 'custom post class', SHORT_NAME), 'manage_options', basename(__FILE__), array(&$this, 'init'));
	}
	
	/* Admin scripts */
	function r_custom_post_scripts() {
		wp_enqueue_script('r_custom_post', ADMIN_URI . '/scripts/jquery.custom_post.js', array('jquery'), '1.0.0');
	}
	
	/* Ajax function */
	function save_order() {
		global $wpdb;
	 
		$order = explode(',', $_POST['order']);
		$counter = 1;
	 
		foreach ($order as $value) {
			$wpdb->update($wpdb->posts, array('menu_order' => $counter), array('ID' => $value));
			$counter++;
		}
		die(1);
	}
	
	/* Set Admin Order */
	function set_admin_order($wp_query) {
	
		if (isset($wp_query->query['post_type'])) {
			
			/* Get the post type from the query */
			$post_type = $wp_query->query['post_type'];
	
			if ($post_type == $this->args['post_name']) {
	
			  /* 'orderby' value can be any column name */
			  $wp_query->set('orderby', 'menu_order');
	
			  /* 'order' value can be ASC or DESC */
			  $wp_query->set('order', 'ASC');
			  
			}
		}
	}

	/* Initialize */
	function init() {
		$this->display();
	}
	
	function display() {
		
		$sort_query = new WP_Query('post_type=' . $this->args['post_name'] . '&posts_per_page=-1&orderby=menu_order&order=ASC');
		
		echo '<div class="wrap">';
		echo '<h3>' . _x('Sort Items', 'custom post class', SHORT_NAME) . ' <img src="' . site_url() . '/wp-admin/images/loading.gif" id="loading-animation" alt="' . $this->args['post_name'] . '"/></h3>';
		echo '<ul id="r-sortable">';
		while ($sort_query->have_posts()){ 
			$sort_query->the_post();
			echo '<li id="' . get_the_ID() . '">';
			echo '<span class="r-drag-item"></span>';
			echo '<a href="' . home_url() . '/wp-admin/post.php?action=edit&post=' . get_the_ID() . '" class="r-edit-item" title="' . _x('Edit This Post', 'custom post class', SHORT_NAME) . '"></a>';
			echo '<div class="r-sortable-content">';
			echo '<h6>' . get_the_title() . ' <span>[id: ' . get_the_ID() . ']</span></h6>';
			echo '</div>';
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>';
	}
	
}
?>