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
<!-- Widget Aksesibilitas Sinjai -->
<script src="https://cdn.jsdelivr.net/gh/kalamangna/a11y-sinjaikab@main/dist/widget.min.js" data-position="bottom-left" data-lang="id" data-color="#0056b3" defer></script>