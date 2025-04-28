<?php
/* Template Name: Payment Details List */

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


get_header();

$current_user_id = get_current_user_id();

// Fetch user payment details
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

// Display user payment details
?>

<div class="payment-details" id="site-width">
<h2 >Your Saved Payment Details</h2>
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
 
<!-- Edit Button -->
<a href="<?php echo esc_url(home_url('/seller-earnings')); ?>" class="edit-button">Edit Payment Details</a>
</div> 
<style>
.payment-details {
    max-width: 800px; /* Set a maximum width for the container */
    margin: 10px auto; /* Center the container */
    padding: 10px; /* Add some padding around the content */
    background-color: #f9f9f9; /* Light background for better contrast */
}

.payment-details h2 {
    padding: 0 10px;
}

.payment-details-title {
    color: #333;
    margin: 20px 0; /* Adjust margin */
    font-size: 24px; /* Larger font for title */
    text-align: center; /* Center the title */
}

.payment-details-list {
    list-style-type: none; /* Remove bullet points */
    padding: 0;
    background: white;
    margin: 20px 0; /* Margin above and below the list */
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.payment-details-list li {
    display: flex;
    justify-content: space-between; /* Space between label and detail */
    align-items: center; /* Center vertically */
    margin: 10px 0;
    padding: 10px 15px; /* Increased padding for comfort */
    border-bottom: 1px solid #e0e0e0; /* Light border for separation */
}

.payment-details-list li:last-child {
    border-bottom: none; /* Remove border for last item */
}

.payment-details-list strong {
    color: #4CAF50; /* Highlight strong text */
}

.edit-button {
    display: inline-block;
    padding: 10px 15px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
    text-align: center; /* Center the button text */
}

.edit-button:hover {
    background-color: #45a049; /* Darker shade on hover */
}

/* Responsive design */
@media (max-width: 600px) {
    #site-width {
        margin: 10px; /* Reduce margin on small screens */
        padding: 10px; /* Adjust padding */
    }

    .payment-details-list {
        margin: 0; /* Remove margin for small screens */
    }

    .payment-details-list li {
        flex-direction: column; /* Stack label and detail */
        align-items: flex-start; /* Align items to the left */
    }

    .edit-button {
        width: 100%; /* Full width button on small screens */
    }
}


</style>
<?php
get_footer();
?>
