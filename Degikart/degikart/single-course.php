<?php
// Handle form submission for comment and rating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['rating'])) {
    $comment = sanitize_text_field($_POST['comment']);
    $rating = intval($_POST['rating']);
    $post_id = intval($_POST['post_id']);

    // Validation
    if (!empty($comment) && !empty($rating)) {
        // Save comment
        $commentdata = array(
            'comment_post_ID' => $post_id,
            'comment_content' => $comment,
            'comment_approved' => 1,
        );
        $comment_id = wp_insert_comment($commentdata);

        if ($comment_id) {
            // Store the rating in comment meta
            add_comment_meta($comment_id, 'rating', $rating);

            // Calculate the average rating
            $ratings = get_comments(array('post_id' => $post_id));
            $rating_values = array_map(function($c) {
                return (int) get_comment_meta($c->comment_ID, 'rating', true);
            }, $ratings);
            
            $average_rating = !empty($rating_values) ? array_sum($rating_values) / count($rating_values) : 0;
            update_post_meta($post_id, 'average_rating', $average_rating);

            // Redirect to avoid resubmission
            wp_redirect(get_permalink($post_id));
            exit;
        }
    } else {
        echo '<p>Please fill in all fields.</p>';
    }
}

// Calculate average rating to display
$average_rating = get_post_meta(get_the_ID(), 'average_rating', true);
$average_rating_display = $average_rating ? number_format($average_rating, 1) : '0.0'; // Default to 0.0 if no rating

get_header();

if (have_posts()) :
    while (have_posts()) : the_post();
    $meta = get_post_meta(get_the_ID());
    $post_id = get_the_ID();
    // Custom fields
    $excerpt = isset($meta['excerpt'][0]) ? esc_html($meta['excerpt'][0]) : '';

    $regular_price = isset($meta['regular_price'][0]) ? esc_html($meta['regular_price'][0]) : '0';
    $video_demo_url = isset($meta['video_demo_url'][0]) ? esc_url($meta['video_demo_url'][0]) : ''; // Video URL field
    $version = isset($meta['version'][0]) ? esc_html($meta['version'][0]) : 'N/A';
    $author_name = get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()));
    $details = isset($meta['details'][0]) ? wp_kses_post($meta['details'][0]) : '';
    $tags = get_the_terms($post_id, 'post_tag');
    $demo_url = isset($meta['demo_url'][0]) ? esc_url($meta['demo_url'][0]) : '';
    $views = get_post_meta(get_the_ID(), 'product_views_count', true);
    
    $resolution = isset($meta['resolution'][0]) ? esc_html($meta['resolution'][0]) : 'N/A';
    $file_size = isset($meta['file_size'][0]) ? esc_html($meta['file_size'][0]) : 'N/A';
    $course_length = isset($meta['course_length'][0]) ? esc_html($meta['course_length'][0]) : 'N/A';
    $num_lessons = isset($meta['number_of_lessons'][0]) ? esc_html($meta['number_of_lessons'][0]) : 'N/A';
$video_encoding = isset($meta['video_encoding'][0]) ? esc_html($meta['video_encoding'][0]) : 'N/A';
$difficulty = isset($meta['difficulty'][0]) ? esc_html($meta['difficulty'][0]) : 'N/A';
    $closed_captions = isset($meta['closed_captions'][0]) ? esc_html($meta['closed_captions'][0]) : 'N/A'; // Closed Captions field
    $thumbnail_url = get_post_meta(get_the_ID(), 'thumbnail_url', true);
    
// Get current post ID
$product_id = get_the_ID();

// Get sales count
$sales_count = get_post_meta($product_id, '_sales_count', true);
$sales_count = $sales_count ? intval($sales_count) : 0;

?>
<div id="site-width">
    <main class="post-site-main">
 

        <header class="post-header">
        <?php custom_breadcrumbs(); ?>

            <h1 class="entry-title"><?php the_title(); ?></h1>
            <?php 

// Get post ID
$product_id = get_the_ID();

// Get average rating
$average_rating = get_post_meta($product_id, 'average_rating', true);
$average_rating_display = $average_rating ? number_format($average_rating, 1) : '0.0'; // Default to 0.0 if no rating

// Get review and rated comment counts
$reviews_count = count(get_comments(array('post_id' => $product_id)));
$rated_comments_count = count(get_comments(array('post_id' => $product_id, 'meta_key' => 'rating')));

// Get views and sales count
$views = get_post_meta($product_id, 'product_views_count', true);
$sales_count = get_post_meta($product_id, '_sales_count', true);
$sales_count = $sales_count ? intval($sales_count) : 0;

// Get post date info
$last_update = get_the_modified_time('U'); // Last modified timestamp
$publish_date = get_the_time('U'); // Original publish timestamp

// Loop through comments to calculate ratings if necessary
$comments = get_comments(array('post_id' => $product_id));
$star_count = [0, 0, 0, 0, 0]; // Count for 1-5 stars

// Loop through comments to count ratings and calculate total star count
foreach ($comments as $comment) {
    $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
    if ($rating > 0) {
        $star_count[$rating - 1]++;
    }
}

// Calculate average rating based on comment stars
$average_rating_display = ($rated_comments_count > 0) ? 
    array_sum(array_map(function($i, $count) { return $count * ($i + 1); }, range(0, 4), $star_count)) / $rated_comments_count 
    : 0;

// Round to 1 decimal place
$average_rating_display = number_format($average_rating_display, 1);

?>
<div class="sales-bar">
    <!-- Display Updated Label if post was updated -->
    <?php if ($last_update !== $publish_date) : ?>
        <span class="updated-label">
            <?php _e('Updated', 'degikart'); ?>
            <!-- Inline SVG icon for updated -->
            <svg viewBox="0 0 122.88 122.87">
                <g>
                    <path fill="#39B54A" d="M32.82,51.34l14.99-0.2l1.12,0.29c3.03,1.74,5.88,3.74,8.54,5.99c1.92,1.63,3.76,3.4,5.5,5.32 c5.38-8.65,11.11-16.6,17.16-23.9c6.63-8,13.66-15.27,21.05-21.9l1.46-0.56h16.36l-3.3,3.66c-10.13,11.26-19.33,22.9-27.64,34.9 C79.74,66.97,72.31,79.37,65.7,92.13l-2.06,3.97l-1.89-4.04c-3.49-7.48-7.66-14.35-12.64-20.49c-4.98-6.14-10.77-11.59-17.52-16.22 L32.82,51.34z"/>
                    <path fill="#3C3C3C" d="M61.44,0c9.53,0,18.55,2.17,26.61,6.04c-3.3,2.61-6.36,5.11-9.21,7.53c-5.43-1.97-11.28-3.05-17.39-3.05 c-14.06,0-26.79,5.7-36,14.92c-9.21,9.22-14.92,21.94-14.92,36c0,14.06,5.7,26.78,14.92,36s21.94,14.92,36,14.92 c14.06,0,26.79-5.7,36-14.92c9.22-9.22,14.91-21.94,14.91-36c0-3.34-0.32-6.62-0.94-9.78c2.64-3.44,5.35-6.88,8.11-10.28 c2.17,6.28,3.35,13.04,3.35,20.06c0,16.96-6.88,32.33-17.99,43.44c-11.12,11.12-26.48,18-43.44,18c-16.96,0-32.32-6.88-43.44-18 C6.88,93.76,0,78.4,0,61.44C0,44.47,6.88,29.11,17.99,18C29.11,6.88,44.47,0,61.44,0L61.44,0L61.44,0z"/>
                </g>
            </svg>
        </span>
    <?php endif; ?>

    <!-- Display Sales Count -->
    <p>
        <svg width="16px" height="16px" viewBox="0 0 297.5 297.5">
            <g>
                <path fill="#3C3C3C" d="M172.416,124.813c-15.453-15.461-24.005-35.976-24.134-57.813h-68.452l-10.732-46.138-58.141-19.967-10.719,31.21 40.858,14.032 29.058,124.932 9.943,43.931h173l17.236-76.146c-11.76,6.456-25.251,10.137-39.59,10.137-22.035-0.007-42.75-8.593-58.327-24.178z"/>
                <circle cx="113.097" cy="265" r="32.5"/>
                <circle cx="220.097" cy="265" r="32.5"/>
                <path fill="#39B54A" d="M230.748,133c36.717,0 66.484-29.765 66.495-66.482 0-36.724-29.754-66.518-66.479-66.518-36.717,0-66.485,29.765-66.495,66.482-0.012,36.724 29.752,66.518 66.478,66.518zm-21.758-72.162l12.631,12.632 30.922-30.922 11.314,11.314-42.236,42.234-23.946-23.946 11.315-11.312z"/>
            </g>
        </svg>
        <strong><?php _e('Sales:', 'degikart'); ?></strong> <?php echo esc_html($sales_count); ?>
    </p>

    <!-- Display Views Count -->
    <p>
        <svg width="16px" height="16px" viewBox="0 0 512 100">
            <path fill-rule="nonzero" d="M3.14 132.9c14.51-17.53 29.53-33.35 44.94-47.39 60.17-54.78 127.69-84 197.43-85.45 69.61-1.46 141.02 24.79 209.14 80.95 18.45 15.21 36.6 32.54 54.3 52 3.82 4.19 4.02 10.42.78 14.81-19.73 27.91-41.98 51.4-65.97 70.56-53.57 42.77-115.96 63.9-179.2 64.29-63.05.39-126.84-19.87-183.44-59.83-28.31-20-54.85-44.93-78.58-74.67-3.65-4.59-3.29-11.1.6-15.27zM256 83.24c32.09 0 58.1 26.01 58.1 58.1s-26.01 58.1-58.1 58.1-58.1-26.01-58.1-58.1c0-5.97.9-11.74 2.57-17.16 4.25 11.15 15.04 19.07 27.68 19.07 16.35 0 29.61-13.26 29.61-29.61 0-12.7-7.98-23.52-19.2-27.73 5.5-1.73 11.36-2.67 17.44-2.67zm107.24-33.52a141.453 141.453 0 0 1 23.1 37.7c6.92 16.67 10.74 34.9 10.74 53.92 0 19.03-3.82 37.26-10.73 53.94a141.479 141.479 0 0 1-30.6 45.8l-1.92 1.89c26.4-9.83 51.79-24.09 75.37-42.91 20.12-16.07 38.96-35.49 55.99-58.27-15-15.93-30.16-30.18-45.38-42.73-25.22-20.8-50.84-37.2-76.57-49.34zm-212.08 185.9c-10.65-11.81-19.33-25.44-25.5-40.32a140.518 140.518 0 0 1-10.74-53.96c0-19.01 3.81-37.22 10.72-53.87 6.85-16.52 16.75-31.46 28.96-44.1-31.5 13.33-61.97 33.25-90.76 59.44-12.7 11.57-25.04 24.3-36.95 38.17 20.74 24.71 43.54 45.64 67.69 62.71 18.19 12.84 37.15 23.5 56.58 31.93zM300.95 32.58c-13.78-5.71-28.98-8.88-44.94-8.88-15.94 0-31.12 3.17-44.93 8.9-14.34 5.95-27.32 14.73-38.23 25.64-10.88 10.89-19.64 23.85-25.6 38.2-5.71 13.79-8.88 28.97-8.88 44.9 0 15.96 3.17 31.17 8.9 44.98a117.654 117.654 0 0 0 25.58 38.19c10.86 10.84 23.84 19.6 38.24 25.57 13.8 5.72 28.98 8.88 44.92 8.88 15.95 0 31.15-3.17 44.96-8.88 14.36-5.93 27.32-14.7 38.2-25.57 10.88-10.88 19.64-23.84 25.57-38.16 5.72-13.85 8.89-29.05 8.89-45.01 0-15.95-3.17-31.14-8.89-44.95-5.93-14.37-14.69-27.33-25.57-38.21-10.86-10.86-23.84-19.63-38.22-25.6z"/>
        </svg>
        <strong> <?php _e('Views:', 'degikart'); ?> </strong> <?php echo esc_html($views); ?>
    </p>

    <!-- Display Rating Stars -->
    <p class="average-rating">
        <?php 
        // Display stars based on average rating
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($average_rating_display)) {
                echo '<span class="star filled">★</span>';
            } elseif ($i == ceil($average_rating_display) && $average_rating_display != floor($average_rating_display)) {
                echo '<span class="star half">★</span>';
            } else {
                echo '<span class="star empty">★</span>';
            }
        }
        ?>
        <span class="rating-text"><?php echo esc_html($average_rating_display); ?></span>
        <strong class="rated-count">(<?php echo esc_html($rated_comments_count); ?>)</strong>
    </p>
    
   
</div>
<div class="sales-bar" id="item-rate">
    <div class="left-item-rate">
        <strong><?php _e('Comments:', 'degikart'); ?> <?php echo esc_html($reviews_count); ?></strong>
        <strong><?php _e('Ratings:', 'degikart'); ?> <?php echo esc_html($rated_comments_count); ?></strong>
    </div>

    <div class="right-item-rate">
    <?php if (is_user_logged_in()) { 
 
    $user_id = get_current_user_id(); // Get the current user's ID
    $post_id = get_the_ID(); // Get the current post ID

    // Check if this post is in the user's favorites
    $favorites = get_user_meta($user_id, 'favorites', true);
    $is_favorite = is_array($favorites) && in_array($post_id, $favorites); // True if post is already favorited
    ?>
   
    <!-- Heart Icon for adding/removing favorites -->
    <button class="favorite-btn" data-post-id="<?php echo $post_id; ?>" 
            data-user-id="<?php echo $user_id; ?>" 
            data-action="<?php echo $is_favorite ? 'remove' : 'add'; ?>" 
            data-favorite-status="<?php echo $is_favorite ? 'true' : 'false'; ?>"  <!-- Add this data attribute -->
    
        <span class="heart-icon <?php echo $is_favorite ? 'favorited' : ''; ?>" 
               style="color: <?php echo $is_favorite ? 'red' : 'gray'; ?>;">&#10084;</span>
    </button>
    <?php } ?>
    </div>

</div>


  
        </header>

        <div class="content-wrapper">
            <div class="product-area" id="primary">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="inside-article">
                        <div class="post-body" itemprop="text">
                        <?php if ($thumbnail_url && $video_demo_url) : ?>
    <div class="post-video">
        <!-- Thumbnail with Play Button -->
        <div class="video-thumbnail" onclick="showVideo(this)">
            <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Video Thumbnail" />
            <span class="play-button">▶</span>  <!-- Play Button Icon -->
        </div>

        <!-- Video Placeholder (Initially hidden) -->
        <div class="video-placeholder" style="display: none;">
            <iframe id="videoIframe" width="1280" height="720" src="<?php echo esc_url($video_demo_url); ?>" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
<?php endif; ?>



<!-- Download Preview Link (Demo video) -->
<div class="post-preshot">
    <?php if ($video_demo_url) : ?>
        <p><a href="<?php echo esc_url($video_demo_url); ?>" download><?php _e('Download Preview', 'degikart'); ?></a></p>
    <?php endif; ?>
</div>
</div>

<?php degikart_post_date(); ?>

                        <div class="item-content">
                            <div class="content-text">
                                <?php echo nl2br($details); ?>
                            </div>
                            <div class="btn-container">
                                <button class="show-more" onclick="toggleContent()">Show More</button>
                            </div>
                        </div>
                   
                </article>
            </div>

            <div class="product-right-sidebar">
            <div id="product-sidebar">
                    <form id="product-form">
                        <div>
                            <p><strong><?php _e('Regular License - $', 'degikart'); ?><?php echo $regular_price; ?></strong></p>
                            <span id="total-price" style="float: right; font-size: 18px; font-weight: 600;">$<?php echo $regular_price; ?></span>
                        </div>

                        <div class="buttontotal">
                              <div class="buttontotal">
                              <button type="button" onclick="addToCart(<?php echo get_the_ID(); ?>, <?php echo $regular_price; ?>)">
    <?php _e('Add to Cart', 'degikart'); ?>
</button>


                        </div>
                        </div>
                    </form>
                </div>
 
                <div class="sidebar-content">
    <table class="table-style">
        <?php if (!empty($author_name)): ?>
            <tr>
                <th><?php _e('Author:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($author_name); ?></td>
            </tr>
        <?php endif; ?>

        <?php if ($last_update = get_post_meta(get_the_ID(), 'last_update', true)): ?>
            <tr>
                <th><?php _e('Last Update:', 'degikart'); ?></th>
                <td class="rtl"><?php echo get_the_date(); ?></td>
            </tr>
        <?php endif; ?>

        <?php if ($tags && !is_wp_error($tags)): 
            $tag_names = wp_list_pluck($tags, 'name'); 
            echo '<tr><th>' . __('Tags:', 'degikart') . '</th><td class="rtl">' . esc_html(implode(', ', $tag_names)) . '</td></tr>';
        endif; ?>

        <!-- Display the additional custom fields -->
        <?php if (!empty($resolution)): ?>
            <tr>
                <th><?php _e('Resolution:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($resolution); ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($file_size)): ?>
            <tr>
                <th><?php _e('File Size:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($file_size); ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($course_length)): ?>
            <tr>
                <th><?php _e('Course Length:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($course_length); ?></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($num_lessons)): ?>
            <tr>
                <th><?php _e('Number of Lessons:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($num_lessons); ?></td>
            </tr>
        <?php endif; ?>

        <div class="product-details">
            <?php if (!empty($video_encoding)): ?>
                <tr>
                    <th><?php _e('Video Encoding:', 'degikart'); ?></th>
                    <td class="rtl"><?php echo esc_html($video_encoding); ?></td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($difficulty)): ?>
                <tr>
                    <th><?php _e('Difficulty:', 'degikart'); ?></th>
                    <td class="rtl"><?php echo esc_html($difficulty); ?></td>
                </tr>
            <?php endif; ?>
        </div>

        <!-- Display the Closed Captions field -->
        <?php if (!empty($closed_captions)): ?>
            <tr>
                <th><?php _e('Closed Captions:', 'degikart'); ?></th>
                <td class="rtl"><?php echo esc_html($closed_captions); ?></td>
            </tr>
        <?php endif; ?>
    </table>
</div>


<?php degikart_author_profile(); ?>

<div class="views-sales" id="views-sales">
            <?php
            // Get comments for the current product
            $comments = get_comments(array('post_id' => get_the_ID()));
            $rated_comments_count = 0;
            $reviews_count = count($comments);
            $five_star_count = 0;
            $four_star_count = 0;
            $three_star_count = 0;
            $two_star_count = 0;
            $one_star_count = 0;

            // Loop through comments to calculate ratings
            foreach ($comments as $comment) {
                $rating = (int) get_comment_meta($comment->comment_ID, 'rating', true);
                if ($rating > 0) {
                    $rated_comments_count++;
                    switch ($rating) {
                        case 5:
                            $five_star_count++;
                            break;
                        case 4:
                            $four_star_count++;
                            break;
                        case 3:
                            $three_star_count++;
                            break;
                        case 2:
                            $two_star_count++;
                            break;
                        case 1:
                            $one_star_count++;
                            break;
                    }
                }
            }

            // Calculate the average rating
            if ($rated_comments_count > 0) {
                $average_rating_display = array_sum([$five_star_count * 5, $four_star_count * 4, $three_star_count * 3, $two_star_count * 2, $one_star_count]) / $rated_comments_count;
            } else {
                $average_rating_display = 0; // Default value if no ratings
            }
            ?>

            <div id="ratingSummary">
                <div class="rating-header">
                    <p><strong><?php _e('Ratings', 'degikart'); ?></strong></p>
                    <button id="rateProductButton">
                        <strong><?php _e('Rate Product', 'degikart'); ?></strong>
                    </button>
                </div>

                <div class="rating-details">
    <div class="rating-info">
        <p>
            <span class="average-rating"><?php echo esc_html(number_format($average_rating_display, 1)); ?>★</span>
        </p>
        <div class="rating-info">
            <span><?php echo esc_html($rated_comments_count); ?> Ratings & <?php echo esc_html($reviews_count); ?> Reviews</span>
        </div>
    </div>

    <div class="rating-breakdown">
    <?php
// Total number of ratings
$total_ratings = $rated_comments_count;

// Check if there are any ratings to avoid division by zero
if ($total_ratings > 0) {
    // Calculate percentages for each star rating
    $five_star_percentage = ($five_star_count / $total_ratings) * 100;
    $four_star_percentage = ($four_star_count / $total_ratings) * 100;
    $three_star_percentage = ($three_star_count / $total_ratings) * 100;
    $two_star_percentage = ($two_star_count / $total_ratings) * 100;
    $one_star_percentage = ($one_star_count / $total_ratings) * 100;
} else {
    // If no ratings, set percentages to 0
    $five_star_percentage = 0;
    $four_star_percentage = 0;
    $three_star_percentage = 0;
    $two_star_percentage = 0;
    $one_star_percentage = 0;
}
?>

    <div class="rating-item">
        <p>5★:</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo esc_html($five_star_percentage); ?>%"></div>
        </div>
        <p><?php echo esc_html($five_star_count); ?> </p>
    </div>

    <div class="rating-item">
        <p>4★:</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo esc_html($four_star_percentage); ?>%"></div>
        </div>
        <p><?php echo esc_html($four_star_count); ?></p>
    </div>

    <div class="rating-item">
        <p>3★:</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo esc_html($three_star_percentage); ?>%"></div>
        </div>
        <p><?php echo esc_html($three_star_count); ?></p>
    </div>

    <div class="rating-item">
        <p>2★:</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo esc_html($two_star_percentage); ?>%"></div>
        </div>
        <p><?php echo esc_html($two_star_count); ?> </p>
    </div>

    <div class="rating-item">
        <p>1★:</p>
        <div class="progress-bar">
            <div class="progress" style="width: <?php echo esc_html($one_star_percentage); ?>%"></div>
        </div>
        <p><?php echo esc_html($one_star_count); ?> </p>
    </div>
</div>
                <div id="commentPopup" class="popup">
                    <div class="popup-content comment-popup">
                        <span class="close">×</span>
                    <h3><?php _e('Leave a Comment and Rate', 'degikart'); ?></h3>
                        <form id="comment-rating-form" method="post">
    <?php
    // Check if the user is logged in
    $current_user = wp_get_current_user();
    if (is_user_logged_in()) :
        // Pre-fill the Name and Email fields with the logged-in user's info
    ?>
        <!-- Name Field (read-only) -->
        <input type="text" name="author" value="<?php echo esc_attr($current_user->display_name); ?>" readonly required>

        <!-- Email Field (read-only) -->
        <input type="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" readonly required>
    <?php else : ?>
        <!-- Name Field (editable if not logged in) -->
        <input type="text" name="author" placeholder="<?php _e('Your Name', 'degikart'); ?>" required>

        <!-- Email Field (editable if not logged in) -->
        <input type="email" name="email" placeholder="<?php _e('Your Email', 'degikart'); ?>" required>
    <?php endif; ?>

    <!-- Comment Textarea -->
    <textarea name="comment" placeholder="<?php _e('Write your comment here...', 'degikart'); ?>" required></textarea>

    <!-- Rating Selector -->
    <div class="rating-container">
        <label><?php _e('Rate this product:', 'degikart'); ?></label>
        <select name="rating" required>
            <option value="">-- Select Rating --</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>

    <!-- Hidden Post ID -->
    <input type="hidden" name="action" value="handle_comment_submission"> <!-- Added this line for AJAX -->
    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>"> <!-- Hidden post ID -->
    

    <!-- Submit Button -->
    <button type="submit"><?php _e('Submit', 'degikart'); ?></button>
</form>


<div class="rating-counter">
<h4><?php _e('Previous:', 'degikart'); ?></h4>
<p>
    <strong><?php _e('Comments:', 'degikart'); ?></strong> <?php echo esc_html($reviews_count); ?>
    <strong><?php _e('Ratings:', 'degikart'); ?></strong> <?php echo esc_html($rated_comments_count); ?>
</p>
</div>

<ul class="comments-list">
    <?php 
    if ($comments) {
        foreach ($comments as $comment) :
            // Fetch user info
            $user_info = get_userdata($comment->user_id);
            ?>
            <li class="comment-card">
                <div class="comment-header">
                    <?php 
                    // Display user's avatar (use size of 50px for better visibility)
                    echo get_avatar($comment->user_id, 50); 
                    ?>
                    <strong><?php echo esc_html($user_info ? $user_info->display_name : $comment->comment_author); ?></strong> 
                    <span class="comment-date"><?php echo esc_html(get_comment_date('', $comment->comment_ID)); ?></span>
                </div>
                <div class="comment-body">
                    <p><?php echo esc_html($comment->comment_content); ?></p>
                </div>
                <div class="comment-footer">
                    <strong>Rating: <?php echo esc_html(get_comment_meta($comment->comment_ID, 'rating', true)); ?> / 5</strong>
                </div>
            </li>
        <?php endforeach; 
    } else {
        echo '<li class="comment-card">' . __('No comments yet.', 'degikart') . '</li>';
    }
    ?>
</ul>


                    </div>
                </div>
            </div>
 

           
            </div>
            
        </div>

        
        </div>

        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "course",
  "name": "<?php echo addslashes(get_the_title()); ?>",
  "image": "<?php echo esc_url($thumbnail_url); ?>",
  "description": "<?php echo addslashes(strip_tags($excerpt)); ?>",
  "sku": "<?php echo esc_html(get_the_ID()); ?>",
  "priceCurrency": "USD", 
  "price": "<?php echo esc_html($regular_price); ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "<?php echo esc_html($average_rating_display); ?>",
    "ratingCount": "<?php echo esc_html($rated_comments_count); ?>"
  },
  "author": {
    "@type": "Person",
    "name": "<?php echo esc_html($author_name); ?>",
    "url": "<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
  }
}
</script>


    </main>
</div>
<script>
 
document.getElementById('rateProductButton').addEventListener('click', function() {
    document.getElementById('commentPopup').style.display = 'block';
});

document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('commentPopup').style.display = 'none';
});

// Close the popup when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('commentPopup')) {
        document.getElementById('commentPopup').style.display = 'none';
    }
};


function showVideo(thumbnailElement) {
    // Hide the thumbnail and play button
    thumbnailElement.style.display = 'none';  // Hide the thumbnail (div)

    // Find the video placeholder and show it
    var videoPlaceholder = thumbnailElement.nextElementSibling;
    videoPlaceholder.style.display = 'block';  // Show the video iframe
}


document.addEventListener('DOMContentLoaded', function() {
    // Initialize the cart items on page load
    loadCartItems();

    window.addToCart = function(productId, price, license = 'Course') {
    console.log("Product ID: ", productId);  // Ensure product ID is passed
    console.log("Price: ", price);  // Ensure price is passed

    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    // Check if the item already exists in the cart
    var existingItem = cartItems.find(function(item) {
        return item.product_id === productId && item.license === license;
    });

    if (existingItem) {
        // Item is already in the cart, show a notification
        alert('This item is already in your cart!');
    } else {
        // Item is not in the cart, add it
        var uniqueId = productId + '-' + new Date().getTime(); // Unique ID for each entry (if needed)

        // Store the product in localStorage
        cartItems.push({
            id: uniqueId,
            product_id: productId,
            price: price,
            license: license
        });

        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        alert('Product added to cart!');
        updateCartCount();
    }
};



    // Update the cart item count in the UI
    function updateCartCount() {
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        document.getElementById('cart-item-count').innerText = cartItems.length;
    }

    // Load cart items on page load (for display or UI update)
    function loadCartItems() {
        var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        console.log("Cart Items: ", cartItems); // Debug log for cart items
    }
});
 
jQuery(document).ready(function($) {
    $('.favorite-btn').on('click', function() {
        var post_id = $(this).data('post-id');
        var user_id = $(this).data('user-id');
        var action = $(this).data('action');
        
        var $button = $(this);
        var $heartIcon = $button.find('.heart-icon');
        
        // Send AJAX request to add/remove favorite
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            method: 'POST',
            data: {
                action: 'toggle_favorite', // AJAX action hook
                post_id: post_id,
                user_id: user_id,
                action_type: action
            },
            success: function(response) {
                if (response.success) {
                    // Toggle heart icon class and action
                    var new_action = (action === 'add') ? 'remove' : 'add';
                    var new_color = (new_action === 'add') ? 'gray' : 'red';
                    $heartIcon.toggleClass('favorited');
                    $heartIcon.css('color', new_color); // Change color of heart
                    $button.data('action', new_action); // Toggle action type
                    
                    // Update the favorite count in the header
                    var $favoriteCount = $('.favorite-count');
                    var count = parseInt($favoriteCount.text());
                    count = (new_action === 'add') ? count - 1 : count + 1;
                    $favoriteCount.text(count);
                }
            }
        });
    });
});

jQuery(document).ready(function($) {
    $('#comment-rating-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting traditionally

        var form = $(this);
        var submitButton = form.find("button[type='submit']");
        var statusText = form.find('.submit-status'); // Add this to select the status text element
        
        // Disable the button and change the text to "Submitting..."
        submitButton.prop('disabled', true).text('Submitting...'); 
        statusText.text('Submitting your review...'); // Display the status below the button

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>', // WordPress AJAX URL
            type: 'POST',
            data: form.serialize(), // Serialize the form data
            success: function(response) {
                if (response.success) {
                    // Add the new comment to the list of previous reviews
                    var newComment = `
                        <li class="comment-card">
                            <div class="comment-header">
                                <img src="${response.avatar}" alt="${response.author}" width="50" height="50">
                                <strong>${response.author}</strong>
                                <span class="comment-date">${response.date}</span>
                            </div>
                            <div class="comment-body">
                                <p>${response.comment}</p>
                            </div>
                            <div class="comment-footer">
                                <strong>Rating: ${response.rating} / 5</strong>
                            </div>
                        </li>
                    `;
                    // Append the new comment to the comments list
                    $('.comments-list').prepend(newComment);

                    // Reset the form and enable the submit button
                    form[0].reset();
                    submitButton.prop('disabled', false).text('Submit'); // Re-enable the button and reset text
                    statusText.text('Thank you for your review!'); // Display success message
                } else {
                    alert('There was an error submitting your comment. Please try again.');
                    submitButton.prop('disabled', false).text('Submit'); // Re-enable the button if error occurs
                    statusText.text(''); // Reset status text if error occurs
                }
            },
            error: function() {
                alert('There was an error submitting your comment. Please try again.');
                submitButton.prop('disabled', false).text('Submit'); // Re-enable the button if error occurs
                statusText.text(''); // Reset status text if error occurs
            }
        });
    });
});

</script>
<?php
    endwhile;
endif;

get_footer();
?>
 

