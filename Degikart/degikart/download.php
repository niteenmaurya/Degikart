<?php
/* Template Name: Download History Page */
get_header();

// Get the current user
$user_id = get_current_user_id();

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Get the user's purchase history
$purchase_history = get_user_meta($user_id, '_purchase_history', true);

// Check if the user has a purchase history
if ($purchase_history && is_array($purchase_history)) :
    $total_items = 0; // Initialize total items count
    $order_count = 0; // Initialize the row number count

    ?>
 
<div class="dwnld-hst" id="site-width">
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
                                echo '<p>These are your products, and they have been purchased by a buyer. You can view the buyer details and the time of purchase here.</p>';
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

get_footer();
?>
