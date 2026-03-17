/**
 * HFE Properties - Frontend JavaScript
 */

(function($) {
    'use strict';

    /**
     * Initialize Swiper Carousel
     */
    function initPropertyCarousel() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        const carousels = document.querySelectorAll('.hfe-properties-carousel');

        carousels.forEach(function(carouselElement) {
            const totalSlides = carouselElement.querySelectorAll('.swiper-slide').length;
            const enableLoop = totalSlides > 3;
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
                    640: { slidesPerView: 1, spaceBetween: 20 },
                    768: { slidesPerView: 2, spaceBetween: 30 },
                    1024: { slidesPerView: 3, spaceBetween: 30 },
                },
            });
        });
    }

    /**
     * SIMPLE POPUP SLIDER - NO EXTERNAL LIBRARIES
     */
    let currentImageIndex = 0;
    let allImages = [];

    function initGalleryPopup() {
        const galleryItems = document.querySelectorAll('.hfe-gallery-item');

        if (galleryItems.length === 0) return;

        // Store all images from data-full-image attribute
        allImages = Array.from(galleryItems).map(item => item.getAttribute('data-full-image'));

        // Add click handlers
        galleryItems.forEach(function(item, index) {
            // Click event
            item.addEventListener('click', function(e) {
                e.preventDefault();
                currentImageIndex = index;
                openPopup();
            });

            // Keyboard accessibility (Enter or Space key)
            item.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    currentImageIndex = index;
                    openPopup();
                }
            });

            // Add hover cursor style
            item.style.cursor = 'pointer';
        });
    }

    function openPopup() {
        // Create popup HTML
        const popup = document.createElement('div');
        popup.id = 'hfe-image-popup';
        popup.innerHTML = `
            <div class="hfe-popup-overlay"></div>
            <div class="hfe-popup-content">
                <button class="hfe-popup-close">&times;</button>
                <button class="hfe-popup-prev">‹</button>
                <button class="hfe-popup-next">›</button>
                <img src="${allImages[currentImageIndex]}" class="hfe-popup-image">
                <div class="hfe-popup-counter">${currentImageIndex + 1} / ${allImages.length}</div>
            </div>
        `;

        document.body.appendChild(popup);
        document.body.style.overflow = 'hidden';

        // Add CSS if not already added
        if (!document.getElementById('hfe-popup-styles')) {
            addPopupStyles();
        }

        // Event listeners
        popup.querySelector('.hfe-popup-close').addEventListener('click', closePopup);
        popup.querySelector('.hfe-popup-overlay').addEventListener('click', closePopup);
        popup.querySelector('.hfe-popup-prev').addEventListener('click', prevImage);
        popup.querySelector('.hfe-popup-next').addEventListener('click', nextImage);

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyboard);
    }

    function closePopup() {
        const popup = document.getElementById('hfe-image-popup');
        if (popup) {
            popup.remove();
            document.body.style.overflow = '';
            document.removeEventListener('keydown', handleKeyboard);
        }
    }

    function prevImage() {
        currentImageIndex--;
        if (currentImageIndex < 0) {
            currentImageIndex = allImages.length - 1;
        }
        updatePopupImage();
    }

    function nextImage() {
        currentImageIndex++;
        if (currentImageIndex >= allImages.length) {
            currentImageIndex = 0;
        }
        updatePopupImage();
    }

    function updatePopupImage() {
        const popup = document.getElementById('hfe-image-popup');
        if (popup) {
            popup.querySelector('.hfe-popup-image').src = allImages[currentImageIndex];
            popup.querySelector('.hfe-popup-counter').textContent = `${currentImageIndex + 1} / ${allImages.length}`;
        }
    }

    function handleKeyboard(e) {
        if (e.key === 'Escape') closePopup();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'ArrowRight') nextImage();
    }

    function addPopupStyles() {
        const style = document.createElement('style');
        style.id = 'hfe-popup-styles';
        style.textContent = `
            #hfe-image-popup {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 999999;
            }
            .hfe-popup-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.95);
            }
            .hfe-popup-content {
                position: relative;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .hfe-popup-image {
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
            }
            .hfe-popup-close {
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                font-size: 40px;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                cursor: pointer;
                line-height: 1;
                transition: background 0.3s;
            }
            .hfe-popup-close:hover {
                background: rgba(255, 255, 255, 0.3);
            }
            .hfe-popup-prev,
            .hfe-popup-next {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                font-size: 60px;
                width: 60px;
                height: 60px;
                border-radius: 50%;
                cursor: pointer;
                transition: background 0.3s;
                line-height: 1;
            }
            .hfe-popup-prev:hover,
            .hfe-popup-next:hover {
                background: rgba(205, 140, 102, 0.8);
            }
            .hfe-popup-prev {
                left: 20px;
            }
            .hfe-popup-next {
                right: 20px;
            }
            .hfe-popup-counter {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px 20px;
                border-radius: 20px;
                font-size: 16px;
                font-weight: 600;
            }
            @media (max-width: 768px) {
                .hfe-popup-prev,
                .hfe-popup-next {
                    width: 50px;
                    height: 50px;
                    font-size: 40px;
                }
                .hfe-popup-close {
                    width: 40px;
                    height: 40px;
                    font-size: 30px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Property Card Animations
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

    /**
     * Initialize on DOM Ready
     */
    $(document).ready(function() {
        initPropertyCarousel();
        initGalleryPopup();
        initPropertyCardAnimations();
    });

})(jQuery);
