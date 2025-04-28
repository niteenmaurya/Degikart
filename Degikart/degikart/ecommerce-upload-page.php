<?php
/*
Template Name: Ecommerce Upload Page
*/

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


// Sanitize category
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input fields
    $theme_name = sanitize_text_field($_POST['theme-name']);
    $plugin_name = sanitize_text_field($_POST['plugin-name']);
    $key_features = sanitize_text_field($_POST['key-features']);
    $html_description = wp_kses_post($_POST['html-description']);
    $main_files = $_FILES['main-files'];
    $thumbnail = $_FILES['thumbnail'];
    $theme_preview = $_FILES['theme-preview'];
    $regular_price = isset($_POST['regular-price']) ? floatval($_POST['regular-price']) : 0;
    $extended_price = isset($_POST['extended-price']) ? floatval($_POST['extended-price']) : 0;
    $support_price = isset($_POST['support-price']) ? floatval($_POST['support-price']) : 0;
    $last_update = sanitize_text_field($_POST['last-update']);
    $published = sanitize_text_field($_POST['published']);
    $high_resolution = isset($_POST['high-resolution']) ? 'Yes' : 'No';
    $compatible_browsers = sanitize_text_field($_POST['compatible-browsers']);
    $compatible_with = sanitize_text_field($_POST['compatible-with']);
    $files_included = sanitize_text_field($_POST['files-included']);
    $columns = sanitize_text_field($_POST['columns']);
    $layout = sanitize_text_field($_POST['layout']);
    $demo_url = sanitize_text_field($_POST['demo-url']);
    $tags = sanitize_text_field($_POST['tags']);
    $message_to_reviewer = sanitize_text_field($_POST['message-to-reviewer']);
    $version = sanitize_text_field($_POST['version']);

    // Validate required fields
    if (empty($theme_name) || empty($key_features) || empty($html_description) || empty($main_files) || empty($thumbnail) || empty($theme_preview) || empty($regular_price) || empty($extended_price) || empty($support_price) || empty($last_update) || empty($published) || empty($compatible_browsers) || empty($compatible_with) || empty($files_included) || empty($columns) || empty($layout) || empty($tags) || empty($version)) {
        echo __('Please fill in all required fields.', 'degikart');
    } else {
        // Check if the same product already exists
        $existing_products = new WP_Query(array(
            'post_type' => 'ecommerce',
            'meta_query' => array(
                array(
                    'key' => 'theme_name',
                    'value' => $theme_name,
                ),
            ),
        ));

        if ($existing_products->have_posts()) {
            echo __('Product already exists with the same details. Please update the details.', 'degikart');
        } else {

                        // Ensure ecommerce media functions are available
if (!function_exists('wp_generate_attachment_metadata')) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
}

                // Handle file uploads
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }
    
             // Function to create a unique filename for the uploaded file
    function generate_unique_filename($file) {
        $file_info = pathinfo($file['name']);
        $ext = isset($file_info['extension']) ? '.' . $file_info['extension'] : '';
        $base_name = sanitize_file_name($file_info['filename']);
    
        // Generate a random string and timestamp for uniqueness
        $random_string = bin2hex(random_bytes(16)); // Generates a 32-character random string
        $hashed_name = hash('sha256', $base_name . $random_string . time() . uniqid()); // Generate SHA256 hash
    
        // Combine the unique hash and the original filename (with extension) at the end
        $unique_name = $hashed_name . '-' . $random_string . $ext;
    
        // Add the original file name (with its extension) at the end of the unique name
        $unique_name_with_original = $random_string . '-' . $hashed_name . '-' . $file['name'];
    
        return $unique_name_with_original;
    }
    
    
                // Function to handle the upload with a custom folder
                function handle_custom_upload($file) {
                    // Create a unique folder based on current timestamp and random string
                    $unique_folder = 'uploads/' . date('Y/m/d') . '/' . uniqid() . '/';
    
                    // Define the full upload directory
                    $upload_dir = wp_upload_dir();
                    $target_dir = $upload_dir['basedir'] . '/' . $unique_folder;
    
                    // Make sure the folder exists
                    if (!file_exists($target_dir)) {
                        wp_mkdir_p($target_dir); // Create the folder if it doesn't exist
                    }
    
                    // Generate a unique filename for the file
                    $unique_filename = generate_unique_filename($file);
    
                    // Define the target file path
                    $target_file = $target_dir . $unique_filename;
    
                    // Move the uploaded file to the new folder with the unique filename
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        // Return the URL of the uploaded file
                        return $upload_dir['baseurl'] . '/' . $unique_folder . $unique_filename;
                    } else {
                        return new WP_Error('upload_failed', __('File upload failed.', 'degikart'));
                    }
                }
    
                // Handle the main file upload (using custom folder)
                $main_files_url = handle_custom_upload($main_files);
    
            $thumbnail_upload = wp_handle_upload($thumbnail, array('test_form' => false));
            $theme_preview_upload = wp_handle_upload($theme_preview, array('test_form' => false));


            if ($main_files_url && !is_wp_error($main_files_url) && $thumbnail_upload && !isset($thumbnail_upload['error']) && $theme_preview_upload && !isset($theme_preview_upload['error'])) {
                $file_url = $main_files_url; // The custom uploaded file URL
                $thumbnail_url = $thumbnail_upload['url'];
                $theme_preview_url = $theme_preview_upload['url'];

                // Create new product
                $post_id = wp_insert_post(array(
                    'post_title' => $theme_name,
                    'post_content' => $html_description,
                    'post_status' => 'pending', // Set status to pending
                    'post_type' => 'ecommerce',
                    'post_author' => get_current_user_id(), // Set the author to the current user
                ));

                if ($post_id) {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_excerpt' => $excerpt, // Save the excerpt directly to the post
                        'post_content' => $html_description,
                    ));
                    wp_set_post_terms($post_id, $category_id, 'blogger_category');
                    update_post_meta($post_id, 'key_features', $key_features);
                    update_post_meta($post_id, 'regular_price', number_format($regular_price, 2, '.', ''));
                    update_post_meta($post_id, 'extended_price', number_format($extended_price, 2, '.', ''));
                    update_post_meta($post_id, 'support_price', number_format($support_price, 2, '.', ''));
                    update_post_meta($post_id, 'last_update', $last_update);
                    update_post_meta($post_id, 'published', $published);
                    update_post_meta($post_id, 'high_resolution', $high_resolution);
                    update_post_meta($post_id, 'compatible_browsers', $compatible_browsers);
                    update_post_meta($post_id, 'compatible_with', $compatible_with);
                    update_post_meta($post_id, 'files_included', $files_included);
                    update_post_meta($post_id, 'columns', $columns);
                    update_post_meta($post_id, 'layout', $layout);
                    update_post_meta($post_id, 'demo_url', $demo_url);
                    wp_set_post_terms($post_id, $tags, 'post_tag');
                    update_post_meta($post_id, 'file_url', esc_url_raw($file_url));
                    update_post_meta($post_id, 'thumbnail_url', esc_url_raw($thumbnail_url));
                    update_post_meta($post_id, 'theme_preview_url', esc_url_raw($theme_preview_url));
                    update_post_meta($post_id, 'message_to_reviewer', $message_to_reviewer);
                    update_post_meta($post_id, 'version', $version);
                    update_post_meta($post_id, 'details', $html_description); // Save details

                    // Set the category
                    wp_set_post_terms($post_id, $category, 'product-category');

                                                // Set the thumbnail as the featured image
                                                if (!is_wp_error($thumbnail_upload)) {
                                                    $thumbnail_attachment = array(
                                                        'post_mime_type' => $thumbnail_upload['type'],
                                                        'post_title' => sanitize_file_name($thumbnail['name']),
                                                        'post_content' => '',
                                                        'post_status' => 'inherit',
                                                    );
                                                    $attachment_id = wp_insert_attachment($thumbnail_attachment, $thumbnail_upload['file'], $post_id);
                                                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $thumbnail_upload['file']);
                                                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                            
                                                    // Set the post's featured image
                                                    set_post_thumbnail($post_id, $attachment_id);
                                                }
                            
        
                                                  // Now handle the plugin preview as a file in the media library
                                                  if (!is_wp_error($theme_preview_upload)) {
                                                    // Upload the theme preview file and add it to the media library
                                                    $theme_preview_attachment = array(
                                                        'post_mime_type' => $theme_preview_upload['type'],
                                                        'post_title' => sanitize_file_name($theme_preview['name']),
                                                        'post_content' => '',
                                                        'post_status' => 'inherit',
                                                    );
                                                    $theme_preview_attachment_id = wp_insert_attachment($theme_preview_attachment, $theme_preview_upload['file'], $post_id);
                                                    $theme_preview_attachment_data = wp_generate_attachment_metadata($theme_preview_attachment_id, $theme_preview_upload['file']);
                                                    wp_update_attachment_metadata($theme_preview_attachment_id, $theme_preview_attachment_data);
                                                
                                                    // Save the theme preview URL in post meta
                                                    update_post_meta($post_id, 'theme_preview_attachment_id', $theme_preview_attachment_id);
                                                }

                    // Redirect to the seller dashboard after successful submission
                    echo '<script type="text/javascript">
                    window.location.href = "' . home_url('/uploaded-products') . '";
                    </script>';
                    exit;
                } else {
                    echo __('Error creating product.', 'degikart');
                }
            } else {
                echo __('Error uploading files.', 'degikart');
            }
        }
    }
}

get_header();
?>

<div id="site-width">
    <form class="form-main" action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
        <!-- Checklist Section -->
        <div class="checklist">
    <h2><?php _e('Before uploading your item for the first time:', 'degikart'); ?></h2>
    <ul>
        <li><?php _e('Review the item submission standards and requirements.', 'degikart'); ?></li>
        <li><?php _e('Organize your files, supporting documents, and assets for upload.', 'degikart'); ?></li>
        <li><?php _e('Follow the submission instructions to ensure a smooth process.', 'degikart'); ?></li>
        <li><?php _e('Complete your Author ID verification.', 'degikart'); ?></li>
    </ul>
</div>


        <!-- Form Fields -->
        <div class="form-group">
            <div class="label-container">
                <label for="theme-name"><?php _e('Theme Name:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="theme-name" name="theme-name" class="input-text" maxlength="100" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="key-features"><?php _e('Key Features:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <textarea id="key-features" name="key-features" class="input-textarea" maxlength="45" required></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="html-description"><?php _e('HTML Description:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <?php
                wp_editor('', 'html-description', array(
                    'textarea_name' => 'html-description',
                    'media_buttons' => true,
                    'tinymce' => array(
                        'toolbar1' => 'bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
                        'toolbar2' => '',
                    ),
                    'quicktags' => true,
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="main-files"><?php _e('Main File(s):', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="main-files" name="main-files" class="input-file" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="thumbnail"><?php _e('Thumbnail:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="thumbnail" name="thumbnail" class="input-file" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="theme-preview"><?php _e('Theme Preview:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="theme-preview" name="theme-preview" class="input-file" required>
            </div>
        </div>

        <div class="form-group">
    <div class="label-container">
        <label for="regular-price"><?php _e('Regular License Price:', 'degikart'); ?></label>
    </div>
    <div class="input-container">
        <input type="number" id="regular-price" name="regular-price" class="input-number" step="0.01" required>
    </div>
</div>

<div class="form-group">
    <div class="label-container">
        <label for="extended-price"><?php _e('Extended License Price:', 'degikart'); ?></label>
    </div>
    <div class="input-container">
        <input type="number" id="extended-price" name="extended-price" class="input-number" step="0.01" required>
    </div>
</div>

<div class="form-group">
    <div class="label-container">
        <label for="support-price"><?php _e('Support Price:', 'degikart'); ?></label>
    </div>
    <div class="input-container">
        <input type="number" id="support-price" name="support-price" class="input-number" step="0.01" required>
    </div>
</div>

        <div class="form-group">
            <div class="label-container">
                <label for="last-update"><?php _e('Last Update:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="last-update" name="last-update" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="published"><?php _e('Published:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="published" name="published" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="high-resolution"><?php _e('High Resolution:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <select id="high-resolution" name="high-resolution">
                    <option value="Yes"><?php _e('Yes', 'degikart'); ?></option>
                    <option value="No"><?php _e('No', 'degikart'); ?></option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="compatible-browsers"><?php _e('Compatible Browsers:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="compatible-browsers" name="compatible-browsers" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="compatible-with"><?php _e('Compatible With:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="compatible-with" name="compatible-with" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="files-included"><?php _e('Files Included:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="files-included" name="files-included" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="columns"><?php _e('Columns:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="columns" name="columns" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="layout"><?php _e('Layout:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="layout" name="layout" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="demo-url"><?php _e('Demo URL:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="url" id="demo-url" name="demo-url" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="tags"><?php _e('Tags:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="tags" name="tags" class="input-text" required>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="message-to-reviewer"><?php _e('Message to the Reviewer:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <textarea id="message-to-reviewer" name="message-to-reviewer" class="input-textarea"></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="label-container">
                <label for="version"><?php _e('Version:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="version" name="version" class="input-text" required>
            </div>
        </div>


    <!-- Category Selection -->
    <div class="form-group">
        <label for="category"><?php _e('Category:', 'degikart'); ?></label>
        <?php
            wp_dropdown_categories(array(
                'taxonomy'         => 'ecommerce_category', // Custom taxonomy for 'ecommerce'
                'name'             => 'category',
                'id'               => 'category',
                'show_option_none' => __('Select Category', 'degikart'),
                'hide_empty'       => false,
            ));
        ?>
    </div>


    <div class="form-button" id="formbutton">
        <div class="input-container">
             <button type="submit" name="submit_post"><?php _e('Submit Post', 'degikart'); ?></button>
        </div>
    </div>
</form>

</div>
<script type="text/javascript">
    document.querySelector('.form-main').addEventListener('submit', function() {
        var submitButton = document.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Submitting...';
    });
</script>

<?php
get_footer();
?>
