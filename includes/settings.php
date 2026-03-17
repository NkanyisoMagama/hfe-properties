<?php
/**
 * Plugin Settings Page
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add settings page to admin menu
 */
function hfe_properties_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=hfe_property',
        __('Settings', 'hfe-properties'),
        __('Settings', 'hfe-properties'),
        'manage_options',
        'hfe-properties-settings',
        'hfe_properties_settings_page'
    );
}
add_action('admin_menu', 'hfe_properties_add_settings_page');

/**
 * Settings page content
 */
function hfe_properties_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['hfe_regenerate_pages']) && check_admin_referer('hfe_properties_settings', 'hfe_properties_nonce')) {
        hfe_properties_regenerate_pages();
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Pages regenerated successfully!', 'hfe-properties') . '</p></div>';
    }

    if (isset($_POST['hfe_update_page_shortcodes']) && check_admin_referer('hfe_properties_settings', 'hfe_properties_nonce')) {
        hfe_properties_update_page_shortcodes();
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Page shortcodes updated successfully!', 'hfe-properties') . '</p></div>';
    }

    if (isset($_POST['hfe_reset_plugin']) && check_admin_referer('hfe_properties_settings', 'hfe_properties_nonce')) {
        hfe_properties_reset_installation();
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Plugin settings reset successfully!', 'hfe-properties') . '</p></div>';
    }

    $sale_page_id = get_option('hfe_properties_page_properties_for_sale');
    $sold_page_id = get_option('hfe_properties_page_properties_sold');
    $purchased_page_id = get_option('hfe_properties_page_properties_purchased');
    $rent_page_id = get_option('hfe_properties_page_properties_for_rent');
    $install_date = get_option('hfe_properties_install_date');
    $version = get_option('hfe_properties_version');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <div class="hfe-settings-container" style="max-width: 1000px;">

            <!-- Plugin Info -->
            <div class="card">
                <h2><?php _e('Plugin Information', 'hfe-properties'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Version:', 'hfe-properties'); ?></th>
                        <td><strong><?php echo esc_html($version ?: HFE_PROPERTIES_VERSION); ?></strong></td>
                    </tr>
                    <tr>
                        <th><?php _e('Installed:', 'hfe-properties'); ?></th>
                        <td><?php echo $install_date ? esc_html(date_i18n(get_option('date_format'), strtotime($install_date))) : __('Unknown', 'hfe-properties'); ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Properties Count:', 'hfe-properties'); ?></th>
                        <td>
                            <?php
                            $count = wp_count_posts('hfe_property');
                            echo esc_html($count->publish);
                            ?> <?php _e('published', 'hfe-properties'); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Generated Pages -->
            <div class="card">
                <h2><?php _e('Generated Pages', 'hfe-properties'); ?></h2>
                <p><?php _e('These pages were automatically created during plugin activation:', 'hfe-properties'); ?></p>

                <table class="widefat">
                    <thead>
                        <tr>
                            <th><?php _e('Page', 'hfe-properties'); ?></th>
                            <th><?php _e('Status', 'hfe-properties'); ?></th>
                            <th><?php _e('Shortcode', 'hfe-properties'); ?></th>
                            <th><?php _e('Actions', 'hfe-properties'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php _e('Properties for Sale', 'hfe-properties'); ?></strong></td>
                            <td>
                                <?php if ($sale_page_id && get_post_status($sale_page_id)) : ?>
                                    <span style="color: green;">✓ <?php _e('Active', 'hfe-properties'); ?></span>
                                <?php else : ?>
                                    <span style="color: red;">✗ <?php _e('Not Found', 'hfe-properties'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code>[hfe_properties_carousel status="all" availability="available" limit="6"]</code></td>
                            <td>
                                <?php if ($sale_page_id && get_post_status($sale_page_id)) : ?>
                                    <a href="<?php echo get_edit_post_link($sale_page_id); ?>" class="button button-small"><?php _e('Edit', 'hfe-properties'); ?></a>
                                    <a href="<?php echo get_permalink($sale_page_id); ?>" class="button button-small" target="_blank"><?php _e('View', 'hfe-properties'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Sold Properties', 'hfe-properties'); ?></strong></td>
                            <td>
                                <?php if ($sold_page_id && get_post_status($sold_page_id)) : ?>
                                    <span style="color: green;">✓ <?php _e('Active', 'hfe-properties'); ?></span>
                                <?php else : ?>
                                    <span style="color: red;">✗ <?php _e('Not Found', 'hfe-properties'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code>[hfe_properties_grid status="all" availability="sold" columns="3" limit="12"]</code></td>
                            <td>
                                <?php if ($sold_page_id && get_post_status($sold_page_id)) : ?>
                                    <a href="<?php echo get_edit_post_link($sold_page_id); ?>" class="button button-small"><?php _e('Edit', 'hfe-properties'); ?></a>
                                    <a href="<?php echo get_permalink($sold_page_id); ?>" class="button button-small" target="_blank"><?php _e('View', 'hfe-properties'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Purchased Properties', 'hfe-properties'); ?></strong></td>
                            <td>
                                <?php if ($purchased_page_id && get_post_status($purchased_page_id)) : ?>
                                    <span style="color: green;">✓ <?php _e('Active', 'hfe-properties'); ?></span>
                                <?php else : ?>
                                    <span style="color: red;">✗ <?php _e('Not Found', 'hfe-properties'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code>[hfe_properties_grid status="all" availability="purchased" columns="3" limit="12"]</code></td>
                            <td>
                                <?php if ($purchased_page_id && get_post_status($purchased_page_id)) : ?>
                                    <a href="<?php echo get_edit_post_link($purchased_page_id); ?>" class="button button-small"><?php _e('Edit', 'hfe-properties'); ?></a>
                                    <a href="<?php echo get_permalink($purchased_page_id); ?>" class="button button-small" target="_blank"><?php _e('View', 'hfe-properties'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php _e('Properties for Rent', 'hfe-properties'); ?></strong></td>
                            <td>
                                <?php if ($rent_page_id && get_post_status($rent_page_id)) : ?>
                                    <span style="color: green;">✓ <?php _e('Active', 'hfe-properties'); ?></span>
                                <?php else : ?>
                                    <span style="color: red;">✗ <?php _e('Not Found', 'hfe-properties'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><code>[hfe_properties_carousel status="all" availability="rent" limit="6"]</code></td>
                            <td>
                                <?php if ($rent_page_id && get_post_status($rent_page_id)) : ?>
                                    <a href="<?php echo get_edit_post_link($rent_page_id); ?>" class="button button-small"><?php _e('Edit', 'hfe-properties'); ?></a>
                                    <a href="<?php echo get_permalink($rent_page_id); ?>" class="button button-small" target="_blank"><?php _e('View', 'hfe-properties'); ?></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <form method="post" style="margin-top: 20px;">
                    <?php wp_nonce_field('hfe_properties_settings', 'hfe_properties_nonce'); ?>
                    <button type="submit" name="hfe_regenerate_pages" class="button button-secondary">
                        <?php _e('Regenerate Missing Pages', 'hfe-properties'); ?>
                    </button>
                    <button type="submit" name="hfe_update_page_shortcodes" class="button button-primary" style="margin-left: 10px;">
                        <?php _e('Update Existing Page Shortcodes', 'hfe-properties'); ?>
                    </button>
                    <p class="description">
                        <?php _e('Regenerate will create missing pages. Update will fix shortcodes on existing pages to use the new format.', 'hfe-properties'); ?>
                    </p>
                </form>
            </div>

            <!-- Taxonomies -->
            <div class="card">
                <h2><?php _e('Taxonomies', 'hfe-properties'); ?></h2>

                <h3><?php _e('Property Types', 'hfe-properties'); ?></h3>
                <?php
                $types = get_terms(array('taxonomy' => 'property_type', 'hide_empty' => false));
                if (!empty($types)) {
                    echo '<ul>';
                    foreach ($types as $type) {
                        echo '<li><strong>' . esc_html($type->name) . '</strong> (' . $type->count . ' ' . __('properties', 'hfe-properties') . ')</li>';
                    }
                    echo '</ul>';
                }
                ?>
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=property_type&post_type=hfe_property'); ?>" class="button">
                    <?php _e('Manage Property Types', 'hfe-properties'); ?>
                </a>

                <h3 style="margin-top: 20px;"><?php _e('Locations', 'hfe-properties'); ?></h3>
                <?php
                $locations = get_terms(array('taxonomy' => 'property_location', 'hide_empty' => false));
                if (!empty($locations)) {
                    echo '<ul>';
                    foreach ($locations as $location) {
                        echo '<li><strong>' . esc_html($location->name) . '</strong> (' . $location->count . ' ' . __('properties', 'hfe-properties') . ')</li>';
                    }
                    echo '</ul>';
                }
                ?>
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=property_location&post_type=hfe_property'); ?>" class="button">
                    <?php _e('Manage Locations', 'hfe-properties'); ?>
                </a>
            </div>

            <!-- Shortcode Reference -->
            <div class="card">
                <h2><?php _e('Shortcode Reference', 'hfe-properties'); ?></h2>

                <h3><?php _e('Properties Carousel', 'hfe-properties'); ?></h3>
                <p><code>[hfe_properties_carousel status="sale" limit="6"]</code></p>
                <p class="description">
                    <?php _e('Parameters:', 'hfe-properties'); ?>
                    <strong>status</strong> (sale/rent/all),
                    <strong>limit</strong> (number),
                    <strong>location</strong> (slug),
                    <strong>type</strong> (slug)
                </p>

                <h3><?php _e('Properties Grid', 'hfe-properties'); ?></h3>
                <p><code>[hfe_properties_grid status="sale" columns="3" limit="9"]</code></p>
                <p class="description">
                    <?php _e('Additional parameter:', 'hfe-properties'); ?>
                    <strong>columns</strong> (2/3/4)
                </p>
            </div>

            <!-- Danger Zone -->
            <div class="card" style="border-left: 4px solid #dc3232;">
                <h2 style="color: #dc3232;"><?php _e('⚠️ Danger Zone', 'hfe-properties'); ?></h2>
                <p><?php _e('Reset plugin installation settings. This will NOT delete properties or pages, only reset the installation flags.', 'hfe-properties'); ?></p>

                <form method="post" onsubmit="return confirm('<?php _e('Are you sure? This will reset the installation settings.', 'hfe-properties'); ?>');">
                    <?php wp_nonce_field('hfe_properties_settings', 'hfe_properties_nonce'); ?>
                    <button type="submit" name="hfe_reset_plugin" class="button button-secondary">
                        <?php _e('Reset Installation Settings', 'hfe-properties'); ?>
                    </button>
                </form>
            </div>

        </div>
    </div>
    <?php
}

/**
 * Regenerate missing pages
 */
function hfe_properties_regenerate_pages() {
    require_once HFE_PROPERTIES_PATH . 'includes/installer.php';

    $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

    $pages = array(
        'properties-for-sale' => array(
            'title' => __('Properties for Sale', 'hfe-properties'),
            'content' => '[hfe_properties_carousel status="all" availability="available" limit="6"]',
            'banner' => $banner_image,
            'meta_description' => __('Browse our selection of properties for sale in the Netherlands. Find your dream home with Homes for Expats.', 'hfe-properties'),
        ),
        'properties-sold' => array(
            'title' => __('Sold Properties', 'hfe-properties'),
            'content' => '[hfe_properties_grid status="all" availability="sold" columns="3" limit="12"]',
            'banner' => $banner_image,
            'meta_description' => __('View our recently sold properties in the Netherlands.', 'hfe-properties'),
        ),
        'properties-purchased' => array(
            'title' => __('Purchased Properties', 'hfe-properties'),
            'content' => '[hfe_properties_grid status="all" availability="purchased" columns="3" limit="12"]',
            'banner' => $banner_image,
            'meta_description' => __('View our recently purchased properties in the Netherlands.', 'hfe-properties'),
        ),
        'properties-for-rent' => array(
            'title' => __('Properties for Rent', 'hfe-properties'),
            'content' => '[hfe_properties_carousel status="all" availability="rent" limit="6"]',
            'banner' => $banner_image,
            'meta_description' => __('Find rental properties in the Netherlands. Quality homes for expats and international professionals.', 'hfe-properties'),
        ),
    );

    foreach ($pages as $slug => $page_data) {
        $page_option = 'hfe_properties_page_' . str_replace('-', '_', $slug);
        $page_id = get_option($page_option);

        // Only create if doesn't exist
        if (!$page_id || !get_post_status($page_id)) {
            $new_page_id = wp_insert_post(array(
                'post_title'     => $page_data['title'],
                'post_content'   => $page_data['content'],
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'post_name'      => $slug,
                'post_author'    => get_current_user_id(),
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
            ));

            if ($new_page_id && !is_wp_error($new_page_id)) {
                update_option($page_option, $new_page_id);

                // Set page template
                update_post_meta($new_page_id, '_wp_page_template', 'templates/page-properties.php');

                // Add banner image
                if (isset($page_data['banner'])) {
                    update_post_meta($new_page_id, '_hfe_banner_image', $page_data['banner']);
                }

                // Add meta description
                if (isset($page_data['meta_description'])) {
                    update_post_meta($new_page_id, '_hfe_meta_description', $page_data['meta_description']);
                }
            }
        }
    }
}

/**
 * Update existing page shortcodes to new format
 */
function hfe_properties_update_page_shortcodes() {
    $pages_to_update = array(
        'hfe_properties_page_properties_for_sale' => '[hfe_properties_carousel status="all" availability="available" limit="6"]',
        'hfe_properties_page_properties_sold' => '[hfe_properties_grid status="all" availability="sold" columns="3" limit="12"]',
        'hfe_properties_page_properties_purchased' => '[hfe_properties_grid status="all" availability="purchased" columns="3" limit="12"]',
        'hfe_properties_page_properties_for_rent' => '[hfe_properties_carousel status="all" availability="rent" limit="6"]',
    );

    foreach ($pages_to_update as $option_name => $new_shortcode) {
        $page_id = get_option($option_name);

        if ($page_id && get_post_status($page_id)) {
            // Update the page content with new shortcode
            wp_update_post(array(
                'ID' => $page_id,
                'post_content' => $new_shortcode,
            ));
        }
    }
}

/**
 * Reset installation settings
 */
function hfe_properties_reset_installation() {
    delete_option('hfe_properties_installed');
    delete_option('hfe_properties_version');
    delete_option('hfe_properties_install_date');
}
