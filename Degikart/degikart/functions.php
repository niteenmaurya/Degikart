<?php
function degikart_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    add_theme_support('align-wide');
    add_theme_support('post-thumbnails');

    // Add custom henqueue_scrader support
    add_theme_support('custom-header', array(
        'header-text' => true,
        // other settings
    ));

 
    // Register Custom Menu Locations
    register_nav_menus( array(
        'primary-menu' => __( 'Primary Menu', 'degikart' ),
        'footer-menu'  => __( 'Footer Menu', 'degikart' ),
    ));


 
// Add custom logo support
function degikart_custom_logo_setup() {
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'degikart_custom_logo_setup');




}
add_action('after_setup_theme', 'degikart_theme_setup');

// Enqueue customize preview script
function degikart_customize_preview_js() {
    wp_enqueue_script('degikart-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array('customize-preview'), '1.0', true);
}
add_action('customize_preview_init', 'degikart_customize_preview_js');

function customize_register($wp_customize) {
    // Add a section for layout settings
    $wp_customize->add_section('layout_settings', array(
        'title' => __('Layout Settings', 'degikart'),
        'priority' => 30,
    ));

    // Title settings
    $wp_customize->add_setting('degikart_title_color', array(
        'default' => '#000000',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_setting('degikart_title_font_size', array(
        'default' => '24px',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_setting('degikart_title_margin', array(
        'default' => '10px 0',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_setting('degikart_title_padding', array(
        'default' => '0',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add control
    $wp_customize->add_control('degikart_toggle_container_control', array(
        'label' => __('Toggle Container Visibility', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_toggle_container',
        'type' => 'checkbox',
    ));

    // Meta settings
    $wp_customize->add_setting('degikart_meta_color', array(
        'default' => '#666666',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_setting('degikart_meta_font_size', array(
        'default' => '14px',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_setting('degikart_meta_margin', array(
        'default' => '5px 0',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_setting('degikart_meta_padding', array(
        'default' => '0',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
 
// Excerpt settings
$wp_customize->add_setting('degikart_excerpt_color', array(
    'default' => '#333333',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_setting('degikart_excerpt_font_size', array(
    'default' => '16px',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_excerpt_margin', array(
    'default' => '10px 0',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_excerpt_padding', array(
    'default' => '0',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_excerpt_length', array(
    'default' => '55',
    'transport' => 'refresh',
    'sanitize_callback' => 'absint',
));

// Button settings
$wp_customize->add_setting('degikart_button_color', array(
    'default' => '#0073aa',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_setting('degikart_button_text_color', array(
    'default' => '#ffffff',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_hex_color',
));
$wp_customize->add_setting('degikart_button_font_size', array(
    'default' => '16px',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_button_margin', array(
    'default' => '10px 0',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_button_padding', array(
    'default' => '10px 15px',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));

// Grid settings
$wp_customize->add_setting('degikart_grid_columns', array(
    'default' => '3',
    'transport' => 'refresh',
    'sanitize_callback' => 'absint',
));
$wp_customize->add_setting('degikart_grid_gap', array(
    'default' => '20px',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_text_field',
));
$wp_customize->add_setting('degikart_grid_bg_color', array(
    'default' => '#f0f0f0',
    'transport' => 'refresh',
    'sanitize_callback' => 'sanitize_hex_color',
));

    // Add controls for title settings
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'degikart_title_color_control', array(
        'label' => __('Title Color', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_title_color',
    )));
    $wp_customize->add_control('degikart_title_font_size_control', array(
        'label' => __('Title Font Size', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_title_font_size',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_title_margin_control', array(
        'label' => __('Title Margin', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_title_margin',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_title_padding_control', array(
        'label' => __('Title Padding', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_title_padding',
        'type' => 'text',
    ));

    // Add controls for meta settings
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'degikart_meta_color_control', array(
        'label' => __('Meta Color', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_meta_color',
    )));
    $wp_customize->add_control('degikart_meta_font_size_control', array(
        'label' => __('Meta Font Size', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_meta_font_size',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_meta_margin_control', array(
        'label' => __('Meta Margin', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_meta_margin',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_meta_padding_control', array(
        'label' => __('Meta Padding', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_meta_padding',
        'type' => 'text',
    ));

    // Add controls for excerpt settings
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'degikart_excerpt_color_control', array(
        'label' => __('Excerpt Color', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_excerpt_color',
    )));
    $wp_customize->add_control('degikart_excerpt_font_size_control', array(
        'label' => __('Excerpt Font Size', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_excerpt_font_size',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_excerpt_margin_control', array(
        'label' => __('Excerpt Margin', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_excerpt_margin',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_excerpt_padding_control', array(
        'label' => __('Excerpt Padding', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_excerpt_padding',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_excerpt_length_control', array(
        'label' => __('Excerpt Length', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_excerpt_length',
        'type' => 'number',
    ));

    // Add controls for button settings
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'degikart_button_color_control', array(
        'label' => __('Button Background Color', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_button_color',
    )));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'degikart_button_text_color_control', array(
        'label' => __('Button Text Color', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_button_text_color',
    )));
    $wp_customize->add_control('degikart_button_font_size_control', array(
        'label' => __('Button Font Size', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_button_font_size',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_button_margin_control', array(
        'label' => __('Button Margin', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_button_margin',
        'type' => 'text',
    ));
    $wp_customize->add_control('degikart_button_padding_control', array(
        'label' => __('Button Padding', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_button_padding',
        'type' => 'text',
    ));

    // Add controls for grid settings
    $wp_customize->add_control('degikart_grid_columns_control', array(
        'label' => __('Grid Columns', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_grid_columns',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 5,
        ),
    ));
    $wp_customize->add_control('degikart_grid_gap_control', array(
        'label' => __('Grid Gap', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_grid_gap',
        'type' => 'text',
    ));



    // Add setting with sanitization callback
    $wp_customize->add_setting('degikart_toggle_container', array(
        'default' => false,
        'transport' => 'refresh',
        'sanitize_callback' => 'degikart_sanitize_checkbox',
    ));

    // Add control
    $wp_customize->add_control('degikart_toggle_container_control', array(
        'label' => __('Toggle Container Visibility', 'degikart'),
        'section' => 'layout_settings',
        'settings' => 'degikart_toggle_container',
        'type' => 'checkbox',
    ));
}
add_action('customize_register', 'customize_register');

// Sanitization callback function
function degikart_sanitize_checkbox($checked) {
    // Boolean check for checkbox input
    return ((isset($checked) && true == $checked) ? true : false);
}


// Register block styles
function degikart_register_block_styles() {
    register_block_style(
        'core/paragraph',
        array(
            'name'  => 'fancy-paragraph',
            'label' => __('Fancy Paragraph', 'degikart'),
        )
    );
}
add_action('init', 'degikart_register_block_styles');

// Register block patterns
function degikart_register_block_patterns() {
    register_block_pattern(
        'degikart/my-pattern',
        array(
            'title'       => __('My Pattern', 'degikart'),
            'description' => _x('A custom block pattern', 'Block pattern description', 'degikart'),
            'content'     => "<!-- wp:paragraph --><p>" . __('Content goes here', 'degikart') . "</p><!-- /wp:paragraph -->",
        )
    );
}
add_action('init', 'degikart_register_block_patterns');

function degikart_enqueue_scripts() {
    // Enqueue custom script
    wp_enqueue_script('degikart-custom-script', get_template_directory_uri() . '/js/custom-script.js', array(), '1.0', true);
    
    // Enqueue header.js
    wp_enqueue_script('header-scripts', get_template_directory_uri() . '/js/header.js', array(), null, true);
    
    // Enqueue index.js
    wp_enqueue_script('index-script', get_template_directory_uri() . '/js/index.js', array(), null, true);
    
    wp_enqueue_script('dashboard-script', get_template_directory_uri() . '/js/dashboard.js', array('jquery'), null, true);

    // Localize script for AJAX
    wp_localize_script('degikart-custom-script', 'ajaxurl', admin_url('admin-ajax.php'));

        // Enqueue custom script
        wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), null, true);
        wp_enqueue_script('sorting-js', get_template_directory_uri() . '/js/sorting.js', array(), null, true);
      
    // Localize script to pass the PHP checkout URL
    wp_localize_script('sorting-js', 'themeData', array(
        'checkoutUrl' => esc_url(home_url('/checkout'))
    ));
        // Localize the script with data from PHP
        if (have_posts()) :
            while (have_posts()) : the_post();
                $meta = get_post_meta(get_the_ID());
                $regular_price = isset($meta['regular_price'][0]) ? esc_html($meta['regular_price'][0]) : 0;
                $extended_price = isset($meta['extended_price'][0]) ? esc_html($meta['extended_price'][0]) : 0;
                $support_price_12_months = isset($meta['support_price'][0]) ? esc_html($meta['support_price'][0]) : 0;
    
                wp_localize_script('custom-script', 'scriptData', array(
                    'commentCount' => get_comments_number(),
                    'regularPrice' => $regular_price,
                    'extendedPrice' => $extended_price,
                    'supportPrice' => $support_price_12_months,
                ));
            endwhile;
        endif;
    
}
add_action('wp_enqueue_scripts', 'degikart_enqueue_scripts');



// Enqueue comment reply script
function degikart_enqueue_comment_reply() {
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'degikart_enqueue_comment_reply');
 
function degikart_add_editor_styles() {
    add_editor_style('editor-style.css');
}
add_action('admin_init', 'degikart_add_editor_styles');



function degikart_customize_css() {
    ?>
    <style type="text/css">
        .post-title {
            color: <?php echo esc_attr(get_theme_mod('degikart_title_color', '#000000')); ?>;
            font-size: <?php echo esc_attr(get_theme_mod('degikart_title_font_size', '24px')); ?>;
            margin: <?php echo esc_attr(get_theme_mod('degikart_title_margin', '10px 0')); ?>;
            padding: <?php echo esc_attr(get_theme_mod('degikart_title_padding', '0')); ?>;
        }
        .post-meta {
            color: <?php echo esc_attr(get_theme_mod('degikart_meta_color', '#666666')); ?>;
            font-size: <?php echo esc_attr(get_theme_mod('degikart_meta_font_size', '14px')); ?>;
            margin: <?php echo esc_attr(get_theme_mod('degikart_meta_margin', '5px 0')); ?>;
            padding: <?php echo esc_attr(get_theme_mod('degikart_meta_padding', '0')); ?>;
        }
        .post-content {
            color: <?php echo esc_attr(get_theme_mod('degikart_excerpt_color', '#333333')); ?>;
            font-size: <?php echo esc_attr(get_theme_mod('degikart_excerpt_font_size', '16px')); ?>;
            margin: <?php echo esc_attr(get_theme_mod('degikart_excerpt_margin', '10px 0')); ?>;
            padding: <?php echo esc_attr(get_theme_mod('degikart_excerpt_padding', '0')); ?>;
        }
        .read-more-link {
            background-color: <?php echo esc_attr(get_theme_mod('degikart_button_color', '#0073aa')); ?>;
            color: <?php echo esc_attr(get_theme_mod('degikart_button_text_color', '#ffffff')); ?>;
            font-size: <?php echo esc_attr(get_theme_mod('degikart_button_font_size', '16px')); ?>;
            margin: <?php echo esc_attr(get_theme_mod('degikart_button_margin', '10px 0')); ?>;
            padding: <?php echo esc_attr(get_theme_mod('degikart_button_padding', '10px 15px')); ?>;
        }
        .card-container, .site-cont  {
            gap: <?php echo esc_attr(get_theme_mod('degikart_grid_gap', '20px')); ?> !important;
         
 
        }
        .grid-item {
            background: #fff !important;
            padding: 10px !important;
            border: 1px solid #ddd !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        <?php if (get_theme_mod('degikart_toggle_container', false)) : ?>
            .card-container article, .site-cont  {
                background: transparent !important;
            border: 0px;
            grid-template-columns: repeat(1, minmax(0, 1fr))!important;
        }
        .card-container, .site-cont {
      display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr))!important;
    }
    @media (min-width: 1200px) {
    .card-container, .site-cont {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr))!important;
    }
        }
        .product-content {
    display: flex
;
    flex-direction: row;
}

.entry-content, .image-box, .entery-header {
    display: flex
;
    flex-direction: row;
 }


.image-box {
    width: 40%;

    margin-right: 5px;
}

.main-price {
    margin-left:auto;
}

.entry-header {
    width: 100%;  
}
.cart-btn {
    display: none;
}
.card-container article  {
       justify-content: space-between;
        }

        .inside-article {
    display: flex
;    margin-right: 5px;
    flex-direction: row;

     
}
.entry-content {
    width: 50%;  display: block;
}
.live-cart { 
    display:block;
}
.sales-preview {
    display: block;
    margin-top:auto;
}
        .grid-item {
            background: transparent !important;
            padding: 0px !important;
            border: none !important;
            box-shadow: none !important;
        }
        <?php endif; ?>

        @media (min-width: 1200px) {
    .card-container, .site-cont {
        display: grid;
        grid-template-columns: repeat(<?php echo esc_attr(get_theme_mod('degikart_grid_columns', '2')); ?>, minmax(0, 1fr));
    }
        }
        
    </style>
    <?php
}
add_action('wp_head', 'degikart_customize_css');

function degikart_customize_sections($wp_customize) {
    // Add a section for layout settings
    $wp_customize->add_section('layout_settings', array(
        'title' => __('Layout Settings', 'degikart'),
        'priority' => 30,
    ));

    // Add a section for homepage settings
    $wp_customize->add_section('homepage_settings', array(
        'title' => __('Homepage Settings', 'degikart'),
        'priority' => 31,
    ));
}
add_action('customize_register', 'degikart_customize_sections');









 







function degikart_customize_register($wp_customize) {
    // Add section for the customizer option
    $wp_customize->add_section('degikart_post_options', array(
        'title'    => __('Post Options', 'degikart'),
        'priority' => 30,
    ));

    // Add setting to control the featured image visibility
    $wp_customize->add_setting('show_featured_image', array(
        'default'           => 'yes', // Default value: 'yes' (show image)
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add control to toggle the featured image display
    $wp_customize->add_control('show_featured_image', array(
        'label'   => __('Show Featured Image on Posts', 'degikart'),
        'section' => 'degikart_post_options',
        'type'    => 'radio',
        'choices' => array(
            'yes' => __('Yes', 'degikart'),
            'no'  => __('No', 'degikart'),
        ),
    ));
    
    // Add a section for the footer settings
    $wp_customize->add_section('degikart_footer_section', array(
        'title' => __('Footer Settings', 'degikart'),
        'priority' => 30,
    ));

    // Add a setting for the copyright text
    $wp_customize->add_setting('degikart_copyright_text', array(
        'default' => '© ' . date('Y') . ' ' . get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add a setting for the site link
    $wp_customize->add_setting('degikart_site_link', array(
        'default' => home_url(), // Default to the site URL
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Add a control to change the copyright text
    $wp_customize->add_control('degikart_copyright_text_control', array(
        'label' => __('Copyright Text', 'degikart'),
        'section' => 'degikart_footer_section',
        'settings' => 'degikart_copyright_text',
        'type' => 'text',
    ));

    // Add a control to change the site link
    $wp_customize->add_control('degikart_site_link_control', array(
        'label' => __('Site Link', 'degikart'),
        'section' => 'degikart_footer_section',
        'settings' => 'degikart_site_link',
        'type' => 'url',
    ));


    }
    add_action('customize_register', 'degikart_customize_register');

// Function to display the dynamic copyright text with site link
function degikart_dynamic_copyright() {
    $site_link = get_theme_mod('degikart_site_link', home_url()); // Changed get_site_url() to home_url()
    $site_name = get_bloginfo('name');
    $current_year = date('Y');
    $copyright_text = get_theme_mod('degikart_copyright_text', '© ' . $current_year . ' ' . $site_name);

    if ($site_link === '#' || empty($site_link)) {
        return esc_html($copyright_text);
    } else {
        return str_replace($site_name, '<a href="' . esc_url($site_link) . '">' . esc_html($site_name) . '</a>', esc_html($copyright_text));
    }
}

// Hook to display the dynamic copyright text in the footer
add_action('wp_footer', 'degikart_dynamic_copyright');

    // Register sidebars in a custom function hooked to widgets_init
    function degikart_widgets_init() {
        register_sidebar(array(
            'name' => esc_html__('Sidebar Location', 'degikart'),
            'id' => 'sidebar',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer 1', 'degikart'),
            'id' => 'footer1',
            'description' => esc_html__('Add Widgets Here for the content of footer1', 'degikart'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer 2', 'degikart'),
            'id' => 'footer2',
            'description' => esc_html__('Add Widgets Here for the content of footer2', 'degikart'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer 3', 'degikart'),
            'id' => 'footer3',
            'description' => esc_html__('Add Widgets Here for the content of footer3', 'degikart'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'name' => esc_html__('Footer 4', 'degikart'),
            'id' => 'footer4',
            'description' => esc_html__('Add Widgets Here for the content of footer4', 'degikart'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
        ));
    }
    add_action('widgets_init', 'degikart_widgets_init');




























    function set_posts_per_page_for_search($query) {
        if ($query->is_search() && $query->is_main_query()) {
            $query->set('posts_per_page', 18);
        }
    }
    add_action('pre_get_posts', 'set_posts_per_page_for_search');
    

































function flush_rewrite_rules_on_init() {
    flush_rewrite_rules();
}
add_action('init', 'flush_rewrite_rules_on_init');

// Register Custom Post Statuses
function register_custom_post_statuses() {
    register_post_status('unapprove', array(
        'label'                     => _x('Unapprove', 'post', 'degikart'),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Unapprove <span class="count">(%s)</span>', 'Unapprove <span class="count">(%s)</span>', 'degikart'),
    ));
}
add_action('init', 'register_custom_post_statuses');

// Handle Product Upload
if (!function_exists('handle_product_upload')) {
    function handle_product_upload() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and validate input fields
            $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
            $theme_name = isset($_POST['theme-name']) ? sanitize_text_field($_POST['theme-name']) : '';
            $author_name = isset($_POST['author-name']) ? sanitize_text_field($_POST['author-name']) : '';
            $price = isset($_POST['price']) ? sanitize_text_field($_POST['price']) : '';
            $version = isset($_POST['version']) ? sanitize_text_field($_POST['version']) : '';
            $details = isset($_POST['details']) ? sanitize_textarea_field($_POST['details']) : '';

            // Check if all fields are filled
            if (empty($category) || empty($theme_name) || empty($author_name) || empty($price) || empty($version) || empty($details)) {
                echo '';
                return;
            }

            // Check if theme name already exists
            $existing_post = get_page_by_title($theme_name, OBJECT, 'product');
            if ($existing_post) {
                // Check if the version already exists
                $existing_versions = get_post_meta($existing_post->ID, 'versions', true);
                if (!$existing_versions) {
                    $existing_versions = array();
                }
                foreach ($existing_versions as $existing_version) {
                    if ($existing_version['version'] == $version) {
                        echo 'This version already exists. Please specify a different version.';
                        return;
                    }
                }
                // Add new version to existing product
                $existing_versions[] = array(
                    'version' => $version,
                    'file_url' => '',
                    'details' => $details,
                    'price' => $price,
                    'author_name' => $author_name,
                    'category' => $category
                );
                update_post_meta($existing_post->ID, 'versions', $existing_versions);
            } else {
                // Handle file upload
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }
                $uploadedfile = isset($_FILES['file']) ? $_FILES['file'] : null;
                if ($uploadedfile && $uploadedfile['error'] == UPLOAD_ERR_OK) {
                    $upload_overrides = array('test_form' => false);
                    $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                    if ($movefile && !isset($movefile['error'])) {
                        // File is uploaded successfully
                        $file_url = $movefile['url'];

                        // Insert post into database
                        $post_id = wp_insert_post(array(
                            'post_title' => $theme_name,
                            'post_content' => $details,
                            'post_status' => 'pending',
                            'post_type' => 'product',
                            'meta_input' => array(
                                'category' => $category,
                                'author_name' => $author_name,
                                'price' => $price,
                                'version' => $version,
                                'file_url' => $file_url,
                            ),
                        ));

                        if ($post_id) {
                            // Redirect to the seller dashboard after successful submission
                            echo '<script type="text/javascript">
                                    window.open("' . home_url('/wp-admin/admin.php?page=uploaded-products') . '", "_self");
                                  </script>';
                        } else {
                            echo 'Failed to submit the product.';
                        }
                    } else {
                        echo 'File upload error: ' . $movefile['error'];
                    }
                } else {
                    echo 'Please upload a valid file.';
                }
            }
        }
    }
}
add_action('template_redirect', 'handle_product_upload');

add_action('wp_ajax_check_product', 'check_product');
add_action('wp_ajax_nopriv_check_product', 'check_product');


function custom_login_redirect($redirect_to, $request, $user) {
    // Check if the user is logged in
    if (isset($user->roles) && is_array($user->roles)) {
        // Redirect to the default dashboard
        return admin_url();
    } else {
        return $redirect_to;
    }
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);

// Registration Redirect
function custom_registration_redirect() {
    return home_url('/custom-dashboard');
}
add_filter('registration_redirect', 'custom_registration_redirect');

// Redirect to home page after logout
function redirect_after_logout() {
    wp_redirect(home_url());
    exit();
} 
add_action('wp_logout', 'redirect_after_logout');

function add_to_cart() {
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        $product_title = sanitize_text_field($_POST['product_title']);
        $license = sanitize_text_field($_POST['license']);
        $support = sanitize_text_field($_POST['support']);
        $price = floatval($_POST['price']);
        
        // Add product to cart (using WooCommerce functions or custom logic)
        // Example using WooCommerce:
        $cart_item_data = array(
            'license' => $license,
            'support' => $support,
            'price' => $price,

        );
        WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);

        echo 'Product added to cart!';
    }
    wp_die();
}
add_action('wp_ajax_add_to_cart', 'add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart');


// Add Meta Boxes for Each Category
function add_product_meta_boxes() {
    add_meta_box(
        'product_meta_box',
        __('Product Details', 'degikart'),
        'product_meta_box_callback',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_product_meta_boxes');

function product_meta_box_callback($post) {
    wp_nonce_field('save_product_meta_box_data', 'product_meta_box_nonce');

    $category = get_post_meta($post->ID, 'category', true);
    $version = get_post_meta($post->ID, 'version', true);

    // Display fields based on category
    $product_categories = wp_get_post_terms($post->ID, 'product-category', array('fields' => 'slugs'));

    if (in_array('wordpress', $product_categories)) {
        echo '<label for="wordpress_field">' . __('WordPress Field', 'degikart') . '</label>';
        echo '<input type="text" id="wordpress_field" name="wordpress_field" value="' . esc_attr($wordpress_field) . '" size="25" /><br>';
    }

    if (in_array('ecommerce', $product_categories)) {
        echo '<label for="ecommerce_field">' . __('eCommerce Field', 'degikart') . '</label>';
        echo '<input type="text" id="ecommerce_field" name="ecommerce_field" value="' . esc_attr($ecommerce_field) . '" size="25" /><br>';
    }

    if (in_array('site-templates', $product_categories)) {
        echo '<label for="site_templates_field">' . __('Site Templates Field', 'degikart') . '</label>';
        echo '<input type="text" id="site_templates_field" name="site_templates_field" value="' . esc_attr($site_templates_field) . '" size="25" /><br>';
    }

    if (in_array('plugins', $product_categories)) {
        echo '<label for="plugins_field">' . __('Plugins Field', 'degikart') . '</label>';
        echo '<input type="text" id="plugins_field" name="plugins_field" value="' . esc_attr($plugins_field) . '" size="25" /><br>';
    }

    if (in_array('blogger', $product_categories)) {
        echo '<label for="blogger_field">' . __('Blogger Field', 'degikart') . '</label>';
        echo '<input type="text" id="blogger_field" name="blogger_field" value="' . esc_attr($blogger_field) . '" size="25" /><br>';
    }

    // Common fields
    echo '<label for="category">' . __('Category', 'degikart') . '</label>';
    echo '<input type="text" id="category" name="category" value="' . esc_attr($category) . '" size="25" /><br>';

    echo '<label for="version">' . __('Version', 'degikart') . '</label>';
    echo '<input type="text" id="version" name="version" value="' . esc_attr($version) . '" size="25" /><br>';
}

// Save Meta Box Data
function save_product_meta_box_data($post_id) {
    if (!isset($_POST['product_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['product_meta_box_nonce'], 'save_product_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['category'])) {
        update_post_meta($post_id, 'category', sanitize_text_field($_POST['category']));
    }
    
    if (isset($_POST['version'])) {
        update_post_meta($post_id, 'version', sanitize_text_field($_POST['version']));
    }
}
add_action('save_post', 'save_product_meta_box_data');



















// Views count
function set_product_views($postID) {
    $count_key = 'product_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

function track_product_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    set_product_views($post_id);
}
add_action('wp_head', 'track_product_views');
 
 






function update_product_impressions() {
    if (is_singular('product')) {
        global $post;
        $impressions = get_post_meta($post->ID, 'impression_count', true);
        $impressions = $impressions ? $impressions + 1 : 1;
        update_post_meta($post->ID, 'impression_count', $impressions);
        error_log("Impressions updated for product ID " . $post->ID . ": " . $impressions);
    }
}
add_action('wp_head', 'update_product_impressions');




function sanitize_html_description($content) {
    // Allow only basic HTML (links, images, text formatting, etc.)
    return wp_kses($content, array(
        'a' => array('href' => array(), 'title' => array()),
        'img' => array('src' => array(), 'alt' => array(), 'width' => array(), 'height' => array(), 'title' => array()),
        'strong' => array(),
        'em' => array(),
        'u' => array(),
        'ul' => array(),
        'ol' => array(),
        'li' => array(),
        'p' => array(),
        'br' => array(),
    ));
}
add_filter('pre_save_post', 'sanitize_html_description');
























// Customizer settings for logo upload and width
function degikart_customizer_settings($wp_customize) {
    // Add setting for logo width with sanitization callback
    $wp_customize->add_setting('degikart_logo_width', array(
        'default' => '150',
        'transport' => 'postMessage', // Use postMessage for live preview
        'sanitize_callback' => 'absint', // Add sanitization callback here
    ));

    // Add slider control for logo width
    $wp_customize->add_control('degikart_logo_width_control', array(
        'label' => __('Logo Width (px)', 'degikart'),
        'section' => 'title_tagline',
        'settings' => 'degikart_logo_width',
        'type' => 'range',
        'input_attrs' => array(
            'min' => 50, // Minimum value
            'max' => 300, // Maximum value
            'step' => 1, // Step size
        ),
    ));

    // Add setting for logo upload
    $wp_customize->add_setting('degikart_logo', array(
        'default' => '',
        'transport' => 'refresh', // Use refresh for logo uploads
        'sanitize_callback' => 'esc_url_raw',
    ));

    // Add control for logo upload
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'degikart_logo_control', array(
        'label' => __('Upload Logo', 'degikart'),
        'section' => 'title_tagline',
        'settings' => 'degikart_logo',
    )));
}
add_action('customize_register', 'degikart_customizer_settings');











// Display Profile Picture in Header
function display_login_or_profile_icon() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        // Get the custom profile picture, if it exists
        $profile_picture_id = get_user_meta($current_user->ID, 'profile_picture', true);

        // Check if a custom profile picture exists
        if ($profile_picture_id) {
            $profile_picture_url = wp_get_attachment_url($profile_picture_id);
        } else {
            // Fallback to Gravatar if no custom profile picture is set
            $profile_picture_url = get_avatar_url($current_user->ID, ['size' => 24]);
        }

        // Output profile picture icon with a link to the profile page
        echo '<a href="' . esc_url(home_url('/profile-page/')) . '" class="profile-icon" id="login-icon">
                <img src="' . esc_url($profile_picture_url) . '" alt="Profile Picture" width="24" height="24">
              </a>';
    } else {
        // If not logged in, show the login icon
        echo '<a href="' . esc_url(home_url('/login/')) . '" class="login-icon" id="login-icon">
               <svg width="25" height="25" viewBox="0 0 256 256">
                  <g transform="translate(1.4 1.4) scale(2.81 2.81)">
                    <path d="M 45 53.718 c -10.022 0 -18.175 -8.153 -18.175 -18.175 S 34.978 17.368 45 17.368 c 10.021 0 18.175 8.153 18.175 18.175 S 55.021 53.718 45 53.718 z" fill="#ffffff"/>
                    <path d="M 45 0 C 20.187 0 0 20.187 0 45 c 0 24.813 20.187 45 45 45 c 24.813 0 45 -20.187 45 -45 C 90 20.187 69.813 0 45 0 z M 74.821 70.096 c -3.543 -5.253 -8.457 -9.568 -14.159 -12.333 c -2.261 -1.096 -4.901 -1.08 -7.247 0.047 c -2.638 1.268 -5.47 1.91 -8.415 1.91 c -2.945 0 -5.776 -0.643 -8.415 -1.91 c -2.343 -1.125 -4.984 -1.143 -7.247 -0.047 c -5.702 2.765 -10.616 7.08 -14.16 12.333 C 9.457 63.308 6 54.552 6 45 C 6 23.495 23.495 6 45 6 s 39 17.495 39 39 C 84 54.552 80.543 63.308 74.821 70.096 z" fill="#ffffff"/>
                  </g>
               </svg>
              </a>';
    }
}







    function add_custom_post_types_to_home($query) {
        if ($query->is_home() && $query->is_main_query()) {
            $query->set('post_type', array('post', 'product')); // 'product' ko apne custom post type ke naam se replace karein
        }
    }
    add_action('pre_get_posts', 'add_custom_post_types_to_home');
    



 


    function custom_post_type_pagination($query = null) {
        global $wp_query;
        if ($query) {
            $wp_query = $query;
        }
    
        $big = 999999999; // need an unlikely integer
        $pagination = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'prev_text' => __('« Previous', 'degikart'),
            'next_text' => __('Next »', 'degikart'),
        ));
    
        if ($pagination) {
            echo '<nav class="custom-pagination">' . $pagination . '</nav>';
        }
    }



























    add_action('wp_ajax_record_purchase', 'handle_record_purchase');
    add_action('wp_ajax_nopriv_record_purchase', 'handle_record_purchase');
    
    function handle_record_purchase() {
        $total_price = $_POST['total_price'];
        $selected_items = json_decode(stripslashes($_POST['selected_items']), true);
    
        // Process the purchase, save it to the database, or perform other actions
        // Example: save total_price and selected_items in an order record
    
        // For debugging:
        error_log('Total Price: ' . $total_price);
        error_log('Selected Items: ' . print_r($selected_items, true));
    
        wp_send_json_success('Purchase recorded successfully');
    }
    






































    function degikart_custom_comments($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment; ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
            <div id="comment-<?php comment_ID(); ?>" class="comment-body">
                <?php echo get_avatar($comment, 64); ?>
                <div class="comment-content">
                    <div class="comment-meta">
                        <span class="comment-author"><?php printf(__(' %s', 'degikart'), get_comment_author_link()); ?></span>
                        <span class="comment-date"><?php printf(__(' %1$s at %2$s', 'degikart'), get_comment_date(), get_comment_time()); ?></span>
                    </div>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em><?php _e('Your comment is awaiting moderation.', 'degikart'); ?></em>
                        <br />
                    <?php endif; ?>
                    <?php comment_text(); ?>
                    <div class="reply">
                        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
                    </div>
                </div>
            </div>
        </li>
    <?php }
    
    







// AJAX handler to fetch file URL for a single Product ID
function fetch_file_url_by_product_id() {
    if (!isset($_POST['product_id'])) {
        wp_send_json_error(['message' => 'Product ID is missing']);
    }

    $product_id = intval($_POST['product_id']);
    $file_url = get_post_meta($product_id, 'file_url', true); // Ensure 'file_url' is set in the product meta

    if ($file_url) {
        wp_send_json_success(['file_url' => $file_url]);
    } else {
        wp_send_json_error(['message' => "No file URL found for Product ID: $product_id"]);
    }
}
add_action('wp_ajax_get_file_url', 'fetch_file_url_by_product_id');
add_action('wp_ajax_nopriv_get_file_url', 'fetch_file_url_by_product_id');
// Action hook for saving purchase history
add_action('wp_ajax_save_purchase_history', 'save_purchase_history');
add_action('wp_ajax_nopriv_save_purchase_history', 'save_purchase_history');

function save_purchase_history() {
    // Verify nonce for security
    if ( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'save_purchase_history_nonce') ) {
        wp_send_json_error('Nonce verification failed.');
        return;
    }

    // Check if required POST data is present
    if (isset($_POST['order_id']) && isset($_POST['product_ids']) && isset($_POST['total_price'])) {
        $user_id = get_current_user_id();
        if ($user_id) {
            // Sanitize and validate the input
            $order_id = sanitize_text_field($_POST['order_id']);
            $product_ids = sanitize_text_field($_POST['product_ids']);
            $total_price = floatval(sanitize_text_field($_POST['total_price']));
            $purchase_time = current_time('mysql');

            // Save purchase history to user meta
            $purchase_history = get_user_meta($user_id, '_purchase_history', true);
            if (!$purchase_history) {
                $purchase_history = [];
            }

            // Create an array of all products purchased in this order
            $product_ids_array = explode(',', $product_ids);
            $product_titles = [];
            foreach ($product_ids_array as $product_id) {
                $product_titles[] = get_the_title($product_id); // Get product titles
            }

            // Save purchase data
            $purchase_data = [
                'order_id' => $order_id,
                'product_ids' => $product_ids,
                'product_titles' => implode(', ', $product_titles),
                'total_price' => $total_price,
                'date' => $purchase_time,
            ];

            $purchase_history[] = $purchase_data;
            update_user_meta($user_id, '_purchase_history', $purchase_history);

            // Initialize total sale amount for this purchase
            $total_sale_amount = $total_price;

            // Create a single record for the seller to keep track of all products sold in this order
            $order_data = [
                'product_ids' => $product_ids,
                'product_titles' => implode(', ', $product_titles),
                'total_sale_amount' => $total_sale_amount,
                'order_id' => $order_id,
                'total_earned' => $total_sale_amount,
                'last_buyer_id' => $user_id,
                'purchase_time' => $purchase_time,
            ];

            // Get the seller ID (the user who uploaded the product)
            $seller_ids = [];
            foreach ($product_ids_array as $product_id) {
                $product_id = intval($product_id);
                $seller_id = get_post_field('post_author', $product_id);
                if (!in_array($seller_id, $seller_ids)) {
                    $seller_ids[] = $seller_id; // Collect all unique seller IDs
                }
            }

            // Update Seller Earnings (send the sale details to the seller's meta) for each seller
            foreach ($seller_ids as $seller_id) {
                // Get the seller's existing earnings data
                $seller_earnings = get_user_meta($seller_id, '_seller_earnings', true);
                if (!$seller_earnings) {
                    $seller_earnings = [];
                }

                // Add the order data as a single entry for this seller
                $seller_earnings[] = $order_data;

                // Update the seller's earnings record
                update_user_meta($seller_id, '_seller_earnings', $seller_earnings);
            }

            wp_send_json_success('Purchase history and seller earnings updated successfully');
        }
    }
    wp_send_json_error('Failed to save purchase history');
}

// Hook to handle AJAX request for applying to become an author
add_action('wp_ajax_apply_for_author', 'handle_apply_for_author');
add_action('wp_ajax_nopriv_apply_for_author', 'handle_apply_for_author'); // Allow non-logged in users to access this action

function handle_apply_for_author() {
    // Ensure data is coming from a valid request
    if (isset($_POST['name'], $_POST['email'], $_POST['why_author'], $_POST['reason'], $_POST['content_upload'])) {
        // Sanitize the form inputs
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $why_author = json_decode(stripslashes($_POST['why_author']), true);
        $reason = sanitize_textarea_field($_POST['reason']);
        $content_upload = intval($_POST['content_upload']);
        
        // Check if email is valid
        if (!is_email($email)) {
            wp_send_json_error(['message' => 'Invalid email address']);
        }

        // Get the current logged-in user
        $current_user = wp_get_current_user();

        // Check if user already has author role
        if (in_array('author', (array) $current_user->roles)) {
            wp_send_json_error(['message' => 'You are already an author!']);
        }

        // Add author role to the user
        $current_user->add_role('author');

        // Optionally store form data into the database (custom table or user meta)
        update_user_meta($current_user->ID, 'why_author', $why_author);
        update_user_meta($current_user->ID, 'reason_for_author', $reason);
        update_user_meta($current_user->ID, 'content_upload', $content_upload);

        // Send confirmation email to the user
        $subject = "Your Author Application has been Approved!";
        $message = "Hello " . $name . ",\n\nYou have been successfully granted the role of 'Author' on our website.\n\n" . 
                   "Your application reasons: " . implode(', ', $why_author) . "\nReason: " . $reason . "\n\nBest regards,\nYour Website Team";

        wp_mail($email, $subject, $message);

        // Send a success response
        wp_send_json_success(['message' => 'Your application has been successfully submitted.']);
    } else {
        wp_send_json_error(['message' => 'Missing required fields']);
    }
}









function save_custom_post_data($post_id) {
    // Prevent autosave from triggering the function
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    // List of supported post types and their respective taxonomies
    $post_types_with_taxonomies = array(
        'blogger'    => 'blogger_category',
        'wordpress'  => 'wordpress_category',
        'plugin'     => 'plugin_category',
        'ecommerce'  => 'ecommerce_category',
        'course'     => 'course_category', // Add more post types and taxonomies as needed
    );

    // Get the current post type
    $post_type = get_post_type($post_id);

    // Check if the current post type is supported
    if (array_key_exists($post_type, $post_types_with_taxonomies)) {
        // Get the taxonomy for the post type
        $taxonomy = $post_types_with_taxonomies[$post_type];

        // Ensure the custom taxonomy term is assigned
        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $category = intval($_POST['category']);
            $category_exists = term_exists($category, $taxonomy);

            if ($category_exists !== 0 && $category_exists !== null) {
                wp_set_post_terms($post_id, $category, $taxonomy);
            }
        }
    }
}

add_action('save_post', 'save_custom_post_data');




function enqueue_follow_script() {
    wp_enqueue_script('follow-script', get_template_directory_uri() . '/js/follow.js', array('jquery'), null, true);
    wp_localize_script('follow-script', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'enqueue_follow_script');

// Follow/Unfollow logic with additional checks
function degikart_handle_follow_unfollow() {
    if (is_user_logged_in() && isset($_POST['author_id'])) {
        $current_user_id = get_current_user_id();
        $author_id = intval($_POST['author_id']);

        // Check current follow status of the logged-in user for this author
        $is_following = get_user_meta($current_user_id, 'degikart_following_' . $author_id, true);

        if ($is_following) {
            // If user is already following, unfollow
            delete_user_meta($current_user_id, 'degikart_following_' . $author_id); // Unfollow

            $followers = get_user_meta($author_id, 'degikart_followers', true);
            // Remove the current user from the author's followers list
            if (is_array($followers)) {
                $followers = array_filter($followers, function($follower_id) use ($current_user_id) {
                    return $follower_id !== $current_user_id;
                });
                $followers = array_values($followers); // Re-index the array
            }

            // Ensure it's updated correctly in the database
            update_user_meta($author_id, 'degikart_followers', $followers);

            echo json_encode([
                'status' => __('unfollowed', 'degikart'),
                'count' => count($followers)
            ]);
        } else {
            // If user is not following, follow
            update_user_meta($current_user_id, 'degikart_following_' . $author_id, true); // Follow

            $followers = get_user_meta($author_id, 'degikart_followers', true);
            if (!is_array($followers)) {
                $followers = [];
            }

            $followers[] = $current_user_id; // Add current user to followers list

            // Ensure it's updated correctly in the database
            update_user_meta($author_id, 'degikart_followers', $followers);

            echo json_encode([
                'status' => __('followed', 'degikart'),
                'count' => count($followers)
            ]);
        }
    }

    die(); // End of AJAX processing
}
add_action('wp_ajax_degikart_follow_unfollow', 'degikart_handle_follow_unfollow');
add_action('wp_ajax_nopriv_degikart_follow_unfollow', 'degikart_handle_follow_unfollow');

function display_followers_list($author_id) {
    // Check if the current logged-in user is the author
    if (is_user_logged_in() && get_current_user_id() == $author_id) {
        // Get the followers of the author from the author's user meta
        $followers = get_user_meta($author_id, 'followers', true);

        // Check if there are any followers and ensure it's an array
        if ($followers && is_array($followers)) {
            echo '<h3>Followers:</h3>';
            echo '<ul>';
            
            // Loop through the followers and display their usernames
            foreach ($followers as $follower_id) {
                $follower_user = get_user_by('id', $follower_id);

                // Ensure the user object is valid
                if ($follower_user && isset($follower_user->user_login)) {
                    echo '<li>' . esc_html($follower_user->user_login) . '</li>';
                } else {
                    // If the user is invalid or missing, display a fallback message
                    echo '<li>Follower ID ' . esc_html($follower_id) . ' not found</li>';
                }
            }
            echo '</ul>';
        } else {
            echo '<p>You have no followers yet.</p>';
        }
    } else {
        echo '<p>This page is only visible to the author.</p>';
    }
}

function load_more_products() {
    // Check if the current user is authorized to view the products (same as the author)
    if (is_user_logged_in() && get_current_user_id() == $_POST['author_id']) {
        $author_id = $_POST['author_id'];

        $args = array(
            'post_type' => ['course', 'plugin', 'blogger', 'wordpress', 'ecommerce'],
            'author' => $author_id,
            'posts_per_page' => 3,  // Change according to how many products you want to load
            'post_status' => 'publish'
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) {
            ob_start();
            while ($products->have_posts()) : $products->the_post();
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                    </div>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <!-- Add other details as required -->
                </div>
                <?php
            endwhile;
            wp_reset_postdata();

            // Return the output
            echo json_encode(['success' => true, 'data' => ob_get_clean()]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No more products available.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    }

    wp_die(); // End AJAX request
}
add_action('wp_ajax_load_more_products', 'load_more_products');
add_action('wp_ajax_nopriv_load_more_products', 'load_more_products');  // For non-logged-in users (if needed)













// Function to handle the thank you page logic
function handle_thank_you_page($order_id, $product_ids_param, $total_price, $timestamp, $user_id) {
    // Initialize response array
    $response = [
        'error' => false,
        'message' => '',
        'purchased_items' => [],
        'order_id' => $order_id,
        'total_price' => $total_price,
        'product_ids_param' => $product_ids_param,
    ];

    // Check if the required parameters are present
    if (empty($order_id) || empty($product_ids_param) || empty($total_price) || empty($timestamp)) {
        $response['error'] = true;
        $response['message'] = 'There was an error processing your order. Please contact support.';
        return $response;
    }

    // Check if the timestamp is within the last hour
    $current_time = time();
    if (($current_time - $timestamp) > 3600) {
        $response['error'] = true;
        $response['message'] = 'This download link has expired. Please contact support for assistance.';
        return $response;
    }

    // Replace dashes (-) with commas (,) to handle product IDs correctly
    $product_ids_param = str_replace('-', ',', $product_ids_param);

    // Split the product IDs (now expecting them to be separated by commas)
    $product_ids = explode(',', $product_ids_param); // Split by commas

    // Initialize an array to store the purchased items with file URLs and prices
    $purchased_items = [];
    $is_valid_purchase = false;

    // Function to securely fetch file URL
    function get_secure_file_url($product_id) {
        // Fetch the 'file_url' custom field
        $file_url = get_post_meta($product_id, 'file_url', true);

        // Perform a check to ensure the file URL is valid
        if (!empty($file_url) && filter_var($file_url, FILTER_VALIDATE_URL)) {
            return $file_url;
        } else {
            return ''; // Return empty string if file URL is not valid
        }
    }

    // Function to check the purchase history
    function check_purchase_history($order_id, $user_id, $product_ids) {
        $purchase_history = get_user_meta($user_id, '_purchase_history', true);

        if ($purchase_history) {
            foreach ($purchase_history as $purchase) {
                // Ensure the required keys exist in the array
                if (isset($purchase['order_id']) && $purchase['order_id'] === $order_id) {
                    // Replace dash with comma for product IDs in the history
                    $purchased_product_ids = str_replace('-', ',', $purchase['product_ids']); // Replace dash with comma
                    $purchased_product_ids = explode(',', $purchased_product_ids); // Split by commas
                    $purchased_product_ids = array_map('trim', $purchased_product_ids); // Clean up any spaces

                    // Check if the purchased product IDs match
                    if (empty(array_diff($product_ids, $purchased_product_ids))) {
                        return true; // All product IDs match
                    }
                }
            }
        }
        return false;
    }

    // Check the purchase history
    $is_valid_purchase = check_purchase_history($order_id, $user_id, $product_ids);

    if ($is_valid_purchase) {
        // Loop through each product ID to fetch product data and file URL
        foreach ($product_ids as $product_id) {
            // Ensure that the product ID is valid
            if (is_numeric($product_id)) {
                // Get the file URL securely using the function
                $file_url = get_secure_file_url($product_id);

                // Fetch product price
                $price = get_post_meta($product_id, '_price', true); // Assuming '_price' is stored for each product

                // Fetch product name
                $product_name = get_the_title($product_id);

                // Check if the product data is correctly fetched
                if (!$file_url) {
                    $file_url = ''; // Provide an empty string if no file URL
                }
                if (!$price) {
                    $price = 'Price not available'; // Fallback message if price is not set
                }

                // Add the product data to the array
                $purchased_items[] = [
                    'id' => $product_id,
                    'file_url' => $file_url, // Use the fetched file URL
                    'name' => $product_name, // Fetch product name
                    'price' => $price, // Add the product price
                ];
            } else {
                // Log an error if the product ID is invalid
                error_log('Invalid product ID: ' . $product_id);
            }
        }
    } else {
        $response['error'] = true;
        $response['message'] = 'The purchase details do not match our records. Please contact support.';
    }

    // Add purchased items to response
    $response['purchased_items'] = $purchased_items;

    return $response;
}



 




function degikart_rating() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/rating.php';
}

function degikart_author_profile() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/author_profile.php';
}

function degikart_sales_bar() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/sales-bar.php';
}

function degikart_post_date() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/post-date.php';
}

function degikart_popup_notification() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/popup-notification.php';
}

function degikart_custom_card() {
    // Include the HTML file for the popup notification
    include get_template_directory() . '/includes/custom-card.php';
}

function degikart_enqueue_styles() {
    wp_enqueue_style('thank-you-page', get_template_directory_uri() . '/assets/thank-you-page.css');
    wp_enqueue_style('author_profile', get_template_directory_uri() . '/assets/author_profile.css');
    wp_enqueue_style('sales-bar', get_template_directory_uri() . '/assets/sales-bar.css');
}
add_action('wp_enqueue_scripts', 'degikart_enqueue_styles');


function handle_comment_submission() {
    if (isset($_POST['comment']) && isset($_POST['rating'])) {
        // Get current user data if logged in
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $author = $current_user->display_name;
            $email = $current_user->user_email;
            $avatar = get_avatar_url($current_user->ID);
        } else {
            // If not logged in, get data from form
            $author = sanitize_text_field($_POST['author']);
            $email = sanitize_email($_POST['email']);
            $avatar = ''; // No avatar if not logged in
        }

        $comment = sanitize_textarea_field($_POST['comment']);
        $rating = intval($_POST['rating']);
        $post_id = intval($_POST['post_id']);
        
        // Prepare comment data
        $comment_data = array(
            'comment_post_ID' => $post_id,
            'comment_author' => $author,
            'comment_author_email' => $email,
            'comment_content' => $comment,
            'comment_approved' => 1, // Automatically approve the comment
        );
        
        // Insert comment
        $comment_id = wp_insert_comment($comment_data);

        // Optionally, save rating as comment meta
        if ($comment_id && $rating) {
            add_comment_meta($comment_id, 'rating', $rating);
        }

        // Prepare response data
        $response = array(
            'success' => true,
            'author' => $author,
            'comment' => $comment,
            'rating' => $rating,
            'avatar' => $avatar,
            'date' => get_comment_date('', $comment_id),
        );

        // Return response
        wp_send_json($response);
    } else {
        wp_send_json(array('success' => false));
    }
}

add_action('wp_ajax_handle_comment_submission', 'handle_comment_submission'); // If logged in
add_action('wp_ajax_nopriv_handle_comment_submission', 'handle_comment_submission'); // If not logged in
















 








// Handle the AJAX request to add/remove favorites
function toggle_favorite() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'You need to be logged in to perform this action.'));
    }

    $user_id = $_POST['user_id'];
    $post_id = $_POST['post_id'];
    $action_type = $_POST['action_type'];

    // Get current favorites of the user
    $favorites = get_user_meta($user_id, 'favorites', true);
    if (!is_array($favorites)) {
        $favorites = array();
    }

    if ($action_type === 'add') {
        // Add post to favorites
        if (!in_array($post_id, $favorites)) {
            $favorites[] = $post_id;
        }
    } elseif ($action_type === 'remove') {
        // Remove post from favorites
        $favorites = array_diff($favorites, array($post_id));
    }

    // Update the user meta with the new favorites list
    update_user_meta($user_id, 'favorites', $favorites);

    wp_send_json_success();
}
add_action('wp_ajax_toggle_favorite', 'toggle_favorite');



function custom_breadcrumbs() {
    // Settings
    $separator = ' / ';  // Breadcrumb separator (changed from ' &gt; ' to ' / ')
    $home = 'Home';      // Name for the home link
    $post_type_name = '';  // Custom post type label (for custom post types)

    // Get the global $post object
    global $post;

    if (!is_home() && !is_front_page()) {
        echo '<nav class="breadcrumbs">';

        // Link to the homepage
        echo '<a href="' . get_home_url() . '">' . $home . '</a>' . $separator;

        // Check if it's a custom post type
        if (is_singular() && !is_page()) {
            // If it's a custom post type, get its name
            $post_type = get_post_type($post);
            $post_type_obj = get_post_type_object($post_type);
            $post_type_name = $post_type_obj->labels->singular_name;

            // Display the custom post type archive link first
            echo '<a href="' . get_post_type_archive_link($post_type) . '">' . $post_type_name . '</a>' . $separator;

            // Output the custom post type category URL if it exists
            if ($taxonomy = get_object_taxonomies($post_type)) {
                $taxonomy = $taxonomy[0];  // Get the first taxonomy (e.g., category, or custom taxonomy)
                $terms = get_the_terms($post->ID, $taxonomy);

                if ($terms && !is_wp_error($terms)) {
                    $term = array_shift($terms);  // Get the first term
                    echo '<a href="' . get_term_link($term) . '">' . $term->name . '</a>' . $separator;
                }
            }

            // Display the post title
            echo get_the_title();
        }

        // For single posts, show category and post title
        elseif (is_category() || is_single()) {
            if (is_single()) {
                $categories = get_the_category();
                if ($categories) {
                    $category = $categories[0];
                    echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>' . $separator;
                }
                // Display the post title
                echo get_the_title();
            } else {
                // If it's a category archive, display the category name
                single_cat_title();
            }
        }
        // Check if it's a page
        elseif (is_page()) {
            echo get_the_title();
        }

        echo '</nav>';
    }
}

// Register custom menu page under Degikart menu
function degikart_settings_page() {
    // Add Degikart menu page (Main Parent menu)
    add_menu_page(
        'Degikart Settings', // Page title
        'Degikart', // Menu title
        'manage_options', // Capability required to access this menu
        'degikart-settings', // Menu slug
        'degikart_main_settings', // Callback function for the page content (renamed)
        'dashicons-admin-generic', // Icon for the menu
        80 // Position of the menu item
    );

    // Add Author Applications as a sub-menu under Degikart menu
    add_submenu_page(
        'degikart-settings', // Parent menu slug (same as the main menu)
        'Author Applications', // Page title
        'Author Applications', // Menu title
        'manage_options', // Capability required to access this menu
        'author_applications', // Sub-menu slug
        'render_author_application_page' // Callback function to display the page content
    );

    // Add OAuth Settings as a sub-menu under Degikart menu
    add_submenu_page(
        'degikart-settings', // Parent menu slug
        'OAuth Settings', // Page title
        'OAuth Settings', // Menu title
        'manage_options', // Capability required to access this menu
        'oauth-settings', // Sub-menu slug
        'render_oauth_settings_page' // Callback function to display OAuth settings page content
    );

    // Add Payment Details as a sub-menu under Degikart menu
    add_submenu_page(
        'degikart-settings', // Parent menu slug
        'Payment Details', // Page title
        'Payment Details', // Menu title
        'manage_options', // Capability required to access this menu
        'payment-details', // Sub-menu slug
        'render_payment_details_page' // Callback function to display Payment Details page content
    );
}

// Hook into the admin menu action
add_action('admin_menu', 'degikart_settings_page');

// Callback function for the main Degikart settings page (Welcome message)
function degikart_main_settings() {
    // Display the welcome message
    echo '<div class="wrap">';
    echo '<h1>Welcome to Degikart Settings</h1>';
    echo '<p>Here you can manage all the settings related to the Degikart platform, including OAuth settings, author applications, and payment details.</p>';
    echo '</div>';
}

// Render OAuth Settings Page Content
function render_oauth_settings_page() {
    ?>
    <div class="wrap">
        <h3>OAuth Settings</h3>
        <form method="post" action="options.php">
            <?php
            // Output the settings fields and sections for OAuth settings
            settings_fields('degikart_oauth_settings_group'); // Settings group
            do_settings_sections('degikart-settings'); // Display settings sections
            submit_button(); // Submit button
            ?>
        </form>
    </div>
    <?php
}

// Callback function to render Payment Details page
function render_payment_details_page() {
    ?>
    <div class="wrap">
        <h1>Payment Details</h1>

        <?php 
        // Assuming display_payment_details is a function to show the payment info
        display_payment_details(); 
        ?>

    </div>  
    <?php
}

function display_payment_details() {
    // Ensure the current user has the necessary permissions
      if (!current_user_can('manage_options')) {
          wp_die('You do not have sufficient permissions to view this page.');
      }
  
      // Get all users sorted by their ID in ascending order
      $users = get_users(array(
          'orderby' => 'ID',   // Order users by ID
          'order'   => 'ASC',  // Ascending order
      ));
      
      echo '<div class="wrap">';
      echo '<h1>Payment Details</h1>';
      
      if ($users) {
          // Start displaying a table with user payment details
          echo '<div class="payment-table-container">';
          echo '<table class="wp-list-table widefat fixed striped">';
          echo '<thead><tr>';
          echo '<th>#</th>';  // List number (serial number)
          echo '<th>User</th>';
          echo '<th>Email</th>';
          echo '<th>First Name</th>';  // First Name Column
          echo '<th>Last Name</th>';   // Last Name Column
          echo '<th>Date of Birth</th>'; // Date of Birth Column
          echo '<th>Country</th>';  // Country Column
          echo '<th>Address</th>';  // Address Column
          echo '<th>City</th>';     // City Column
          echo '<th>Postal Code</th>'; // Postal Code Column
          echo '<th>PayPal Email</th>';
          echo '<th>Bank Account Country</th>';
          echo '<th>Bank Account Number</th>';
          echo '<th>Account Holder Name</th>';
          echo '<th>Additional Info</th>';  // New additional column
          echo '</tr></thead>';
          echo '<tbody>';
  
          // Loop through each user and display the relevant data
          $counter = 1;  // Counter for serial number
          foreach ($users as $user) {
              $user_id = $user->ID;
  
              // Get the user meta for payment details
              $paypal_email = get_user_meta($user_id, 'paypal_email', true);
              $bank_account_country = get_user_meta($user_id, 'bank_country', true);
              $bank_account_number = get_user_meta($user_id, 'account_number', true);
              $account_holder = get_user_meta($user_id, 'account_holder', true);
              $payout_method = get_user_meta($user_id, 'payout_method', true);
  
              // Get additional user details
              $first_name = get_user_meta($user_id, 'first_name', true);
              $last_name = get_user_meta($user_id, 'last_name', true);
              $dob = get_user_meta($user_id, 'dob', true);
              $country = get_user_meta($user_id, 'country', true);
              $address = get_user_meta($user_id, 'address', true);
              $city = get_user_meta($user_id, 'city', true);
              $postal_code = get_user_meta($user_id, 'postal_code', true);
  
              // Initialize variables to hold PayPal and Bank Account information
              $paypal_info = '';
              $bank_country = '';
              $bank_account_number_info = '';
              $account_holder_name = '';
              $additional_info = '';  // You can add extra details here
  
              // Set PayPal Email if it exists
              if ($paypal_email) {
                  $paypal_info = esc_html($paypal_email);
              } else {
                  $paypal_info = 'No PayPal info';
              }
  
              // Set Bank Account Info if Bank Transfer is selected
              if ($payout_method === 'bank_transfer' && $bank_account_country && $bank_account_number && $account_holder) {
                  $bank_country = esc_html($bank_account_country);
                  $bank_account_number_info = esc_html($bank_account_number);
                  $account_holder_name = esc_html($account_holder);
              } else {
                  $bank_country = 'N/A';
                  $bank_account_number_info = 'N/A';
                  $account_holder_name = 'N/A';
              }
  
              // Add some additional info (for demonstration, we just use user ID here, but you can customize this)
              $additional_info = 'User ID: ' . $user_id;
  
              // Output the user details in a table row
              echo '<tr>';
              echo '<td>' . $counter . '</td>';  // Display serial number
              echo '<td>' . esc_html($user->display_name) . '</td>';
              echo '<td>' . esc_html($user->user_email) . '</td>';  // Display the user's email
              echo '<td>' . esc_html($first_name) . '</td>';
              echo '<td>' . esc_html($last_name) . '</td>';
              echo '<td>' . esc_html($dob) . '</td>';
              echo '<td>' . esc_html($country) . '</td>';
              echo '<td>' . esc_html($address) . '</td>';
              echo '<td>' . esc_html($city) . '</td>';
              echo '<td>' . esc_html($postal_code) . '</td>';
              echo '<td>' . $paypal_info . '</td>';
              echo '<td>' . $bank_country . '</td>';
              echo '<td>' . $bank_account_number_info . '</td>';
              echo '<td>' . $account_holder_name . '</td>';
              echo '<td>' . $additional_info . '</td>';  // Additional info column
              echo '</tr>';
  
              // Increment counter for serial number
              $counter++;
          }
  
          echo '</tbody>';
          echo '</table>';
          echo '</div>';  // Close the scroller container
      } else {
          echo '<p>No users found.</p>';
      }
      
      echo '</div>';
  }
  
 // Add custom styles to the admin page for better UI
 function custom_admin_styles() {
    echo '<style>
        .wp-list-table th, .wp-list-table td {
            padding: 10px;
            text-align: left;
        }
        .wp-list-table th {
            background-color: #f1f1f1;
        }
        .wp-list-table {
            width: 100%;
            border-collapse: collapse;
           
        }
        .wp-list-table td {
            border-top: 1px solid #ddd;
        }
        .wp-list-table th, .wp-list-table td {
            word-wrap: break-word;
            max-width: 150px;
        }
        .wp-list-table {
            table-layout: auto;
        }
        .wp-list-table td {
            max-width: 200px;
        }
        .payment-table-container {
            overflow-x: auto !important; /* Force horizontal scroll */
            width: 100%;
            margin-top: 20px;
        }
        .payment-table-container table {
            min-width: 1200px; /* Ensure table is wider than container */
        }
    </style>';
}
add_action('admin_head', 'custom_admin_styles');

// Register settings and sections for OAuth
function degikart_oauth_settings_init() {
    register_setting('degikart_oauth_settings_group', 'degikart_google_client_id');
    register_setting('degikart_oauth_settings_group', 'degikart_facebook_app_id');
    register_setting('degikart_oauth_settings_group', 'degikart_apple_service_id');
    register_setting('degikart_oauth_settings_group', 'degikart_google_redirect_uri');
    register_setting('degikart_oauth_settings_group', 'degikart_facebook_redirect_uri');
    register_setting('degikart_oauth_settings_group', 'degikart_apple_redirect_uri');

    // Add sections and fields for Google, Facebook, and Apple OAuth
    add_settings_section('degikart_google_section', 'Google OAuth Settings', null, 'degikart-settings');
    add_settings_field('degikart_google_client_id_field', 'Google Client ID', 'degikart_google_client_id_field', 'degikart-settings', 'degikart_google_section');
    add_settings_field('degikart_google_redirect_uri_field', 'Google Redirect URI', 'degikart_google_redirect_uri_field', 'degikart-settings', 'degikart_google_section');

    add_settings_section('degikart_facebook_section', 'Facebook OAuth Settings', null, 'degikart-settings');
    add_settings_field('degikart_facebook_app_id_field', 'Facebook App ID', 'degikart_facebook_app_id_field', 'degikart-settings', 'degikart_facebook_section');
    add_settings_field('degikart_facebook_redirect_uri_field', 'Facebook Redirect URI', 'degikart_facebook_redirect_uri_field', 'degikart-settings', 'degikart_facebook_section');

    add_settings_section('degikart_apple_section', 'Apple OAuth Settings', null, 'degikart-settings');
    add_settings_field('degikart_apple_service_id_field', 'Apple Service ID', 'degikart_apple_service_id_field', 'degikart-settings', 'degikart_apple_section');
    add_settings_field('degikart_apple_redirect_uri_field', 'Apple Redirect URI', 'degikart_apple_redirect_uri_field', 'degikart-settings', 'degikart_apple_section');
 // Payment Details Sections and Fields
 register_setting('degikart_payment_settings_group', 'degikart_payment_gateway');
 add_settings_section('degikart_payment_section', 'Payment Gateway Settings', null, 'degikart-payment-settings');
 add_settings_field('degikart_payment_gateway_field', 'Payment Gateway', 'degikart_payment_gateway_field', 'degikart-payment-settings', 'degikart_payment_section');
}
add_action('admin_init', 'degikart_oauth_settings_init');

// Define the fields for Google OAuth
function degikart_google_client_id_field() {
    $google_client_id = get_option('degikart_google_client_id');
    echo '<input type="text" name="degikart_google_client_id" value="' . esc_attr($google_client_id) . '" class="regular-text" />';
}

function degikart_google_redirect_uri_field() {
    $google_redirect_uri = get_option('degikart_google_redirect_uri');
    echo '<input type="text" name="degikart_google_redirect_uri" value="' . esc_attr($google_redirect_uri) . '" class="regular-text" />';
}

// Define the fields for Facebook OAuth
function degikart_facebook_app_id_field() {
    $facebook_app_id = get_option('degikart_facebook_app_id');
    echo '<input type="text" name="degikart_facebook_app_id" value="' . esc_attr($facebook_app_id) . '" class="regular-text" />';
}

function degikart_facebook_redirect_uri_field() {
    $facebook_redirect_uri = get_option('degikart_facebook_redirect_uri');
    echo '<input type="text" name="degikart_facebook_redirect_uri" value="' . esc_attr($facebook_redirect_uri) . '" class="regular-text" />';
}

// Define the fields for Apple OAuth
function degikart_apple_service_id_field() {
    $apple_service_id = get_option('degikart_apple_service_id');
    echo '<input type="text" name="degikart_apple_service_id" value="' . esc_attr($apple_service_id) . '" class="regular-text" />';
}

function degikart_apple_redirect_uri_field() {
    $apple_redirect_uri = get_option('degikart_apple_redirect_uri');
    echo '<input type="text" name="degikart_apple_redirect_uri" value="' . esc_attr($apple_redirect_uri) . '" class="regular-text" />';
}


 // Render the author application page in the admin panel
function render_author_application_page() {
    // Get the selected status from the URL, default to 'pending'
    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'pending';

    // Set up the query to fetch users based on status filter
    $args = [
        'meta_key' => 'author_application_status',
    ];

    if ($status_filter !== 'all') {
        $args['meta_value'] = $status_filter; // Filter by the selected status (pending, approved, rejected)
    }

    // Get all users with the specified application status
    $users = get_users($args);

    ?>
    <div class="wrap">
        <h1>Author Applications</h1>

        <!-- Filter by status -->
        <form method="get" action="">
            <input type="hidden" name="page" value="author_applications" />
            <select name="status_filter" onchange="this.form.submit()">
                <option value="all" <?php selected($status_filter, 'all'); ?>>All Applications</option>
                <option value="pending" <?php selected($status_filter, 'pending'); ?>>Pending</option>
                <option value="approved" <?php selected($status_filter, 'approved'); ?>>Approved</option>
                <option value="rejected" <?php selected($status_filter, 'rejected'); ?>>Rejected</option>
            </select>
        </form>

        <!-- Add styles for horizontal scrolling for the table rows -->
        <style>
            .author-application-table {
                width: 100%;
                border-collapse: collapse;
                overflow: hidden;
            }

            /* Make the table body scrollable */
            .scrollable-rows {
                display: block;
                max-height: 400px; /* Set the max height for the table body */
                overflow-x: auto;  /* Enable horizontal scrolling */
                overflow-y: auto;  /* Enable vertical scrolling */
            }

            /* Make the header fixed */
            .author-application-table thead, .author-application-table th {
                position: sticky;
                top: 0;
                background-color: #fff; /* Ensure header is readable */
                z-index: 1;
            }

            .author-application-table th, .author-application-table td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: left;
            }

            /* Prevent text wrapping in table cells */
            .author-application-table td, .author-application-table th {
                white-space: nowrap;
            }

            /* Style buttons inside the table */
            .author-application-table td form button {
                margin: 5px 0;
            }
        </style>

        <div class="scrollable-rows">
            <table class="author-application-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Reason</th>
                        <th>Reasons Selected</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo esc_html($user->user_login); ?> (<?php echo esc_html($user->user_email); ?>)</td>
                            <td><?php echo esc_html(get_user_meta($user->ID, 'author_application_reason', true)); ?></td>
                            <td><?php echo esc_html(get_user_meta($user->ID, 'author_application_checkboxes', true)); ?></td>
                            <td>
                                <?php 
                                $status = get_user_meta($user->ID, 'author_application_status', true);
                                echo esc_html(ucfirst($status)); // Display the status (pending, approved, rejected)
                                ?>
                            </td>
                            <td>
                                <form method="post" action="admin-post.php">
                                    <?php wp_nonce_field('approve_reject_author', 'approve_reject_nonce'); ?>
                                    <input type="hidden" name="action" value="handle_author_application">
                                    <input type="hidden" name="user_id" value="<?php echo esc_attr($user->ID); ?>">

                                    <?php 
                                    $status = get_user_meta($user->ID, 'author_application_status', true);
                                    if ($status === 'pending') : ?>
                                        <button type="submit" name="approve_author" class="button button-primary">Approve</button>
                                        <button type="submit" name="reject_author" class="button button-secondary">Reject</button>
                                    <?php elseif ($status === 'approved') : ?>
                                        <span class="status-approved">Approved</span>
                                        <button type="submit" name="reject_author" class="button button-secondary">Reject</button>
                                    <?php elseif ($status === 'rejected') : ?>
                                        <span class="status-rejected">Rejected</span>
                                        <button type="submit" name="approve_author" class="button button-primary">Approve Again</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}

// Function to notify user of approval or rejection
function notify_user_of_decision($user_id, $status) {
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return;
    }

    $subject = "Your Author Application Status";
    
    // Set the message based on the status
    if ($status === 'approved') {
        $message = "Congratulations! Your application to become an author has been approved.";
    } elseif ($status === 'rejected') {
        $message = "We're sorry, your application to become an author has been rejected.";
    } else {
        return;
    }

    // Send the email
    wp_mail($user->user_email, $subject, $message);
}// Function to approve or reject the author application
function approve_or_reject_author_application($user_id, $action) {
    // Get user data to ensure application data is preserved
    $reason = get_user_meta($user_id, 'author_application_reason', true);
    $checkboxes = get_user_meta($user_id, 'author_application_checkboxes', true);

    // Ensure status is updated accordingly
    if ($action == 'approve') {
        // Update user status to 'approved' and role to 'author'
        update_user_meta($user_id, 'author_application_status', 'approved');
        
        // Check if the user is already an author
        $user = get_user_by('ID', $user_id);
        if ($user && !in_array('author', (array) $user->roles)) {
            // Only change role to 'author' if the user doesn't already have the 'author' role
            $user->remove_role('subscriber'); // Remove subscriber role if present
            $user->add_role('author');         // Add author role
        }
    } elseif ($action == 'reject') {
        // Update the user status to 'rejected'
        update_user_meta($user_id, 'author_application_status', 'rejected');

        // If user was approved, remove 'author' role and assign 'subscriber' role
        $user = get_user_by('ID', $user_id);
        if ($user && in_array('author', (array) $user->roles)) {
            $user->remove_role('author'); // Remove 'author' role
            $user->add_role('subscriber'); // Assign 'subscriber' role (or other default role)
        }
    } else {
        return; // If action is neither 'approve' nor 'reject', do nothing
    }
}

// Handle approval/rejection logic
function handle_author_approval_rejection() {
    if (isset($_POST['approve_author']) || isset($_POST['reject_author'])) {
        // Verify the nonce for security
        if (!isset($_POST['approve_reject_nonce']) || !wp_verify_nonce($_POST['approve_reject_nonce'], 'approve_reject_author')) {
            return;
        }

        $user_id = $_POST['user_id'];
        
        if (isset($_POST['approve_author'])) {
            // Approve the user: change their role to 'author' and send approval email
            approve_or_reject_author_application($user_id, 'approve');
        } elseif (isset($_POST['reject_author'])) {
            // Reject the user: update their status to 'rejected'
            approve_or_reject_author_application($user_id, 'reject');
        }

        // Redirect back to the author applications page
        wp_redirect(admin_url('admin.php?page=author_applications'));
        exit;
    }
}
add_action('admin_post_handle_author_application', 'handle_author_approval_rejection');
