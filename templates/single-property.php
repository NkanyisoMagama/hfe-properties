<?php
/**
 * Single Property Template
 * Template for displaying single property details
 */

get_header();
?>

<style>
.elementor-location-footer {
    display: none;
}

.hfe-single-property {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.hfe-property-featured-image {
    width: 100vw;
    height: 100vh;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
    overflow: hidden;
}

.hfe-property-featured-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

/* Availability badge colors */
.hfe-availability-available {
    background-color: #10b981 !important;
    color: #ffffff !important;
}

.hfe-availability-sold {
    background-color: #ef4444 !important;
    color: #ffffff !important;
}

.hfe-availability-purchased {
    background-color: #f59e0b !important;
    color: #ffffff !important;
}

.hfe-availability-rent {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
}

.hfe-availability-pending {
    background-color: #6b7280 !important;
    color: #ffffff !important;
}
</style>

<?php
while (have_posts()) : the_post();
    $property_id = get_the_ID();
    $meta = hfe_get_property_meta($property_id);
    $locations = wp_get_post_terms($property_id, 'property_location');
    $property_types = wp_get_post_terms($property_id, 'property_type');
?>

<article id="property-<?php the_ID(); ?>" <?php post_class('hfe-single-property'); ?>>

    <div class="hfe-property-featured-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full'); ?>
        <?php endif; ?>
    </div>

    <div class="hfe-property-header">
        <div class="hfe-container">
            <?php if (!empty($locations)) : ?>
                <div class="hfe-property-location-badge">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                        <path d="M8 0C5.24 0 3 2.24 3 5c0 3.75 5 9 5 9s5-5.25 5-9c0-2.76-2.24-5-5-5zm0 7c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
                    </svg>
                    <?php echo esc_html($locations[0]->name); ?>
                </div>
            <?php endif; ?>

            <h1 class="hfe-property-main-title"><?php the_title(); ?></h1>

            <div class="hfe-property-meta-header">
                <?php if (!empty($property_types)) : ?>
                    <span class="hfe-property-type"><?php echo esc_html($property_types[0]->name); ?></span>
                <?php endif; ?>
                <?php if ($meta['availability']) : ?>
                    <?php
                    $availability_labels = array(
                        'available' => __('For Sale', 'hfe-properties'),
                        'sold' => __('Sold', 'hfe-properties'),
                        'purchased' => __('Purchased', 'hfe-properties'),
                        'rent' => __('For Rent', 'hfe-properties'),
                        'pending' => __('Pending', 'hfe-properties'),
                    );
                    $availability_label = isset($availability_labels[$meta['availability']]) ? $availability_labels[$meta['availability']] : ucfirst($meta['availability']);
                    ?>
                    <span class="hfe-property-status-badge hfe-availability-<?php echo esc_attr($meta['availability']); ?>">
                        <?php echo $availability_label; ?>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($meta['price']) : ?>
                <div class="hfe-property-main-price">
                    <?php echo hfe_get_property_price($property_id); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="hfe-container">
        <div class="hfe-property-layout">
            <div class="hfe-property-main-content">

                <!-- Property Stats -->
                <?php if ($meta['size'] || $meta['bedrooms'] || $meta['bathrooms'] || $meta['floor'] || $meta['parking'] || $meta['year_built']) : ?>
                <div class="hfe-property-stats">
                    <?php if ($meta['size']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 3v18h18V3H3zm16 16H5V5h14v14z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['size']); ?> m²</span>
                                <span class="hfe-stat-label"><?php _e('Total Area', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($meta['bedrooms']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 13h12v6H4v-6zm0-3V8c0-1.1.9-2 2-2h8c1.1 0 2 .9 2 2v2h2c.55 0 1 .45 1 1v8c0 .55-.45 1-1 1H3c-.55 0-1-.45-1-1v-8c0-.55.45-1 1-1h1z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['bedrooms']); ?></span>
                                <span class="hfe-stat-label"><?php _e('Bedrooms', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($meta['bathrooms']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3 10c0-3.86 3.14-7 7-7 .55 0 1 .45 1 1s-.45 1-1 1c-2.76 0-5 2.24-5 5H3zm18 7H3v3h18v-3zm0-3c.55 0 1-.45 1-1s-.45-1-1-1H3c-.55 0-1 .45-1 1s.45 1 1 1h18z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['bathrooms']); ?></span>
                                <span class="hfe-stat-label"><?php _e('Bathrooms', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($meta['floor']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 7v10l10 5 10-5V7L12 2zm0 2.2l7.5 3.75L12 11.7 4.5 7.95 12 4.2zM4 9.47l7 3.5v7.03l-7-3.5V9.47zm16 7.03l-7 3.5V12.97l7-3.5v7.03z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['floor']); ?></span>
                                <span class="hfe-stat-label"><?php _e('Floor', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($meta['parking']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M13 3H6v18h4v-6h3c3.31 0 6-2.69 6-6s-2.69-6-6-6zm.2 8H10V7h3.2c1.1 0 2 .9 2 2s-.9 2-2 2z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['parking']); ?></span>
                                <span class="hfe-stat-label"><?php _e('Parking', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($meta['year_built']) : ?>
                        <div class="hfe-stat-item">
                            <div class="hfe-stat-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L2 12h3v8h6v-6h2v6h6v-8h3L12 2z"/>
                                </svg>
                            </div>
                            <div class="hfe-stat-content">
                                <span class="hfe-stat-value"><?php echo esc_html($meta['year_built']); ?></span>
                                <span class="hfe-stat-label"><?php _e('Year Built', 'hfe-properties'); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <?php
                $content = get_the_content();
                if (!empty(trim(strip_tags($content)))) :
                ?>
                <div class="hfe-property-description">
                    <h2><?php _e('Property Description', 'hfe-properties'); ?></h2>
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>

                <!-- Features -->
                <?php hfe_property_features($property_id); ?>

                <!-- Gallery -->
                <?php hfe_property_gallery($property_id); ?>

            </div>

            <!-- Sidebar -->
            <div class="hfe-property-sidebar">
                <?php hfe_property_contact_form($property_id); ?>

                <!-- Additional Info -->
                <div class="hfe-property-additional-info">
                    <h3><?php _e('Property Details', 'hfe-properties'); ?></h3>
                    <ul class="hfe-info-list">
                        <li>
                            <span class="hfe-info-label"><?php _e('Property ID:', 'hfe-properties'); ?></span>
                            <span class="hfe-info-value"><?php echo esc_html($property_id); ?></span>
                        </li>
                        <?php if ($meta['status']) : ?>
                            <li>
                                <span class="hfe-info-label"><?php _e('Status:', 'hfe-properties'); ?></span>
                                <span class="hfe-info-value">
                                    <?php echo $meta['status'] === 'rent' ? __('For Rent', 'hfe-properties') : __('For Sale', 'hfe-properties'); ?>
                                </span>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($property_types)) : ?>
                            <li>
                                <span class="hfe-info-label"><?php _e('Type:', 'hfe-properties'); ?></span>
                                <span class="hfe-info-value"><?php echo esc_html($property_types[0]->name); ?></span>
                            </li>
                        <?php endif; ?>
                        <?php if ($meta['availability']) : ?>
                            <li>
                                <span class="hfe-info-label"><?php _e('Availability:', 'hfe-properties'); ?></span>
                                <span class="hfe-info-value"><?php echo esc_html(ucfirst($meta['availability'])); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Share -->
                <div class="hfe-property-share">
                    <h3><?php _e('Share this property', 'hfe-properties'); ?></h3>
                    <div class="hfe-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="hfe-share-button hfe-share-facebook">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M18 0H2C.9 0 0 .9 0 2v16c0 1.1.9 2 2 2h8v-7H8V9h2V7c0-2.2 1.8-4 4-4h2v2h-2c-.55 0-1 .45-1 1v2h3l-.5 2H13v7h5c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" class="hfe-share-button hfe-share-twitter">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M20 3.8c-.7.3-1.5.5-2.4.6.8-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1-.7-.8-1.8-1.3-3-1.3-2.3 0-4.1 1.8-4.1 4.1 0 .3 0 .6.1.9C6.4 6.7 3.4 5.1 1.4 2.6c-.3.6-.5 1.2-.5 1.9 0 1.4.7 2.7 1.8 3.4-.7 0-1.3-.2-1.8-.5v.1c0 2 1.4 3.6 3.3 4-.3.1-.7.1-1.1.1-.3 0-.5 0-.8-.1.5 1.6 2 2.8 3.8 2.8-1.4 1.1-3.2 1.8-5.1 1.8-.3 0-.7 0-1-.1 1.8 1.2 4 1.8 6.3 1.8 7.5 0 11.7-6.2 11.7-11.7v-.5c.8-.6 1.5-1.3 2-2.1z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" class="hfe-share-button hfe-share-linkedin">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M19 0H1C.4 0 0 .4 0 1v18c0 .6.4 1 1 1h18c.6 0 1-.4 1-1V1c0-.6-.4-1-1-1zM6 17H3V8h3v9zM4.5 6.3c-1 0-1.8-.8-1.8-1.8s.8-1.8 1.8-1.8 1.8.8 1.8 1.8-.8 1.8-1.8 1.8zM17 17h-3v-4.4c0-1.1 0-2.5-1.5-2.5s-1.7 1.2-1.7 2.4V17H8V8h2.8v1.2h.1c.4-.7 1.3-1.5 2.7-1.5 2.9 0 3.4 1.9 3.4 4.4V17z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</article>

<?php
endwhile;

get_footer();
