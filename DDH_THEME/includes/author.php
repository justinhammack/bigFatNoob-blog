<!-- about-author -->
<div class="about-author">
    <div class="about-author-avatar"><?php echo get_avatar(get_the_author_meta('email'), '84'); ?></div>
    <div class="about-author-text">
        <p><strong><?php the_author_link(); ?></strong> - <?php the_author_meta('description'); ?></p>
    </div>
</div>
<!-- /about-author -->