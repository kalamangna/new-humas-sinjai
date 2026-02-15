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
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            body.classList.toggle('overflow-hidden');
        }
    }

    if (openBtn) openBtn.addEventListener('click', toggleSidebar);
    if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
    if (overlay) overlay.addEventListener('click', toggleSidebar);

    // Auto-dismiss alerts
    document.querySelectorAll('.bg-emerald-50, .bg-red-50').forEach(alert => {
        setTimeout(() => {
            alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => alert.remove(), 500);
        }, 6000);
    });
</script>
<script defer src="https://cdn.userway.org/widget.js" data-account="S41ThPrHz4" data-position="5"></script>