<?php 
	global $wp_query, $r_option;
	
	/* Intro options */
    $intro_width = 950;
    $intro_height = 190;
	$intro_delay = 5;
	$intro_duration = 1000;
	$intro_easing = 'easeOutSine';
	$disable_intro_nav = 'off';
	
	if (!is_archive() && !is_category() && !is_tax() && !is_search() && !is_404()) {
		$intro_source = get_post_meta($wp_query->post->ID, '_intro_source', true);
		$intro_source = isset($intro_source) ? $intro_source = $intro_source : $intro_source = '';
	} else { 
	    $intro_source = 'text';
	}
?>
<?php if ($intro_source == 'image') : ?>
<!-- start intro-wrap -->
<div class="wide-wrap">
	<div id="intro-image" class="pngfix">
    	<?php 
		$intro_image = get_post_meta($wp_query->post->ID, '_intro_image', true);
		$intro_image_crop = get_post_meta($wp_query->post->ID, '_intro_image_crop', true);
		if (isset($intro_image) && isset($intro_image_crop)) :
		?>
		<img src="<?php echo r_image_resize($intro_width, $intro_height, $intro_image, $intro_image_crop); ?>"/>
        <?php endif; ?>
     </div>   
</div>
<?php endif; ?>
<?php if ($intro_source == 'slider') : ?>
<!-- start intro-wrap -->
<div class="wide-wrap">
    <div id="intro" class="pngfix">
    	<?php
		$intro_category = get_post_meta($wp_query->post->ID, '_intro_category', true);
		$intro_category = !$intro_category || $intro_category == '_all' ? $intro_category = '' : $intro_category = $intro_category;
		?>
        <!-- slider -->
        <div id="intro-slider" class="intro-slider navigation" data-delay="<?php echo $intro_delay; ?>" data-width="<?php echo $intro_width; ?>" data-height="<?php echo $intro_height; ?>" data-duration="<?php echo $intro_duration; ?>" data-easing="<?php echo $intro_easing; ?>" style="width:<?php echo $intro_width; ?>px;height:<?php echo $intro_height; ?>px;">
            <!-- slider content -->
            <div class="rs-content">
                <?php
                $backup = $post;
                $args = array(
                              'post_type' => 'wp_slider',
                              'wp_slider_categories' => $intro_category,
                              'posts_per_page' => '-1',
                              'orderby' => 'menu_order',
                              'order' => 'ASC'
                              );
                /* Slider inner content query */
                $intro_query = new WP_Query($args);
                
                /* Start loop */
                if ($intro_query->have_posts()) : while ($intro_query->have_posts()) : $intro_query->the_post();
                
                /* Slider image */
                $slider_image = get_post_meta($intro_query->post->ID, '_slider_image', true);
                
                /* Cropping */
                $crop = get_post_meta($intro_query->post->ID, '_slider_image_crop', true);
                $crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
                
                /* Image effect */
                $image_effect = get_post_meta($intro_query->post->ID, '_image_effect', true);
                $image_effect = isset($image_effect) && $image_effect != '' ? $image_effect = $image_effect : $image_effect = 'fade';
				
				/* Slider type */
			    $slider_type = get_post_meta($intro_query->post->ID, '_slider_type', true);
			    $slider_type = isset($slider_type) && $slider_type != '' ? $slider_type = $slider_type : $slider_type = 'Image';
                ?>
                <?php if ($slider_type != 'Vimeo' && $slider_type != 'Youtube') : ?>
                <!-- image slide -->
                <div class="rs-slide" data-effect="<?php echo $image_effect; ?>" style="width:<?php echo $intro_width; ?>px;height:<?php echo $intro_height; ?>px;">
                    <div class="rs-slide-content">
                        <!-- image -->
                        <div class="rs-image">
                        <img src="<?php echo r_image_resize($intro_width, $intro_height, $slider_image, $crop); ?>" title="<?php esc_attr(the_title()); ?>"/>
                        </div>
                        <!-- /image -->
                     </div>
                </div>
                <!-- /image slide -->
                <?php endif; ?>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php $post = $backup; ?>
            </div>
            <!-- /slider content -->
            <!-- slider navigation -->
            <div class="rs-timer"></div>
            <?php if ($disable_intro_nav == 'off') : ?>
            <div class="rs-nav"></div>
            <a class="rs-next" href="#"></a> <a class="rs-prev" href="#"></a>
            <?php endif; ?>
            <!-- /slider navigation -->
      </div>
      <!-- slider -->
			
	</div>   
</div>
<?php endif; ?>
<?php if ($intro_source == 'text'): ?>
<!-- start intro-wrap -->
<div class="wide-wrap">
	<div id="intro-text">
    	<?php 
	  	if (is_category()) { 
			$intro_text = _x('Categories', 'intro', SHORT_NAME);
			$intro_text = $intro_text . ' - ' . single_cat_title('', false);
		}
		elseif (is_tag()) {
			$intro_text = _x('Tag: ', 'intro', SHORT_NAME);
			$intro_text .= single_tag_title('', false);
		}
		elseif (is_tax() && taxonomy_exists('wp_portfolio_categories')) { 
			$intro_text = _x('Categories', 'intro', SHORT_NAME);
			$intro_text = $intro_text . ' - ' . single_cat_title('', false);
		}
		elseif (is_archive()) {
			$intro_text = get_the_category($post->ID);
	        if(is_year()) $intro_text  = get_the_time('Y');
	        if(is_month()) $intro_text  = get_the_time('F, Y');
	        if(is_day() || is_time()) $intro_text  = get_the_time('l - ' . $r_option['custom_date']);
		}
		elseif (is_search())
			$intro_text = _x('Search', 'intro', SHORT_NAME);
		elseif (is_404())
			$intro_text = _x('Error 404', 'intro', SHORT_NAME);
		else {
			$intro_text = get_post_meta($wp_query->post->ID, '_intro_text', true);
			$intro_text = isset($intro_text) ? $intro_text = $intro_text : $intro_text = '';
		}
	  	?>
        <h5><?php echo $intro_text; ?></h5>
	</div>   
</div>
<?php endif; ?>