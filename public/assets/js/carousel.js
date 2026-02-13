document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const nextBtn = document.getElementById('next-slide');
    const prevBtn = document.getElementById('prev-slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((s, i) => {
            if (i === index) {
                s.classList.replace('opacity-0', 'opacity-100');
                s.classList.replace('z-0', 'z-10');
                s.classList.replace('absolute', 'relative');
            } else {
                s.classList.replace('opacity-100', 'opacity-0');
                s.classList.replace('z-10', 'z-0');
                s.classList.replace('relative', 'absolute');
            }
        });

        indicators.forEach((ind, i) => {
            if (i === index) {
                ind.classList.replace('bg-white/40', 'bg-blue-600');
                ind.classList.add('w-8');
            } else {
                ind.classList.replace('bg-blue-600', 'bg-white/40');
                ind.classList.remove('w-8');
            }
        });

        currentSlide = index;
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            let prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        });
    }

    // Auto play
    let autoPlay = setInterval(() => {
        if (slides.length > 1) {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }
    }, 7000);

    // Reset autoplay on interaction
    [nextBtn, prevBtn, ...indicators].forEach(el => {
        if (el) {
            el.addEventListener('click', () => {
                clearInterval(autoPlay);
                autoPlay = setInterval(() => {
                    let next = (currentSlide + 1) % slides.length;
                    showSlide(next);
                }, 10000);
            });
        }
    });
});
