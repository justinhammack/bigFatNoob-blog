<?php

/*
 * Plugin Name: R-Flickr Widget
 * Plugin URI: http://rascals.eu
 * Description: Display images from flickr.
 * Version: 1.1
 * Author: Rascals Labs
 * Author URI: http://rascals.eu
 */
 
class r_flickr_widget extends WP_Widget {

	/* Widget setup */ 
	function r_flickr_widget() {

		/* Widget settings */ 
		$widget_ops = array(
			'classname' => 'widget_r_flickr',
			'description' => _x('Display images from flickr', 'r-flickr', SHORT_NAME)
		);

		/* Widget control settings */ 
		$control_ops = array(
			'width' => 200,
			'height' => 200,
			'id_base' => 'r-flickr-widget'
		);

		/* Create the widget */ 
		$this->WP_Widget('r-flickr-widget', _x('R-Flickr', 'r-flickr', SHORT_NAME), $widget_ops, $control_ops);
		
	}

	/* Display the widget on the screen */ 
	function widget($args, $instance) {
		
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$id = $instance['flickr_id'];
		$nr = ($instance['flickr_limit'] != '') ? $nr = $instance['flickr_limit'] : $nr = 6;
		
		echo $before_widget;
		
		if (isset($title)) echo $before_title . $title . $after_title;
		
		if ($id == '') {
	        echo '<p>' . _x('The Flickr ID is invalid or does not exist.', 'r-flickr', SHORT_NAME) . '</p>';
			echo $after_widget;
			return false;
		}
		
		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=' . $nr . '&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=' . $id . '"></script>';
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
		$instance['flickr_limit'] = strip_tags($new_instance['flickr_limit']);
		
		return $instance;
		
	}

	function form($instance) {
		$defaults = array('title' => _x('Flickr', 'r-flickr', SHORT_NAME), 'flickr_id' => '', 'flickr_limit' => '6');
		$instance = wp_parse_args((array)$instance, $defaults);
		echo '<p>';
		echo '<label for="' . $this->get_field_id('title') . '">' . _x('Title:', 'r-flickr', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" type="text" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" class="widefat" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id('flickr_id') . '">' . _x('Flickr ID:', 'r-flickr', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('flickr_id') . '" type="text" name="' . $this->get_field_name('flickr_id') . '" value="' . $instance['flickr_id'] . '" class="widefat" />';
		echo '<small style="line-height:12px;"><a href="http://www.idgettr.com">' . _x('Find your Flickr user or group id', 'r-flickr', SHORT_NAME) . '</a></small>';
		echo '</p>';
        echo '<p>';
		echo '<label for="' . $this->get_field_id('flickr_limit') . '">' . _x('Number of photos:', 'r-flickr', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('flickr_limit') . '" type="text" name="' . $this->get_field_name('flickr_limit') . '" value="' . $instance['flickr_limit'] . '" class="widefat" />';
		echo '</p>';
		
	}
	
}

register_widget('r_flickr_widget');

?>