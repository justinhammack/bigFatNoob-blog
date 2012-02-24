<?php global $r_option; ?>
<?php
global $more, $wp_query; 

/* Slider options */
$slider_effect = 'horizontal';
$slider_width = 210;
$slider_height = 210;
$slider_delay = 5;
$slider_duration = 500;
$slider_easing = 'easeOutSine';
$disable_slider_nav = 'off';

/* Portfolio Category */
$slider_category = $r_option['recent_category'];
$slider_category = !$slider_category || $slider_category == '_all' ? $slider_category = '' : $slider_category = $slider_category;

/* Display Limit */
$limit = (int)$r_option['recent_works_limit'];
$limit = $limit && $limit == '' ? $limit = 3 : $limit = $limit;
?>

<!-- slider wrap -->
<div id="recent-wrap">
<div id="recent">
    <!-- slider -->
    <div id="recent-slider" class="recent-slider navigation" data-delay="<?php echo $slider_delay; ?>" data-width="<?php echo $slider_width; ?>" data-height="<?php echo $slider_height; ?>" data-duration="<?php echo $slider_duration; ?>" data-easing="<?php echo $slider_easing; ?>" data-effect="<?php echo $slider_effect; ?>" style="width:<?php echo $slider_width; ?>px;height:<?php echo $slider_height; ?>px;">
        <!-- slider content -->
        <div class="rs-content">
        	<?php
			$count = 0;
			$args = array(
						  'post_type' => 'wp_portfolio',
						  'wp_portfolio_categories' => $slider_category,
						  'showposts'=> $limit
						  );
			/* Slider query */
			$recent_slider_query = new WP_Query($args);
			
			/* Start loop */
			if ($recent_slider_query->have_posts()) : while ($recent_slider_query->have_posts()) : $recent_slider_query->the_post();
			
			/* Count */
            $count++;
			
			/* Slider image */
			$slider_image = get_post_meta($recent_slider_query->post->ID, '_portfolio_image', true); 
			
			/* Cropping */
			$crop = get_post_meta($recent_slider_query->post->ID, '_portfolio_image_crop', true);
			$crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
			
			/* Music */
			$music = get_post_meta($recent_slider_query->post->ID, '_music', true);
			
			/* Music Title */
			$music_title = get_post_meta($recent_slider_query->post->ID, '_music_title', true);
			$music_title = isset($music_title) && $music_title != '' ? $music_title = $music_title : $music_title = get_the_title();
			 
			/* Lightbox link */
			$lightbox_link = get_post_meta($recent_slider_query->post->ID, '_lightbox_link', true);
			$lightbox_link = isset($lightbox_link) && $lightbox_link != '' ? $lightbox_link = $lightbox_link : $lightbox_link = '';
			
			/* Custom lightbox content */
			$lightbox_content = get_post_meta($recent_slider_query->post->ID, '_lightbox_content', true);
			$lightbox_content = isset($lightbox_content) && $lightbox_content != '' ? $lightbox_content = $lightbox_content : $lightbox_content = '';
			
			/* Custom link URL */
			$link_url = get_post_meta($recent_slider_query->post->ID, '_link_url', true);
			$link_url = isset($link_url) && $link_url != '' ? $link_url = $link_url : $link_url = $slider_image;
			
			/* Title link URL */
			$title_link = get_post_meta($recent_slider_query->post->ID, '_title_link', true);
			$title_link = isset($title_link) && $title_link == 'on' ? $title_link = true : $title_link = false;
			
			/* Link target attribute */
			$target = get_post_meta($recent_slider_query->post->ID, '_target', true);
			$target = isset($target) && $target == 'on' ? $target = 'target-blank' : $target = '';
					
			/* Portfolio type */
			$portfolio_type = get_post_meta($recent_slider_query->post->ID, '_portfolio_type', true);
			$portfolio_type = isset($portfolio_type) && $portfolio_type != '' ? $portfolio_type = $portfolio_type : $portfolio_type = 'Image';
			
	        ?>		
            
            <!-- slide -->
            <div class="rs-slide" data-effect="<?php echo $slider_effect; ?>" style="width:<?php echo $slider_width; ?>px;height:<?php echo $slider_height; ?>px;">
                <div class="rs-slide-content">
                    <!-- image -->
                    <?php switch ($portfolio_type) { case 'Image' : ?>
                    <div class="rs-image">
                    <img class="recent-image" src="<?php echo r_image_resize($slider_width, $slider_height, $slider_image, $crop); ?>" title="<?php esc_attr(the_title()); ?>"/>
                    </div>
                    <!-- /image -->
                    <?php break; ?>
                    
                    <?php case 'Image with lightbox' : ?>
                     <!-- image -->
                    <div class="rs-image">
                    <?php echo r_image(array(
                                     'link' => $lightbox_link,
                                     'src' => $slider_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $slider_width,
                                     'height' => $slider_height,
                                     'rel' => 'lightbox',
                                     'classes' => 'hover',
                                     'autoload' => true
                                     ));
                     ?>
                    </div>
                    <!-- /image -->

                    <?php break; ?>
                    
                     <?php case 'Image with custom lightbox' : ?>
                     <!-- image -->
                    <div class="rs-image">
                     <?php echo r_image(array(
                                     'link' => '#custom-lightbox-' . $count,
                                     'src' => $slider_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $slider_width,
                                     'height' => $slider_height,
                                     'rel' => 'lightbox',
                                     'classes' => 'hover',
                                     'autoload' => true
                                     ));
                     ?>
                    </div>
                    <!-- /image -->
                    <div id="custom-lightbox-<?php echo $count; ?>" class="hidden-content">
                        <?php echo do_content($lightbox_content); ?>
                    </div>
                    <?php break; ?>
                    
                    <?php case 'Image with link' : ?>
                     <!-- image -->
                    <div class="rs-image">
                    <?php echo r_image(array(
                                     'link' => $link_url,
                                     'src' => $slider_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $slider_width,
                                     'height' => $slider_height,
                                     'classes' => $target . ' hover',
                                     'autoload' => true
                                     ));
                        ?>
                    </div>
                    <!-- /image -->

                    <?php break; ?>
                    
					<?php }; // End Switch ?>
                    <?php if (isset($music) && $music != '') : ?>
                    <!-- music -->
                    <div class="rs-recent-music">                 
                        <ul class="playlist portfolio-player">
                        <?php $music = theme_path($music); ?>
                            <li><a href="<?php echo $music; ?>" id="recent-music-<?php the_ID(); ?>"><?php _e('Play track', SHORT_NAME); ?></a></li>
                        </ul>
                    </div>
                    <!-- /music -->
                    <?php endif; ?>
                </div>
            </div>
            <!-- /slide -->
        
		    <?php endwhile; ?>
		    <?php endif; ?>
        </div>
        <!-- /slider content -->
        <!-- slider navigation -->
        <div class="rs-timer"></div>
        <?php if ($disable_slider_nav == 'off') : ?>
        <div class="rs-nav hidden"></div>
        <a class="rs-next" href="#"></a> <a class="rs-prev" href="#"></a>
        <?php endif; ?>
        <!-- /slider navigation -->
    </div>
    <!-- slider -->
    </div>
</div>
<!-- /slider-wrap -->