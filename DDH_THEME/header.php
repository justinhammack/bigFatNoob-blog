<!DOCTYPE html>
<?php global $r_option; ?>
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html <?php language_attributes(); ?> class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?> >
<!--<![endif]-->
<head>
<title><?php wp_title('&lsaquo;', true, 'right');?><?php bloginfo('name');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
<meta name="keywords" content="<?php bloginfo('name'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php if (isset($r_option['favicon']) && $r_option['favicon'] != '') : ?>
<link rel="icon" href="<?php echo $r_option['favicon']; ?>"/>
<link rel="shortcut icon" href="<?php echo $r_option['favicon']; ?>"/>
<link rel="apple-itouch-icon" href="<?php echo $r_option['favicon']; ?>">
<?php endif; ?>
<?php wp_head();?>
<style type="text/css"><!--
<?php if($r_option['menu_width'] != '') : ?>
ul#menu li ul {
	width: <?php echo $r_option['menu_width']; ?>px;
}
<?php endif; ?>
<?php if (isset($r_option['custom_css']) && $r_option['custom_css'] != '') echo $r_option['custom_css']; ?>
--></style>
<?php if (isset($r_option['custom_js']) && $r_option['custom_js'] != '') echo $r_option['custom_js']; ?>
<!-- GUILD WARS 2 CUSTOM SCRIPT -->
<script type="text/javascript" src="http://static-ascalon.cursecdn.com/1-0-4619-33130/js/syndication/tt.js"></script>
</head>
<body  <?php body_class(); ?>>
<!--[if lte IE 6]>
   <div id="ie-message"><p><?php _e('You are using Internet Explorer 6.0 or older to view the web. IE6 is an eight year old browser which does not display modern web sites properly.  Please upgrade to a newer browser to fully enjoy the web. <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Upgrade your browser</a>', SHORT_NAME); ?></p></div>
<![endif]-->
<!-- start bg-wrap -->
<div id="bg-wrap">
	<div id="header-stripe"></div>
	<div id="header">
    	<div id="header-content">
       		<div id="logo">
  	        	<a href="<?php echo home_url(); ?>" title="<?php esc_attr(bloginfo('name', 'display')); ?>" ><img src="<?php echo $r_option['logo']; ?>" title="<?php esc_attr(bloginfo('name', 'display')); ?>" alt="<?php esc_attr(bloginfo('name', 'display')); ?>" /></a>
            </div>
      		<div id="menu-wrap">
      			<?php wp_nav_menu(array('theme_location' => 'main', 'menu_id' => 'menu')); ?>
      		</div>
      		<!-- end nav-container -->
   		</div>
    	<!-- end header-content -->
	</div>
	<!-- end header -->