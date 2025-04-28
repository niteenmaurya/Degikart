<!-- Check if the post has been updated -->
<?php
// Last update aur publish date ke Unix timestamps nikalna
$last_update = get_the_modified_time('U');
$publish_date = get_the_time('U');

// Check karna agar post ke last update ka time publish date se different hai
if ($last_update !== $publish_date) :
?>
     
<!-- Agar post update hui hai tab -->
<?php endif; ?>
<div class="post-dates">
    <p class="post-created">Created: <?php echo get_the_date(); ?></p>
    <p class="post-updated">Updated: <?php echo get_the_modified_date(); ?></p>
</div>
