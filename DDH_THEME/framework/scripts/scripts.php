<?php

/* Theme Scripts
 ------------------------------------------------------------------------*/
function r_theme_scripts() {

	global $r_option;

	if (!is_admin() && !is_login_page()) {

		/* Theme Styles */
		wp_enqueue_style('style', THEME_URI . '/style.css', false, '1.0.0', 'screen');
		if (!isset($r_option['style']) || $r_option['style'] == '') $r_option['style'] = 'default.css';
        wp_enqueue_style('color-style', THEME_URI . '/styles/' . $r_option['style'], false, '1.0.0', 'screen');

		/* jQuery */
		wp_deregister_script('jquery');
		wp_register_script('jquery', THEME_SCRIPTS_URI . ' /jquery-1.8.1.min.js', false, '1.8.1', true);

		/* jQuery Easing */
		wp_enqueue_script('jquery-easing', THEME_SCRIPTS_URI . ' /jquery.easing-1.3.js', array('jquery'), '1.3');

		/* Custom scripts */
		wp_enqueue_script('custom', THEME_SCRIPTS_URI . ' /custom.js', array('jquery'), '1.0');
		$js_variables = array('swf_path' => THEME_SCRIPTS_URI . '/soundmanager2/swf',
							  'rf_invalid_answer' => __('Error: Invalid answer', SHORT_NAME),
							  'rf_is_not_valid' => __('Error: Value is not valid', SHORT_NAME),
							  'rf_success' => __('Your message has been sent. Thank you for contacting us.!', SHORT_NAME),
							  'rf_error' => __('Error: Sending message', SHORT_NAME),
							  'lightbox' => $r_option['lightbox'],
							  'lightbox_deeplinking' => $r_option['lightbox_deeplinking'],
							  'lightbox_social_tools' => $r_option['lightbox_social_tools'],
							  'lightbox_gallery' => $r_option['lightbox_gallery'],
							  'lightbox_style' => $r_option['lightbox_style'],
							  'use_cufon_fonts' => $r_option['use_cufon_fonts']
							);
		wp_localize_script('custom', 'theme_vars', $js_variables);

		/* Sound scripts */
		wp_enqueue_script('soundmanager2', THEME_SCRIPTS_URI . ' /soundmanager2/soundmanager2-nodebug-jsmin.js', array('jquery'), '1.0' );
		//wp_enqueue_script('soundmanager2', THEME_SCRIPTS_URI . ' /soundmanager2/soundmanager2.js', array('jquery'), '1.0' );
		wp_enqueue_script('page-player', THEME_SCRIPTS_URI . ' /soundmanager2/page-player.js', array('soundmanager2'), '1.0');

		/* Rascals Scripts */
		wp_enqueue_script('R-Forms', THEME_SCRIPTS_URI . ' /jquery.R-Forms.js', array('jquery'), '1.3');
		wp_localize_script('R-Forms', 'ajax_action', array('ajaxurl' => admin_url('admin-ajax.php'), 'ajax_nonce' => wp_create_nonce('ajax-nonce')));

		wp_enqueue_script('R-Slider', THEME_SCRIPTS_URI . ' /jquery.R-Slider.js', array('jquery'), '2.1');
		wp_enqueue_script('R-Menu', THEME_SCRIPTS_URI . ' /jquery.R-Menu.js', array('jquery'), '1.2');
		wp_enqueue_script('R-Dynamic-Images', THEME_SCRIPTS_URI . ' /jquery.R-Dynamic-Images.js', array('jquery'), '1.2');
		wp_enqueue_script('R-Scripts', THEME_SCRIPTS_URI . ' /jquery.R-Scripts.js', array('jquery'), '1.0');

		/* HTML5 Scripts */
		wp_enqueue_script('HTML5', THEME_SCRIPTS_URI . ' /html5.js', array('jquery'), '1.0');

		/* Cufon Fonts */
		if ($r_option['use_cufon_fonts'] == 'on') {
			wp_enqueue_script('cufon', THEME_SCRIPTS_URI . ' /cufon-yui.js', array('jquery'));

			$cufon_fonts = explode('|', $r_option['cufon_fonts']);
			if (is_array($cufon_fonts)) {
				foreach($cufon_fonts as $i => $font) {
                    wp_enqueue_script('cufon-font-' . $i, THEME_URI . ' /styles/cufon_fonts/' . $font, array('jquery'));
				}
			}

		}

		/* PrettyPhoto */
		if (isset($r_option['lightbox']) && $r_option['lightbox'] == 'on') {
			wp_enqueue_style('prettyPhoto_style', THEME_SCRIPTS_URI . '/pretty_photo/css/prettyPhoto.css', false, '1.0.0', 'screen');
			wp_enqueue_script('prettyPhoto', THEME_SCRIPTS_URI . ' /pretty_photo/js/jquery.prettyPhoto.js', array('jquery'));
		}

	}
}
add_action('init', 'r_theme_scripts');

function cufon_code() {
	global $r_option;
	if ($r_option['use_cufon_fonts'] == 'on') {
		echo "<script type='text/javascript'>\n /* <![CDATA[ */\n";
		echo "jQuery.noConflict();\n";
		echo "jQuery(document).ready(function () {\n";
		echo $r_option['cufon_code'];
		echo "\n})\n";
		echo "/* ]]> */\n";
		echo "</script>\n";
	}
}

add_action('wp_head', 'cufon_code');
?>