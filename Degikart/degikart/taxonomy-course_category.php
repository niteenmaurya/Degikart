<?php

get_header();

// Get current page number
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Sorting parameters from URL (with default values)
$orderby = isset($_GET['orderby']) && $_GET['orderby'] !== 'undefined' ? $_GET['orderby'] : 'price'; // Default sort by price
$order = isset($_GET['order']) && $_GET['order'] !== 'undefined' ? $_GET['order'] : 'asc'; // Default order: ascending
$meta_key = 'regular_price'; // Default meta_key for price sorting

// Setting the meta_key based on selected 'orderby' filter
if ($orderby == 'sales') {
    $meta_key = 'sales_count'; // Sorting by sales count (for Trending)
} elseif ($orderby == 'rating') {
    $meta_key = '_wc_average_rating'; // Sorting by rating (for Best Rated)
} elseif ($orderby == 'date') {
    // If 'orderby' is 'date', we don't need meta_key as we're sorting by post date
    $meta_key = '';
}
// Get current taxonomy term
$term = get_queried_object();
 
// Get the total count of published posts in 'template-kits' category
$args_count = array(
    'post_type' => 'course', // Custom post type for products
    'tax_query' => array(
        array(
            'taxonomy' => 'course_category', // Custom taxonomy name
            'field'    => 'slug',
            'terms'    => $term->slug, 
        ),
    ),
);
// Get the total count of published products
$product_count = wp_count_posts('course')->publish;

// Custom query to fetch posts with pagination and sorting
$args = array(
    'post_type' => 'course', // Custom post type for products
    'posts_per_page' => 6, // 12 items per page
    'paged' => $paged, // Pagination support
    'meta_key' => $meta_key, // Sorting by price or sales, etc.
    'orderby' => $meta_key ? 'meta_value_num' : 'date', // Sorting by meta_value_num if using meta_key, otherwise by date
    'order' => $order, // Order (ascending or descending)
    'tax_query' => array(
        array(
            'taxonomy' => 'course_category', // Custom taxonomy name
            'field'    => 'slug',
            'terms'    => $term->slug, 
        ),
    ),
);


$custom_query = new WP_Query($args);
?>

<div id="site-width">
<?php degikart_custom_card(); ?>

    <main class="site-content-area" id="content">

 

        
        <div class="sorting-buttons">
                <div class="total-products-count">
                    <p ><?php echo sprintf(__('Items: %d', 'degikart'), $product_count); ?></p>
                </div>
                <div class="filter-toogle">
                    <select id="sort-options" onchange="sortProducts()">
                        <option value="" disabled selected><?php _e('Filter', 'degikart'); ?></option>
                        <option value="sales-asc" <?php echo ($orderby == 'sales' && $order == 'asc') ? 'selected' : ''; ?>><?php _e('Sales: Low to High', 'degikart'); ?></option>
                        <option value="sales-desc" <?php echo ($orderby == 'sales' && $order == 'desc') ? 'selected' : ''; ?>><?php _e('Sales: High to Low', 'degikart'); ?></option>
                        <option value="price-asc" <?php echo ($orderby == 'price' && $order == 'asc') ? 'selected' : ''; ?>><?php _e('Price: Low to High', 'degikart'); ?></option>
                        <option value="price-desc" <?php echo ($orderby == 'price' && $order == 'desc') ? 'selected' : ''; ?>><?php _e('Price: High to Low', 'degikart'); ?></option>
                        <option value="rating-desc" <?php echo ($orderby == 'rating' && $order == 'desc') ? 'selected' : ''; ?>><?php _e('Best Rated', 'degikart'); ?></option>
                        <option value="newest" <?php echo ($orderby == 'date' && $order == 'desc') ? 'selected' : ''; ?>><?php _e('Newest Products', 'degikart'); ?></option>
                    </select>
                </div>
                
            </div>
      
        <div class="site-cont" id="cont-site">
            <?php if ($custom_query->have_posts()) : ?>
                <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                    <?php 
                $post_type = get_post_type();
                $meta = get_post_meta(get_the_ID());
                $categories = wp_get_post_terms(get_the_ID(), 'product-category', array('fields' => 'names'));

                $regular_price = isset($meta['regular_price'][0]) ? esc_html($meta['regular_price'][0]) : '0';
                $extended_price = isset($meta['extended_price'][0]) ? esc_html($meta['extended_price'][0]) : '0';
               
                $sales_count = isset($meta['sales_count'][0]) ? esc_html($meta['sales_count'][0]) : '0';
                $demo_url = isset($meta['demo_url'][0]) ? esc_url($meta['demo_url'][0]) : '';
                $thumbnail_url = isset($meta['thumbnail_url'][0]) ? esc_url($meta['thumbnail_url'][0]) : '';
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
                                        <span class="author vcard" itemprop="author" itemtype="https://schema.org/Person" itemscope="">
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
                                <form class="cart-btn" onsubmit="addToCart(event, <?php echo get_the_ID(); ?>, '<?php echo $regular_price; ?>', '<?php echo $theme_name; ?>', '<?php echo $author_name; ?>')">
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
            <?php else : ?>
                <p><?php _e('No products found.', 'degikart'); ?></p>
            <?php endif; ?>

         
            <div id="popup-notification" class="popup-notification">
            <p class="notice" id="popup-notice-message" 
       data-item-added="<?php _e('Item added to your cart', 'degikart'); ?>"
       data-item-already-added="<?php _e('Item already added to your cart', 'degikart'); ?>">
    </p>
    <p><strong><?php _e('Theme Name:', 'degikart'); ?></strong> <span id="popup-theme-name"></span></p>
    <p><strong><?php _e('By:', 'degikart'); ?></strong> <span id="popup-author-name"></span></p>
    <p class="popup-price"><strong><?php _e('Price:', 'degikart'); ?></strong><b>$<span id="popup-price" class="highlight-price"></span></b></p>

    <!-- License Selection (Only Regular License now) -->
    <p><strong><?php _e('License:', 'degikart'); ?></strong> 
    <?php _e('Regular License', 'degikart'); ?>
</p>

 

    <div class="popup-buttons">
        <button class="left-button" onclick="closePopup()"><?php _e('Keep Browsing', 'degikart'); ?></button>
        <button class="right-button" onclick="goToCheckout()"><?php _e('Go to Checkout', 'degikart'); ?></button>
    </div>
</div>

    </main>
       <!-- Pagination -->
       <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $custom_query->max_num_pages
                ));
                ?>
            </div>
            
</div>

  <script>
    function updatePopupPrice() {
    const licenseSelect = document.getElementById('popup-license');
   
    const priceSpan = document.getElementById('popup-price');

    // Regular License price (from the dropdown)
    const licensePrice = parseFloat(licenseSelect.selectedOptions[0].getAttribute('data-price'));

    // Additional support price for the 12 months checkbox
   

    // Base price could be passed when the product is added to the cart
    const basePrice = parseFloat(priceSpan.getAttribute('data-base-price')) || 0;

    // Total price calculation
    const totalPrice = basePrice + licensePrice;

    // Update the price in the popup
    priceSpan.textContent = totalPrice.toFixed(2); // Adjust to 2 decimal places
}

// Function to update the cart item count
function updateCartCount() {
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    var cartItemCount = cartItems.length;

    // Update the cart item count displayed in the cart
    document.getElementById('cart-item-count').innerText = cartItemCount;
}

// Function to add an item to the cart
function addToCart(event, productId, regularPrice, themeName, authorName) {
    event.preventDefault(); // Prevent the form from submitting normally

    // Get the cart items from localStorage
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // Check if the item already exists in the cart (same productId, license, and support type)
    var existingItem = cartItems.find(function(item) {
        return item.product_id === productId;
    });

    var popupMessage = '';
    if (existingItem) {
        // Item already in cart
        popupMessage = 'already_added';
    } else {
        // Item not in cart, add it
        popupMessage = 'added';

        // Create a unique ID for the new item
        var uniqueId = productId + '-' + new Date().getTime();

        // Add the new item to the cart
        cartItems.push({
            id: uniqueId,
            product_id: productId,
            price: regularPrice,
            theme_name: themeName,
            author_name: authorName,
            license: 'Course',
          
        });

        // Store the updated cart in localStorage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }

    // Show the popup with the appropriate message
    showPopup(themeName, authorName, regularPrice, popupMessage);

    // Update the cart item count immediately after adding the item
    updateCartCount();
}

// Function to show the popup with the appropriate message
function showPopup(themeName, authorName, regularPrice, popupMessage) {
     document.getElementById('popup-theme-name').innerText = themeName;
    document.getElementById('popup-author-name').innerText = authorName;
    document.getElementById('popup-price').innerText = regularPrice;

    // Set data attributes for price calculations
    document.getElementById('popup-price').setAttribute('data-regular-price', regularPrice);


    // Get the popup notice element
    var popupNotice = document.getElementById('popup-notice-message');

    // Choose the message based on whether the item was added or already added
    var message = popupMessage === 'added' 
        ? 'Item added to the cart!' 
        : 'This item is already in your cart.';

    // Update the notice message
    popupNotice.innerText = message;

    // Show the popup
    var popup = document.getElementById('popup-notification');
    popup.style.display = 'block';
}

// Function to close the popup
function closePopup() {
    var popup = document.getElementById('popup-notification');
    popup.style.display = 'none';
}

function goToCheckout() {
    // Get the current selected item (the last added item from localStorage)
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // We will use only the latest item to replace the previous cart items
    var selectedItem = cartItems[cartItems.length - 1]; // Assuming the last added item is the current one

    if (!selectedItem) {
        console.error("No item found in the cart.");
        return; // Exit if no item is in the cart
    }

    // Now clear any previous cart data to ensure only the latest item is in the cart
    localStorage.removeItem('selectedCartItems');
    localStorage.removeItem('totalPrice');

    // Generate a unique ID for the item (using the current timestamp)
    var uniqueId = new Date().getTime();  // Unique ID based on the current timestamp

    // Use the actual product ID from the selected item (do not fallback to 87, get it dynamically)
    var productId = selectedItem.product_id;  // This should already be set correctly in your cart item
    if (!productId) {
        console.error("Product ID is missing.");
        return; // Exit if the product_id is missing
    }

    var combinedId = productId + '-' + uniqueId;  // Combine product ID and unique ID

    // Create an array with only the selected item (replacing any previous items)
    var selectedItems = [{
        id: combinedId, // Add combined ID (productId-uniqueId)
        theme_name: selectedItem.theme_name,
        price: selectedItem.price,
    }];

    // Save the selected items in localStorage
    localStorage.setItem('selectedCartItems', JSON.stringify(selectedItems));

    // Calculate the total price (for now, we assume the total price is just the price of the selected item)
    var totalPrice = parseFloat(selectedItem.price);

    // Save the total price in localStorage
    localStorage.setItem('totalPrice', totalPrice.toFixed(2));

    // Redirect to the payment page with the product ID and unique ID in the URL
    var checkoutUrl = "<?php echo esc_url(home_url('/checkout')); ?>";  // Checkout URL from WordPress
    var productIds = selectedItems.map(item => item.id).join(',');  // Join the IDs if there are multiple items

    // Redirect to the checkout page, passing the combined ID in the URL
    window.location.href = checkoutUrl + '?product_ids=' + encodeURIComponent(productIds);
}


 
    function sortProducts() {
        const select = document.getElementById("sort-options");
        const value = select.value;
        let orderby = 'price';
        let order = 'asc';

        if (value === 'sales-asc') {
            orderby = 'sales_count';
            order = 'desc';
        } else if (value === 'sales-desc') {
            orderby = 'sales_count';
            order = 'asc';
        } else if (value === 'price-asc') {
            orderby = 'price';
            order = 'asc';
        } else if (value === 'price-desc') {
            orderby = 'price';
            order = 'desc';
        } else if (value === 'rating-desc') {
            orderby = 'rating';
            order = 'desc';
        } else if (value === 'newest') {
            orderby = 'date';
            order = 'desc';
        }

        // Update the URL with the new parameters
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('orderby', orderby);
        newUrl.searchParams.set('order', order);
        window.location.href = newUrl.toString();
    }


 </script>
<?php get_footer(); ?>
