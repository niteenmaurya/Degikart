<?php
/* Template Name: Seller Page */
get_header();
?>

<div id="site-width">
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

 
<style>

#seller-page {
    background-color: #f9f9f9;
    padding: 10px 0;
}

h1, h2 {
    text-align: center;
    font-size: 2em;
    margin-bottom: 20px;
}

#sales-info {
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

#product-sales-list {
    margin-top: 20px;
}
/* Add a scrollable area for the table */
.sales-table-wrapper {
    max-height: 400px; /* Set maximum height of the table wrapper */
    overflow-y: auto;  /* Add vertical scroll */
    margin-bottom: 20px; /* Add space below the table */
}

.sales-table {
    width: 100%; /* Make sure table takes the full width of the container */
    border-collapse: collapse; /* Ensures that borders are collapsed */
}

.sales-table th, .sales-table td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd; /* Table cell borders */
}

.sales-table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.sales-table td {
    background-color: #fff;
}

  /* Center the link below the table */
  .commission-info-link {
        text-align: center; /* Center the text */
        margin-top: 20px;  /* Add some space above the link */
    }

    .commission-info-link a {
        color: #007bff; /* Blue color for the link */
        font-size: 16px; /* Set the font size */
        text-decoration: none; /* Remove underline */
        font-weight: bold; /* Make the link bold */
    }

    .commission-info-link a:hover {
        text-decoration: underline; /* Underline the link on hover */
    }

</style>