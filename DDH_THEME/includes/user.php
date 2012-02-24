<?php if ($user_ID) : ?>

<p class="user"><a href="<?php echo get_option( 'siteurl' ); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a> | <?php edit_post_link('edit'); ?>
 | <a href="<?php echo wp_logout_url( get_permalink() ); ?>">logout</a></p>
 
<?php endif; ?>