<?php
/* Do not delete these line */
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die (__('Please do not load this page directly. Thanks!', SHORT_NAME)); 
?>

<?php

/* Comment display function
 ------------------------------------------------------------------------*/
   
function theme_comments($comment, $args, $depth) {
global $r_option;
$GLOBALS['comment'] = $comment; ?>
<!-- comment -->
<li id="li-comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
<div id="comment-<?php comment_ID(); ?>">
    <div class="comment">
    	<div class="commenter"><?php echo get_avatar($comment, '84'); ?>
    		<div class="comment-date">
            	<span><?php comment_date($r_option['custom_date']); ?></span> <span class="time"><?php comment_time('H:i'); ?></span>
            </div>
    		<div class="commenter-pointer"></div>
		</div>
        <div class="comment-text-wrap">
        <div class="comment-text">
        	<h4><?php comment_author_link(); ?></h4>
          	<?php comment_text(); ?>
            <?php if($comment->comment_approved == '0') : ?>
            <p><?php _e('Your comment is awaiting moderation.', SHORT_NAME); ?></p>
            <?php endif; ?> 
		</div>
        </div>
	</div>
    <!-- /comment -->
    <!-- reply -->
    <div class="reply"><?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply', SHORT_NAME), 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?></div>
	<!-- /reply -->
    </div>
<?php } // End comment function ?>
<?php global $r_option;  ?>

<!-- start comments -->
<div class="clear"></div>
<div id="comments">

<?php 

/* If post password required
 ------------------------------------------------------------------------*/ 

if (post_password_required()) : ?>
	<p><?php _e('This post is password protected. Enter the password to view any comments.', SHORT_NAME); ?></p>
    </div>
<?php return; endif; ?>
    
<?php 

/* If the post has a comments
 ------------------------------------------------------------------------*/ 

if (have_comments()) : ?>
    <h5 class="comments-header"><?php printf( _n( 'One Response to %2$s', '%1$s Responses to %2$s', get_comments_number(), SHORT_NAME), number_format_i18n(get_comments_number()), '"' . get_the_title()); ?>"</h5>
    <ol class="commentlist">
    <?php wp_list_comments('callback=theme_comments'); ?>
    </ol>
	<?php else : ?>
    <?php if (!comments_open()) : ?>
    <p><?php _e('Comments are closed.', SHORT_NAME); ?></p>
    <?php else : ?>
	<p><?php _e('Currently there are no comments related to this article. You have a special honor to be the first commenter. Thanks!', SHORT_NAME); ?></p>
    <?php endif; // end !comments_open() ?>
    <?php endif; // end have_comments() ?>
    
<?php 

/* Comment form
 ------------------------------------------------------------------------*/ 

?>  
    <!-- comment form -->    
	<?php if('open' == $post->comment_status) : ?>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
    <p><?php _e( 'You must be logged in to post a comment..', SHORT_NAME ); ?></p>
 	<?php else : ?>
    <div id="respond" class="comment-form">
    	<h3><?php _e( 'Leave a Reply', SHORT_NAME ); ?> <span id="cancel-comment-reply"><?php cancel_comment_reply_link(__( '(Click here to cancel reply)', SHORT_NAME )); ?></span></h3>  
	<span class="not-pub"><?php _e( 'Your email address will not be published.', SHORT_NAME ); ?></span>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
        	<fieldset>
            	<?php if(!$user_ID ) : ?>
                <input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" size="22" tabindex="1" class="input name-icon" />
                <input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" class="input email-icon" />
                <input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" class="input web-icon no-margin" />
                <?php endif; ?>
                <textarea tabindex="4" rows="9" id="comment" name="comment" class="textarea"></textarea>
               	<div class="submit-wrap">
                   	<input type="hidden" value="<?php echo $id; ?>" name="comment_post_ID" />
                    <input type="submit" value="Submit" tabindex="5" id="comment-submit" name="submit" />
                    <?php comment_id_fields(); ?>
                    <?php do_action('comment_form', $post->ID); ?>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- end Comment Form -->
    <?php endif; ?>
	<?php endif; ?>  
</div>
<!-- end comments -->