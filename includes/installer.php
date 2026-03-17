<?php
/**
 * Plugin Installer - Creates default pages, taxonomies, and sample content
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class HFE_Properties_Installer {

    /**
     * Run installation
     */
    public static function install() {
        // Check if already installed
        if (get_option('hfe_properties_installed')) {
            return;
        }

        // Create default pages
        self::create_default_pages();

        // Create default taxonomies
        self::create_default_taxonomies();

        // Set installation flag
        update_option('hfe_properties_installed', true);
        update_option('hfe_properties_version', HFE_PROPERTIES_VERSION);
        update_option('hfe_properties_install_date', current_time('mysql'));

        // Set admin notice
        set_transient('hfe_properties_activation_notice', true, 30);
    }

    /**
     * Create default pages
     */
    private static function create_default_pages() {
        $banner_image = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

        $pages = array(
            'properties-for-sale' => array(
                'title' => __('Properties for Sale', 'hfe-properties'),
                'content' => '[hfe_properties_carousel status="all" availability="available,sold" limit="12"]',
                'meta_description' => __('Browse our selection of properties for sale in the Netherlands. Find your dream home with Homes for Expats.', 'hfe-properties'),
                'banner' => $banner_image,
            ),
            'properties-sold' => array(
                'title' => __('Sold Properties', 'hfe-properties'),
                'content' => '[hfe_properties_grid status="all" availability="sold" columns="3" limit="12"]',
                'meta_description' => __('View our recently sold properties in the Netherlands.', 'hfe-properties'),
                'banner' => $banner_image,
            ),
            'properties-purchased' => array(
                'title' => __('Purchased Properties', 'hfe-properties'),
                'content' => '[hfe_properties_grid status="all" availability="purchased" columns="3" limit="12"]',
                'meta_description' => __('View our recently purchased properties in the Netherlands.', 'hfe-properties'),
                'banner' => $banner_image,
            ),
            'properties-for-rent' => array(
                'title' => __('Properties for Rent', 'hfe-properties'),
                'content' => '[hfe_properties_carousel status="all" availability="rent" limit="6"]',
                'meta_description' => __('Find rental properties in the Netherlands. Quality homes for expats and international professionals.', 'hfe-properties'),
                'banner' => $banner_image,
            ),
        );

        foreach ($pages as $slug => $page_data) {
            // Check if page already exists
            $existing_page = get_page_by_path($slug);

            if (!$existing_page) {
                $page_id = wp_insert_post(array(
                    'post_title'     => $page_data['title'],
                    'post_content'   => $page_data['content'],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'post_name'      => $slug,
                    'post_author'    => 1,
                    'comment_status' => 'closed',
                    'ping_status'    => 'closed',
                ));

                if ($page_id && !is_wp_error($page_id)) {
                    // Store page ID for reference
                    update_option('hfe_properties_page_' . str_replace('-', '_', $slug), $page_id);

                    // Set page template
                    update_post_meta($page_id, '_wp_page_template', 'templates/page-properties.php');

                    // Add banner image
                    if (isset($page_data['banner'])) {
                        update_post_meta($page_id, '_hfe_banner_image', $page_data['banner']);
                    }

                    // Add meta description if available
                    if (isset($page_data['meta_description'])) {
                        update_post_meta($page_id, '_hfe_meta_description', $page_data['meta_description']);
                    }
                }
            }
        }
    }

    /**
     * Create default property types
     */
    private static function create_default_taxonomies() {
        // Default Property Types
        $property_types = array(
            array(
                'name' => __('Apartment', 'hfe-properties'),
                'slug' => 'apartment',
                'description' => __('Apartments in residential buildings', 'hfe-properties'),
            ),
            array(
                'name' => __('House', 'hfe-properties'),
                'slug' => 'house',
                'description' => __('Single-family houses', 'hfe-properties'),
            ),
            array(
                'name' => __('Studio', 'hfe-properties'),
                'slug' => 'studio',
                'description' => __('Compact studio apartments', 'hfe-properties'),
            ),
            array(
                'name' => __('Villa', 'hfe-properties'),
                'slug' => 'villa',
                'description' => __('Luxury villas and mansions', 'hfe-properties'),
            ),
            array(
                'name' => __('Penthouse', 'hfe-properties'),
                'slug' => 'penthouse',
                'description' => __('Luxury penthouses with premium amenities', 'hfe-properties'),
            ),
        );

        foreach ($property_types as $type) {
            if (!term_exists($type['slug'], 'property_type')) {
                wp_insert_term(
                    $type['name'],
                    'property_type',
                    array(
                        'slug' => $type['slug'],
                        'description' => $type['description'],
                    )
                );
            }
        }

        // Default Locations (Major Dutch cities)
        $locations = array(
            array(
                'name' => __('Amsterdam', 'hfe-properties'),
                'slug' => 'amsterdam',
                'description' => __('Properties in Amsterdam and surrounding areas', 'hfe-properties'),
            ),
            array(
                'name' => __('Rotterdam', 'hfe-properties'),
                'slug' => 'rotterdam',
                'description' => __('Properties in Rotterdam', 'hfe-properties'),
            ),
            array(
                'name' => __('The Hague', 'hfe-properties'),
                'slug' => 'the-hague',
                'description' => __('Properties in The Hague (Den Haag)', 'hfe-properties'),
            ),
            array(
                'name' => __('Utrecht', 'hfe-properties'),
                'slug' => 'utrecht',
                'description' => __('Properties in Utrecht', 'hfe-properties'),
            ),
            array(
                'name' => __('Eindhoven', 'hfe-properties'),
                'slug' => 'eindhoven',
                'description' => __('Properties in Eindhoven', 'hfe-properties'),
            ),
            array(
                'name' => __('Haarlem', 'hfe-properties'),
                'slug' => 'haarlem',
                'description' => __('Properties in Haarlem', 'hfe-properties'),
            ),
            array(
                'name' => __('Leiden', 'hfe-properties'),
                'slug' => 'leiden',
                'description' => __('Properties in Leiden', 'hfe-properties'),
            ),
        );

        foreach ($locations as $location) {
            if (!term_exists($location['slug'], 'property_location')) {
                wp_insert_term(
                    $location['name'],
                    'property_location',
                    array(
                        'slug' => $location['slug'],
                        'description' => $location['description'],
                    )
                );
            }
        }
    }

    /**
     * Create sample property (optional - can be uncommented)
     */
    private static function create_sample_property() {
        // Check if sample already exists
        $existing = get_posts(array(
            'post_type' => 'hfe_property',
            'meta_key' => '_hfe_sample_property',
            'meta_value' => '1',
            'posts_per_page' => 1,
        ));

        if (!empty($existing)) {
            return;
        }

        // Create sample property
        $property_id = wp_insert_post(array(
            'post_title'   => __('Beautiful Canal-side Apartment in Amsterdam', 'hfe-properties'),
            'post_content' => __('This stunning 2-bedroom apartment offers breathtaking views of the Amsterdam canals. Located in the heart of the historic city center, this property combines modern luxury with classic Dutch charm. Features include high ceilings, large windows, a fully equipped kitchen, and a spacious living area. Perfect for expats looking for an authentic Amsterdam living experience.', 'hfe-properties'),
            'post_status'  => 'publish',
            'post_type'    => 'hfe_property',
            'post_author'  => 1,
        ));

        if ($property_id && !is_wp_error($property_id)) {
            // Add property meta
            update_post_meta($property_id, '_hfe_price', '475000');
            update_post_meta($property_id, '_hfe_price_currency', 'EUR');
            update_post_meta($property_id, '_hfe_size', '85');
            update_post_meta($property_id, '_hfe_bedrooms', '2');
            update_post_meta($property_id, '_hfe_bathrooms', '1');
            update_post_meta($property_id, '_hfe_floor', '3rd floor');
            update_post_meta($property_id, '_hfe_terrace', '1');
            update_post_meta($property_id, '_hfe_parking', '0');
            update_post_meta($property_id, '_hfe_status', 'sale');
            update_post_meta($property_id, '_hfe_availability', 'available');
            update_post_meta($property_id, '_hfe_year_built', '1920');
            update_post_meta($property_id, '_hfe_sample_property', '1');

            // Add features
            $features = "Canal views\nHigh ceilings\nModern kitchen\nHardwood floors\nDouble glazing\nCentral heating\nClose to public transport";
            update_post_meta($property_id, '_hfe_features', $features);

            // Assign taxonomies
            wp_set_object_terms($property_id, 'apartment', 'property_type');
            wp_set_object_terms($property_id, 'amsterdam', 'property_location');
        }
    }
}

/**
 * Display admin notice after activation
 */
function hfe_properties_activation_notice() {
    if (!get_transient('hfe_properties_activation_notice')) {
        return;
    }

    // Delete transient
    delete_transient('hfe_properties_activation_notice');

    $sale_page_id = get_option('hfe_properties_page_properties_for_sale');
    $sold_page_id = get_option('hfe_properties_page_properties_sold');
    $purchased_page_id = get_option('hfe_properties_page_properties_purchased');
    $rent_page_id = get_option('hfe_properties_page_properties_for_rent');
    ?>
    <div class="notice notice-success is-dismissible">
        <h2><?php _e('🎉 HFE Properties Activated Successfully!', 'hfe-properties'); ?></h2>
        <p><?php _e('The plugin has been set up with default pages and taxonomies.', 'hfe-properties'); ?></p>

        <h3><?php _e('✅ What was created:', 'hfe-properties'); ?></h3>
        <ul style="list-style: disc; margin-left: 20px;">
            <li>
                <strong><?php _e('Pages:', 'hfe-properties'); ?></strong>
                <ul style="list-style: circle; margin-left: 20px;">
                    <?php if ($sale_page_id) : ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($sale_page_id); ?>" target="_blank">
                                <?php _e('Properties for Sale', 'hfe-properties'); ?>
                            </a>
                            - <a href="<?php echo get_permalink($sale_page_id); ?>" target="_blank"><?php _e('View Page', 'hfe-properties'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($sold_page_id) : ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($sold_page_id); ?>" target="_blank">
                                <?php _e('Sold Properties', 'hfe-properties'); ?>
                            </a>
                            - <a href="<?php echo get_permalink($sold_page_id); ?>" target="_blank"><?php _e('View Page', 'hfe-properties'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($purchased_page_id) : ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($purchased_page_id); ?>" target="_blank">
                                <?php _e('Purchased Properties', 'hfe-properties'); ?>
                            </a>
                            - <a href="<?php echo get_permalink($purchased_page_id); ?>" target="_blank"><?php _e('View Page', 'hfe-properties'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($rent_page_id) : ?>
                        <li>
                            <a href="<?php echo get_edit_post_link($rent_page_id); ?>" target="_blank">
                                <?php _e('Properties for Rent', 'hfe-properties'); ?>
                            </a>
                            - <a href="<?php echo get_permalink($rent_page_id); ?>" target="_blank"><?php _e('View Page', 'hfe-properties'); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
            <li>
                <strong><?php _e('Property Types:', 'hfe-properties'); ?></strong>
                <?php _e('Apartment, House, Studio, Villa, Penthouse', 'hfe-properties'); ?>
            </li>
            <li>
                <strong><?php _e('Locations:', 'hfe-properties'); ?></strong>
                <?php _e('Amsterdam, Rotterdam, The Hague, Utrecht, Eindhoven, Haarlem, Leiden', 'hfe-properties'); ?>
            </li>
        </ul>

        <h3><?php _e('🚀 Next Steps:', 'hfe-properties'); ?></h3>
        <ol style="margin-left: 20px;">
            <li>
                <a href="<?php echo admin_url('post-new.php?post_type=hfe_property'); ?>">
                    <strong><?php _e('Add your first property', 'hfe-properties'); ?></strong>
                </a>
            </li>
            <li><?php _e('Add the pages to your navigation menu', 'hfe-properties'); ?></li>
            <li><?php _e('Customize the page content as needed', 'hfe-properties'); ?></li>
            <li><?php _e('Update colors to match your brand', 'hfe-properties'); ?></li>
        </ol>

        <p>
            <a href="<?php echo admin_url('post-new.php?post_type=hfe_property'); ?>" class="button button-primary">
                <?php _e('Add Property Now', 'hfe-properties'); ?>
            </a>
            <a href="<?php echo admin_url('edit.php?post_type=hfe_property'); ?>" class="button">
                <?php _e('View All Properties', 'hfe-properties'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=property_type&post_type=hfe_property'); ?>" class="button">
                <?php _e('Manage Property Types', 'hfe-properties'); ?>
            </a>
            <a href="<?php echo admin_url('edit-tags.php?taxonomy=property_location&post_type=hfe_property'); ?>" class="button">
                <?php _e('Manage Locations', 'hfe-properties'); ?>
            </a>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'hfe_properties_activation_notice');
