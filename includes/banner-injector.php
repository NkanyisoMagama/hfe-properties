<?php
/**
 * Banner Injector
 * Automatically adds banner to property listing pages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Inject banner AFTER header on property listing pages
 */
function hfe_inject_banner_on_property_pages() {
    // Check if we're on a page (not a post)
    if (!is_page()) {
        return;
    }

    // Get the page slug
    $page_slug = get_post_field('post_name', get_the_ID());

    // List of page slugs that should have banner
    $banner_pages = array(
        'properties-for-sale',
        'properties-for-rent',
        'all-properties',
        'sold-properties',
    );

    // Check if current page should have banner
    if (in_array($page_slug, $banner_pages)) {
        // Hardcoded banner image
        $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';
        $page_title = get_the_title();
        ?>
        <div class="hfe-page-banner" style="background-image: url('<?php echo esc_url($banner_image); ?>');">
            <div class="hfe-banner-overlay">
                <div class="hfe-container">
                    <div class="hfe-breadcrumb">
                        <a href="<?php echo home_url(); ?>">Home</a>
                        <span class="hfe-breadcrumb-separator">/</span>
                        <span class="hfe-breadcrumb-current"><?php echo esc_html($page_title); ?></span>
                    </div>
                    <h1 class="hfe-page-title"><?php echo esc_html($page_title); ?></h1>
                </div>
            </div>
        </div>
        <?php
    }
}

// Hook after header - try multiple hooks for theme compatibility
add_action('genesis_after_header', 'hfe_inject_banner_on_property_pages', 5);
add_action('tha_header_after', 'hfe_inject_banner_on_property_pages', 5);
add_action('elementor/page_templates/canvas/before_content', 'hfe_inject_banner_on_property_pages', 5);

/**
 * Filter the content to prepend banner (fallback method)
 */
function hfe_prepend_banner_to_content($content) {
    // Only on main query and pages
    if (!is_main_query() || !is_page()) {
        return $content;
    }

    // Get the page slug
    $page_slug = get_post_field('post_name', get_the_ID());

    // List of page slugs that should have banner
    $banner_pages = array(
        'properties-for-sale',
        'properties-for-rent',
        'all-properties',
        'sold-properties',
    );

    // Check if current page should have banner
    if (in_array($page_slug, $banner_pages)) {
        // Hardcoded banner image
        $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';
        $page_title = get_the_title();

        $banner_html = '
        <div class="hfe-page-banner" style="background-image: url(\'' . esc_url($banner_image) . '\');">
            <div class="hfe-banner-overlay">
                <div class="hfe-container">
                    <div class="hfe-breadcrumb">
                        <a href="' . home_url() . '">Home</a>
                        <span class="hfe-breadcrumb-separator">/</span>
                        <span class="hfe-breadcrumb-current">' . esc_html($page_title) . '</span>
                    </div>
                    <h1 class="hfe-page-title">' . esc_html($page_title) . '</h1>
                </div>
            </div>
        </div>';

        return $banner_html . $content;
    }

    return $content;
}
add_filter('the_content', 'hfe_prepend_banner_to_content', 1);
