<?php

/**
 * Plugin Name: R-Twitter Widget
 * Plugin URI: http://rascals.eu
 * Description: Display latest tweets from twitter.
 * Version: 1.1
 * Author: Rascals Labs
 * Author URI: http://rascals.eu
 */
 
class r_twitter_widget extends WP_Widget {

	/* Widget setup */ 
	function r_twitter_widget() {

		/* Widget settings */
		$widget_ops = array(
			'classname' => 'widget_r_twitter',
			'description' => _x('Display latest tweets from twitter', 'r-twitter', SHORT_NAME)
		);

		/* Widget control settings */
		$control_ops = array(
			'width' => 200,
			'height' => 200,
			'id_base' => 'r-twitter-widget'
		);
		
		/* Create the widget */ 
		$this->WP_Widget('r-twitter-widget', _x('R-Twitter', 'r-twitter', SHORT_NAME), $widget_ops, $control_ops);
		
	}

	/* Display the widget on the screen */ 
	function widget($args, $instance) {
		
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$username = $instance['username'];
		$limit = ($instance['limit'] != '') ? $limit = $instance['limit'] : $limit = 3;
		
		echo $before_widget;
		
		if (isset($title)) echo $before_title . $title . $after_title;
		
		echo r_parse_twitter($username, $limit);
		
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['limit'] = strip_tags($new_instance['limit']);
		
		return $instance;
		
	}

	function form($instance) {
		
		global $r_option;
		
		$defaults = array('title' => _x('Tweets', 'r-twitter', SHORT_NAME), 'username' => $r_option['twitter_username'], 'limit' => '3');
		$instance = wp_parse_args((array)$instance, $defaults);
		echo '<p>';
		echo '<label for="' . $this->get_field_id('title') . '">' . _x('Title:', 'r-twitter', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" type="text" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" class="widefat" />';
		echo '</p>';
		echo '<p>';
		echo '<label for="' . $this->get_field_id('username') . '">' . _x('Username', 'r-twitter', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('username') . '" type="text" name="' . $this->get_field_name('username') . '" value="' . $instance['username'] . '" class="widefat" />';
		echo '</p>';
        echo '<p>';
		echo '<label for="' . $this->get_field_id('limit') . '">' . _x('Number of links', 'r-twitter', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('limit') . '" type="text" name="' . $this->get_field_name('limit') . '" value="' . $instance['limit'] . '" class="widefat" />';
		echo '<small style="line-height:12px;">' . _x('20 is the maximum', 'r-twitter', SHORT_NAME) . '</small>';
		echo '</p>';

	}
	
}

register_widget('r_twitter_widget');

?>