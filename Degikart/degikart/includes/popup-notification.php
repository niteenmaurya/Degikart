<div id="popup-notification" class="popup-notification">
    <p class="notice" id="popup-notice-message"
       data-item-added="<?php _e('Item added to your cart', 'degikart'); ?>"
       data-item-already-added="<?php _e('Item already added to your cart', 'degikart'); ?>">
    </p>
    <p><strong><?php _e('Theme Name:', 'degikart'); ?></strong> <span id="popup-theme-name"></span></p>
    <p><strong><?php _e('By:', 'degikart'); ?></strong> <span id="popup-author-name"></span></p>
    <p class="popup-price"><strong><?php _e('Price:', 'degikart'); ?></strong><b>$<span id="popup-price" class="highlight-price"></span></b></p>
    <p><strong><?php _e('License:', 'degikart'); ?></strong>
        <select id="popup-license" onchange="updatePopupPrice()">
            <option value="regular" data-price="10"><?php _e('Regular License', 'degikart'); ?></option>
            <option value="extended" data-price="20"><?php _e('Extended License', 'degikart'); ?></option>
        </select>
    </p>
    <p><strong><?php _e('Support:', 'degikart'); ?></strong> <span id="popup-support">6 <?php _e('months support', 'degikart'); ?></span></p>
    <label>
        <input type="checkbox" id="popup-support-checkbox" onclick="updatePopupPrice()"> <?php _e('12 Months Support', 'degikart'); ?>
    </label>
    <div class="popup-buttons">
        <button class="left-button" onclick="closePopup()"><?php _e('Keep Browsing', 'degikart'); ?></button>
        <button class="right-button" onclick="goToCheckout()"><?php _e('Go to Checkout', 'degikart'); ?></button>
    </div>
</div>
