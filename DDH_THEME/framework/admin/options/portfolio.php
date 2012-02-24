<?php

/* Portfolio Options
 ------------------------------------------------------------------------*/
global $r_option;

/* Class arguments */
if (isset($r_option['portfolio_order']) && $r_option['portfolio_order'] == 'custom') $portfolio_sortable = true;
else $portfolio_sortable = false;

$args = array('post_name' => 'wp_portfolio', 'sortable' => $portfolio_sortable);

/* Post Labels */
$labels = array(
				'name' => _x('Portfolio', 'portfolio | post type general name', SHORT_NAME),
				'singular_name' => _x('Portfolio', 'portfolio | post type singular name', SHORT_NAME),
				'add_new' => _x('Add New', 'portfolio', SHORT_NAME),
				'add_new_item' => __('Add New Portfolio Item', SHORT_NAME),
				'edit_item' => __('Edit Portfolio Item', SHORT_NAME),
				'new_item' => __('New Portfolio Item', SHORT_NAME),
				'view_item' => __('View Portfolio Item', SHORT_NAME),
				'search_items' => __('Search Items', SHORT_NAME),
				'not_found' =>  __('No portfolio items found', SHORT_NAME),
				'not_found_in_trash' => __('No portfolio items found in Trash', SHORT_NAME), 
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
								 'slug' => 'portfolio',
								 'with_front' => false,
								 ),
			  'supports' => array('title', 'editor', 'excerpt', 'comments', 'custom-fields'),
			  'menu_position' => 100,
			  'menu_icon' => ADMIN_URI . '/images/r-panel/icon-portfolio.png'
);

/* Add class instance */
$portfolio_manager = new r_custom_post_type($args, $options);

/* Add Taxonomy */
register_taxonomy('wp_portfolio_categories', array('wp_portfolio'), array(
															 'hierarchical' => true,
															 'label' => _x('Categories', 'portfolio | post type taxonomy', SHORT_NAME),
															 'singular_label' => _x('Category', 'portfolio | post type taxonomy', SHORT_NAME),
															 'query_var' => true,
     														 'rewrite' => array('slug' => 'portfolio-category')
															 ));

/* Remove variables */
unset($args, $options);


/* Column Layout
 ------------------------------------------------------------------------*/
function portfolio_columns($columns) {
	
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => _x('Title', 'portfolio | post type column', SHORT_NAME),
		'portfolio_preview' => _x('Preview', 'portfolio | post type column', SHORT_NAME),
		'taxonomies' => _x('Categories', 'portfolio | post type column', SHORT_NAME),
		'date' => 'Date'
	);

	return $columns;
}
add_filter('manage_edit-wp_portfolio_columns', 'portfolio_columns');

function portfolio_display_columns($column) {
	global $post;
	
	switch ($column) {
		case 'portfolio_preview':
			$custom = get_post_custom();
			if (!isset($custom['_portfolio_image'][0]) || $custom['_portfolio_image'][0] == '') {
				echo '<img src="'. ADMIN_URI . '/images/r-panel/no-photo.png" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
			} else {
				if (isset($custom['_portfolio_image'][0]) && r_image_exists($custom['_portfolio_image'][0]))
				echo '<img src="' . r_image_resize('130', '60', $custom['_portfolio_image'][0]) . '" alt="' . esc_attr(get_the_title()) . '" style="padding:5px"/>';
				else echo __('The link is incorrect or the image does not exist.', SHORT_NAME);
			}
			break;
		case 'taxonomies' :
				$taxonomies = get_the_terms($post->ID, 'wp_portfolio_categories');
				if ($taxonomies) {
					foreach($taxonomies as $taxonomy) {
						echo $taxonomy->name . ' ';
					}
				}
			break;
	}
}

add_action('manage_posts_custom_column', 'portfolio_display_columns');


/* Column Filter
 ------------------------------------------------------------------------*/
function add_portfolio_filter() {

	// only display these taxonomy filters on desired custom post_type listings
	global $typenow;
	if ($typenow == 'wp_portfolio') {
		$args = array('name' => 'wp_portfolio_categories');
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
add_action('restrict_manage_posts', 'add_portfolio_filter');

/* Add Filter - Request */
function portfolio_request($request) {
	if (is_admin() && isset($request['post_type']) && $request['post_type'] == 'wp_portfolio' && isset($request['wp_portfolio_categories'])) {
		
	    $term = get_term($request['wp_portfolio_categories'], 'wp_portfolio_categories');
		if (is_array($term)) {
			$term = $term->name;
			$request['term'] = $term;
		}
		
	}
	return $request;
}
add_action('request', 'portfolio_request');


/* Metaboxes
 ------------------------------------------------------------------------*/
 
 
/* Portfolio Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Portfolio Options', 'portfolio | post type metabox', SHORT_NAME), 'id'=>'r_custom_portfolio_options', 'page'=>array('wp_portfolio'), 'context'=>'normal', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(			  
	array (
		   'label' => __('Portfolio Type', SHORT_NAME),
		   'type' => 'select',
		   'options' => array(
							  array('name' => 'Image', 'value' => 'Image'),
							  array('name' => 'Image with lightbox', 'value' => 'Image with lightbox'),
							  array('name' => 'Image with custom lightbox', 'value' => 'Image with custom lightbox'),
							  array('name' => 'Image with link', 'value' => 'Image with link')
							 ),
		    'id' => '_portfolio_type',
			'desc' => __('Select portfolio type.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Image', SHORT_NAME),
		   'type' => 'upload_image',
		   'id' => array(
						 array('id' => '_portfolio_image', 'std' => ''),
						 array('id' => '_portfolio_image_crop', 'std' => 'c')
		                 ),
		   'thumb_width' => '300',
		   'thumb_height' => '148',
		   'desc' => __('Portfolio image.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Music', SHORT_NAME),
		   'type' => 'upload_audio',
  		   'id' => '_music',
		   'desc' => __('Add Music File.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Music Title', SHORT_NAME),
		   'type' => 'text',
  		   'id' => '_music_title',
		   'desc' => __('Music title.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Lightbox Link', SHORT_NAME),
		   'type' => 'text',
  		   'id' => '_lightbox_link',
		   'desc' => __('Paste the full URL (include http://) of your image or video would like to use for jQuery lightbox pop-up effect. Examples on how to format the links can be found here: <br /><a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/" target="_blank">PrettyPhoto</a> under Demos on there homepage.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Lightbox Custom Content', SHORT_NAME),
		   'type' => 'textarea',
		   'tinymce' => 'true',
		   'height' => '200',
  		   'id' => '_lightbox_content',
		   'desc' => __('Lightbox custom content.', SHORT_NAME)
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
		   'label' => __('Portfolio title as a link', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_title_link',
		   'desc' => __('If this option is on, you should see portfolio item title as a link. If you click on this link, you will see your portfolio item.', SHORT_NAME)
		  )
);

/* Add class instance */
if (is_admin()) $porfolio_custom_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Portfolio Item Options
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Portfolio Item Options', 'portfolio | post type metabox', SHORT_NAME), 'id'=>'r_item_portfolio_options', 'page'=>array('wp_portfolio'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('post'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Portfolio Item Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png')
							 ),
		    'id' => '_post_layout',
			'desc' => __('Choose the portfolio item layout.', SHORT_NAME)
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
		   'label' => __('Disable Post Categories', SHORT_NAME),
		   'type' => 'switch_button',
		   'std' => 'off',
  		   'id' => '_post_categories',
		   'desc' => __('If this opion is off, you should see post categories.', SHORT_NAME)
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
if (is_admin()) $porfolio_item_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Portfolio Options (Template)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Portfolio Options', 'portfolio template | post type metabox', SHORT_NAME), 'id'=>'r_portfolio_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-portfolio.php'));

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Porfolio Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => '3',
		   'images' => array(
							 array('id' => '1', 'image' => '1_col.png'),
							 array('id' => '2', 'image' => '2_col.png'),
							 array('id' => '3', 'image' => '3_col.png')
							 ),
		    'id' => '_portfolio_layout',
			'desc' => __('Choose the portfolio layout.', SHORT_NAME)
		  ),  
	array (
		   'label' => __('Portfolio Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_portfolio_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select portfolio category.', SHORT_NAME),
  		   'id' => '_portfolio_category'
		  ),
	array (
		   'label' => __('Portfolio Items Per Page', SHORT_NAME),
		   'type' => 'text',
		   'std' => '6',
  		   'id' => '_limit',
		   'desc' => __('Number of portfolio items to display per page.', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $portfolio_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Portfolio Options (Template 2)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Portfolio Options', 'portfolio template | post type metabox', SHORT_NAME), 'id'=>'r_portfolio_two_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-portfolio2.php'));

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Portfolio Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_portfolio_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select portfolio category.', SHORT_NAME),
  		   'id' => '_portfolio_category'
		  ),
	array (
		   'label' => __('Portfolio Items Per Page', SHORT_NAME),
		   'type' => 'text',
		   'std' => '6',
  		   'id' => '_limit',
		   'desc' => __('Number of portfolio items to display per page.', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $portfolio_two_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Portfolio Music Options (Template)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => _x('Portfolio Options', 'portfolio template | post type metabox', SHORT_NAME), 'id'=>'r_portfolio_music_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-music.php'));

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Portfolio Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_portfolio_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select portfolio category.', SHORT_NAME),
  		   'id' => '_portfolio_category'
		  ),
	array (
		   'label' => __('Portfolio Items Per Page', SHORT_NAME),
		   'type' => 'text',
		   'std' => '6',
  		   'id' => '_limit',
		   'desc' => __('Number of portfolio items to display per page.', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $portfolio_music_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


?>