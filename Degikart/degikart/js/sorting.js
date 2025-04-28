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
function goToCheckout() {
// Get item details from the popup
var themeName = document.getElementById('popup-theme-name').innerText;
var price = parseFloat(document.getElementById('popup-price').innerText.replace('$', ''));
var license = document.getElementById('popup-license').value;
var support = document.getElementById('popup-support-checkbox').checked ? '12 months support' : '6 months support';

// Generate a unique ID for this item (using the current timestamp)
var uniqueId = new Date().getTime();  // Unique ID based on the current timestamp

// Generate the product ID (replace this with your dynamic logic if necessary)
var productId = document.getElementById('popup-notification').getAttribute('data-product-id');

// Concatenate product ID and unique ID for the checkout
var combinedId = productId + '-' + uniqueId;  // No "item-" in the ID

// Create an array with the current selected item (replacing previous items)
var selectedItems = [{
    id: combinedId,  // Store the combined ID (product ID and unique ID)
    theme_name: themeName,
    price: price,
    license: license,
    support: support,
}];

// Calculate the total price for the new item (since it's the only one now)
var totalPrice = price; // Only the current item price

// Save the new item and total price to localStorage, replacing previous data
localStorage.setItem('selectedCartItems', JSON.stringify(selectedItems));
localStorage.setItem('totalPrice', totalPrice.toFixed(2));

// Get the localized checkout URL passed from PHP
var checkoutUrl = themeData.checkoutUrl;

// Combine the product ID and unique ID into a query parameter
var productIds = selectedItems.map(item => item.id).join(',');

// Redirect to the checkout page with the product IDs in the URL
window.location.href = checkoutUrl + '?product_ids=' + encodeURIComponent(productIds);
}

