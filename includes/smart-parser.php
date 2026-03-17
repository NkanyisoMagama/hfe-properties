<?php
/**
 * Smart Property Parser
 * Intelligently extracts property data from unstructured text
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Smart Parser Meta Box
 */
function hfe_add_smart_parser_meta_box() {
    add_meta_box(
        'hfe_smart_parser',
        __('🤖 Smart Property Parser', 'hfe-properties'),
        'hfe_smart_parser_callback',
        'hfe_property',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'hfe_add_smart_parser_meta_box');

/**
 * Smart Parser Meta Box Callback
 */
function hfe_smart_parser_callback($post) {
    wp_nonce_field('hfe_smart_parser_action', 'hfe_smart_parser_nonce');
    ?>
    <div class="hfe-smart-parser-container">
        <p class="description">
            <?php _e('Paste property details in any format. The AI will automatically extract and populate all fields.', 'hfe-properties'); ?>
        </p>

        <textarea
            name="hfe_property_text_input"
            id="hfe_property_text_input"
            rows="12"
            style="width: 100%; font-size: 12px;"
            placeholder="Example:
Beautiful apartment in Amsterdam
3 bedrooms, 2 bathrooms
120 m², 5th floor
Price: €450,000
Year built: 2018
Parking: 1 space
Features: Balcony, Modern kitchen, Hardwood floors"
        ></textarea>

        <p style="margin-top: 10px;">
            <button type="button" class="button button-primary button-large" id="hfe-parse-property-btn" style="width: 100%;">
                <span class="dashicons dashicons-admin-generic" style="margin-top: 3px;"></span>
                <?php _e('Parse & Auto-Fill Fields', 'hfe-properties'); ?>
            </button>
        </p>

        <div id="hfe-parse-results" style="margin-top: 15px; display: none;">
            <div class="notice notice-success" style="padding: 10px; margin: 0;">
                <p style="margin: 0;"><strong><?php _e('Parsing Results:', 'hfe-properties'); ?></strong></p>
                <ul id="hfe-parsed-items" style="margin: 10px 0 0 20px; font-size: 12px;"></ul>
                <p style="margin: 10px 0 0 0; font-size: 11px; color: #666;">
                    <?php _e('Review the auto-populated fields below and make any necessary adjustments.', 'hfe-properties'); ?>
                </p>
            </div>
        </div>

        <div id="hfe-parse-error" style="margin-top: 15px; display: none;">
            <div class="notice notice-error" style="padding: 10px; margin: 0;">
                <p style="margin: 0;" id="hfe-error-message"></p>
            </div>
        </div>
    </div>

    <style>
        .hfe-smart-parser-container textarea:focus {
            border-color: #CD8C66;
            box-shadow: 0 0 0 1px #CD8C66;
        }
        #hfe-parse-property-btn:hover {
            background-color: #b57854;
            border-color: #b57854;
        }
    </style>
    <?php
}

/**
 * Parse Property Text (AJAX Handler)
 */
function hfe_parse_property_text() {
    check_ajax_referer('hfe_smart_parser_action', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Insufficient permissions');
    }

    $text = isset($_POST['text']) ? wp_unslash($_POST['text']) : '';

    if (empty($text)) {
        wp_send_json_error('No text provided');
    }

    // Parse the text using intelligent pattern matching
    $parsed_data = hfe_intelligent_parse($text);

    wp_send_json_success($parsed_data);
}
add_action('wp_ajax_hfe_parse_property_text', 'hfe_parse_property_text');

/**
 * Intelligent Property Data Parser
 */
function hfe_intelligent_parse($text) {
    $data = array(
        'title' => '',
        'description' => '',
        'price' => '',
        'currency' => 'EUR',
        'size' => '',
        'bedrooms' => '',
        'bathrooms' => '',
        'floor' => '',
        'terrace' => '',
        'parking' => '',
        'year_built' => '',
        'status' => 'sale',
        'availability' => 'available',
        'features' => array(),
        'location' => '',
    );

    // Normalize text
    $text = trim($text);
    $lines = explode("\n", $text);

    // Extract title (usually first line)
    if (!empty($lines[0])) {
        $first_line = trim($lines[0]);
        if (strlen($first_line) > 10 && strlen($first_line) < 150) {
            $data['title'] = $first_line;
        }
    }

    // Convert to lowercase for pattern matching
    $text_lower = strtolower($text);

    // Price extraction with currency detection
    if (preg_match('/(?:price|asking|cost)?\s*[:\-]?\s*[€$£]\s*([\d,\.]+(?:\s*(?:k|thousand|million))?)/i', $text, $matches)) {
        $data['price'] = hfe_normalize_price($matches[1]);
        // Detect currency
        if (strpos($text, '€') !== false) $data['currency'] = 'EUR';
        elseif (strpos($text, '$') !== false) $data['currency'] = 'USD';
        elseif (strpos($text, '£') !== false) $data['currency'] = 'GBP';
    } elseif (preg_match('/([\d,\.]+)\s*(?:euro|eur|euros)/i', $text, $matches)) {
        $data['price'] = hfe_normalize_price($matches[1]);
        $data['currency'] = 'EUR';
    } elseif (preg_match('/([\d,\.]+)\s*(?:dollar|usd)/i', $text, $matches)) {
        $data['price'] = hfe_normalize_price($matches[1]);
        $data['currency'] = 'USD';
    } elseif (preg_match('/(?:€|eur)\s*([\d,\.]+)/i', $text, $matches)) {
        $data['price'] = hfe_normalize_price($matches[1]);
        $data['currency'] = 'EUR';
    }

    // Bedrooms
    if (preg_match('/(\d+)\s*(?:bed(?:room)?s?|br|slaapkamers?)/i', $text, $matches)) {
        $data['bedrooms'] = $matches[1];
    }

    // Bathrooms
    if (preg_match('/([\d\.]+)\s*(?:bath(?:room)?s?|badkamers?)/i', $text, $matches)) {
        $data['bathrooms'] = $matches[1];
    }

    // Size (m² or sq m)
    if (preg_match('/(\d+)\s*(?:m²|m2|sqm|square\s*meters?|vierkante\s*meters?)/i', $text, $matches)) {
        $data['size'] = $matches[1];
    }

    // Floor
    if (preg_match('/(\d+)(?:st|nd|rd|th|e)?\s*(?:floor|verdieping)/i', $text, $matches)) {
        $data['floor'] = $matches[1] . 'th floor';
    } elseif (preg_match('/(?:ground\s*floor|begane\s*grond)/i', $text, $matches)) {
        $data['floor'] = 'Ground floor';
    }

    // Year built
    if (preg_match('/(?:built|bouwjaar|year)?\s*[:\-]?\s*(19\d{2}|20\d{2})/i', $text, $matches)) {
        $year = intval($matches[1]);
        if ($year >= 1800 && $year <= date('Y') + 5) {
            $data['year_built'] = $year;
        }
    }

    // Parking
    if (preg_match('/(\d+)\s*(?:parking\s*(?:space|spot|place)?s?|parkeerplaats(?:en)?)/i', $text, $matches)) {
        $data['parking'] = $matches[1];
    } elseif (preg_match('/parking|parkeren/i', $text)) {
        $data['parking'] = '1';
    }

    // Terrace/Balcony
    if (preg_match('/(\d+)\s*(?:terrace|balcon(?:y|ies)|terras)/i', $text, $matches)) {
        $data['terrace'] = $matches[1];
    } elseif (preg_match('/(?:terrace|balcony|terras|balkon)/i', $text)) {
        $data['terrace'] = '1';
    }

    // Status (For Sale / For Rent)
    if (preg_match('/(?:for\s*)?rent|rental|te\s*huur|huren/i', $text)) {
        $data['status'] = 'rent';
    } elseif (preg_match('/(?:for\s*)?sale|te\s*koop|kopen/i', $text)) {
        $data['status'] = 'sale';
    }

    // Availability
    if (preg_match('/sold|verkocht/i', $text)) {
        $data['availability'] = 'sold';
    } elseif (preg_match('/pending|option/i', $text)) {
        $data['availability'] = 'pending';
    }

    // Location extraction (common Dutch cities and patterns)
    $dutch_cities = array('Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven',
                          'Tilburg', 'Groningen', 'Almere', 'Breda', 'Nijmegen',
                          'Haarlem', 'Arnhem', 'Zaanstad', 'Amersfoort', 'Apeldoorn');

    foreach ($dutch_cities as $city) {
        if (stripos($text, $city) !== false) {
            $data['location'] = $city;
            break;
        }
    }

    // Features extraction
    $feature_keywords = array(
        'balcony', 'terrace', 'garden', 'garage', 'parking', 'elevator', 'lift',
        'modern', 'renovated', 'furnished', 'unfurnished', 'luxury', 'spacious',
        'central heating', 'air conditioning', 'fireplace', 'hardwood', 'laminate',
        'double glazing', 'alarm', 'storage', 'basement', 'attic', 'roof terrace',
        'canal view', 'city view', 'south facing', 'north facing', 'corner',
        'energy label', 'sustainable', 'solar panels', 'dishwasher', 'washing machine',
        'tuin', 'balkon', 'terras', 'garage', 'lift', 'modern', 'gerenoveerd',
        'gemeubileerd', 'luxe', 'ruim', 'centrale verwarming', 'airco', 'openhaard'
    );

    foreach ($feature_keywords as $keyword) {
        if (stripos($text, $keyword) !== false) {
            $data['features'][] = ucfirst($keyword);
        }
    }

    // Extract description (remove lines that are clearly data points)
    $description_lines = array();
    foreach ($lines as $line) {
        $line = trim($line);
        // Skip short lines, data lines, and the title
        if (strlen($line) < 20 ||
            $line === $data['title'] ||
            preg_match('/^\d+\s*(?:bed|bath|m²|€|\$|£)/i', $line) ||
            preg_match('/^(?:price|bedrooms|bathrooms|size|floor|parking)/i', $line)) {
            continue;
        }
        $description_lines[] = $line;
    }

    if (!empty($description_lines)) {
        $data['description'] = implode("\n\n", $description_lines);
    }

    return $data;
}

/**
 * Normalize price (convert K, thousand, million to numbers)
 */
function hfe_normalize_price($price_str) {
    $price_str = strtolower(str_replace(array(',', ' '), '', $price_str));

    if (strpos($price_str, 'million') !== false || strpos($price_str, 'm') === strlen($price_str) - 1) {
        return floatval($price_str) * 1000000;
    } elseif (strpos($price_str, 'thousand') !== false || strpos($price_str, 'k') === strlen($price_str) - 1) {
        return floatval($price_str) * 1000;
    }

    return floatval($price_str);
}

/**
 * Enqueue Smart Parser Scripts
 */
function hfe_enqueue_smart_parser_scripts($hook) {
    global $post_type;

    if (('post.php' === $hook || 'post-new.php' === $hook) && 'hfe_property' === $post_type) {
        wp_enqueue_script(
            'hfe-smart-parser',
            HFE_PROPERTIES_URL . 'assets/js/smart-parser.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('hfe-smart-parser', 'hfeParser', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('hfe_smart_parser_action'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'hfe_enqueue_smart_parser_scripts');
