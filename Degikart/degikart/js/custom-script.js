document.addEventListener('DOMContentLoaded', function() {

    // Function to update cart item count from local storage
    function updateCartCount() {
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        document.getElementById('cart-item-count').innerText = cartItems.length;
    }
    
    // Update cart count on page load
    updateCartCount();

    // Initialize total price on page load
    updateTotalPrice();

    // Function to update total price based on selected license and support
    function updateTotalPrice() {
        var license = document.getElementById('license').value;
        var support = document.getElementById('support').checked;
        
        // Parse the prices as floats
        var regularPrice = parseFloat(scriptData.regularPrice);
        var extendedPrice = parseFloat(scriptData.extendedPrice);
        var supportPrice = parseFloat(scriptData.supportPrice);
        
        var totalPrice = 0;
    
        // Set total price based on the selected license
        if (license === 'regular') {
            totalPrice = regularPrice;
        } else if (license === 'extended') {
            totalPrice = extendedPrice;
        }
    
        // Add support price only if checked
        if (support) {
            totalPrice += supportPrice;
        }
    
        // Update the total price display with conditional formatting
        if (totalPrice % 1 === 0) {
            // If total price is an integer, show without decimals
            document.getElementById('total-price').innerText = '$' + totalPrice.toString();
        } else {
            // If total price has decimal value, show with 2 decimal places
            document.getElementById('total-price').innerText = '$' + totalPrice.toFixed(2);
        }
    }

    // Update total price when license or support changes
    document.getElementById('license').addEventListener('change', updateTotalPrice);
    document.getElementById('support').addEventListener('click', updateTotalPrice);

    // Add product to cart
    window.addToCart = function(productId) {
        var license = document.getElementById('license').value;
        var support = document.getElementById('support').checked ? '12 month support' : '6 month support';
        var price = document.getElementById('total-price').innerText.replace('$', '');
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        // Check if the item already exists in the cart
        var existingItem = cartItems.find(function(item) {
            return item.product_id === productId && item.license === license && item.support === support;
        });

        if (existingItem) {
            // Item is already in the cart, show a notification
            alert('This item is already in your cart!');
        } else {
            // Item is not in the cart, add it
            var uniqueId = productId + '-' + new Date().getTime();

            cartItems.push({
                id: uniqueId,
                product_id: productId,
                license: license,
                support: support,
                price: price,
            });

            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            alert('Product added to cart!');
        }

        // Update cart count after adding the item
        updateCartCount();
    }
});




























function validateForm() {
    var themeName = document.getElementById('theme-name').value;
    var keyFeatures = document.getElementById('key-features').value;
    var htmlDescription = document.getElementById('html-description').value;
    var mainFiles = document.getElementById('main-files').value;
    var thumbnail = document.getElementById('thumbnail').value;
    var themePreview = document.getElementById('theme-preview').value;
    var regularPrice = document.getElementById('regular-price').value;
    var extendedPrice = document.getElementById('extended-price').value;
    var supportPrice = document.getElementById('support-price').value;
    var lastUpdate = document.getElementById('last-update').value;
    var published = document.getElementById('published').value;
    var compatibleBrowsers = document.getElementById('compatible-browsers').value;
    var compatibleWith = document.getElementById('compatible-with').value;
    var themeforestFilesIncluded = document.getElementById('themeforest-files-included').value;
    var columns = document.getElementById('columns').value;
    var layout = document.getElementById('layout').value;
    var tags = document.getElementById('tags').value;
    var version = document.getElementById('version').value;

    if (!themeName || !keyFeatures || !htmlDescription || !mainFiles || !thumbnail || !themePreview || !regularPrice || !extendedPrice || !supportPrice || !lastUpdate || !published || !compatibleBrowsers || !compatibleWith || !themeforestFilesIncluded || !columns || !layout || !tags || !version) {
        alert('Please fill in all required fields.');
        return false;
    }

    // Check if the same details already exist
    var xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxurl, false);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send('action=check_product&theme_name=' + themeName + '&key_features=' + keyFeatures + '&html_description=' + htmlDescription);

    if (xhr.responseText == 'exists') {
        alert('Product already exists with the same details. Please update the details.');
        return false;
    }

    return true;
}



















function loadCartItems() {
    // Fetch the cart items from localStorage safely
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // If the cart is empty, hide the cart container and show the empty message
    if (cartItems.length === 0) {
        document.getElementById('cart-items').innerHTML = '';  // Clear any existing items
        document.getElementById('total-price').innerText = '$0.00';  // Ensure the total price is 0
        document.getElementById('empty-cart-message').style.display = 'block';  // Show the empty cart message
        document.getElementById('buy-now-button').disabled = true;  // Disable the "Buy Now" button
        document.getElementById('cart-container').style.display = 'none';  // Hide the entire cart container
        document.getElementById('buy-now-message').style.display = 'none';  // Hide the "Please select at least one item" message
    } else {
        // If there are items in the cart, show the cart container and hide the empty cart message
        document.getElementById('cart-container').style.display = 'block';  // Show the entire cart container
        document.getElementById('empty-cart-message').style.display = 'none';  // Hide the empty cart message
        document.getElementById('buy-now-button').disabled = false;  // Enable the "Buy Now" button

        // Build cart items HTML
        var cartItemsHtml = cartItems.map(function(item, index) {
            // Ensure quantity is set to 1 if it's undefined or not valid
            if (!item.quantity || item.quantity <= 0) {
                item.quantity = 1;  // Default quantity to 1
            }

            // Ensure checkbox is always checked
            var itemHtml = '<div class="cart-item">' +
                           '<div class="post-card-header">' +
                           '<span class="item-count">Item ' + (index + 1) + '</span>' +
                           '<span class="item-price">$' + (item.price * item.quantity).toFixed(2) + '</span>' +  // Adjust price based on quantity
                           '</div>' +
                           '<div class="item-details">' +
                           // Only display the License information if it's defined and not empty
                           (item.license && item.license !== 'undefined' && item.license !== '' ? '<p><strong>License:</strong> ' + item.license + '</p>' : '') +
                           
                           // Check if support is valid and not undefined or empty
                           (item.support && item.support !== 'undefined' && item.support !== '' ? '<p><strong>Support:</strong> ' + item.support + '</p>' : '') +

                           '<p><strong>ID:</strong> ' + item.id + '</p>' +
                        '</div>' +
                        '<div class="post-card-footer">' +
                        '<button class="remove-item-button" onclick="removeCartItem(\'' + item.id + '\')">Remove</button>' +
                        '<input type="number" class="cart-item-quantity" data-id="' + item.id + '" value="' + item.quantity + '" min="1" max="10" onchange="updateItemQuantity(\'' + item.id + '\', this.value)">' +  // Quantity input with max 10
                        '<input type="checkbox" class="cart-item-checkbox" data-id="' + item.id + '" data-price="' + item.price + '" checked onchange="updateTotalPrice()">' +  // Make checkbox checked by default
                        '</div>' +
                        '</div>';

            return itemHtml;
        }).join('');  // Join all the item HTML parts

        // Insert the HTML for all items into the cart-items container
        document.getElementById('cart-items').innerHTML = cartItemsHtml;
        
        // Update total price after rendering items
        updateTotalPrice();
    }
}

function updateItemQuantity(itemId, quantity) {
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    var updatedItems = cartItems.map(function(item) {
        if (item.id === itemId) {
            item.quantity = parseInt(quantity, 10);  // Update the quantity
        }
        return item;
    });
    localStorage.setItem('cartItems', JSON.stringify(updatedItems));
    loadCartItems();  // Reload cart to reflect updated quantity
    updateTotalPrice();  // Update total price based on new quantity
}

function updateTotalPrice() {
    var checkboxes = document.querySelectorAll('.cart-item-checkbox');
    var totalPrice = 0;
    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            var price = parseFloat(checkbox.getAttribute('data-price'));
            var quantity = parseInt(checkbox.closest('.cart-item').querySelector('.cart-item-quantity').value, 10);  // Get quantity from input
            if (!isNaN(price) && !isNaN(quantity)) {
                totalPrice += price * quantity;  // Multiply price by quantity for total price
            }
        }
    });
    document.getElementById('total-price').innerText = '$' + totalPrice.toFixed(2);
}

function removeCartItem(itemId) {
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    cartItems = cartItems.filter(function(item) {
        return item.id !== itemId;
    });
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    loadCartItems();
    updateTotalPrice();
}

document.addEventListener('DOMContentLoaded', function() {
    // Load cart items and set up initial total price when the page is loaded
    loadCartItems();
});

function checkout(event) {
    event.preventDefault(); // Prevent default form submission
    var selectedItems = [];
    var checkboxes = document.querySelectorAll('.cart-item-checkbox');
    var totalPrice = 0; // Initialize total price

    checkboxes.forEach(function(checkbox) {
        if (checkbox.checked) {
            var itemId = checkbox.getAttribute('data-id');  // Get the product ID from the checkbox
            var price = parseFloat(checkbox.getAttribute('data-price')); // Get the price from the checkbox
            var quantity = parseInt(checkbox.closest('.cart-item').querySelector('.cart-item-quantity').value, 10);  // Get quantity from input

            if (!isNaN(price) && !isNaN(quantity)) {
                totalPrice += price * quantity; // Add to total price based on quantity
            }

            // Add the item with quantity to the selectedItems array
            for (var i = 0; i < quantity; i++) {
                selectedItems.push({
                    id: itemId, // Store the product ID
                    price: price // Store the price for each selected item
                });
            }
        }
    });

    // Check if any items were selected
    if (selectedItems.length > 0) {
        // Hide the message when items are selected
        document.getElementById('buy-now-message').style.display = 'none';

        // Store the total price in local storage
        localStorage.setItem('totalPrice', totalPrice.toFixed(2)); // Store as string with two decimal points
        localStorage.setItem('selectedCartItems', JSON.stringify(selectedItems));

        // Redirect to the checkout page, passing the product IDs as a query parameter
        var checkoutUrl = 'checkout';  // Assuming 'checkout' is the URL or page for checkout
        var productIds = selectedItems.map(item => item.id).join(',');  // Create a comma-separated string of product IDs

        // Append the product IDs to the URL as a query string (e.g., 'checkout?product_ids=1,2,3')
        window.location.href = checkoutUrl + '?product_ids=' + productIds;
    } else {
        // Show the message when no items are selected
        document.getElementById('buy-now-message').style.display = 'block';
    }
}



document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
    updateTotalPrice();
    document.getElementById('buy-now-button').addEventListener('click', checkout);
});




























 







 
















 













 




 






function toggleContent() {
    var contentText = document.querySelector('.content-text');
    var showMoreButton = document.querySelector('.show-more');
    
    // Check if the content is collapsed or expanded
    if (contentText.style.maxHeight === '' || contentText.style.maxHeight === '200px') {
        contentText.style.maxHeight = 'none'; // Show full content
        showMoreButton.textContent = 'Show Less';
    } else {
        contentText.style.maxHeight = '200px'; // Mobile size (5 lines content)
        showMoreButton.textContent = 'Show More';
    }
}



 




















// // JavaScript to fetch file URLs for each product
// function fetchFileUrl(productId, callback) {
//     var xhr = new XMLHttpRequest();
//     xhr.open("POST", "<?php echo admin_url('admin-ajax.php'); ?>", true);
//     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//     xhr.onload = function () {
//         if (xhr.status === 200) {
//             var response = JSON.parse(xhr.responseText);
//             if (response.success) {
//                 callback(response.data.file_url);
//             } else {
//                 callback(null);
//             }
//         } else {
//             callback(null);
//         }
//     };
//     xhr.send("action=get_file_url&product_id=" + productId);
// }

// document.addEventListener('DOMContentLoaded', function () {
//     var purchaseRows = document.querySelectorAll('#dwnld-hst__items tr');
    
//     purchaseRows.forEach(function (row) {
//         var productIds = row.querySelector('td:nth-child(3)').textContent.trim().split(','); // Get multiple product IDs
//         var fileUrlContainer = row.querySelector('.dwnld-hst__file-url');

//         // Iterate through all product IDs and fetch their file URLs individually
//         productIds.forEach(function (productId) {
//             fetchFileUrl(productId, function (fileUrl) {
//                 var productFileContainer = document.createElement('div');
                
//                 // Add the 'hard' class to the container
//                 productFileContainer.classList.add('dwnld-hst__btn');

//                 if (fileUrl) {
//                     productFileContainer.innerHTML = `<a href="${fileUrl}" download class="download-btn">Download</a>`;
//                 } else {
//                     productFileContainer.innerHTML = `<span>No file available for Product ${productId}</span>`;
//                 }
//                 fileUrlContainer.appendChild(productFileContainer);
//             });
//         });
//     });
// });


























jQuery(document).ready(function($) {
    // Hide all sections by default
    $('.fhgbta-gr-dashboard-section').hide();
    
    // Show the default section (Dashboard) initially if no 'tab' parameter is present
    $('#fhgbta-gr-dashboard').show();

    // Handle tab clicks
    $('#fhgbta-gr-dashboard-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-dashboard').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=dashboard');
        return false;
    });

    $('#fhgbta-gr-profile-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-profile').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=profile');
        return false;
    });

    $('#fhgbta-gr-favourite-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-favourite').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=favourite');
        return false;
    });

    $('#fhgbta-gr-earnings-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-earnings').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=earnings');
        return false;
    });

    $('#fhgbta-gr-payment-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-payment').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=payment');
        return false;
    });

    $('#fhgbta-gr-downloads-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-downloads').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=downloads');
        return false;
    });

    $('#fhgbta-gr-upload-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-upload').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=upload');
        return false;
    });

    $('#fhgbta-gr-uploadeditems-tab').click(function() {
        $('.fhgbta-gr-dashboard-section').hide();
        $('#fhgbta-gr-uploadeditems').show();
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');
        $(this).addClass('active');
        // Update the URL with the 'tab' parameter
        history.pushState(null, null, '?tab=uploadeditems');
        return false;
    });

    // URL-based tab activation logic
    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get('tab'); // Get the 'tab' parameter from the URL

    // If the 'tab' parameter is found in the URL, activate that tab
    if (tabParam) {
        var tabId = '#fhgbta-gr-' + tabParam;
        var tabLinkId = '#fhgbta-gr-' + tabParam + '-tab'; // The ID of the corresponding <a> link
        
        if ($(tabId).length > 0) {
            $('.fhgbta-gr-dashboard-section').hide();  // Hide all sections
            $(tabId).show();  // Show the selected section
            $('.fhgbta-gr-dashboard-tabs a').removeClass('active');  // Remove active class from all tabs
            $(tabLinkId).addClass('active');  // Add active class to the selected tab
        }
    } else {
        // If no 'tab' parameter is passed, activate the default tab (Dashboard)
        $('#fhgbta-gr-dashboard').show();
        $('#fhgbta-gr-dashboard-tab').addClass('active');
    }

    
});






