function sortProducts(orderby, order) {
    var url = new URL(window.location.href);
    url.searchParams.set('orderby', orderby);
    url.searchParams.set('order', order);
    window.location.href = url.toString();
}

function updatePopupPrice() {
    var licenseSelect = document.getElementById('popup-license');
    var supportCheckbox = document.getElementById('popup-support-checkbox');
    var priceElement = document.getElementById('popup-price');
    var selectedLicense = licenseSelect.options[licenseSelect.selectedIndex];
    var licensePrice = parseFloat(selectedLicense.getAttribute('data-price'));
    var supportPrice = parseFloat(priceElement.getAttribute('data-support-price'));

    var newPrice = licensePrice;
    if (supportCheckbox.checked) {
        newPrice += supportPrice;
        document.getElementById('popup-support').innerText = '12 months support';
    } else {
        document.getElementById('popup-support').innerText = '6 months support';
    }

    priceElement.innerText = newPrice.toFixed(2);
}

// Function to update the cart item count
function updateCartCount() {
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    document.getElementById('cart-item-count').innerText = cartItems.length;
}

// Function to add an item to the cart
function addToCart(event, productId, regularPrice, extendedPrice, supportPrice, themeName, authorName, supportPrice12Months) {
    event.preventDefault(); // Prevent the form from submitting normally

    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    
    // Check if the item already exists in the cart (same productId, license, and support type)
    var existingItem = cartItems.find(function(item) {
        return item.product_id === productId && item.license === 'Regular' && item.support === '6 months support';
    });

    var popupMessage = '';
    if (existingItem) {
        // Item already in cart
        popupMessage = 'already_added';
    } else {
        // Item not in cart, add it
        popupMessage = 'added';
        
        var uniqueId = productId + '-' + new Date().getTime(); // Create a unique ID for the new item

        // Add the new item to the cart
        cartItems.push({
            id: uniqueId,
            product_id: productId,
            price: regularPrice,
            theme_name: themeName,
            author_name: authorName,
            license: 'Regular',
            support: '6 months support',
        });

        // Store the updated cart in localStorage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }

    // Show the popup with the appropriate message
    showPopup(themeName, authorName, regularPrice, extendedPrice, supportPrice, popupMessage);

    // Populate the popup with the corresponding product details
    document.getElementById('popup-theme-name').innerText = themeName;
    document.getElementById('popup-author-name').innerText = authorName;
    document.getElementById('popup-price').innerText = regularPrice;

    // Set the correct product ID in the popup for checkout
    var popup = document.getElementById('popup-notification');
    popup.setAttribute('data-product-id', productId);

    // Show the popup
    popup.style.display = 'block';

    // Immediately update the cart count after adding the item
    updateCartCount(); // <-- This line ensures the cart count is updated
}

function showPopup(themeName, authorName, regularPrice, extendedPrice, supportPrice, popupMessage) {
    document.getElementById('popup-theme-name').innerText = themeName;
    document.getElementById('popup-author-name').innerText = authorName;
    document.getElementById('popup-price').innerText = regularPrice;
    document.getElementById('popup-price').setAttribute('data-regular-price', regularPrice);
    document.getElementById('popup-price').setAttribute('data-support-price', supportPrice);

    var licenseSelect = document.getElementById('popup-license');
    licenseSelect.options[0].setAttribute('data-price', regularPrice);
    licenseSelect.options[1].setAttribute('data-price', extendedPrice);

    // Get the translated messages from the data-attributes
    var popupNotice = document.getElementById('popup-notice-message');
    var message = popupMessage === 'added' 
        ? popupNotice.getAttribute('data-item-added') 
        : popupNotice.getAttribute('data-item-already-added');

    // Update the popup notice message
    popupNotice.innerText = message;

    // Show the popup
    var popup = document.getElementById('popup-notification');
    popup.style.display = 'block';

    // Reset support checkbox to the default unchecked state
    document.getElementById('popup-support-checkbox').checked = false;
}


function closePopup() {
    var popup = document.getElementById('popup-notification');
    popup.style.display = 'none';
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

    // Redirect to the payment page with the combined ID of the selected item in the URL
    var checkoutUrl = 'checkout';  // Checkout URL
    var productIds = selectedItems.map(item => item.id).join(',');  // Combine the product ID and unique ID

    // Redirect to the checkout page, passing the combined ID in the URL
    window.location.href = checkoutUrl + '?product_ids=' + encodeURIComponent(productIds);
}



 
