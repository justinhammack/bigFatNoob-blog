<?php

/* Ajax contact form
 ------------------------------------------------------------------------*/
function ajax_r_form() {
	global $r_option;
	
	$nonce = $_POST['ajax_nonce'];
    if (!wp_verify_nonce($nonce, 'ajax-nonce')) 
	    die('Busted!');
 
	/* Variables */
	$data = $_POST['order'];
    $mail_to = $data['mail_to'] && $data['mail_to'] != '' ? $mail_to = $data['mail_to'] : $mail_to = $r_option['email_address'];
    $email = $data['email'];
    $name = $data['name'];
    $subject = $data['subject'];
    $answer = $r_option['answer'];
	$message = '';
	$items = array('asq', 'mail_to');
	
	/* If "Anty Spam Question" exist */
    if (isset($data['asq'])) $question = $data['asq'];
    else $question = false;
	
	if ($question == $answer || $question == false) {
		
	    foreach ($data as $key => $value) {
	        if (!in_array($key, $items)) {
		        $message .= $key . ' : ' . $value . '<br>';
		    }
	    }
	
	    /* Message */
	    $body = "
	    <html>
		    <head>
			    <title>Contact</title>
		    </head>
		    <body>
			    $message
		    </body>
	    </html>
	    ";
	
	    /* E-mail from */
	    $mail_from = "MIME-Version: 1.0\r\n";
	    $mail_from .= "Content-type: text/html; charset=utf-8\r\n";
	    $mail_from .= "From: $name <$email>\r\n";
	
	    $mail = wp_mail($mail_to, $subject, $body, $mail_from);
	
	    /* Send E-mail */
	    if ($mail) echo 'success'; 
	    else echo 'error';
	} else {
		header("Content-Type: application/json");
		echo 'bad_answer';
	}
	exit;
}
add_action('wp_ajax_nopriv_r_form', 'ajax_r_form');
add_action('wp_ajax_r_form', 'ajax_r_form');


/* Custom password form
 ------------------------------------------------------------------------*/
function custom_password_form() {
	global $post;
	$label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
	$o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-pass.php" method="post">
	<p>' . __('This post is password protected. To view it please enter your password below:', SHORT_NAME) . ' </p>
	<label for="' . $label . '">' . __('Password:', SHORT_NAME) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" class="pass"/><input type="submit" name="Submit" value="' .  __(esc_attr__('Submit'), SHORT_NAME) . '" class="submit pass_submit" /></p></form>';
	return $o;
}

add_filter('the_password_form', 'custom_password_form');


/* Breadcrumb navigation
 ------------------------------------------------------------------------*/
function breadcrumb() {
	
	global $wp_query, $post;
	
	/* Bulid navigation */
	function bulid_nav($this_post) {
		if($this_post->post_parent) {
			$parent_post = get_post($this_post->post_parent);
			$link = '<li><a href="' . get_permalink($parent_post->ID) . '">' . get_the_title($parent_post->ID) . '</a></li>';
			bulid_nav($parent_post); 
			return $link;
			
		}
	}
	
	/* Homepage */
	if (is_front_page()) {
		echo '<li class="breadcrumb-home"><a href=" ' . home_url() . ' ">' . __('HOME', SHORT_NAME) . '</a></li>';
		echo '<li class="breadcrumb-title">' . esc_attr(get_bloginfo('name', 'display')) . '</li>';
		return;
	} else {
		echo '<li class="breadcrumb-home"><a href=" ' . home_url() . ' ">' . __('HOME', SHORT_NAME) . '</a></li>';
	}
	
	/* Page */
	if (is_page()) {
		echo bulid_nav($post);
		echo '<li class="breadcrumb-title">' . $post->post_title . '</li>';
		update_option(bulid_nav($post), 'r_breadcrumbs');
		return;
	}
	
	/* Single post */
	if (is_single() && !is_attachment()) {
		if (get_post_type() != 'post') {
			echo '<li class="breadcrumb-title">' . $post->post_title . '</li>';
			return;
		} else {
			$cat = get_the_category(); 
			$cat = $cat[0];
			echo '<li>' . get_category_parents($cat, TRUE, '', FALSE) . '</li>';
			echo '<li class="breadcrumb-title">' . $post->post_title . '</li>';
			return;
		}
    } 
	
	/* Attachment */
	if (is_attachment()) {
		$parent = get_post($post->post_parent);
		$cat = get_the_category($parent->ID);
		$cat = $cat[0];
		echo '<li>' . '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>' . '</li>';
		echo '<li class="breadcrumb-title">' . $post->post_title . '</li>';
		return;
	}
	
	/* Taxonomy */
	if (is_tax()) {
		$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
		$breadcrumbarray[] = $term->term_id;
		$tempterm = $term;
	
		while ($tempterm->parent != 0) {
			$tempterm = get_term_by('id', $tempterm->parent, get_query_var('taxonomy'));
			$breadcrumbarray[] .= $tempterm->term_id;
		}
		$breadcrumbarray = array_reverse($breadcrumbarray);

		foreach($breadcrumbarray as $termid) {
		    $terminfo = get_term_by('id', $termid,get_query_var('taxonomy'));
		    if ($terminfo->term_id != $term->term_id) {
		        $url = get_term_link($terminfo->name, get_query_var('taxonomy'));
		        echo '<li>' . '<a href="' . $url . '">' . $terminfo->name . '</a>' . '</li>';
		    }
		}
		echo '<li class="breadcrumb-title">' . $term->name . '</li>';
		return;
    }

	/* Category */
	if (is_category()) {
	 	$cat_obj = $wp_query->get_queried_object();
     	$this_cat = $cat_obj->term_id;
      	$this_cat = get_category($this_cat);
      	$parent_cat = get_category($this_cat->parent);
		if ($this_cat->parent != 0) { 
			echo '<li>' . (get_category_parents($parent_cat, TRUE, '', FALSE)) . '</li>';
		}
		echo '<li class="breadcrumb-title">' . single_cat_title('',FALSE) . '</li>';
		return;
	}
	
	/* Author */
	if(is_author()){
		$curr_auth = get_userdatabylogin(get_query_var('author_name'));
		echo '<li class="breadcrumb-title">' . __('Author:', SHORT_NAME) . ' ' . $curr_auth->nickname . '</li>';
		return;
	}
	
	/* Tag */
	if(is_tag()) { 
		echo '<li class="breadcrumb-title">' . __('Tag:', SHORT_NAME) . ' ' . single_tag_title('', FALSE) . '</li>';
		return;
	}
	
	/* Archive */
	if (is_archive()) { 
		echo '<li class="breadcrumb-title">' . __('Archives', SHORT_NAME) . '</li>';
		return;
	}
	
	/* Search */
	if (is_search()) { 
		echo '<li class="breadcrumb-title">' . __('Search Results', SHORT_NAME) . '</li>';
		return;
	}
	
	/* 404 */
	if (is_404()) { 
		echo '<li class="breadcrumb-title">' . __('Error 404', SHORT_NAME) . '</li>';
		return;
	}
	
}


/* Twitter
 ------------------------------------------------------------------------*/

/* Parse cache feed */
function r_parse_twitter($usernames, $limit) {
	
	include_once(ABSPATH . WPINC . '/feed.php');
	//$usernames = 'twitterapi';
	
	$rss  = fetch_feed('http://twitter.com/statuses/user_timeline/' . $usernames . '.rss');
	$output = '';
	
	if (!is_wp_error($rss)) { 
        $maxitems = $rss->get_item_quantity($limit);
		
        /* Build an array of all the items, starting with element 0 (first element). */
        $rss_items = $rss->get_items(0, $maxitems); 
	 } else { 
	     return '<ul><li><p>' . __('RSS not configured', SHORT_NAME) . '</p></li></ul>'; 
	 }
		
	 if ($maxitems == 0) {
	     return '<ul><li><p>' . __('No public Twitter messages', SHORT_NAME) . '</p></li></ul>';
	} else {
		  foreach ($rss_items as $item) {
		      $tweet = substr(strstr($item->get_description(),': '), 2, strlen($item->get_description())) . " ";
			  if (isset($encode_utf8)) $tweet = utf8_encode($tweet);
			  $tweet = prettylinks($tweet);
			  $output .= '<li><p>' . $tweet . '</p><span class="twitter-date">' . human_time_diff(strtotime($item->get_date()), current_time('timestamp', 1)) . ' ' . __('ago', SHORT_NAME) . '</span></li>' . "\n";
		
		  }
		  return '<ul>' . $output . '</ul>';
	  }
	
}


/* Pretty links */
function prettylinks($text) {
   $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\">$1</a>", $text);
    $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\">$1</a>", $text);    
    $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
    $text = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/#search?q=$2\" class=\"twitter-link\">#$2</a>$3 ", $text);
    return $text;
}


/* Theme Path
 ------------------------------------------------------------------------*/
function theme_path($string) {
	global $r_option;
	if (isset($r_option['demo_content']) && $r_option['demo_content'] == 'on')
	$string = str_replace('THEME_PATH', THEME_URI, $string);
	
	return $string;
}


/* Check Files Permissions
 ------------------------------------------------------------------------*/
function file_perms($file, $octal = false) {
	
    if(!file_exists($file)) return false;
    $perms = fileperms($file);
    $cut = $octal ? 2 : 3;
    return substr(decoct($perms), $cut);
}


/* Image Exists Function
 ------------------------------------------------------------------------*/
 
function remote_file_exists($url) {
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);
    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  

        if ($statusCode == 200) {
            $ret = true;   
        }
    }
    curl_close($curl);
    return $ret;
}

function r_image_exists($src) {
	
	$src = theme_path($src);
	if (function_exists('curl_init') && CURL == 'on') {
		$exists = remote_file_exists($src);
        if ($exists) {
            
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $src); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			$result = curl_exec($ch); 
			$img = imagecreatefromstring($result);
			$image = array();
			$image['width'] = imagesx($img);
			$image['height'] = imagesy($img);
			$image['mime'] = 'curl_image';
			if ($img) {
				imagedestroy($img);
				return $image;
			} else {
				imagedestroy($img);
				return false;
			}
	
        } else {
            return false;  
        }
	} else {
		$src = parse_url($src);
		$src = $src['path'];
		$src = strstr($src, 'wp-content');
		$image_path =  ABSPATH . $src;
		$image = @getimagesize($image_path);
	  	if ($image) return $image;
		else return false;
	}
}


/* Image Resize Function
 ------------------------------------------------------------------------*/
function r_image_resize($width, $height, $src, $crop = 'c') {
	
	global $r_option;
	
	if (!isset($crop) || $crop == '') $crop = 'c';
	$src = theme_path($src);
	$theme_path = THEME_URI;
	$quality = ($r_option['quality'] == '') ? $quality = 75 : $quality = $r_option['quality'];
	
	/* Check image size via CURL */
	if (function_exists('curl_init') && CURL == 'on') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $src); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($ch); 
		$img = imagecreatefromstring($result);
		if (imagesy($img) <= $height && imagesx($img) <= $width) {
			imagedestroy($img);
			return $src;
		} else {
			imagedestroy($img);
			return "$theme_path/thumb.php?src=$src&amp;a=$crop&amp;w=$width&amp;h=$height&amp;q=$quality";
		}
	}
	
	$image = r_image_exists($src);
	
	if ($image) {
		if ($image[1] <= $height && $image[0] <= $width) return $src;
		else return "$theme_path/thumb.php?src=$src&amp;a=$crop&amp;w=$width&amp;h=$height&amp;q=$quality";
	} else return $src;

}


/* Autoresize Image
 ------------------------------------------------------------------------*/
function r_image($options) {
	
	/* Check image SRC */
	if (!isset($options['src']) && $options['src'] == '') return '<!-- ERROR: Image SRC does not exist -->';
	
	$crop = isset($options['crop']) && $options['crop'] != '' ? $crop = $options['crop'] : $crop = '';
	$image_src = r_image_resize($options['width'], $options['height'], $options['src'], $crop);
	if (isset($options['link']) && $options['link'] != '') {
		$link = theme_path($options['link']);
	} else {
		$link = theme_path($options['src']);
	}
	$title = isset($options['title']) && $options['title'] != '' ? $title = $options['title'] : $title = '';
	$rel = isset($options['rel']) && $options['rel'] != '' ? $rel = $options['rel'] : $rel = '';
	$classes = isset($options['classes']) && $options['classes'] != '' ? $classes = $options['classes'] : $classes = '';
		
	if (isset($options['autoload']) && $options['autoload'] == true) {
	    $output = '<a href="' . $link . '" title="' . $title . '" data-gal="' . $rel . '" style="width: ' . $options['width'] . 'px; height: ' . $options['height'] . 'px" class="autoload ' . $classes . '" data-image_url="' . $image_src . '"></a>';
	} else {
		$output = '<a href="' . $link . '" title="' . $title . '" data-gal="' . $rel . '" class="' . $classes . '"><img src="' . $image_src . '" alt="" /></a>';
		//$output = '<img src="' . $image_src . '" alt=""/>'; //style="z-index:2;position:absolute;left:5px;top:5px"
	}
	
	return $output;
}


/* Trim Function
 ------------------------------------------------------------------------*/
function r_trim($text, $length, $strip_tags = false, $end = '[...]') {
	//$text = str_replace(']]>', ']]>', $text);
	if ($strip_tags) $text = strip_tags($text);
	$words = explode(' ', $text, $length + 1);
	if (count($words) > $length) {
		array_pop($words);
		array_push($words, $end);
		$text = implode(' ', $words);
	}		
	return $text;
}


/* Login function
 ------------------------------------------------------------------------*/
function is_login_page() {
	global $pagenow;
    return in_array($pagenow, array('wp-login.php', 'wp-register.php'));
}


/* Facebook Image
 ------------------------------------------------------------------------*/
function r_facebook_image() {
	global $wp_query; 
	if (is_single() || is_page()) {
		$facebook_thumb = get_post_meta($wp_query->post->ID, 'facebook_image', true);
		$facebook_thumb = theme_path($facebook_thumb);		 
		if (isset($facebook_thumb) && r_image_exists($facebook_thumb)) {
		    echo '<meta property="og:image" content="' . $facebook_thumb . '"/>' . "\n";
	
		}
	}
}
add_action('wp_head', 'r_facebook_image'); 

?>