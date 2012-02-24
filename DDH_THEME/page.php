<?php global $r_option; ?>

<?php get_header(); ?>

<?php 
	global $wp_query; 
	$page_layout = get_post_meta($wp_query->post->ID, '_page_layout', true);
	$custom_sidebar = get_post_meta($wp_query->post->ID, '_custom_sidebar', true);
	$page_layout = isset($page_layout) && $page_layout != '' ? $page_layout = $page_layout : $page_layout = 'sidebar_right';
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
    <div id="left" <?php if ($page_layout == 'wide') echo 'class="wide"'; else echo 'class="narrow"'; ?>>
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" class="entry">
      		<?php include_once(THEME . '/includes/user.php'); ?>
        	<h1 class="entry-heading"><?php the_title();?></h1>
        	<span class="line"></span>
      		<div class="entry-content">
				<?php the_content();?>
      			<div id="page-link"><?php wp_link_pages('page-link=%'); ?></div>   
      		</div>
    	</div>
        <?php endwhile; ?>
        <?php endif; ?>
  		<?php if ('open' == $post->comment_status) comments_template(); ?>
	</div>
    <!-- end left -->
    <?php if ($page_layout == 'sidebar_right') : ?>
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