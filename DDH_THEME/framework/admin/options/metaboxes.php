<?php

/* Page Options
 ------------------------------------------------------------------------*/
 
/* Meta info */ 
$meta_info = array('title' => __('Page Options', SHORT_NAME), 'id'=>'r_page_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template'=>array('default'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Page Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png')
							 ),
		    'id' => '_page_layout',
			'desc' => __('Choose the page layout.', SHORT_NAME)
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
if (is_admin()) $page_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Post Options
 ------------------------------------------------------------------------*/
 
/* Meta info */ 
$meta_info = array('title' => __('Post Options', SHORT_NAME), 'id'=>'r_post_options', 'page'=>array('post'), 'context'=>'normal', 'priority'=>'high', 'callback'=>'', 'template'=>array('post'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Post Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png')
							 ),
		    'id' => '_post_layout',
			'desc' => __('Choose the post layout.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Post Image', SHORT_NAME),
		   'type' => 'upload_image',
		   'id' => array(
						 array('id' => '_post_image', 'std' => ''),
						 array('id' => '_post_image_crop', 'std' => 'c')
		                 ),
		   'thumb_width' => '300',
		   'thumb_height' => '148',
		   'desc' => __('Post image.', SHORT_NAME)
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
if (is_admin()) $post_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Homepage Options
 ------------------------------------------------------------------------*/
 
/* Meta info */ 
$meta_info = array('title' => __('Homepage Options', SHORT_NAME), 'id'=>'r_home_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template'=>array('template-home.php'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Slider Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_slider_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select slider category.', SHORT_NAME),
  		   'id' => '_slider_category'
		  )
);

/* Add class instance */
if (is_admin()) $homepage_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Homepage Blog Options (Template)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => __('Homepage Blog Options', SHORT_NAME), 'id'=>'r_homepage_blog_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-home-blog.php'));

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Blog Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png'),
							 array('id' => 'big_thumb', 'image' => 'big_thumb.png'),
							 array('id' => 'small_thumb', 'image' => 'small_thumb.png'),
							 array('id' => 'small_thumb_wide', 'image' => 'small_thumb_wide.png')
							 ),
		    'id' => '_blog_layout',
			'desc' => __('Choose the blog layout.', SHORT_NAME)
		  ),		   
	array (
		   'label' => __('Blog Category', SHORT_NAME),
		   'type' => 'categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Categories without posts are not displayed.', SHORT_NAME),
  		   'id' => '_blog_category'
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
		   'label' => __('Slider Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_slider_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select slider category.', SHORT_NAME),
  		   'id' => '_slider_category'
		  )
);

/* Add class instance */
if (is_admin()) $homepage_blog_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Blog Options (Template)
 ------------------------------------------------------------------------*/
 
/* Meta info */
$meta_info = array('title' => __('Blog Options', SHORT_NAME), 'id'=>'r_blog_options', 'page'=>array('page'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template' => array('template-blog.php'));

/* Meta options */
$meta_options = array(
					  
	array (
		   'label' => __('Blog Layout', SHORT_NAME),
		   'type' => 'select_image',
		   'std' => 'sidebar_right',
		   'images' => array(
							 array('id' => 'sidebar_right', 'image' => 'sidebar_right.png'),
							 array('id' => 'wide', 'image' => 'wide.png'),
							 array('id' => 'big_thumb', 'image' => 'big_thumb.png'),
							 array('id' => 'small_thumb', 'image' => 'small_thumb.png'),
							 array('id' => 'small_thumb_wide', 'image' => 'small_thumb_wide.png')
							 ),
		    'id' => '_blog_layout',
			'desc' => __('Choose the blog layout.', SHORT_NAME)
		  ),		   
	array (
		   'label' => __('Blog Category', SHORT_NAME),
		   'type' => 'categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Categories without posts are not displayed.', SHORT_NAME),
  		   'id' => '_blog_category'
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
if (is_admin()) $blog_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Intro Options
 ------------------------------------------------------------------------*/
 
/* Meta info */ 
$meta_info = array('title' => __('Intro Options', SHORT_NAME), 'id'=>'r_intro_options', 'page'=>array('post', 'page', 'wp_portfolio', 'wp_events_manager'), 'context'=>'normal', 'priority'=>'high', 'callback'=>'', 'template'=>array('post', 'default', 'template-home.php', 'template-blog.php', 'template-home-blog.php', 'template-events.php', 'template-archives.php', 'template-portfolio.php', 'template-portfolio2.php', 'template-music.php'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Intro Layout', SHORT_NAME),
		   'type' => 'select',
		   'std' => 'disabled',
		   'options' => array(
							 array('name' => 'Disabled', 'value' => 'disabled'),
							 array('name' => 'Image', 'value' => 'image'),
							 array('name' => 'Slider', 'value' => 'slider'),
							 array('name' => 'Custom Text', 'value' => 'text')
							 ),
		    'id' => '_intro_source',
			'desc' => __('Choose the intro layout.', SHORT_NAME)
		  ),		   	
	array (
		   'label' => __('Intro Image', SHORT_NAME),
		   'type' => 'upload_image',
		   'id' => array(
						 array('id' => '_intro_image', 'std' => ''),
						 array('id' => '_intro_image_crop', 'std' => 'c')
		                 ),
		   'thumb_width' => '300',
		   'thumb_height' => '60',
		   'desc' => __('Intro image.', SHORT_NAME)
		  ),
	array (
		   'label' => __('Slider Category', SHORT_NAME),
		   'type' => 'taxonomy',
		   'taxonomy' => 'wp_slider_categories',
		   'options' => array(
							 array('name' => '_all', 'label' => 'All')
							 ),
		   'desc' => __('Select slider category.', SHORT_NAME),
  		   'id' => '_intro_category'
		  ),
	array (
		   'label' => __('Intro Text', SHORT_NAME),
		   'type' => 'textarea',
		   'std' => '',
  		   'id' => '_intro_text',
		   'height' => '100',
		   'desc' => __('Add text to the intro section below the title. <br/> If you want to change the colour of the text use: &lt;span&gt;&lt;/span&gt;', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $intro_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);


/* Facebook Image
 ------------------------------------------------------------------------*/
 
/* Meta info */ 
$meta_info = array('title' => __('Facebook Image', SHORT_NAME), 'id'=>'r_fb_options', 'page'=>array('post', 'page', 'wp_portfolio', 'wp_events_manager'), 'context'=>'side', 'priority'=>'high', 'callback'=>'', 'template'=>array('post', 'default', 'template-home.php', 'template-blog.php', 'template-home-blog.php', 'template-events.php', 'template-archives.php', 'template-portfolio.php', 'template-portfolio2.php', 'template-music.php'));	

/* Meta options */
$meta_options = array(
	array (
		   'label' => __('Facebook Image', SHORT_NAME),
		   'type' => 'upload_image',
		   'id' => array(
						 array('id' => 'facebook_image', 'std' => '')
		                 ),
		   'thumb_width' => '160',
		   'thumb_height' => '160',
		   'desc' => __('Facebook image.', SHORT_NAME)
		  )

);

/* Add class instance */
if (is_admin()) $fb_image_options_box = new r_meta_box($meta_options, $meta_info);

/* Remove variables */
unset($meta_options, $meta_info);

?>