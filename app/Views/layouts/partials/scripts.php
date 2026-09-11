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
<!-- Accessibility Widget (On-Demand / Click-to-Load to eliminate 3rd-party cookie issues) -->
<div id="userway-wrapper" class="fixed bottom-5 right-5 z-40">
    <button id="userway-trigger-btn"
            type="button"
            onclick="loadUserWayWidget(this)"
            aria-label="Menu Aksesibilitas Disabilitas"
            title="Menu Aksesibilitas"
            class="flex items-center justify-center w-11 h-11 md:w-12 md:h-12 rounded-full bg-blue-800 hover:bg-blue-900 text-white shadow-xl hover:scale-110 active:scale-95 transition-all duration-200 border-2 border-white/30 focus:outline-none focus:ring-4 focus:ring-blue-400">
        <i class="fa-solid fa-universal-access text-xl md:text-2xl"></i>
    </button>
</div>

<script>
    window.userwayLoaded = false;
    function loadUserWayWidget(btn) {
        if (window.userwayLoaded) {
            if (window.UserWay && typeof window.UserWay.openWidget === 'function') {
                window.UserWay.openWidget();
            }
            return;
        }
        window.userwayLoaded = true;

        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-lg"></i>';
            btn.setAttribute('aria-busy', 'true');
        }

        const s = document.createElement('script');
        s.src = 'https://cdn.userway.org/widget.js';
        s.setAttribute('data-account', 'S41ThPrHz4');
        s.setAttribute('data-position', '5');
        s.async = true;
        s.onload = () => {
            setTimeout(() => {
                const wrapper = document.getElementById('userway-wrapper');
                if (wrapper) wrapper.style.display = 'none';
                if (window.UserWay && typeof window.UserWay.openWidget === 'function') {
                    window.UserWay.openWidget();
                }
            }, 600);
        };
        s.onerror = () => {
            if (btn) {
                btn.innerHTML = '<i class="fa-solid fa-universal-access text-xl md:text-2xl"></i>';
                btn.removeAttribute('aria-busy');
            }
        };
        document.body.appendChild(s);
    }
</script>