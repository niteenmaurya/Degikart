<?php
/* Template Name: Become an Author Page */

// Ensure user is logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Get the current user
$current_user = wp_get_current_user();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_author'])) {

    // Ensure the user is not already an author
    if (in_array('author', (array) $current_user->roles)) {
        $error_message = "You are already an author!";
    } else {
        // Save application data in user meta
        update_user_meta($current_user->ID, 'author_application_status', 'pending');
        update_user_meta($current_user->ID, 'author_application_reason', sanitize_textarea_field($_POST['reason']));
        update_user_meta($current_user->ID, 'author_application_checkboxes', implode(', ', $_POST['why_author'])); // Store selected reasons

        // Redirect after submission to show the success message
        wp_redirect(add_query_arg('status', 'pending', get_permalink()));
        exit;
    }
}

// Display the page header
get_header();

// Check the application status of the current user
$application_status = get_user_meta($current_user->ID, 'author_application_status', true);

// Display different messages based on the status
if ($application_status == 'approved') {
    echo '<div class="success-message">Congratulations! You are now an author.</div>';
} elseif ($application_status == 'rejected') {
    echo '<div class="error-message">Sorry, your application has been rejected.</div>';
} elseif ($application_status == 'pending') {
    echo '<div class="pending-message">Your application is pending review. You will be notified once it is processed.</div>';
} else {
    // Form to Apply for Author Role
    ?>

    <div id="site-width" class="author-application-container">
        <h2>Become an Author</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <p>If you'd like to become an author and publish content on our site, please fill in the details below and click the button.</p>

        <!-- Form to Apply for Author Role -->
        <form method="post" id="author-application-form" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo esc_attr($current_user->user_login); ?>" class="form-input" readonly>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" class="form-input" readonly>
            </div>

            <div class="form-group">
                <label for="why_author">Why do you want to become an author? (Select all that apply):</label>
                <ul class="checkbox-list">
                    <li>
                        <input type="checkbox" id="why_author_1" name="why_author[]" value="Share Knowledge">
                        <label for="why_author_1">Share Knowledge</label>
                    </li>
                    <li>
                        <input type="checkbox" id="why_author_2" name="why_author[]" value="Build a Reputation">
                        <label for="why_author_2">Build a Reputation</label>
                    </li>
                    <li>
                        <input type="checkbox" id="why_author_3" name="why_author[]" value="Monetize Content">
                        <label for="why_author_3">Monetize Content</label>
                    </li>
                    <li>
                        <input type="checkbox" id="why_author_4" name="why_author[]" value="Connect with Audience">
                        <label for="why_author_4">Connect with Audience</label>
                    </li>
                    <li>
                        <input type="checkbox" id="why_author_5" name="why_author[]" value="Other">
                        <label for="why_author_5">Other</label>
                    </li>
                </ul>
                <div id="checkbox-error" class="error-message" style="display:none;">Please select at least one reason why you want to become an author.</div>
            </div>

            <div class="form-group">
                <label for="reason">Please describe why you want to become an author:</label>
                <textarea id="reason" name="reason" class="form-input"></textarea>
                <div id="reason-error" class="error-message" style="display:none;">Please describe why you want to become an author.</div>
            </div>

            <div class="form-group">
                <label for="content_upload">Do you plan to upload content (articles, posts)?</label>
                <input type="checkbox" id="content_upload" name="content_upload" value="1">
            </div>

            <div class="form-group">
                <button type="submit" name="apply_author" class="apply-author-button">Apply to Become an Author</button>
            </div>
        </form>
    </div>

<?php
}
?>

<script>
    function validateForm() {
        let valid = true;

        // Check if at least one checkbox is selected for "Why do you want to become an author?"
        var checkboxes = document.querySelectorAll('input[name="why_author[]"]:checked');
        if (checkboxes.length === 0) {
            document.getElementById('checkbox-error').style.display = 'block';
            valid = false;
        } else {
            document.getElementById('checkbox-error').style.display = 'none';
        }

        // Check if the "Please describe why you want to become an author" textarea is filled
        var reason = document.getElementById('reason').value.trim();
        if (reason === "") {
            document.getElementById('reason-error').style.display = 'block';
            valid = false;
        } else {
            document.getElementById('reason-error').style.display = 'none';
        }

        return valid; // Prevent form submission if any validation fails
    }
</script>

<?php
// Display the footer of the page
get_footer();

?>
