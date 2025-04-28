<?php
/*
Template Name: Courses Upload Page
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
    $course_name = sanitize_text_field($_POST['course-name']);
    $plugin_name = sanitize_text_field($_POST['plugin-name']);
    $html_description = wp_kses_post($_POST['html-description']);
    $main_files = $_FILES['main-files'];
    $thumbnail = $_FILES['thumbnail'];
    $video_demo = $_FILES['video-demo'];  // Video Demo File
    $resolution = sanitize_text_field($_POST['resolution']);  // Video Resolution
    $closed_captions = sanitize_text_field($_POST['closed-captions']);  // Closed Captions (Yes/No)
    $regular_price = floatval($_POST['regular-price']);
    $course_length = sanitize_text_field($_POST['course-length']);
    $number_of_lessons = intval($_POST['number-of-lessons']);
    $difficulty = sanitize_text_field($_POST['difficulty']);
    $video_encoding = sanitize_text_field($_POST['video-encoding']);
    $file_size = sanitize_text_field($_POST['file-size']);  // File Size
    $tags = sanitize_text_field($_POST['tags']);  // Tags input
    $message_to_reviewer = sanitize_text_field($_POST['message-to-reviewer']);


    // Validate required fields
    if (empty($course_name) || empty($html_description) || empty($main_files) || empty($thumbnail) || empty($video_demo) || empty($regular_price) || empty($course_length) || empty($number_of_lessons) || empty($difficulty)) {
        echo __('Please fill in all required fields.', 'degikart');
    } else {
        // Check file sizes - Example: Limit each file to 10MB
        $max_file_size = 10 * 1024 * 1024; // 10MB
        if ($main_files['size'] > $max_file_size || $thumbnail['size'] > $max_file_size || $video_demo['size'] > $max_file_size) {
            echo __('One or more files exceed the allowed size limit of 10MB.', 'degikart');
        } else {

                        // Ensure WordPress media functions are available
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
            $main_files_url = handle_custom_upload($main_files);       $thumbnail_upload = wp_handle_upload($thumbnail, array('test_form' => false));
            $video_demo_upload = wp_handle_upload($video_demo, array('test_form' => false));


            if ($main_files_url && !is_wp_error($main_files_url) && $thumbnail_upload && !isset($thumbnail_upload['error']) && $video_demo_upload && !isset($video_demo_upload['error'])) {
                $file_url = $main_files_url; // The custom uploaded file URL
                $thumbnail_url = $thumbnail_upload['url'];
                $video_demo_url = $video_demo_upload['url'];

                // Create new course post
                $post_id = wp_insert_post(array(
                    'post_title' => $course_name,
                    'post_content' => $html_description,
                    'post_status' => 'pending', // Set status to pending for review
                    'post_type' => 'course',
                    'post_author' => get_current_user_id(),
                ));

                if ($post_id) {
                  
                    wp_set_post_terms($post_id, $category_id, 'course_category');
                   
                    // Save course metadata
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_excerpt' => $excerpt, // Save the excerpt directly to the post
                        'post_content' => $html_description,
                    ));
                    update_post_meta($post_id, 'regular_price', $regular_price);
                    update_post_meta($post_id, 'course_length', $course_length);
                    update_post_meta($post_id, 'number_of_lessons', $number_of_lessons);
                    update_post_meta($post_id, 'difficulty', $difficulty);
                    update_post_meta($post_id, 'video_encoding', $video_encoding);
                    update_post_meta($post_id, 'resolution', $resolution);  // Store video resolution
                    update_post_meta($post_id, 'file_size', $file_size);  // Store file size
                    update_post_meta($post_id, 'closed_captions', $closed_captions);  // Store closed captions info
                    update_post_meta($post_id, 'thumbnail_url', esc_url_raw($thumbnail_url));
                    update_post_meta($post_id, 'video_demo_url', esc_url_raw($video_demo_url));  // Store video demo URL
                    update_post_meta($post_id, 'file_url', esc_url_raw($file_url));  // Store main file URL
                    update_post_meta($post_id, 'message_to_reviewer', $message_to_reviewer);
                    update_post_meta($post_id, 'last_update', $last_update);
                    update_post_meta($post_id, 'published', $published);
                    update_post_meta($post_id, 'details', $html_description); // Save details
                    wp_set_post_terms($post_id, $tags, 'post_tag');
                    // Set the course category
                    wp_set_post_terms($post_id, $category, 'course-category');

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

                        if (!is_wp_error($video_demo_upload)) {
                            $video_demo_attachment = array(
                                'post_mime_type' => $video_demo_upload['type'],
                                'post_title' => sanitize_file_name($video_demo['name']),
                                'post_content' => '',
                                'post_status' => 'inherit',
                            );
                            $video_demo_attachment_id = wp_insert_attachment($video_demo_attachment, $video_demo_upload['file'], $post_id);
                            $video_demo_attachment_data = wp_generate_attachment_metadata($video_demo_attachment_id, $video_demo_upload['file']);
                            wp_update_attachment_metadata($video_demo_attachment_id, $video_demo_attachment_data);
                        
                            // Optionally, you can link the video demo attachment with the post
                            update_post_meta($post_id, 'video_demo_attachment_id', $video_demo_attachment_id);
                        }

                    // Redirect to the uploaded courses page after successful submission
                    echo '<script type="text/javascript">
                        window.location.href = "' . home_url('/uploaded-products') . '";
                    </script>';
                    exit;
                } else {
                    echo __('Error creating course.', 'degikart');
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
        <!-- Course Name -->
        <div class="form-group">
            <div class="label-container">
                <label for="course-name"><?php _e('Course Name:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="course-name" name="course-name" class="input-text" maxlength="100" required>
            </div>
        </div>

        <!-- HTML Description -->
 
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


        <!-- Main Files -->
        <div class="form-group">
            <div class="label-container">
                <label for="main-files"><?php _e('Main File(s):', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="main-files" name="main-files" class="input-file" required>
            </div>
        </div>

        <!-- Thumbnail -->
        <div class="form-group">
            <div class="label-container">
                <label for="thumbnail"><?php _e('Thumbnail:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="thumbnail" name="thumbnail" class="input-file" required>
            </div>
        </div>

        <!-- Video Demo -->
        <div class="form-group">
            <div class="label-container">
                <label for="video-demo"><?php _e('Video Demo:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="file" id="video-demo" name="video-demo" class="input-file" required>
            </div>
        </div>

        <!-- Closed Captions -->
        <div class="form-group">
            <div class="label-container">
                <label for="closed-captions"><?php _e('Closed Captions:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <select id="closed-captions" name="closed-captions" class="input-select">
                    <option value="no"><?php _e('No', 'degikart'); ?></option>
                    <option value="yes"><?php _e('Yes', 'degikart'); ?></option>
                </select>
            </div>
        </div>

        <!-- Resolution -->
        <div class="form-group">
            <div class="label-container">
                <label for="resolution"><?php _e('Resolution:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="resolution" name="resolution" class="input-text" value="1280x720" required>
            </div>
        </div>

        <!-- File Size -->
        <div class="form-group">
            <div class="label-container">
                <label for="file-size"><?php _e('File Size:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="file-size" name="file-size" class="input-text" required>
            </div>
        </div>

        <!-- Regular License Price -->
        <div class="form-group">
            <div class="label-container">
                <label for="regular-price"><?php _e('Regular License Price:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="number" id="regular-price" name="regular-price" class="input-number" step="0.01" required>
            </div>
        </div>

        <!-- Tags -->
        <div class="form-group">
    <div class="label-container">
        <label for="tags"><?php _e('Tags:', 'degikart'); ?></label>
        </div><div class="input-container">
             <input type="text" id="tags" name="tags" class="input-text" required>
             </div></div>

        <!-- Course Length -->
        <div class="form-group">
            <div class="label-container">
                <label for="course-length"><?php _e('Total Course Video Length:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="text" id="course-length" name="course-length" class="input-text" required placeholder="e.g. 1:02:15">
            </div>
        </div>

        <!-- Number of Lessons -->
        <div class="form-group">
            <div class="label-container">
                <label for="number-of-lessons"><?php _e('Number of Lessons:', 'degikart'); ?></label>
            </div>
            <div class="input-container">
                <input type="number" id="number-of-lessons" name="number-of-lessons" class="input-number" required>
            </div>
        </div>

<!-- Message to Reviewer -->
<div class="form-group">
   <div class="label-container">
       <label for="message-to-reviewer"><?php _e('Message to Reviewer:', 'degikart'); ?></label>
   </div>
   <div class="input-container">
       <textarea id="message-to-reviewer" name="message-to-reviewer" class="input-textarea" placeholder="Enter a message to the reviewer (optional)"></textarea>
   </div>
</div>

<!-- Video Encoding -->
<div class="form-group">
   <div class="label-container">
       <label for="video-encoding"><?php _e('Video Encoding:', 'degikart'); ?></label>
   </div>
   <div class="input-container">
       <input type="text" id="video-encoding" name="video-encoding" class="input-text" required placeholder="e.g., H.264, VP9">
   </div>
</div>

<!-- Difficulty Level -->
<div class="form-group">
   <div class="label-container">
       <label for="difficulty"><?php _e('Difficulty Level:', 'degikart'); ?></label>
   </div>
   <div class="input-container">
       <select id="difficulty" name="difficulty" class="input-select" required>
           <option value="beginner"><?php _e('Beginner', 'degikart'); ?></option>
           <option value="intermediate"><?php _e('Intermediate', 'degikart'); ?></option>
           <option value="advanced"><?php _e('Advanced', 'degikart'); ?></option>
       </select>
   </div>
</div>

    <!-- Category Selection -->
    <div class="form-group">
        <label for="category"><?php _e('Category:', 'degikart'); ?></label>
        <?php
            wp_dropdown_categories(array(
                'taxonomy'         => 'course_category', // Custom taxonomy for 'blogger'
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
<?php get_footer(); ?>
