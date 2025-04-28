<?php
/* Template Name: Cart Page */
get_header();
?>

<div id="site-width">
    <div class="cart-container" id="cart-container">
    <div class="header-container">
        <h1 class="header-title">Your Cart</h1>
    </div>
    
    <div class="cart-page">
        <div class="cart-items-container">
            <div id="cart-items" class="cart-items"></div>
        </div>
        
        <div class="total-price-container">
            <div class="total-price" id="all-price">
                <p>Total Price: <span id="total-price" class="total-price">$0.00</span></p>
                <button id="buy-now-button" class="buy-now-button">Buy Now</button>
            </div>
            <!-- Placeholder for the message -->
            <div id="buy-now-message" class="buy-now-message">Please select at least one item to buy.</div>
        </div>
    </div>
     </div>

    <!-- Empty cart message -->
    <div id="empty-cart-message" class="empty-cart-message" style="display: none;">
        <h1>Your Cart is Empty</h1>
        <br>Browse through our <a href="<?php echo esc_url(home_url('/products/?orderby=sales_count&order=asc')); ?>" class="empty-cart-link">popular items</a> or check out the <a href="<?php echo esc_url(home_url('/products/?orderby=date&order=desc')); ?>" class="empty-cart-link">newest arrivals</a>!
    </div>
</div>

<?php
get_footer();
?>
