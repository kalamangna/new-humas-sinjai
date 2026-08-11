<script>
    // Mobile Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            if (!isHidden) {
                document.body.classList.add('overflow-hidden');
            } else {
                document.body.classList.remove('overflow-hidden');
            }
        });
    }

    // Mobile Categories Toggle
    const catBtn = document.getElementById('mobile-categories-button');
    const catMenu = document.getElementById('mobile-categories-menu');
    const catArrow = document.getElementById('mobile-categories-arrow');
    if (catBtn && catMenu) {
        catBtn.addEventListener('click', () => {
            catMenu.classList.toggle('hidden');
            catArrow.classList.toggle('rotate-180');
        });
    }

    // Mobile Profile Toggle
    const profileBtn = document.getElementById('mobile-profile-button');
    const profileMenu = document.getElementById('mobile-profile-menu');
    const profileArrow = document.getElementById('mobile-profile-arrow');
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
            profileArrow.classList.toggle('rotate-180');
        });
    }

    // Mobile Live Toggle
    const liveBtn = document.getElementById('mobile-live-button');
    const liveMenu = document.getElementById('mobile-live-menu');
    const liveArrow = document.getElementById('mobile-live-arrow');
    if (liveBtn && liveMenu) {
        liveBtn.addEventListener('click', () => {
            liveMenu.classList.toggle('hidden');
            liveArrow.classList.toggle('rotate-180');
        });
    }
</script>

<?= $this->renderSection('scripts') ?>
<script>
    // Delay non-critical accessibility widget until idle/interaction to boost LCP & reduce TBT
    window.addEventListener('DOMContentLoaded', () => {
        const loadUserWay = () => {
            if (window.userwayLoaded) return;
            window.userwayLoaded = true;
            const s = document.createElement('script');
            s.src = 'https://cdn.userway.org/widget.js';
            s.setAttribute('data-account', 'S41ThPrHz4');
            s.setAttribute('data-position', '5');
            s.async = true;
            document.body.appendChild(s);
        };

        if ('requestIdleCallback' in window) {
            requestIdleCallback(loadUserWay, { timeout: 4000 });
        } else {
            setTimeout(loadUserWay, 3000);
        }

        ['pointerdown', 'keydown', 'scroll'].forEach(evt => {
            window.addEventListener(evt, loadUserWay, { once: true, passive: true });
        });
    });
</script>