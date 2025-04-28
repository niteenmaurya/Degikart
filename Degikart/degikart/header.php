<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='stylesheet' type='text/css' href='<?php echo esc_url(get_template_directory_uri()); ?>/style.css'>
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

        <header id="full-header">
            <div class="site-header" id="site-width" >
                <div class="inside-header">
                    <nav class="mobile-menu">
                        <button id="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <svg id="menu-icon" width="20" height="20" viewBox="0 0 20 15" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" y1="5" x2="17" y2="5"/>
                                <line x1="3" y1="10" x2="17" y2="10"/>
                                <line x1="3" y1="15" x2="17" y2="15"/>
                            </svg>
                            <span class="screen-reader-text"><?php esc_html_e('open', 'degikart'); ?></span>
                        </button>
                    </nav>
                    <div class="site-branding-container">
                        <?php
                        $logo_width = get_theme_mod('degikart_logo_width', 150); // Default width
                        if (function_exists('the_custom_logo') && has_custom_logo()) {
                            // Display the custom logo
                            the_custom_logo();
                        } else {
                            $logo_url = get_theme_mod('degikart_logo');
                            // Check if the logo URL exists
                            if ($logo_url) {
                                echo '<a href="' . esc_url(home_url()) . '">';
                                echo '<img src="' . esc_url($logo_url) . '" class="site-logo" style="width: ' . esc_attr($logo_width) . 'px;">';
                                echo '</a>';
                            } 
                        }
                        ?>
                        
                        <div class="site-branding">
                            <?php 
                                if (display_header_text() == true) {
                                    echo '<p class="main-title"><a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a></p>';
                                    echo '<p class="site-description"><a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('description')) . '</a></p>';                            
                                }
                            ?>
                            </div>
                        </div>
                    </div>

                    <nav id="mobile-nav" class="mobile-nav">
                        <div id="close-mobile-nav">
                            <svg width="24" height="24" viewBox="5 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/>
                                <line x1="6" y1="6" x2=" 18" y2="18"/>
                            </svg>
                        </div>

                        <div class="mobile-navigation">
                            <?php
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'primary-menu',
                                        'menu_id'        => 'nav',
                                    )
                                );
                            ?>  
                        </div>
                    </nav>
                    <div class="header-icons" role="navigation">
                        <a href="<?php echo esc_url( home_url('/cart/') ); ?>" class="cart-icon" aria-label="<?php esc_attr_e('Cart', 'degikart'); ?>">
                            <span class="cart-item-count" id="cart-item-count">0</span>   
                            <svg width="30" height="30" viewBox="0 0 80 70" role="img" aria-labelledby="cart-title">
                                <title id="cart-title"><?php esc_html_e('Shopping Cart', 'degikart'); ?></title>
                                    <path fill="#ffffff" d="M26.029 58.156c-1.683 0-3.047 1.334-3.047 2.979 0 1.646 1.364 2.979 3.047 2.979s3.047-1.333 3.047-2.979c0-1.645-1.364-2.979-3.047-2.979zm17.795 0c-1.682 0-3.046 1.334-3.046 2.979 0 1.646 1.364 2.979 3.046 2.979 1.683 0 3.047-1.333 3.047-2.979 0-1.645-1.364-2.979-3.047-2.979zM22.515 26.997l5.416 14.5h21.793l6.189-14.5H22.515z"/>
                                    <path fill="#ffffff" d="m58.753 13-9.67 28.181H23.85l-6.527-17.968h29.111v-2.27H14.036l7.722 21.258-6.281 10.643h35.794v-2.271H19.494l4.207-7.125h27.051l9.67-28.18H71V13H58.753zm-33.4 41.861c-3.134.002-5.674 2.484-5.676 5.548.002 3.065 2.542 5.548 5.676 5.549 3.133-.002 5.672-2.485 5.672-5.549 0-3.064-2.539-5.546-5.672-5.548zm0 8.827c-1.853-.003-3.35-1.468-3.353-3.279.003-1.81 1.5-3.274 3.353-3.277 1.849.003 3.349 1.467 3.352 3.277-.003 1.812-1.503 3.276-3.352 3.279zm17.794-8.827c-3.134.002-5.673 2.484-5.674 5.548.001 3.065 2.54 5.548 5.674 5.549 3.134-.002 5.672-2.485 5.674-5.549-.002-3.064-2.54-5.546-5.674-5.548zm0 8.827c-1.851-.003-3.349-1.468-3.352-3.279.003-1.81 1.501-3.274 3.352-3.277 1.851.003 3.35 1.467 3.353 3.277-.003 1.812-1.502 3.276-3.353 3.279z"/>
                            </svg>
                        </a>
                        <?php display_login_or_profile_icon(); ?>
                        <?php if (is_user_logged_in()) : ?>
                            <div class="gverfa-menu-container">
                                <!-- More Options Button (three dots) -->
                                <svg id="gverfa-dots-icon" width="20" height="20" viewBox="0 0 24 24" role="img" aria-labelledby="gverfa-dots-title" class="gverfa-dots-icon">
                                    <title id="gverfa-dots-title">More Options</title>
                                    <g fill="none" stroke="#ffffff" stroke-width="2">
                                        <circle cx="12" cy="4" r="1"/>
                                        <circle cx="12" cy="12" r="1"/>
                                        <circle cx="12" cy="20" r="1"/>
                                    </g>
                                </svg>
                                <!-- Toggle Menu (hidden by default) -->
                                <div id="gverfa-menu" class="gverfa-menu">
                                    <ul>
                                        <!-- Heading with no link -->
                                        <div class="gverfa-menu-heading">Hi, <?php echo wp_get_current_user()->user_login; ?></div>
                                        <!-- Menu items with dynamic links -->
                                        <li>
                                            <a href="<?php echo esc_url( home_url( '/profile-page' ) ); ?>">Profile</a>
                                        </li>
                                        <?php
                                            // Get the current user ID
                                            $user_id = get_current_user_id();

                                            // Get the user's favorite posts
                                            $favorites = get_user_meta($user_id, 'favorites', true);
                                            $favorite_count = is_array($favorites) ? count($favorites) : 0;
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url(home_url('/dashboard/?tab=favourite')); ?>" class="favorites-link">
                                                <span class="favorite-text">Favorites&nbsp;</span>
                                            (<span class="favorite-count">
                                                    <?php echo $favorite_count > 0 ? $favorite_count : '0'; ?>
                                                </span>)
                                            </a>
                                        </li>
                                        <li><a href="<?php echo esc_url( home_url( '/dashboard/?tab=downloads' ) ); ?>">Downloads</a></li>
                                        <?php
                                            $current_user = wp_get_current_user(); // Initialize the $current_user variable
                                        ?>

                                        <!-- Link to Become an Author -->
                                        <?php if (!in_array('author', (array) $current_user->roles)): ?>
                                            <li><a href="<?php echo esc_url( home_url( '/become-an-author' ) ); ?>">Become an Author</a></li>
                                        <?php endif; ?>

                                        <?php
                                        if ( is_user_logged_in() ) {
                                            $current_user = wp_get_current_user();
                                            
                                            // Check if the current user is an author
                                            if ( in_array( 'author', (array) $current_user->roles ) ) :
                                        ?>
                                        <!-- Author Settings Heading -->
                                        <div class="gverfa-menu-heading">Author Settings</div>
                                        
                                        <!-- Author Dashboard Link -->
                                        <li><a href="<?php echo esc_url( home_url( '/dashboard' ) ); ?>">Dashboard</a></li>
                                        
                                        <!-- Author Upload Link -->
                                        <li><a href="<?php echo esc_url( home_url( '/dashboard/?tab=upload' ) ); ?>">Upload</a></li>
                                        
                                        <!-- Author Earnings Link -->
                                        <li><a href="<?php echo esc_url( home_url( '/dashboard/?tab=earnings' ) ); ?>">Earnings</a></li>
                                        
                                        <?php endif; } ?>

                                        <!-- Heading with Sign Out link -->
                                        <div class="gverfa-menu-heading">
                                            <a href="<?php echo esc_url( wp_logout_url() ); ?>" class="gverfa-sign-out-link" id="gverfa-sign-out-link">
                                                <span class="gverfa-sign-out-text">Sign Out</span>
                                                <svg class="gverfa-sign-out-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M13 3H5C4.44772 3 4 3.44772 4 4V20C4 20.5523 4.44772 21 5 21H13C13.5523 21 14 20.5523 16 20V4C14 3.44772 13.5523 3 13 3ZM13 4V20H5V4H13ZM15 12H19C19.5523 12 20 11.5523 20 11C20 10.4477 19.5523 10 19 10H15V12Z" fill="#ffffff"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>  
        <div class="desktop-navigation" >
            <nav class="desktop-nav" id="site-width">
                <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary-menu',
                            'menu_id'        => 'desktop-nav',
                        )
                    );
                ?>
            </nav>
        </div>
        <?php wp_footer(); ?>
    </body> 
</html>
