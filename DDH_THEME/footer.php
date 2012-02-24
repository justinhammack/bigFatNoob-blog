<?php global $r_option; ?>
<div class="clear-footer"></div>
<?php if ($r_option['show_footer_widgets'] == 'on') : ?>
<!-- start footer -->
<div id="footer-wrap" class="pngfix">
	<div id="footer">
    	<div class="footer-col">
      		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Left')) : ?>
      		<?php endif; ?>  
      	</div>
      	<div class="footer-col">
      		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Center')) : ?>
      		<?php endif; ?>  
      	</div>
      	<div class="footer-col no-margin">
      		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer Right')) : ?>
      		<?php endif; ?>  
      	</div>
    </div>
</div>
<!-- end footer -->
<?php endif; ?>
<!-- start bottom -->
<div id="bottom">
	<div id="copyright">
    	<?php echo $r_option['copyright']; ?>
    </div>
    <div id="social">
    	<?php if ($r_option['show_rss'] == 'on') : ?>
     		<a class="rss" href="<?php bloginfo('rss2_url'); ?>">RSS<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['twitter']) && $r_option['twitter'] != '') : ?>
     		<a class="twitter" href="<?php echo $r_option['twitter'];?>">Twitter<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['facebook']) && $r_option['facebook'] != '') : ?>
     		<a class="facebook" href="<?php echo $r_option['facebook'];?>">Facebook<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['flickr']) && $r_option['flickr'] != '') : ?>
     		<a class="flickr" href="<?php echo $r_option['flickr'];?>">Flickr<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['lastfm']) && $r_option['lastfm'] != '') : ?>
     		<a class="lastfm" href="<?php echo $r_option['lastfm'];?>">LastFM<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['myspace']) && $r_option['myspace'] != '') : ?>
     		<a class="myspace" href="<?php echo $r_option['myspace'];?>">My Space<span></span></a>
     	<?php endif; ?>
        <?php if (isset($r_option['youtube']) && $r_option['youtube'] != '') : ?>
     		<a class="youtube" href="<?php echo $r_option['youtube'];?>">Youtube<span></span></a>
     	<?php endif; ?>
	</div>
</div>
  <!-- end bottom -->
</div>
<!-- end bg-wrap -->
<?php if (isset($r_option['google_analytics'])) echo $r_option['google_analytics']; ?>
<?php  if($r_option['use_cufon_fonts'] == 'on') : ?>
<script type="text/javascript"> Cufon.now(); </script>
<?php endif; ?>
<?php wp_footer(); ?> 
</body>
</html>