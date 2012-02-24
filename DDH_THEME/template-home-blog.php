<?php
/*
Template Name: Homepage Blog
*/
?>
<?php get_header(); ?>
  
<?php global $r_option; ?>

<?php 
	global $more, $wp_query; 
	$blog_layout = get_post_meta($wp_query->post->ID, '_blog_layout', true);
	$blog_category = get_post_meta($wp_query->post->ID, '_blog_category', true);
	$custom_sidebar = get_post_meta($wp_query->post->ID, '_custom_sidebar', true);
	
	/* Set page width */
    $wide = strpos($blog_layout, 'wide');
    if ($wide !== false) {
       $sidebar = false;
    } else {
		$sidebar = true;
    }
?>

<?php include_once(THEME.'/includes/intro.php'); ?>

<!-- Homepage slider -->
<?php include_once(THEME . '/includes/r_slider.php'); ?>

<!-- content -->
<div class="content">
	<div id="breadcrumb-wrap" class="pngfix">
    	<ul id="breadcrumb">
      		<?php breadcrumb(); ?>
    	</ul>
    </div>
    <div class="clear"></div>
    <!-- left -->
    <div id="left" <?php if ($sidebar) echo 'class="narrow"'; else echo 'class="wide"'; ?>>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
       	<?php endwhile; ?>
        <?php endif; ?>
        <?php
		$more = 0;
		if (get_query_var('paged')) $paged = get_query_var('paged');
		elseif (get_query_var('page')) $paged = get_query_var('page');
		else $paged = 1;
		$args = array(
					  'cat' => $blog_category,
					  'showposts'=> $posts_per_page,
					  'paged' => $paged
					  );
		$wp_query = new WP_Query();
		$wp_query->query($args);
		?>
    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php 
		
		 /* Post image */
         $post_image = get_post_meta($wp_query->post->ID, '_post_image', true); 
		 
		 /* Entry Class */
		 $entry_class = '';
                
         /* Cropping */
         $crop = get_post_meta($wp_query->post->ID, '_post_image_crop', true);
         $crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
		 
		 /* Post Date */
         $post_date = get_post_meta($wp_query->post->ID, '_post_date', true);
         $post_date = isset($post_date) && $post_date == 'on' ? $post_date = true : $post_date = false;
		 
		 /* Post Categories */
         $post_categories = get_post_meta($wp_query->post->ID, '_post_categories', true);
         $post_categories = isset($post_categories) && $post_categories == 'on' ? $post_categories = true : $post_categories = false;
		
		?>
        <!-- entry -->
      	<div id="post-<?php the_ID(); ?>" class="entry">
      		<?php include(THEME . '/includes/user.php'); ?>
        	<h2 class="entry-heading"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                <div class="metadata">
                <?php if (!$post_date) : ?>
                <span class="meta-date">
                    <?php the_time($r_option['custom_date']); ?>
                </span>
                <?php endif; ?>
                <?php if ('open' == $wp_query->post->comment_status) : ?>
                <span class="meta-comments">
                    <a href="<?php the_permalink();?>#comments"><?php _e('Comments', SHORT_NAME); ?> <?php comments_number('0','1','%'); ?></a>
                </span>
                <?php endif; ?>
                <?php if (!$post_categories) : ?><?php the_category(' / '); ?><?php endif; ?>
        	</div>
        	<span class="line"></span>
            
            <?php switch ($blog_layout) {
            case 'sidebar_right' : ?>
        	<!-- entry-content -->
            <div class="entry-content">
                <div class="entry-text">
                <?php if (has_excerpt()) : ?>
                <?php the_excerpt(); ?>
                <a class="read-more-button" href="<?php the_permalink(); ?>"></a>
                <?php else : ?>
                <?php the_content(__('Read more...', SHORT_NAME)); ?> 
                <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /entry-content -->
            
            <?php break; ?>
            <?php case 'wide' : ?>
            
            <!-- entry-content -->
            <div class="entry-content">
                <div class="entry-text">
                <?php if (has_excerpt()) : ?>
                <?php the_excerpt(); ?>
                <a class="read-more-button" href="<?php the_permalink(); ?>"></a>
                <?php else : ?>
                <?php the_content(__('Read more...', SHORT_NAME)); ?> 
                <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /entry-content -->
            
            <?php break; ?>
            <?php case 'big_thumb' : ?>
            <?php if (isset($post_image) && $post_image != '') : ?>
            <div class="image-l">
                <div class="image-l-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $post_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => '614',
                                     'height' => '275',
									 'classes' => 'hover',
                                     'autoload' => true
                                     ));
                ?>
            </div>
            <?php endif; ?>
            <!-- entry-content -->
            <div class="entry-content">
                <div class="entry-text">
                <?php if (has_excerpt()) : ?>
                <?php the_excerpt(); ?>
                <a class="read-more-button" href="<?php the_permalink(); ?>"></a>
                <?php else : ?>
                <?php the_content(__('Read more...', SHORT_NAME)); ?> 
                <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /entry-content -->
                       
            <?php break; ?>
            <?php case 'small_thumb' : ?>
            <?php if (isset($post_image) && $post_image != '') : ?>
            <div class="entry-image">
                <div class="entry-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $post_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => '280',
                                     'height' => '280',
									 'classes' => 'hover',
                                     'autoload' => true
                                     ));
                ?>
                <?php $entry_class = 'entry-text-310'; ?>
            </div>
            <?php endif; ?>
            <!-- entry-content -->
            <div class="entry-content">
                <div class="entry-text <?php echo $entry_class ?>">
                <?php if (has_excerpt()) : ?>
                <?php the_excerpt(); ?>
                <a class="read-more-button" href="<?php the_permalink(); ?>"></a>
                <?php else : ?>
                <?php the_content(__('Read more...', SHORT_NAME)); ?> 
                <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /entry-content -->
            
            <?php break; ?>
            <?php case 'small_thumb_wide' : ?>
            <?php if (isset($post_image) && $post_image != '') : ?>
            <div class="entry-image">
                <div class="entry-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $post_image,
                                     'crop' => $crop,
                                     'title' => get_the_title(),
                                     'width' => '280',
                                     'height' => '280',
									 'classes' => 'hover',
                                     'autoload' => true
                                     ));
                ?>
                <?php $entry_class = 'entry-text-646'; ?>
            </div>
            <?php endif; ?>
            <!-- entry-content -->
            <div class="entry-content">
                <div class="entry-text <?php echo $entry_class ?>">
                <?php if (has_excerpt()) : ?>
                <?php the_excerpt(); ?>
                <a class="read-more-button" href="<?php the_permalink(); ?>"></a>
                <?php else : ?>
                <?php the_content(__('Read more...', SHORT_NAME)); ?> 
                <?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <!-- /entry-content -->
            
            <?php break; ?>
            
            <?php }; // End Switch ?>
		</div>
    	<!-- /entry -->
        
    	<?php endwhile; ?>
        <?php endif; ?>
        <?php if (function_exists('wp_pagenavi')) {wp_pagenavi();} ?>
	</div>
	<!-- /left -->
    <?php if ($sidebar) : ?>
	<!-- right -->
	<div id="right">
    	<?php if ($custom_sidebar == '' || $custom_sidebar == '_default') : ?>
    	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Default')) ?>
    	<?php else : ?>
	    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($custom_sidebar)) ?> 
    	<?php endif; ?>
    </div>
    <!-- /right -->
    <?php endif; ?>
</div>
<!-- /content -->
<?php get_footer(); ?>
