jQuery(document).ready(function($) {
    $('.follow-btn').on('click', function() {
        var button = $(this);
        var authorId = button.data('author-id'); // Get the author ID from the button

        // Send the AJAX request
        $.post(ajaxurl, {
            action: 'follow_unfollow',  // The action hook to trigger
            author_id: authorId         // Pass the author ID in the request
        }, function(response) {
            if (response === 'followed') {
                button.text('Unfollow');  // Change button text to "Unfollow"
            } else if (response === 'unfollowed') {
                button.text('Follow');    // Change button text to "Follow"
            }
        });
    });
});

 