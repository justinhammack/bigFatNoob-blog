<?php

/* Events Manager Options
 ------------------------------------------------------------------------*/

/* Class arguments */
$args = array('post_name' => 'wp_events_manager', 'sortable' => false);

/* Post Labels */
$labels = array(
				'name' => _x('Events', 'events manager | post type general name', SHORT_NAME),
				'singular_name' => _x('Events Manager', 'events manager | post type general name', SHORT_NAME),
				'add_new' => _x('Add New', 'events manager | post type general name', SHORT_NAME),
				'add_new_item' => __('Add New Event', SHORT_NAME),
				'edit_item' => __('Edit Event', SHORT_NAME),
				'new_item' => __('New Event', SHORT_NAME),
				'view_item' => __('View Event', SHORT_NAME),
				'search_items' => __('Search Items', SHORT_NAME),
				'not_found' =>  __('No events found', SHORT_NAME),
				'not_found_in_trash' => __('No events found in Trash', SHORT_NAME), 
				'parent_item_colon' => ''
);

/* Post Options */
$options = array(
			  'labels' => $labels,
			  'public' => true,
			  'show_ui' => true,
			  'capability_type' => 'post',
			  'hierarchical' => false,
			  'rewrite' => array(
								 'slug' => 'events',
								 'with_front' => FALSE,
								 ),
			  'supports' => array('title', 'editor', 'excerpt', 'comments'),
			  'menu_position' => 100,
			  'menu_icon' => ADMIN_URI . '/images/r-panel/icon-events-manager.png',
			  'show_in_nav_menus' => false
);

/* Add class instance */
$events_manager = new r_custom_post_type($args, $options);


/* Add Taxonomy */
register_taxonomy('wp_event_type', array('wp_events_manager'), 
    array(
		 'hierarchical' => true,
		 'label' => _x('Event Type', 'events manager | post type taxonomy', SHORT_NAME),
		 'singular_label' => _x('Event Type', 'events manager | post type taxonomy', SHORT_NAME),
		 'show_ui' => true,
		 'query_var' => true,
		 'capabilities' => array(
								 'manage_terms' => 'manage_divisions',
								 'edit_terms' => 'edit_divisions',
								 'delete_terms' => 'delete_divisions',
								 'assign_terms' => 'edit_posts'
								),
		 'rewrite' => array('slug' => 'event-type'),
		 'show_in_nav_menus' => false
	));


/* Remove variables */
unset($labels, $options);

/* Settings */
$time_zone = 'local_time'; /* local_time, server_time, UTC */

/* Timezone */
$current_date = array();
$current_date['local_time'] = date('Y-m-d', current_time('timestamp', 0));
$current_date['server_time'] = date('Y-m-d', current_time('timestamp', 1));
$current_date['UTC'] = date('Y-m-d');
$current_date = $current_date[$time_zone];

/* Insert default taxonomy */
function r_insert_taxonomy($cat_name, $parent, $description, $taxonomy) {
	global $wpdb;

	if (!term_exists($cat_name, $taxonomy)) {
		$args = compact(
						$wpdb->escape(__($cat_name)),
						$cat_slug = sanitize_title(_c($cat_name)),
						$parent = 0,
						$description = ''
						);
		wp_insert_term($cat_name, $taxonomy, $args);
		return;
	}
  return;
}


if (is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' && SHOW_ACTIVATION == true) {
    r_insert_taxonomy('Future events', 0, '', 'wp_event_type');
    r_insert_taxonomy('Past events', 0, '', 'wp_event_type');
}

/* Get Taxonomy ID */
function r_get_taxonomy_id($cat_name, $taxonomy) {
	
	$args = array(
				  'hide_empty' => false
				  );
	
	$taxonomies = get_terms($taxonomy, $args);
	if ($taxonomies) {
		foreach($taxonomies as $taxonomy) {
			
			if ($taxonomy->name == $cat_name) {
				return $taxonomy->term_id;
			}
			
		}
	}
	
	return false;
}


/* Column Layout
 ------------------------------------------------------------------------*/
function event_manager_columns($columns) {
	global $current_date;
	
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => _x('Event Title', 'events manager | post type column', SHORT_NAME),
		'event_date' => _x('Event Date', 'events manager | post type column', SHORT_NAME) . ' (' . $current_date . ')',
		'event_days' => _x('Days', 'events manager | post type column', SHORT_NAME),
		'event_days_left' => _x('Days Left', 'events manager | post type column', SHORT_NAME),
		'event_type' => _x('Event Type', 'events manager | post type column', SHORT_NAME),
		'image_preview' => _x('Image Preview', 'events manager | post type column', SHORT_NAME)
	);

	return $columns;
}
add_filter('manage_edit-wp_events_manager_columns', 'event_manager_columns');

function event_manager_display_columns($column) {
	global $post, $current_date;
	
	$today = strtotime($current_date);
	
	switch ($column) {
		case 'event_date':
			$event_date_start = get_post_custom();
			$event_date_end = get_post_custom();
			echo $event_date_start['_event_date_start'][0] . ' - ' . $event_date_end['_event_date_end'][0];
			break;
		case 'event_days' :
			$event_date_start = get_post_custom();
			$event_date_end = get_post_custom();
			echo r_days_left($event_date_start['_event_date_start'][0], $event_date_end['_event_date_end'][0], 'days');
			break;
		case 'event_days_left' :
			$event_date_start = get_post_custom();
			$event_date_end = get_post_custom();
			echo r_days_left($event_date_start['_event_date_start'][0], $event_date_end['_event_date_end'][0], 'days_left');
			break;
		case 'event_type' :
				$taxonomies = get_the_terms($post->ID, 'wp_event_type');
				$event_date_end = get_post_custom();
				if ($taxonomies) {
					foreach($taxonomies as $taxonomy) {
						if (strtotime($event_date_end['_event_date_end'][0]) >= $today && $taxonomy->name == 'Future events') 
						    echo '<strong>' . $taxonomy->name . '</strong>';
						else 
						    echo $taxonomy->name;
					}
				}
				break;
		case 'image_preview':
			$custom = get_post_custom();
			if (!isset($custom['_event_image'][0]) || $custom['_event_image'][0] == '') {
				echo '<img src="'. ADMIN_URI . '/images/r-panel/no-photo.png" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
			} else {
				if (isset($custom['_event_image'][0]) && r_image_exists($custom['_event_image'][0]))
				echo '<img src="' . r_image_resize('130', '60', $custom['_event_image'][0]) . '" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
				else echo __('The link is incorrect or the image does not exist.', SHORT_NAME);
			}
			break;
	}
}

add_action('manage_posts_custom_column', 'event_manager_display_columns');


/* Menage Events
 ------------------------------------------------------------------------*/
function manage_events() {
	global $post, $current_date;
	
	$backup = $post;
	$today = strtotime($current_date);
	$args = array(
				  'post_type' => 'wp_events_manager',
				  'wp_event_type' => 'Future events',
				  'post_status' => 'publish, pending, draft, future, private, trash',
				  'numberposts' => '-1',
				  'orderby' => 'meta_value',  
                  'meta_key' => '_event_date_end',
                  'order' => 'ASC',
				  'meta_query' => array(array('key' => '_event_date_end', 'value' => date('Y-m-d'), 'compare' => '<', 'type' => 'DATE')),
				  );
	$events = get_posts($args);
	
 	foreach($events as $event) {
		
		$event_date_start = strtotime(get_post_meta($event->ID, '_event_date_start', true));
		$event_date_end = strtotime(get_post_meta($event->ID, '_event_date_end', true));
		$event_date_end_temp = get_post_meta($event->ID, '_event_date_end', true);
		
		/* Move Events */
		wp_set_post_terms($event->ID, r_get_taxonomy_id('Past events', 'wp_event_type'), 'wp_event_type', false);
	}
	$post = $backup; 
	wp_reset_query();
}

/* Shelude events */
if (false === ($event_task = get_transient('event_task'))) {
    $current_time = time();
	manage_events();
	set_transient('event_task', $current_time, 60*60);
}
//delete_transient('event_task');

/* Save Events */
function save_postdata_events() {
    global $current_date;
	
	if (isset($_POST['post_ID'])) $post_id = $_POST['post_ID'];
	else return;
    
    if ($_POST['post_type'] == 'wp_events_manager') {
		
        $today = strtotime($current_date, $current_date);
	    $event_date_start = strtotime(get_post_meta($post_id, '_event_date_start', true));
	    $event_date_end = strtotime(get_post_meta($post_id, '_event_date_end', true));
		
        /* Add Default Date */
	    if (!$event_date_start) {
	  	    add_post_meta($post_id, '_event_date_start', date('Y-m-d', $today));
	    }
	    if (!$event_date_end) {
		    add_post_meta($post_id, '_event_date_end', get_post_meta($post_id, '_event_date_start', true));
	    }
	    if ($event_date_end < $event_date_start) {
		    update_post_meta($post_id, '_event_date_end', get_post_meta($post_id, '_event_date_start', true));
	    }
		
		$event_date_start = strtotime(get_post_meta($post_id, '_event_date_start', true));
	    $event_date_end = strtotime(get_post_meta($post_id, '_event_date_end', true));
		
		/* Add Default Term */
		$taxonomies = get_the_terms($event->ID, 'wp_event_type');
		if (!$taxonomies) {
			wp_set_post_terms($post_id, r_get_taxonomy_id('Future events', 'wp_event_type'), 'wp_event_type', false);	
		}
	    if ($event_date_end >= $today) {
	  	    if (is_object_in_term($post_id, 'wp_event_type', 'Past events'))
	        wp_set_post_terms($post_id, r_get_taxonomy_id('Future events', 'wp_event_type'), 'wp_event_type', false);	
	    } else {	
	        if (is_object_in_term($post_id, 'wp_event_type', 'Future events'))
		    wp_set_post_terms($post_id, r_get_taxonomy_id('Past events', 'wp_event_type'), 'wp_event_type', false);
	    }
		
    }
	
}

add_action('wp_insert_post', 'save_postdata_events');

/* Custom Order */
function events_manager_order($query) {
	global $r_option;
	
	if (is_admin()) {
	    $post_type = $query->query['post_type'];
    	if ($post_type == 'wp_events_manager') {
		    if (isset($r_option['events_order']) && $r_option['events_order'] == 'start_date') $events_order = '_event_date_start';
		    else $events_order = '_event_date_end';
				   
			    if (get_bloginfo('version') >= 3.1 ) {
			        $query->query_vars['meta_key'] = $events_order;
			        $query->query_vars['orderby'] = 'meta_value';
			        $query->query_vars['order'] = 'asc';
			        $query->query_vars['meta_query'] = array( array( 'key' => $events_order, 'value' => '1900-01-01', 'compare' => '>', 'type' => 'NUMERIC') );
                } else {
			        $query->query_vars['orderby'] = 'meta_value';
			        $query->query_vars['meta_key'] = $events_order;
			        $query->query_vars['order'] = 'ASC';
				}
    	}
  	}
  
}
add_filter('parse_query', 'events_manager_order');


/* Column Filter
 ------------------------------------------------------------------------*/
function add_events_filter() {

    // only display these taxonomy filters on desired custom post_type listings
    global $typenow;
    if ($typenow == 'wp_events_manager') {
        $args = array('name' => 'wp_event_type');
        // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
        $filters = get_taxonomies($args);

        foreach ($filters as $tax_slug) {
            // retrieve the taxonomy object
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;

            // output html for taxonomy dropdown filter
            echo '<select name="' . $tax_slug. '" id="' . $tax_slug . '" class="postform">';
			echo '<option value="">' . __('Show All', SHORT_NAME) . '</option>';
            generate_taxonomy_options($tax_slug,0,0);
            echo "</select>";
        }
    }
}
add_action('restrict_manage_posts', 'add_events_filter');

/* Add Filter - Request */
function events_request($request) {
	if (is_admin() && isset($request['post_type']) && $request['post_type'] == 'wp_events_manager' && isset($request['wp_event_type'])) {
		$term = get_term($request['wp_event_type'], 'wp_event_type');
		if ($term) {
			$term = $term->name;
			$request['term'] = $term;
		}
	}
	return $request;
}
add_action('request', 'events_request');

/* Days left */
function r_days_left($start_date, $end_date, $type) {
	global $current_date;
	
	$now = strtotime($current_date);
	$start_date = strtotime($start_date);
	$end_date = strtotime($end_date);
	
	/* Days left to start date */
	$hours_left_start = (mktime(0, 0, 0, date('m', $start_date), date('d', $start_date), date('Y', $start_date)) - $now)/3600;
	$days_left_start = ceil($hours_left_start/24);
	
	/* Days left to end date */
	$hours_left_end = (mktime(0, 0, 0, date('m', $end_date), date('d', $end_date), date('Y', $end_date)) - $now)/3600;
	$days_left_end = ceil($hours_left_end/24);
	$days_number = ($days_left_end - $days_left_start) + 1;
	
	if ($type == 'days') {
		return $days_number;
	}
	
	if ($type == 'days_left') {
		
		/* If future events */
		if ($days_left_end >= 0) {
		
			if ($days_left_start == 0) {
				return '<span style="color:red;font-weight:bold">'. __('Start Today', SHORT_NAME) .'</span>';
			}
			elseif ($days_left_start < 0 ) {
				return '<span style="color:red;font-weight:bold">' . __('Continued', SHORT_NAME) . '</span>';
			}
			elseif ($days_left_start > 0) {
				return $days_left_start;
			}
		
		} else return '-- --';
	}
	
}


/* Metaboxes
 ------------------------------------------------------------------------*/
 
 
/* Event Date Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => 'Event Date', 'id'=>'r_event_date_options', 'page'=>array('wp_events_manager'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Event Date', SHORT_NAME),
		   'type' => 'date_range',
  		   'id' => array(
						 array('id' => '_event_date_start', 'std' => date('Y-m-d')),
						 array('id' => '_event_date_end', 'std' => date('Y-m-d'))
		                 ),
		   'desc' => __('Type event date eg 2010-09-11', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $event_date_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Event Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => 'Event Options', 'id'=>'r_event_options', 'page'=>array('wp_events_manager'), 'context'=>'normal', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(
     array (
		   'label' => __('Event Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png')
							 ),
		    'id' => '_event_layout',
			'desc' => __('Choose the event layout.', SHORT_NAME)
		  ),
     array (
		   'label' => __('Image', SHORT_NAME),
		   'type' => 'upload_image',
		   'id' => array(
						 array('id' => '_event_image', 'std' => ''),
						 array('id' => '_event_image_crop', 'std' => 'c')
		                 ),
		   'thumb_width' => '300',
		   'thumb_height' => '148',
		   'desc' => __('Event image.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Custom Sidebar', SHORT_NAME),
		   'type' => 'array_select',
		   'array' => 'custom_sidebars',
		   'key' => 'name',
		   'options' => array(
							 array('name' => '_default', 'label' => 'Default')
							 ),
		   'desc' => __('Select custom sidebar or select default sidebar.', SHORT_NAME),
  		   'id' => '_custom_sidebar'
		  ),
	array (
		   'label' => __('Disable Post Date', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_post_date',
		   'desc' => __('If this opion is off, you should see post date.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Disable Author Information', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_post_author_info',
		   'desc' => __('If this opion is off, you should see author information.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Disable Bookmarks', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_post_bookmarks',
		   'desc' => __('If this opion is off, you should see bookmarks icons.', SHORT_NAME)
		  )
					  
);

/* Add class instance */
if (is_admin()) $events_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Event Options (Template)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => 'Events Options', 'id'=>'r_event_template_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-events.php'));	

/* Meta options */
$meta_options = array(
    array (
		   'label' => __('Events Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png'),
							 array('id' => 'big_thumb', 'image' => 'big_thumb.png'),
							 array('id' => 'small_thumb', 'image' => 'small_thumb.png'),
							 array('id' => 'small_thumb_wide', 'image' => 'small_thumb_wide.png')
							 ),
		    'id' => '_events_layout',
			'desc' => __('Choose the events layout.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Events Items Per Page', SHORT_NAME),
		   'type' => 'text',
		   'std' => '6',
  		   'id' => '_limit',
		   'desc' => __('Number of events to display per page.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Event Type', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_event_type',
		   'desc' => __('Select event type', SHORT_NAME),
  		   'id' => '_event_type'
		  ),
	array (
		   'label' => __('Custom Sidebar', SHORT_NAME),
		   'type' => 'array_select',
		   'array' => 'custom_sidebars',
		   'key' => 'name',
		   'options' => array(
							 array('name' => '_default', 'label' => 'Default')
							 ),
		   'desc' => __('Select custom sidebar or select default sidebar.', SHORT_NAME),
  		   'id' => '_custom_sidebar'
		  )
				  
);

/* Add class instance */
if (is_admin()) $events_template_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);
?>