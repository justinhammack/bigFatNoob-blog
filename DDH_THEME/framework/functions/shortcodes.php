<?php

/* Raw Formatter
 ------------------------------------------------------------------------*/
function my_formatter($content) {
	
	$new_content = '';
		
	/* Matches the contents and the open and closing tags */
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
		
	/* Matches just the contents */
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		
    /* Matches the contents and the open and closing tags */
	//$pattern_full = '{(\[raw\].*?\[/raw\])}is';
		
	/* Matches just the contents */
	//$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
		
	/* Divide content into pieces */
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
	
	/* Loop over pieces */
	foreach ($pieces as $piece) {
		
		/* Look for presence of the shortcode */
		if (preg_match($pattern_contents, $piece, $matches)) {
			
			/* Append to content (no formatting) */
			$new_content .= $matches[1];
			
		} else {
			
			/* Format and append to content */
			$new_content .= wptexturize(wpautop($piece));
			
		}
	}
		
		return $new_content;
}

/* Remove the 2 main auto-formatters */
remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

/* Before displaying for viewing, apply this function */
add_filter('the_content', 'my_formatter', 99);
add_filter('widget_text', 'my_formatter', 99);

/* Custom content */
function do_content($content) {
	$content = apply_filters('the_content', $content);
    return $content;
}


/* Add shortcodes to text widget
 ------------------------------------------------------------------------*/
if (function_exists ('shortcode_unautop')) {
	add_filter ('widget_text', 'shortcode_unautop');
}
add_filter ('widget_text', 'do_shortcode');
 
 
/* Misc Stuff
 ------------------------------------------------------------------------*/

/* Theme Path */
function r_theme_path($atts, $content = null) {
   return THEME_URI;
}
add_shortcode('theme_path', 'r_theme_path');

/* Clear */
function r_clear($atts, $content = null) {
   return '<div class="clear"></div>';
}
add_shortcode('clear', 'r_clear');

/* Last tweet */
function r_last_tweet($atts, $content = null) {
	global $r_option;
	extract(shortcode_atts(array(
		'username'       => ''
    ), $atts));
	
	$tweet = r_parse_twitter($username, 1);
	$output = '<div class="last-tweet">';
	$output.= '<div class="last-tweet-content">';
	$output.= $tweet;
	$output.= '</div>';
	$output.= '</div>';
	return $output;
}
add_shortcode('last_tweet', 'r_last_tweet');

/* Divider */
function r_divider($atts, $content = null) {
   return '<div class="line"></div>';
}
add_shortcode('divider', 'r_divider');

/* Divider top */
function r_divider_top($atts, $content = null) {
   return '<div class="line"><a href="#header" class="top">Top</a></div>';
}
add_shortcode('divider_top', 'r_divider_top');

/* Highlight */
function r_highlight($atts, $content = null) {
	extract(shortcode_atts(array(
		'color'             => '#000',
		'background_color'  => '#fff'
    ), $atts));
   return '<span class="highlight" style="color:' . $color . ';background-color:' . $background_color . '">' . do_shortcode($content) . '</span>';
}
add_shortcode('highlight', 'r_highlight');

/* Highlight 2 */
function r_highlight2($atts, $content = null) {
   return '<span class="highlight2">' . do_shortcode($content) . '</span>';
}
add_shortcode('highlight2', 'r_highlight2');


/* Columns
 ------------------------------------------------------------------------*/

/* Two */
function r_1_2($atts, $content = null) {
   return '<div class="column col-1-2">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_2', 'r_1_2');

function r_1_2_last($atts, $content = null) {
   return '<div class="column col-1-2 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_2_last', 'r_1_2_last');

/* Three */
function r_1_3($atts, $content = null) {
   return '<div class="column col-1-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_3', 'r_1_3');

function r_1_3_last($atts, $content = null) {
   return '<div class="column col-1-3 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_3_last', 'r_1_3_last');

/* Four */
function r_1_4($atts, $content = null) {
   return '<div class="column col-1-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_4', 'r_1_4');

function r_1_4_last($atts, $content = null) {
   return '<div class="column col-1-4 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('1_4_last', 'r_1_4_last');

/* Two Third*/
function r_2_3($atts, $content = null) {
   return '<div class="column col-2-3">' . do_shortcode($content) . '</div>';
}
add_shortcode('2_3', 'r_2_3');

function r_2_3_last($atts, $content = null) {
   return '<div class="column col-2-3 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('2_3_last', 'r_2_3_last');

/* Three Fourth*/
function r_3_4($atts, $content = null) {
   return '<div class="column col-3-4">' . do_shortcode($content) . '</div>';
}
add_shortcode('3_4', 'r_3_4');

function r_3_4_last($atts, $content = null) {
   return '<div class="column col-3-4 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('3_4_last', 'r_3_4_last');

/* Blog columns (with sidebar) */
function r_blog_one($atts, $content = null) {
   return '<div class="col-blog-1">' . do_shortcode($content) . '</div>';
}
add_shortcode('blog_one', 'r_blog_one');

function r_blog_two($atts, $content = null) {
   return '<div class="col-blog-2 last">' . do_shortcode($content) . '</div>';
}
add_shortcode('blog_two', 'r_blog_two');


/* Custom Image
 ------------------------------------------------------------------------*/
function r_custom_image($atts, $content = null) {
	
	extract(shortcode_atts(array(					 
								 'src' => THEME_URI . '/styles/default/no-photo.jpg',
								 'link' => '',
								 'width' => '48',
								 'height' => '48',
								 'lightbox' => '',
								 'group' => '',
								 'hover' => '1',
								 'target' => '0',
								 'title' => '',
								 'crop' => 'c',
								 'image_alignment' => 'left',
								 'autoload' => 'true'
								 ), $atts));
	$classes = '';
	if ($group != '') $lightbox = $lightbox . '[' . $group . ']';
	
	if ($image_alignment == 'left') $image_alignment = 'alignleft';
	else if ($image_alignment == 'right') $image_alignment = 'alignright';
	else if ($image_alignment == 'center') $image_alignment = 'aligncenter';
	
	if ($hover == '1') $classes .= 'hover';
	if ($target == '1') $classes .= ' target-blank';
	
	if ($autoload == 'true') $autoload = true;
	else $autoload = false;
	
	$output = '<div class="custom-image '. $image_alignment .'" style="width:' . $width . 'px;height:' . $height . 'px">'."\n";
	$output .= r_image(array(
						 'src' => $src,
						 'link' => $link,
						 'width' => $width,
						 'height' => $height,
						 'crop' => $crop,
						 'title' => $title,
						 'rel' => $lightbox,
						 'classes' => $classes,
						 'autoload' => $autoload
						 ));
	$output .= '</div>'."\n";
	return $output;
}
add_shortcode('custom_image', 'r_custom_image');


/* Images
 ------------------------------------------------------------------------*/
 
/* Image */
function r_easy_image($atts, $content = null) {
	extract(shortcode_atts(array(
		'src'       => '',						 
        'title' 	=> '',
		'group' 	=> '',
		'link'      => '',
		'size'      => 's',
		'crop'      => 'c',
		'autoload' => 'true'
		
    ), $atts));
	
	if ($link == '') {
	  if ($group != '') $lightbox = "lightbox[$group]";
	  else $lightbox = 'lightbox';
	  $link = $content;
	} else {
		$lightbox = '';
	}
	$images_sizes = array(
						  'xl' => array('width' => '950', 'height' => '360'),
						  'l' => array('width' => '614', 'height' => '275'),
						  'm' => array('width' => '446', 'height' => '205'),
						  's' => array('width' => '278', 'height' => '140'),
						  'xs' => array('width' => '194', 'height' => '95')
						  );
	
	if ($autoload == 'true') $autoload = true;
	else $autoload = false;
	
	if (r_image_exists($src)) {
		$output =  '<div class="image-'. $size .'">' . "\n";
		$output .= '<div class="image-'. $size .'-frame"></div>' . "\n";
		$output .= '<p>' . "\n";
		$output .=  r_image(array(
								 'link' => $link,
								 'src' => $src,
								 'crop' => $crop,
								 'title' => $title,
								 'width' => $images_sizes[$size]['width'],
								 'height' => $images_sizes[$size]['height'],
								 'rel' => $lightbox,
								 'classes' => 'hover',
								 'autoload' => $autoload
								 ));
		$output .=  '</p>' . "\n";
		$output .=  '</div>' . "\n";
	} else {
		$output = __('The link is incorrect or the image does not exist.', SHORT_NAME);
	}
	return $output;
}
add_shortcode('easy_image', 'r_easy_image');


/* Messages
 ------------------------------------------------------------------------*/

/* Message */
function r_message($atts, $content = null) {
   return '<div class="message default"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('message', 'r_message');

/* Message update */
function r_message_update($atts, $content = null) {
   return '<div class="message update"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('message_update', 'r_message_update');


/* Buttons
 ------------------------------------------------------------------------*/
 
/* Button Default */
function r_button_default($atts, $content = null) {
    extract(shortcode_atts(array(
      	'title'     => 'Button Title',
        'link'      => '#'
    ), $atts));

	$output = '<a class="button button-default" href="' . $link . '">' . $title . '</a>';
    
    return $output;
}
add_shortcode('button', 'r_button_default');

/* Button Blue */
function r_button_blue($atts, $content = null) {
    extract(shortcode_atts(array(
        'title'     => 'Button Title',
        'link'      => '#'
    ), $atts));

	$output = '<a class="button button-blue" href="' . $link . '">' . $title . '</a>';
    
    return $output;
}
add_shortcode('button_blue', 'r_button_blue');

/* Button Orange */
function r_button_orange($atts, $content = null) {
    extract(shortcode_atts(array(
        'title'     => 'Button Title',
        'link'      => '#'
    ), $atts));

	$output = '<a class="button button-orange" href="' . $link . '">' . $title . '</a>';
    
    return $output;
}
add_shortcode('button_orange', 'r_button_orange');

/* Button Green */
function r_button_green($atts, $content = null) {
    extract(shortcode_atts(array(
	    'title'     => 'Button Title',
        'link'      => '#'
    ), $atts));

	$output = '<a class="button button-green" href="' . $link . '">' . $title . '</a>';
    
    return $output;
}
add_shortcode('button_green', 'r_button_green');

/* Button Download */
function r_button_download($atts, $content = null) {
    extract(shortcode_atts(array(
        'title'     => 'Button Title',
        'link'      => '#'
    ), $atts));

	$output = '<a class="button button-download" href="' . $link . '">' . $title . '</a>';
    
    return $output;
}
add_shortcode('button_download', 'r_button_download');


/* Box
 ------------------------------------------------------------------------*/
function r_box($atts, $content = null) {
   return '<div class="box">' . do_shortcode($content) . '</div>';
}
add_shortcode('box', 'r_box');


/* List
 ------------------------------------------------------------------------*/

/* Check List */
function r_check_list($atts, $content = null) {
	$content = str_replace('<ul>', '<ul class="check-list">', do_shortcode($content));
	return $content;
	
}
add_shortcode('check_list', 'r_check_list');

/* Events List */
function r_events_list($atts, $content = null) {
	global $r_option;
	
	extract(shortcode_atts(array(
								 'display_limit' => '-1'
								 ), $atts));
	
    if (isset($r_option['events_order']) && $r_option['events_order'] == 'start_date') $events_order = '_event_date_start';
	else $events_order = '_event_date_end';
	
	$args = array(
				  'post_type' => 'wp_events_manager',
				  'posts_per_page' => '-1',
				  'orderby' => 'meta_value',
				  'meta_key' => $events_order,
				  'order' => 'ASC'
				  );
	
	if (isset($post)) $backup = $post;
	
	$events_query = new WP_Query();
	$events_query->query($args);
	
	if ($events_query->have_posts()) {
		$output =  '<div class="dynamic-list" data-display_limit="' . $display_limit . '">'."\n";
		$output .=  '<div class="dynamic-container">'."\n";
		$output .=  '<ul>'."\n";
		$output .= '';
		
		while ($events_query->have_posts()) {
			$events_query->the_post();
			$event_image = get_post_meta($events_query->post->ID, '_event_image', true);
			if (isset($event_image) && $event_image != '') $image_tooltip = 'image-tooltip';
			else $image_tooltip = '';
			
	        $event_date_start = strtotime(get_post_meta($events_query->post->ID, '_event_date_start', true));
			
			if (is_object_in_term($events_query->post->ID, 'wp_event_type', 'Future events')) {
			    $output .= '<li class="news-entry">'."\n";
				$output .= '<div class="date-wrap">'."\n";
				if ($r_option['recent_date_format'] == 'd/m/y') {
					$output .= '<span class="day">' . date('d', $event_date_start) . '</span>';
					$output .= '<span class="year">' . date('m/y', $event_date_start). '</span>';
				} else {
					$output .= '<span class="day">' . date('m', $event_date_start) . '</span>';
					$output .= '<span class="year">' . date('d/y', $event_date_start). '</span>';
				}
                $output .= '</div>'."\n";
				
				$output .= '<div class="news-wrap">'."\n";
				$output .= '<div class="news-content">'."\n";
				$output .= '<a href="' . get_permalink() . '" class="' . $image_tooltip . '" rel="' . r_image_resize(200, 200, $event_image) . '">' . get_the_title() . '</a>'."\n";
				
				$output .= '</div>'."\n";
			    $output .= '</div>'."\n";
				$output .= '</li>'."\n";
			}
		}
		$output .= '</ul>'."\n";
		$output .= '</div>'."\n";
		$output .= '</div>'."\n";
		$output .= '<!-- /dynamic list -->'."\n";
	} else {
		$output = '<p>' . __('Currently we have no events.', SHORT_NAME) . '</p>'."\n";
	}
	
	if (isset($post)) $post = $backup;
	wp_reset_query();
	
	return $output;
	
}
add_shortcode('events_list', 'r_events_list');

/* Posts List */
function r_posts_list($atts, $content = null) {
	global $r_option;
	
	extract(shortcode_atts(array(
								 'cat' => '',
								 'limit' => '-1',
								 'display_limit' => '3'
								 ), $atts));
	
	if (isset($post)) $backup = $post;
	
	if ($cat == '_all') $cat = '';
	$args = array(
				  'cat' => $cat,
				  'posts_per_page' => $limit
				  );
	$posts_list_query = new WP_Query();
	$posts_list_query->query($args);
	
	if ($posts_list_query->have_posts()) {
		$output =  '<div class="dynamic-list" data-display_limit="' . $display_limit . '">'."\n";
		$output .=  '<div class="dynamic-container">'."\n";
		$output .=  '<ul>'."\n";
		$output .= '';
		
		while ($posts_list_query->have_posts()) {
			$posts_list_query->the_post();
			$post_image = get_post_meta($posts_list_query->post->ID, '_post_image', true);
			if (isset($post_image) && $post_image != '') $image_tooltip = 'image-tooltip';
			else $image_tooltip = '';

		
			$output .= '<li class="news-entry">'."\n";
			$output .= '<div class="date-wrap">'."\n";
			if ($r_option['recent_date_format'] == 'd/m/y') {
				$output .= '<span class="day">' . get_the_time('d') . '</span>';
				$output .= '<span class="year">' . get_the_time('m/y'). '</span>';
			} else {
				$output .= '<span class="day">' . get_the_time('m') . '</span>';
				$output .= '<span class="year">' . get_the_time('d/y'). '</span>';
			}
			$output .= '</div>'."\n";
			
			$output .= '<div class="news-wrap">'."\n";
			$output .= '<div class="news-content">'."\n";
			$output .= '<a href="' . get_permalink() . '" class="' . $image_tooltip . '" rel="' . r_image_resize(200, 200, $post_image) . '">' . get_the_title() . '</a>'."\n";
			
			$output .= '</div>'."\n";
			$output .= '</div>'."\n";
			$output .= '</li>'."\n";
			
		}
		$output .= '</ul>'."\n";
		$output .= '</div>'."\n";
		$output .= '</div>'."\n";
		$output .= '<!-- /dynamic list -->'."\n";
	}
	
	if (isset($post)) $post = $backup;
	wp_reset_query();
	
	return $output;
	
}
add_shortcode('posts_list', 'r_posts_list');


/* Contact Form
 ------------------------------------------------------------------------*/
function _r_contact_form($atts, $content = null) {
	
	global $r_option;
	
	$output .= "[raw]<div id=\"rf\">";
	$output .= "<input type=\"hidden\" value=\"".THEME_URI."/includes/contact-form.php\" id=\"cp\"/>";
	$output .= "<input type=\"hidden\" value=\"".ABSPATH."\" name=\"wordpress_path\" id=\"wordpress_path\" class=\"val\"/>";
	$output .= "<p class=\"rf\">";
	$output .= "<label>* ".$r_option['contact_label_name']."</label>";
	$output .= "<input type=\"text\" value=\"\" name=\"name\" class=\"val req\"/>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "<p class=\"rf\">";
	$output .= "<label>* ".$r_option['contact_label_email']."</label>";
	$output .= "<input type=\"text\" value=\"\" name=\"email\" class=\"val req email\"/>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "<p class=\"rf\">";
	$output .= "<label>".$r_option['contact_label_phone']."</label>";
	$output .= "<input type=\"text\" value=\"\" name=\"phone\" class=\"val\"/>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "<p class=\"rf\">";
	$output .= "<label>* ".$r_option['contact_label_message']."</label>";
	$output .= "<textarea cols=\"5\" rows=\"10\" name=\"message\" class=\"val req\"></textarea>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "<p class=\"rf\">";
	$output .= "<label>".$r_option['question']."</label>";
	$output .= "<input type=\"text\" value=\"\" name=\"asq\" class=\"val asq req\" style=\"width:60px\"/>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "<p class=\"rf-submit\">";
	$output .= "<input type=\"submit\" value=\"".$r_option['contact_submit']."\" class=\"submit\" id=\"send\"/>";
	$output .= "<span></span>";
	$output .= "</p>";
	$output .= "</div>[/raw]";
	
   	return $output;
}
add_shortcode('_contact_form', '_r_contact_form');


/* Contact Form
 ------------------------------------------------------------------------*/
function r_contact_form($atts, $content = null) {
	
	global $r_option;
	
	extract(shortcode_atts(array(
								 'email' => $r_option['email_address']
								 ), $atts));
	
	$output = '[raw]<div class="r-form">';
	$output .= '<input type="hidden" value="' . $email . '" name="mail_to" />';
	$output .= '<div class="rf">';
	$output .= '<label>' . __('* Name:', SHORT_NAME) . '</label>';
	$output .= '<input type="text" value="" name="name" class="req valid_length" />';
	$output .= '</div>';
	$output .= '<div class="rf">';
	$output .= '<label>' . __('* E-mail:', SHORT_NAME) . '</label>';
	$output .= '<input type="text" value="" name="email" class="req valid_email" />';
	$output .= '</div>';
	$output .= '<div class="rf">';
	$output .= '<label>' . __('* Subject:', SHORT_NAME) . '</label>';
	$output .= '<input type="text" value="" name="subject" class="req valid_length" />';
	$output .= '</div>';
	$output .= '<div class="rf">';
	$output .= '<label>' . __('Phone:', SHORT_NAME) . '</label>';
	$output .= '<input type="text" value="" name="phone" class="" />';
	$output .= '</div>';
	$output .= '<div class="rf">';
	$output .= '<label>' . __('* Message:', SHORT_NAME) . '</label>';
	$output .= '<textarea cols="5" rows="10" name="message" class="req valid_length"></textarea>';
	$output .= '</div>';
	$output .= '<div class="rf">';
	$output .= '<label>' . $r_option['question'] . '</label>';
	$output .= '<input type="text" value="" name="asq" class="req valid_asq valid_length antyspam" style="width:60px" />';
	$output .= '</div>';
	$output .= '<div class="rf-submit">';
	$output .= '<input type="submit" value="' . __('Send message', SHORT_NAME) . '" class="submit rf-send" />';
	$output .= '<div class="rf-ajax-loader"></div>';
	$output .= '<span class="rf-message"></span>';
	$output .= '</div>';
	$output .= '</div>[/raw]';
	
   	return $output;
}
add_shortcode('contact_form', 'r_contact_form');


/* Video - Vimeo
 ------------------------------------------------------------------------*/
function r_vimeo($atts, $content=null) {
	
	extract(shortcode_atts(array(
		'id' 	=> '',
		'width' 	=> '400',
		'height' 	=> '225',
	), $atts));

	if (empty($id) || !is_numeric($id)) return '<!-- Vimeo: Invalid id -->';
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	
	$output = '<object width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" data="http://vimeo.com/moogaloop.swf?clip_id='. $id .'&amp;server=vimeo.com&amp;show_title=1&amp;fullscreen=1&amp;autoplay=0&amp;loop=0">
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='. $id .'&amp;server=vimeo.com&amp;show_title=1&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" />
          	</object>';
	
	return $output;
}
add_shortcode('vimeo', 'r_vimeo');


/* Video - YouTube
 ------------------------------------------------------------------------*/
function r_youtube($atts, $content=null) {
	
	extract(shortcode_atts(array(
		'id' 	=> '',
		'width' 	=> '400',
		'height' 	=> '225',
	), $atts));

	if (empty($id)) return '<!-- Youtube: Invalid id -->';
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);
	
	$output = '<object width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" data="http://youtube.com/v/' . $id . '">
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://youtube.com/v/' . $id . '" />
          	</object>';
	
	return $output;
}
add_shortcode('youtube', 'r_youtube');


/* R-Player
 ------------------------------------------------------------------------*/
$player_id = 0;

function r_player($atts, $content=null) {
	
	global $player_id;
	
	extract(shortcode_atts(array(
		'url' 	=> ''
	), $atts));
	
	$url = theme_path($url);
	
	$player_id++;
	
	$player_path = THEME_SCRIPTS_URI.'/r-player/r-player-compact.swf';

	if (empty($url)) return '<!-- player: Invalid url -->';
	
	$output = '<object id="player_' . $player_id . '" width="288" height="80" type="application/x-shockwave-flash" data="' . $player_path . '">
			<param name="flashvars" value="file=' . $url . '&amp;id=player_' . $player_id . '" />
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="' . $player_path . '" />
          	</object>';
	
	return $output;
}
add_shortcode('player', 'r_player');
add_shortcode('sidebar_player', 'r_player');


/* HTML5 Player
 ------------------------------------------------------------------------*/
function r_html_player($atts, $content=null) {
	
	extract(shortcode_atts(array(
		'title'     => 'Track',
		'url' 	=> ''
	), $atts));
	
	$url = theme_path($url);
	
	if ($url == '') return '<p>Player error: <strong>Invalid Track</strong></p>';;
	
	$output = '<ul class="playlist small-list">' . "\n";
	$output .= '<li> <a href="' . $url . '">' . $title . '</a></li>' . "\n";
	$output .= '</ul>' . "\n";
	
	return $output;
}
add_shortcode('html5_player', 'r_html_player');


/* Playlist
 ------------------------------------------------------------------------*/
function r_playlist($atts, $content=null) {
	
	$output = '<ul class="playlist small-list">' . "\n";
	$output .= do_shortcode($content);
	$output .= '</ul>' . "\n";
	
	return $output;
}
add_shortcode('playlist', 'r_playlist');

/* Playlist track */
function r_playlist_track($atts, $content=null) {
	
	extract(shortcode_atts(array(
		'title'     => 'Track',
		'url' 	=> ''
	), $atts));
	
	$url = theme_path($url);
	
	if ($url == '') return '<p>Player error: <strong>Invalid Track</strong></p>';
	
	$output = '<li> <a href="' . $url . '">' . $title . '</a></li>' . "\n";
	
	return $output;
}
add_shortcode('playlist_track', 'r_playlist_track');


/* Soundcloud
 ------------------------------------------------------------------------*/
function r_soundcloud($atts, $content=null) {
	
	extract(shortcode_atts(array(
		'url' 	=> '',
		'height' => '81',
		'width' => '600',
		'params' => ''
	), $atts));
	
    if ($params != '') {
		str_replace("&", "&amp;", $params);
		$url = $url . '&amp;' . $params;
	}
	if (empty($url)) return '<p>Soundcloud error: <strong>Invalid Track</strong></p>';
	
	$output = '<div style="width:'. $width .'px;height:'. $height .'px;margin-bottom:20px"><object height="'. $height .'" width="'. $width .'" data="http://player.soundcloud.com/player.swf?url=' . $url . '">
            <param name="movie" value="http://player.soundcloud.com/player.swf?url=' . $url . '" />
            <param name="allowscriptaccess" value="always"/>
            <embed allowscriptaccess="always" src="http://player.soundcloud.com/player.swf?url=' . $url . '" type="application/x-shockwave-flash" height="'. $height .'" width="'. $width .'"/>
          </object></div>';
	
	return $output;
}
add_shortcode('soundcloud', 'r_soundcloud');

?>