/**
 * Smart Property Parser - Frontend JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        /**
         * Parse Property Button Click Handler
         */
        $('#hfe-parse-property-btn').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $textarea = $('#hfe_property_text_input');
            const text = $textarea.val().trim();

            // Validation
            if (text === '') {
                showError('Please enter property details to parse.');
                return;
            }

            // Show loading state
            $button.prop('disabled', true);
            $button.html('<span class="dashicons dashicons-update spin"></span> Parsing...');
            hideMessages();

            // AJAX request to parse text
            $.ajax({
                url: hfeParser.ajax_url,
                type: 'POST',
                data: {
                    action: 'hfe_parse_property_text',
                    nonce: hfeParser.nonce,
                    text: text
                },
                success: function(response) {
                    if (response.success && response.data) {
                        populateFields(response.data);
                        showResults(response.data);
                        $textarea.val(''); // Clear textarea after successful parse
                    } else {
                        showError(response.data || 'Failed to parse property data.');
                    }
                },
                error: function(xhr, status, error) {
                    showError('An error occurred while parsing. Please try again.');
                    console.error('Parse error:', error);
                },
                complete: function() {
                    // Reset button
                    $button.prop('disabled', false);
                    $button.html('<span class="dashicons dashicons-admin-generic"></span> Parse & Auto-Fill Fields');
                }
            });
        });

        /**
         * Populate Form Fields with Parsed Data
         */
        function populateFields(data) {
            // Title
            if (data.title && $('#title').val() === '') {
                $('#title').val(data.title);
            }

            // Description (WordPress editor)
            if (data.description) {
                // Check if using Classic Editor
                if ($('#content').length) {
                    $('#content').val(data.description);
                }
                // Check if using Block Editor (Gutenberg)
                if (wp && wp.data && wp.data.dispatch('core/editor')) {
                    const currentContent = wp.data.select('core/editor').getEditedPostContent();
                    if (!currentContent || currentContent.trim() === '') {
                        wp.data.dispatch('core/editor').editPost({
                            content: data.description
                        });
                    }
                }
            }

            // Price
            if (data.price) {
                $('#hfe_price').val(data.price);
            }

            // Currency
            if (data.currency) {
                $('#hfe_price_currency').val(data.currency);
            }

            // Size
            if (data.size) {
                $('#hfe_size').val(data.size);
            }

            // Bedrooms
            if (data.bedrooms) {
                $('#hfe_bedrooms').val(data.bedrooms);
            }

            // Bathrooms
            if (data.bathrooms) {
                $('#hfe_bathrooms').val(data.bathrooms);
            }

            // Floor
            if (data.floor) {
                $('#hfe_floor').val(data.floor);
            }

            // Terrace
            if (data.terrace) {
                $('#hfe_terrace').val(data.terrace);
            }

            // Parking
            if (data.parking) {
                $('#hfe_parking').val(data.parking);
            }

            // Year Built
            if (data.year_built) {
                $('#hfe_year_built').val(data.year_built);
            }

            // Status
            if (data.status) {
                $('#hfe_status').val(data.status);
            }

            // Availability
            if (data.availability) {
                $('#hfe_availability').val(data.availability);
            }

            // Features
            if (data.features && data.features.length > 0) {
                const currentFeatures = $('#hfe_features').val();
                const newFeatures = data.features.join('\n');

                if (currentFeatures === '') {
                    $('#hfe_features').val(newFeatures);
                } else {
                    $('#hfe_features').val(currentFeatures + '\n' + newFeatures);
                }
            }

            // Highlight populated fields
            highlightPopulatedFields();
        }

        /**
         * Highlight Populated Fields
         */
        function highlightPopulatedFields() {
            const fields = [
                '#hfe_price', '#hfe_size', '#hfe_bedrooms', '#hfe_bathrooms',
                '#hfe_floor', '#hfe_terrace', '#hfe_parking', '#hfe_year_built'
            ];

            fields.forEach(function(selector) {
                const $field = $(selector);
                if ($field.val() !== '') {
                    $field.css({
                        'background-color': '#e7f4e7',
                        'border-color': '#4caf50'
                    });

                    // Remove highlight after 3 seconds
                    setTimeout(function() {
                        $field.css({
                            'background-color': '',
                            'border-color': ''
                        });
                    }, 3000);
                }
            });
        }

        /**
         * Show Parse Results
         */
        function showResults(data) {
            const $resultsList = $('#hfe-parsed-items');
            $resultsList.empty();

            let itemCount = 0;

            // Build results list
            if (data.title) {
                $resultsList.append('<li>Title: <strong>' + escapeHtml(data.title) + '</strong></li>');
                itemCount++;
            }
            if (data.price) {
                const currencySymbol = data.currency === 'EUR' ? '€' : (data.currency === 'USD' ? '$' : '£');
                $resultsList.append('<li>Price: <strong>' + currencySymbol + formatNumber(data.price) + '</strong></li>');
                itemCount++;
            }
            if (data.bedrooms) {
                $resultsList.append('<li>Bedrooms: <strong>' + data.bedrooms + '</strong></li>');
                itemCount++;
            }
            if (data.bathrooms) {
                $resultsList.append('<li>Bathrooms: <strong>' + data.bathrooms + '</strong></li>');
                itemCount++;
            }
            if (data.size) {
                $resultsList.append('<li>Size: <strong>' + data.size + ' m²</strong></li>');
                itemCount++;
            }
            if (data.floor) {
                $resultsList.append('<li>Floor: <strong>' + data.floor + '</strong></li>');
                itemCount++;
            }
            if (data.year_built) {
                $resultsList.append('<li>Year Built: <strong>' + data.year_built + '</strong></li>');
                itemCount++;
            }
            if (data.parking) {
                $resultsList.append('<li>Parking: <strong>' + data.parking + ' space(s)</strong></li>');
                itemCount++;
            }
            if (data.terrace) {
                $resultsList.append('<li>Terrace/Balcony: <strong>' + data.terrace + '</strong></li>');
                itemCount++;
            }
            if (data.status) {
                $resultsList.append('<li>Status: <strong>' + (data.status === 'rent' ? 'For Rent' : 'For Sale') + '</strong></li>');
                itemCount++;
            }
            if (data.features && data.features.length > 0) {
                $resultsList.append('<li>Features: <strong>' + data.features.length + ' detected</strong></li>');
                itemCount++;
            }

            if (itemCount > 0) {
                $resultsList.append('<li style="color: #4caf50; font-weight: bold; margin-top: 8px;">✓ ' + itemCount + ' field(s) populated successfully!</li>');
                $('#hfe-parse-results').slideDown();
            } else {
                showError('No property data could be extracted. Please check your input format.');
            }
        }

        /**
         * Show Error Message
         */
        function showError(message) {
            $('#hfe-error-message').text(message);
            $('#hfe-parse-error').slideDown();

            setTimeout(function() {
                $('#hfe-parse-error').slideUp();
            }, 5000);
        }

        /**
         * Hide Messages
         */
        function hideMessages() {
            $('#hfe-parse-results').hide();
            $('#hfe-parse-error').hide();
        }

        /**
         * Format Number with Thousand Separators
         */
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        /**
         * Escape HTML
         */
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        /**
         * Add spinning animation for loading icon
         */
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .spin {
                animation: spin 1s linear infinite;
                display: inline-block;
            }
        `;
        document.head.appendChild(style);
    });

})(jQuery);
