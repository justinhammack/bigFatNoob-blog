<?php global $r_option; ?>

<?php get_header(); ?>

<?php include_once(THEME . '/includes/intro.php'); ?>
<!-- content -->
<div class="content">
	<div id="breadcrumb-wrap" class="pngfix">
    	<ul id="breadcrumb">
      		<?php breadcrumb(); ?>
      	</ul>
    </div>
	<div class="clear"></div>
    <!-- left -->
	<div id="left" class="narrow">
    	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post();?>
    	<?php
		
		/* Post image */
        $post_image = get_post_meta($wp_query->post->ID, '_post_image', true);
		
		/* Cropping */
        $crop = get_post_meta($wp_query->post->ID, '_post_image_crop', true);
        $crop = isset($crop) && $crop != '' ? $crop = $crop : $crop = 'c';
		
		/* Entry Class */
		$entry_class = '';
		
		?>
      	<!-- entry -->
      	<div class="entry">
      		<?php include(THEME . '/includes/user.php'); ?>
        	<h2 class="entry-heading"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
             <div class="metadata">
                <span class="meta-date"><?php the_time($r_option['custom_date']); ?></span>
                <span class="meta-comments"><a href="<?php the_permalink();?>#comments"><?php _e('Comments', SHORT_NAME); ?> <?php comments_number('0','1','%'); ?></a></span>
                <?php the_category(' / '); ?>
        	</div>
        	<span class="line"></span>
            
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
            <?php else : ?>
            <?php  $entry_class = 'entry-text-624'; ?>
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
            
		</div>
      	<!-- /entry -->
		<?php endwhile; ?>
		<?php endif; ?>    
		<?php if (function_exists('wp_pagenavi')) {wp_pagenavi();} ?>
    </div>
    <!-- /left -->
    <!-- right -->
    <div id="right">
   		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Category')) : ?>
    	<?php endif; ?> 
    </div>
    <!-- /right -->
</div>
<!-- /content-->
<?php get_footer(); ?>