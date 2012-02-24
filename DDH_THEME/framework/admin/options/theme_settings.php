<?php

/* R-Panel Options
 ------------------------------------------------------------------------*/

/* Class arguments */
$args = array('menu_name' => _x('General Settings', 'r-panel', SHORT_NAME), 'option_name' => SHORT_NAME . '_general_settings');

/* Helper options */
$styles = array();
if (is_dir(THEME . "/styles/")) {
	if ($open_dir = opendir(THEME . "/styles/")) {
		$styles_count = 0;
		while (($style = readdir($open_dir)) !== false) {
			if (stristr($style, ".css") !== false) {
				$styles[$styles_count]['name'] = $style;
				$styles[$styles_count]['value'] = $style;
				$styles_count++;
			}
		}
	}
}

/* Options array */
$options = array(
				 
	/* General Settings */
	array(
		'type' => 'open',
		'tab_name' => _x('General Settings', 'r-panel', SHORT_NAME),
		'tab_id' => 'general-settings'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('General Settings', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-general-settings'
	),
	array(
		'name' => _x('Custom Date', 'r-panel', SHORT_NAME),
		'id' => 'custom_date',
		'type' => 'text',
		'std' => 'd/m/Y',
		'desc' => _x('Enter your custom date. <a href="http://codex.wordpress.org/Formatting_Date_and_Time">Click Here for more information</a>', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Image Quality', 'r-panel', SHORT_NAME),
		'id' => 'quality',
		'type' => 'range',
		'min' => 0,
		'max' => 100,
		'unit' => '',
		'std' => '80',
		'desc' => _x('Enter Quality images 0-100 <br/>100 - low compression (good quality)<br/>0 - high compression (weak quality)', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Footer */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Footer', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-footer'
	),
	array(
		'name' => _x('Copyright Text', 'r-panel', SHORT_NAME),
		'id' => 'copyright',
		'type' => 'textarea',
		'std' => '<p>Copyright &copy; 2011 Pendulum. All Rights Reserved. Design & development by Rascals Labs.</p>',
		'height' => '100',
		'desc' => _x('Enter HTML text.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Footer Widgets', 'r-panel', SHORT_NAME),
		'id' => 'show_footer_widgets',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('Show footer widgets.', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Google Codes */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Google Codes', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-google'
	),
	array(
		'name' => _x('Google Analytics Code', 'r-panel', SHORT_NAME),
		'id' => 'google_analytics',
		'type' => 'textarea',
		'std' => '',
		'height' => '100',
		'desc' => _x('Insert your Google Analytics code here.', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	array(
		'type' => 'close'
	),

	/* Customize */
	array(
		'type' => 'open',
		'tab_name' => _x('Customize', 'r-panel', SHORT_NAME),
		'tab_id' => 'customize'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Customize', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-customize'
	),
	array(
		'name' => _x('Custom Favicon', 'r-panel', SHORT_NAME),
		'id' => 'favicon',
		'type' => 'upload',
		'img_w' => '16',
		'img_h' => '16',
		'std' => '',
		'button_title' => _x('Add Image', 'r-panel', SHORT_NAME),
		'desc' => _x('Upload a 16px x 16px <a href="http://favicon-generator.org/">ico image</a> for your theme, or specify the image address.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Logo image', 'r-panel', SHORT_NAME),
		'id' => 'logo',
		'type' => 'upload',
		'img_w' => '300',
		'img_h' => '150',
		'std' => THEME_URI.'/assets/logo.png',
		'button_title' => _x('Add Image', 'r-panel', SHORT_NAME),
		'desc' => _x('Upload a logo for your theme, or specify the image address (http://yoursite.com/your_image.jpg).', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Theme Stylesheet', 'r-panel', SHORT_NAME),
		'id' => 'style',
		'type' => 'select',
		'std' => 'default.css',
		'desc' => _x('Select your color scheme.', 'r-panel', SHORT_NAME),
		'options' => $styles
	),
	array(
		'name' => _x('Submenu Width', 'r-panel', SHORT_NAME),
		'id' => 'menu_width',
		'type' => 'range',
		'min' => 150,
		'max' => 300,
		'unit' => 'px',
		'std' => '170',
		'desc' => _x('Enter submenu width.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Portfolio Order', 'r-panel', SHORT_NAME),
		'id' => 'portfolio_order',
		'type' => 'select',
		'std' => 'date',
		'desc' => _x('Portfolio order allows you to set the order of pages through a drag and drop interface or by date.', 'r-panel', SHORT_NAME),
		'options' => array(
			array('name' => 'Date', 'value' => 'date'),
			array('name' => 'Drag and drop', 'value' => 'custom')
		)
	),
	array(
		'name' => _x('Custom CSS', 'r-panel', SHORT_NAME),
		'id' => 'custom_css',
		'type' => 'textarea',
		'std' => '',
		'height' => '200',
		'desc' => _x('Type your custom CSS rules.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Custom Javascript', 'r-panel', SHORT_NAME),
		'id' => 'custom_js',
		'type' => 'textarea',
		'std' => '',
		'height' => '200',
		'desc' => _x('Type your custom Javascript code.', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Cufon Fonts */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Cufon Fonts', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-cufon'
	),
	array(
		'name' => _x('Select Cufon Fonts', 'r-panel', SHORT_NAME),
		'id' => 'cufon_fonts',
		'type' => 'cufon_fonts',
		'std' => 'ColaborateLight_400.font.js',
		'desc' => _x('Select cufon fonts.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Cufon Code', 'r-panel', SHORT_NAME),
		'id' => 'cufon_code',
		'type' => 'cufon_code',
		'height' => '100',
		'std' => "Cufon.replace(\"h1,h2,h3,h4,h5,h6\", {fontFamily : \"ColaborateLight\", hover: \"true\"});",
		'desc' => _x('Sample code: <br/>
					<code>
					Cufon.replace("h1,h2,h3,h4,h5,h6", {fontFamily : "PT Sans Narrow", hover: "true"});
					Cufon.replace("#intro-text h5", {fontFamily : "PT Sans Bold", hover: "true"});
					</code><br/>
					You can use the buttons above to paste the prepared code, you need only enter the HTML elements that you want to have been replaced. For more code tips go to official <a href="http://wiki.github.com/sorccu/cufon/styling">Cufon\'s site</a>.
					', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Cufon Fonts', 'r-panel', SHORT_NAME),
		'id' => 'use_cufon_fonts',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x('If this option is enabled automatically text elements will be replaced with the Cufon fonts.', 'r-panel', SHORT_NAME),
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Lightbox */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Lightbox', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-lightbox'
	),
	array(
		'name' => _x('PrettyPhoto Lightbox', 'r-panel', SHORT_NAME),
		'id' => 'lightbox',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x('Enable PrettyPhoto lightbox. <a href="http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/">Read more about prettyPhoto lightbox</a>.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Lightbox Deeplinking', 'r-panel', SHORT_NAME),
		'id' => 'lightbox_deeplinking',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('Allow lightbox to update the url to enable deeplinking.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Show Lightbox Social Tools', 'r-panel', SHORT_NAME),
		'id' => 'lightbox_social_tools',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x('Show lightbox social tools in popup window.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Lightbox Overlay Gallery', 'r-panel', SHORT_NAME),
		'id' => 'lightbox_gallery',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x('If set to true, a gallery will overlay the fullscreen image on mouse over.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Lightbox Style', 'r-panel', SHORT_NAME),
		'id' => 'lightbox_style',
		'type' => 'select',
		'std' => 'pp_default',
		'desc' => _x('Please select your lightbox scheme.', 'r-panel', SHORT_NAME),
		'options' => array(
						   array('name' => 'Default', 'value' => 'pp_default'),
						   array('name' => 'Facebook', 'value' => 'facebook'),
						   array('name' => 'Light square', 'value' => 'light_square'),
						   array('name' => 'Light rounded', 'value' => 'light_rounded'),
						   array('name' => 'Dark square', 'value' => 'dark_square'),
						   array('name' => 'Dark rounded', 'value' => 'dark_rounded')
		)
	),
	array(
		'type' => 'sub_close'
	),
	
	array(
		'type' => 'close'
	),

	/* Homepage */
	array(
		'type' => 'open',
		'tab_name' => _x('Homepage', 'r-panel', SHORT_NAME),
		'tab_id' => 'home-page'
	),
	
	/* Slider */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Slider', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-slider'
	),
	array(
		'name' => _x('Homepage Slider Delay', 'r-panel', SHORT_NAME),
		'id' => 'slider_delay',
		'type' => 'range',
		'min' => 0,
		'max' => 100,
		'unit' => 's',
		'std' => '0',
		'desc' => _x("Type homepage slider delay (in seconds), value '0' is disable timer.", 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Slide Transition Speed', 'r-panel', SHORT_NAME),
		'id' => 'slider_duration',
		'type' => 'range',
		'min' => 100,
		'max' => 5000,
		'unit' => 'ms',
		'std' => '1000',
		'desc' => _x('Type homepage slider speed (in milliseconds).', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Disable Slider Navigation', 'r-panel', SHORT_NAME),
		'id' => 'disable_slider_nav',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('If this option is enabled then disappears slider navigation.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Homepage Slider Easing', 'r-panel', SHORT_NAME),
		'id' => 'slider_easing',
		'type' => 'select',
		'std' => 'easeOutSine',
		'desc' => _x('Select Homepage slider easing.', 'r-panel', SHORT_NAME),
		'options' => array(
						   array('name' => 'easeInQuad', 'value' => 'easeInQuad'),
						   array('name' => 'easeOutQuad', 'value' => 'easeOutQuad'),
						   array('name' => 'easeInOutQuad', 'value' => 'easeInOutQuad'),
						   array('name' => 'easeInCubic', 'value' => 'easeInCubic'),
						   array('name' => 'easeOutCubic', 'value' => 'easeOutCubic'),
						   array('name' => 'easeInOutCubic', 'value' => 'easeInOutCubic'),
						   array('name' => 'easeInQuart', 'value' => 'easeInQuart'),
						   array('name' => 'easeOutQuart', 'value' => 'easeOutQuart'),
						   array('name' => 'easeInOutQuart', 'value' => 'easeInOutQuart'),
						   array('name' => 'easeInQuint', 'value' => 'easeInQuint'),
						   array('name' => 'easeOutQuint', 'value' => 'easeOutQuint'),
						   array('name' => 'easeInOutQuint', 'value' => 'easeInOutQuint'),
						   array('name' => 'easeInSine', 'value' => 'easeInSine'),
						   array('name' => 'easeOutSine', 'value' => 'easeOutSine'),
						   array('name' => 'easeInOutSine', 'value' => 'easeInOutSine'),
						   array('name' => 'easeInExpo', 'value' => 'easeInExpo'),
						   array('name' => 'easeOutExpo', 'value' => 'easeOutExpo'),
						   array('name' => 'easeInOutExpo', 'value' => 'easeInOutExpo'),
						   array('name' => 'easeInCirc', 'value' => 'easeInCirc'),
						   array('name' => 'easeOutCirc', 'value' => 'easeOutCirc'),
						   array('name' => 'easeInOutCirc', 'value' => 'easeInOutCirc'),
						   array('name' => 'easeInElastic', 'value' => 'easeInElastic'),
						   array('name' => 'easeOutElastic', 'value' => 'easeOutElastic'),
						   array('name' => 'easeInOutElastic', 'value' => 'easeInOutElastic'),
						   array('name' => 'easeInBack', 'value' => 'easeInBack'),
						   array('name' => 'easeOutBack', 'value' => 'easeOutBack'),
						   array('name' => 'easeInOutBack', 'value' => 'easeInOutBack'),
						   array('name' => 'easeInBounce', 'value' => 'easeInBounce'),
						   array('name' => 'easeOutBounce', 'value' => 'easeOutBounce'),
						   array('name' => 'easeInOutBounce', 'value' => 'easeInOutBounce')
		                  )
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Content */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Content', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-home-content'
	),
	array(
		'name' => _x('Show Only Text Box', 'r-panel', SHORT_NAME),
		'id' => 'wide_text_box',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('If this option is enabled you should see on the home page only the content of the text box and the remaining items will be hidden.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Text Box <span>(heading)</span>', 'r-panel', SHORT_NAME),
		'id' => 'text_box_heading',
		'type' => 'text',
		'std' => '<span>About</span>us',
		'desc' => _x('Enter HTML text <br/> If you want to change the colour of the text use: &lt;span>&lt;/span>', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Text Box <span>(content)</span>', 'r-panel', SHORT_NAME),
		'id' => 'text_box',
		'type' => 'textarea',
		'tinymce' => 'true',
		'std' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et dignissim ipsum. Nam ac interdum sem. Pellentesque diam lacus, dictum in dapibus id, hendrerit eget felis. Nunc nec turpis libero.</p><p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Maecenas euismod condimentum mollis. In non congue orci. Nulla nunc velit, volutpat vestibulum congue vitae, tincidunt at sem. Pellentesque tincidunt molestie mi, eu aliquam quam fringilla nec. Sed suscipit adipiscing urna, et varius libero commodo eget.</p>',
		'height' => '200',
		'desc' => _x('Entered text is visible on the homepage under the slider.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Recent Posts / Events <span>(heading)</span>', 'r-panel', SHORT_NAME),
		'id' => 'recent_posts_heading',
		'type' => 'text',
		'std' => '<span>Upcoming</span>events',
		'desc' => _x('Enter HTML text <br/> If you want to change the colour of the text use: &lt;span>&lt;/span> tag.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' =>  _x('Recent Posts Category', 'r-panel', SHORT_NAME),
		'id' => 'recent_posts_category',
		'type' => 'categories',
		'options' => array(
							array('name' => 'All', 'value' => '_all')
						    ),
		'desc' => _x('Select recent posts category. Categories without posts are not displayed.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Recent Posts / Events Date Format', 'r-panel', SHORT_NAME),
		'id' => 'recent_date_format',
		'type' => 'select',
		'std' => 'd/m/y',
		'desc' => _x('Please select your date format.', 'r-panel', SHORT_NAME),
		'options' => array(
						   array('name' => 'd/m/y', 'value' => 'd/m/y'),
						   array('name' => 'm/d/y', 'value' => 'm/d/y')
		)
	),
	array(
		'name' => _x('Events Manager', 'r-panel', SHORT_NAME),
		'id' => 'events_manager',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('If this opion is enabled, you should see future events on the homepage.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Events Manager Order', 'r-panel', SHORT_NAME),
		'id' => 'events_order',
		'type' => 'select',
		'std' => 'start_date',
		'desc' => _x('Please select events order.', 'r-panel', SHORT_NAME),
		'options' => array(
						   array('name' => 'Start event date', 'value' => 'start_date'),
						   array('name' => 'End event date', 'value' => 'end_date')
		)
	),
	array(
		'name' => _x('Recent Works <span>(heading)</span>', 'r-panel', SHORT_NAME),
		'id' => 'recent_works_heading',
		'type' => 'text',
		'std' => 'Recent<span>works</span>',
		'desc' => _x('If you want to change the colour of the text use: &lt;span>&lt;/span> tag.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Recent Works Category', 'r-panel', SHORT_NAME),
		'type' => 'taxonomy',
		'taxonomy' => 'wp_portfolio_categories',
		'options' => array(
		   				   array('name' => 'All', 'value' => '_all')
						   ),
		 'desc' => _x('Select recent works category.', 'r-panel', SHORT_NAME),
  		 'id' => 'recent_category'
    ),
	array(
		'name' => _x('Number of Recent Works', 'r-panel', SHORT_NAME),
		'id' => 'recent_works_limit',
		'type' => 'text',
		'std' => '-1',
		'desc' => _x('Enter the number of recent works that you see on the homepage. If you want to see all items from the category type "-1".', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	array(
		'type' => 'close'
	),
	
	/* Social integration */
    array(
		'type' => 'open',
		'tab_name' => _x('Social Integration', 'r-panel', SHORT_NAME),
		'tab_id' => 'social'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Social Integration', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-social'
	),
	array(
		'name' => _x('Display RSS Icon', 'r-panel', SHORT_NAME),
		'id' => 'show_rss',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x('If this opion is enabled, you should see the RSS icon in the footer section.', 'r-panel', SHORT_NAME),
	),
		array(
		'name' => _x('Twitter Username', 'r-panel', SHORT_NAME),
		'id' => 'twitter_username',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Twitter USERNAME. The passes of the name is necessary to the using LAST TWEET shortcode.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Twitter URL', 'r-panel', SHORT_NAME),
		'id' => 'twitter',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Twitter URL e.g http://twitter.com/your_twitter_name', 'r-panel', SHORT_NAME)
	),
	array(
		'name' =>  _x('Facebook URL', 'r-panel', SHORT_NAME),
		'id' => 'facebook',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Facebook URL e.g http://facebook.com/', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Flickr URL', 'r-panel', SHORT_NAME),
		'id' => 'flickr',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Flickr URL e.g http://flickr.com/', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Lastfm URL', 'r-panel', SHORT_NAME),
		'id' => 'lastfm',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Lastfm URL e.g http://lastfm.com/', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('MySpace URL', 'r-panel', SHORT_NAME),
		'id' => 'myspace',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your Myspace URL e.g http://myspace.com/', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('YouTube URL', 'r-panel', SHORT_NAME),
		'id' => 'youtube',
		'type' => 'text',
		'std' => '',
		'desc' => _x('Enter your YouTube URL e.g http://youtube.com/', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	array(
		'type' => 'close'
	),
	
	/* Contact Form */
	array(
		'type' => 'open',
		'tab_name' => _x('Contact Form', 'r-panel', SHORT_NAME),
		'tab_id' => 'contact-form'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Contact Form', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-contact'
	),
	array(
		'name' => _x('Default e-mail address', 'r-panel', SHORT_NAME),
		'id' => 'email_address',
		'type' => 'text',
		'std' => 'youremail@domain.com',
		'desc' => _x('Enter your default e-mail address.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Anty spam question', 'r-panel', SHORT_NAME),
		'id' => 'question',
		'type' => 'text',
		'std' => '*2+3:',
		'desc' => _x('Enter your anty spam question.', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Anty spam answer', 'r-panel', SHORT_NAME),
		'id' => 'answer',
		'type' => 'text',
		'std' => '5',
		'desc' => _x('Enter your anty spam answer.', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	array(
		'type' => 'close'
	),
	
	/* Custom Sidebars */
	array(
		'type' => 'open',
		'tab_name' => _x('Sidebars', 'r-panel', SHORT_NAME),
		'tab_id' => 'sidebars'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Sidebars', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-sidebars'
	),
	array(
		'name' => _x('Sidebars', 'r-panel', SHORT_NAME),
		'sortable' => false,
		'array_name' => 'custom_sidebars',
		'id' => array(
					  array('name' => 'name', 'id' => 'sidebar', 'label' => 'Name:')
					  ),
		'type' => 'dynamic_list',
		'desc' => _x('Add your custom sideabrs.', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	array(
		'type' => 'close'
	),
	
	/* System Information */
	array(
		'type' => 'open',
		'tab_name' => _x('System Information', 'r-panel', SHORT_NAME),
		'tab_id' => 'sys-info'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('System Information', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-sys-info'
	),
	array(
		'name' => 'System Information',
		'type' => 'sys_info',
	),
	array(
		'type' => 'sub_close'
	),
	array(
		'type' => 'close'
	),
	
	/* Advanced */
	array(
		'type' => 'open',
		'tab_name' => _x('Advanced', 'r-panel', SHORT_NAME),
		'tab_id' => 'advanced'
	),
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Advanced', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-advanced'
	),
	array(
		'name' => _x('cURL', 'r-panel', SHORT_NAME),
		'id' => 'curl',
		'type' => 'switch_button',
		'std' => 'off',
		'desc' => _x('Enable this option if the images do not appear correctly. Important - your server must have the cURL extension. You can check this in the "System Information".', 'r-panel', SHORT_NAME)
	),
	array(
		'name' => _x('Demo Content', 'r-panel', SHORT_NAME),
		'id' => 'demo_content',
		'type' => 'switch_button',
		'std' => 'on',
		'desc' => _x("Disable this option when your template is ready and you will not need demo content (images, audio). This can speed up the template.", 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Admin Panel */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Admin Panel', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-admin-panel'
	),
	array(
		'name' => _x('Admin Logo', 'r-panel', SHORT_NAME),
		'id' => 'admin_logo',
		'type' => 'upload',
		'img_w' => '200',
		'img_h' => '144',
		'std' => '',
		'button_title' => _x('Add Image', 'r-panel', SHORT_NAME),
		'desc' => _x('Upload a logo for your admin panel (200x144 px), or specify the image address (http://yoursite.com/your_image.jpg).', 'r-panel', SHORT_NAME)
	),
	array(
		'type' => 'sub_close'
	),
	
	/* Import Export */
	array(
		'type' => 'sub_open',
		'sub_tab_name' => _x('Import/Export', 'r-panel', SHORT_NAME),
		'sub_tab_id' => 'sub-import'
	),
	array(
		'type' => 'export'
	),
	array(
		'type' => 'import'
	),
	array(
		'type' => 'close'
	)
	
);

/* Add class instance */
$r_panel = new r_panel($args, $options);
	
/* Remove variables */
unset($args, $options);

?>