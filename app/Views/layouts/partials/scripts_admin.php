<script>
    // Sidebar Toggles
    const openBtn = document.getElementById('open-sidebar');
    const closeBtn = document.getElementById('close-sidebar');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const body = document.body;

    // Persistent state for desktop
    if (localStorage.getItem('sidebar-expanded') === 'false') {
        body.classList.remove('sidebar-expanded');
    }

    function toggleSidebar() {
        if (window.innerWidth >= 1024) {
            // Desktop toggle
            const isExpanded = body.classList.toggle('sidebar-expanded');
            localStorage.setItem('sidebar-expanded', isExpanded);
        } else {
            // Mobile toggle
            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                body.classList.remove('overflow-hidden');
            } else {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                body.classList.add('overflow-hidden');
            }
        }
    }

    if (openBtn) openBtn.addEventListener('click', toggleSidebar);
    if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    // Auto-reset mobile sidebar on viewport expansion
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.add('hidden');
            body.classList.remove('overflow-hidden');
        }
    });

    // Auto-dismiss alerts
    document.querySelectorAll('.flash-alert').forEach(alert => {
        setTimeout(() => {
            alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => alert.remove(), 500);
        }, 6000);
    });
</script>