<?php
/*
Template Name: Portfolio Music
*/
?>
<?php global $r_option; ?>

<?php get_header(); ?>

<?php 
	global $more, $wp_query;
	
	/* Image Size */
	$image_width = '290';
	$image_height = '210';
	
	/* Portfolio Category */
	$portfolio_category = get_post_meta($wp_query->post->ID, '_portfolio_category', true);
	$portfolio_category = !$portfolio_category || $portfolio_category == '_all' ? $portfolio_category = '' : $portfolio_category = $portfolio_category;
	
	/* Pagination Limit */
	$limit = (int)get_post_meta($wp_query->post->ID, '_limit', true);
	$limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;
?>

<?php include_once(THEME . '/includes/intro.php'); ?>
<div class="content">
<!-- start content -->
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
                              'paged' => $paged,
							  'meta_key' => '_music',
							  'meta_compare' => '!=',
							  'meta_value'=> ''
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

                /* Custom link URL */
                $link_url = get_post_meta($wp_query->post->ID, '_link_url', true);
                $link_url = isset($link_url) && $link_url != '' ? $link_url = $link_url : $link_url = '';
				
				/* Title link URL */
                $title_link = get_post_meta($wp_query->post->ID, '_title_link', true);
                $title_link = isset($title_link) && $title_link == 'on' ? $title_link = true : $title_link = false;
				
				/* Music */
                $music = get_post_meta($wp_query->post->ID, '_music', true);

                ?>
               <!-- music -->
                <div class="release <?php if ($count % 3 == 0) echo 'no-margin'; ?>">
                    <div class="release-image">
                        <span class="release-badge" id="badge_rp_<?php echo $count; ?>"></span>
                        <div class="release-frame"></div>
                        <?php if (isset($music) && $music != '') : ?>
                        <div class="release-data">
                            <div class="release-wrap">
                                <div class="release-content">
                                    <?php if ($title_link) : ?>
                                    <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							        <?php else : ?>
                                    <h2><?php the_title(); ?></h2>
                                    <?php endif; ?>
                                    <div class="release-player">
                                        <object id="rp_<?php echo $count; ?>" width="290" height="70" type="application/x-shockwave-flash" data="<?php echo THEME_SCRIPTS_URI; ?>/r-player/r-player.swf">
                                            <param name="flashvars" value="file=<?php echo $music; ?>&amp;id=rp_<?php echo $count; ?>&amp;info_url=<?php echo $link_url; ?>" />
                                            <param name="allowfullscreen" value="true" />
                                            <param name="allowscriptaccess" value="always" />
                                            <param name="wmode" value="opaque" />
                                            <param name="movie" value="<?php echo THEME_SCRIPTS_URI; ?>/r-player/r-player.swf" />
                                        </object>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>  
                     
                      <a href="#" rev="<?php echo r_image_resize($image_width, $image_height, $portfolio_image, $crop); ?>" class="autoload-release-image"></a>
                      </div>
                </div>
                <!-- /music -->
                <?php if ($count % 3 == 0) : ?>
                <div class="clear"></div>
                <?php endif; ?>         
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