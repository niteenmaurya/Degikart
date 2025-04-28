<?php
/* Template Name: Payment Page */

// Check if user is logged in, otherwise redirect to login
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

get_header();
?>

<div id="site-width">
    <div id="payment-page">
        <h1>Payment Method</h1>
        <div id="selected-items" style="display:none;"></div>
        <div class="paypal-button-wrapper">
            <div id="paypal-button-container"></div>
        </div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AU0_Gki1M2NO6QoEikX4d1Z2i1DXYZJWYeExzRVa4qPNPJArxvRcLH7adNzBohb-wYmH5OI10lghbFhL&currency=USD"></script>

<script>
function loadSelectedItems() {
    // Retrieve the selected items and total price from localStorage
    var selectedItems = JSON.parse(localStorage.getItem('selectedCartItems')) || [];
    var totalPrice = localStorage.getItem('totalPrice') || '0.00'; // Retrieve total price from localStorage

    // Map the items to HTML structure for cart display
    var selectedItemsHtml = selectedItems.map(function(item, index) {
        return '<div class="cart-item post-card" id="item-' + index + '">' +
            '<div class="post-card-header">' +
            '<div class="item-title">Item ' + (index + 1) + ': ' + item.theme_name + '</div>' +
            '<div class="item-price">$' + item.price + '</div>' +
            '</div>' +
            '<div class="post-card-body">' +
            '<p class="item-license">License: ' + item.license + '</p>' +
            '</div>' +
            '</div>';
    }).join('');

    // Display the selected items and the total price
    document.getElementById('selected-items').innerHTML = selectedItemsHtml;
    document.getElementById('selected-items').insertAdjacentHTML('beforeend', '<div class="total-price">Total Price: $' + totalPrice + '</div>');
    document.getElementById('selected-items').style.display = 'none'; // Show selected items section
}

paypal.Buttons({
    createOrder: function(data, actions) {
        var totalPrice = parseFloat(localStorage.getItem('totalPrice')).toFixed(2);

        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: totalPrice
                }
            }]
        });
    },

    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // After successful payment, retrieve selected items
            var selectedItems = JSON.parse(localStorage.getItem('selectedCartItems')) || [];
            var productIds = selectedItems.map(function(item) {
                return item.id;
            }).join(',');

            var totalPrice = parseFloat(localStorage.getItem('totalPrice')).toFixed(2);
            var timestamp = Math.floor(Date.now() / 1000);

            // Send the data to WordPress to save the purchase history
            var nonce = '<?php echo wp_create_nonce('save_purchase_history_nonce'); ?>'; // Secure nonce
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Redirect to the "Thank You" page with order details
                    window.location.href = "<?php echo esc_url(home_url('/thank-you')); ?>?order_id=" + details.id + "&product_ids=" + productIds + "&total_price=" + totalPrice + "&timestamp=" + timestamp;
                }
            };
            xhr.send("action=save_purchase_history&nonce=" + nonce + "&order_id=" + details.id + "&product_ids=" + productIds + "&total_price=" + totalPrice);
        });
    },

    onError: function(err) {
        console.error(err);
        alert('An error occurred during the transaction. Please try again.');
    }
}).render('#paypal-button-container'); // Render the PayPal button

document.addEventListener('DOMContentLoaded', function() {
    loadSelectedItems();
});
</script>

<?php get_footer(); ?>
