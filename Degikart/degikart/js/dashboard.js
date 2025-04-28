jQuery(document).ready(function($) {
    // Function to show the selected tab and hide others
    function switchTab(tabId, tabLinkId) {
        $('.fhgbta-gr-dashboard-section').hide();  // Hide all sections
        $(tabId).show();  // Show the selected section
        $('.fhgbta-gr-dashboard-tabs a').removeClass('active');  // Remove active class from all tabs
        $(tabLinkId).addClass('active');  // Add active class to the selected tab
    }

    // Tab switching logic: when a tab is clicked, update the URL and show the corresponding tab
    $('#fhgbta-gr-dashboard-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=dashboard');
        switchTab('#fhgbta-gr-dashboard', '#fhgbta-gr-dashboard-tab');
        return false;
    });

    $('#fhgbta-gr-profile-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=profile');
        switchTab('#fhgbta-gr-profile', '#fhgbta-gr-profile-tab');
        return false;
    });

    $('#fhgbta-gr-earnings-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=earnings');
        switchTab('#fhgbta-gr-earnings', '#fhgbta-gr-earnings-tab');
        return false;
    });

    $('#fhgbta-gr-payment-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=payment');
        switchTab('#fhgbta-gr-payment', '#fhgbta-gr-payment-tab');
        return false;
    });

    $('#fhgbta-gr-downloads-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=downloads');
        switchTab('#fhgbta-gr-downloads', '#fhgbta-gr-downloads-tab');
        return false;
    });

    $('#fhgbta-gr-upload-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=upload');
        switchTab('#fhgbta-gr-upload', '#fhgbta-gr-upload-tab');
        return false;
    });

    $('#fhgbta-gr-uploadeditems-tab').click(function() {
        window.history.pushState({}, '', window.location.pathname + '?tab=uploadeditems');
        switchTab('#fhgbta-gr-uploadeditems', '#fhgbta-gr-uploadeditems-tab');
        return false;
    });

    // URL-based tab activation logic: Read the 'tab' parameter from the URL on page load
    var urlParams = new URLSearchParams(window.location.search);
    var tabParam = urlParams.get('tab'); // Get the 'tab' parameter from the URL

    // If the 'tab' parameter is found in the URL, activate that tab
    if (tabParam) {
        var tabId = '#fhgbta-gr-' + tabParam;
        var tabLinkId = '#fhgbta-gr-' + tabParam + '-tab'; // The ID of the corresponding <a> link
        
        if ($(tabId).length > 0) {
            switchTab(tabId, tabLinkId); // Activate the tab using the helper function
        }
    } else {
        // If no 'tab' parameter is passed, set the default tab (e.g., Dashboard)
        switchTab('#fhgbta-gr-dashboard', '#fhgbta-gr-dashboard-tab');
    }

    // PayPal / Bank Transfer info toggling logic
    $('#payout-method').change(function() {
        const payoutMethod = $(this).val();
        if (payoutMethod === 'paypal') {
            $('#paypal-info').show();
            $('#bank-info').hide();
        } else {
            $('#paypal-info').hide();
            $('#bank-info').show();
        }
    });

    // Trigger correct payout method info on page load
    $(document).ready(function() {
        const payoutMethod = $('#payout-method').val();
        if (payoutMethod === 'paypal') {
            $('#paypal-info').show();
            $('#bank-info').hide();
        } else {
            $('#paypal-info').hide();
            $('#bank-info').show();
        }
    });

    // Edit details button to toggle between form and saved details
    $('#edit-details').click(function() {
        $('#payment-form').show();  // Show form
        $('#saved-details').hide();  // Hide saved details
        return false;
    });

    // Update URL when payment details are saved
    $('#save-payment-details').click(function() {
        // Update the URL to include the "tab=payment" parameter after saving
        window.history.pushState({}, '', window.location.pathname + '?tab=payment');
        
        // Optionally, switch to the Payment tab immediately after saving
        switchTab('#fhgbta-gr-payment', '#fhgbta-gr-payment-tab');
    });
});
