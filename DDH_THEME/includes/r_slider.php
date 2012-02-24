<?php global $r_option; ?>
<?php
global $more, $wp_query; 

/* Slider options */
$slider_width = 940;
$slider_height = 380;

/* Portfolio Category */
$slider_category = get_post_meta($wp_query->post->ID, '_slider_category', true);
$slider_category = !$slider_category || $slider_category == '_all' ? $slider_category = '' : $slider_category = $slider_category;
?>

<!-- slider wrap -->
<div id="slider-wrap" class="pngfix">
    <!-- slider -->
    <div id="homepage-slider" class="homepage-slider navigation" data-delay="<?php echo $r_option['slider_delay']; ?>" data-width="<?php echo $slider_width; ?>" data-height="<?php echo $slider_height; ?>" data-duration="<?php echo $r_option['slider_duration']; ?>" data-easing="<?php echo $r_option['slider_easing']; ?>" style="width:<?php echo $slider_width; ?>px;height:<?php echo $slider_height; ?>px;">
        <!-- slider content -->
        <div class="rs-content">
        	<?php
			$backup = $post;
			$args = array(
						  'post_type' => 'wp_slider',
						  'wp_slider_categories' => $slider_category,
						  'posts_per_page' => '-1',
						  'orderby' => 'menu_order',
						  'order' => 'ASC'
						  );
			/* Slider content query */
			$slider_query = new WP_Query($args);
			
			/* Start loop */
			if ($slider_query->have_posts()) : while ($slider_query->have_posts()) : $slider_query->the_post();
			
			/* Slider image */
			$slider_image = get_post_meta($slider_query->post->ID, '_slider_image', true);
			
			/* Cropping */
			$crop = get_post_meta($slider_query->post->ID, '_slider_image_crop', true);
			$crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
			
			/* Hide title */
			$hide_title = get_post_meta($slider_query->post->ID, '_hide_title', true);
			$hide_title = isset($hide_title) && $hide_title == 'on' ? $hide_title = true : $hide_title = false;
			
			/* Hide description */
			$hide_description = get_post_meta($slider_query->post->ID, '_hide_description', true);
			$hide_description = isset($hide_description) && $hide_description == 'on' ? $hide_description = true : $hide_description = false;
			
			/* Lightbox link */
			$lightbox_link = get_post_meta($slider_query->post->ID, '_lightbox_link', true);
			$lightbox_link = isset($lightbox_link) && $lightbox_link != '' ? $lightbox_link = $lightbox_link : $lightbox_link = '';
			
			/* Custom link URL */
			$link_url = get_post_meta($slider_query->post->ID, '_link_url', true);
			$link_url = isset($link_url) && $link_url != '' ? $link_url = $link_url : $link_url = $slider_image;
			
			/* Link target attribute */
			$target = get_post_meta($slider_query->post->ID, '_target', true);
			$target = isset($target) && $target == 'on' ? $target = 'target-blank' : $target = '';
					
			/* Slider type */
			$slider_type = get_post_meta($slider_query->post->ID, '_slider_type', true);
			$slider_type = isset($slider_type) && $slider_type != '' ? $slider_type = $slider_type : $slider_type = 'Image';
			
			/* Image effect */
			$image_effect = get_post_meta($slider_query->post->ID, '_image_effect', true);
			$image_effect = isset($image_effect) && $image_effect != '' ? $image_effect = $image_effect : $image_effect = 'fade';
			
			/* Music */
			$music = get_post_meta($slider_query->post->ID, '_music', true);
			if (isset($music) && $music != '' && $slider_type == 'Image with music') $image_effect = 'media';
			else $music = false;
			
			/* YouTube */
			$youtube = get_post_meta($slider_query->post->ID, '_youtube', true);
			if (isset($youtube) && $youtube != '' && $slider_type == 'Youtube') $image_effect = 'media';
			else $youtube = false;
			
			/* Vimeo */
			$vimeo = get_post_meta($slider_query->post->ID, '_vimeo', true);
			if (isset($vimeo) && $vimeo != '' && $slider_type == 'Vimeo' ) $image_effect = 'media';
			else $vimeo = false;
			
	        ?>		
            
            <!-- slide -->
            <div class="rs-slide" data-effect="<?php echo $image_effect; ?>" style="width:<?php echo $slider_width; ?>px;height:<?php echo $slider_height; ?>px;">
                <div class="rs-slide-content">
                    <!-- image -->
                    <?php switch ($slider_type) { case 'Image' : ?>
                    <div class="rs-image">
                    <img src="<?php echo r_image_resize($slider_width, $slider_height, $slider_image, $crop); ?>" title="<?php esc_attr(the_title()); ?>"/>
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
									 'classes' => '',
									 'autoload' => false
									 ));
			        ?>
                    </div>
                    <!-- /image -->

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
									 'classes' => $target,
									 'autoload' => false
									 ));
			        ?>
                    </div>
                    <!-- /image -->
                    
                    
                     <?php break; ?>
                    
                    <?php case 'Image with music' : ?>
                    <?php
					 /* Hide description */
					 $hide_title = true;
					 $hide_description = true;
					 ?>
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
									 'classes' => '',
									 'autoload' => false
									 ));
			        ?>
                    </div>
                    <!-- /image -->
                    <?php if ($music) : ?>
                    <!-- music -->
                    <div class="rs-music">
                        <div class="rs-music-inner">
                          <ul class="playlist hp-player">
                          <?php $music = theme_path($music); ?>
						      <li><a href="<?php echo $music; ?>" id="slider-music-<?php the_ID(); ?>"><?php the_title(); ?></a></li>
                          </ul>
                        
                        </div>
                    </div>
                    <!-- /music -->
                    <?php endif; ?>

                    <?php break; ?>
                    
                     <?php case 'Youtube' : ?>
                     <?php if ($youtube) : ?>
                     <?php
					 /* Hide description */
					 $hide_title = true;
					 $hide_description = true;
					 ?>
                     <!-- image -->
                    <div class="rs-image rs-video">
                    <object class="youtube-player" id="slider_youtube_<?php the_ID(); ?>" width="940" height="380" type="application/x-shockwave-flash" data="http://youtube.com/v/<?php echo $youtube; ?>?enablejsapi=1&amp;version=3&amp;playerapiid=slider_youtube_<?php the_ID(); ?>&amp;autoplay=0&amp;hd=1&amp;color1=0x3a3a3a&amp;color2=0x999999">
                      <param name="allowfullscreen" value="true" />
                      <param name="allowscriptaccess" value="always" />
                      <param name="wmode" value="transparent" />
                      <param name="movie" value="http://youtube.com/v/<?php echo $youtube; ?>" />
                    </object>
                    </div>
                    <!-- /image -->
                    <?php endif; ?>
                    <?php break; ?>
                    
                     <?php case 'Vimeo' : ?>
                     <?php if ($vimeo) : ?>
                     <?php
					 /* Hide description */
					 $hide_title = true;
					 $hide_description = true;
					 ?>
                     <!-- image -->
                    <div class="rs-image rs-video">
                   <object class="vimeo-player" id="slider_vimeo_<?php the_ID(); ?>" width="940" height="380" type="application/x-shockwave-flash" data="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $vimeo; ?>&amp;server=vimeo.com&amp;show_title=1&amp;fullscreen=1&amp;loop=0">
            <param name="allowfullscreen" value="true" />
            <param name="allowscriptaccess" value="always" />
            <param name="flashvars" value="api=1&amp;player_id=slider_vimeo_<?php the_ID(); ?>" /> 
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=<?php echo $vimeo; ?>&amp;server=vimeo.com&amp;show_title=1&amp;fullscreen=1&amp;loop=0" />
          </object>
                    </div>
                    <!-- /image -->
                    <?php endif; ?>
                    <?php break; ?>
                    
					<?php }; // End Switch ?>
                    <?php if (!$hide_title || !$hide_description) : ?>
                    <!-- captions -->
                    <div class="rs-caption">
                        <div class="rs-caption-inner">
                          <?php if (!$hide_title) : ?>
                          <h3><?php the_title(); ?></h3>
                          <?php endif; ?>
                          <?php if (!$hide_description) : ?>
                          <?php the_content(); ?>
                          <?php endif; ?>
                        </div>
                    </div>
                    <!-- /captions -->
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
        <?php if (isset($r_option['disable_slider_nav']) && $r_option['disable_slider_nav'] == 'off') : ?>
        <div class="rs-nav"></div>
        <a class="rs-next" href="#"></a> <a class="rs-prev" href="#"></a>
        <?php endif; ?>
        <!-- /slider navigation -->
    </div>
    <!-- slider -->
</div>
<!-- /slider-wrap -->
<?php $post = $backup; ?>