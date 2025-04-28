<?php
/* Template Name: Thank You Page */

get_header();

// Get the current user
$user_id = get_current_user_id();

// Get the order ID, product IDs, total price, and timestamp from the URL
$order_id = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';
$product_ids_param = isset($_GET['product_ids']) ? sanitize_text_field($_GET['product_ids']) : '';
$total_price = isset($_GET['total_price']) ? sanitize_text_field($_GET['total_price']) : '';
$timestamp = isset($_GET['timestamp']) ? intval($_GET['timestamp']) : 0;

// Call the function to handle thank-you page logic
$response = handle_thank_you_page($order_id, $product_ids_param, $total_price, $timestamp, $user_id);

if ($response['error']) {
    echo '<div class="thank-you-page" id="site-width">';
    echo '<h1 class="thank-you-page__header">Error</h1>';
    echo '<p class="thank-you-page__intro">' . esc_html($response['message']) . '</p>';
    echo '<a href="' . esc_url(home_url('/contact')) . '" class="thank-you-page__contact-support-button">Contact Support</a>';
    echo '</div>';
    get_footer();
    exit;
}

?>

<div class="thank-you-page">
    <h1 class="thank-you-page__header">Thank you for your purchase!</h1>
    <p class="thank-you-page__intro">Your payment was successful. Below are the details of your order:</p>

    <!-- Display the Order ID -->
    <?php if ($response['order_id']): ?>
        <p class="thank-you-page__order-id"><strong>Order ID:</strong> <?php echo esc_html($response['order_id']); ?></p>
    <?php else: ?>
        <p class="thank-you-page__order-id"><strong>Order ID not available.</strong></p>
    <?php endif; ?>

    <!-- Display the Product IDs (for debugging) -->
    <p class="thank-you-page__product-ids"><strong>Product IDs:</strong> <?php echo esc_html($response['product_ids_param']); ?></p>

    <!-- Display the Total Price -->
    <p class="thank-you-page__total-price"><strong>Total Price:</strong> $<?php echo esc_html($response['total_price']); ?></p>

    <p class="thank-you-page__item-heading">Below are the files you purchased:</p>

    <div id="download-links" class="thank-you-page__download-links">
        <?php if (!empty($response['purchased_items'])): ?>
            <?php foreach ($response['purchased_items'] as $item): ?>
                <?php if (!empty($item['name']) && !empty($item['file_url'])): ?>
                    <div class="thank-you-page__item-details">
                        <p class="thank-you-page__product-name">
                            <strong>Product:</strong> <?php echo esc_html($item['name']); ?> 
                            <!-- Securely pass the file URL and ensure valid download link -->
                            <a href="<?php echo esc_url($item['file_url']); ?>" download class="thank-you-page__download-link" 
                               id="download-link-<?php echo esc_attr($item['id']); ?>">
                               Download File
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="thank-you-page__no-items">No items were purchased or items could not be retrieved.</p>
        <?php endif; ?>
    </div>

    <a href="<?php echo esc_url(home_url('/contact')); ?>" class="thank-you-page__contact-support-button">Contact Support</a>
</div>

<?php
get_footer();
?>


