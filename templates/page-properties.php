<?php
/**
 * Template Name: Properties Page with Banner
 * Template for properties listing pages with banner/breadcrumb
 */

get_header();

// Hardcoded banner image
$banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

// Get page title
$page_title = get_the_title();
?>

<!-- Banner / Breadcrumb Section -->
<div class="hfe-page-banner" style="background-image: url('<?php echo esc_url($banner_image); ?>');">
    <div class="hfe-banner-overlay">
        <div class="hfe-container">
            <div class="hfe-breadcrumb">
                <a href="<?php echo home_url(); ?>"><?php _e('Home', 'hfe-properties'); ?></a>
                <span class="hfe-breadcrumb-separator">/</span>
                <span class="hfe-breadcrumb-current"><?php echo esc_html($page_title); ?></span>
            </div>
            <h1 class="hfe-page-title"><?php echo esc_html($page_title); ?></h1>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="hfe-page-content">
    <div class="hfe-container">
        <?php
        while (have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>

<?php get_footer(); ?>
