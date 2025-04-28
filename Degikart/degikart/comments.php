<?php
if ( have_comments() ) :
    // Display comments
    wp_list_comments(array(
        'style'      => 'ol',
        'short_ping' => true,
        'avatar_size'=> 64,
        'callback'   => 'degikart_custom_comments'  // Updated function name here
    ));

    // Add comment pagination
    the_comments_navigation();
endif;

// Display comment form
comment_form();
?>
