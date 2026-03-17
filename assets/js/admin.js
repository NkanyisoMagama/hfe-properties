/**
 * HFE Properties - Admin JavaScript
 */

(function($) {
    'use strict';

    /**
     * Gallery Image Upload
     */
    function initGalleryUpload() {
        let galleryFrame;
        const galleryContainer = $('#hfe-gallery-images');
        const galleryInput = $('#hfe_gallery');

        // Add images button
        $('#hfe-add-gallery-images').on('click', function(e) {
            e.preventDefault();

            // If the media frame already exists, reopen it.
            if (galleryFrame) {
                galleryFrame.open();
                return;
            }

            // Create a new media frame
            galleryFrame = wp.media({
                title: 'Select Property Images',
                button: {
                    text: 'Add to Gallery'
                },
                multiple: true
            });

            // When images are selected
            galleryFrame.on('select', function() {
                const selection = galleryFrame.state().get('selection');
                let galleryIds = galleryInput.val() ? galleryInput.val().split(',') : [];

                selection.map(function(attachment) {
                    attachment = attachment.toJSON();

                    // Add to array if not already there
                    if (galleryIds.indexOf(attachment.id.toString()) === -1) {
                        galleryIds.push(attachment.id);

                        // Add image to gallery preview
                        const imageHtml = `
                            <div class="hfe-gallery-image" data-id="${attachment.id}">
                                <img src="${attachment.sizes.thumbnail.url}" />
                                <span class="hfe-remove-image">&times;</span>
                            </div>
                        `;
                        galleryContainer.append(imageHtml);
                    }
                });

                // Update hidden input
                galleryInput.val(galleryIds.join(','));
            });

            // Open the modal
            galleryFrame.open();
        });

        // Remove image
        $(document).on('click', '.hfe-remove-image', function(e) {
            e.preventDefault();
            const imageDiv = $(this).parent();
            const imageId = imageDiv.data('id');
            let galleryIds = galleryInput.val().split(',');

            // Remove from array
            galleryIds = galleryIds.filter(function(id) {
                return id !== imageId.toString();
            });

            // Update input and remove from DOM
            galleryInput.val(galleryIds.join(','));
            imageDiv.fadeOut(300, function() {
                $(this).remove();
            });
        });

        // Make gallery sortable
        if ($.fn.sortable) {
            galleryContainer.sortable({
                items: '.hfe-gallery-image',
                cursor: 'move',
                placeholder: 'hfe-gallery-placeholder',
                update: function() {
                    const galleryIds = [];
                    galleryContainer.find('.hfe-gallery-image').each(function() {
                        galleryIds.push($(this).data('id'));
                    });
                    galleryInput.val(galleryIds.join(','));
                }
            });
        }
    }

    /**
     * Price Currency Symbol Update
     */
    function initPriceCurrencyUpdate() {
        $('#hfe_price_currency').on('change', function() {
            const currency = $(this).val();
            const symbols = {
                'EUR': '€',
                'USD': '$',
                'GBP': '£'
            };

            // You can add visual feedback here if needed
            console.log('Currency changed to: ' + symbols[currency] || currency);
        });
    }

    /**
     * Status and Availability Indicators
     */
    function initStatusIndicators() {
        const statusSelect = $('#hfe_status');
        const availabilitySelect = $('#hfe_availability');

        function updateStatusColor() {
            const status = statusSelect.val();
            statusSelect.css('border-left', status === 'sale' ? '4px solid #4caf50' : '4px solid #CD8C66');
        }

        function updateAvailabilityColor() {
            const availability = availabilitySelect.val();
            let color = '#4caf50'; // available - green

            if (availability === 'pending') {
                color = '#ff9800'; // pending - orange
            } else if (availability === 'sold') {
                color = '#f44336'; // sold - red
            }

            availabilitySelect.css('border-left', '4px solid ' + color);
        }

        if (statusSelect.length) {
            updateStatusColor();
            statusSelect.on('change', updateStatusColor);
        }

        if (availabilitySelect.length) {
            updateAvailabilityColor();
            availabilitySelect.on('change', updateAvailabilityColor);
        }
    }

    /**
     * Auto-format Price Input
     */
    function initPriceFormatting() {
        $('#hfe_price').on('blur', function() {
            const value = parseFloat($(this).val());
            if (!isNaN(value)) {
                $(this).val(value.toFixed(2));
            }
        });
    }

    /**
     * Property Type Description Helper
     */
    function initPropertyTypeHelper() {
        // Add helpful descriptions based on property type
        const typeDescriptions = {
            'apartment': 'Typical features: Bedrooms, bathrooms, shared building',
            'house': 'Typical features: Multiple floors, private garden, parking',
            'studio': 'Typical features: Open plan, compact living',
            'villa': 'Typical features: Luxury, spacious, premium amenities'
        };

        // You can expand this to show contextual help
    }

    /**
     * Form Validation Helper
     */
    function initFormValidation() {
        $('form#post').on('submit', function(e) {
            const postType = $('#post_type').val();

            if (postType === 'hfe_property') {
                const price = $('#hfe_price').val();
                const size = $('#hfe_size').val();

                // Soft validation - warnings only
                if (!price) {
                    console.warn('Price not set for this property');
                }

                if (!size) {
                    console.warn('Size not set for this property');
                }
            }
        });
    }

    /**
     * Banner Image Upload
     */
    function initBannerUpload() {
        let bannerFrame;
        const bannerPreview = $('#hfe-banner-preview');
        const bannerIdInput = $('#hfe_banner_image_id');
        const bannerUrlInput = $('#hfe_banner_image');

        // Upload banner button
        $('#hfe-upload-banner-btn').on('click', function(e) {
            e.preventDefault();

            // If the media frame already exists, reopen it.
            if (bannerFrame) {
                bannerFrame.open();
                return;
            }

            // Create a new media frame
            bannerFrame = wp.media({
                title: 'Select Banner Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            // When an image is selected
            bannerFrame.on('select', function() {
                const attachment = bannerFrame.state().get('selection').first().toJSON();

                // Update preview
                bannerPreview.html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; display: block;" />');

                // Update inputs
                bannerIdInput.val(attachment.id);
                bannerUrlInput.val(attachment.url);

                // Show remove button if not visible
                if ($('#hfe-remove-banner-btn').length === 0) {
                    $('#hfe-upload-banner-btn').after(' <button type="button" class="button" id="hfe-remove-banner-btn">Remove</button>');
                    initBannerRemove();
                }
            });

            // Open the modal
            bannerFrame.open();
        });

        // Initialize remove button
        initBannerRemove();
    }

    /**
     * Remove Banner Image
     */
    function initBannerRemove() {
        $('#hfe-remove-banner-btn').off('click').on('click', function(e) {
            e.preventDefault();

            // Reset to default
            const defaultBanner = 'https://homesforexpats.nl/wp-content/uploads/2025/01/image32.jpg';

            bannerPreview.html('<img src="' + defaultBanner + '" style="max-width: 100%; height: auto; display: block;" />');
            $('#hfe_banner_image_id').val('');
            $('#hfe_banner_image').val(defaultBanner);

            $(this).remove();
        });
    }

    /**
     * Initialize Admin Features
     */
    $(document).ready(function() {
        initGalleryUpload();
        initPriceCurrencyUpdate();
        initStatusIndicators();
        initPriceFormatting();
        initPropertyTypeHelper();
        initFormValidation();
        initBannerUpload();
    });

})(jQuery);
