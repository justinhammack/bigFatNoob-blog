<?php 
	function r_tweet_link() {
		if (!$short_link = get_post_meta(get_the_ID(), 'short_link', true)) {
			$short_link = wp_remote_retrieve_body(wp_remote_get('http://tinyurl.com/api-create.php?url=' . get_permalink(get_the_ID())));
			if (!$short_link) $short_link = sprintf('%s?p=%s', home_url(), get_the_ID());
			add_post_meta(get_the_ID(), 'short_link', $short_link);
		}
		$output = sprintf('http://twitter.com/home?status=%s%s%s', urlencode(get_the_title()), urlencode(' - '), $short_link);
		$output = str_replace('+','%20',$output);
		return $output;
	}
		
	$blogname = urlencode(get_bloginfo('name')." ".get_bloginfo('description'));
	$excerpt = urlencode(strip_tags(strip_shortcodes(get_the_excerpt())));
		
	if ($excerpt == '') $excerpt = urlencode(substr(strip_tags(strip_shortcodes(get_the_content())),0,250));
		
	$excerpt = str_replace('+','%20',$excerpt);
	$excerpt = str_replace('%0D%0A','',$excerpt);
	$permalink = urlencode(get_permalink());
	$title = str_replace('+','%20',urlencode(get_the_title()));	
?>
<!-- share icons -->
<div id="bookmarks">
	<div>
		<a href="<?php echo r_tweet_link(); ?>" class="share-it share-twitter bookmarks-tip" title="Twitter"></a>
		<a href="http://delicious.com/post?url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>&amp;notes=<?php echo $excerpt; ?>" class="share-it share-delicious bookmarks-tip" title="del.icio.us"></a>
		<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>&amp;source=<?php echo $title; ?>&amp;summary=<?php echo $blogname; ?>" class="share-it share-linked-in bookmarks-tip" title="LinkedIn"></a>
		<a href="http://digg.com/submit?phase=2&amp;url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>&amp;bodytext=<?php echo $excerpt; ?>" class="share-it share-digg bookmarks-tip" title="Digg"></a>
		<a href="http://www.stumbleupon.com/submit?url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" class="share-it share-stumble bookmarks-tip" title="StumbleUpon"></a>
		<a href="http://www.facebook.com/share.php?u=<?php echo $permalink; ?>&amp;t=<?php echo $title; ?>" class="share-it share-facebook bookmarks-tip" title="Facebook"></a>
		<a href="http://reddit.com/submit?url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" class="share-it share-reddit bookmarks-tip" title="Reddit"></a>
	</div>
</div>
<!-- /bookmarks -->