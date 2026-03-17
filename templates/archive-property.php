<?php
/**
 * Archive Property Template
 */

get_header();
?>

<div class="hfe-archive-properties">
    <div class="hfe-container">
        <header class="hfe-archive-header">
            <?php
            if (is_tax('property_location')) {
                ?>
                <h1 class="hfe-archive-title">
                    <?php _e('Properties in', 'hfe-properties'); ?> <?php single_term_title(); ?>
                </h1>
                <?php
            } elseif (is_tax('property_type')) {
                ?>
                <h1 class="hfe-archive-title">
                    <?php single_term_title(); ?>
                </h1>
                <?php
            } else {
                ?>
                <h1 class="hfe-archive-title">
                    <?php _e('All Properties', 'hfe-properties'); ?>
                </h1>
                <?php
            }

            if (term_description()) {
                echo '<div class="hfe-archive-description">' . term_description() . '</div>';
            }
            ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="hfe-properties-grid hfe-grid-columns-3">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $property_id = get_the_ID();
                    $meta = hfe_get_property_meta($property_id);
                    $locations = wp_get_post_terms($property_id, 'property_location');

                    $currency_symbols = array(
                        'EUR' => '€',
                        'USD' => '$',
                        'GBP' => '£',
                    );
                    $currency_symbol = isset($currency_symbols[$meta['currency']]) ? $currency_symbols[$meta['currency']] : $meta['currency'];
                    ?>
                    <div class="hfe-property-card">
                        <?php if ($meta['availability'] === 'sold') : ?>
                            <div class="hfe-property-badge hfe-badge-sold">
                                <?php echo $meta['status'] === 'rent' ? __('Rented', 'hfe-properties') : __('Sold', 'hfe-properties'); ?>
                            </div>
                        <?php elseif ($meta['availability'] === 'pending') : ?>
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
                                    <?php echo $meta['status'] === 'rent' ? __('For Rent', 'hfe-properties') : __('For Sale', 'hfe-properties'); ?>
                                </span>
                            </div>

                            <h3 class="hfe-property-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <?php if ($meta['price']) : ?>
                                <div class="hfe-property-price">
                                    <?php echo esc_html($currency_symbol . number_format($meta['price'], 0, ',', '.')); ?>
                                    <?php if ($meta['status'] === 'rent') : ?>
                                        <span class="hfe-price-period"><?php _e('/month', 'hfe-properties'); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="hfe-property-meta">
                                <?php if ($meta['bedrooms']) : ?>
                                    <span class="hfe-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                            <path d="M3 9h10v4H3V9zm0-2V5c0-1.1.9-2 2-2h6c1.1 0 2 .9 2 2v2h1c.55 0 1 .45 1 1v6c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1V8c0-.55.45-1 1-1h1z"/>
                                        </svg>
                                        <?php echo esc_html($meta['bedrooms']); ?> <?php _e('Beds', 'hfe-properties'); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($meta['bathrooms']) : ?>
                                    <span class="hfe-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                            <path d="M2 7c0-2.76 2.24-5 5-5 .55 0 1 .45 1 1s-.45 1-1 1C5.35 4 4 5.35 4 7H2zm12 5H2v2h12v-2zm0-2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1h12z"/>
                                        </svg>
                                        <?php echo esc_html($meta['bathrooms']); ?> <?php _e('Baths', 'hfe-properties'); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($meta['size']) : ?>
                                    <span class="hfe-meta-item">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                            <path d="M2 2v12h12V2H2zm10 10H4V4h8v8z"/>
                                        </svg>
                                        <?php echo esc_html($meta['size']); ?> m²
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
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Previous', 'hfe-properties'),
                'next_text' => __('Next &raquo;', 'hfe-properties'),
            ));
            ?>

        <?php else : ?>
            <div class="hfe-no-properties">
                <p><?php _e('No properties found.', 'hfe-properties'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();
