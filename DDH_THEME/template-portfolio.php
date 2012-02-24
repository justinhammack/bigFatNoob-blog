<?php
/*
Template Name: Portfolio
*/
?>
<?php global $r_option; ?>

<?php get_header(); ?>

<?php 
	global $more, $wp_query; 
	
	/* Portfolio Layout */
	$portfolio_layout = get_post_meta($wp_query->post->ID, '_portfolio_layout', true);
	$portfolio_layout = $portfolio_layout && $portfolio_layout != '' ? $portfolio_layout = $portfolio_layout : $portfolio_layout = '3';
	
	if ($portfolio_layout == '1') {
		$image_size = 'image-l';
		$image_width = '614';
		$image_height = '275';
	} elseif ($portfolio_layout == '2') {
		$image_size = 'image-m';
		$image_width = '446';
		$image_height = '205';
	} elseif ($portfolio_layout == '3') {
		$image_size = 'image-s';
		$image_width = '278';
		$image_height = '140';
	}
	
	/* Portfolio Category */
	$portfolio_category = get_post_meta($wp_query->post->ID, '_portfolio_category', true);
	$portfolio_category = !$portfolio_category || $portfolio_category == '_all' ? $portfolio_category = '' : $portfolio_category = $portfolio_category;
	
	/* Pagination Limit */
	$limit = (int)get_post_meta($wp_query->post->ID, '_limit', true);
	$limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;
?>

<?php include_once(THEME . '/includes/intro.php'); ?>
<!-- start content -->
<div class="content">
	<div id="breadcrumb-wrap" class="pngfix">
   		<ul id="breadcrumb">
   			<?php breadcrumb(); ?>
   		</ul>
   	</div>
   	<div class="clear"></div>
	<!-- start left -->
   	<div id="left" class="wide">
        <div class="entry" style="margin-bottom:0">
            <div class="entry-content">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
                <?php endwhile; ?>
                <?php endif; ?>
                <?php
                $count = 0;
                $args = array(
                              'post_type' => 'wp_portfolio',
                              'wp_portfolio_categories' => $portfolio_category,
                              'showposts'=> $limit,
                              'paged' => $paged
                              );
                $wp_query = new WP_Query();
                $wp_query->query($args);
                ?>
           
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php
                
                /* Count */
                $count++;
                
                /* Portfolio image */
                $portfolio_image = get_post_meta($wp_query->post->ID, '_portfolio_image', true); 
                
                /* Cropping */
                $crop = get_post_meta($wp_query->post->ID, '_portfolio_image_crop', true);
                $crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
                
                /* Music */
                $music = get_post_meta($wp_query->post->ID, '_music', true);
                
                /* Music Title */
                $music_title = get_post_meta($wp_query->post->ID, '_music_title', true);
                $music_title = isset($music_title) && $music_title != '' ? $music_title = $music_title : $music_title = get_the_title();
                 
                /* Lightbox link */
                $lightbox_link = get_post_meta($wp_query->post->ID, '_lightbox_link', true);
                $lightbox_link = isset($lightbox_link) && $lightbox_link != '' ? $lightbox_link = $lightbox_link : $lightbox_link = '';
				
                /* Custom lightbox content */
                $lightbox_content = get_post_meta($wp_query->post->ID, '_lightbox_content', true);
                $lightbox_content = isset($lightbox_content) && $lightbox_content != '' ? $lightbox_content = $lightbox_content : $lightbox_content = '';
				
                /* Custom link URL */
                $link_url = get_post_meta($wp_query->post->ID, '_link_url', true);
                $link_url = isset($link_url) && $link_url != '' ? $link_url = $link_url : $link_url = $portfolio_image;
                
                /* Title link URL */
                $title_link = get_post_meta($wp_query->post->ID, '_title_link', true);
                $title_link = isset($title_link) && $title_link == 'on' ? $title_link = true : $title_link = false;
                
                /* Link target attribute */
                $target = get_post_meta($wp_query->post->ID, '_target', true);
                $target = isset($target) && $target == 'on' ? $target = 'target-blank' : $target = '';
                        
                /* Portfolio type */
                $portfolio_type = get_post_meta($wp_query->post->ID, '_portfolio_type', true);
                $portfolio_type = isset($portfolio_type) && $portfolio_type != '' ? $portfolio_type = $portfolio_type : $portfolio_type = 'Image';
                    

                ?>
           
                <!-- portfolio -->
                <div class="portfolio column <?php if ($portfolio_layout != '1') echo 'col-1-' . $portfolio_layout; else echo 'col-2-3'; ?> <?php if ($count % (int)$portfolio_layout == 0 && $portfolio_layout != '1') echo 'last'; ?>">
                    <div class="<?php echo $image_size; ?>">
                        <div class="<?php echo $image_size; ?>-frame"></div>
                         <?php switch ($portfolio_type) {
                         case 'Image' : ?>
                         
                        <img class="portfolio-image" src="<?php echo r_image_resize($image_width, $image_height, $portfolio_image, $crop); ?>" title="<?php esc_attr(the_title()); ?>"/>
                        <?php break; ?>
                        <?php case 'Image with lightbox' : ?>
                        
                        <?php echo r_image(array(
                                     'link' => $lightbox_link,
                                     'src' => $portfolio_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $image_width,
                                     'height' => $image_height,
                                     'rel' => 'lightbox[portfolio]',
                                     'classes' => 'hover',
                                     'autoload' => true
                                     ));
                        ?>
                        <?php break; ?>
                        <?php case 'Image with custom lightbox' : ?>
                        
                        <?php echo r_image(array(
                                     'link' => '#custom-lightbox-' . $count,
                                     'src' => $portfolio_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $image_width,
                                     'height' => $image_height,
                                     'rel' => 'lightbox[portfolio]',
                                     'classes' => 'hover',
                                     'autoload' => true
                                     ));
                        ?>
                        <div id="custom-lightbox-<?php echo $count; ?>" class="hidden-content">
                        <?php echo do_content($lightbox_content); ?>
                        </div>
                        <?php break; ?>
                        <?php case 'Image with link' : ?>
                        
                        <?php echo r_image(array(
                                     'link' => $link_url,
                                     'src' => $portfolio_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => $image_width,
                                     'height' => $image_height,
                                     'classes' => $target . ' hover',
                                     'autoload' => true
                                     ));
                        ?>
                        
                        <?php break; ?>                               
                        
                        <?php }; // End Switch ?>
                        <?php if (isset($music) && $music != '') : ?>
                        <?php $music = theme_path($music); ?>
                        <div id="music-player-<?php echo $count; ?>" class="music-player">
                            <ul class="playlist portfolio-player">
                                <li><a href="<?php echo $music; ?>" id="portolio-music-<?php the_ID(); ?>"><?php echo $music_title; ?></a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
                        </div>
                    <?php if ($portfolio_layout == '1') echo '</div><div class="column col-1-3 portfolio last">'; ?>
                    <?php if ($title_link) : ?>
                    <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    <?php else : ?>
                    <h2><?php the_title(); ?></h2>
                    <?php endif; ?>
                    <?php the_excerpt(); ?>
                    
                </div>
                <!-- /portfolio -->
                <?php if ($count % (int)$portfolio_layout == 0) echo '<div class="clear"></div>'; ?>
                
                <?php endwhile; ?>
                <?php endif; ?>
          
         	</div>
		</div>
        <div class="clear"></div>
        <?php if (function_exists('wp_pagenavi')) {wp_pagenavi();} ?>
	</div>
    <!-- /left -->
</div>
<!-- end content-->
<?php get_footer(); ?>