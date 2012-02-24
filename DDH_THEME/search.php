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
    <div id="left" class="wide">
    	<h3><?php _e('Search results for', SHORT_NAME); echo  ' "' . $_GET['s'] .'"'; ?></h3>
      	<?php
		global $more, $query_string;
		$more = 1;
		$pp = 5;
	    $posts = query_posts($query_string.'&posts_per_page='.$pp);
	  	?>
     	<?php if (have_posts()) : ?>
	  	<?php while (have_posts()) : the_post();?>
      	<!-- entry -->
      	<div class="entry">
        	<h2 class="entry-heading"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
        	<span class="line"></span>
        	<!-- entry-content -->
        	<div class="entry-content">
            	<?php the_content();?>
            	<a class="read-more-button" href="<?php the_permalink(); ?>">Read more...</a>
          		<div class="clear"></div>
        	</div>
        	<!-- /entry-content -->
     	</div>
      	<!-- /entry -->
    	<?php endwhile; ?>
    	<script type="text/javascript">
 		//<![CDATA[
			jQuery(document).ready(function () {
			jQuery('#left').highlight('<?php echo $search_words = $_GET['s']; ?>');
			})
		//]]>
		</script>
    	<?php else: ?>
		<div class="entry">
  			<h1 class="entry-heading"><?php _e('Search key not found.', SHORT_NAME); ?></h1>
    		<span class="line"></span>
    		<div class="entry-content">
    			<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps searching will help.', SHORT_NAME); ?></p>
    		</div>
    	</div>
    	<?php endif; ?>  
    	<?php if (function_exists('wp_pagenavi')) {wp_pagenavi();} ?>
	</div>
    <!-- /left -->
</div>
<!-- /content-->
<?php get_footer(); ?>