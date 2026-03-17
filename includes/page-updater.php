<?php
/**
 * Update Existing Pages with Banner
 * Run this once to update existing property pages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin page for updating existing pages
 */
function hfe_add_page_updater_menu() {
    add_submenu_page(
        'edit.php?post_type=hfe_property',
        __('Update Pages', 'hfe-properties'),
        __('Update Pages', 'hfe-properties'),
        'manage_options',
        'hfe-update-pages',
        'hfe_page_updater_page'
    );
}
add_action('admin_menu', 'hfe_add_page_updater_menu');

/**
 * Page updater admin page
 */
function hfe_page_updater_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle update action
    if (isset($_POST['hfe_update_pages']) && check_admin_referer('hfe_update_pages_action', 'hfe_update_pages_nonce')) {
        $result = hfe_update_existing_pages();

        if ($result['success']) {
            echo '<div class="notice notice-success is-dismissible"><p>' .
                 sprintf(__('Successfully updated %d pages!', 'hfe-properties'), $result['count']) .
                 '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' .
                 esc_html($result['message']) .
                 '</p></div>';
        }
    }

    $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

    // Find existing pages
    $pages_to_check = array(
        'properties-for-sale' => 'Properties for Sale',
        'properties-for-rent' => 'Properties for Rent',
        'all-properties' => 'All Properties',
        'sold-properties' => 'Sold Properties',
    );

    $found_pages = array();
    foreach ($pages_to_check as $slug => $title) {
        $page = get_page_by_path($slug);
        if ($page) {
            $has_banner = get_post_meta($page->ID, '_hfe_banner_image', true);
            $has_template = get_post_meta($page->ID, '_wp_page_template', true);

            $found_pages[] = array(
                'id' => $page->ID,
                'title' => $page->post_title,
                'slug' => $slug,
                'has_banner' => !empty($has_banner),
                'has_template' => $has_template === 'templates/page-properties.php',
                'edit_link' => get_edit_post_link($page->ID),
                'view_link' => get_permalink($page->ID),
            );
        }
    }
    ?>
    <div class="wrap">
        <h1><?php _e('Update Existing Property Pages', 'hfe-properties'); ?></h1>

        <div class="card" style="max-width: 800px;">
            <h2><?php _e('Add Banner to Existing Pages', 'hfe-properties'); ?></h2>
            <p><?php _e('This tool will add the banner image and template to your existing property pages.', 'hfe-properties'); ?></p>

            <?php if (!empty($found_pages)) : ?>
                <table class="widefat" style="margin: 20px 0;">
                    <thead>
                        <tr>
                            <th><?php _e('Page', 'hfe-properties'); ?></th>
                            <th><?php _e('Banner', 'hfe-properties'); ?></th>
                            <th><?php _e('Template', 'hfe-properties'); ?></th>
                            <th><?php _e('Actions', 'hfe-properties'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($found_pages as $page) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($page['title']); ?></strong></td>
                                <td>
                                    <?php if ($page['has_banner']) : ?>
                                        <span style="color: green;">✓ <?php _e('Has Banner', 'hfe-properties'); ?></span>
                                    <?php else : ?>
                                        <span style="color: red;">✗ <?php _e('No Banner', 'hfe-properties'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($page['has_template']) : ?>
                                        <span style="color: green;">✓ <?php _e('Template Set', 'hfe-properties'); ?></span>
                                    <?php else : ?>
                                        <span style="color: red;">✗ <?php _e('No Template', 'hfe-properties'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo esc_url($page['edit_link']); ?>" class="button button-small"><?php _e('Edit', 'hfe-properties'); ?></a>
                                    <a href="<?php echo esc_url($page['view_link']); ?>" class="button button-small" target="_blank"><?php _e('View', 'hfe-properties'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                    <h3 style="margin-top: 0;"><?php _e('What This Will Do:', 'hfe-properties'); ?></h3>
                    <ul style="margin-left: 20px;">
                        <li><?php _e('Set banner image to:', 'hfe-properties'); ?> <code><?php echo esc_html($banner_image); ?></code></li>
                        <li><?php _e('Assign page template:', 'hfe-properties'); ?> <code>templates/page-properties.php</code></li>
                        <li><?php _e('Keep all existing content unchanged', 'hfe-properties'); ?></li>
                    </ul>
                </div>

                <form method="post" onsubmit="return confirm('<?php _e('Are you sure you want to update these pages?', 'hfe-properties'); ?>');">
                    <?php wp_nonce_field('hfe_update_pages_action', 'hfe_update_pages_nonce'); ?>
                    <button type="submit" name="hfe_update_pages" class="button button-primary button-large">
                        <?php _e('Update All Pages Now', 'hfe-properties'); ?>
                    </button>
                </form>

            <?php else : ?>
                <div class="notice notice-warning inline">
                    <p><?php _e('No property pages found. The pages may have been deleted or renamed.', 'hfe-properties'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Manual Update Instructions', 'hfe-properties'); ?></h2>
            <p><?php _e('If you prefer to update pages manually:', 'hfe-properties'); ?></p>
            <ol style="margin-left: 20px;">
                <li><?php _e('Go to Pages > All Pages', 'hfe-properties'); ?></li>
                <li><?php _e('Edit each property page', 'hfe-properties'); ?></li>
                <li><?php _e('In Page Attributes, select Template: "Properties Page with Banner"', 'hfe-properties'); ?></li>
                <li><?php _e('Add Custom Field:', 'hfe-properties'); ?>
                    <ul style="margin-left: 20px;">
                        <li><?php _e('Name:', 'hfe-properties'); ?> <code>_hfe_banner_image</code></li>
                        <li><?php _e('Value:', 'hfe-properties'); ?> <code><?php echo esc_html($banner_image); ?></code></li>
                    </ul>
                </li>
                <li><?php _e('Update the page', 'hfe-properties'); ?></li>
            </ol>
        </div>
    </div>
    <?php
}

/**
 * Update existing pages with banner and template
 */
function hfe_update_existing_pages() {
    $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

    $pages_to_update = array(
        'properties-for-sale',
        'properties-for-rent',
        'all-properties',
        'sold-properties',
    );

    $count = 0;

    foreach ($pages_to_update as $slug) {
        $page = get_page_by_path($slug);

        if ($page) {
            // Set banner image
            update_post_meta($page->ID, '_hfe_banner_image', $banner_image);

            // Set page template
            update_post_meta($page->ID, '_wp_page_template', 'templates/page-properties.php');

            $count++;
        }
    }

    return array(
        'success' => true,
        'count' => $count,
        'message' => ''
    );
}
