function init() {	
	tinyMCEPopup.resizeToInnerSize();
}

function insert_shortcode() {
	
	/* Variables */
	var output;
	var shortcode_panel = document.getElementById('shortcodes_panel');
	var image_panel = document.getElementById('image_panel');
	
	/* Custom Image */
	if (image_panel.className.indexOf('current') != -1) {
		var image_url = document.getElementById('image_url').value,
      		image_title = document.getElementById('image_title').value,
		    image_width = document.getElementById('image_width').value,
		    image_height = document.getElementById('image_height').value,
			image_link = document.getElementById('image_link').value,
			image_crop = document.getElementById('image_crop').value,
			image_pos = document.getElementById('image_pos').value,
			lightbox_group = document.getElementById('lightbox_group').value,
			hover = '0',
			target = '0',
			lightbox = '';
			
			if (document.shortcodes.lightbox.checked == true) lightbox = 'lightbox';
			if (document.shortcodes.target.checked == true) target = '1';
			if (document.shortcodes.hover.checked == true) hover = '1';

		output = '[custom_image src="'+image_url+'" width="'+image_width+'" height="'+image_height+'" crop="'+image_crop+'" title="'+image_title+'" link="'+image_link+'" lightbox="'+lightbox+'" group="'+lightbox_group+'" hover="'+hover+'" target="'+target+'" image_alignment="'+image_pos+'"]';
	}
	
	/* Shortcodes */
 	if (shortcode_panel.className.indexOf('current') != -1) {
		var shortcode = document.getElementById('shortcode').value;
		if (shortcode == 0 ){
			tinyMCEPopup.close();
		}
		/* Columns */
		/* Two columns */
		else if (shortcode == '2_columns'){
			output = '[1_2]<br><br>Insert your text here<br><br>[/1_2]<br><br>[1_2_last]<br><br>Insert your text here<br><br>[/1_2_last]';
		}
		/* Three columns */
		else if (shortcode == '3_columns'){
			output = '[1_3]<br><br>Insert your text here<br><br>[/1_3]<br><br>[1_3]<br><br>Insert your text here<br><br>[/1_3]<br><br>[1_3_last]<br><br>Insert your text here<br><br>[/1_3_last]';
		}
		/* Four columns */
		else if (shortcode == '4_columns'){
			output = '[1_4]<br><br>Insert your text here<br><br>[/1_4]<br><br>[1_4]<br><br>Insert your text here<br><br>[/1_4]<br><br>[1_4]<br><br>Insert your text here<br><br>[/1_4]<br><br>[1_4_last]<br><br>Insert your text here<br><br>[/1_4_last]';
		}
		/* 2/3 Column + 1/3 Column */
		else if (shortcode == '2_3_1_3_columns'){
			output = '[2_3]<br><br>Insert your text here<br><br>[/2_3]<br><br>[1_3_last]<br><br>Insert your text here<br><br>[/1_3_last]';
		}
		/* 1/3 Column + 2/3 Column */
		else if (shortcode == '1_3_2_3_columns'){
			output = '[1_3]<br><br>Insert your text here<br><br>[/1_3]<br><br>[2_3_last]<br><br>Insert your text here<br><br>[/2_3_last]';
		}
		/* 3/4 Column + 1/4 Column */
		else if (shortcode == '3_4_1_4_columns'){
			output = '[3_4]<br><br>Insert your text here<br><br>[/3_4]<br><br>[1_4_last]<br><br>Insert your text here<br><br>[/1_4_last]';
		}
		/* 1/4 Column + 3/4 Column */
		else if (shortcode == '1_4_3_4_columns'){
			output = '[1_4]<br><br>Insert your text here<br><br>[/1_4]<br><br>[3_4_last]<br><br>Insert your text here<br><br>[/3_4_last]';
		}
		
		/* Music */
		/* Flash player */
		else if (shortcode == 'player'){
			output = '[player url=""]';
		}
		/* HTML5 player */
		else if (shortcode == 'html5_player'){
			output = '[html5_player url="" title=""]';
		}
		/* Playlist */
		else if (shortcode == 'playlist'){
			output = '[playlist]<br><br>[playlist_track url="" title="Demo Track 01"]<br>[playlist_track url="" title="Demo Track 02"]<br>[playlist_track url="" title="Demo Track 03"]<br><br>[/playlist]';
		}
		/* Soundcloud */
		else if (shortcode == 'soundcloud'){
			output = '[soundcloud url="" width="600" height="81" params=""]';
		}
				
		/* Videos */
		/* YouTube */
		else if (shortcode == 'youtube'){
			output = '[youtube id="zfsqVKK5mrw" width="624" height="360"]';
		}
		/* Vimeo */
		else if (shortcode == 'vimeo'){
			output = '[vimeo id="1084537" width="624" height="360"]';
		}
		
		/* Images */
		/* Image */
		else if (shortcode == 'easy_image'){
			output = '[easy_image src="#" size="s" title="Image Title" group="" link="" crop="c"]';
		}
		
		/* Buttons */
		/* Button */
		else if (shortcode == 'button'){
			output = '[button title="Button" link="#"]';
		}
		/* Button Blue */
		else if (shortcode == 'button_blue'){
			output = '[button_blue title="Button Blue" link="#"]';
		}
		/* Button Orange */
		else if (shortcode == 'button_orange'){
			output = '[button_orange title="Button Orange" link="#"]';
		}
		/* Button Green */
		else if (shortcode == 'button_green'){
			output = '[button_green title="Button Green" link="#"]';
		}
		/* Button Download */
		else if (shortcode == 'button_download'){
			output = '[button_download title="Button Download" link="#"]';
		}
		
		/* Messages */
		/* Message */
		else if (shortcode == 'message'){
			output = '[message]<br><br>Insert your text here<br><br>[/message]';
		}
		/* Message */
		else if (shortcode == 'message_update'){
			output = '[message_update]<br><br>Insert your text here<br><br>[/message_update]';
		}
		
		/* Lists */
		/* Check list */
		else if (shortcode == 'check_list'){
			output = '[check_list]<br><ul><li>List Item 1</li><li>List Item 2</li><li>List Item 3</li></ul><br>[/check_list]';
		}
		/* Events list */
		else if (shortcode == 'events_list'){
			output = '[events_list display_limit="3"]';
		}
		/* Posts list */
		else if (shortcode == 'posts_list'){
			output = '[posts_list display_limit="3" cat="" limit="-1"]';
		}
		
		/* Contact Forms */
		/* Contact form */
		else if (shortcode == 'contact_form'){
			output = '[contact_form email="youremail@domain.com"]';
		}
		
		/* Misc Stuff */
		/* Divider */
		else if (shortcode == 'divider'){
			output = '[divider]';
		}
		/* Divider with top button */
		else if (shortcode == 'divider_top'){
			output = '[divider_top]';
		}
		/* Last Tweet */
		else if (shortcode == 'last_tweet'){
			output = '[last_tweet username=""]';
		}
		/* Highlight */
		else if (shortcode == 'highlight'){
			output = '[highlight color="#000" background_color = "#fff"]...Insert your text here...[/highlight]';
		}
		/* Box */
		else if (shortcode == 'box'){
			output = '[box]<br><br>Insert your text here<br><br>[/box]';
		}
	
	}
  		
	if (window.tinyMCE) {
		var inst = tinyMCE.activeEditor.id;
		window.tinyMCE.execInstanceCommand(inst, 'mceInsertContent', false, output);
	  	tinyMCEPopup.editor.execCommand('mceRepaint');
	  	tinyMCEPopup.close();
  	}
  	return;
}