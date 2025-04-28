<?php get_header(); ?>

<div id="site-width">
    <section class="error-404">
        <h1><?php _e('404 - Oops! Page Not Found', 'degikart'); ?></h1>
        <p><?php _e('How about trying a search?', 'degikart'); ?></p>
        <?php get_search_form(); ?>
        <p><a href="<?php echo esc_url(home_url()); ?>"><?php _e('Return to Home', 'degikart'); ?></a></p>
    </section>
</div>

<?php get_footer(); ?>
