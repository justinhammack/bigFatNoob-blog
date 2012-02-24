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
        <div class="entry">
            <h1 class="entry-heading"><?php _e('404 - Not Found', SHORT_NAME); ?></h1>
            <span class="line"></span>
            <div class="entry-content">
                <p><?php _e('Apologies, but the page you are looking for does not exist.', SHORT_NAME); ?></p>
            </div>
        </div>
	</div>
    <!-- /left -->
</div>
<!-- /content-->
<?php get_footer(); ?>