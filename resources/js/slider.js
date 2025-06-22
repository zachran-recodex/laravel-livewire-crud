// Hero Slider using Swiper.js
import { Swiper } from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/autoplay';
import 'swiper/css/effect-fade';

// Global swiper instance
window.heroSwiper = null;

function initHeroSlider() {
    // Check if Swiper is available
    if (typeof Swiper === 'undefined') {
        console.error('Swiper is not loaded');
        return false;
    }
    
    // Check if swiper container exists
    const swiperContainer = document.querySelector('.hero-swiper');
    if (!swiperContainer) {
        console.log('Swiper container not found');
        return false;
    }
    
    // Check if slides exist
    const slides = swiperContainer.querySelectorAll('.swiper-slide');
    if (!slides.length) {
        console.log('No slides found');
        return false;
    }
    
    try {
        // Destroy existing swiper instance
        if (window.heroSwiper) {
            window.heroSwiper.destroy(true, true);
            window.heroSwiper = null;
        }
        
        // Initialize Swiper
        window.heroSwiper = new Swiper('.hero-swiper', {
            modules: [Navigation, Pagination, Autoplay, EffectFade],
            
            // Slider settings
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            
            // Effect - use slide instead of fade for better compatibility
            effect: 'slide',
            
            // Autoplay
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
                waitForTransition: false,
                stopOnLastSlide: false,
            },
            
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            
            // Pagination bullets
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                type: 'bullets',
            },
            
            // Keyboard control
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            
            // Touch/swipe
            touchRatio: 1,
            touchAngle: 45,
            grabCursor: true,
            
            // Speed and transitions
            speed: 600,
            
            // Events
            on: {
                init: function () {
                    console.log('Swiper initialized successfully');
                    // Force start autoplay
                    this.autoplay.start();
                },
                slideChange: function () {
                    console.log('Slide changed to:', this.activeIndex);
                },
                autoplayStart: function () {
                    console.log('Autoplay started');
                },
                autoplayStop: function () {
                    console.log('Autoplay stopped');
                }
            }
        });
        
        // Ensure autoplay starts after initialization
        setTimeout(() => {
            if (window.heroSwiper && window.heroSwiper.autoplay) {
                window.heroSwiper.autoplay.start();
                console.log('Autoplay manually started');
            }
        }, 500);
        
        console.log('Hero slider initialized successfully');
        return true;
    } catch (error) {
        console.error('Error initializing hero slider:', error);
        return false;
    }
}

// Event listeners with retry mechanism
document.addEventListener('DOMContentLoaded', initHeroSlider);
document.addEventListener('livewire:navigated', () => setTimeout(initHeroSlider, 300));

// Retry mechanism for better Livewire compatibility
function retryHeroSlider() {
    [500, 1000, 2000].forEach(delay => {
        setTimeout(() => {
            if (!window.heroSwiper) {
                const success = initHeroSlider();
                if (success && window.heroSwiper) {
                    // Double check autoplay is running
                    setTimeout(() => {
                        if (window.heroSwiper.autoplay) {
                            window.heroSwiper.autoplay.start();
                        }
                    }, 1000);
                }
            }
        }, delay);
    });
}

// Force autoplay check function
function ensureAutoplay() {
    setTimeout(() => {
        if (window.heroSwiper && window.heroSwiper.autoplay) {
            window.heroSwiper.autoplay.start();
            console.log('Autoplay force started');
        }
    }, 2000);
}

document.addEventListener('DOMContentLoaded', retryHeroSlider);
document.addEventListener('livewire:navigated', retryHeroSlider);

// Additional check for autoplay
document.addEventListener('DOMContentLoaded', ensureAutoplay);
document.addEventListener('livewire:navigated', ensureAutoplay);