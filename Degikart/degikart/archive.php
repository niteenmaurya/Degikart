<?php
get_header();
?>

<div id="site-width">

    <?php
    // Archive title display (category, date, or author name)
    if (is_category()) {
        echo '<h1 class="archive-title">Category: ' . single_cat_title('', false) . '</h1>';
    } elseif (is_tag()) {
        echo '<h1 class="archive-title">Tag: ' . single_tag_title('', false) . '</h1>';
    } elseif (is_author()) {
        $author = get_queried_object();
        echo '<h1 class="archive-title">Author: ' . $author->display_name . '</h1>';
    } elseif (is_day()) {
        echo '<h1 class="archive-title">Daily Archive: ' . get_the_date() . '</h1>';
    } elseif (is_month()) {
        echo '<h1 class="archive-title">Monthly Archive: ' . get_the_date('F Y') . '</h1>';
    } elseif (is_year()) {
        echo '<h1 class="archive-title">Yearly Archive: ' . get_the_date('Y') . '</h1>';
    } else {
        echo '<h1 class="archive-title">Archives</h1>';
    }

    // Custom WP Query to fetch posts for archive
    $args = array(
        'posts_per_page' => 10, // Number of posts per page
        'paged' => get_query_var('paged', 1), // Pagination
    );

    if (is_category()) {
        $args['cat'] = get_queried_object_id(); // Filter by current category
    } elseif (is_tag()) {
        $args['tag'] = get_queried_object()->slug; // Filter by current tag
    } elseif (is_author()) {
        $args['author'] = get_queried_object_id(); // Filter by current author
    }

    $archive_query = new WP_Query($args);

    // Start the loop
    if ($archive_query->have_posts()) :
        echo '<div class="archive-posts">'; // Container for all posts
        while ($archive_query->have_posts()) : $archive_query->the_post();
        ?>

        <article class="archive-post">
            
            <!-- Featured Image -->
             <?php if (has_post_thumbnail()) : ?>
                <div class="post-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('large'); ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Post Header with Title and Meta Information -->
             <header>
                <h2 class="post-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="post-meta">
                    <span class="post-date"><?php the_date(); ?></span> |
                    <span class="post-author"><?php the_author(); ?></span>
                </div>
            </header>
            
            <!-- Post Excerpt -->
             <div class="entry-content">
                <?php
                $excerpt_length = get_theme_mod('degikart_blog_excerpt_length', 55); // 55 words default
                $excerpt = get_the_excerpt();
                if (empty($excerpt)) {
                    $excerpt = get_the_content();
                }
                echo wp_trim_words($excerpt, $excerpt_length, '...');
                ?>
            </div>

            <!-- Read More Button -->
            <div class="read-btn">
                <a href="<?php the_permalink(); ?>" class="read-more-btn">Read more</a>
            </div>

        </article>

    <?php endwhile; ?>
    <?php else : ?>
        <p><?php _e( 'No posts found.', 'degikart' ); ?></p>
    <?php endif; ?>
    
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

</div>

<?php
wp_reset_postdata(); // Reset post data
get_footer();
?>
