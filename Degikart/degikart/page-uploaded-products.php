<?php
/*
Template Name: Uploaded Products
*/
get_header();
?>
<div id="site-width">
<div id="uploaded-products-page">
    <div class="uploaded-products-container">
        <h1>Your Uploaded Products</h1>
        <div class="products-grid">
            <?php
            $current_user = wp_get_current_user();
            $args = array(
                'post_type' => ['course', 'plugin', 'blogger', 'wordpress', 'ecommerce'],
                'author' => $current_user->ID,
                'posts_per_page' => -1, // Show all products
                'post_status' => array('pending', 'publish', 'unapprove') // Include unapprove status
            );
            $products = new WP_Query($args);
            if ($products->have_posts()) :
                while ($products->have_posts()) : $products->the_post();
                    if (get_the_author_meta('ID') == $current_user->ID) {
                        $meta = get_post_meta(get_the_ID());
                        $category = isset($meta['category'][0]) ? $meta['category'][0] : 'N/A';
                        $price = isset($meta['price'][0]) ? $meta['price'][0] : 'N/A';
                        $version = isset($meta['version'][0]) ? $meta['version'][0] : 'N/A';
                        $status = get_post_status();
                        $views = get_post_meta(get_the_ID(), 'product_views_count', true);
                        $sales_count = isset($meta['sales_count'][0]) ? $meta['sales_count'][0] : '0';
            ?>
            <div class="product-card">
                <h2><?php the_title(); ?></h2>
                <p><strong>Category:</strong> <?php echo $category; ?></p>
                <p><strong>Price:</strong> <?php echo $price; ?></p>
                <p><strong>Version:</strong> <?php echo $version; ?></p>
                <p><strong>Status:</strong> <?php echo ucfirst($status); ?></p>
                <p><strong>Views:</strong> <?php echo $views; ?></p>
                <p><strong>Sales:</strong> <?php echo $sales_count; ?></p>
                <?php if ($status != 'pending') : ?>
                    <a href="<?php echo esc_url(home_url('/upload/?product_id=' . get_the_ID())); ?>" class="upload-link">Upload Next Version</a>
                <?php endif; ?>
            </div>
            <?php
                    }
                endwhile;
            else :
            ?>
            <p>No products found.</p>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
<div>
<?php get_footer(); ?>
