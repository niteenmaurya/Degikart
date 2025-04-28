<?php
/*
Template Name: Custom Dashboard Page
*/

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
get_header();
?>
<div id="site-width" >
    <div id="fhgbta-gr-dashboard-container" class="fhgbta-gr-dashboard-container" style="display: block;">
        <!-- Sidebar (now top row) -->
        <div id="fhgbta-gr-sidebar" class="fhgbta-gr-sidebar">
            <ul class="fhgbta-gr-dashboard-tabs" style="display: flex; list-style: none; padding: 0; margin: 0;">
                <li><a href="#" id="fhgbta-gr-dashboard-tab" class="fhgbta-gr-tab">Dashboard</a></li>
                <li><a href="#" id="fhgbta-gr-profile-tab" class="fhgbta-gr-tab">Profile</a></li>
                <li><a href="#" id="fhgbta-gr-favourite-tab" class="fhgbta-gr-tab">Favourite</a></li>
                  <li><a href="#" id="fhgbta-gr-earnings-tab" class="fhgbta-gr-tab">Earning</a></li>
                <li><a href="#" id="fhgbta-gr-payment-tab" class="fhgbta-gr-tab">Payment</a></li>
                <li><a href="#" id="fhgbta-gr-downloads-tab" class="fhgbta-gr-tab">Downloads</a></li>
                <li><a href="#" id="fhgbta-gr-upload-tab" class="fhgbta-gr-tab">Upload</a></li>
                <li><a href="#" id="fhgbta-gr-uploadeditems-tab" class="fhgbta-gr-tab">Uploaded Items</a></li>
            </ul>
        </div>
        <!-- Main Content Area (below sidebar) -->
        <div id="fhgbta-gr-content" class="fhgbta-gr-content">
            <!-- Dynamic Sections -->
            <div id="fhgbta-gr-dashboard" class="fhgbta-gr-dashboard-section">
                <div class="main-dash">

               <h2>Dashboard Content</h2>
                <p>This is the Dashboard section.</p>
                </div>
            </div>
            <div id="fhgbta-gr-profile" class="fhgbta-gr-dashboard-section" style="display: none;">
                <div class="profile-container">
                    <?php echo do_shortcode('[custom_profile]'); ?>
                </div>
            </div>
        </div>

        <div id="fhgbta-gr-favourite" class="fhgbta-gr-dashboard-section" style="display: none;">
            
        



        <?php
 

 // Get the current user ID
 $user_id = get_current_user_id();
 
 // Ensure the user is logged in
 if ($user_id == 0) {
     echo '<p>You need to be logged in to view your favorites.</p>';
     get_footer();
     return;
 }
 
 // Get the user's favorite posts
 $favorites = get_user_meta($user_id, 'favorites', true);
 
 // Ensure $favorites is always an array, even if empty
 if (!is_array($favorites)) {
     $favorites = [];
 }
 
 if (!empty($favorites)) :
     $args = array(
         'post_type' => ['plugin', 'blogger', 'wordpress', 'ecommerce'], // Custom post types
         'post__in' => $favorites, // Get posts with these IDs
         'posts_per_page' => -1, // Show all favorites
         'orderby' => 'post__in', // Ensure the order of posts is the same as in the favorites array
     );
 
     $query = new WP_Query($args);
 
     if ($query->have_posts()) :
         echo '<div class="item-new-products">';
         echo '<div class="title-des">';
         echo '<h2 class="weekly-best-courses-title">' . __('Your Favorite Posts', 'degikart') . '</h2>';
         echo '<p class="best-courses-description">' . __('Check out your most loved posts!', 'degikart') . '</p>';
         echo '</div>';
 
         echo '<div class="card-container">'; // Start the card container
 
         while ($query->have_posts()) : $query->the_post();
 
             // Get post meta
             $post_meta = get_post_meta(get_the_ID());
             $categories = wp_get_post_terms(get_the_ID(), 'product-category', ['fields' => 'names']);
 
             // Get regular price, extended price, and other metadata (if applicable)
             $regular_price = isset($post_meta['regular_price'][0]) ? esc_html($post_meta['regular_price'][0]) : '0';
             $extended_price = isset($post_meta['extended_price'][0]) ? esc_html($post_meta['extended_price'][0]) : '0';
             $support_price_12_months = isset($post_meta['support_price'][0]) ? esc_html($post_meta['support_price'][0]) : '0';
             $demo_url = isset($post_meta['demo_url'][0]) ? esc_url($post_meta['demo_url'][0]) : '';
             $thumbnail_url = has_post_thumbnail() ? get_the_post_thumbnail_url() : '';
 
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
                 
     
                 <div class="sales-preview">
                 <p class="main-price">$<?php echo $regular_price; ?></p>
 
               
                     <div class="live-cart">
                     <?php
    $user_id = get_current_user_id(); // Get the current user's ID
    $post_id = get_the_ID(); // Get the current post ID

    // Check if this post is in the user's favorites
    $favorites = get_user_meta($user_id, 'favorites', true);
    $is_favorite = is_array($favorites) && in_array($post_id, $favorites); // True if post is already favorited
    ?>
    <!-- Heart Icon for adding/removing favorites -->
    <button class="favorite-btn" data-post-id="<?php echo $post_id; ?>" 
            data-user-id="<?php echo $user_id; ?>" 
            data-action="<?php echo $is_favorite ? 'remove' : 'add'; ?>" 
            data-favorite-status="<?php echo $is_favorite ? 'true' : 'false'; ?>"  <!-- Add this data attribute -->
    
        <span class="heart-icon <?php echo $is_favorite ? 'favorited' : ''; ?>" 
               style="color: <?php echo $is_favorite ? 'red' : 'gray'; ?>;">&#10084;</span>
    </button>
                         <p class="live-preview"><a href="<?php echo $demo_url; ?>"><?php _e('View Demo', 'degikart'); ?></a></p>
                     </div>
                 </div>
             </div>
         </article>   <!-- End of Product Card -->
 
 <?php endwhile; ?>
 </div>
  
 </div> <!-- End of card container -->
 <?php wp_reset_postdata(); ?>
 <?php else : ?>
 <p>You have no favorite posts yet.</p>
 <?php endif;
 else :
 echo '<p>You have no favorite posts yet.</p>';
 endif;
 
 ?>
 <script>jQuery(document).ready(function($) {
     $('.favorite-btn').on('click', function() {
         var post_id = $(this).data('post-id');
         var user_id = $(this).data('user-id');
         var action = $(this).data('action');
         
         var $button = $(this);
         var $heartIcon = $button.find('.heart-icon');
         
         // Send AJAX request to add/remove favorite
         $.ajax({
             url: '<?php echo admin_url('admin-ajax.php'); ?>',
             method: 'POST',
             data: {
                 action: 'toggle_favorite', // AJAX action hook
                 post_id: post_id,
                 user_id: user_id,
                 action_type: action
             },
             success: function(response) {
                 if (response.success) {
                     // Toggle heart icon class and action
                     var new_action = (action === 'add') ? 'remove' : 'add';
                     var new_color = (new_action === 'add') ? 'gray' : 'red';
                     $heartIcon.toggleClass('favorited');
                     $heartIcon.css('color', new_color); // Change color of heart
                     $button.data('action', new_action); // Toggle action type
                     
                     // Update the favorite count in the header
                     var $favoriteCount = $('.favorite-count');
                     var count = parseInt($favoriteCount.text());
                     count = (new_action === 'add') ? count - 1 : count + 1;
                     $favoriteCount.text(count);
                 }
             }
         });
     });
 });
 
 </script>
 


        </div>

        <div id="fhgbta-gr-earnings" class="fhgbta-gr-dashboard-section" style="display: none;">
            <div id="seller-page">
                <h1>Your Sales</h1>
                <!-- Seller's Products and Sales Info -->
                 <div id="sales-info">
                    <h2>Sold Products</h2>
            
            <!-- Add wrapper div for scrollable table -->
            <div class="sales-table-wrapper">
                <div id="product-sales-list">
                    <?php
                    // Get current user's ID (the seller)
                    $current_user_id = get_current_user_id();

                    if (!$current_user_id) {
                        echo '<p>Please log in to view your earnings.</p>';
                        get_footer();
                        exit;
                    }

                    // Get the current month and year
                    $current_month = date('F'); // e.g., "November"
                    $current_year = date('Y');  // e.g., "2024"

                    // Fetch the seller's earnings from user meta
                    $monthly_earnings = get_user_meta($current_user_id, '_monthly_earnings_' . $current_year . '_' . $current_month, true); // Monthly earnings
                    $alltime_earnings = get_user_meta($current_user_id, '_alltime_earnings', true); // All-time earnings

                    if (!$monthly_earnings) $monthly_earnings = 0; // Initialize if no monthly earnings
                    if (!$alltime_earnings) $alltime_earnings = 0; // Initialize if no all-time earnings

                    // Fetch the seller's sales records (stored in user meta)
                    $seller_earnings = get_user_meta($current_user_id, '_seller_earnings', true);

                    if ($seller_earnings) {
                        // Start the table
                        echo '<table class="sales-table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Products</th>';
                        echo '<th>Total Sale Amount</th>';
                        echo '<th>Order ID</th>';
                        echo '<th>Last Buyer ID</th>';
                        echo '<th>Sold on</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        $total_earnings = 0; // Initialize total earnings for this session
                        $total_commission = 0; // Initialize total commission

                        foreach ($seller_earnings as $earning) {
                            $product_ids = explode(',', $earning['product_ids']); // Get product IDs
                            $product_titles = ''; // Initialize variable to hold product titles
                            $total_sale_amount = floatval($earning['total_sale_amount']); // Total sale amount for this order
                            $order_id = esc_html($earning['order_id']);
                            $last_buyer_id = esc_html($earning['last_buyer_id']);
                            $purchase_time = esc_html($earning['purchase_time']);

                            // Loop through product IDs to generate product titles with links
                            foreach ($product_ids as $product_id) {
                                $product = get_post($product_id); // Get the product post object
                                if ($product) {
                                    $product_title = esc_html($product->post_title);
                                    $product_url = get_permalink($product->ID); // Get product's URL
                                    $product_titles .= '<p><a href="' . $product_url . '" target="_blank">' . $product_title . '</a></p>'; // Each product title in a new paragraph
                                }
                            }

                            // Commission (35%)
                            $commission_rate = 0.35; // 35% commission
                            $commission = $total_sale_amount * $commission_rate; // Calculate commission

                            // Add to totals
                            $total_earnings += $total_sale_amount;
                            $total_commission += $commission;

                            // Update monthly earnings and all-time earnings
                            $monthly_earnings += $total_sale_amount - $commission; // Add earnings after commission for this month
                            $alltime_earnings += $total_sale_amount - $commission; // Add to all-time earnings

                            // Check if values are set before displaying them
                            if ($product_titles && $total_sale_amount && $order_id && $purchase_time) {
                                echo '<tr>';
                                echo '<td>' . $product_titles . '</td>';
                                echo '<td>$' . number_format($total_sale_amount, 2) . '</td>';
                                echo '<td>' . $order_id . '</td>';
                                echo '<td>' . $last_buyer_id . '</td>';
                                echo '<td>' . $purchase_time . '</td>';
                                echo '</tr>';
                            } else {
                                echo '<tr><td colspan="5">No data available for this sale.</td></tr>';
                            }
                        }

                        // Update the user meta with the latest monthly and all-time earnings
                        update_user_meta($current_user_id, '_monthly_earnings_' . $current_year . '_' . $current_month, $monthly_earnings);
                        update_user_meta($current_user_id, '_alltime_earnings', $alltime_earnings);

                        // Display the monthly earnings
                        echo '<tr>';
                        echo '<td colspan="4"><strong>Total Earnings This Month (' . $current_month . ')</strong></td>';
                        echo '<td><strong>$' . number_format($monthly_earnings, 2) . '</strong></td>';
                        echo '</tr>';

                        // Display the total commission
                        echo '<tr>';
                        echo '<td colspan="4"><strong>Total Commission (35%)</strong></td>';
                        echo '<td><strong>-$' . number_format($total_commission, 2) . '</strong></td>';
                        echo '</tr>';

                        // Display the sales earnings after commission
                        $sales_after_commission = $total_earnings - $total_commission;
                        echo '<tr>';
                        echo '<td colspan="4"><strong>Sales earnings this month (' . $current_month . '), after associated author fees, & before taxes:</strong></td>';
                        echo '<td><strong>$' . number_format($sales_after_commission, 2) . '</strong></td>';
                        echo '</tr>';

                        // Display the all-time earnings
                        echo '<tr>';
                        echo '<td colspan="4"><strong>Total All-Time Earnings</strong></td>';
                        echo '<td><strong>$' . number_format($alltime_earnings, 2) . '</strong></td>';
                        echo '</tr>';

        
                        echo '</tbody>';
                        echo '</table>';

                        // Display link for information about commission
                        echo '<p class="commission-info-link"><a href="your-commission-info-page-url" target="_blank">Click here for more information</a></p>';


                    } else {
                        echo '<p>No sales yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>

        <div id="fhgbta-gr-payment" class="fhgbta-gr-dashboard-section" style="display: none;">
        <?php
 
// Handle the POST request for saving payment details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && check_admin_referer('payment_details_nonce')) {
    // Sanitize and save payment details
    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name = sanitize_text_field($_POST['last_name'] ?? '');
    $dob = sanitize_text_field($_POST['dob'] ?? '');
    $country = sanitize_text_field($_POST['country'] ?? '');
    $address = sanitize_text_field($_POST['address'] ?? '');
    $address2 = sanitize_text_field($_POST['address2'] ?? '');
    $city = sanitize_text_field($_POST['city'] ?? '');
    $postal_code = sanitize_text_field($_POST['postal_code'] ?? '');
    $payout_method = sanitize_text_field($_POST['payout_method'] ?? '');
    $paypal_email = sanitize_email($_POST['paypal_email'] ?? '');
    $bank_country = sanitize_text_field($_POST['bank_country'] ?? '');
    $bank_currency = sanitize_text_field($_POST['bank_currency'] ?? '');
    $routing_number = sanitize_text_field($_POST['routing_number'] ?? '');
    $account_number = sanitize_text_field($_POST['account_number'] ?? '');
    $account_holder = sanitize_text_field($_POST['account_holder'] ?? '');

    // Save the data in the user meta
    $current_user_id = get_current_user_id();
    update_user_meta($current_user_id, 'first_name', $first_name);
    update_user_meta($current_user_id, 'last_name', $last_name);
    update_user_meta($current_user_id, 'dob', $dob);
    update_user_meta($current_user_id, 'country', $country);
    update_user_meta($current_user_id, 'address', $address);
    update_user_meta($current_user_id, 'address2', $address2);
    update_user_meta($current_user_id, 'city', $city);
    update_user_meta($current_user_id, 'postal_code', $postal_code);
    update_user_meta($current_user_id, 'payout_method', $payout_method);
    update_user_meta($current_user_id, 'paypal_email', $paypal_email);
    update_user_meta($current_user_id, 'bank_country', $bank_country);
    update_user_meta($current_user_id, 'bank_currency', $bank_currency);
    update_user_meta($current_user_id, 'routing_number', $routing_number);
    update_user_meta($current_user_id, 'account_number', $account_number);
    update_user_meta($current_user_id, 'account_holder', $account_holder);

    // Data saved successfully, so fetch and display it immediately
    $saved_data = true;
}



// Fetch current user payment details (to display after saving)
$current_user_id = get_current_user_id();
$first_name = get_user_meta($current_user_id, 'first_name', true);
$last_name = get_user_meta($current_user_id, 'last_name', true);
$dob = get_user_meta($current_user_id, 'dob', true);
$country = get_user_meta($current_user_id, 'country', true);
$address = get_user_meta($current_user_id, 'address', true);
$address2 = get_user_meta($current_user_id, 'address2', true);
$city = get_user_meta($current_user_id, 'city', true);
$postal_code = get_user_meta($current_user_id, 'postal_code', true);
$payout_method = get_user_meta($current_user_id, 'payout_method', true);
$paypal_email = get_user_meta($current_user_id, 'paypal_email', true);
$bank_country = get_user_meta($current_user_id, 'bank_country', true);
$bank_currency = get_user_meta($current_user_id, 'bank_currency', true);
$routing_number = get_user_meta($current_user_id, 'routing_number', true);
$account_number = get_user_meta($current_user_id, 'account_number', true);
$account_holder = get_user_meta($current_user_id, 'account_holder', true);
?>

<div id="site-width">

    <!-- Payment Details Form -->
    <form method="POST" class="seller-details" id="payment-form" <?php echo isset($saved_data) ? 'style="display:none;"' : ''; ?>>
        <?php wp_nonce_field('payment_details_nonce'); ?>
        <h2>General Information</h2>
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo esc_attr($first_name); ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo esc_attr($last_name); ?>" required><br>

        <label>Date of Birth:</label>
        <input type="date" name="dob" value="<?php echo esc_attr($dob); ?>" required><br>

        <label>Country:</label>
        <select name="country" required>
    <option value="">Select Country</option>
    <?php
    $countries = array(
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", 
        "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", 
        "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", 
        "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", 
        "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", 
        "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", 
        "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", 
        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", 
        "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", 
        "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", 
        "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", 
        "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", 
        "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", 
        "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
        "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", 
        "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", 
        "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Macedonia", "Norway", 
        "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", 
        "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", 
        "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", 
        "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", 
        "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", 
        "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", 
        "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", 
        "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", 
        "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    );

    foreach ($countries as $country) {
        echo '<option value="' . esc_attr($country) . '" ' . selected($country, $selected_country, false) . '>' . esc_html($country) . '</option>';
    }
    ?>
</select>
<br>

        <label>Address:</label>
        <input type="text" name="address" value="<?php echo esc_attr($address); ?>" required><br>

        <label>Address 2 (optional):</label>
        <input type="text" name="address2" value="<?php echo esc_attr($address2); ?>"><br>

        <label>City:</label>
        <input type="text" name="city" value="<?php echo esc_attr($city); ?>" required><br>

        <label>Postal Code (optional):</label>
        <input type="text" name="postal_code" value="<?php echo esc_attr($postal_code); ?>"><br>

        <h2>Select Payout Method</h2>
        <label>Payout Method:</label>
        <select name="payout_method" id="payout-method" required>
            <option value="paypal" <?php selected($payout_method, 'paypal'); ?>>PayPal</option>
            <option value="bank_transfer" <?php selected($payout_method, 'bank_transfer'); ?>>Bank Transfer</option>
        </select><br>

        <!-- PayPal and Bank Information (conditionally displayed) -->
        <div id="paypal-info" style="display: none;">
            <label>PayPal Email:</label>
            <input type="email" name="paypal_email" value="<?php echo esc_attr($paypal_email); ?>"><br>
        </div>

        <div id="bank-info" style="display: none;">
            <label>Bank Account Country:</label>
            <select name="bank_country" required>
                <option value="United States" <?php selected($bank_country, 'United States'); ?>>United States</option>
            </select><br>

            <label>Routing Number:</label>
            <input type="text" name="routing_number" value="<?php echo esc_attr($routing_number); ?>" required><br>

            <label>Account Number:</label>
            <input type="text" name="account_number" value="<?php echo esc_attr($account_number); ?>" required><br>

            <label>Name of Account Holder:</label>
            <input type="text" name="account_holder" value="<?php echo esc_attr($account_holder); ?>" required><br>
        </div>

        <button type="submit">Save Payment Details</button>
    </form>

    <!-- Display Saved Payment Data -->
    <?php if (isset($saved_data) && $saved_data): ?>
        <div id="saved-details">
            <h2>Your Saved Payment Details:</h2>
            <ul class="payment-details-list">
                <li><strong>First Name:</strong> <?php echo esc_html($first_name); ?></li>
                <li><strong>Last Name:</strong> <?php echo esc_html($last_name); ?></li>
                <li><strong>Date of Birth:</strong> <?php echo esc_html($dob); ?></li>
                <li><strong>Country:</strong> <?php echo esc_html($country); ?></li>
                <li><strong>Address:</strong> <?php echo esc_html($address) . ' ' . esc_html($address2); ?></li>
                <li><strong>City:</strong> <?php echo esc_html($city); ?></li>
                <li><strong>Postal Code:</strong> <?php echo esc_html($postal_code); ?></li>
                <li><strong>Payout Method:</strong> <?php echo esc_html($payout_method); ?></li>
                <?php if ($payout_method === 'paypal'): ?>
                    <li><strong>PayPal Email:</strong> <?php echo esc_html($paypal_email); ?></li>
                <?php else: ?>
                    <li><strong>Bank Account Country:</strong> <?php echo esc_html($bank_country); ?></li>
                    <li><strong>Routing Number:</strong> <?php echo esc_html($routing_number); ?></li>
                    <li><strong>Account Number:</strong> <?php echo esc_html($account_number); ?></li>
                    <li><strong>Account Holder Name:</strong> <?php echo esc_html($account_holder); ?></li>
                <?php endif; ?>
            </ul>
            <a href="javascript:void(0);" id="edit-details" class="edit-button">Edit Payment Details</a>
        </div>
    <?php endif; ?>
</div>
</div >



        <div id="fhgbta-gr-downloads" class="fhgbta-gr-dashboard-section" style="display: none;">
        <?php
 
 

// Get the current user
$user_id = get_current_user_id();



// Get the user's purchase history
$purchase_history = get_user_meta($user_id, '_purchase_history', true);

// Check if the user has a purchase history
if ($purchase_history && is_array($purchase_history)) :
    $total_items = 0; // Initialize total items count
    $order_count = 0; // Initialize the row number count
?>
 
<div class="dwnld-hst" >
    <h1 class="dwnld-hst__title">Your Download History</h1>
    <p class="dwnld-hst__desc">Below are the files you've purchased and downloaded:</p>

    <div class="tbl-container">
        <table class="dwnld-hst__table">
            <thead>
                <tr>
                    <th>Row No.</th>
                    <th>Order ID</th>
                    <th>Product IDs</th>
                    <th>Price</th>
                    <th>Download Links</th>
                    <th>Item Count</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody id="dwnld-hst__items">
                <?php foreach ($purchase_history as $purchase) :
                    $order_count++; // Increment row number for each order
                    
                    // Ensure product_ids is a string before calling explode()
                    $productIdsString = isset($purchase['product_ids']) ? $purchase['product_ids'] : '';
                    $productIdsArray = is_string($productIdsString) ? explode(',', $productIdsString) : [];
                    
                    $productCount = count($productIdsArray); // Count products for this order
                    $total_items += $productCount; // Add to total count

                    // Get the order date
                    $order_date = isset($purchase['date']) ? date('M j, Y h:i A', strtotime($purchase['date'])) : 'N/A';
                ?>
                    <tr class="dwnld-hst__row" data-order-id="<?php echo esc_attr($purchase['order_id']); ?>">
                        <td><?php echo esc_html($order_count); ?></td>
                        <td><?php echo esc_html($purchase['order_id']); ?></td>
                        <td><?php echo esc_html(implode(', ', array_map('trim', $productIdsArray))); ?></td>
                        <td><?php echo esc_html('$' . $purchase['total_price']); ?></td>
                        <td>
                            <?php
                            // Display download links for the buyer
                            foreach ($productIdsArray as $product_id) :
                                $product_id = intval($product_id);
                                $file_url = get_post_meta($product_id, 'file_url', true);

                                if ($file_url) {
                                    echo '<a href="' . esc_url($file_url) . '" target="_blank">Download File</a><br>';
                                }
                            endforeach;

                            // Display additional download link information for the seller
                            $seller_id = get_post_field('post_author', $product_id); // Get the seller ID
                            if ($seller_id == $user_id) {
                                // This block is now empty, so nothing will be echoed
                            }                            
                            ?>
                        </td>
                        <td><?php echo $productCount; ?></td>
                        <td><?php echo esc_html($order_date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
else :
    echo '<p>No purchase history found.</p>';
endif;

 
?>

        </div>

        <div id="fhgbta-gr-upload" class="fhgbta-gr-dashboard-section" style="display: none;">
        <?php
 
?>
<div id="site-width">
<form action="" method="get" id="category-form" class="category-selection-form">
    <label for="category">Select Category:</label>
    <select id="category" name="category" required>
    <option value="" disabled selected>Select Category</option>   
        <option value="wordpress">WordPress</option>
        <option value="ecommerce">eCommerce</option>
        <option value="plugin">Plugins</option>
        <option value="blogger">Blogger</option>
        <option value="courses">Courses</option>
    </select><br>
    <input type="submit" value="Next">
  
</form>
</div>
<script>
document.getElementById('category-form').onsubmit = function() {
    var category = document.getElementById('category').value;
    if (category) {
        window.location.href = '<?php echo esc_url(home_url()); ?>/' + category + '-upload/';
    } else {
        alert('Please select a category.');
    }
    return false;
}
</script>

 


 



        <div id="fhgbta-gr-uploadeditems" class="fhgbta-gr-dashboard-section" style="display: none;">
        <?php
 
?>

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
 

        </div>
 

    </div>
</div>
 

</div></div></div>
<?php get_footer(); ?>
 