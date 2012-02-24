<?php

/**
 * Look for the server path to the file wp-load.php for user authentication
 */

$wp_include = "../wp-load.php";
$i = 0;
while (!file_exists($wp_include) && $i++ < 10) {
  $wp_include = "../$wp_include";
}
require($wp_include);

/* Check user premissions */
if (!is_user_logged_in() || !current_user_can('edit_posts')) 
	wp_die(__('You are not allowed to be here', SHORT_NAME));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Shortcodes Manager</title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo ADMIN_URI ?>/shortcodes_manager/shortcodes_manager.js"></script>
<base target="_self" />
</head>
<body id="link" onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display=''">
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
<form name="shortcodes" action="#">
  <div class="tabs">
    <ul>
      <li id="shortcodes_tab" class="current"><span><a href="javascript:mcTabs.displayTab('shortcodes_tab','shortcodes_panel');" onMouseDown="return false;">Shortcodes</a></span></li>
      <li id="image_tab"><span><a href="javascript:mcTabs.displayTab('image_tab','image_panel');" onMouseDown="return false;">Custom Image</a></span></li>
    </ul>
  </div>
  <div class="panel_wrapper" style="height:260px;">
    <!-- shortcodes panel -->
    <div id="shortcodes_panel" class="panel current"> <br />
      <fieldset>
        <legend>Select the Style Shortcode you would like to insert into the post.</legend>
        <table border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td nowrap="nowrap"><label for="shortcode">Select a shortcode:</label></td>
            <td><select id="shortcode" name="shortcode" style="width: 200px" >
                <option value="0"></option>
                <optgroup label="Columns">
                <option value="2_columns">2 Columns</option>
                <option value="3_columns">3 Columns</option>
                <option value="4_columns">4 Columns</option>
                <option value="2_3_1_3_columns">2/3 Column + 1/3 Column</option>
                <option value="1_3_2_3_columns">1/3 Column + 2/3 Column</option>
                <option value="3_4_1_4_columns">3/4 Column + 1/4 Column</option>
                <option value="1_4_3_4_columns">1/4 Column + 3/4 Column</option>
                </optgroup>
                <optgroup label="Music">
                <option value="player">Flash Player</option>
                <option value="html5_player">HTML5 Player</option>
                <option value="playlist">Playlist</option>
                <option value="soundcloud">Soundcloud</option>
                </optgroup>
                <optgroup label="Videos">
                <option value="youtube">YouTube</option>
                <option value="vimeo">Vimeo</option>
                </optgroup>
                <optgroup label="Images">
                <option value="easy_image">Easy Image</option>
                </optgroup>
                <optgroup label="Buttons">
                <option value="button">Button</option>
                <option value="button_blue">Button Blue</option>
                <option value="button_orange">Button Orange</option>
                <option value="button_green">Button Green</option>
                <option value="button_download">Button Download</option>
                </optgroup>
                <optgroup label="Messages">
                <option value="message">Message</option>
                <option value="message_update">Message Update</option>
                </optgroup>
                <optgroup label="Lists">
                <option value="check_list">Check List</option>
                <option value="events_list">Events List</option>
                <option value="posts_list">Posts List</option>
                </optgroup>
                <optgroup label="Contact Forms">
                <option value="contact_form">Contact Form</option>
                </optgroup>
                <optgroup label="Misc Stuff">
                <option value="divider">Divider</option>
                <option value="divider_top">Divider With Top Button</option>
                <option value="last_tweet">Last Tweet</option>
                <option value="highlight">Highlight</option>
                <option value="box">Box</option>
                </optgroup>
              </select></td>
          </tr>
        </table>
      </fieldset>
    </div>
    <!-- /shortcodes panel -->
    <!-- image panel -->
    <div id="image_panel" class="panel"> <br />
      <fieldset>
        <legend>Custom image.</legend>
        <table border="0" cellpadding="4" cellspacing="0">
          <tr>
            <td nowrap="nowrap"><label for="image_url">Image URL (http://):</label></td>
            <td colspan="3"><input name="image_url" id="image_url" value="" style="width: 200px"/></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="image_title">Image title:</label></td>
            <td colspan="3"><input name="image_title" id="image_title" value="" style="width: 200px"/></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="image_width">Width (px):</label></td>
            <td><input name="image_width" id="image_width" value="" style="width: 60px"/></td>
            <td nowrap="nowrap"><label for="image_height">Height (px):</label></td>
            <td><input name="image_height" id="image_height" value="" style="width: 60px"/></td>
          </tr>
           <tr>
            <td nowrap="nowrap"><label for="image_crop">Image Crop:</label></td>
            <td colspan="3">
            <select name="image_crop" id="image_crop">
            <option value="c" selected="selected">Center</option>
            <option value="t">Top</option>
            <option value="tr">Top right</option>
            <option value="tl">Top left</option>
            <option value="b">Bottom</option>
            <option value="br">Bottom right</option>
            <option value="bl">Bottom left</option>
            <option value="l">Left</option>
            <option value="r">Right</option>
            </select>
            </td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="image_link">Image link (http://):</label></td>
            <td colspan="3"><input name="image_link" id="image_link" value="" style="width: 200px"/></td>
          </tr>
          <tr> <td colspan="3">
            <label>
              <input name="hover" type="checkbox" id="hover" checked="checked" value="1" />
              Hover effect</label>
            <label>
              <input name="target" type="checkbox" id="target" value="1" />
              Open link in new window</label>
              </td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="lightbox">Lightbox:</label></td>
            <td colspan="3"><input name="lightbox" type="checkbox" id="lightbox" value="lightbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><label for="lightbox_group">Lightbox Group:</label></td>
            <td colspan="3"><input name="lightbox_group" id="lightbox_group" value="" style="width: 100px"/></td>
          </tr>
            <tr>
            <td nowrap="nowrap"><label for="image_pos">Image Alignment:</label></td>
            <td colspan="3">
            <select name="image_pos" id="image_pos">
            <option value="left" selected="selected">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
            </select>
            </td>
          </tr>
        </table>
      </fieldset>
    </div>
    <!-- /image panel -->
  </div>
  <div class="mceActionPanel">
    <div style="float: left">
      <input type="button" id="cancel" name="cancel" value="<?php _e('Cancel', SHORT_NAME); ?>" onClick="tinyMCEPopup.close();" />
    </div>
    <div style="float: right">
      <input type="submit" id="insert" name="insert" value="<?php _e('Insert', SHORT_NAME); ?>" onClick="insert_shortcode();" />
    </div>
  </div>
</form>
</body>
</html>
