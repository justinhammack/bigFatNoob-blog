<?php

/* Theme activation
 ------------------------------------------------------------------------*/
 
/* Theme activate */
function r_theme_activate() {

	/* Variables */
	$options = array();
    $defaults = array(
					  'cache' => THEME . '/cache',
					  'test_file' => THEME.'/cache/test.txt',
					  'timthumb' => THEME . '/thumb.php',
					  'test_file_content' => 'This is a test the cache folder \r\n',
					  'title' => 'Welcome',
					  'width' => '500',
					  'height' => '400'
    );
    $options = array_merge($defaults, $options);
    extract($options);
	
	$cache_perms = file_perms(THEME . '/cache/', true);
	$message_cache_dir = '<p class="r-details folder">' . $options['cache'] . '</p>';
	$message_chmod = '<p class="r-details">The folder /cache should have 777 permissions. If this does not work, try giving 755 permissions to cache. See here on how to give files and folders permissions <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">Changing File Permissions</a>.</p>';
	
	$content = '<p>Thank you for purchasing our theme. If you have any support question, please get in touch via Themeforest user page: <a href="http://themeforest.net/user/rascals" target="_blank">profile page</a></p>';
	$content .= '<h3>Check system settings</h3>';
	$content .= '<ul id="r-system-info">';
    /* Check exists cache folder */
	if (file_exists($options['cache'])) $content .= '<li>Cache directory exists</li>';
	else $content .= '<li class="error">Cache directory does not exists</li>';
	
    /* Check writable cache folder */
    if ($handle = @fopen($options['test_file'], 'a')){
        if(is_writable($options['test_file'])) {
            if(fwrite($handle, $options['test_file_content']) === FALSE){
                $content .= '<li class="error">Cannot write to test file</li>';
            }
            $content .= '<li>The cache directory is writable</li>';
            fclose($handle);
			unlink($options['test_file']);
        } else{
             $content .= '<li class="error">The cache directory is not writable! The permissions are set at <strong>' . $cache_perms . '</strong>';
		     $content .= $message_cache_dir;
			 $content .= $message_chmod;
             $content .= '</li>';
        }
    } else {
        $content .= '<li class="error">The cache directory is not writable! The permissions are set at <strong>' . $cache_perms . '</strong>';
		$content .= $message_cache_dir;
		$content .= $message_chmod;
		$content .= '</li>';
	}
	
	/* Check exists cache script */
	if (file_exists($options['timthumb'])) $content .= '<li>Auto resize script exists</li>';
	else $content .= '<li class="error">Auto resize script doesn`t exists!</li>';
	
	/* GD Libary */
	if (extension_loaded('gd') && function_exists('gd_info')) $content .= '<li>GD libary is installed on your server</li>';
	else {
		$content .= '<li class="error">GD Libary is not installed on your server!';
		$content .= '<p class="r-details">Make sure your server has the GD library for PHP enabled. Ask your hosting provider, if you do not know what this means.</p>';
		$content .= '</li>';
	}
	
	/* getimagesize() */
	$src = ADMIN_URI . '/images/r-panel/icon-config.png';
	$image = r_image_exists($src);
	if ($image) $content .= '<li>Function getimagesize() is working well on your server</li>';
	else {
		$content .= '<li class="error">Function getimagesize() is not working on your server!';
		$content .= '</li>';
	}
	$content .= '</ul>';

    
?>
	
<script type="text/javascript">
      // <![CDATA[
	jQuery.noConflict();

    jQuery(document).ready(function () {
									 
	    /* Destroy dialog */
		jQuery('#r-theme-info').remove();
		jQuery('#r-theme-info').dialog('destroy');
		
		/* Dialog content */
		jQuery('body').append('<div id="r-theme-info" title="<?php echo $options['title'] ?>"><?php echo $content ?></div>');

		jQuery('#r-theme-info').dialog({
			show: {effect: 'fade'},
			resizable: false,
			autoOpen: true,
			height: <?php echo $options['height'] ?>,
			width: <?php echo $options['width'] ?>,
			modal: false,
			buttons: {
					Close: function() {
						jQuery(this).dialog('close');
					}
				}
		});

    });

// ]]>
</script>
<?php
}

if (is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' && SHOW_ACTIVATION == true) {
    add_action('admin_head', 'r_theme_activate');
}
 

/* Taxonomy options
 ------------------------------------------------------------------------*/
function generate_taxonomy_options($tax_slug, $parent = '', $level = 0) {
    $args = array('show_empty' => 1);
    if (!is_null($parent)) {
        $args = array('parent' => $parent);
    } 
    $terms = get_terms($tax_slug, $args);
    $tab='';
    for ($i=0; $i<$level; $i++) {
        $tab.='--';
    }
    foreach ($terms as $term) {
        // output each select option line, check against the last $_GET to show the current option selected
        echo '<option value='. $term->slug, isset($_GET[$tax_slug]) && $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
        generate_taxonomy_options($tax_slug, $term->term_id, $level+1);
    }
}


/* Ajax Thumbs Generator
 ------------------------------------------------------------------------*/
function r_ajax_thumbs() {
	$src = $_POST['src'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	$crop = $_POST['crop'];
	
	if (strpos($src, ".ico") !== false) die($src);
	
	$img = r_image_exists($src);
	
	if ($img)
		echo r_image_resize($width, $height, $src, $crop);
	else
		echo 'error';
	exit;
}

add_action('wp_ajax_r_ajax_thumbs', 'r_ajax_thumbs');


/* Easy Link
 ------------------------------------------------------------------------*/
 
function r_easy_link_query( $args = array() ) {
	$pts = get_post_types( array( 'public' => true ), 'objects' );
	$pt_names = array_keys( $pts );

	$query = array(
		'post_type' => $pt_names,
		'suppress_filters' => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'post_status' => 'publish',
		'order' => 'DESC',
		'orderby' => 'post_date',
		'posts_per_page' => 20,
	);

	$args['pagenum'] = isset( $args['pagenum'] ) ? absint( $args['pagenum'] ) : 1;

	if ( isset( $args['s'] ) )
		$query['s'] = $args['s'];

	$query['offset'] = $args['pagenum'] > 1 ? $query['posts_per_page'] * ( $args['pagenum'] - 1 ) : 0;

	// Do main query.
	$get_posts = new WP_Query;
	$posts = $get_posts->query( $query );
	// Check if any posts were found.
	if ( ! $get_posts->post_count )
		return false;

	// Build results.
	$results = array();
	foreach ( $posts as $post ) {
		if ( 'post' == $post->post_type )
			$info = mysql2date( __( 'Y/m/d' ), $post->post_date );
		else
			$info = $pts[ $post->post_type ]->labels->singular_name;

		$results[] = array(
			'ID' => $post->ID,
			'title' => trim( esc_html( strip_tags( get_the_title( $post ) ) ) ),
			'permalink' => get_permalink( $post->ID ),
			'info' => $info,
		);
	}

	return $results;
} 
 
 
/* Easy link Box */
function r_easy_link_box() {
	
    $args = array();
    $args['pagenum'] = 0;
	$results = r_easy_link_query($args);
  
    echo '<div id="r-link" style="display:none">';
	echo '<div id="r-link-search-wrap">';
	echo '<label for="r-link-search">';
	echo '<span>' . __( 'Search' ) . '</span>';
	echo '<input type="text" id="r-link-search" name="r-link-search" tabindex="60" autocomplete="off" value="" />';
	echo '<input type="hidden" id="r-link-target" name="r-link-target" value=""/>';
	echo '</label>';
	echo '<img class="r-link-ajax" src="' . esc_url(admin_url('images/wpspin_light.gif')) . '" alt="" />';
	echo '</div>';
	echo '<div id="r-link-results">';
	echo '<ul>';
	echo '</ul>';
    echo '</div>';
    echo '</div>';

}

if (is_admin()) {
    add_action('admin_footer', 'r_easy_link_box');
}


/* Easy link */
function r_easy_link() {
	
	$pagenum = $_POST['page_num'];
    $args = array();
    $args['pagenum'] = $pagenum;
	
	if (isset($_POST['s']) && $_POST['s'] != '') $args['s'] = stripslashes($_POST['s']);
	
	$results = r_easy_link_query($args);
	if (!isset($results)) die();
	
    $output = '';
	if (!empty($results)) {
		foreach ($results as $i => $result) {
			if ($i % 2 == 0) $odd = 'class="odd"';
			else $odd ='';
		  $output .= '<li ' . $odd . '><span class="r-link-title">' . $result['title'] . '</span><span class="r-link-info">' . $result['info'] . '</span><span class="r-permalink r-hidden">' . $result['permalink'] . '</span><span class="r-link-id r-hidden">' . $result['ID'] . '</span></li>';
		}
	} else {
		$output = 'end pages';
	}

    echo $output;
    exit;
}

add_action('wp_ajax_r_easy_link', 'r_easy_link');


/* Add Menu Link
 ------------------------------------------------------------------------*/
function r_add_menu_link() {
	
	$menu_id = $_POST['menu_id'];
	$menu_label = $_POST['menu_label'];
	$menu_link = $_POST['menu_link'];
	
	if (isset($menu_id) && isset($menu_label) && $menu_label != '' && isset($menu_link) && $menu_link != '') {
		
      wp_update_nav_menu_item($menu_id, 0, array(
          'menu-item-title' =>  $menu_label,
          'menu-item-classes' => '',
          'menu-item-url' => $menu_link, 
          'menu-item-status' => 'publish'));
	  
      update_option('menu_check', true);
      echo 'success';
	} else {
		 die('error - empty fields');
	}
    exit;
}

add_action('wp_ajax_r_add_menu_link', 'r_add_menu_link');

?>