<?php
/**
 * Shortcodes
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Properties Carousel Shortcode
 * Usage: [hfe_properties_carousel status="sale" limit="6"]
 */
function hfe_properties_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'status' => 'sale', // sale, rent, or all
        'availability' => '', // available, pending, sold (optional filter)
        'limit' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'location' => '', // location slug
        'type' => '', // property type slug
    ), $atts, 'hfe_properties_carousel');

    // Query args
    // When using multiple availability values, we need to fetch more posts to sort them properly
    $has_multiple_availability = !empty($atts['availability']) && strpos($atts['availability'], ',') !== false;

    $args = array(
        'post_type' => 'hfe_property',
        'posts_per_page' => $has_multiple_availability ? -1 : intval($atts['limit']), // Get all if multiple availability
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
    );

    // Filter by status
    if ($atts['status'] !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => '_hfe_status',
                'value' => $atts['status'],
                'compare' => '='
            )
        );
    }

    // Filter by availability (supports comma-separated values)
    if (!empty($atts['availability'])) {
        if (!isset($args['meta_query'])) {
            $args['meta_query'] = array();
        }

        // Check if multiple availability values are provided (comma-separated)
        $availability_values = array_map('trim', explode(',', $atts['availability']));

        if (count($availability_values) > 1) {
            // Multiple values: use IN comparison
            $args['meta_query'][] = array(
                'key' => '_hfe_availability',
                'value' => $availability_values,
                'compare' => 'IN'
            );
        } else {
            // Single value: use exact match
            $args['meta_query'][] = array(
                'key' => '_hfe_availability',
                'value' => $atts['availability'],
                'compare' => '='
            );
        }
    }

    // Filter by location
    if (!empty($atts['location'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property_location',
            'field' => 'slug',
            'terms' => $atts['location'],
        );
    }

    // Filter by type
    if (!empty($atts['type'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property_type',
            'field' => 'slug',
            'terms' => $atts['type'],
        );
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>' . __('No properties found.', 'hfe-properties') . '</p>';
    }

    // Custom sorting: prioritize available properties when multiple availability values are used
    if ($has_multiple_availability && $query->have_posts()) {
        $all_posts = $query->posts;
        $available_posts = array();
        $other_posts = array();

        foreach ($all_posts as $post) {
            $availability = get_post_meta($post->ID, '_hfe_availability', true);
            if ($availability === 'available') {
                $available_posts[] = $post;
            } else {
                $other_posts[] = $post;
            }
        }

        // Merge: available first, then others
        $query->posts = array_merge($available_posts, $other_posts);

        // Limit to requested number
        $query->posts = array_slice($query->posts, 0, intval($atts['limit']));
        $query->post_count = count($query->posts);
    }

    ob_start();
    ?>
    <div class="hfe-properties-carousel-wrapper">
        <div class="swiper hfe-properties-carousel">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php
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
                    ?>
                    <div class="swiper-slide">
                        <div class="hfe-property-card">
                            <?php if ($availability === 'sold') : ?>
                                <div class="hfe-property-badge hfe-badge-sold">
                                    <?php echo $status === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
                                </div>
                            <?php elseif ($availability === 'purchased') : ?>
                                <div class="hfe-property-badge hfe-badge-purchased">
                                    <?php _e('Purchased', 'hfe-properties'); ?>
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
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>

            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('hfe_properties_carousel', 'hfe_properties_carousel_shortcode');

/**
 * Properties Grid Shortcode
 * Usage: [hfe_properties_grid status="sale" limit="9" columns="3"]
 */
function hfe_properties_grid_shortcode($atts) {
    $atts = shortcode_atts(array(
        'status' => 'sale',
        'availability' => '', // available, pending, sold (optional filter)
        'limit' => 9,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
        'location' => '',
        'type' => '',
    ), $atts, 'hfe_properties_grid');

    // Query args (similar to carousel)
    // When using multiple availability values, we need to fetch more posts to sort them properly
    $has_multiple_availability = !empty($atts['availability']) && strpos($atts['availability'], ',') !== false;

    $args = array(
        'post_type' => 'hfe_property',
        'posts_per_page' => $has_multiple_availability ? -1 : intval($atts['limit']), // Get all if multiple availability
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
    );

    if ($atts['status'] !== 'all') {
        $args['meta_query'] = array(
            array(
                'key' => '_hfe_status',
                'value' => $atts['status'],
                'compare' => '='
            )
        );
    }

    // Filter by availability (supports comma-separated values)
    if (!empty($atts['availability'])) {
        if (!isset($args['meta_query'])) {
            $args['meta_query'] = array();
        }

        // Check if multiple availability values are provided (comma-separated)
        $availability_values = array_map('trim', explode(',', $atts['availability']));

        if (count($availability_values) > 1) {
            // Multiple values: use IN comparison
            $args['meta_query'][] = array(
                'key' => '_hfe_availability',
                'value' => $availability_values,
                'compare' => 'IN'
            );
        } else {
            // Single value: use exact match
            $args['meta_query'][] = array(
                'key' => '_hfe_availability',
                'value' => $atts['availability'],
                'compare' => '='
            );
        }
    }

    if (!empty($atts['location'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property_location',
            'field' => 'slug',
            'terms' => $atts['location'],
        );
    }

    if (!empty($atts['type'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property_type',
            'field' => 'slug',
            'terms' => $atts['type'],
        );
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>' . __('No properties found.', 'hfe-properties') . '</p>';
    }

    // Custom sorting: prioritize available properties when multiple availability values are used
    if ($has_multiple_availability && $query->have_posts()) {
        $all_posts = $query->posts;
        $available_posts = array();
        $other_posts = array();

        foreach ($all_posts as $post) {
            $availability = get_post_meta($post->ID, '_hfe_availability', true);
            if ($availability === 'available') {
                $available_posts[] = $post;
            } else {
                $other_posts[] = $post;
            }
        }

        // Merge: available first, then others
        $query->posts = array_merge($available_posts, $other_posts);

        // Limit to requested number
        $query->posts = array_slice($query->posts, 0, intval($atts['limit']));
        $query->post_count = count($query->posts);
    }

    ob_start();
    ?>
    <div class="hfe-properties-grid hfe-grid-columns-<?php echo esc_attr($atts['columns']); ?>">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
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
            ?>
            <div class="hfe-property-card">
                <?php if ($availability === 'sold') : ?>
                    <div class="hfe-property-badge hfe-badge-sold">
                        <?php echo $status === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
                    </div>
                <?php elseif ($availability === 'purchased') : ?>
                    <div class="hfe-property-badge hfe-badge-purchased">
                        <?php _e('Purchased', 'hfe-properties'); ?>
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
        <?php endwhile; ?>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('hfe_properties_grid', 'hfe_properties_grid_shortcode');

/**
 * Recently Sold/Purchased Properties Shortcode
 * Usage: [hfe_recent_sold limit="3" status="sale" columns="3"]
 */
function hfe_recent_sold_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 3,
        'status' => 'sale', // sale or rent
        'columns' => 3,
        'title' => '', // Optional heading
        'show_date' => 'true', // Show sold date
    ), $atts, 'hfe_recent_sold');

    // Query args
    $args = array(
        'post_type' => 'hfe_property',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => 'modified', // Order by last modified date (when marked as sold)
        'order' => 'DESC',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_hfe_status',
                'value' => $atts['status'],
                'compare' => '='
            ),
            array(
                'key' => '_hfe_availability',
                'value' => 'sold',
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>' . __('No recently sold properties found.', 'hfe-properties') . '</p>';
    }

    ob_start();
    ?>
    <div class="hfe-recent-sold-wrapper">
        <?php if (!empty($atts['title'])) : ?>
            <h2 class="hfe-recent-sold-title"><?php echo esc_html($atts['title']); ?></h2>
        <?php endif; ?>

        <div class="hfe-properties-grid hfe-grid-columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $property_id = get_the_ID();
                $price = get_post_meta($property_id, '_hfe_price', true);
                $currency = get_post_meta($property_id, '_hfe_price_currency', true) ?: 'EUR';
                $size = get_post_meta($property_id, '_hfe_size', true);
                $bedrooms = get_post_meta($property_id, '_hfe_bedrooms', true);
                $bathrooms = get_post_meta($property_id, '_hfe_bathrooms', true);
                $status = get_post_meta($property_id, '_hfe_status', true);
                $locations = wp_get_post_terms($property_id, 'property_location');
                $sold_date = get_the_modified_date('F Y'); // e.g., "December 2024"

                $currency_symbols = array(
                    'EUR' => '€',
                    'USD' => '$',
                    'GBP' => '£',
                );
                $currency_symbol = isset($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency;
                ?>
                <div class="hfe-property-card hfe-sold-property-card">
                    <div class="hfe-property-badge hfe-badge-sold">
                        <?php echo $status === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
                    </div>

                    <?php if ($atts['show_date'] === 'true') : ?>
                        <div class="hfe-sold-date-badge">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor">
                                <path d="M11 1v1H5V1H3v1H2c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2h-1V1h-2zm3 13H2V7h12v7z"/>
                            </svg>
                            <?php echo esc_html($sold_date); ?>
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
                            <span class="hfe-property-status hfe-status-sold-inline">
                                <?php echo $status === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
                            </span>
                        </div>

                        <h3 class="hfe-property-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>

                        <?php if ($price) : ?>
                            <div class="hfe-property-price hfe-sold-price">
                                <?php echo esc_html($currency_symbol . number_format($price, 0, ',', '.')); ?>
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
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('hfe_recent_sold', 'hfe_recent_sold_shortcode');
