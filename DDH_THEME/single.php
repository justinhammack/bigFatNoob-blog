<?php global $r_option; ?>

<?php get_header(); ?>

<?php 
	global $wp_query; 
	$post_layout = get_post_meta($wp_query->post->ID, '_post_layout', true);
	$custom_sidebar = get_post_meta($wp_query->post->ID, '_custom_sidebar', true);
	$post_layout = isset($post_layout) && $post_layout != '' ? $post_layout = $post_layout : $post_layout = 'sidebar_right';
?>

<?php include_once(THEME.'/includes/intro.php'); ?>
<div class="content">
<!-- start content -->
	<div id="breadcrumb-wrap" class="pngfix">
    	<ul id="breadcrumb">
      		<?php breadcrumb(); ?>
    	</ul>
    </div>
    <div class="clear"></div>
    <!-- start left -->
    <div id="left" <?php if ($post_layout == 'wide') echo 'class="wide"'; else echo 'class="narrow"'; ?>>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
		
		 /* Post Date */
         $post_date = get_post_meta($wp_query->post->ID, '_post_date', true);
         $post_date = isset($post_date) && $post_date == 'on' ? $post_date = true : $post_date = false;
		 
		 /* Post Categories */
         $post_categories = get_post_meta($wp_query->post->ID, '_post_categories', true);
         $post_categories = isset($post_categories) && $post_categories == 'on' ? $post_categories = true : $post_categories = false;
		 
		 /* Post Author Info */
         $post_author_info = get_post_meta($wp_query->post->ID, '_post_author_info', true);
         $post_author_info = isset($post_author_info) && $post_author_info == 'on' ? $post_author_info = true : $post_author_info = false;
		 
		 /* Post Author Info */
         $post_bookmarks = get_post_meta($wp_query->post->ID, '_post_bookmarks', true);
         $post_bookmarks = isset($post_bookmarks) && $post_bookmarks == 'on' ? $post_bookmarks = true : $post_bookmarks = false;
		
		?>
		<div id="post-<?php the_ID(); ?>" class="entry">
      		<?php include_once(THEME . '/includes/user.php'); ?>
        	<h1 class="entry-heading"><?php the_title();?></h1>
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
      		<div class="entry-content">
				<?php the_content();?>
                <div class="clear"></div>
                <?php the_tags(); ?>
      		</div>
            <?php if (!$post_bookmarks) include_once(THEME . '/includes/bookmarks.php'); ?>
            <?php if (!$post_author_info && get_the_author_meta('description') != '') include_once(THEME . '/includes/author.php'); ?>
    	</div>
        <?php endwhile; ?>
        <?php endif; ?>
  		<?php if ('open' == $post->comment_status) comments_template(); ?>
	</div>
    <!-- end left -->
    <?php if ($post_layout == 'sidebar_right') : ?>
    <!-- start right -->
    <div id="right">
    	<?php if ($custom_sidebar == '' || $custom_sidebar == '_default') : ?>
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Default')) ?>
        <?php else : ?>
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar($custom_sidebar)) ?> 
        <?php endif; ?>
    </div>
    <!-- end right -->
    <?php endif; ?>
</div>
<!-- end content-->
<?php get_footer(); ?>