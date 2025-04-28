<section id="search-card">
    <form role="search" method="get" class="card-search" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="search" class="search-field" placeholder="<?php _e('Search...', 'degikart'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit"><?php _e('Search', 'degikart'); ?></button>
    </form>
</section>
