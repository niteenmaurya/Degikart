<?php
get_header();
?>

<div id="site-width">      
    <section id="custom-card">
        <div class="card-wrapper">
            <div class="card-header">
                <h1><?php _e('Top WordPress Themes & Website Templates for Every Project', 'degikart'); ?></h1>
                <p class="card-subtitle"><?php _e('Browse through a wide selection of customizable themes, templates, and CMS solutions, crafted by expert developers to help bring your website vision to life.', 'degikart'); ?></p>
                 <?php degikart_custom_card(); ?>
            </div>
            <div class="card-image">
                <img src="" alt="">
            </div>
        </div>
    </section>
    
    <main class="site-content-area" id="content">
        <!-- Products & Courses Cards -->
         <div class="post-category">
            <?php
            // Define card content with different URLs for each card's buttons
            $cards = [
                ['title' => 'WordPress', 'description' => 'Explore the best WordPress themes for websites.', 'bg_color' => '#f4f4f4', 'slug' => 'wordpress-category/wordpress'],
                ['title' => 'Courses', 'description' => 'Learn and grow with the most effective online courses.', 'bg_color' => '#e9f7f9', 'slug' => 'course-category/courses'],
                ['title' => 'Blogger', 'description' => 'Start your blogging journey with easy platforms today.', 'bg_color' => '#fff3e6', 'slug' => 'blogger-category/blogger'],
                ['title' => 'Plugins', 'description' => 'Enhance your website using the best essential plugins.', 'bg_color' => '#f1e5f8', 'slug' => 'plugin-category/plugin'],
                ['title' => 'Ecommerce', 'description' => 'Build your online store with powerful ecommerce tools.', 'bg_color' => '#e0f7fa', 'slug' => 'ecommerce-category/ecommerce/']
            ];

            foreach ($cards as $card) {
                // Dynamic URL with the category slug
                $category_url = home_url("{$card['slug']}/");

                echo '<div class="card" style="background-color: ' . esc_attr($card['bg_color']) . ';">';
                echo '<div class="card-content">';
                // Make the title clickable, using dynamic category slug in the URL
                echo '<h3><a href="' . esc_url($category_url) . '">' . esc_html($card['title']) . '</a></h3>';
                echo '<p>' . esc_html($card['description']) . '</p>';
                echo '<div class="card-buttons">';
                // Use dynamic URLs for each card's buttons
                echo '<a href="' . esc_url($category_url . '?orderby=date&order=desc') . '#" class="btn new-btn">Newest</a>';
                echo '<a href="' . esc_url($category_url . '?orderby=sales_count&order=asc') . '#" class="btn bestseller-btn">Bestsellers</a>'; 
                echo '</div></div></div>'; 
            }
            ?>
        </div>

        <!-- Always Best Sales Section -->
        <?php
        // Query to get the top 6 best-selling items
        $args = [
            'post_type' => ['plugin', 'blogger', 'wordpress', 'ecommerce'], // Array of custom post types
            'posts_per_page' => 6,
            'meta_key' => 'sales_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => 'sales_count',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'NUMERIC'
                ]
            ]
        ];

        $top_selling_query = new WP_Query($args);

        // Check if there are any posts
        if ($top_selling_query->have_posts()) : ?>
            <div class="item-bestsales">
                <div class="title-des">
                    <h2 class="weekly-best-sales-title"><?php _e('Weekly Best Sales', 'degikart'); ?></h2>
                    <p class="best-sales-description"><?php _e('Check out our weekly best-selling products and top-rated items!', 'degikart'); ?></p>
                </div>

                <div class="card-container">
                    <?php 
                    while ($top_selling_query->have_posts()) : $top_selling_query->the_post();
                        $post_meta = get_post_meta(get_the_ID());
                        $categories = wp_get_post_terms(get_the_ID(), 'product-category', array('fields' => 'names'));

                        $regular_price = isset($post_meta['regular_price'][0]) ? esc_html($post_meta['regular_price'][0]) : '0';
                        $extended_price = isset($post_meta['extended_price'][0]) ? esc_html($post_meta['extended_price'][0]) : '0';
                        $support_price_12_months = isset($post_meta['support_price'][0]) ? esc_html($post_meta['support_price'][0]) : '0';

                        $demo_url = isset($post_meta['demo_url'][0]) ? esc_url($post_meta['demo_url'][0]) : '';
                        $thumbnail_url = isset($post_meta['thumbnail_url'][0]) ? esc_url($post_meta['thumbnail_url'][0]) : '';
                        $theme_name = get_the_title();
                        $author_name = get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()));

                        // Calculate the average rating
                        $comments = get_comments(array('post_id' => get_the_ID()));
                        $total_ratings = 0;
                        $total_raters = 0;
                        foreach ($comments as $comment) {
                            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                            if ($rating) {
                                $total_ratings += $rating;
                                $total_raters++;
                            }
                        }
                        $average_rating = $total_raters > 0 ? $total_ratings / $total_raters : 0;
                        $rounded_rating = round($average_rating * 2) / 2; // Round to nearest 0.5

                        // Get current post ID
                        $product_id = get_the_ID();

                        // Get sales count
                        $sales_count = get_post_meta($product_id, '_sales_count', true);
                        $sales_count = $sales_count ? intval($sales_count) : 0;
                    ?>
                    <article class="product-content">
                        <div class="inside-article">
                            <div class="image-box">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

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
                            <p class="main-price">$<?php echo $regular_price; ?></p>

                            <p class="rating">
                                <span class="rating-stars">
                                    <?php
                                    $full_stars = floor($rounded_rating);
                                    $half_star = ($rounded_rating - $full_stars) >= 0.5;
                                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

                                    echo str_repeat('<span class="star yellow">★</span>', $full_stars);
                                    if ($half_star) {
                                        echo '<span class="star half">★</span>';
                                    }
                                    echo str_repeat('<span class="star">☆</span>', $empty_stars);
                                    ?>
                                </span>
                                <span class="rating-count">(<?php echo esc_html($total_raters); ?>)</span>
                            </p>

                            <div class="sales-preview">
                                <p class="sales"><strong><?php _e('Sales:', 'degikart'); ?></strong> <?php echo $sales_count; ?></p>

                                <div class="live-cart">
                                    <form class="cart-btn" onsubmit="addToCart(event, <?php echo get_the_ID(); ?>, '<?php echo $regular_price; ?>', '<?php echo $extended_price; ?>', '<?php echo $support_price_12_months; ?>', '<?php echo $theme_name; ?>', '<?php echo $author_name; ?>')">
                                        <button type="submit">
                                            <svg width="26" height="26" viewBox="0 0 80 65" xml:space="preserve">
                                                <path fill="#ffffff" d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z"/>
                                                <path fill="#233251" d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <p class="live-preview"><a href="<?php echo $demo_url; ?>"><?php _e('View Demo', 'degikart'); ?></a></p>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Load More Button -->
                <div class="load-more-container">
                    <a href="<?php echo esc_url(home_url('/products/?orderby=sales_count&order=asc')); ?>" class="bestsales-load">Load More Items</a>
                </div>
            </div>
        <?php 
        wp_reset_postdata(); 
        else :
            // Optionally, display a message if there are no products
        endif;
        ?>
        <?php degikart_popup_notification(); ?>
        
        <?php
        // Query to get the newest 6 best-selling products from the last 7 days, ordered by sales count
        $args = [
            'post_type' => 'course',
            'posts_per_page' => 6,
            'date_query' => [
                [
                    'after' => '1 week ago' // Filters posts from the last 7 day
                ]
            ],
            'meta_query' => [
                [
                    'key' => 'sales_count', // Ensure the meta key for sales count is correct
                    'type' => 'NUMERIC',
                    'compare' => 'EXISTS'
                ]
            ],
            'orderby' => [
                'meta_value_num' => 'DESC', // Order by the sales_count meta value
                'date' => 'DESC' // In case of a tie, order by date
            ],
            'order' => 'DESC'
        ];
        // Create the WP_Query object
        $newest_query = new WP_Query($args);
        ?>
                    
        <?php if ($newest_query->have_posts()) : ?>
            <div class="item-new-products">
                <div class="title-des">
                    <h2 class="weekly-best-courses-title"><?php _e('Weekly Best Courses', 'degikart'); ?></h2>
                    <p class="best-courses-description"><?php _e('Check out our top-selling and most popular courses this week!', 'degikart'); ?></p>
                </div>
                <div class="card-container">
                    <?php
                    while ($newest_query->have_posts()) : $newest_query->the_post();

                    // Get post meta
                    $post_meta = get_post_meta(get_the_ID());
                    $categories = wp_get_post_terms(get_the_ID(), 'product-category', ['fields' => 'names']);

                    // Get regular price, extended price, and other metadata
                    $regular_price = isset($post_meta['regular_price'][0]) ? esc_html($post_meta['regular_price'][0]) : '0';
                    $extended_price = isset($post_meta['extended_price'][0]) ? esc_html($post_meta['extended_price'][0]) : '0';
                    $support_price_12_months = isset($post_meta['support_price'][0]) ? esc_html($post_meta['support_price'][0]) : '0';
                    $demo_url = isset($post_meta['demo_url'][0]) ? esc_url($post_meta['demo_url'][0]) : '';
                    $thumbnail_url = isset($post_meta['thumbnail_url'][0]) ? esc_url($post_meta['thumbnail_url'][0]) : '';

                    // Get author and product details
                    $theme_name = get_the_title();
                    $author_name = get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()));

                    // Calculate the average rating for this product
                    $comments = get_comments(['post_id' => get_the_ID()]);
                    $total_ratings = 0;
                    $total_raters = 0;
                    foreach ($comments as $comment) {
                        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                        if ($rating) {
                            $total_ratings += $rating;
                            $total_raters++;
                        }
                    }
                    $average_rating = $total_raters > 0 ? $total_ratings / $total_raters : 0;
                    $rounded_rating = round($average_rating * 2) / 2; // Round to nearest 0.5

                    // Get sales count
                    $sales_count = get_post_meta(get_the_ID(), '_sales_count', true);
                    $sales_count = $sales_count ? intval($sales_count) : 0;
                    ?>
                    
                    <article class="product-content">
                        <div class="inside-article">
                            <div class="image-box">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

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
                            <p class="main-price">$<?php echo $regular_price; ?></p>

                            <p class="rating">
                                <span class="rating-stars">
                                    <?php
                                    // Display rating stars
                                    $full_stars = floor($rounded_rating);
                                    $half_star = ($rounded_rating - $full_stars) >= 0.5;
                                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

                                    echo str_repeat('<span class="star yellow">★</span>', $full_stars);
                                    if ($half_star) {
                                        echo '<span class="star half">★</span>';
                                    }
                                    echo str_repeat('<span class="star">☆</span>', $empty_stars);
                                    ?>
                                </span>
                                <span class="rating-count">(<?php echo esc_html($total_raters); ?>)</span>
                            </p>

                            <div class="sales-preview">
                                <p class="sales"><strong><?php _e('Sales:', 'degikart'); ?></strong> <?php echo $sales_count; ?></p>

                                <div class="live-cart">
                                    <form class="cart-btn" onsubmit="addToCart(event, <?php echo get_the_ID(); ?>, '<?php echo $regular_price; ?>', '<?php echo $extended_price; ?>', '<?php echo $support_price_12_months; ?>', '<?php echo $theme_name; ?>', '<?php echo $author_name; ?>')">
                                        <button type="submit">
                                            <svg width="26" height="26" viewBox="0 0 80 65" xml:space="preserve">
                                                <path fill="#ffffff" d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z"/>
                                                <path fill="#233251" d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <p class="live-preview"><a href="<?php echo $demo_url; ?>"><?php _e('View Demo', 'degikart'); ?></a></p>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
                
                <!-- Load More Button -->
                <div class="load-more-container">
                    <a href="<?php echo esc_url(home_url('/course/?orderby=date&order=desc')); ?>" class="bestsales-load"><?php _e('Load More Items', 'degikart'); ?></a>
                </div>
            </div>
        <?php else : ?>
        <!-- If no new products found, section is hidden -->
        <?php endif; ?>

        <!-- Weekly New Items Section -->
        <?php
        // Query to get the newest 6 products added in the last 7 days
        $args = [
            'post_type' => ['plugin', 'blogger', 'wordpress', 'ecommerce'], // Array of custom post types
            'posts_per_page' => 6, // Limit to 6 products (you can change this number as needed)
            'orderby' => 'date', // Order by date
            'order' => 'desc' // Order in descending (most recent first)
        ];
        
        $newest_query = new WP_Query($args);
        ?>
        <?php if ($newest_query->have_posts()) : ?>
            <div class="item-new-products">
                <div class="title-des">
                    <h2 class="weekly-new-items-title"><?php _e('Explore Our Latest Products', 'degikart'); ?></h2>
                    <p class="new-items-description"><?php _e('Browse our handpicked selection of top-quality products below!', 'degikart'); ?></p>
                </div>
                <div class="card-container">
                    <?php
                    // Loop through each post
                    while ($newest_query->have_posts()) : $newest_query->the_post();
                        // Fetch post meta
                        $post_meta = get_post_meta(get_the_ID());
                        $meta = get_post_meta(get_the_ID());

                        // Fetch necessary meta values
                        $regular_price = isset($meta['regular_price'][0]) ? esc_html($meta['regular_price'][0]) : '0';
                        $extended_price = isset($meta['extended_price'][0]) ? esc_html($meta['extended_price'][0]) : '0';
                        $support_price_12_months = isset($meta['support_price'][0]) ? esc_html($meta['support_price'][0]) : '0';
                        $demo_url = isset($meta['demo_url'][0]) ? esc_url($meta['demo_url'][0]) : '';
                        $theme_name = get_the_title();
                        $author_name = get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()));

                        // Calculate the average rating for this product
                        $comments = get_comments(array('post_id' => get_the_ID()));
                        $total_ratings = 0;
                        $total_raters = 0;
                        foreach ($comments as $comment) {
                            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                            if ($rating) {
                                $total_ratings += $rating;
                                $total_raters++;
                            }
                        }
                        $average_rating = $total_raters > 0 ? $total_ratings / $total_raters : 0;
                        $rounded_rating = round($average_rating * 2) / 2; // Round to the nearest 0.5

                        // Get current post ID for sales count
                        $product_id = get_the_ID();
                        $sales_count = get_post_meta($product_id, '_sales_count', true);
                        $sales_count = $sales_count ? intval($sales_count) : 0;
                    ?>
                    <article class="product-content">
                        <div class="inside-article">
                            <div class="image-box">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

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
                            <p class="main-price">$<?php echo $regular_price; ?></p>

                            <p class="rating">
                                <span class="rating-stars">
                                    <?php
                                    $full_stars = floor($rounded_rating);
                                    $half_star = ($rounded_rating - $full_stars) >= 0.5;
                                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);

                                    echo str_repeat('<span class="star yellow">★</span>', $full_stars);
                                    if ($half_star) {
                                        echo '<span class="star half">★</span>';
                                    }
                                    echo str_repeat('<span class="star">☆</span>', $empty_stars);
                                    ?>
                                </span>
                                <span class="rating-count">(<?php echo esc_html($total_raters); ?>)</span>
                            </p>

                            <div class="sales-preview">
                                <p class="sales"><strong><?php _e('Sales:', 'degikart'); ?></strong> <?php echo $sales_count; ?></p>

                                <div class="live-cart">
                                    <form class="cart-btn" onsubmit="addToCart(event, <?php echo get_the_ID(); ?>, '<?php echo $regular_price; ?>', '<?php echo $extended_price; ?>', '<?php echo $support_price_12_months; ?>', '<?php echo $theme_name; ?>', '<?php echo $author_name; ?>')">
                                        <button type="submit">
                                            <svg width="26" height="26" viewBox="0 0 80 65" xml:space="preserve">
                                                <path fill="#ffffff" d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z"/>
                                                <path fill="#233251" d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <p class="live-preview"><a href="<?php echo $demo_url; ?>"><?php _e('View Demo', 'degikart'); ?></a></p>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>

                <!-- Load More Button -->
                <div class="load-more-container">
                    <a href="<?php echo esc_url(home_url('/products/?orderby=date&order=desc')); ?>" class="bestsales-load"><?php _e('Load More Items', 'degikart'); ?></a>
                </div>
            </div>
        <?php else : ?>
        <!-- If no new products found, section is hidden -->
        <?php endif; ?> 
    </main>
</div>

<?php get_footer(); ?>

