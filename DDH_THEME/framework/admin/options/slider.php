<?php

/* Slider Options
 ------------------------------------------------------------------------*/
 
/* Class arguments */
$args = array('post_name' => 'wp_slider', 'sortable' => true);

/* Post Labels */
$labels = array(
				'name' => _x('Slider', 'slider | post type general name', SHORT_NAME),
				'singular_name' => _x('Slider', 'slider | post type singular name', SHORT_NAME),
				'add_new' => _x('Add New', 'slider', SHORT_NAME),
				'add_new_item' => __('Add New Slider Item', SHORT_NAME),
				'edit_item' => __('Edit Slider Item', SHORT_NAME),
				'new_item' => __('New Slider Item', SHORT_NAME),
				'view_item' => __('View Slider Item', SHORT_NAME),
				'search_items' => __('Search Items', SHORT_NAME),
				'not_found' =>  __('No homepage slider items found', SHORT_NAME),
				'not_found_in_trash' => __('No homepage slider items found in Trash', SHORT_NAME), 
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
								 'slug' => 'sliders',
								 'with_front' => false,
								 ),
			  'supports' => array('title', 'editor'),
			  'menu_position' => 100,
			  'menu_icon' => ADMIN_URI . '/images/r-panel/icon-slider.png'
);

/* Add class instance */
$sliders_manager = new r_custom_post_type($args, $options);

/* Add Taxonomy */
register_taxonomy('wp_slider_categories', array('wp_slider'), array(
															 'hierarchical' => true,
															 'label' => _x('Categories', 'slider | post type taxonomy', SHORT_NAME),
															 'singular_label' => _x('Category', 'slider | post type taxonomy', SHORT_NAME),
															 'query_var' => true,
     														 'rewrite' => array('slug' => 'slider-category')
															 ));

/* Remove variables */
unset($args, $options);


/* Column Layout
 ------------------------------------------------------------------------*/
function slider_columns($columns) {
	
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => _x('Title', 'slider | post type column', SHORT_NAME),
		'slider_preview' => _x('Preview', 'slider | post type column', SHORT_NAME),
		'type' => _x('Slider type', 'slider | post type column', SHORT_NAME),
		'taxonomies' => _x('Categories', 'slider | post type column', SHORT_NAME),
		'date' => _x('Date', 'slider | post type column', SHORT_NAME)
	);

	return $columns;
}
add_filter('manage_edit-wp_slider_columns', 'slider_columns');

function slider_display_columns($column) {
	global $post;
	
	switch ($column) {
		case 'slider_preview':
			$image = get_post_custom();
			$type = get_post_custom();
			if (isset($type['_slider_type'][0]) && $type['_slider_type'][0] == 'Youtube') {
				echo '<img src="'. ADMIN_URI . '/images/r-panel/logo-youtube.png" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
			} else if (isset($type['_slider_type'][0]) && $type['_slider_type'][0] == 'Vimeo') {
				echo '<img src="'. ADMIN_URI . '/images/r-panel/logo-vimeo.png" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
			} else {
			if (!isset($image['_slider_image'][0]) || $image['_slider_image'][0] == '') {
				echo '<img src="'. ADMIN_URI . '/images/r-panel/no-photo.png" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
			} else {
				if (isset($image['_slider_image'][0]) && r_image_exists($image['_slider_image'][0]))
				echo '<img src="' . r_image_resize('130', '60', $image['_slider_image'][0]) . '" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
				else echo __('The link is incorrect or the image does not exist.', SHORT_NAME);
			}
			}
			break;
		case 'type':
			$type = get_post_custom();
			if (isset($type['_slider_type'][0])) echo $type['_slider_type'][0];
			break;
		case 'taxonomies' :
			$taxonomies = get_the_terms($post->ID, 'wp_slider_categories');
			if ($taxonomies) {
				foreach($taxonomies as $taxonomy) {
					echo $taxonomy->name . ' ';
				}
			}
			break;
	}
}

add_action('manage_posts_custom_column', 'slider_display_columns');


/* Column Filter
 ------------------------------------------------------------------------*/
function add_slider_filter() {

	// only display these taxonomy filters on desired custom post_type listings
	global $typenow;
	if ($typenow == 'wp_slider') {
		$args = array('name' => 'wp_slider_categories');
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
add_action('restrict_manage_posts', 'add_slider_filter');

/* Add Filter - Request */
function slider_request($request) {
	if (is_admin() && isset($request['post_type']) && $request['post_type'] == 'wp_slider' && isset($request['wp_slider_categories'])) {
		
	    $term = get_term($request['wp_slider_categories'], 'wp_slider_categories');
		if (is_array($term)) {
			$term = $term->name;
			$request['term'] = $term;
		}
		
	}
	return $request;
}
add_action('request', 'slider_request');


/* Metaboxes
 ------------------------------------------------------------------------*/


/* Slider Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Slider Options', 'slider | post type metabox', SHORT_NAME), 'id'=>'r_slider_options', 'page'=>array('wp_slider'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Slider Type', SHORT_NAME),
		   'type' => 'select',
		   'options' => array(
							  array('name' => 'Image', 'value' => 'Image'),
							  array('name' => 'Image with lightbox', 'value' => 'Image with lightbox'),
							  array('name' => 'Image with link', 'value' => 'Image with link'),
							  array('name' => 'Image with music', 'value' => 'Image with music'),
							  array('name' => 'Youtube', 'value' => 'Youtube'),
							  array('name' => 'Vimeo', 'value' => 'Vimeo')
							 ),
		    'id' => '_slider_type',
			'desc' => __('Select slider type.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Image Effect', SHORT_NAME),
		   'type' => 'select',
		   'options' => array(
							  array('name' => 'Fade', 'value' => 'fade'),
							  array('name' => 'Horizontal', 'value' => 'horizontal'),
							  array('name' => 'Vertical', 'value' => 'vertical'),
							  array('name' => 'Fold', 'value' => 'fold'),
							  array('name' => 'Horizontal slice', 'value' => 'horizontal_slice'),
							  array('name' => 'Verical slice', 'value' => 'vertical_slice'),
							  array('name' => 'Vertical mirror', 'value' => 'vertical_mirror')
							 ),
		    'id' => '_image_effect',
			'desc' => __('Select image effect.', SHORT_NAME)
		  ),		   
	array (
		   'label' => __('Lightbox Link', SHORT_NAME),
		   'type' => 'text',
  		   'id' => '_lightbox_link',
		   'desc' => __('Paste the full URL (include http://) of your image or video would like to use for jQuery lightbox pop-up effect. Examples on how to format the links can be found here: <br /><a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/" target="_blank">PrettyPhoto</a> under Demos on there homepage.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Custom Link URL', SHORT_NAME),
		   'type' => 'easy_link',
  		   'id' => array(
						 array('id' => '_link_url', 'std' => ''),
						 array('id' => '_target', 'std' => 'off')
						 ),
		   'desc' => __('Paste the full URL (include http://) of your link or click and select from your site.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Hide Slider Description', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_hide_description',
		   'desc' => __('If this opion is off, you should see slider description.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Hide Slider Title', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_hide_title',
		   'desc' => __('If this opion is off, you should see slider title.', SHORT_NAME)
		  ),
);

/* Add class instance */
if (is_admin()) $slider_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Slider Media Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Slider Media', 'slider | post type metabox', SHORT_NAME), 'id'=>'r_slider_media', 'page'=>array('wp_slider'), 'context'=>'normal', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Image', SHORT_NAME),
		   'type' => 'upload_image',
  		   'id' => array(
						 array('id' => '_slider_image', 'std' => ''),
						 array('id' => '_slider_image_crop', 'std' => 'c')
		                 ),
		   'thumb_width' => '300',
		   'thumb_height' => '121',
		   'desc' => __('Slider image. <br/> ', SHORT_NAME)
		  ),
	array (
		   'label' => __('Music', SHORT_NAME),
		   'type' => 'upload_audio',
  		   'id' => '_music',
		   'desc' => __('Add Music File.', SHORT_NAME)
		  ),
	array (
		   'label' => __('YouTube ID', SHORT_NAME),
		   'type' => 'text',
  		   'id' => '_youtube',
		   'desc' => __('Paste the ID of your YouTube video link would like to see e.g. uaECFlh6ru0', SHORT_NAME)
		  ),
	array (
		   'label' => __('Vimeo ID', SHORT_NAME),
		   'type' => 'text',
  		   'id' => '_vimeo',
		   'desc' => __('Paste the ID of your Vimeo video link would like to see e.g. 6045312', SHORT_NAME)
		  )
);

/* Add class instance */
if (is_admin()) $slider_media_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);

?>