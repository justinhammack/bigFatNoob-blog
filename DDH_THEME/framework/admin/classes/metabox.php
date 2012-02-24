<?php

/* Rascals Meta Box Class
 ------------------------------------------------------------------------*/
class r_meta_box {

	var $options;
	var $box;

	function r_meta_box($options, $box) {
		
		/* Set options */
		$this->options = $options;
		$this->box = $box;
		
		add_action('admin_menu', array(&$this, 'init'));
		add_action('save_post', array(&$this, 'save_postdata'));
		
	}
	
	 
	/* Initialize */
	function init() {	
		$this->create_meta_box();
	}

	function create_meta_box() {
		
		if (function_exists('add_meta_box') && is_array($this->box['template'])) {
			
			foreach ($this->box['template'] as $template) {
				if (isset($_GET['post'])) $template_name = get_post_meta($_GET['post'], '_wp_page_template', true);
		        else $template_name = '';
			
				if ($template == 'default' && $template_name == '') $template_name = 'default';
				else if ($template == 'post') $template = '';
				
				if ($template == $template_name) {
					if (is_array($this->box['page'])) {
						foreach ($this->box['page'] as $area) {	
							if ($this->box['callback'] == '') $this->box['callback'] = 'display_meta_box';
							
							add_meta_box ( 	
								$this->box['id'], 
								$this->box['title'],
								array(&$this, $this->box['callback']),
								$area, $this->box['context'], 
								$this->box['priority']
							);  
						}
					}  
				}
			}
		}
	}  
	
	function display_meta_box() {	
	
		global $post;
        $count = 1;
		$css_class = '';
		$array_size = count($this->options);

		foreach ($this->options as $option) {
			if (method_exists($this, $option['type'])) {
				
				if (is_array($option['id'])) {
					foreach ($option['id'] as $i => $option_id) {
						$meta_box_value = get_post_meta($post->ID, $option_id['id'], true);
						if (isset($meta_box_value) && $meta_box_value != '') $option['id'][$i]['std'] = $meta_box_value;
						if (!isset($option_id['std'])) $option['id'][$i]['std'] = '';
					}
					//echo '<pre>';		
					//print_r($option['id']);
					//echo '</pre>';
					
			    } else {
					$meta_box_value = get_post_meta($post->ID, $option['id'], true);
					if (isset($meta_box_value) && $meta_box_value != '') $option['std'] = $meta_box_value;
					if (!isset($option['std'])) $option['std'] = '';
			    }
				if ($array_size == 1) $css_class = 'r-meta-first-child r-meta-last-child';
				else if ($count == 1) $css_class = 'r-meta-first-child';
				else if ($count == $array_size) $css_class = 'r-meta-last-child';
				else $css_class = '';
				
				echo '<div class="r-meta ' . $css_class . '">';
				$this->$option['type']($option);
				echo '</div>';
				
				$count++;
			}
		}
		
		/* Security field */
		echo'<input type="hidden" name="' . $this->box['id'] . '_noncename" id="' . $this->box['id'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__) ) . '" />';  

	}
	
	function save_postdata()  {
		
		if (isset($_POST['post_ID'])) {
			
			$post_id = $_POST['post_ID'];
			
			foreach ($this->options as $option) {
				
				/* Verify */
				if (isset($_POST[$this->box['id'] . '_noncename']) && !wp_verify_nonce($_POST[$this->box['id'] . '_noncename'], plugin_basename(__FILE__))) {	
					return $post_id;
				}
				
				if ('page' == $_POST['post_type']) {
					if (!current_user_can( 'edit_page', $post_id))
					return $post_id;
				} 
				else {
					if (!current_user_can( 'edit_post', $post_id))
					return $post_id;
				}
				
				if (is_array($option['id'])) {
					foreach ($option['id'] as $option_id) {
						if (isset($_POST[$option_id['id']])) {
						    $data = $_POST[$option_id['id']];
							if (get_post_meta($post_id , $option_id['id']) == '')
							add_post_meta($post_id , $option_id['id'], $data, true);
							
							elseif ($data != get_post_meta($post_id , $option_id['id'], true))
							update_post_meta($post_id , $option_id['id'], $data);
							
							elseif ($data == '')
							delete_post_meta($post_id , $option_id['id'], get_post_meta($post_id , $option_id['id'], true));
					    }
					}
				} else {
					if (isset($_POST[$option['id']])) {
						$data = $_POST[$option['id']];

						if (get_post_meta($post_id , $option['id']) == '')
						add_post_meta($post_id , $option['id'], $data, true);
						
						elseif ($data != get_post_meta($post_id , $option['id'], true))
						update_post_meta($post_id , $option['id'], $data);
						
						elseif ($data == '')
						delete_post_meta($post_id , $option['id'], get_post_meta($post_id , $option['id'], true));
					}
				}
			}
		}
	}
	
	
	/* Helper Fumctions
	 ------------------------------------------------------------------------*/
	
	/* Text */
	function text($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap"><input type="text" value="' . htmlspecialchars($value['std']) . '" id="' . $value['id'] . '" name="' . $value['id'] . '" class="r-meta-input"/></div>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}
	
	/* Range */
	function range($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
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
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}
	
	/* Easy Link */
	function easy_link($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap"><input type="text" value="' . $value['id'][0]['std'] . '" id="' . $value['id'][0]['id'] . '" name="' . $value['id'][0]['id'] . '" class="r-meta-input r-link-input"/></div>';
		echo '<a href="#" class="r-easy-link r-button-meta">' . __('Insert Link', SHORT_NAME) . '</a>';
		
		/* New window */
		
		echo '<div class="r-easy-link-target">';
		echo '<label>' . __('New window: ', SHORT_NAME) . '</label>';
		echo '<span class="r-switch r-btn-check"></span>';
		echo '<input name="' . $value['id'][1]['id'] . '" id="' . $value['id'][1]['id'] . '" type="hidden" value="' . $value['id'][1]['std'] . '" class="r-input-switch"/>';
		echo'</div>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}
	
	/* Link Generator */
	function link_generator($value) {
		global $post;
		
		$post_name = $post->post_name;
		$post_permalink = get_permalink($post->ID);
		$locations = get_registered_nav_menus();
		$menus = wp_get_nav_menus();
		$home_id = get_option('page_on_front');
		$post_link = '';
		$is_link = false;
		
		if (isset($locations) && count($locations) > 0 && isset($menus) && count($menus) > 0) {
			
			/* Create link */
			if ($value['link_type'] == 'hash') {
				if ($home_id == $post->ID) $post_link = home_url() . '/#home';
				else $post_link = home_url() . '/#' . $post_name;
			} else {
				$post_link = $post_permalink;
			}
			
			$global_link = '#HOME_URL/#' . $post_name;
			
			/* Check if this page is already on the menu. */
			foreach($menus as $menu) {
				$menu_items = wp_get_nav_menu_items($menu->term_id);
				$current_menu = $menu->name;
				foreach($menu_items as $menu_item) {
				    if ($menu_item->url == $post_link || $menu_item->url == $global_link) {
						echo '<p>' . __('This page already exists in:', SHORT_NAME) . ' <strong>' . $current_menu . '</strong></p>';
						$is_link = true;
						break;
					}
				}
			}
			
			if (!$is_link) { 
				echo '<div class="r-remove">';
				echo '<div class="r-meta r-meta-first-child"><label for="r_menu_' . $post_name . '" class="r-meta-label">' . __('Menu Name', SHORT_NAME) . '</label>';
				echo '<select name="r_menu_' . $post_name . '" id="r_menu_' . $post_name . '" class="r-meta-select r-menu-id">';
				foreach($menus as $menu) {
					echo '<option value="' . $menu->term_id . '">' . $menu->name . '</option>';
				}
				
				echo '</select></div>';
				echo '<div class="r-meta"><label for="r_menu_label_' . $post_name . '" class="r-meta-label">' . __('Menu Label', SHORT_NAME) . '</label>';
				echo '<div class="r-meta-input-wrap"><input type="text" value="' . $post->post_title . '" id="r_menu_label_' . $post_name . '" name="r_menu_label_' . $post_name . '" class="r-meta-input r-menu-label"/></div></div>';
				
				echo '<div class="r-meta r-meta-last-child"><label for="r_menu_link_' . $post_name . '" class="r-meta-label">' . __('Menu Link', SHORT_NAME) . '</label>';
					
				echo '<div class="r-meta-input-wrap"><input type="text" value="' . $post_link . '" id="r_menu_link_' . $post_name . '" name="r_menu_link_' . $post_name . '" class="r-meta-input r-menu-link" disabled="disabled" style="opacity:0.5"/></div>';
				 
				
				echo '<a href="#" class="r-add-menu-link r-button-meta" rel="image">' . __('Add Menu Item', SHORT_NAME) . '</a>';
				echo '<img src="' . site_url() . '/wp-admin/images/loading.gif" class="r-ajax-loading"/>';
				echo '</div></div>';
			}
		} else { 
	        echo '<p>' . __('Your theme does not have menus. If you want to add this page you need to create a new menu. Go to -> Appearance -> Menus', SHORT_NAME) . '</p>';
		}
		
		echo '<p class="r-ajax-warning r-hidden r-empty-fields">' . __('Error - The fields can not be empty. Fill in empty fields and try again.', SHORT_NAME) . '</p>';
		echo '<p class="r-meta-success r-hidden">' . __('Page has been added to the menu.', SHORT_NAME) . '</p>';
				
	}
	
	/* Date Range */
	function date_range($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap" style="float:left;width:78px;clear:none"><input type="text" value="' . $value['id'][0]['std'] . '" id="' . $value['id'][0]['id'] . '" name="' . $value['id'][0]['id'] . '" class="r-meta-input r-datepicker"/></div>';
		echo '<span style="line-height:30px;float:left;padding:0 4px">-</span>';
		echo '<div class="r-meta-input-wrap" style="float:left;width:78px;clear:none"><input type="text" value="' . $value['id'][1]['std'] . '" id="' . $value['id'][1]['id'] . '" name="' . $value['id'][1]['id'] . '" class="r-meta-input r-datepicker"/></div>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}
	
	/* Color */
	function color($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap"><input type="text" value="' . $value['std'] . '" id="' . $value['id'] . '" name="' . $value['id'] . '" class="r-meta-input color-picker"/></div>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}
	
	/* Textarea */	
	function textarea($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label r-meta-label-float">' . $value['label'] . '</label>';

		if (isset($value['tinymce']) && $value['tinymce'] == 'true') {
			echo '<div class="r-meta-tiny-editor">';
			wp_editor($value['std'], $value['id'], $settings = array());
			echo '</div>';
		} else {
			if(isset($value['height']) && $value['height'] != '') $textarea_height = 'style="height:' . $value['height'] . 'px"';
			echo '<div class="r-meta-input-wrap"><textarea cols="30" rows="2" id="' . $value['id'] . '" name="' . $value['id'] . '" class="r-meta-textarea" ' . $textarea_height . '>' . stripslashes($value['std']) . '</textarea></div>';
		}
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
		
	}

	/* Switch button */	
	function switch_button($value) {
		echo '<div class="r-meta-switch-wrap"><label class="r-meta-label-float">' . $value['label'] . '</label>';
		echo '<span class="r-switch r-btn-switch"></span>';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="hidden" value="' . $value['std'] . '" class="r-input-switch"/></div>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help" >' . $value['desc'] . '</p>';
	}
	
	/* Select */	
	function select($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-meta-select">';
		
		foreach($value['options'] as $option) {
			
			if ($value['std'] == $option['value']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value='" . $option['value'] . "'>" . $option['name'] . "</option>";
		}	
		
		echo '</select>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Select image */	
	function select_image($value) {
		
		$image_path = ADMIN_URI . '/images/icons/';
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<input name="' . $value['id'] . '" id="' . $value['id'] . '" type="hidden" value="' . $value['std'] . '" class="r-select-image-input"/>';
		echo '<ul class="r-select-image">';
		
		foreach($value['images'] as $image) {
			
			if ($value['std'] == $image['id']) $selected = '<span class="r-selected-image"></span>';
			else $selected = '';
			echo '<li><img src="' . $image_path . $image['image'] . '" alt="' . $image['id'] . '" />' . $selected . '</li>';
		}
		
		echo '</ul>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Categories */		
	function categories($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-meta-select">';
		
		foreach($value['options'] as $option) {
			if ($value['std'] == $option['name']) $selected = 'selected="selected"';
			echo '<option ' . $selected . ' value="' . $option['name'] . '">' . $option['label'] . '</option>';
		}
		
		foreach((get_categories()) as $category) {
			
			if ($category->term_id == $value['std']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value=\"" . $category->term_id . "\">" . $category->name . "</option>" . "\n";
		}
		
		echo '</select>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Taxonomy */		
	function taxonomy($value) {
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-meta-select">';
		
		foreach($value['options'] as $option) {
			if ($value['std'] == $option['name']) $selected = 'selected="selected"';
			echo '<option ' . $selected . ' value="' . $option['name'] . '">' . $option['label'] . '</option>';
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
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Upload Audio */	
	function upload_audio($value) {
		global $post;
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap"><input type="text" value="' . $value['std'] . '" id="' . $value['id'] . '" name="' . $value['id'] . '" class="r-meta-input"/></div>';
	    echo '<a href="#upload-file" class="r-upload r-button-meta" rel="audio">' . __('Add File', SHORT_NAME) . '</a>';
		if (isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Upload Image */	
	function upload_image($value) {
		global $post;
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<div class="r-meta-input-wrap"><input type="text" value="' . $value['id'][0]['std'] . '" id="' . $value['id'][0]['id'] . '" name="' . $value['id'][0]['id'] . '" class="r-meta-input r-input-image"/></div>';
	    echo '<a href="#upload-file" class="r-upload r-button-meta" rel="image">' . __('Add File', SHORT_NAME) . '</a>';
		echo '<img src="' . site_url() . '/wp-admin/images/loading.gif" class="r-ajax-loading"/>';
		echo '<div class="clear" style="padding:4px 0;"></div>';
		
		/* Crop */
		if (isset($value['id'][1]['id'])) {
	    echo '<img src="'. ADMIN_URI . '/images/r-panel/icon-crop.png" alt="" style="margin-right:4px;position:relative;top:3px;"/>';
		echo '<select name="' . $value['id'][1]['id'] . '" id="' . $value['id'][1]['id'] . '" size="1" class="r-meta-select r-meta-crop">';
		$options = array(
						 array('name' => 'Center', 'value' => 'c'),
						 array('name' => 'Top', 'value' => 't'),
						 array('name' => 'Top right', 'value' => 'tr'),
						 array('name' => 'Top left', 'value' => 'tl'),
						 array('name' => 'Bottom', 'value' => 'b'),
						 array('name' => 'Bottom right', 'value' => 'br'),
						 array('name' => 'Bottom left', 'value' => 'bl'),
						 array('name' => 'Left', 'value' => 'l'),
						 array('name' => 'Right', 'value' => 'r')
						 );
		foreach($options as $option) {
			
			if ($value['id'][1]['std'] == $option['value']) $selected = 'selected="selected"';
			else $selected = '';
			echo "<option $selected value='" . $option['value'] . "'>" . $option['name'] . "</option>";
		}
		echo '</select>';
		}
        
		/* Image preview */
	    echo '<div class="r-image">';
		$hidden_class = 'r-hidden';
		
		if ($value['id'][0]['std'] != '') {
			if (isset($value['id'][1]['std'])) $image_crop = $value['id'][1]['std'];
			else $image_crop = 'c';
			$img = r_image_exists($value['id'][0]['std']);
			if ($img) {
				echo '<img src="' . r_image_resize($value['thumb_width'], $value['thumb_height'], $value['id'][0]['std'], $image_crop) . '" alt="' . theme_path($value['id'][0]['std']) . '" alt="' . $value['id'][0]['std'] . '" class="r-preview"/>';
		    } else $hidden_class = '';
		}
		
		echo '</div>';
		if (isset($value['id'][1]['std'])) $image_crop = $value['id'][1]['std'];
		else $image_crop = 'c';
		echo '<span class="r-ajax-warning ' . $hidden_class  . '">' . __('The link is incorrect or the image does not exist.', SHORT_NAME) . '</span>';
		echo '<div class="hidden"><span class="r-set-crop">' . $image_crop . '</span><span class="r-set-width">' . $value['thumb_width'] . '</span><span class="r-set-height">' . $value['thumb_height'] . '</span></div>';
			
		if (isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}
	
	/* Array select */	
	function array_select($value) {
		global $r_option;
		
		echo '<label for="' . $value['id'] . '" class="r-meta-label">' . $value['label'] . '</label>';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" size="1" class="r-meta-select">';
		
		if (isset($value['options'])) {
			foreach($value['options'] as $option) {
				if ($value['std'] == $option['name']) $selected = 'selected="selected"';
				echo '<option ' . $selected . ' value="' . $option['name'] . '">' . $option['label'] . '</option>';
			}
		}
		
		$custom_array = $value['array'];
		$key = $value['key'];
		
		if (isset($r_option[$custom_array])) {
			foreach($r_option[$custom_array] as $array) {
				if ($value['std'] == $array[$key]) $selected = 'selected="selected"';
				else $selected = '';
				echo "<option $selected value=\"" . $array[$key] . "\">" . $array[$key] . "</option>" . "\n";
			}
		}

		echo '</select>';
		if(isset($value['desc']) && $value['desc'] != '') echo '<p class="r-meta-help">' . $value['desc'] . '</p>';
	}

}
?>