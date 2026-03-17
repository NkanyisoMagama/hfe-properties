/**
 * Property Filters - Frontend JavaScript
 */
(function($) {
    'use strict';

    let currentFilters = {
        status: 'sale',
        availability: '',
        limit: 12,
        view: 'grid',
        sort: 'date_desc'
    };

    $(document).ready(function() {
        initializeFilters();
    });

    /**
     * Initialize filters
     */
    function initializeFilters() {
        const $wrapper = $('.hfe-property-filters-wrapper');
        const $container = $('#hfe-properties-container');

        if (!$wrapper.length || !$container.length) {
            return;
        }

        // Get initial values
        currentFilters.status = $wrapper.data('status') || 'sale';
        currentFilters.availability = $wrapper.data('availability') || '';
        currentFilters.limit = $container.data('default-limit') || 12;
        currentFilters.view = $container.data('default-view') || 'grid';

        // Update results count initially
        updateResultsCount();

        // Limit buttons
        $('.hfe-limit-btn').on('click', function() {
            $('.hfe-limit-btn').removeClass('active');
            $(this).addClass('active');
            currentFilters.limit = $(this).data('limit');
            loadProperties();
        });

        // Sort dropdown
        $('#hfe-sort-select').on('change', function() {
            currentFilters.sort = $(this).val();
            loadProperties();
        });

        // View type toggle
        $('.hfe-view-btn').on('click', function() {
            $('.hfe-view-btn').removeClass('active');
            $(this).addClass('active');
            currentFilters.view = $(this).data('view');
            loadProperties();
        });
    }

    /**
     * Load properties via AJAX
     */
    function loadProperties() {
        const $container = $('#hfe-properties-container');
        const $spinner = $('.hfe-loading-spinner');
        const $resultsText = $('.hfe-results-text');

        // Show loading
        $spinner.show();
        $resultsText.text('Loading...');
        $container.css('opacity', '0.5');

        $.ajax({
            url: hfeFilters.ajax_url,
            type: 'POST',
            data: {
                action: 'hfe_load_filtered_properties',
                nonce: hfeFilters.nonce,
                status: currentFilters.status,
                availability: currentFilters.availability,
                limit: currentFilters.limit,
                view: currentFilters.view,
                sort: currentFilters.sort
            },
            success: function(response) {
                if (response.success && response.data.html) {
                    $container.html(response.data.html);

                    // Reinitialize carousel if view is carousel
                    if (currentFilters.view === 'carousel') {
                        setTimeout(function() {
                            initPropertyCarousel();
                        }, 100);
                    }

                    // Reinitialize card animations
                    setTimeout(function() {
                        initPropertyCardAnimations();
                    }, 100);

                    // Update results count
                    updateResultsCount();

                    // Smooth scroll to top of results
                    $('html, body').animate({
                        scrollTop: $container.offset().top - 100
                    }, 400);
                } else {
                    $container.html('<div class="hfe-no-properties"><p>Failed to load properties. Please try again.</p></div>');
                }
            },
            error: function() {
                $container.html('<div class="hfe-no-properties"><p>An error occurred. Please refresh the page.</p></div>');
            },
            complete: function() {
                $spinner.hide();
                $container.css('opacity', '1');
            }
        });
    }

    /**
     * Update results count text
     */
    function updateResultsCount() {
        const $container = $('#hfe-properties-container');
        const $resultsText = $('.hfe-results-text');

        // Count property cards
        const count = $container.find('.hfe-property-card').length;

        if (count === 0) {
            $resultsText.text('No properties found');
        } else if (count === 1) {
            $resultsText.text('Showing 1 property');
        } else {
            const limitText = currentFilters.limit === -1 ? 'all' : currentFilters.limit;
            $resultsText.text(`Showing ${count} ${count === 1 ? 'property' : 'properties'}`);
        }
    }

    /**
     * Initialize Property Carousel (from main script)
     */
    function initPropertyCarousel() {
        if (typeof Swiper === 'undefined') {
            console.error('Swiper is not loaded');
            return;
        }

        const carousels = document.querySelectorAll('.hfe-properties-carousel');

        carousels.forEach(function(carouselElement) {
            // Destroy existing swiper instance if any
            if (carouselElement.swiper) {
                carouselElement.swiper.destroy(true, true);
            }

            // Count total slides
            const totalSlides = carouselElement.querySelectorAll('.swiper-slide').length;

            // Only enable loop if there are more than 3 slides
            const enableLoop = totalSlides > 3;

            // Only enable autoplay if loop is enabled
            const autoplayConfig = enableLoop ? {
                delay: 5000,
                disableOnInteraction: false,
            } : false;

            new Swiper(carouselElement, {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: enableLoop,
                autoplay: autoplayConfig,
                pagination: {
                    el: carouselElement.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: carouselElement.querySelector('.swiper-button-next'),
                    prevEl: carouselElement.querySelector('.swiper-button-prev'),
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
            });
        });
    }

    /**
     * Initialize Property Card Animations
     */
    function initPropertyCardAnimations() {
        const cards = document.querySelectorAll('.hfe-property-card');

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, {
                threshold: 0.1
            });

            cards.forEach(function(card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        }
    }

})(jQuery);
