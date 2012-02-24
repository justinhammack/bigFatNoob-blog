<?php
/*
Template Name: Events
*/
?>

<?php global $r_option; ?>

<?php get_header(); ?>

<?php 
	global $more, $wp_query; 
	$events_layout = get_post_meta($wp_query->post->ID, '_events_layout', true);
	$event_type = get_post_meta($wp_query->post->ID, '_event_type', true);
	$custom_sidebar = get_post_meta($wp_query->post->ID, '_custom_sidebar', true);
	
	/* Pagination Limit */
	$limit = (int)get_post_meta($wp_query->post->ID, '_limit', true);
	$limit = $limit && $limit == '' ? $limit = 6 : $limit = $limit;
	
	/* Set page width */
    $wide = strpos($events_layout, 'wide');
    if ($wide !== false) {
       $sidebar = false;
    } else {
		$sidebar = true;
    }
?>
<?php include_once(THEME.'/includes/intro.php'); ?>
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
		if (isset($r_option['events_order']) && $r_option['events_order'] == 'start_date') $events_order = '_event_date_start';
	    else $events_order = '_event_date_end';
		$more = 0;
		$pp = $posts_per_page;
		$order = $event_type == 'Future events' ? $order = 'ASC' : $order = 'DSC';
  		$args = array(
					  'post_type' => 'wp_events_manager',
					  'wp_event_type' => $event_type,
					  'showposts'=> $limit,
                      'paged' => $paged,
					  'orderby' => 'meta_value',
				  	  'meta_key' => $events_order,
				  	  'order' => $order
					  );
		$wp_query = new WP_Query();
		$wp_query->query($args);
		?>
    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php 
		
		 /* Post Image */
         $event_image = get_post_meta($wp_query->post->ID, '_event_image', true); 
		 
		 /* Entry Class */
		 $entry_class = '';
                
         /* Cropping */
         $crop = get_post_meta($wp_query->post->ID, '_event_image_crop', true);
         $crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
		 
		 /* Post Date */
         $post_date = get_post_meta($wp_query->post->ID, '_post_date', true);
         $post_date = isset($post_date) && $post_date == 'on' ? $post_date = true : $post_date = false;
		 
		 /* Event Date */
		 $event_date_start = strtotime(get_post_meta($wp_query->post->ID, '_event_date_start', true));
		 $event_date_end = strtotime(get_post_meta($wp_query->post->ID, '_event_date_end', true));
		 
		?>
        <!-- entry -->
      	<div id="post-<?php the_ID(); ?>" class="entry">
      		<?php include(THEME . '/includes/user.php'); ?>
        	<h2 class="entry-heading"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                <div class="metadata">
                <?php if (!$post_date) : ?>
                <span class="meta-date">
                    <?php echo date($r_option['custom_date'], $event_date_start); ?>
                    <?php if ($event_date_start != $event_date_end) : ?>
                     - <?php echo date($r_option['custom_date'], $event_date_end); ?>
                     <?php endif; ?>
                </span>
                <?php endif; ?>
                <?php if ('open' == $wp_query->post->comment_status) : ?>
                <span class="meta-comments">
                    <a href="<?php the_permalink();?>#comments"><?php _e('Comments', SHORT_NAME); ?> <?php comments_number('0','1','%'); ?></a>
                </span>
                <?php endif; ?>
        	</div>
        	<span class="line"></span>
            
            <?php switch ($events_layout) {
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
            <?php if (isset($event_image) && $event_image != '') : ?>
            <div class="image-l">
                <div class="image-l-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $event_image,
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
            <?php if (isset($event_image) && $event_image != '') : ?>
            <div class="entry-image">
                <div class="entry-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $event_image,
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
            <?php if (isset($event_image) && $event_image != '') : ?>
            <div class="entry-image">
                <div class="entry-frame"></div>
				<?php echo r_image(array(
                                     'link' => get_permalink(),
                                     'src' => $event_image,
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