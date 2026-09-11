document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.getElementById('hero-carousel');
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const nextBtn = document.getElementById('next-slide');
    const prevBtn = document.getElementById('prev-slide');
    let currentSlide = 0;
    let autoPlayTimer = null;

    function preloadSlideImage(slideEl) {
        if (!slideEl) return;
        const img = slideEl.querySelector('img[data-src]');
        if (img) {
            img.src = img.getAttribute('data-src');
            img.removeAttribute('data-src');
        }
        const srcsetImg = slideEl.querySelector('img[data-srcset]');
        if (srcsetImg) {
            srcsetImg.srcset = srcsetImg.getAttribute('data-srcset');
            srcsetImg.removeAttribute('data-srcset');
        }
    }

    function showSlide(index) {
        if (!slides.length) return;
        index = (index + slides.length) % slides.length;

        preloadSlideImage(slides[index]);
        // Preload next slide in advance
        if (slides.length > 1) {
            preloadSlideImage(slides[(index + 1) % slides.length]);
        }

        slides.forEach((s, i) => {
            if (i === index) {
                s.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                s.classList.add('opacity-100', 'z-10');
            } else {
                s.classList.remove('opacity-100', 'z-10');
                s.classList.add('opacity-0', 'z-0', 'pointer-events-none');
            }
        });

        indicators.forEach((ind, i) => {
            const dot = ind.querySelector('span') || ind;
            if (i === index) {
                dot.classList.remove('bg-white/40', 'bg-white/50', 'w-2', 'w-2.5');
                dot.classList.add('bg-blue-600', 'w-6', 'md:w-8');
            } else {
                dot.classList.remove('bg-blue-600', 'w-6', 'md:w-8');
                dot.classList.add('bg-white/50', 'w-2', 'md:w-2.5');
            }
        });

        currentSlide = index;
    }

    function startAutoPlay() {
        if (slides.length <= 1) return;
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 7000);
    }

    function resetAutoPlay() {
        if (slides.length <= 1) return;
        clearInterval(autoPlayTimer);
        autoPlayTimer = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 10000);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            showSlide(currentSlide + 1);
            resetAutoPlay();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            showSlide(currentSlide - 1);
            resetAutoPlay();
        });
    }

    // Indicator direct click navigation
    indicators.forEach((ind, i) => {
        ind.addEventListener('click', () => {
            showSlide(i);
            resetAutoPlay();
        });
    });

    // Touch Swipe Support for Mobile
    if (carousel && slides.length > 1) {
        let touchStartX = 0;
        let touchStartY = 0;

        carousel.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });

        carousel.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].screenX;
            const touchEndY = e.changedTouches[0].screenY;
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;

            // Trigger horizontal swipe if horizontal movement > 40px and dominant over vertical scroll
            if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
                if (diffX < 0) {
                    // Swiped Left -> Next Slide
                    showSlide(currentSlide + 1);
                } else {
                    // Swiped Right -> Previous Slide
                    showSlide(currentSlide - 1);
                }
                resetAutoPlay();
            }
        }, { passive: true });
    }

    // Start initial autoplay
    startAutoPlay();
});
