<?php
/* Template Name: Payment Details */

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


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

get_header();

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

<script>
// Toggle PayPal or Bank Transfer info based on selection
document.getElementById('payout-method').addEventListener('change', function() {
    const payoutMethod = this.value;
    if (payoutMethod === 'paypal') {
        document.getElementById('paypal-info').style.display = 'block';
        document.getElementById('bank-info').style.display = 'none';
    } else {
        document.getElementById('paypal-info').style.display = 'none';
        document.getElementById('bank-info').style.display = 'block';
    }
});

// Trigger the correct payout method display on page load
document.addEventListener('DOMContentLoaded', function() {
    const payoutMethod = document.getElementById('payout-method').value;
    if (payoutMethod === 'paypal') {
        document.getElementById('paypal-info').style.display = 'block';
        document.getElementById('bank-info').style.display = 'none';
    } else {
        document.getElementById('paypal-info').style.display = 'none';
        document.getElementById('bank-info').style.display = 'block';
    }
});

// Edit button to toggle between form and saved details
document.getElementById('edit-details').addEventListener('click', function() {
    document.getElementById('payment-form').style.display = 'block'; // Show form
    document.getElementById('saved-details').style.display = 'none'; // Hide saved details
});
</script>

<style>
.edit-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 4px;
    cursor: pointer;
}

.edit-button:hover {
    background-color: #45a049;
}
</style>

<?php get_footer(); ?>
