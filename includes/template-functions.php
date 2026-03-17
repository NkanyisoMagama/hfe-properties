<?php
/**
 * Template Functions
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load property single template
 */
function hfe_property_single_template($template) {
    if (is_singular('hfe_property')) {
        $plugin_template = HFE_PROPERTIES_PATH . 'templates/single-property.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('single_template', 'hfe_property_single_template');

/**
 * Load property archive template
 */
function hfe_property_archive_template($template) {
    if (is_post_type_archive('hfe_property') || is_tax('property_type') || is_tax('property_location')) {
        $plugin_template = HFE_PROPERTIES_PATH . 'templates/archive-property.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('archive_template', 'hfe_property_archive_template');

/**
 * Get property price formatted
 */
function hfe_get_property_price($property_id = null) {
    if (!$property_id) {
        $property_id = get_the_ID();
    }

    $price = get_post_meta($property_id, '_hfe_price', true);
    $currency = get_post_meta($property_id, '_hfe_price_currency', true) ?: 'EUR';
    $status = get_post_meta($property_id, '_hfe_status', true);

    if (!$price) {
        return '';
    }

    $currency_symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£',
    );
    $currency_symbol = isset($currency_symbols[$currency]) ? $currency_symbols[$currency] : $currency;

    $formatted_price = $currency_symbol . number_format($price, 0, ',', '.');

    if ($status === 'rent') {
        $formatted_price .= '<span class="hfe-price-period">' . __('/month', 'hfe-properties') . '</span>';
    }

    return $formatted_price;
}

/**
 * Get property meta data
 */
function hfe_get_property_meta($property_id = null) {
    if (!$property_id) {
        $property_id = get_the_ID();
    }

    return array(
        'price' => get_post_meta($property_id, '_hfe_price', true),
        'currency' => get_post_meta($property_id, '_hfe_price_currency', true) ?: 'EUR',
        'size' => get_post_meta($property_id, '_hfe_size', true),
        'bedrooms' => get_post_meta($property_id, '_hfe_bedrooms', true),
        'bathrooms' => get_post_meta($property_id, '_hfe_bathrooms', true),
        'floor' => get_post_meta($property_id, '_hfe_floor', true),
        'terrace' => get_post_meta($property_id, '_hfe_terrace', true),
        'parking' => get_post_meta($property_id, '_hfe_parking', true),
        'status' => get_post_meta($property_id, '_hfe_status', true),
        'availability' => get_post_meta($property_id, '_hfe_availability', true),
        'year_built' => get_post_meta($property_id, '_hfe_year_built', true),
        'gallery' => get_post_meta($property_id, '_hfe_gallery', true),
        'features' => get_post_meta($property_id, '_hfe_features', true),
    );
}

/**
 * Display property gallery
 */
function hfe_property_gallery($property_id = null) {
    if (!$property_id) {
        $property_id = get_the_ID();
    }

    $gallery_ids = get_post_meta($property_id, '_hfe_gallery', true);

    if (empty($gallery_ids)) {
        return;
    }

    $gallery_ids_array = explode(',', $gallery_ids);
    ?>
    <div class="hfe-property-gallery-section">
        <h2>
            <?php _e('Property Gallery', 'hfe-properties'); ?>
            <span class="hfe-gallery-count">(<?php echo count($gallery_ids_array); ?> <?php _e('photos', 'hfe-properties'); ?>)</span>
        </h2>
        <div class="hfe-property-gallery-grid">
            <?php foreach ($gallery_ids_array as $image_id) : ?>
                <?php $image_url = wp_get_attachment_image_url($image_id, 'large'); ?>
                <?php if ($image_url) : ?>
                    <div class="hfe-gallery-item"
                         data-full-image="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>"
                         role="button"
                         tabindex="0"
                         aria-label="<?php _e('View full size image', 'hfe-properties'); ?>">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title($property_id)); ?>" />
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Display property features
 */
function hfe_property_features($property_id = null) {
    if (!$property_id) {
        $property_id = get_the_ID();
    }

    $features = get_post_meta($property_id, '_hfe_features', true);

    if (empty($features)) {
        return;
    }

    $features_array = explode("\n", $features);
    $features_array = array_filter(array_map('trim', $features_array));

    if (empty($features_array)) {
        return;
    }
    ?>
    <div class="hfe-property-features-section">
        <h2><?php _e('Features & Amenities', 'hfe-properties'); ?></h2>
        <ul class="hfe-features-list">
            <?php foreach ($features_array as $feature) : ?>
                <li>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm-2 15l-5-5 1.41-1.41L8 12.17l7.59-7.59L17 6l-9 9z"/>
                    </svg>
                    <?php echo esc_html($feature); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Display contact form section
 */
function hfe_property_contact_form($property_id = null) {
    if (!$property_id) {
        $property_id = get_the_ID();
    }
    ?>
    <div class="hfe-property-contact-section">
        <h2><?php _e('Interested in Buying a Home?', 'hfe-properties'); ?></h2>
        <p><?php _e('Contact us to schedule a meeting.', 'hfe-properties'); ?></p>

        <?php
        // Contact Form 7 Integration
        echo do_shortcode('[contact-form-7 id="9d85c62" title="Contact form 1"]');
        ?>

        <div class="hfe-contact-info">
            <div class="hfe-contact-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                <a href="mailto:info@exhs.nl">info@exhs.nl</a>
            </div>
            <div class="hfe-contact-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
                <a href="tel:+31628322235">+31 6 28 32 22 35</a>
            </div>
        </div>
    </div>
    <?php
}
