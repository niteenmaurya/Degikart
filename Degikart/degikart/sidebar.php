 
<?php
/**
 * Sidebar Template
 *
 * @package degikart
 */
?>

<div class="middle-right-bottom">
    <?php if ( is_active_sidebar( 'sidebar' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar' ); ?>
    <?php else : ?>
        <p><?php esc_html_e( 'No widgets found.', 'degikart' ); ?></p>
    <?php endif; ?>
</div>
