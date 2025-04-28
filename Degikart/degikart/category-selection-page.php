<?php
/*
Template Name: Category Selection Page
*/

if (!is_user_logged_in()) {
    // Store the current page URL (payment page) so that after login user is redirected back to this page
    wp_redirect(home_url('/login') . '?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}


get_header();
?>
<div id="site-width">
<form action="" method="get" id="category-form" class="category-selection-form">
    <label for="category">Select Category:</label>
    <select id="category" name="category" required>
    <option value="" disabled selected>Select Category</option>   
        <option value="wordpress">WordPress</option>
        <option value="ecommerce">eCommerce</option>
        <option value="plugin">Plugins</option>
        <option value="blogger">Blogger</option>
        <option value="courses">Courses</option>
    </select><br>
    <input type="submit" value="Next">
  
</form>
</div>
<script>
document.getElementById('category-form').onsubmit = function() {
    var category = document.getElementById('category').value;
    if (category) {
        window.location.href = '<?php echo esc_url(home_url()); ?>/' + category + '-upload/';
    } else {
        alert('Please select a category.');
    }
    return false;
}
</script>

<?php
get_footer();
?>
