<?php
/**
 * The template for displaying all single posts
 *
 * @package degikart
 */
get_header();
the_post();
?>
<div class="site-content" id="site-width" tabindex="0">
    <main class="main-content" id="primary-content">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php
                // Get the customizer setting value
                $show_featured_image = get_theme_mod('show_featured_image', 'yes'); // Default to 'yes' if not set

                // Only show the featured image if the setting is 'yes' and the post has a thumbnail
                if ($show_featured_image === 'yes' && has_post_thumbnail()) :
                    $imagepath = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                    $image_url = $imagepath ? $imagepath[0] : false;
                    if ($image_url) :
                ?>
                
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
                <?php endif; endif; ?>
                
                <h1 class="single-title"><?php the_title(); ?></h1>
                
                <div class="entry-meta">
                    <span class="byline">
                        <span class="author vcard" itemprop="author" itemtype="https://schema.org/Person" itemscope="">
                            <a class="url fn n" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="<?php printf(__('View all posts by %s', 'degikart'), get_the_author()); ?>" rel="author" itemprop="url">
                                <?php degikart_display_author_profile_picture(get_the_author_meta('ID')); ?>
                                <span class="author-name" itemprop="name"><?php the_author(); ?></span>
                            </a>
                        </span>
                    </span>
                    <span class="posted-on"><?php echo get_the_date(); ?></span>
                </div>

            </header>

            <div class="entry-content" itemprop="text">
                <?php the_content(); ?>
                <?php
                $args = array(
                    'before'           => '<p>' . __( 'Pages:', 'degikart' ) . '</p>',
                    'after'            => '</p>',
                    'link_before'      => '',
                    'link_after'       => '',
                    'next_or_number'   => 'number',
                    'separator'        => ' ',
                    'nextpagelink'     => __( 'Next page', 'degikart' ),
                    'previouspagelink' => __( 'Previous page', 'degikart' ),
                    'pagelink'         => '%',
                    'echo'             => 1
                );
                wp_link_pages($args);
                ?>
            </div>
            <?php the_category(); ?>
            <?php the_tags(); ?>            
        </article>
        <div class="post-navigation">
            <div class="nav-previous">
                <?php
                $prev_post = get_previous_post();
                if ($prev_post) {
                    $prev_post_number = get_post_number($prev_post->ID);
                    previous_post_link('%link', '< Previous Post (' . $prev_post_number . ')');
                }
                ?>
            </div>
            <div class="nav-next">
                <?php
                $next_post = get_next_post();
                if ($next_post) {
                    $next_post_number = get_post_number($next_post->ID);
                    next_post_link('%link', 'Next Post (' . $next_post_number . ') >');
                }
                ?>
            </div>
        </div>
        
        <div class="content-comment">
            <?php if (comments_open() || get_comments_number()) : ?>
                <?php comments_template(); ?>
            <?php endif; ?>
        </div>
    </main>

    <aside id="sidebar" class="sidebar-section">
        <?php get_sidebar(); ?>
    </aside>

</div>

<?php 
function get_post_number($post_id) {
    global $wpdb;
    $query = "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' AND ID <= $post_id";
    return $wpdb->get_var($query);
}

get_footer(); 
?>
