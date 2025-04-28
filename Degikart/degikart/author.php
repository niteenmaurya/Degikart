<?php

get_header();
?>
<div id="site-width">
    <div id="author-page">
        <div class="author-info">
            <?php

$skills = get_user_meta(get_the_author_meta('ID'), 'skills', true);
            $author = get_queried_object();
            $author_id = $author->ID;
            $profile_picture_url = get_user_meta($author_id, 'profile_picture_url', true);
            ?>
<div class="author-profile">
<div class="profile-image">
    <?php degikart_display_author_profile_picture(get_the_author_meta('ID')); ?>
</div>
    <div class="profile-info">
        <h1><?php echo esc_html(get_the_author_meta('display_name', $author_id)); ?></h1>
        <?php if (!empty($skills)) : ?>
    <strong class="skills-label">Skills:</strong> <span class="skills"><?php echo esc_html($skills); ?></span>
<?php endif; ?>

        <p><?php echo esc_html(get_the_author_meta('description', $author_id)); ?></p>
    </div>
</div>
 

<div class="author-social-links">
    <?php
    // Product ke author ke social media URLs ko fetch karna
    $facebook_url = get_user_meta($author_id, 'facebook_url', true);
    $twitter_url = get_user_meta($author_id, 'twitter_url', true);
    $instagram_url = get_user_meta($author_id, 'instagram_url', true);
    $linkedin_url = get_user_meta($author_id, 'linkedin_url', true);

    // Social media links ko display karna
    if (!empty($facebook_url)) {
        echo '<a href="' . esc_url($facebook_url) . '" target="_blank" class="social-link facebook">Facebook</a>';
    }
    if (!empty($twitter_url)) {
        echo '<a href="' . esc_url($twitter_url) . '" target="_blank" class="social-link twitter">Twitter</a>';
    }
    if (!empty($instagram_url)) {
        echo '<a href="' . esc_url($instagram_url) . '" target="_blank" class="social-link instagram">Instagram</a>';
    }
    if (!empty($linkedin_url)) {
        echo '<a href="' . esc_url($linkedin_url) . '" target="_blank" class="social-link linkedin">LinkedIn</a>';
    }
    ?>
</div>
<div class="follow-followers">
  
    <!-- Followers Count -->
    <?php
    // Function to format numbers with K, L, or M notation
    function degikart_format_follower_count($count) {
        if ($count >= 10000000) {
            return number_format($count / 1000000, 1) . 'M'; // Millions
        } elseif ($count >= 100000) {
            return number_format($count / 100000, 1) . 'L'; // Lakhs
        } elseif ($count >= 1000) {
            return number_format($count / 1000, 1) . 'K'; // Thousands
        } else {
            return $count; // Less than 1000, return the number as is
        }
    }

    $author_id = get_the_author_meta('ID');
    $current_user_id = get_current_user_id();

    // Get the follower count and format it
    $followers = get_user_meta($author_id, 'degikart_followers', true);

    // Ensure $followers is an array before counting
    if (is_array($followers)) {
        $followers_count = count($followers); // Count followers if it's an array
    } else {
        $followers_count = 0; // If not an array, set count to 0
    }

    $formatted_count = degikart_format_follower_count($followers_count);
    ?>

    <p class="followers-count"><?php echo sprintf(__('%s followers', 'degikart'), $formatted_count); ?></p>

    <!-- Follow Button -->
    <?php if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();
        $author_id = get_the_author_meta('ID');
        $is_following = get_user_meta($current_user_id, 'degikart_following_' . $author_id, true);
        $button_text = $is_following ? __('Unfollow', 'degikart') : __('Follow', 'degikart');
    ?>
        <button class="follow-btn" data-author-id="<?php echo $author_id; ?>"><?php echo $button_text; ?></button>
        <?php } ?>

</div>

<?php
// Get the author ID
$author_id = get_the_author_meta('ID');

// Fetch and display followers
$followers = get_user_meta($author_id, 'degikart_followers', true);

if (!empty($followers)) {
    echo '<div class="followers-box">';
    foreach ($followers as $follower_id) {
        $follower_user = get_user_by('id', $follower_id);
        if ($follower_user) {
            $follower_avatar = get_avatar_url($follower_user->ID, array('size' => 50));
            $follower_profile_url = get_author_posts_url($follower_user->ID);
            echo '<div class="follower-item">';
            echo '<a href="' . esc_url($follower_profile_url) . '">';
            echo '<img src="' . esc_url($follower_avatar) . '" alt="' . esc_attr($follower_user->user_login) . '" class="follower-avatar" />';
            echo esc_html($follower_user->user_login);
            echo '</a>';
            echo '</div>';
        }
    }
    echo '</div>';
} else {
   
}
?>

<script>
jQuery(document).ready(function($) {
    // Handle the follow/unfollow button click
    $('.follow-btn').on('click', function(event) {
        event.preventDefault(); // Prevent page reload

        var button = $(this);
        var author_id = button.data('author-id');

        // Send the AJAX request to follow/unfollow
        $.ajax({
            url: ajaxurl, // This is the localized WordPress AJAX URL
            type: 'POST',
            data: {
                action: 'degikart_follow_unfollow', // Updated action name
                author_id: author_id
            },
            success: function(response) {
                var data = JSON.parse(response); // Parse the JSON response

                if (data.status === 'followed') {
                    button.text('<?php echo __('Unfollow', 'degikart'); ?>'); // Change button text to 'Unfollow'
                } else if (data.status === 'unfollowed') {
                    button.text('<?php echo __('Follow', 'degikart'); ?>'); // Change button text to 'Follow'
                }

                // Update the follower count
                updateFollowerCount(data.count);
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error: " + error); // Log AJAX errors for debugging
            }
        });
    });

    // Function to update the follower count
    function updateFollowerCount(count) {
        var countText = $('.followers-count');
        countText.text(' ' + formatNumber(count) + ' <?php echo __('followers.', 'degikart'); ?>');
    }

    // Function to format numbers (K, L, M)
    function formatNumber(count) {
        if (count >= 10000000) {
            return (count / 1000000).toFixed(1) + 'M';
        } else if (count >= 100000) {
            return (count / 100000).toFixed(1) + 'L';
        } else if (count >= 1000) {
            return (count / 1000).toFixed(1) + 'K';
        } else {
            return count;
        }
    }
});
</script>

<style>/* Styling for the Skills label */
.skills-label {
    font-weight: bold; /* Makes the 'Skills' text bold */
    color: #0073e6; /* Set the color to a nice blue */
    font-size: 16px; /* Adjust the font size */
    margin-right: 5px; /* Adds a small space after the 'Skills:' label */
}

    /* General author card container */
.author-info {
    display: flex;
    flex-direction: column;
    background-color: #f9f9f9;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    transition: all 0.3s ease;
}

.author-profile {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

/* Profile Image Container */
.profile-image {
    border-radius: 50%; /* Ensure the container is circular */
   
    margin-right: 20px; /* Spacing between image and profile info */
}

/* Profile Image */
.profile-image img { 
    width: 100px; /* Fixed width for circular shape */
    height: 100px; /* Fixed height to maintain a square shape */
    object-fit: cover; /* Ensure the image covers the area without distorting */
    border-radius: 50%; /* Round the image itself */
}
.profile-info h1 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    color: #333;
}

.profile-info p {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
}
/* Author Social Links - Flex Layout */
.author-social-links {
    display: flex;
    justify-content: flex-start; /* Align items to the left */
    flex-wrap: wrap;  /* Allow links to wrap onto the next line if needed */
    gap: 15px;  /* Add some space between the social media links */
}

.social-link {
    display: inline-block;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
    text-decoration: none;
    color: #fff;
    transition: background-color 0.3s ease;
}

.social-link.facebook {
    background-color: #3b5998;
}

.social-link.twitter {
    background-color: #1da1f2;
}

.social-link.instagram {
    background-color: #e1306c;
}

.social-link.linkedin {
    background-color: #0077b5;
}

/* Hover effect for social links */
.social-link:hover {
    background-color: #444;
}

/* Follow button */
.follow-btn {
    background-color: #0073e6;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.follow-btn:hover {
    background-color: #005bb5;
}

.follow-login-message {
    margin-top: 10px;
    font-size: 14px;
    color: #0073e6;
}

.follow-login-message a {
    text-decoration: none;
    color: inherit;
    font-weight: bold;
}

.follow-followers {
    display: flex;
    align-items: center;  /* Vertically align both elements */
    justify-content: space-between; /* Add space between the button and the followers count */
    width: 100%; /* Ensure the container takes full width */
    margin-top: 20px; /* Optional: Add some space from the top */
}
 
 

/* Style for Follow Button */
.follow-btn {
    background-color: #0073e6;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 30px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.follow-btn:hover {
    background-color: #005bb5;
}

/* Follower count */
.followers-count {
    font-size: 16px;
    color: #333;
    margin-top: 20px;
}


 
/* Followers Box (Scrollable) */
.followers-box {
    display: flex;
    flex-wrap: wrap; /* Allows items to wrap to the next line if needed */
    gap: 10px; /* Adds space between follower items */
    max-height: 150px; /* Adjust the height according to your design */
    overflow-y: auto; /* Enables vertical scrolling if the content overflows */
    padding-right: 15px; /* Adds some space on the right side to prevent scrollbar overlap */
    margin-top: 20px;
    width: 100%; /* Make sure the box takes full width */
}

/* Follower item */
.follower-item {
    display: flex;
    align-items: center;
     
    width: 48%; /* Ensures that two items take up 50% each */
    margin-bottom: 10px; /* Adds space between rows of items */
}
.follower-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
}

.follower-item a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.follower-item a:hover {
    color: #0073e6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .author-info {
        padding: 15px;
    }


    .author-profile {
        flex-direction: column;
 
    }
.profile-image {
    margin-right: 0px;
}
    .profile-info h1 {
        font-size: 20px;
    }

    .profile-info p {
        font-size: 14px;
    }

    .followers-box {
        display: block;
    }

    .social-link {
        font-size: 12px;
        padding: 6px 12px;
    }
    .follow-followers {
     
    justify-content: space-between; /* Add space between the button and the followers count */
    gap: 20px; /* Optional: Adds space between the button and followers count */
}
 
}






 
</style>

   </div>

        <div class="author-products">
            
            <h2 class="author-h2"><?php _e('Products', 'degikart'); ?></h2>
            <div class="products-grid">
                <?php
                $args = array(
                    'post_type' => ['course', 'plugin', 'blogger', 'wordpress', 'ecommerce'],
                    'author' => $author_id,
                    'posts_per_page' => -1,
                    'post_status' => 'publish' // Only show published posts
                );
                $products = new WP_Query($args);
                $total_sales = 0;
                $total_ratings = 0;
                $total_raters = 0;

                if ($products->have_posts()) :
                    $product_count = 0;
                    while ($products->have_posts()) : $products->the_post();
                        $meta = get_post_meta(get_the_ID());
                        $sales_count = isset($meta['sales_count'][0]) ? (int)$meta['sales_count'][0] : 0;
                        $total_sales += $sales_count;

                        $comments = get_comments(array('post_id' => get_the_ID()));
                        $ratings_count = 0;
                        $product_ratings_sum = 0;

                        foreach ($comments as $comment) {
                            $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                            if ($rating) {
                                $ratings_count++;
                                $product_ratings_sum += (int)$rating;
                            }
                        }

                        if ($ratings_count > 0) {
                            $total_raters++;
                            $total_ratings += $product_ratings_sum;
                        }

                        $product_count++;
                ?>
                <div class="product-card" style="display: <?php echo ($product_count <= 3) ? 'block' : 'none'; ?>;">
                <div class="image-box">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                    <h2 class="main-entry-title" itemprop="headline">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                  
                  <div class="author-itemdata">

                
                    <p><strong><?php _e('Sales:', 'degikart'); ?></strong> <?php echo esc_html($sales_count); ?></p>
                    <p> 
                        <?php 
                        $average_rating = $ratings_count > 0 ? $product_ratings_sum / $ratings_count : 0;
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $average_rating) ? '★' : '☆';
                        }
                        ?>
                        (<?php echo esc_html($ratings_count); ?>)
                    </p>
                    <p class="author-price"> $<?php echo esc_html($meta['regular_price'][0]); ?></p>
                    </div>
                </div>
                <?php
                    endwhile;
                else :
                ?>
                <p><?php _e('No products found.', 'degikart'); ?></p>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
            <button id="show-more" style="margin-top: 20px;"><?php _e('Show More', 'degikart'); ?></button>
            <div class="total-info">



<div class="total-ratings">
    <?php
    // Initialize total ratings and total raters variables
    $total_ratings = 0;
    $total_raters = 0;

    // Get all products from the current author
    $author_id = get_the_author_meta('ID');
    $args = array(
        'post_type' => array('course', 'plugin', 'blogger', 'wordpress', 'ecommerce'), // Correct post types
        'posts_per_page' => -1, // Get all products
        'author' => $author_id
    );

    $products = new WP_Query($args);

    // Loop through each product and calculate total ratings and raters
    if ($products->have_posts()) :
        while ($products->have_posts()) : $products->the_post();

            $product_id = get_the_ID();
            $comments = get_comments(array('post_id' => $product_id));
            
            foreach ($comments as $comment) {
                $rating = get_comment_meta($comment->comment_ID, 'rating', true);
                if ($rating) {
                    $total_ratings += $rating; // Add rating to total
                    $total_raters++; // Increment raters count
                }
            }

        endwhile;
    endif;
    wp_reset_postdata();

    // Calculate average rating (out of 5)
    $total_average_rating = $total_raters > 0 ? $total_ratings / $total_raters : 0;

    // Round average rating to the nearest half-star (0.5 increments)
    $rounded_rating = round($total_average_rating * 2) / 2;
    ?>

    <h3 class="overall-rating">
        <?php _e('Overall Rating:', 'degikart'); ?>
        <span class="rating-stars">
            <?php 
            // Display the filled stars (full, half, and empty stars)
            $full_stars = floor($rounded_rating); // Number of full stars
            $half_star = ($rounded_rating - $full_stars) >= 0.5; // If there's a half-star
            $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0); // Remaining empty stars

            // Display full stars
            echo str_repeat('<span class="star yellow">★</span>', $full_stars);

            // Display half star if needed
            if ($half_star) {
                echo '<span class="star half">★</span>';
            }

            // Display empty stars
            echo str_repeat('<span class="star">☆</span>', $empty_stars);
            ?>
        </span>
        <span class="rating-count">(<?php echo esc_html($total_raters); ?> <?php _e('ratings', 'degikart'); ?>)</span>
    </h3>

    <div class="total-sales">
        <h3><?php _e('Total Sales:', 'degikart'); ?> <span class="sales-count"><?php echo esc_html($total_sales); ?></span></h3>
    </div>
</div>

</div>



            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('show-more').addEventListener('click', function() {
        const hiddenProducts = document.querySelectorAll('.product-card[style*="none"]');
        hiddenProducts.forEach(product => {
            product.style.display = 'block'; // Show hidden products
        });
        this.style.display = 'none'; // Hide the button after showing products
    });
</script>

<?php get_footer(); ?>
