<?php
/**
 * Property Meta Boxes
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes
 */
function hfe_add_property_meta_boxes() {
    add_meta_box(
        'hfe_property_details',
        __('Property Details', 'hfe-properties'),
        'hfe_property_details_callback',
        'hfe_property',
        'normal',
        'high'
    );

    add_meta_box(
        'hfe_property_gallery',
        __('Property Gallery', 'hfe-properties'),
        'hfe_property_gallery_callback',
        'hfe_property',
        'normal',
        'default'
    );

    add_meta_box(
        'hfe_property_features',
        __('Property Features & Amenities', 'hfe-properties'),
        'hfe_property_features_callback',
        'hfe_property',
        'normal',
        'default'
    );

    add_meta_box(
        'hfe_property_banner',
        __('Property Page Banner Image', 'hfe-properties'),
        'hfe_property_banner_callback',
        'hfe_property',
        'side',
        'low'
    );
}
add_action('add_meta_boxes', 'hfe_add_property_meta_boxes');

/**
 * Property Details Meta Box Callback
 */
function hfe_property_details_callback($post) {
    wp_nonce_field('hfe_save_property_details', 'hfe_property_details_nonce');

    $price = get_post_meta($post->ID, '_hfe_price', true);
    $price_currency = get_post_meta($post->ID, '_hfe_price_currency', true) ?: 'EUR';
    $size = get_post_meta($post->ID, '_hfe_size', true);
    $bedrooms = get_post_meta($post->ID, '_hfe_bedrooms', true);
    $bathrooms = get_post_meta($post->ID, '_hfe_bathrooms', true);
    $floor = get_post_meta($post->ID, '_hfe_floor', true);
    $terrace = get_post_meta($post->ID, '_hfe_terrace', true);
    $parking = get_post_meta($post->ID, '_hfe_parking', true);
    $status = get_post_meta($post->ID, '_hfe_status', true) ?: 'sale';
    $availability = get_post_meta($post->ID, '_hfe_availability', true) ?: 'available';
    $year_built = get_post_meta($post->ID, '_hfe_year_built', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="hfe_availability"><?php _e('Property Status', 'hfe-properties'); ?></label></th>
            <td>
                <select name="hfe_availability" id="hfe_availability" style="width: 250px;">
                    <option value="available" <?php selected($availability, 'available'); ?>><?php _e('For Sale', 'hfe-properties'); ?></option>
                    <option value="sold" <?php selected($availability, 'sold'); ?>><?php _e('Sold Property', 'hfe-properties'); ?></option>
                    <option value="purchased" <?php selected($availability, 'purchased'); ?>><?php _e('Purchased Property', 'hfe-properties'); ?></option>
                    <option value="rent" <?php selected($availability, 'rent'); ?>><?php _e('For Rent', 'hfe-properties'); ?></option>
                    <option value="pending" <?php selected($availability, 'pending'); ?>><?php _e('Pending', 'hfe-properties'); ?></option>
                </select>
                <p class="description"><strong><?php _e('Select the current status - this determines which page the property appears on:', 'hfe-properties'); ?></strong><br>
                • <strong>For Sale</strong> - Shows on "Properties for Sale" page<br>
                • <strong>Sold Property</strong> - Shows on "Sold Properties" page<br>
                • <strong>Purchased Property</strong> - Shows on "Purchased Properties" page<br>
                • <strong>For Rent</strong> - Shows on "Properties for Rent" page</p>
            </td>
        </tr>
        <tr style="display:none;">
            <th><label for="hfe_status"><?php _e('Internal Status', 'hfe-properties'); ?></label></th>
            <td>
                <select name="hfe_status" id="hfe_status" style="width: 200px;">
                    <option value="sale" <?php selected($status, 'sale'); ?>><?php _e('Sale', 'hfe-properties'); ?></option>
                    <option value="rent" <?php selected($status, 'rent'); ?>><?php _e('Rent', 'hfe-properties'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="hfe_price"><?php _e('Price', 'hfe-properties'); ?></label></th>
            <td>
                <select name="hfe_price_currency" id="hfe_price_currency" style="width: 80px;">
                    <option value="EUR" <?php selected($price_currency, 'EUR'); ?>>€</option>
                    <option value="USD" <?php selected($price_currency, 'USD'); ?>>$</option>
                    <option value="GBP" <?php selected($price_currency, 'GBP'); ?>>£</option>
                </select>
                <input type="number" name="hfe_price" id="hfe_price" value="<?php echo esc_attr($price); ?>" style="width: 150px;" step="0.01" />
                <p class="description"><?php _e('Enter the property price', 'hfe-properties'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="hfe_size"><?php _e('Size (m²)', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_size" id="hfe_size" value="<?php echo esc_attr($size); ?>" style="width: 150px;" />
                <p class="description"><?php _e('Property size in square meters', 'hfe-properties'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="hfe_bedrooms"><?php _e('Bedrooms', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_bedrooms" id="hfe_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" style="width: 150px;" min="0" />
            </td>
        </tr>
        <tr>
            <th><label for="hfe_bathrooms"><?php _e('Bathrooms', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_bathrooms" id="hfe_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" style="width: 150px;" min="0" step="0.5" />
            </td>
        </tr>
        <tr>
            <th><label for="hfe_floor"><?php _e('Floor', 'hfe-properties'); ?></label></th>
            <td>
                <input type="text" name="hfe_floor" id="hfe_floor" value="<?php echo esc_attr($floor); ?>" style="width: 150px;" />
                <p class="description"><?php _e('e.g., 6th floor, Ground floor', 'hfe-properties'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="hfe_terrace"><?php _e('Terrace/Balcony', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_terrace" id="hfe_terrace" value="<?php echo esc_attr($terrace); ?>" style="width: 150px;" min="0" />
                <p class="description"><?php _e('Number of terraces/balconies', 'hfe-properties'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="hfe_parking"><?php _e('Parking Spaces', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_parking" id="hfe_parking" value="<?php echo esc_attr($parking); ?>" style="width: 150px;" min="0" />
            </td>
        </tr>
        <tr>
            <th><label for="hfe_year_built"><?php _e('Year Built', 'hfe-properties'); ?></label></th>
            <td>
                <input type="number" name="hfe_year_built" id="hfe_year_built" value="<?php echo esc_attr($year_built); ?>" style="width: 150px;" min="1800" max="<?php echo date('Y') + 5; ?>" />
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Property Gallery Meta Box Callback
 */
function hfe_property_gallery_callback($post) {
    wp_nonce_field('hfe_save_property_gallery', 'hfe_property_gallery_nonce');

    $gallery_ids = get_post_meta($post->ID, '_hfe_gallery', true);
    ?>
    <div class="hfe-gallery-container">
        <div class="hfe-gallery-images" id="hfe-gallery-images">
            <?php
            if (!empty($gallery_ids)) {
                $gallery_ids_array = explode(',', $gallery_ids);
                foreach ($gallery_ids_array as $image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    if ($image_url) {
                        echo '<div class="hfe-gallery-image" data-id="' . esc_attr($image_id) . '">';
                        echo '<img src="' . esc_url($image_url) . '" />';
                        echo '<span class="hfe-remove-image">&times;</span>';
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
        <input type="hidden" name="hfe_gallery" id="hfe_gallery" value="<?php echo esc_attr($gallery_ids); ?>" />
        <button type="button" class="button button-primary" id="hfe-add-gallery-images">
            <?php _e('Add Images', 'hfe-properties'); ?>
        </button>
        <p class="description"><?php _e('Add multiple images for the property gallery', 'hfe-properties'); ?></p>
    </div>
    <?php
}

/**
 * Property Features Meta Box Callback
 */
function hfe_property_features_callback($post) {
    wp_nonce_field('hfe_save_property_features', 'hfe_property_features_nonce');

    $features = get_post_meta($post->ID, '_hfe_features', true);
    ?>
    <textarea name="hfe_features" id="hfe_features" rows="8" style="width: 100%;"><?php echo esc_textarea($features); ?></textarea>
    <p class="description"><?php _e('Enter property features, one per line (e.g., "Luxury Living Room", "Italian Furniture", "Smart Home System")', 'hfe-properties'); ?></p>
    <?php
}

/**
 * Property Banner Meta Box Callback
 */
function hfe_property_banner_callback($post) {
    wp_nonce_field('hfe_save_property_banner', 'hfe_property_banner_nonce');

    $banner_id = get_post_meta($post->ID, '_hfe_banner_image_id', true);
    $banner_url = get_post_meta($post->ID, '_hfe_banner_image', true);

    // Default banner
    if (!$banner_url) {
        $banner_url = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';
    }
    ?>
    <div class="hfe-banner-upload-container">
        <div class="hfe-banner-preview" id="hfe-banner-preview">
            <?php if ($banner_url) : ?>
                <img src="<?php echo esc_url($banner_url); ?>" style="max-width: 100%; height: auto; display: block;" />
            <?php endif; ?>
        </div>
        <input type="hidden" name="hfe_banner_image_id" id="hfe_banner_image_id" value="<?php echo esc_attr($banner_id); ?>" />
        <input type="hidden" name="hfe_banner_image" id="hfe_banner_image" value="<?php echo esc_attr($banner_url); ?>" />

        <p>
            <button type="button" class="button button-primary" id="hfe-upload-banner-btn">
                <?php _e('Upload Banner Image', 'hfe-properties'); ?>
            </button>
            <?php if ($banner_id) : ?>
                <button type="button" class="button" id="hfe-remove-banner-btn">
                    <?php _e('Remove', 'hfe-properties'); ?>
                </button>
            <?php endif; ?>
        </p>
        <p class="description">
            <?php _e('Banner image displayed at the top of the property detail page. Recommended size: 1920x400px', 'hfe-properties'); ?>
        </p>
        <?php if (!$banner_id) : ?>
            <p class="description">
                <em><?php _e('Using default banner. Upload a custom banner to override.', 'hfe-properties'); ?></em>
            </p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save Property Meta Data
 */
function hfe_save_property_meta($post_id) {
    // Check if nonces are set
    $details_nonce = isset($_POST['hfe_property_details_nonce']) ? $_POST['hfe_property_details_nonce'] : '';
    $gallery_nonce = isset($_POST['hfe_property_gallery_nonce']) ? $_POST['hfe_property_gallery_nonce'] : '';
    $features_nonce = isset($_POST['hfe_property_features_nonce']) ? $_POST['hfe_property_features_nonce'] : '';
    $banner_nonce = isset($_POST['hfe_property_banner_nonce']) ? $_POST['hfe_property_banner_nonce'] : '';

    // Verify nonces
    if (!wp_verify_nonce($details_nonce, 'hfe_save_property_details') &&
        !wp_verify_nonce($gallery_nonce, 'hfe_save_property_gallery') &&
        !wp_verify_nonce($features_nonce, 'hfe_save_property_features') &&
        !wp_verify_nonce($banner_nonce, 'hfe_save_property_banner')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save property details
    $fields = array(
        'hfe_price' => 'sanitize_text_field',
        'hfe_price_currency' => 'sanitize_text_field',
        'hfe_size' => 'sanitize_text_field',
        'hfe_bedrooms' => 'sanitize_text_field',
        'hfe_bathrooms' => 'sanitize_text_field',
        'hfe_floor' => 'sanitize_text_field',
        'hfe_terrace' => 'sanitize_text_field',
        'hfe_parking' => 'sanitize_text_field',
        'hfe_status' => 'sanitize_text_field',
        'hfe_availability' => 'sanitize_text_field',
        'hfe_year_built' => 'sanitize_text_field',
        'hfe_gallery' => 'sanitize_text_field',
        'hfe_features' => 'sanitize_textarea_field',
        'hfe_banner_image_id' => 'absint',
        'hfe_banner_image' => 'esc_url_raw',
    );

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, call_user_func($sanitize_callback, $_POST[$field]));
        }
    }
}
add_action('save_post_hfe_property', 'hfe_save_property_meta');
