<footer class="footer">
    <div id="site-width">
        <div class="footer-widgets <?php echo ( !is_active_sidebar('footer1') && !is_active_sidebar('footer2') && !is_active_sidebar('footer3') && !is_active_sidebar('footer4') ) ? 'empty' : ''; ?>">
            <div class="footer-widget">
                <?php if (is_active_sidebar('footer1')) : ?>
                    <?php dynamic_sidebar('footer1'); ?>
                <?php endif; ?>
            </div>
            <div class="footer-widget">
                <?php if (is_active_sidebar('footer2')) : ?>
                    <?php dynamic_sidebar('footer2'); ?>
                <?php endif; ?>
            </div>
            <div class="footer-widget">
                <?php if (is_active_sidebar('footer3')) : ?>
                    <?php dynamic_sidebar('footer3'); ?>
                <?php endif; ?>
            </div>
            <div class="footer-widget">
                <?php if (is_active_sidebar('footer4')) : ?>
                    <?php dynamic_sidebar('footer4'); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-copyright">
            <div>
                <?php echo degikart_dynamic_copyright(); ?>
            </div>
            <button onclick="topFunction()" id="myBtn" title="<?php esc_attr_e('Go to top', 'degikart'); ?>" style="color: #D3D3D3; background-color: transparent; border: none;">
                <svg width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V4.707l3.147 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 .708.708L7.5 4.707V11.5A.5.5 0 0 0 8 12z"/>
                </svg>
                <?php esc_html_e('Top', 'degikart'); ?>
            </button>          
        </div>
    </div>
</footer>
<?php wp_footer(); ?>