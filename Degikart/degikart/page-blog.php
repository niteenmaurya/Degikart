<?php
get_header();
?>

<main class="site-content-area" id="content">
    <div class="site-cont" id="cont-site">
    <?php
    // The Query to get blog posts
    $args = array(
        'post_type' => 'post',  // Ensure you're pulling posts
    );

    $blog_query = new WP_Query($args);
 if ($blog_query->have_posts()) :
        while ($blog_query->have_posts()) : $blog_query->the_post();
            ?>
                <article>

                    <div class="insider-article">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <div class="post-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large'); ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <header class="entry-header">
                            <h2 class="main-entry-title" itemprop="headline">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="entry-meta">
                            <span class="byline">
                                <span class="author vcard" itemprop="author" itemscope="">
                                    <a class="url fn n" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php printf(__('View all posts by %s', 'degikart'), get_the_author()); ?>" rel="author" itemprop="url">
                                        <?php degikart_display_author_profile_picture(get_the_author_meta('ID')); ?>
                                        <span class="author-name" itemprop="name"><?php the_author(); ?></span>
                                    </a>
                                </span>
                            </span>
                            <span class="posted-on"><?php echo get_the_date(); ?></span>
                        </div>
                        </header>
                    </div>

                    <div class="entry-content">

                        <?php
                        $excerpt_length = get_theme_mod('degikart_blog_excerpt_length', 55);
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $excerpt = get_the_content();
                        }
                        echo wp_trim_words($excerpt, $excerpt_length, '...');
                        ?>
                
                    </div>
                    <div class="read-btn">
    <a href="<?php the_permalink(); ?>" tabindex="5">
        <input type="button" value="<?php _e( 'Read more', 'degikart' ); ?>" name=" " tabindex="0">
    </a>
</div>

                    
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php _e( 'No posts found.', 'degikart' ); ?></p>
        <?php endif; ?>
    </div>
    <div class="pagination">
    <?php
    the_posts_pagination( array(
        'mid_size'  => 0, // Number of pages to show on either side of current page
        'end_size'  => 1, // Number of pages to show at the beginning and end
        'prev_text' => __( '← Previous', 'degikart' ),
        'next_text' => __( 'Next →', 'degikart' ),
    ) );
    ?>
    </div>
</main>
<?php
get_footer();
?>

 