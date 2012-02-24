<?php

/**
 * Plugin Name: R-Posts Widget
 * Plugin URI: http://rascals.eu
 * Description: Display latest posts.
 * Version: 1.1
 * Author: Rascals Labs
 * Author URI: http://rascals.eu
 */
 
class r_posts_widget extends WP_Widget {

	/* Widget setup */ 
	function r_posts_widget() {

		/* Widget settings */ 
		$widget_ops = array(
			'classname' => 'widget_r_posts',
			'description' => _x('Display latest posts', 'r-posts', SHORT_NAME)
		);

		/* Widget control settings */ 
		$control_ops = array(
			'width' => 200,
			'height' => 200,
			'id_base' => 'r-posts-widget'
		);

		/* Create the widget */
		$this->WP_Widget('r-posts-widget', _x('R-Posts', 'r-posts', SHORT_NAME), $widget_ops, $control_ops);
		
	}

	/* Display the widget on the screen */ 
	function widget($args, $instance) {
		
		global $post, $wp_query, $r_option;
		
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$cat = (get_cat_ID($instance['posts_cat']) != '_all') ? $cat = get_cat_ID($instance['posts_cat']) : $cat = '0';
		$limit = ($instance['posts_limit'] != '') ? $limit = $instance['posts_limit'] : $limit = 3;
		$length = ($instance['length'] != '') ? $length = $instance['length'] : $length = 10;
		$show_thumb = ($instance['show_thumb'] == 'true') ? $show_thumb = true : $show_thumb = false;
		
		echo $before_widget;
		
		if ($title) echo $before_title . $title . $after_title;
		
		$args = array(
					'cat' => $cat,
					'showposts'=> $limit
			         );
		$r_posts_query = new WP_Query($args); 
		
		if ($r_posts_query->have_posts()) {
       		echo '<ul>';
			
        	while ($r_posts_query->have_posts()) {
				$r_posts_query->the_post();
				$excerpt = r_trim($r_posts_query->post->post_excerpt, $length, true, ' [...]');
            	$post_image = get_post_meta($r_posts_query->post->ID, '_post_image', true);
				$thumb_class = '';
       			echo '<li>';
				if ($show_thumb == true) {
					echo '<a href="' . get_permalink() . '" title ="' . get_the_title() . '"><img alt="' . get_the_title() . '" ';
					if (isset($post_image) && $post_image != '') echo 'src="' . r_image_resize(60, 60, $post_image) . '"';
					else echo 'src="' . THEME_URI . '/styles/default/no-photo.jpg"';
					echo '/></a>';
					$thumb_class = 'class="r_post_thumb"';
				}
				$title = '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
				echo '<p ' . $thumb_class . '>';	
				echo $title;
				echo $excerpt;
				echo '</p>';
      			echo '</li>';
			}
			
        	echo '</ul>';
        }
        
		echo $after_widget;
		
	}

	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['posts_cat'] = $new_instance['posts_cat'];
		$instance['posts_limit'] = $new_instance['posts_limit'];
		$instance['length'] = $new_instance['length'];
		$instance['show_thumb'] = $new_instance['show_thumb'];
		
		return $instance;
		
	}

	function form($instance) {
		
		$defaults = array('title' => _x('Recent Posts', 'r-posts', SHORT_NAME), 'posts_cat' => '_all', 'posts_limit' => '3', 'length' => '10', 'show_thumb' => 'true');
		$instance = wp_parse_args((array)$instance, $defaults);
		echo '<p>';
		echo '<label for="' . $this->get_field_id('title') . '">' . _x('Title:', 'r-widget', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" type="text" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" class="widefat" />';
		echo '</p>';
        echo '<p>';
		echo '<label for="' . $this->get_field_id('posts_cat') . '">' . _x('Select category:', 'r-posts', SHORT_NAME) . '</label>';
		echo '<select id="' . $this->get_field_id('posts_cat') . '" name="' . $this->get_field_name('posts_cat') . '" class="widefat" style="width:100%;">';
		
		if ($instance['posts_cat'] == '_all') $selected = 'selected="selected"';
		else $selected = '';
		
		echo '<option ' . $selected . ' value="_all">' . _x('All', 'r-posts', SHORT_NAME) . '</option>';
			
		foreach((get_categories()) as $category) {
				
			if ($instance['posts_cat'] == $category->cat_name) $selected = 'selected="selected"';
			else $selected = '';
				
     		echo '<option ' . $selected . ' value="' . $category->cat_name . '">' . $category->cat_name . '</option>';
		}
			
		echo '</select>';
		echo '</p>';
        echo '<p>';
		echo '<label for="' . $this->get_field_id('posts_limit') . '">' . _x('Number of posts:', 'r-posts', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('posts_limit') . '" type="text" name="' . $this->get_field_name('posts_limit') . '" value="' . $instance['posts_limit'] . '" class="widefat" />';
		echo '</p>';
		
		echo '<p>';
		echo '<label for="' . $this->get_field_id('length') . '">' . _x('Type the word limit for the post:', 'r-posts', SHORT_NAME) . '</label>';
		echo '<input id="' . $this->get_field_id('length') . '" type="text" name="' . $this->get_field_name('length') . '" value="'.$instance['length'].'" class="widefat" />';
		echo '<small style="line-height:12px;">' . _x('Enter the number of words eg 10.', 'r-posts', SHORT_NAME) . '</small>';
		echo '</p>';
		
        echo '<p>';
        
		if ($instance['show_thumb']) $checked = 'checked="checked"';
		else $checked = '';
		
		echo '<input class="checkbox" type="checkbox" value="true" id="' . $this->get_field_id('show_thumb') . '" ' . $checked . ' name="' . $this->get_field_name('show_thumb') . '" />';
		echo '<label for="' . $this->get_field_id('show_thumb') . '"> ' . _x('Display post thumbnails', 'r-posts', SHORT_NAME) . '</label>';
		echo '</p>';
		
	}
	
}

register_widget('r_posts_widget');

?>