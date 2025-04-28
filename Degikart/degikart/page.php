<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package degikart
 */

get_header();
the_post();
?>

<main class="site-content" id="site-width">
    <div class="main-content">

        <div class="page-status">
            <h1><?php the_title(); ?></h1>
        </div>

        <?php 
        the_excerpt(); 
        the_post_thumbnail('large'); 
        ?>

        <div class="about-content">

            <?php the_content(); 
            $imagepath = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
            if ($imagepath) : 
            ?>

            <img src="<?php echo esc_url($imagepath[0]); ?>" alt="<?php the_title_attribute(); ?>" width="large"/>
            
            <?php endif;
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'degikart'),
                'after' => '</div>',
            ));
            ?>

        </div>
        
    </div>

    <aside id="sidebar" class="sidebar-section">
        <?php get_sidebar(); ?>
    </aside>

</main>

<?php get_footer(); ?>
