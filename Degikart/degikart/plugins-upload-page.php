<?php
/*
Template Name: Plugin Upload Page
*/

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


// Sanitize category
$category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and prefix input fields
    $plugin_name = sanitize_text_field($_POST['plugin-name']);
    $excerpt = sanitize_text_field($_POST['excerpt']);
    $key_features = sanitize_text_field($_POST['key-features']);
    $html_description = wp_kses_post($_POST['html-description']);
    $main_files = $_FILES['main-files'];
    $thumbnail = $_FILES['thumbnail'];
    $plugin_preview = $_FILES['plugin-preview'];
    $demo_url = sanitize_text_field($_POST['demo-url']);
    $regular_price = isset($_POST['regular-price']) ? floatval($_POST['regular-price']) : 0;
    $extended_price = isset($_POST['extended-price']) ? floatval($_POST['extended-price']) : 0;
    $support_price = isset($_POST['support-price']) ? floatval($_POST['support-price']) : 0;    
    $last_update = sanitize_text_field($_POST['last-update']);
    $published = sanitize_text_field($_POST['published']);
    $high_resolution = isset($_POST['high-resolution']) ? 'Yes' : 'No';
    $compatible_browsers = sanitize_text_field($_POST['compatible-browsers']);
    $compatible_with = sanitize_text_field($_POST['compatible-with']);
    $version = sanitize_text_field($_POST['version']);
    $tags = sanitize_text_field($_POST['tags']);
    $message_to_reviewer = sanitize_text_field($_POST['message-to-reviewer']);

    
    
    // Validate required fields
    if (empty($plugin_name) || empty($key_features) || empty($html_description) || empty($main_files) || empty($thumbnail) || empty($plugin_preview) || empty($regular_price) || empty($extended_price) || empty($support_price) || empty($last_update) || empty($published) || empty($compatible_browsers) || empty($compatible_with) || empty($tags) || empty($version)) {
        echo __('Please fill in all required fields.', 'degikart');
    } else {
        // Check if the same plugin already exists
        $existing_plugins = new WP_Query(array(
            'post_type' => 'plugin',
            'meta_query' => array(
                array(
                    'key' => 'plugin_name',
                    'value' => $plugin_name,
                    'compare' => '='
                ),
                array(
                    'key' => 'regular_price',
                    'value' => $regular_price,
                    'compare' => '='
                ),
            ),
        ));

        if ($existing_plugins->have_posts()) {
            echo __('Plugin already exists with the same details. Please update the details.', 'degikart');
        } else {
            // Ensure plugin media functions are available
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
            $plugin_preview_upload = wp_handle_upload($plugin_preview, array('test_form' => false));

                if ($main_files_url && !is_wp_error($main_files_url) && $thumbnail_upload && !isset($thumbnail_upload['error']) && $plugin_preview_upload && !isset($plugin_preview_upload['error'])) {
                $file_url = $main_files_url; // The custom uploaded file URL
                $thumbnail_url = $thumbnail_upload['url'];
                $plugin_preview_url = $plugin_preview_upload['url'];

                // Create new plugin post
                $post_id = wp_insert_post(array(
                    'post_title' => $plugin_name,
                    'post_content' => $html_description,
                    'post_status' => 'pending', // Set status to pending
                    'post_type' => 'plugin',
                    'post_author' => get_current_user_id(), // Set the author to the current user
                ));

                if ($post_id) {
                    // Save plugin details to post meta
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_excerpt' => $excerpt, // Save the excerpt directly to the post
                        'post_content' => $html_description,
                    ));
                
                    wp_set_post_terms($post_id, $category_id, 'course_category');
                    update_post_meta($post_id, 'key_features', $key_features);
                    update_post_meta($post_id, 'regular_price', $regular_price);
                    update_post_meta($post_id, 'extended_price', $extended_price);
                    update_post_meta($post_id, 'support_price', $support_price);
                    update_post_meta($post_id, 'last_update', $last_update);
                    update_post_meta($post_id, 'published', $published);
                    update_post_meta($post_id, 'high_resolution', $high_resolution);
                    update_post_meta($post_id, 'compatible_browsers', $compatible_browsers);
                    update_post_meta($post_id, 'compatible_with', $compatible_with);
                    update_post_meta($post_id, 'version', $version);
                    wp_set_post_terms($post_id, $tags, 'post_tag');
                    update_post_meta($post_id, 'file_url', esc_url_raw($file_url));
                    update_post_meta($post_id, 'thumbnail_url', esc_url_raw($thumbnail_url));
                    update_post_meta($post_id, 'plugin_preview_url', esc_url_raw($plugin_preview_url));
                    update_post_meta($post_id, 'demo_url', esc_url_raw($demo_url)); // Store demo URL
                    update_post_meta($post_id, 'message_to_reviewer', $message_to_reviewer);
                    update_post_meta($post_id, 'details', $_POST['html-description']);
                    // Set the category
                    wp_set_post_terms($post_id, $category, 'category');

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
        if (!is_wp_error($plugin_preview_upload)) {
            // Upload the plugin preview file and add it to the media library
            $plugin_preview_attachment = array(
                'post_mime_type' => $plugin_preview_upload['type'],
                'post_title' => sanitize_file_name($plugin_preview['name']),
                'post_content' => '',
                'post_status' => 'inherit',
            );
            $plugin_preview_attachment_id = wp_insert_attachment($plugin_preview_attachment, $plugin_preview_upload['file'], $post_id);
            $plugin_preview_attachment_data = wp_generate_attachment_metadata($plugin_preview_attachment_id, $plugin_preview_upload['file']);
            wp_update_attachment_metadata($plugin_preview_attachment_id, $plugin_preview_attachment_data);

            // Save the plugin preview URL in post meta
            update_post_meta($post_id, 'plugin_preview_attachment_id', $plugin_preview_attachment_id);
        }
                    // Redirect to the seller dashboard after successful submission
                    echo '<script type="text/javascript">
                    window.location.href = "' . home_url('/uploaded-products') . '";
                    </script>';
                    exit;
                } else {
                    echo __('Error creating plugin.', 'degikart');
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
<form class="form-main" action="" method="post" enctype="multipart/form-data">
    <!-- Plugin Name -->
    <div class="form-group">
        <div class="label-container">
            <label for="plugin-name"><?php _e('Plugin Name:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <input type="text" id="plugin-name" name="plugin-name" class="input-text" required>
        </div>
    </div>

    <!-- Excerpt -->
    <div class="form-group">
        <div class="label-container">
            <label for="excerpt"><?php _e('Excerpt:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <textarea id="excerpt" name="excerpt" class="input-textarea" maxlength="100" required></textarea>
        </div>
    </div>

    <!-- Key Features -->
    <div class="form-group">
        <div class="label-container">
            <label for="key-features"><?php _e('Key Features:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <textarea id="key-features" name="key-features" class="input-textarea" required></textarea>
        </div>
    </div>

    <!-- Plugin Description -->
 
    <div class="form-group">
</div>
<div class="label-container">
    <label for="html-description"><?php _e('HTML Description:', 'degikart'); ?></label>
    <div class="input-container">
        <?php
        wp_editor('', 'html-description', array(
            'textarea_name' => 'html-description',
            'media_buttons' => true, // Keep media buttons enabled for images
            'tinymce' => array(
                'toolbar1' => 'bold,italic,underline,alignleft,aligncenter,alignright,link,unlink,removeformat', // Allow formatting tools
                'toolbar2' => 'bullist,numlist,blockquote,code', // Allow list, blockquote, code
                'valid_elements' => 'a[href|title],strong/b,em/i,u,ul,ol,li,p,img[src|alt|width|height|title],br', // Allow only certain HTML elements
                'extended_valid_elements' => 'img[src|alt|width|height|title]' // Allow images and specific attributes
            ),
            'quicktags' => array(
                'buttons' => 'strong,em,link,ul,ol,li,blockquote,code,img' // Allow image insertion via QuickTags
            ),
        ));
        ?>
        <p class="guide-instructions">
            Only HTML, plain text, and images are allowed. No emojis or other media types. Please be detailed as this will help in search.
            For external images, ensure they are optimized and hosted on a fast server or CDN to avoid slowing down the page.
        </p>
    </div>
</div>


    <!-- Main File -->
    <div class="form-group">
        <div class="label-container">
            <label for="main-files"><?php _e('Main File(s):', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <input type="file" id="main-files" name="main-files" class="input-file" required>
        </div>
    </div>

    <!-- Plugin Thumbnail -->
    <div class="form-group">
        <div class="label-container">
            <label for="thumbnail"><?php _e('Plugin Thumbnail:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <input type="file" id="thumbnail" name="thumbnail" class="input-file" required>
        </div>
    </div>

    <!-- Plugin Preview -->
    <div class="form-group">
        <div class="label-container">
            <label for="plugin-preview"><?php _e('Plugin Preview:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <input type="file" id="plugin-preview" name="plugin-preview" class="input-file" required>
        </div>
    </div>

    <!-- Demo URL -->
    <div class="form-group">
        <div class="label-container">
            <label for="demo-url"><?php _e('Plugin Demo URL:', 'degikart'); ?></label>
        </div>
        <div class="input-container">
            <input type="url" id="demo-url" name="demo-url" class="input-text" placeholder="<?php _e('Enter the demo URL (if available)', 'degikart'); ?>">
        </div>
    </div>

    <!-- Pricing Information -->
    <div class="form-group">
        <label><?php _e('Pricing Information:', 'degikart'); ?></label>
        <div class="input-container">
            <input type="number" name="regular-price" placeholder="<?php _e('Regular Price', 'degikart'); ?>" class="input-text" required>
            <input type="number" name="extended-price" placeholder="<?php _e('Extended Price', 'degikart'); ?>" class="input-text" required>
            <input type="number" name="support-price" placeholder="<?php _e('Support Price', 'degikart'); ?>" class="input-text" required>
        </div>
    </div>

    <!-- Compatibility and other details -->
    <div class="form-group">
        <label><?php _e('Plugin Compatibility:', 'degikart'); ?></label>
        <div class="input-container">
            <input type="text" name="compatible-browsers" placeholder="<?php _e('Compatible Browsers', 'degikart'); ?>" class="input-text" required>
            <input type="text" name="compatible-with" placeholder="<?php _e('Compatible With', 'degikart'); ?>" class="input-text" required>
        </div>
    </div>

    <div class="form-group">
        <label><?php _e('Tags and Version:', 'degikart'); ?></label>
        <div class="input-container">
            <input type="text" name="tags" placeholder="<?php _e('Tags (separate with commas)', 'degikart'); ?>" class="input-text" required>
            <input type="text" name="version" placeholder="<?php _e('Plugin Version', 'degikart'); ?>" class="input-text" required>
        </div>
    </div>

    <!-- Last Update and Published Information -->
    <div class="form-group">
        <label><?php _e('Last Update and Published Status:', 'degikart'); ?></label>
        <div class="input-container">
            <input type="text" name="last-update" placeholder="<?php _e('Last Update', 'degikart'); ?>" class="input-text" required>
            <input type="text" name="published" placeholder="<?php _e('Published Status', 'degikart'); ?>" class="input-text" required>
        </div>
    </div>

    <!-- Message to Reviewer -->
    <div class="form-group">
        <label for="message-to-reviewer"><?php _e('Message to Reviewer (Optional):', 'degikart'); ?></label>
        <div class="input-container">
            <textarea name="message-to-reviewer" placeholder="<?php _e('Message to Reviewer', 'degikart'); ?>" class="input-textarea"></textarea>
        </div>
    </div>


    <!-- Category Selection -->
    <div class="form-group">
        <label for="category"><?php _e('Category:', 'degikart'); ?></label>
        <?php
            wp_dropdown_categories(array(
                'taxonomy'         => 'plugin_category', // Custom taxonomy for 'plugin'
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
