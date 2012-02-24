<?php
/*
Template Name: Homepage
*/
?>
<?php get_header(); ?>
  
<?php global $r_option; ?>
  
<?php include_once(THEME . '/includes/intro.php'); ?>

<!-- Homepage slider -->
<?php include_once(THEME . '/includes/r_slider.php'); ?>

<!-- start homepage boxes -->
<div class="content">
	<!-- start homepage boxes -->
	<div id="hb">
   		<div id="hb-col1" <?php if ($r_option['wide_text_box'] == 'on') echo 'class="col1-wide"'; ?>>
       		<h1><?php echo $r_option['text_box_heading']; ?></h1>
   			<?php echo do_content($r_option['text_box']); ?>
        </div>
        
		<?php if ($r_option['wide_text_box'] == 'off') : ?>
		<div id="hb-col2">
			<h3><?php echo $r_option['recent_posts_heading']; ?></h3>
			<?php if ($r_option['events_manager'] == 'on') : ?>
            <?php echo r_events_list($atts = array('display_limit' => '3')); ?>
            <?php else : ?>
            <?php echo r_posts_list($atts = array('display_limit' => '3', 'cat' => $r_option['recent_posts_category'], 'limit' => '-1')); ?>
            <?php endif; ?>
      	</div>
      	<div id="hb-col3">
      		<h3><?php echo $r_option['recent_works_heading']; ?></h3>
        	<!-- Recent slider -->
			<?php include_once(THEME . '/includes/recent_slider.php'); ?>
      	</div>
		<?php endif; // End wide text box ?>
	</div>
	<div class="clear"></div>
</div>  
<?php get_footer(); ?>