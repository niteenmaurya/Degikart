<?php
/*
Plugin Name: Degikart Plugin
Plugin URI: http://yourwebsite.com/degikart-plugin
Description: Essential custom functionality and features for the Degikart theme. Enhances and extends the theme's capabilities for a complete digital marketplace experience.
Version: 1.0
Author: Niteen Maurya
Author URI: http://yourwebsite.com
License: GPL-2.0-or-later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: degikart
Requires at least: 5.0
Tested up to: 6.3
Requires PHP: 7.4
Theme Dependency: Degikart (Commercial License Required)
*/

// Custom functionality starts here

// Hide admin bar for non-admin users
add_action('after_setup_theme', 'degikart_remove_admin_bar');
function degikart_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

// Redirect non-admin users away from wp-admin
add_action('admin_init', 'degikart_redirect_non_admin_users');
function degikart_redirect_non_admin_users() {
    if (!current_user_can('administrator') && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
}
// Function to display the profile page
function degikart_custom_profile_page() { 
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $profile_picture_id = get_user_meta($current_user->ID, 'profile_picture', true);
        $profile_picture_url = wp_get_attachment_url($profile_picture_id);

        // Retrieve social media links and skills from user meta
        $facebook_url = get_user_meta($current_user->ID, 'facebook_url', true);
        $twitter_url = get_user_meta($current_user->ID, 'twitter_url', true);
        $instagram_url = get_user_meta($current_user->ID, 'instagram_url', true);
        $linkedin_url = get_user_meta($current_user->ID, 'linkedin_url', true);
        $skills = get_user_meta($current_user->ID, 'skills', true); // Retrieve skills

        // Add a query string to the image URL to ensure it updates
        if ($profile_picture_url) {
            $profile_picture_url = add_query_arg('v', time(), $profile_picture_url);
        }

        ob_start();
        ?>
        <div class="profile-page">
            <h2>Profile Details</h2>
            <?php if ($profile_picture_url): ?>
                <img src="<?php echo esc_url($profile_picture_url); ?>" alt="Profile Picture" width="100" height="100"><br>
            <?php endif; ?>

            <!-- Success message after form update -->
            <?php
            if (isset($_GET['updated']) && $_GET['updated'] == 'true') {
                echo '<div class="success-message">Profile updated successfully!</div>';
            }
            ?>

            <form id="profile-form" method="post" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo esc_attr($current_user->user_login); ?>" disabled><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>"><br>

                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>"><br>

                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>"><br>

                <label for="password">New Password:</label>
                <input type="password" id="password" name="password"><br>

                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture"><br>

                <!-- Skills Input -->
                <label for="skills">Skills (comma separated):</label>
                <input type="text" id="skills" name="skills" value="<?php echo esc_attr($skills); ?>"><br>

                <!-- Social Media Links -->
                <label for="facebook_url">Facebook URL:</label>
                <input type="url" id="facebook_url" name="facebook_url" value="<?php echo esc_url($facebook_url); ?>"><br>

                <label for="twitter_url">Twitter URL:</label>
                <input type="url" id="twitter_url" name="twitter_url" value="<?php echo esc_url($twitter_url); ?>"><br>

                <label for="instagram_url">Instagram URL:</label>
                <input type="url" id="instagram_url" name="instagram_url" value="<?php echo esc_url($instagram_url); ?>"><br>

                <label for="linkedin_url">LinkedIn URL:</label>
                <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo esc_url($linkedin_url); ?>"><br>

                <div class="button-container">
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-button">Logout</a>
                    <button type="submit" name="update_profile" class="update-button">Update Profile</button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    } else {
        return '<p>You need to be logged in to view this page.</p>';
    }
}
add_shortcode('custom_profile', 'degikart_custom_profile_page');

// Function to handle profile updates
function degikart_handle_profile_update() {
    if (isset($_POST['update_profile'])) {
        // Include necessary media functions for handling file uploads
        if (!function_exists('media_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
        }

        $current_user = wp_get_current_user();

        // Handle email update
        if (!empty($_POST['email'])) {
            wp_update_user(array('ID' => $current_user->ID, 'user_email' => sanitize_email($_POST['email'])));
        }

        // Handle first name update
        if (!empty($_POST['first_name'])) {
            update_user_meta($current_user->ID, 'first_name', sanitize_text_field($_POST['first_name']));
        }

        // Handle last name update
        if (!empty($_POST['last_name'])) {
            update_user_meta($current_user->ID, 'last_name', sanitize_text_field($_POST['last_name']));
        }

        // Handle password update
        if (!empty($_POST['password'])) {
            wp_set_password($_POST['password'], $current_user->ID);
        }

        // Handle profile picture upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $uploaded = media_handle_upload('profile_picture', 0);
            if (!is_wp_error($uploaded)) {
                update_user_meta($current_user->ID, 'profile_picture', $uploaded);
            }
        }

        // Handle skills update
        if (!empty($_POST['skills'])) {
            update_user_meta($current_user->ID, 'skills', sanitize_text_field($_POST['skills']));
        }

        // Handle social media link updates
        // Only update if the field is not empty
        if (!empty($_POST['facebook_url'])) {
            update_user_meta($current_user->ID, 'facebook_url', esc_url($_POST['facebook_url']));
        } else {
            delete_user_meta($current_user->ID, 'facebook_url');  // Remove if empty
        }

        if (!empty($_POST['twitter_url'])) {
            update_user_meta($current_user->ID, 'twitter_url', esc_url($_POST['twitter_url']));
        } else {
            delete_user_meta($current_user->ID, 'twitter_url');  // Remove if empty
        }

        if (!empty($_POST['instagram_url'])) {
            update_user_meta($current_user->ID, 'instagram_url', esc_url($_POST['instagram_url']));
        } else {
            delete_user_meta($current_user->ID, 'instagram_url');  // Remove if empty
        }

        if (!empty($_POST['linkedin_url'])) {
            update_user_meta($current_user->ID, 'linkedin_url', esc_url($_POST['linkedin_url']));
        } else {
            delete_user_meta($current_user->ID, 'linkedin_url');  // Remove if empty
        }

        // Redirect to the same page after profile update
        wp_redirect(add_query_arg('updated', 'true', get_permalink()));
        exit;
    }
}
add_action('wp_loaded', 'degikart_handle_profile_update');


// Function to display the author's profile picture
function degikart_display_author_profile_picture($user_id) {
    $profile_picture_id = get_user_meta($user_id, 'profile_picture', true);
    if ($profile_picture_id) {
        $profile_picture_url = wp_get_attachment_url($profile_picture_id);
        if ($profile_picture_url) {
            echo '<img src="' . esc_url($profile_picture_url) . '" alt="Profile Picture" width="50" height="50">';
        }
    }
}

// Callback function to display the meta box
function degikart_course_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('degikart_course_nonce_action', 'degikart_course_nonce');

    // Retrieve saved values, if any
    $course_duration = get_post_meta($post->ID, '_course_duration', true);
    $course_lessons = get_post_meta($post->ID, '_course_lessons', true);
    $course_difficulty = get_post_meta($post->ID, '_course_difficulty', true);
    $course_video_encoding = get_post_meta($post->ID, '_course_video_encoding', true);

    ?>
    <p>
        <label for="course_duration"><?php _e('Course Duration (in hours):', 'degikart'); ?></label><br>
        <input type="text" name="course_duration" id="course_duration" value="<?php echo esc_attr($course_duration); ?>" />
    </p>
    <p>
        <label for="course_lessons"><?php _e('Number of Lessons:', 'degikart'); ?></label><br>
        <input type="number" name="course_lessons" id="course_lessons" value="<?php echo esc_attr($course_lessons); ?>" />
    </p>
    <p>
        <label for="course_difficulty"><?php _e('Difficulty Level:', 'degikart'); ?></label><br>
        <select name="course_difficulty" id="course_difficulty">
            <option value="easy" <?php selected($course_difficulty, 'easy'); ?>>Easy</option>
            <option value="medium" <?php selected($course_difficulty, 'medium'); ?>>Medium</option>
            <option value="hard" <?php selected($course_difficulty, 'hard'); ?>>Hard</option>
        </select>
    </p>
    <p>
        <label for="course_video_encoding"><?php _e('Video Encoding:', 'degikart'); ?></label><br>
        <input type="text" name="course_video_encoding" id="course_video_encoding" value="<?php echo esc_attr($course_video_encoding); ?>" />
    </p>
    <?php
}

// Save the custom fields
function degikart_save_course_meta($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['degikart_course_nonce']) || !wp_verify_nonce($_POST['degikart_course_nonce'], 'degikart_course_nonce_action')) {
        return;
    }

    // Check if this is a valid post type
    if ('course' != get_post_type($post_id)) {
        return;
    }

    // Save custom fields
    if (isset($_POST['course_duration'])) {
        update_post_meta($post_id, '_course_duration', sanitize_text_field($_POST['course_duration']));
    }
    if (isset($_POST['course_lessons'])) {
        update_post_meta($post_id, '_course_lessons', sanitize_text_field($_POST['course_lessons']));
    }
    if (isset($_POST['course_difficulty'])) {
        update_post_meta($post_id, '_course_difficulty', sanitize_text_field($_POST['course_difficulty']));
    }
    if (isset($_POST['course_video_encoding'])) {
        update_post_meta($post_id, '_course_video_encoding', sanitize_text_field($_POST['course_video_encoding']));
    }
}
add_action('save_post', 'degikart_save_course_meta');

// Display courses on the front-end (Example Shortcode)
function degikart_display_courses() {
    $args = array(
        'post_type' => 'course',
        'posts_per_page' => 10
    );
    $courses = new WP_Query($args);

    if ($courses->have_posts()) {
        $output = '<ul>';
        while ($courses->have_posts()) {
            $courses->the_post();
            $output .= '<li>';
            $output .= '<h2>' . get_the_title() . '</h2>';
            $output .= '<p>' . get_the_excerpt() . '</p>';
            $output .= '</li>';
        }
        $output .= '</ul>';
    } else {
        $output = '<p>No courses found.</p>';
    }

    wp_reset_postdata();
    return $output;
}
add_shortcode('display_courses', 'degikart_display_courses');










// Register Custom Admin Menu for "Products Category"
function degikart_register_products_category_menu() {
    // Create the custom parent menu for "Products Category"
    add_menu_page(
        'Products',            // Page title
        'Products',            // Menu title
        'manage_options',               // Capability
        'degikart_products_category',    // Menu slug (unique identifier for the menu)
        'degikart_products_category_page', // Function for the page content
        'dashicons-category',           // Icon for the menu
        21                               // Position of the menu (21 places it after the default menus)
    );
}

// Callback function for the "Products Category" page with Tabs
function degikart_products_category_page() {
    // Check for the active tab, default is 'posttype'
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'posttype';
    ?>
    <div class="wrap">
    <h1><?php _e('Manage Categories, Tags, and Post Types for Products, Bloggers, Courses, WordPress, Ecommerce, and More', 'degikart'); ?></h1>
<p><?php _e('Here you can manage the categories, tags, and post types for your custom content types like Products, Bloggers, Courses, WordPress, Ecommerce, and others.', 'degikart'); ?></p>

        
        <!-- Tabs for navigation -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=degikart_products_category&tab=posttype" class="nav-tab <?php echo $active_tab == 'posttype' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Manage Post Types', 'degikart'); ?>
            </a>
        </h2>
        
        <?php
        // Show content based on the active tab
        if ($active_tab == 'posttype') {
            ?>
            <!-- Manage Custom Post Types Content -->
            <h2><?php _e('Manage Custom Post Types', 'degikart'); ?></h2>
            <p><?php _e('Here you can manage the custom post types and their respective categories and tags.', 'degikart'); ?></p>

            <!-- Wrapping the table in a container for horizontal scrolling -->
            <div style="overflow-x: auto;">
            <table class="widefat fixed" cellspacing="0" style="table-layout: auto;">
    <thead>
        <tr>
            <th><?php _e('Post Type', 'degikart'); ?></th>
            <th><?php _e('Posts', 'degikart'); ?></th>
            <th><?php _e('Categories', 'degikart'); ?></th>
            <th><?php _e('Tags', 'degikart'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $post_types = get_post_types(['public' => true], 'names'); // Get public post types

        // Exclude 'post', 'page', and 'attachment' post types
        $post_types = array_diff($post_types, ['post', 'page', 'attachment']); 

        foreach ($post_types as $type) {
            // Start each row in the table
            ?>
            <tr>
                <td><?php echo ucfirst($type); ?></td>
                <td><a href="<?php echo admin_url("edit.php?post_type={$type}"); ?>" class="button button-primary"><?php echo sprintf(__('Manage %s', 'degikart'), ucfirst($type)); ?></a></td>
                
                <td>
                    <?php 
                    // Check for categories associated with this post type
                    $taxonomies = get_object_taxonomies($type, 'objects');
                    $category_link = '';
                    foreach ($taxonomies as $taxonomy) {
                        if (strpos($taxonomy->name, 'category') !== false) {
                            $category_link = admin_url("edit-tags.php?taxonomy={$taxonomy->name}");
                        }
                    }
                    if ($category_link) {
                        echo '<a href="' . $category_link . '" class="button button-secondary">' . 
                             __('Categories', 'degikart') . '</a>';
                    }
                    ?>
                </td>
                
                <td>
                    <?php 
                    // Check for tags associated with this post type
                    $tag_link = '';
                    $has_tag = false; // Flag to check if there are tags for this post type
                    foreach ($taxonomies as $taxonomy) {
                        if (strpos($taxonomy->name, 'tag') !== false) {
                            $tag_link = admin_url("edit-tags.php?taxonomy={$taxonomy->name}");
                            $has_tag = true; // Tag found, set flag to true
                        }
                    }
                    if ($has_tag) {
                        echo '<a href="' . $tag_link . '" class="button button-secondary">' . 
                             __('Tags', 'degikart') . '</a>';
                    } else {
                        echo __('No Tags Available', 'degikart'); // Show message if no tags
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

            </div>

        <?php
        }
        ?>
    </div>

    <style>
        .nav-tab-wrapper {
            margin-bottom: 20px;
        }

        .nav-tab-active {
            background-color: #0073aa;
            border-color: #0073aa;
            color: white;
        }

        .widefat th, .widefat td {
            padding: 10px;
            text-align: left;
            word-wrap: break-word; /* Ensures long text will wrap to the next line */
        }

        .widefat th {
            text-align: left;
        }

        .button {
            font-size: 14px;
            padding: 10px 20px;
        }

        .button-primary {
            background-color: #0073aa;
            border-color: #0073aa;
            color: #fff;
        }

        .button-secondary {
            background-color: #f1f1f1;
            border-color: #ccc;
            color: #333;
        }

        table.widefat {
            width: 100%;
            margin-top: 20px;
            table-layout: auto; /* Auto layout for column width adjustment */
        }

        .widefat th, .widefat td {
            border: 1px solid #ddd;
            text-align: left;
        }

        /* Add horizontal scroll to the table */
        .widefat {
            min-width: 900px; /* Minimum width to allow for scrolling */
        }
    </style>
    <?php
}

// Hook into 'admin_menu' to add the "Products Category" menu
add_action('admin_menu', 'degikart_register_products_category_menu');

 

// Register Custom Post Type for Courses
function degikart_create_custom_course_post_type() {
    $labels = array(
        'name' => _x('Courses', 'Post Type General Name', 'degikart'),
        'singular_name' => _x('Course', 'Post Type Singular Name', 'degikart'),
        'menu_name' => __('Courses', 'degikart'),
        'name_admin_bar' => __('Course', 'degikart'),
        'all_items'         => __('All Courses', 'degikart'),
        'add_new_item' => __('Add New Course', 'degikart'),
        'new_item' => __('New Course', 'degikart'),
        'edit_item' => __('Edit Course', 'degikart'),
        'view_item' => __('View Course', 'degikart'),
        'search_items' => __('Search Courses', 'degikart'),
        'not_found' => __('No courses found', 'degikart'),
        'not_found_in_trash' => __('No courses found in Trash', 'degikart'),
    );
    $args = array(
        'label' => __('Course', 'degikart'),
        'description' => __('Course Description', 'degikart'),
        'labels' => $labels,
        'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'comments'),
        'taxonomies' => array('post_tag', 'course_category'), // Attach both categories and tags
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu'          => 'degikart_menu',
        'menu_position' => 5,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'course'),
    );
    register_post_type('course', $args);
}
add_action('init', 'degikart_create_custom_course_post_type', 0);

// Register Custom Taxonomy for Courses
function degikart_create_course_taxonomy() {
    $labels = array(
        'name'              => _x('Course Categories', 'Taxonomy General Name', 'degikart'),
        'singular_name'     => _x('Course Category', 'Taxonomy Singular Name', 'degikart'),
        'menu_name'         => __('Course Categories', 'degikart'),
        'all_items'         => __('All Courses', 'degikart'),
        'parent_item'       => __('Parent Category', 'degikart'),
        'parent_item_colon' => __('Parent Category:', 'degikart'),
        'new_item_name'     => __('New Category Name', 'degikart'),
        'add_new_item'      => __('Add New Category', 'degikart'),
        'edit_item'         => __('Edit Category', 'degikart'),
        'update_item'       => __('Update Category', 'degikart'),
        'view_item'         => __('View Category', 'degikart'),
        'separate_items_with_commas' => __('Separate categories with commas', 'degikart'),
        'add_or_remove_items'        => __('Add or remove categories', 'degikart'),
        'choose_from_most_used'      => __('Choose from the most used', 'degikart'),
        'not_found'                  => __('No categories found.', 'degikart'),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true, // Set to true for parent-child relationship
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'rewrite'           => array('slug' => 'course-category'), // Custom slug
    );

    register_taxonomy('course_category', array('course'), $args);
}
add_action('init', 'degikart_create_course_taxonomy', 0);

// Register Custom Post Type 'Blogger'
function degikart_create_blogger_post_type() {
    $labels = array(
        'name'                  => _x('Bloggers', 'Post Type General Name', 'degikart'),
        'singular_name'         => _x('Blogger', 'Post Type Singular Name', 'degikart'),
        'menu_name'             => __('Bloggers', 'degikart'),
        'name_admin_bar'        => __('Blogger', 'degikart'),
        'archives'              => __('Blogger Archives', 'degikart'),
        'attributes'            => __('Blogger Attributes', 'degikart'),
        'parent_item_colon'     => __('Parent Blogger:', 'degikart'),
        'all_items'             => __('All Bloggers', 'degikart'),
        'add_new_item'          => __('Add New Blogger', 'degikart'),
        'add_new'               => __('Add New', 'degikart'),
        'new_item'              => __('New Blogger', 'degikart'),
        'edit_item'             => __('Edit Blogger', 'degikart'),
        'update_item'           => __('Update Blogger', 'degikart'),
        'view_item'             => __('View Blogger', 'degikart'),
        'view_items'            => __('View Bloggers', 'degikart'),
        'search_items'          => __('Search Blogger', 'degikart'),
        'not_found'             => __('Not found', 'degikart'),
        'not_found_in_trash'    => __('Not found in Trash', 'degikart'),
        'featured_image'        => __('Featured Image', 'degikart'),
        'set_featured_image'    => __('Set featured image', 'degikart'),
        'remove_featured_image' => __('Remove featured image', 'degikart'),
        'use_featured_image'    => __('Use as featured image', 'degikart'),
        'insert_into_item'      => __('Insert into Blogger', 'degikart'),
        'uploaded_to_this_item' => __('Uploaded to this Blogger', 'degikart'),
        'items_list'            => __('Bloggers list', 'degikart'),
        'items_list_navigation' => __('Bloggers list navigation', 'degikart'),
        'filter_items_list'     => __('Filter Bloggers list', 'degikart'),
    );

    $args = array(
        'label'                 => __('Blogger', 'degikart'),
        'description'           => __('Blogger Description', 'degikart'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'comments'),
        'taxonomies'            => array('post_tag'), // Default taxonomies
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'degikart_menu',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'blogger'),
    );

    // Registering the 'Blogger' post type
    register_post_type('blogger', $args);
}
add_action('init', 'degikart_create_blogger_post_type', 0);

// Register Custom Taxonomy for 'Blogger' category
function degikart_register_blogger_taxonomy() {
    $labels = array(
        'name'              => _x('Blogger Categories', 'taxonomy general name', 'degikart'),
        'singular_name'     => _x('Blogger Category', 'taxonomy singular name', 'degikart'),
        'search_items'      => __('Search Blogger Categories', 'degikart'),
        'all_items'         => __('All Blogger Categories', 'degikart'),
        'parent_item'       => __('Parent Blogger Category', 'degikart'),
        'parent_item_colon' => __('Parent Blogger Category:', 'degikart'),
        'edit_item'         => __('Edit Blogger Category', 'degikart'),
        'update_item'       => __('Update Blogger Category', 'degikart'),
        'add_new_item'      => __('Add New Blogger Category', 'degikart'),
        'new_item_name'     => __('New Blogger Category Name', 'degikart'),
        'menu_name'         => __('Blogger Category', 'degikart'),
    );

    $args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array(
            'slug' => 'blogger-category', // Use a more unique slug
            'with_front' => false, // Don't add the front base (like /blog)
            'hierarchical' => true, // Enable hierarchical URL structure
        ),
    );

    // Register taxonomy
    register_taxonomy('blogger_category', array('blogger'), $args);
}

// Hook into 'init' to register the 'Blogger' taxonomy
add_action('init', 'degikart_register_blogger_taxonomy', 0);




  
 
function degikart_create_wordpress_post_type() {
    $labels = array(
        'name'                  => _x('Wordpresss', 'Post Type General Name', 'degikart'),
        'singular_name'         => _x('Wordpress', 'Post Type Singular Name', 'degikart'),
        'menu_name'             => __('Wordpresss', 'degikart'),
        'name_admin_bar'        => __('Wordpress', 'degikart'),
        'archives'              => __('Wordpress Archives', 'degikart'),
        'attributes'            => __('Wordpress Attributes', 'degikart'),
        'parent_item_colon'     => __('Parent Wordpress:', 'degikart'),
        'all_items'             => __('All Wordpresss', 'degikart'),
        'add_new_item'          => __('Add New Wordpress', 'degikart'),
        'add_new'               => __('Add New', 'degikart'),
        'new_item'              => __('New Wordpress', 'degikart'),
        'edit_item'             => __('Edit Wordpress', 'degikart'),
        'update_item'           => __('Update Wordpress', 'degikart'),
        'view_item'             => __('View Wordpress', 'degikart'),
        'view_items'            => __('View Wordpresss', 'degikart'),
        'search_items'          => __('Search Wordpress', 'degikart'),
        'not_found'             => __('Not found', 'degikart'),
        'not_found_in_trash'    => __('Not found in Trash', 'degikart'),
        'featured_image'        => __('Featured Image', 'degikart'),
        'set_featured_image'    => __('Set featured image', 'degikart'),
        'remove_featured_image' => __('Remove featured image', 'degikart'),
        'use_featured_image'    => __('Use as featured image', 'degikart'),
        'insert_into_item'      => __('Insert into Wordpress', 'degikart'),
        'uploaded_to_this_item' => __('Uploaded to this Wordpress', 'degikart'),
        'items_list'            => __('Wordpresss list', 'degikart'),
        'items_list_navigation' => __('Wordpresss list navigation', 'degikart'),
        'filter_items_list'     => __('Filter Wordpresss list', 'degikart'),
    );

    $args = array(
        'label'                 => __('Wordpress', 'degikart'),
        'description'           => __('Wordpress Description', 'degikart'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'comments'),
        'taxonomies'            => array('wordpress_category'), // Use custom taxonomy
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'degikart_menu',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'wordpress'),
    );

    // Registering the 'Wordpress' post type
    register_post_type('wordpress', $args);
}

add_action('init', 'degikart_create_wordpress_post_type', 0);


// Register Custom Taxonomy for 'Wordpress' category
function degikart_register_wordpress_taxonomy() {
    $labels = array(
        'name'              => _x('Wordpress Categories', 'taxonomy general name', 'degikart'),
        'singular_name'     => _x('Wordpress Category', 'taxonomy singular name', 'degikart'),
        'search_items'      => __('Search Wordpress Categories', 'degikart'),
        'all_items'         => __('All Wordpress Categories', 'degikart'),
        'parent_item'       => __('Parent Wordpress Category', 'degikart'),
        'parent_item_colon' => __('Parent Wordpress Category:', 'degikart'),
        'edit_item'         => __('Edit Wordpress Category', 'degikart'),
        'update_item'       => __('Update Wordpress Category', 'degikart'),
        'add_new_item'      => __('Add New Wordpress Category', 'degikart'),
        'new_item_name'     => __('New Wordpress Category Name', 'degikart'),
        'menu_name'         => __('Wordpress Category', 'degikart'),
    );

    $args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
       'rewrite'           => array(
        'slug' => 'wordpress-category', // Use a more unique slug
            'with_front' => false, // Don't add the front base (like /blog)
            'hierarchical' => true, // Enable hierarchical URL structure
        ),
    );

    // Register taxonomy
    register_taxonomy('wordpress_category', array('wordpress'), $args);
}

add_action('init', 'degikart_register_wordpress_taxonomy', 0);
 
 

// Register Custom Post Type 'plugin'
function degikart_create_plugin_post_type() {
    $labels = array(
        'name'                  => _x('plugin', 'Post Type General Name', 'degikart'),
        'singular_name'         => _x('plugin', 'Post Type Singular Name', 'degikart'),
        'menu_name'             => __('plugin', 'degikart'),
        'name_admin_bar'        => __('plugin', 'degikart'),
        'archives'              => __('plugin Archives', 'degikart'),
        'attributes'            => __('plugin Attributes', 'degikart'),
        'parent_item_colon'     => __('Parent plugin:', 'degikart'),
        'all_items'             => __('All plugin', 'degikart'),
        'add_new_item'          => __('Add New plugin', 'degikart'),
        'add_new'               => __('Add New', 'degikart'),
        'new_item'              => __('New plugin', 'degikart'),
        'edit_item'             => __('Edit plugin', 'degikart'),
        'update_item'           => __('Update plugin', 'degikart'),
        'view_item'             => __('View plugin', 'degikart'),
        'view_items'            => __('View plugin', 'degikart'),
        'search_items'          => __('Search plugin', 'degikart'),
        'not_found'             => __('Not found', 'degikart'),
        'not_found_in_trash'    => __('Not found in Trash', 'degikart'),
        'featured_image'        => __('Featured Image', 'degikart'),
        'set_featured_image'    => __('Set featured image', 'degikart'),
        'remove_featured_image' => __('Remove featured image', 'degikart'),
        'use_featured_image'    => __('Use as featured image', 'degikart'),
        'insert_into_item'      => __('Insert into plugin', 'degikart'),
        'uploaded_to_this_item' => __('Uploaded to this plugin', 'degikart'),
        'items_list'            => __('plugin list', 'degikart'),
        'items_list_navigation' => __('plugin list navigation', 'degikart'),
        'filter_items_list'     => __('Filter plugin list', 'degikart'),
    );

    $args = array(
        'label'                 => __('plugin', 'degikart'),
        'description'           => __('plugin Description', 'degikart'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'comments'),
        'taxonomies'            => array('plugin_category', 'post_tag'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'degikart_menu',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'plugin'),
    );

    // Registering the 'plugin' post type
    register_post_type('plugin', $args);
}
add_action('init', 'degikart_create_plugin_post_type', 0);

// Register Custom Taxonomy for 'plugin' post type
function degikart_create_plugin_category_taxonomy() {
    $labels = array(
        'name'              => _x('plugin Categories', 'taxonomy general name', 'degikart'),
        'singular_name'     => _x('plugin Category', 'taxonomy singular name', 'degikart'),
        'search_items'      => __('Search plugin Categories', 'degikart'),
        'all_items'         => __('All plugin Categories', 'degikart'),
        'parent_item'       => __('Parent plugin Category', 'degikart'),
        'parent_item_colon' => __('Parent plugin Category:', 'degikart'),
        'edit_item'         => __('Edit plugin Category', 'degikart'),
        'update_item'       => __('Update plugin Category', 'degikart'),
        'add_new_item'      => __('Add New plugin Category', 'degikart'),
        'new_item_name'     => __('New plugin Category Name', 'degikart'),
        'menu_name'         => __('plugin Categories', 'degikart'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'plugin-category'),
        'meta_box_cb'       => 'post_categories_meta_box', // Ensures it shows below tags
    );

    register_taxonomy('plugin_category', array('plugin'), $args);

}
add_action('init', 'degikart_create_plugin_category_taxonomy', 0);

function degikart_create_ecommerce_post_type() {
    $labels = array(
        'name'                  => _x('ecommerces', 'Post Type General Name', 'degikart'),
        'singular_name'         => _x('ecommerce', 'Post Type Singular Name', 'degikart'),
        'menu_name'             => __('ecommerces', 'degikart'),
        'name_admin_bar'        => __('ecommerce', 'degikart'),
        'archives'              => __('ecommerce Archives', 'degikart'),
        'attributes'            => __('ecommerce Attributes', 'degikart'),
        'parent_item_colon'     => __('Parent ecommerce:', 'degikart'),
        'all_items'             => __('All ecommerces', 'degikart'),
        'add_new_item'          => __('Add New ecommerce', 'degikart'),
        'add_new'               => __('Add New', 'degikart'),
        'new_item'              => __('New ecommerce', 'degikart'),
        'edit_item'             => __('Edit ecommerce', 'degikart'),
        'update_item'           => __('Update ecommerce', 'degikart'),
        'view_item'             => __('View ecommerce', 'degikart'),
        'view_items'            => __('View ecommerces', 'degikart'),
        'search_items'          => __('Search ecommerce', 'degikart'),
        'not_found'             => __('Not found', 'degikart'),
        'not_found_in_trash'    => __('Not found in Trash', 'degikart'),
        'featured_image'        => __('Featured Image', 'degikart'),
        'set_featured_image'    => __('Set featured image', 'degikart'),
        'remove_featured_image' => __('Remove featured image', 'degikart'),
        'use_featured_image'    => __('Use as featured image', 'degikart'),
        'insert_into_item'      => __('Insert into ecommerce', 'degikart'),
        'uploaded_to_this_item' => __('Uploaded to this ecommerce', 'degikart'),
        'items_list'            => __('ecommerces list', 'degikart'),
        'items_list_navigation' => __('ecommerces list navigation', 'degikart'),
        'filter_items_list'     => __('Filter ecommerces list', 'degikart'),
    );

    $args = array(
        'label'                 => __('ecommerce', 'degikart'),
        'description'           => __('ecommerce Description', 'degikart'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'comments'),
        'taxonomies'            => array('ecommerce_category'), // Use custom taxonomy
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => 'degikart_menu',
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array('slug' => 'ecommerce'),
    );

    // Registering the 'ecommerce' post type
    register_post_type('ecommerce', $args);
}

add_action('init', 'degikart_create_ecommerce_post_type', 0);


// Register Custom Taxonomy for 'ecommerce' category
function degikart_register_ecommerce_taxonomy() {
    $labels = array(
        'name'              => _x('ecommerce Categories', 'taxonomy general name', 'degikart'),
        'singular_name'     => _x('ecommerce Category', 'taxonomy singular name', 'degikart'),
        'search_items'      => __('Search ecommerce Categories', 'degikart'),
        'all_items'         => __('All ecommerce Categories', 'degikart'),
        'parent_item'       => __('Parent ecommerce Category', 'degikart'),
        'parent_item_colon' => __('Parent ecommerce Category:', 'degikart'),
        'edit_item'         => __('Edit ecommerce Category', 'degikart'),
        'update_item'       => __('Update ecommerce Category', 'degikart'),
        'add_new_item'      => __('Add New ecommerce Category', 'degikart'),
        'new_item_name'     => __('New ecommerce Category Name', 'degikart'),
        'menu_name'         => __('ecommerce Category', 'degikart'),
    );

    $args = array(
        'hierarchical'      => true, // Like categories
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
       'rewrite'           => array(
        'slug' => 'ecommerce-category', // Use a more unique slug
            'with_front' => false, // Don't add the front base (like /blog)
            'hierarchical' => true, // Enable hierarchical URL structure
        ),
    );

    // Register taxonomy
    register_taxonomy('ecommerce_category', array('ecommerce'), $args);
}

add_action('init', 'degikart_register_ecommerce_taxonomy', 0);

function create_categories() {
    // Define the parent category
    $parent_category_name = 'WordPress'; // This is the parent category

    // Define the child categories based on the <option> values
    $child_categories = array(
        'Template Kits',
        'Creative & Design',
        'Business & Services',
        'Health & Medical',
        'Fashion & Beauty',
        'Shopping & eCommerce',
        'Travel & Accommodation',
        'Food & Drink',
        'Sport & Fitness',
        'Technology & Apps',
        'Education',
        'Non-Profit & Religion',
        'Events & Entertainment',
        'Personal & CVv',
        'Weddings',
        'Blogs & Podcasts',
        'Real Estate & Construction',
        'Automotive & Transportation',
        'News & Magazines',
        'Finance & Law',
        'Music & Bands',
        'Film & TV',
        'Kids & Babies',
        'Photography',
        'Miscellaneous'
    );

    // Check if the parent category exists in the custom 'wordpress_category' taxonomy
    $parent_category = get_term_by( 'name', $parent_category_name, 'wordpress_category' );

    if ( ! $parent_category ) {
        // Create the parent category if it doesn't exist in the custom taxonomy
        $parent_category = wp_insert_term(
            $parent_category_name,       // Category name
            'wordpress_category'         // Custom taxonomy
        );

        // Get the term ID of the newly created parent category
        $parent_category_id = $parent_category['term_id'];

        // Debugging: Log the creation of the parent category
        error_log( 'Parent Category Created: ' . $parent_category_name );
    } else {
        // If parent category exists, get the ID
        $parent_category_id = $parent_category->term_id;
        // Debugging: Log if the parent category already exists
        error_log( 'Parent Category already exists: ' . $parent_category_name );
    }

    // Loop through the child categories and create them under the parent category in the custom taxonomy
    foreach ( $child_categories as $category_name ) {
        // Check if the child category already exists in the custom taxonomy
        $category = get_term_by( 'name', $category_name, 'wordpress_category' );

        if ( ! $category ) {
            // Create a new child category under the parent category in the custom taxonomy
            wp_insert_term(
                $category_name,              // Category name
                'wordpress_category',        // Custom taxonomy
                array(
                    'parent' => $parent_category_id // Set parent category ID
                )
            );

            // Debugging: Log the creation of the child category
            error_log( 'Child Category Created: ' . $category_name );
        } else {
            // Debugging: Log if the child category already exists
            error_log( 'Child Category already exists: ' . $category_name );
        }
    }
}

// Hook into wp_loaded to run when WordPress is fully initialized
add_action( 'wp_loaded', 'create_categories' );



function create_blogger_categories() {
    // Define the parent category
    $parent_category_name = 'Blogger'; // This is the parent category

    // Define the child categories related to blogging
    $child_categories = array(
        'Personal Blogs',
        'Technology',
        'Travel',
        'Food',
        'Health & Wellness',
        'Fashion & Lifestyle',
        'Finance',
        'Education',
        'News & Media',
        'DIY & Crafts',
        'Gaming',
        'Photography',
        'Parenting',
        'Business',
        'Creative Writing',
        'Sports',
        'Entertainment',
        'Marketing',
        'SEO',
        'Affiliate Marketing'
    );

    // Check if the parent category exists in the custom 'blogger_category' taxonomy
    $parent_category = get_term_by('name', $parent_category_name, 'blogger_category');

    if (!$parent_category) {
        // Create the parent category if it doesn't exist in the custom taxonomy
        $parent_category = wp_insert_term(
            $parent_category_name,       // Category name
            'blogger_category'           // Custom taxonomy
        );

        // Get the term ID of the newly created parent category
        $parent_category_id = $parent_category['term_id'];

        // Debugging: Log the creation of the parent category
        error_log('Parent Category Created: ' . $parent_category_name);
    } else {
        // If parent category exists, get the ID
        $parent_category_id = $parent_category->term_id;
        // Debugging: Log if the parent category already exists
        error_log('Parent Category already exists: ' . $parent_category_name);
    }

    // Loop through the child categories and create them under the parent category in the custom taxonomy
    foreach ($child_categories as $category_name) {
        // Check if the child category already exists in the custom taxonomy
        $category = get_term_by('name', $category_name, 'blogger_category');

        if (!$category) {
            // Create a new child category under the parent category in the custom taxonomy
            wp_insert_term(
                $category_name,              // Category name
                'blogger_category',          // Custom taxonomy
                array(
                    'parent' => $parent_category_id // Set parent category ID
                )
            );

            // Debugging: Log the creation of the child category
            error_log('Child Category Created: ' . $category_name);
        } else {
            // Debugging: Log if the child category already exists
            error_log('Child Category already exists: ' . $category_name);
        }
    }
}

// Hook into wp_loaded to run when WordPress is fully initialized
add_action('wp_loaded', 'create_blogger_categories');


function create_plugin_categories() {
    // Define the parent category
    $parent_category_name = 'Plugin'; // This is the parent category

    // Define the child categories related to plugins (adjust these names based on your plugin categories)
    $child_categories = array(
        'SEO Plugin',
        'Security Plugin',
        'E-commerce Plugin',
        'Cache Plugin',
        'Backup Plugin',
        'Analytics Plugin',
        'Social Media Plugin',
        'Content Plugin',
        'Membership Plugin',
        'Email Marketing Plugin',
        'Form Plugin',
        'Multilingual Plugin',
        'Performance Plugin',
        'Media Gallery Plugin',
        'Affiliate Plugin',
        'Slider Plugin',
        'Event Plugin',
        'Payment Gateway Plugin',
        'Custom Post Type Plugin'
    );

    // Ensure the custom taxonomy exists (replace 'plugin_category' with your taxonomy name)
    if (!taxonomy_exists('plugin_category')) {
        error_log('Custom taxonomy "plugin_category" does not exist.');
        return;
    }

    // Check if the parent category exists in the custom 'plugin_category' taxonomy
    $parent_category = get_term_by('name', $parent_category_name, 'plugin_category');

    if (!$parent_category) {
        // Create the parent category if it doesn't exist in the custom taxonomy
        $parent_category = wp_insert_term(
            $parent_category_name,       // Category name
            'plugin_category'            // Custom taxonomy
        );

        // Check for errors while creating the parent category
        if (is_wp_error($parent_category)) {
            error_log('Error creating parent category: ' . $parent_category->get_error_message());
            return;
        }

        // Get the term ID of the newly created parent category
        $parent_category_id = $parent_category['term_id'];

        // Debugging: Log the creation of the parent category
        error_log('Parent Category Created: ' . $parent_category_name);
    } else {
        // If parent category exists, get the ID
        $parent_category_id = $parent_category->term_id;
        // Debugging: Log if the parent category already exists
        error_log('Parent Category already exists: ' . $parent_category_name);
    }

    // Loop through the child categories and create them under the parent category in the custom taxonomy
    foreach ($child_categories as $category_name) {
        // Check if the child category already exists in the custom taxonomy
        $category = get_term_by('name', $category_name, 'plugin_category');

        if (!$category) {
            // Create a new child category under the parent category in the custom taxonomy
            $child_category = wp_insert_term(
                $category_name,              // Category name
                'plugin_category',           // Custom taxonomy
                array(
                    'parent' => $parent_category_id // Set parent category ID
                )
            );

            // Check for errors while creating the child category
            if (is_wp_error($child_category)) {
                error_log('Error creating child category "' . $category_name . '": ' . $child_category->get_error_message());
                continue; // Skip this category if there was an error
            }

            // Debugging: Log the creation of the child category
            error_log('Child Category Created: ' . $category_name);
        } else {
            // Debugging: Log if the child category already exists
            error_log('Child Category already exists: ' . $category_name);
        }
    }
}

// Hook into wp_loaded to run when WordPress is fully initialized
add_action('wp_loaded', 'create_plugin_categories');



function create_course_categories() {
    // Define the parent category
    $parent_category_name = 'Courses'; // This is the parent category

    // Define the child categories related to courses
    $child_categories = array(
        'Web Development',
        'Data Science & Analytics',
        'Digital Marketing',
        'Graphic Design',
        'Business & Entrepreneurship',
        'Photography & Videography',
        'Music Production',
        'Health & Fitness',
        'Personal Development',
        'Finance & Accounting',
        'Language Learning',
        'Cooking & Culinary',
        'Art & Crafts',
        'IT & Networking',
        'AI & Machine Learning',
        'Game Development',
        'Writing & Content Creation',
        'Career Development',
        'Education & Teaching'
    );

    // Check if the parent category exists in the custom 'course_category' taxonomy
    $parent_category = get_term_by('name', $parent_category_name, 'course_category');

    if (!$parent_category) {
        // Create the parent category if it doesn't exist in the custom taxonomy
        $parent_category = wp_insert_term(
            $parent_category_name,       // Category name
            'course_category'           // Custom taxonomy for courses
        );

        // Get the term ID of the newly created parent category
        $parent_category_id = $parent_category['term_id'];

        // Debugging: Log the creation of the parent category
        error_log('Parent Category Created: ' . $parent_category_name);
    } else {
        // If parent category exists, get the ID
        $parent_category_id = $parent_category->term_id;
        // Debugging: Log if the parent category already exists
        error_log('Parent Category already exists: ' . $parent_category_name);
    }

    // Loop through the child categories and create them under the parent category in the custom taxonomy
    foreach ($child_categories as $category_name) {
        // Check if the child category already exists in the custom taxonomy
        $category = get_term_by('name', $category_name, 'course_category');

        if (!$category) {
            // Create a new child category under the parent category in the custom taxonomy
            wp_insert_term(
                $category_name,              // Category name
                'course_category',           // Custom taxonomy
                array(
                    'parent' => $parent_category_id // Set parent category ID
                )
            );

            // Debugging: Log the creation of the child category
            error_log('Child Category Created: ' . $category_name);
        } else {
            // Debugging: Log if the child category already exists
            error_log('Child Category already exists: ' . $category_name);
        }
    }
}

// Hook into wp_loaded to run when WordPress is fully initialized
add_action('wp_loaded', 'create_course_categories');






function create_pages_with_templates() {
    // Define the pages and their templates with hyphens
    $pages = array(
        array(
            'title' => 'Blogger Upload',
            'template' => 'blogger-upload-page', // Hyphenated template name
        ),
        array(
            'title' => 'Cart',
            'template' => 'cart-page', // Hyphenated template name
        ),
        array(
            'title' => 'Courses Upload',
            'template' => 'courses-upload-page', // Hyphenated template name
        ),
        array(
            'title' => 'Ecommerce Upload',
            'template' => 'ecommerce-upload-page', // Hyphenated template name
        ),
        array(
            'title' => 'Profile Page',
            'template' => 'page-profile', // Hyphenated template name
        ),
        array(
            'title' => 'Uploaded Products',
            'template' => 'page-uploaded-products', // Hyphenated template name
        ),
        array(
            'title' => 'Dashboard',
            'template' => 'page-dashboard', // Hyphenated template name
        ),
        array(
            'title' => 'Checkout',
            'template' => 'payment-page', // Hyphenated template name
        ),
        array(
            'title' => 'Plugin Upload',
            'template' => 'plugins-upload-page', // Hyphenated template name
        ),
        array(
            'title' => 'login',
            'template' => 'login-page', // Hyphenated template name
        ),
        array(
            'title' => 'WordPress Upload',
            'template' => 'wordpress-upload-page', // Hyphenated template name
        ),
        array(
            'title' => 'Upload',
            'template' => 'category-selection-page', // Hyphenated template name
        ),
        array(
            'title' => 'Thank You',
            'template' => 'thank-you-page', // Hyphenated template name
        ),
        array(
            'title' => 'Download',
            'template' => 'download', // Hyphenated template name
        ),
                array(
            'title' => 'Become An Author',
            'template' => 'become-author', // Hyphenated template name
        ),
        array(
            'title' => 'Products',
            'template' => 'page-products', // Hyphenated template name
        ),
    );

    // Loop through the pages array and create pages
    foreach ( $pages as $page ) {
        // Check if the page already exists
        $page_check = get_page_by_title( $page['title'] );

        if ( ! $page_check ) {
            // Create a new page
            $new_page = array(
                'post_title'   => $page['title'],
                'post_content' => '', // You can set default content here if you want
                'post_status'  => 'publish',
                'post_type'    => 'page',
            );
            // Insert the page
            $page_id = wp_insert_post( $new_page );

            // Check if the page was successfully created
            if ( ! is_wp_error( $page_id ) ) {
                // Debugging: Log the successful creation of the page
                error_log( 'Page Created: ' . $page['title'] . ' (ID: ' . $page_id . ')');

                // Set the page template if the page was created
                $template = $page['template'] . '.php';

                // Ensure the template file exists in the theme directory
                $template_path = get_template_directory() . '/' . $template;

                if ( file_exists( $template_path ) ) {
                    // Set the template for the page
                    update_post_meta( $page_id, '_wp_page_template', $template );
                    // Debugging: Log the template assignment
                    error_log( 'Template set: ' . $template . ' for Page ID: ' . $page_id );
                } else {
                    // Log an error if the template file is missing
                    error_log( 'Template file not found: ' . $template_path );
                }
            } else {
                // Log an error if the page wasn't created
                error_log( 'Failed to create page: ' . $page['title'] );
            }
        }
    }
}

// Hook into theme setup to run the function when the plugin is activated
add_action( 'after_setup_theme', 'create_pages_with_templates' );
 







function create_ecommerce_categories() {
    // Define the parent category
    $parent_category_name = 'Ecommerce'; // This is the parent category

    // Define the child categories related to ecommerce
    $child_categories = array(
        'Electronics',
        'Fashion',
        'Home & Garden',
        'Beauty & Health',
        'Sports & Outdoors',
        'Automotive',
        'Toys & Games',
        'Books',
        'Music',
        'Movies & TV',
        'Food & Grocery',
        'Pet Supplies',
        'Office Supplies',
        'Jewelry & Watches',
        'Arts & Crafts',
        'Tools & Equipment',
        'Baby & Kids',
        'Furniture',
        'Handmade & Vintage',
        'Digital Products',
    );

    // Check if the parent category exists in the custom 'ecommerce_category' taxonomy
    $parent_category = get_term_by('name', $parent_category_name, 'ecommerce_category');

    if (!$parent_category) {
        // Create the parent category if it doesn't exist in the custom taxonomy
        $parent_category = wp_insert_term(
            $parent_category_name,       // Category name
            'ecommerce_category'         // Custom taxonomy
        );

        // Get the term ID of the newly created parent category
        $parent_category_id = $parent_category['term_id'];

        // Debugging: Log the creation of the parent category
        error_log('Parent Category Created: ' . $parent_category_name);
    } else {
        // If parent category exists, get the ID
        $parent_category_id = $parent_category->term_id;
        // Debugging: Log if the parent category already exists
        error_log('Parent Category already exists: ' . $parent_category_name);
    }

    // Loop through the child categories and create them under the parent category in the custom taxonomy
    foreach ($child_categories as $category_name) {
        // Check if the child category already exists in the custom taxonomy
        $category = get_term_by('name', $category_name, 'ecommerce_category');

        if (!$category) {
            // Create a new child category under the parent category in the custom taxonomy
            wp_insert_term(
                $category_name,              // Category name
                'ecommerce_category',        // Custom taxonomy
                array(
                    'parent' => $parent_category_id // Set parent category ID
                )
            );

            // Debugging: Log the creation of the child category
            error_log('Child Category Created: ' . $category_name);
        } else {
            // Debugging: Log if the child category already exists
            error_log('Child Category already exists: ' . $category_name);
        }
    }
}

// Hook into wp_loaded to run when WordPress is fully initialized
add_action('wp_loaded', 'create_ecommerce_categories');
 
