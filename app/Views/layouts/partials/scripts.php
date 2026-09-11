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
<div id="userway-wrapper" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999;">
    <button id="userway-trigger-btn"
            type="button"
            onclick="loadUserWayWidget(this)"
            aria-label="Menu Aksesibilitas Disabilitas"
            title="Menu Aksesibilitas Disabilitas"
            onmouseover="this.style.transform='scale(1.1)'; this.style.backgroundColor='#1d4ed8';"
            onmouseout="this.style.transform='scale(1)'; this.style.backgroundColor='#1e3a8a';"
            style="width: 50px; height: 50px; border-radius: 50%; background-color: #1e3a8a; color: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.2); border: 2.5px solid rgba(255, 255, 255, 0.6); cursor: pointer; transition: transform 0.2s ease, background-color 0.2s ease; outline: none;">
        <i class="fa-solid fa-universal-access" style="font-size: 24px; color: #ffffff;"></i>
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