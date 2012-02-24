<?php
/*
Template Name: Archives
*/
?>

<?php global $r_option; ?>

<?php get_header(); ?>

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
    <div id="left" class="narrow">	
      <?php if (have_posts()) : ?>
	  <?php while (have_posts()) : the_post();?>
      <!-- entry -->
      <div class="entry">
        <!-- entry-content -->
        <div class="entry-content">
        <h3><?php _e('Archives by Year', SHORT_NAME); ?></h3>
  		<ul>
    		<?php wp_get_archives('type=yearly&show_post_count=true'); ?>
 		</ul>
        <h3><?php _e('Archives by Month', SHORT_NAME); ?></h3>
  		<ul>
    		<?php wp_get_archives('type=monthly&show_post_count=true'); ?>
 		</ul>
		<h3><?php _e('Archives by Subject', SHORT_NAME); ?></h3>
 		<ul>
    		 <?php wp_list_categories('optioncount=1&title_li='); ?>
 		</ul>
          <div class="clear"></div>
        </div>
        <!-- /entry-content -->
      </div>
      <!-- /entry -->
    <?php endwhile; ?>
    <?php endif; ?>
    </div>
    <!--/left -->
    <!-- right -->
    <div id="right">
   	<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Archive')) : ?>
    <?php endif; ?> 
    </div>
    <!-- /right -->
  </div>
  <!-- /content-->
<?php get_footer(); ?>