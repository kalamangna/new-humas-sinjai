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

    // Auto logout setelah 30 menit tidak aktif dengan sinkronisasi antar tab
    (function () {
        const IDLE_TIMEOUT_MS = <?= \App\Controllers\Auth\Login::IDLE_TIMEOUT_SECONDS * 1000 ?>;
        const PING_INTERVAL_MS = 10 * 60 * 1000;
        const CHECK_INTERVAL_MS = 30 * 1000;
        const ACTIVITY_KEY = 'admin_last_activity';
        const LOGOUT_KEY = 'admin_force_logout';
        const LOGOUT_URL = '<?= base_url('logout?reason=timeout') ?>';
        const LOGIN_URL = '<?= base_url('masuk') ?>';
        const MANUAL_LOGOUT_PATH = new URL('<?= base_url('logout') ?>', window.location.origin).pathname;
        const PING_URL = '<?= base_url('admin/session/ping') ?>';

        let lastActivity = Date.now();
        let lastStorageWrite = 0;
        let lastPingAt = Date.now();
        let isLoggingOut = false;

        try {
            localStorage.setItem(ACTIVITY_KEY, String(lastActivity));
        } catch (e) {}

        function getTimeoutLogoutUrl() {
            const next = window.location.pathname + window.location.search;
            return LOGOUT_URL + '&next=' + encodeURIComponent(next);
        }

        function recordActivity() {
            const now = Date.now();
            lastActivity = now;
            if (now - lastStorageWrite >= 5000) {
                lastStorageWrite = now;
                try {
                    localStorage.setItem(ACTIVITY_KEY, String(now));
                } catch (e) {}
            }
        }

        function triggerLogout() {
            if (isLoggingOut) return;
            isLoggingOut = true;
            try {
                localStorage.setItem(LOGOUT_KEY, 'timeout:' + Date.now());
            } catch (e) {}
            window.location.href = getTimeoutLogoutUrl();
        }

        function attachFrameListeners(frame) {
            try {
                const doc = frame.contentDocument || frame.contentWindow?.document;
                if (doc && doc.documentElement) {
                    ['keydown', 'mousedown', 'input'].forEach(evt => doc.addEventListener(evt, recordActivity, { passive: true }));
                }
            } catch (e) {}
        }

        function bindIframeActivity() {
            document.querySelectorAll('iframe').forEach(frame => {
                if (frame.dataset.activityBound === '1') return;
                frame.dataset.activityBound = '1';
                attachFrameListeners(frame);
                frame.addEventListener('load', () => attachFrameListeners(frame));
            });
        }

        const activityEvents = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
        activityEvents.forEach(evt => document.addEventListener(evt, recordActivity, { passive: true }));

        // Deteksi aktivitas di dalam iframe editor (misal TinyMCE) termasuk yang dimuat asinkron
        window.addEventListener('load', bindIframeActivity);
        if (typeof MutationObserver !== 'undefined' && document.body) {
            const iframeObserver = new MutationObserver(bindIframeActivity);
            iframeObserver.observe(document.body, { childList: true, subtree: true });
        }

        // Sinkronisasi logout manual ke seluruh tab admin yang terbuka
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]');
            if (!link) return;
            try {
                const targetUrl = new URL(link.href, window.location.origin);
                if (targetUrl.origin === window.location.origin && targetUrl.pathname === MANUAL_LOGOUT_PATH && !targetUrl.searchParams.has('reason')) {
                    localStorage.setItem(LOGOUT_KEY, 'manual:' + Date.now());
                }
            } catch (err) {}
        });

        window.addEventListener('storage', (e) => {
            if (e.key === ACTIVITY_KEY && e.newValue) {
                const synced = parseInt(e.newValue, 10);
                if (!Number.isNaN(synced) && synced > lastActivity) {
                    lastActivity = synced;
                }
            } else if (e.key === LOGOUT_KEY && e.newValue) {
                if (!isLoggingOut) {
                    isLoggingOut = true;
                    window.location.href = e.newValue.startsWith('manual:') ? LOGIN_URL : getTimeoutLogoutUrl();
                }
            }
        });

        function checkIdleAndPing() {
            bindIframeActivity();
            const now = Date.now();
            if (now - lastActivity >= IDLE_TIMEOUT_MS) {
                triggerLogout();
                return;
            }

            // Perpanjang sesi di server hanya jika ada aktivitas nyata sejak ping terakhir
            if (lastActivity > lastPingAt && (now - lastPingAt) >= PING_INTERVAL_MS) {
                lastPingAt = now;
                fetch(PING_URL, {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                }).then(res => {
                    if (res.status === 401) {
                        triggerLogout();
                    }
                }).catch(() => {});
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                checkIdleAndPing();
            }
        });

        setInterval(checkIdleAndPing, CHECK_INTERVAL_MS);
    })();
</script>