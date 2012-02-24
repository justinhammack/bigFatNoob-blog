<?php

/*
 * Plugin Name: R-Cat Widget
 * Plugin URI: http://rascals.eu
 * Description: Display and exclude categories.
 * Version: 1.1
 * Author: Rascals Labs
 * Author URI: http://rascals.eu
 */
 
class r_cat_widget extends WP_Widget {

	/* Widget setup */
	function r_cat_widget() {

		/* Widget settings */ 
		$widget_ops = array(
			'classname' => 'widget_r_cat',
			'description' => _x('Display and exclude categories', 'r-cat', SHORT_NAME)
		);

		/* Widget control settings */ 
		$control_ops = array(
			'width' => 200,
			'height' => 200,
			'id_base' => 'r-cat-widget'
		);

		/* Create the widget */ 
		$this->WP_Widget('r-cat-widget', _x('R-Cat', 'r-cat', SHORT_NAME), $widget_ops, $control_ops);
		
	}

	/* Display the widget on the screen */ 
	function widget($args, $instance) {
		
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$cat = $instance['cat'];
		$show_post_counts = ($instance['show_post_counts'] == 'true') ? $show_post_counts = 1 : $show_post_counts = 0;
		echo $before_widget;
		
		if (isset($title)) echo $before_title . $title . $after_title;
		
		echo '<ul>';
		wp_list_categories('orderby=name&show_count=' . $show_post_counts . '&title_li=&depth=-1&exclude=' . $cat);
		echo '</ul>';
		
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['cat'] = strip_tags($new_instance['cat']);
		$instance['show_post_counts'] = strip_tags($new_instance['show_post_counts']);
		return $instance;
		
	}

	function form($instance) {
		$defaults = array('title' => _x('Categories', 'r-cat', SHORT_NAME), 'cat' => '', 'show_post_counts' => 'true');
		$instance = wp_parse_args((array)$instance, $defaults);
		echo '<p>';
        echo '<label for="' . $this->get_field_id('title') . '">' . _x('Title:', 'r-cat', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" type="text" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" class="widefat" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id('cat') . '">' . _x('Type category ID to exclude:', 'r-cat', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('cat') . '" type="text" name="' . $this->get_field_name('cat') . '" value="' . $instance['cat'] . '" class="widefat" />';
		echo '<small style="line-height:12px;">' . _x('Separate by commas eg 12,23,45', 'r-cat', SHORT_NAME) . '</small>';
		echo '</p>';
        
		if ($instance['show_post_counts']) $checked = 'checked="checked"';
		else $checked = '';
		
		echo '<p>';
		echo '<input class="checkbox" type="checkbox" value="true" id="' . $this->get_field_id('show_post_counts') . '" ' . $checked . ' name="' . $this->get_field_name('show_post_counts') . '" />';
		echo '<label for="' . $this->get_field_id('show_post_counts') . '"> ' . _x('Show post counts', 'r-cat', SHORT_NAME) . '</label>';
		echo '</p>';
		
	}
	
}

register_widget('r_cat_widget');

?>