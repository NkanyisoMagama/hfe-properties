<?php
/**
 * Property Filters - Frontend controls for users
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Property Filter Controls Shortcode
 * Usage: [hfe_property_filters]
 */
function hfe_property_filters_shortcode($atts) {
    $atts = shortcode_atts(array(
        'status' => 'sale', // sale, rent, or all
        'availability' => '', // available, sold, purchased, rent, pending
        'show_limit' => 'true',
        'show_sort' => 'true',
        'show_view_type' => 'true',
        'default_limit' => '12',
        'default_view' => 'grid', // grid or carousel
    ), $atts, 'hfe_property_filters');

    ob_start();
    ?>
    <div class="hfe-property-filters-wrapper" data-status="<?php echo esc_attr($atts['status']); ?>" data-availability="<?php echo esc_attr($atts['availability']); ?>">
        <div class="hfe-property-filters">

            <?php if ($atts['show_limit'] === 'true') : ?>
                <div class="hfe-filter-group">
                    <label class="hfe-filter-label">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M3 3h10v2H3V3zm0 4h10v2H3V7zm0 4h10v2H3v-2z"/>
                        </svg>
                        <?php _e('Show:', 'hfe-properties'); ?>
                    </label>
                    <div class="hfe-filter-buttons">
                        <button class="hfe-limit-btn <?php echo $atts['default_limit'] == '6' ? 'active' : ''; ?>" data-limit="6">6</button>
                        <button class="hfe-limit-btn <?php echo $atts['default_limit'] == '12' ? 'active' : ''; ?>" data-limit="12">12</button>
                        <button class="hfe-limit-btn <?php echo $atts['default_limit'] == '24' ? 'active' : ''; ?>" data-limit="24">24</button>
                        <button class="hfe-limit-btn <?php echo $atts['default_limit'] == '-1' ? 'active' : ''; ?>" data-limit="-1"><?php _e('All', 'hfe-properties'); ?></button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($atts['show_sort'] === 'true') : ?>
                <div class="hfe-filter-group">
                    <label class="hfe-filter-label">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M5 2v12l-3-3m3 3l3-3M11 2l3 3m-3-3v12"/>
                        </svg>
                        <?php _e('Sort:', 'hfe-properties'); ?>
                    </label>
                    <select class="hfe-sort-select" id="hfe-sort-select">
                        <option value="date_desc"><?php _e('Newest First', 'hfe-properties'); ?></option>
                        <option value="date_asc"><?php _e('Oldest First', 'hfe-properties'); ?></option>
                        <option value="price_asc"><?php _e('Price: Low to High', 'hfe-properties'); ?></option>
                        <option value="price_desc"><?php _e('Price: High to Low', 'hfe-properties'); ?></option>
                        <option value="size_desc"><?php _e('Size: Largest First', 'hfe-properties'); ?></option>
                        <option value="size_asc"><?php _e('Size: Smallest First', 'hfe-properties'); ?></option>
                    </select>
                </div>
            <?php endif; ?>

            <?php if ($atts['show_view_type'] === 'true') : ?>
                <div class="hfe-filter-group">
                    <label class="hfe-filter-label">
                        <?php _e('View:', 'hfe-properties'); ?>
                    </label>
                    <div class="hfe-view-toggle">
                        <button class="hfe-view-btn <?php echo $atts['default_view'] == 'grid' ? 'active' : ''; ?>" data-view="grid" title="<?php _e('Grid View', 'hfe-properties'); ?>">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M1 1h6v6H1V1zm8 0h6v6H9V1zM1 9h6v6H1V9zm8 0h6v6H9V9z"/>
                            </svg>
                        </button>
                        <button class="hfe-view-btn <?php echo $atts['default_view'] == 'carousel' ? 'active' : ''; ?>" data-view="carousel" title="<?php _e('Carousel View', 'hfe-properties'); ?>">
                            <svg width="18" height="18" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M0 2h16v12H0V2zm2 2v8h12V4H2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="hfe-filter-results-count">
                <span class="hfe-loading-spinner" style="display: none;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="3" stroke-dasharray="32" stroke-linecap="round">
                            <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
                        </circle>
                    </svg>
                </span>
                <span class="hfe-results-text"></span>
            </div>
        </div>
    </div>

    <!-- Properties Container (will be populated dynamically) -->
    <div id="hfe-properties-container" data-default-limit="<?php echo esc_attr($atts['default_limit']); ?>" data-default-view="<?php echo esc_attr($atts['default_view']); ?>">
        <?php
        // Load initial properties
        echo hfe_load_filtered_properties(array(
            'status' => $atts['status'],
            'availability' => $atts['availability'],
            'limit' => $atts['default_limit'],
            'view' => $atts['default_view'],
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('hfe_property_filters', 'hfe_property_filters_shortcode');

/**
 * Load filtered properties (AJAX handler)
 */
function hfe_ajax_load_filtered_properties() {
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'sale';
    $availability = isset($_POST['availability']) ? sanitize_text_field($_POST['availability']) : '';
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 12;
    $view = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : 'grid';
    $sort = isset($_POST['sort']) ? sanitize_text_field($_POST['sort']) : 'date_desc';

    // Parse sort option
    list($orderby, $order) = explode('_', $sort);

    $html = hfe_load_filtered_properties(array(
        'status' => $status,
        'availability' => $availability,
        'limit' => $limit,
        'view' => $view,
        'orderby' => $orderby,
        'order' => strtoupper($order),
    ));

    wp_send_json_success(array(
        'html' => $html,
    ));
}
add_action('wp_ajax_hfe_load_filtered_properties', 'hfe_ajax_load_filtered_properties');
add_action('wp_ajax_nopriv_hfe_load_filtered_properties', 'hfe_ajax_load_filtered_properties');

/**
 * Load properties HTML based on filters
 */
function hfe_load_filtered_properties($args) {
    $defaults = array(
        'status' => 'sale',
        'availability' => '',
        'limit' => 12,
        'view' => 'grid',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $args = wp_parse_args($args, $defaults);

    // Build query
    $query_args = array(
        'post_type' => 'hfe_property',
        'posts_per_page' => $args['limit'],
        'post_status' => 'publish',
    );

    // Set orderby
    if ($args['orderby'] === 'price') {
        $query_args['meta_key'] = '_hfe_price';
        $query_args['orderby'] = 'meta_value_num';
        $query_args['order'] = $args['order'];
    } elseif ($args['orderby'] === 'size') {
        $query_args['meta_key'] = '_hfe_size';
        $query_args['orderby'] = 'meta_value_num';
        $query_args['order'] = $args['order'];
    } else {
        $query_args['orderby'] = $args['orderby'];
        $query_args['order'] = $args['order'];
    }

    // Filter by status
    if ($args['status'] !== 'all') {
        $query_args['meta_query'] = array(
            array(
                'key' => '_hfe_status',
                'value' => $args['status'],
                'compare' => '='
            )
        );
    }

    // Filter by availability
    if (!empty($args['availability'])) {
        if (!isset($query_args['meta_query'])) {
            $query_args['meta_query'] = array();
        }
        $query_args['meta_query'][] = array(
            'key' => '_hfe_availability',
            'value' => $args['availability'],
            'compare' => '='
        );
    }

    $query = new WP_Query($query_args);

    if (!$query->have_posts()) {
        return '<div class="hfe-no-properties"><p>' . __('No properties found.', 'hfe-properties') . '</p></div>';
    }

    ob_start();

    // Render based on view type
    if ($args['view'] === 'carousel') {
        // Render carousel
        ?>
        <div class="hfe-properties-carousel-wrapper">
            <div class="swiper hfe-properties-carousel">
                <div class="swiper-wrapper">
                    <?php
                    while ($query->have_posts()) : $query->the_post();
                        hfe_render_property_card('swiper-slide');
                    endwhile;
                    ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <?php
    } else {
        // Render grid
        ?>
        <div class="hfe-properties-grid hfe-grid-columns-3">
            <?php
            while ($query->have_posts()) : $query->the_post();
                hfe_render_property_card();
            endwhile;
            ?>
        </div>
        <?php
    }

    wp_reset_postdata();
    return ob_get_clean();
}

/**
 * Render a single property card
 */
function hfe_render_property_card($wrapper_class = '') {
    $property_id = get_the_ID();
    $price = get_post_meta($property_id, '_hfe_price', true);
    $currency = get_post_meta($property_id, '_hfe_price_currency', true) ?: 'EUR';
    $size = get_post_meta($property_id, '_hfe_size', true);
    $bedrooms = get_post_meta($property_id, '_hfe_bedrooms', true);
    $bathrooms = get_post_meta($property_id, '_hfe_bathrooms', true);
    $status = get_post_meta($property_id, '_hfe_status', true);
    $availability = get_post_meta($property_id, '_hfe_availability', true);
    $locations = wp_get_post_terms($property_id, 'property_location');

    $currency_symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
    );
    $currency_symbol = isset($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency;

    $wrapper_start = $wrapper_class ? '<div class="' . esc_attr($wrapper_class) . '">' : '';
    $wrapper_end = $wrapper_class ? '</div>' : '';

    echo $wrapper_start;
    ?>
    <div class="hfe-property-card">
        <?php if ($availability === 'sold') : ?>
            <div class="hfe-property-badge hfe-badge-sold">
                <?php echo $status === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
            </div>
        <?php elseif ($availability === 'pending') : ?>
            <div class="hfe-property-badge hfe-badge-pending">
                <?php _e('Pending', 'hfe-properties'); ?>
            </div>
        <?php endif; ?>

        <a href="<?php the_permalink(); ?>" class="hfe-property-image">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
            <?php else : ?>
                <img src="<?php echo HFE_PROPERTIES_URL; ?>assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>" />
            <?php endif; ?>
        </a>

        <div class="hfe-property-content">
            <div class="hfe-property-header">
                <?php if (!empty($locations)) : ?>
                    <span class="hfe-property-location">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor">
                            <path d="M7 0C4.24 0 2 2.24 2 5c0 3.75 5 9 5 9s5-5.25 5-9c0-2.76-2.24-5-5-5zm0 7c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                        </svg>
                        <?php echo esc_html($locations[0]->name); ?>
                    </span>
                <?php endif; ?>
                <span class="hfe-property-status">
                    <?php
                    $availability_labels = array(
                        'available' => __('For Sale', 'hfe-properties'),
                        'sold' => __('Sold', 'hfe-properties'),
                        'purchased' => __('Purchased', 'hfe-properties'),
                        'rent' => __('For Rent', 'hfe-properties'),
                        'pending' => __('Pending', 'hfe-properties'),
                    );
                    echo isset($availability_labels[$availability]) ? $availability_labels[$availability] : ucfirst($availability);
                    ?>
                </span>
            </div>

            <h3 class="hfe-property-title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>

            <?php if ($price) : ?>
                <div class="hfe-property-price">
                    <?php echo esc_html($currency_symbol . number_format($price, 0, ',', '.')); ?>
                    <?php if ($status === 'rent') : ?>
                        <span class="hfe-price-period"><?php _e('/month', 'hfe-properties'); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="hfe-property-meta">
                <?php if ($bedrooms) : ?>
                    <span class="hfe-meta-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M3 9h10v4H3V9zm0-2V5c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2v2h1c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1V8c0-.55.45-1 1-1h1z"/>
                        </svg>
                        <?php echo esc_html($bedrooms); ?> <?php _e('Beds', 'hfe-properties'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($bathrooms) : ?>
                    <span class="hfe-meta-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M2 7c0-2.76 2.24-5 5-5 .55 0 1 .45 1 1s-.45 1-1 1C5.35 4 4 5.35 4 7H2zm12 5H2v2h12v-2zm0-2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1h12z"/>
                        </svg>
                        <?php echo esc_html($bathrooms); ?> <?php _e('Baths', 'hfe-properties'); ?>
                    </span>
                <?php endif; ?>
                <?php if ($size) : ?>
                    <span class="hfe-meta-item">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M2 2v12h12V2H2zm10 10H4V4h8v8z"/>
                        </svg>
                        <?php echo esc_html($size); ?> m²
                    </span>
                <?php endif; ?>
            </div>

            <a href="<?php the_permalink(); ?>" class="hfe-property-button">
                <?php _e('View Details', 'hfe-properties'); ?>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M8 0L6.59 1.41 12.17 7H0v2h12.17l-5.58 5.59L8 16l8-8z"/>
                </svg>
            </a>
        </div>
    </div>
    <?php
    echo $wrapper_end;
}

/**
 * Enqueue filter scripts
 */
function hfe_enqueue_filter_scripts() {
    if (is_page() && (has_shortcode(get_post()->post_content, 'hfe_property_filters'))) {
        wp_enqueue_script(
            'hfe-property-filters',
            HFE_PROPERTIES_URL . 'assets/js/property-filters.js',
            array('jquery'),
            HFE_PROPERTIES_VERSION,
            true
        );

        wp_localize_script('hfe-property-filters', 'hfeFilters', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hfe_filters_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'hfe_enqueue_filter_scripts');
