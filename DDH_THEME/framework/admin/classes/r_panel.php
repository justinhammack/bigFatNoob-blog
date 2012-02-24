<?php

/* Rascals R-Panel Class
 ------------------------------------------------------------------------*/
class r_panel {
	
	var $args;
	var $options;
	var $saved_options;
	var $json;
	var $pagenow;
	var $cufon_fonts;
	
	/* Constructor */
	function r_panel($args, $options) {
		global $pagenow;
	
		
		/* Set options and page variables */
		$this->args = $args;
		$this->options = $options;
		$this->pagenow = $pagenow;
		
		/* Json */
		$this->json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		
		/* Call method to create the sidebar menu items */
		add_action('admin_menu', array(&$this, 'add_admin_menu'));
		add_action('wp_ajax_r_panel_save', array(&$this, 'r_panel_save'));
		
		
		if (isset($_REQUEST['page'])) {
			if ($this->pagenow == 'admin.php' && $_REQUEST['page'] == basename(__FILE__)) {		
				add_action('admin_enqueue_scripts', array(&$this, 'r_panel_scripts'));
				add_action('admin_head', array(&$this, 'r_panel_admin_head'));
			}
		}
	}
		
	/* Create the sidebar menu */
	function add_admin_menu() {	
	
		/* Theme Menu */
		add_menu_page(THEME_NAME, THEME_NAME, 'manage_options', basename(__FILE__), array(&$this, 'init'), ADMIN_URI . '/images/r-panel/icon-config.png', 99);
		define('TOP_LEVEL', basename(__FILE__));
	
		add_submenu_page(TOP_LEVEL, THEME_NAME, $this->args['menu_name'], 'manage_options', basename(__FILE__), array(&$this, 'init'));
	}
	
	/* Admin scripts */
	function r_panel_scripts() {
		wp_enqueue_style('r_panel', ADMIN_URI . '/css/r-panel.css', false, '1.0.0', 'screen');
	    wp_enqueue_style('thickbox');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('r_panel_cufon_yui', ADMIN_URI . '/scripts/cufon-yui.js', array('jquery'), '1.0.9');
		/* Fonts */
		$i = 0;
		if (is_dir(THEME . "/styles/cufon_fonts/")) {
			if ($open_dir = opendir(THEME . "/styles/cufon_fonts/")) {
				while (($font = readdir($open_dir)) !== false) {
					if (stristr($font, ".js") !== false) {
						$font_content = file_get_contents(THEME . "/styles/cufon_fonts/" . $font);
						if (preg_match('/font-family":"(.*?)"/i', $font_content, $match)){
						    wp_enqueue_script('r_panel_cufon_font'.$i, THEME_URI . '/styles/cufon_fonts/' . $font , array('jquery'), '1.0.9');
				            $this->cufon_fonts[$i]['name'] = $match[1];
							$this->cufon_fonts[$i]['file_name'] = $font;
							$this->cufon_fonts[$i]['file_path'] = THEME_URI . '/styles/cufon_fonts/' . $font;
			            }
						$i++;
					}
				}
			}
		}
		
		wp_enqueue_script('r_panel', ADMIN_URI . '/scripts/jquery.r_panel_ajax.js', array('jquery'), '3.0.0');
    }

    function r_panel_admin_head() {
	
		/* Fonts */
		$i = 0;
		$fonts_script = "<script type='text/javascript'>\njQuery(document).ready(function($) {\n";
																						
		foreach($this->cufon_fonts as $font) {
	        $fonts_script .= stripslashes("Cufon.replace('#cufon-font-$i', { fontFamily: '" . $font['name'] . "' });\n");
		    $i++;
		}
		echo $fonts_script . "});\n</script>";
		
    }

	
	/* Initialize */
	function init() {
		if (isset($_REQUEST['page'])) {
			if ($this->pagenow == 'admin.php' && $_REQUEST['page'] == basename(__FILE__)) {
		        $this->display();
			}
		}
	}
	
	/* Save Options */
	function r_panel_save() {
	    $data = $_POST['data'];
		
		$new_options = array();
		
		if (isset($data['save_options'])) {
			
			if (isset($data['import']) && $data['import'] != '') {
				
				/* Import items */
				$data = stripslashes($data['import']);
				$new_options = $this->json->decode($data);
				update_option($this->args['option_name'], $new_options);
				
			} else {
				foreach ($this->options as $option) {
					if (isset($option['id']) && is_array($option['id'])) {
						$items_count = count($data[$option['array_name'].'_hidden']);
						for ($items = $items_count; $items >= 0; $items--) {
	
							foreach ($option['id'] as $item => $option_id) {
								
								if (isset($data[$option_id['id']][$items]) && $data[$option_id['id']][$items] !='') {
									$data_items = $data[$option_id['id']][$items];
									$new_options[$option['array_name']][$items][$option_id['name']] = $data_items;
								}
							}
							
						}
	
					} else {
						if (isset($option['id']) && isset($data[$option['id']])) {
							if ($data[$option['id']] != '')
							  $new_options[$option['id']] = stripslashes($data[$option['id']]);
							else if (isset($option['std']) && $option['std'] != '')
							   $new_options[$option['id']] = stripslashes($option['std']);
						}
					}
				}
			
			   update_option($this->args['option_name'], $new_options);
			}
			
			$this->saved_options = $new_options;
			//print_r($this->saved_options);
			$response = $this->json->encode($this->saved_options);
			echo $response;
		}
		die();
	}
	
	
	function display() {
		
		$this->saved_options = get_option($this->args['option_name']);
		
		/* Sidebar */
		echo '<div id="r-sidebar">';
		echo '<div id="r-logo">';
		if (isset($this->saved_options['admin_logo']) && r_image_exists($this->saved_options['admin_logo'])) {
		    echo '<img src="' . r_image_resize('200', '144', $this->saved_options['admin_logo']) . '" alt="" />';
        } else { 
		    echo '<img src="' . ADMIN_URI . '/images/r-panel/logo.png" />';
		}
		echo '</div>';
  
		echo '<ul id="r-menu">';
		
		/* Display menu */
    	foreach($this->options as $option) {
			if ($option['type'] == 'open' && isset($option['tab_name'])) {
				echo '<li><a rel="' . $option['tab_id'] . '" href="#nav">' . $option['tab_name'] . '</a></li>' . "\n";
			}
		}
        echo '</ul>';
		echo '<span id="r-save">' . _x('Save Settings', 'r-panel', SHORT_NAME) . '</span>';
		echo '</div>';
		
    	/* Panel */
		echo '<div id="r-panel">';
		echo '<div id="r-messages">';
		
		/* Ajax notyfication */
		echo '<div class="r-notyfication r-ajax-messages"><span class="r-ajax-sending">' . _x('Please wait...', 'r-panel', SHORT_NAME) . '</span><span class="r-ajax-success">' . THEME_NAME . ' ' . _x('settings saved', 'r-panel', SHORT_NAME) . '</span></div>';
        if (isset($_POST['save_options'])) echo '<div class="r-notyfication r-saved-settings">' . THEME_NAME . ' ' . _x('settings saved', 'r-panel', SHORT_NAME) . '</div>';  
    	echo '</div>';
    	echo '<form method="post" id="r_panel_form" action="#">';
		
		/* Display */
      	foreach ($this->options as $option) {	
			if (method_exists($this, $option['type'])) {
				$this->$option['type']($option);
			}
		}
		
		echo '<input type="hidden" name="save_options" value="1"/>';
		echo '</form>';
		echo '</div>';
	}
	
	
	/* Helper Fumctions
	 ------------------------------------------------------------------------*/
	
	/* Open */
	function open($value) {
		echo '<div class="r-tab" id="' . $value['tab_id'] . '">';
		
		/* Bulid sub menu */
		echo '<ul class="r-sub-menu">';
		
		/* Display menu */
		$flag = false;
    	foreach($this->options as $option) {
			if (isset($option['tab_id'])) {
				if ($option['tab_id'] == $value['tab_id']) $flag = true;
			}
			if ($flag) {
				if ($option['type'] == 'sub_open' && isset($option['sub_tab_name'])) {
				echo '<li><a rel="' . $option['sub_tab_id'] . '" href="#nav">' . $option['sub_tab_name'] . '</a></li>' . "\n";
				}
				if ($option['type'] == 'close') {
				  $flag = false;
				  break;
				}
			
			}
		}
        echo '</ul>';
	}
	
	/* Close */
	function close($value) {
		echo '</div>';
	}
	
	/* Sub open */
	function sub_open($value) {
		echo '<div class="r-sub-tab" id="' . $value['sub_tab_id'] . '">';
	}
	
	/* Sub close */
	function sub_close($value) {
		echo '</div>';
	}
	
	/* Cufon Fonts */	
	function cufon_fonts($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		
        echo '<ul id="cufon-list">';
		
		$i = 0;
		$saved_fonts = explode('|', $value['std']);
		
		echo '<div class="hidden" id="cufon-id">' . $value['id'] . '</div>';
		//print_r($saved_fonts);
		
		foreach($this->cufon_fonts as $font) {
			
			if ($i % 2 == 0) $classes = 'odd';
			else $classes = '';
			
			/* Get saved fonts */
			if (is_array($saved_fonts)) {
				foreach($saved_fonts as $save_font) {
					if ($save_font == $font['file_name']) {
						$classes .= ' selected';
					}
				}
			}
			
			echo '<li class="'. $classes .'">';
			echo '<h3 id="cufon-font-' . $i . '">' . $font['name'] . '</h3>';
			echo '<div class="hidden cufon-file-name">' . $font['file_name'] . '</div>';
			echo '<div class="hidden cufon-font-name">' . $font['name'] . '</div>';
			echo '</li>';
			$i++;
		}
		echo '</ul>';
		echo '<input id="cufon-fonts" type="hidden" name="' . $value['id'] . '" value="' . $value['std'] . '" />';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Cufon code */
	function cufon_code($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<textarea id="cufon-code" name="' . $value['id'] . '" style="height:' . $value['height'] . 'px" cols="" rows="" class="r-textarea">' . $value['std'] . '</textarea>';
		echo '<div id="cufon-tags"></div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Range */	
	function range($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="range-wrap">';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="range" value="' . $value['std'] . '"';
		if (isset($value['min'])) {
			echo ' min="' . $value['min'] . '"';
		}
		if (isset($value['max'])) {
			echo ' max="' . $value['max'] . '"';
		}
		if (isset($value['step'])) {
			echo ' step="' . $value['step'] . '"';
		}
		echo '/>';
		if (isset($value['unit']) && $value['unit']) {
			echo '<span class="range-unit">' . $value['unit'] . '</span>';
		}
		echo '</div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Text */	
	function text($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="r-input-wrap">';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . htmlspecialchars($value['std']) . '" class="r-input"/>';
		echo '</div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Easy Link */	
	function easy_link($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="r-input-wrap">';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . htmlspecialchars($value['std']) . '" class="r-input r-link-input" style="width:480px;"/>';
		echo '</div>';
		echo '<a href="#" class="r-button r-easy-link">' . __('Insert Link', SHORT_NAME) . '</a>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Dynamic list */	
	function dynamic_list($value) {	
		
		echo '<div class="r-type">';
		
		//echo '<pre>';
		//print_r($this->saved_options[$value['array_name']]);
		//echo '</pre>';
			
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="clear"></div>';
		
		/* Hidden items */
		echo '<div class="r-new-item" style="display:none">';
		echo '<ul>';
		echo '<li>';
		echo '<span class="r-delete-item"></span>';
		echo '<span class="r-drag-item"></span>';
		echo '<div class="content">';
		echo '<input type="hidden" value="" name="' . $value['array_name'] . '_hidden[]"/>';
		foreach ($value['id'] as $count => $item) {
		    echo '<label for="' . $item['id'] . '[]">' . $item['label'] . '</label>';
			echo '<input type="text" class="r-input" value="" name="' . $item['id'] . '[]"/>';
		}
		echo '</div>';
		echo '</li>';
		echo '</ul>';
		echo '</div>';
		
		if ($value['sortable'] == true) $sort = 'r-sortable';
		else $sort = '';
		if (isset($this->saved_options[$value['array_name']]) && is_array($this->saved_options[$value['array_name']]))
		  $list_class = $value['array_name'];
		else 
		  $list_class = '';
		echo '<ul class="dynamic-list ' . $list_class . ' ' . $sort .'">';
			
		if (isset($this->saved_options[$value['array_name']]) && is_array($this->saved_options[$value['array_name']])) {
			foreach ($this->saved_options[$value['array_name']] as $items) {
				echo '<li>';
				echo '<span class="r-delete-item"></span>';
				echo '<span class="r-drag-item"></span>';
				echo '<div class="content">';
				echo '<input type="hidden" value="" name="' . $value['array_name'] . '_hidden[]"/>';
				foreach ($value['id'] as $count => $item) {
					echo '<label for="' . $item['id'] . '[]">' . $item['label'] . '</label>';
					if (isset($items[$item['name']]))
					echo '<input type="text" class="r-input" value="' . $items[$item['name']] . '" name="' . $item['id'] . '[]"/>';
					else 
					echo '<input type="text" class="r-input" value="" name="' . $item['id'] . '[]"/>';
				}
				echo '</div>';
				echo '</li>';
			}
		}
		echo '</ul>';
        echo '<div class="clear"></div>';
		echo '<a href="#" class="r-button r-add r-add-new-item">' . _x('Add New Item', 'r-panel', SHORT_NAME) . '</a>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Switch Button */
	function switch_button($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3 style="width:546px;">' . $value['name'] . '</h3>';
		echo '<span class="r-switch r-btn-switch"></span>';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="hidden" value="' . $value['std'] . '" class="r-input-switch"/>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Textarea */
	function textarea($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		if (isset($value['tinymce']) && $value['tinymce'] == 'true') {
			echo '<div class="r-input-wrap r-tiny-editor" style="padding:0;border:none" data-id="'.$value['id'].'">';
			wp_editor($value['std'], $value['id'], $settings = array());
		    echo '</div>';
		} else {
			echo '<div class="r-input-wrap">';
			echo '<textarea name="' . $value['id'] . '" style="height:' . $value['height'] . 'px" cols="" rows="" class="r-textarea">' . $value['std'] . '</textarea>';
			echo '</div>';
			
		}
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Color */
	function color($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="r-input-wrap">';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . $value['std'] . '" class="r-input color-picker"/>';
		echo '</div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Select */
	function select($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-select">';
		
		foreach($value['options'] as $option) {
			if ($this->saved_options[$value['id']] == $option['value']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value='" . $option['value'] . "'>" . $option['name'] . "</option>";
		}
		
		echo '</select>';
		echo '<div class="clear"></div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Category */	
	function categories($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-select">';
		
		foreach($value['options'] as $option) {
			if ($value['std'] == $option['name']) $selected = 'selected="selected"';
			echo '<option ' . $selected . ' value="' . $option['value'] . '">' . $option['name'] . '</option>';
		}
		
		foreach((get_categories()) as $category) {
			
			if ($category->term_id == $value['std']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value=\"" . $category->term_id . "\">" . $category->name . "</option>" . "\n";
		}
		
		echo '</select>';
		echo '<div class="clear"></div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Taxonomy */		
	function taxonomy($value) {
		
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-select">';
		
		foreach($value['options'] as $option) {
			if ($value['std'] == $option['name']) $selected = 'selected="selected"';
			echo '<option ' . $selected . ' value="' . $option['value'] . '">' . $option['name'] . '</option>';
		}	
		
		$args = array(
					  'hide_empty' => false
		);
		
		$taxonomies = get_terms($value['taxonomy'], $args);
		
		foreach($taxonomies as $taxonomy) {
			
			if ($taxonomy->name == $value['std']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value=\"" . $taxonomy->name . "\">" . $taxonomy->name . "</option>" . "\n";
		}
		
		echo '</select>';
		echo '<div class="clear"></div>';
		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Upload */
	function upload($value) {	
	
		if (isset($this->saved_options[$value['id']])) $value['std'] = $this->saved_options[$value['id']];
		
		if (isset($value['button_title']) && $value['button_title']) $button_title = $value['button_title'];
		else $button_title = __('Add File', SHORT_NAME);
			
		echo '<div class="r-type">';
		echo '<h3>' . $value['name'] . '</h3>';
		echo '<div class="r-input-wrap">';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" value="' . $value['std'] . '" class="r-input r-input-image" style="width:480px;" /></div>';	
		echo '<a href="#upload-file" class="r-upload r-button" rel="image">' . $button_title . '</a>';
		echo '<img src="' . site_url() . '/wp-admin/images/loading.gif" class="r-ajax-loading"/>';
		echo '<div class="r-image">';
		
		/* Loaded image */
		$hidden_class = 'r-hidden';
		if (isset($this->saved_options[$value['id']])) {
			if (r_image_exists($this->saved_options[$value['id']])) {
				echo '<img src="' . r_image_resize($value['img_w'], $value['img_h'], $this->saved_options[$value['id']]) . '" alt="' . $this->saved_options[$value['id']] . '" class="r-preview" />';
			} else $hidden_class = '';
		}
		
		echo '</div>';
		echo '<span class="r-ajax-warning ' . $hidden_class  . '">' . __('The link is incorrect or the image does not exist.', SHORT_NAME) . '</span>';
		echo '<div class="hidden"><span class="r-set-crop">c</span><span class="r-set-width">' .$value['img_w'] . '</span><span class="r-set-height">' . $value['img_h'] . '</span></div>';
		
		

		echo '<div class="help-box">';
		echo '<p>' . $value['desc'] . '</p>';
		echo '</div>';
		echo '</div>';
	}
	
	/* System Info */	
	function sys_info($value) {	
			
		/* Premissions */
		echo '<div class="r-type">';
		echo '<h3>' . _x('Permissions', 'r-panel', SHORT_NAME) . '</h3>';
		echo '<table cellspacing="0" border="0" class="r-table">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th>' . _x('File/Folder', 'r-panel', SHORT_NAME) . '</th>';
		echo '<th>' . _x('Permission', 'r-panel', SHORT_NAME) . '</th>';
		echo '<th>' . _x('Recommended Permission', 'r-panel', SHORT_NAME) . '</th>';
		echo '</tr>';
		$file = file_perms(THEME . '/thumb.php');
		echo '<tr>';
		echo '<td>/thumb.php</td>';
		echo '<td>' . $file . '</td>';
		echo '<td>755</td>';
		echo '</tr>';
		$file = file_perms(THEME . '/cache/', true);
		echo '<tr>';
		echo '<td>/cache/</td>';
		echo '<td>' . $file . '</td>';
		echo '<td>777 or 755</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		
		/* PHP */
		echo '<div class="r-type">';
		echo '<h3>' . _x('PHP', 'r-panel', SHORT_NAME) . '</h3>';
		echo '<table cellspacing="0" border="0" class="r-table">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th>' . _x('Option', 'r-panel', SHORT_NAME) . '</th>';
		echo '<th>' . _x('Value', 'r-panel', SHORT_NAME) . '</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>' . _x('Current PHP version', 'r-panel', SHORT_NAME) . '</td>';
		echo '<td>' . phpversion() . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>allow_url_fopen</td>';
		if (function_exists('allow_url_fopen')) $status = '<strong>' . _x('ON', 'r-panel', SHORT_NAME) . '</strong>';
		else $status = _x('OFF', 'r-panel', SHORT_NAME);
		echo '<td>' . $status . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>php_curl</td>';
		if (function_exists('curl_init')) $status = '<strong>' . _x('ON', 'r-panel', SHORT_NAME) . '</strong>';
		else $status = _x('OFF', 'r-panel', SHORT_NAME);
		echo '<td>' . $status . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>getimagesize()</td>';
		$src = ADMIN_URI . '/images/r-panel/icon-config.png';
		$image = r_image_exists($src);
		if ($image) $status = '<strong>' . _x('ON', 'r-panel', SHORT_NAME) . '</strong>';
		else $status = _x('OFF', 'r-panel', SHORT_NAME);
		echo '<td>' . $status . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>GD Libary</td>';
		if (extension_loaded('gd') && function_exists('gd_info')) $status = '<strong>' . _x('ON', 'r-panel', SHORT_NAME) . '</strong>';
		else $status = _x('OFF', 'r-panel', SHORT_NAME);
		echo '<td>' . $status . '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
		
		/* Theme */
		echo '<div class="r-type">';
		echo '<h3>' . _x('Data', 'r-panel', SHORT_NAME) . '</h3>';
		echo '<table cellspacing="0" border="0" class="r-table">';
		echo '<tbody>';
		echo '<tr>';
		echo '<th>' . _x('Option', 'r-panel', SHORT_NAME) . '</th>';
		echo '<th>' . _x('Value', 'r-panel', SHORT_NAME) . '</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>' . _x('Theme name', 'r-panel', SHORT_NAME) . '</td>';
		echo '<td>' . THEME_NAME . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>' . _x('Theme version', 'r-panel', SHORT_NAME) . '</td>';
		echo '<td>' . THEME_VERSION . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>' . _x('R-Panel version', 'r-panel', SHORT_NAME) . '</td>';
		echo '<td>' . RPANEL_VERSION . '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td>' . _x('Framework', 'r-panel', SHORT_NAME) . '</td>';
		echo '<td>' . FRAMEWORK . '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div>';
	}
	
	/* Advanced */
	function export($value) {	
		
		if ($this->saved_options != false && count($this->saved_options) > 0) {
			$export = $this->json->encode($this->saved_options);
			//$export = str_replace("\n", " ", trim($export));
		} else $export = '';
	    
		echo '<div class="r-type">';
		echo '<h3>' . _x('Export Data', 'r-panel', SHORT_NAME) . '</h3>';
		echo '<div class="r-input-wrap">';
		echo '<textarea name="export" style="height:200px;overflow:auto" cols="" rows="" class="r-input r-textarea">' . $export . '</textarea>';
		echo '</div>';
		echo '</div>';
	}
	
	/* Import */
	function import($value) {	
		
		echo '<div class="r-type">';
		echo '<div id="r-data-import-wrap" style="display:none">';
		echo '<h3>' . _x('Import Data', 'r-panel', SHORT_NAME) . '</h3>';
		echo '<div class="r-input-wrap" style="margin-bottom:20px">';
		echo '</div>';
		echo '<div class="help-box">';
		echo '<p>' . _x('Paste in the box above previously exported data, and press the save button.', 'r-panel', SHORT_NAME) . '</p>';
		echo '</div>';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<a href="#" class="r-button r-data-import">' . _x('Import data', 'r-panel', SHORT_NAME) . '</a>';
		echo '</div>';		
	}
}
?>