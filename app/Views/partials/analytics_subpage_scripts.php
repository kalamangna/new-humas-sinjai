<script>
    window.AnalyticsHelpers = (function() {
        function escapeHtml(value) {
            if (value === null || value === undefined) return '';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function showError(message) {
            const container = document.getElementById('ga-error-container');
            const messageEl = document.getElementById('ga-error-message');
            if (container && messageEl) {
                messageEl.textContent = message;
                container.classList.remove('hidden');
            }
        }

        function hideError() {
            const container = document.getElementById('ga-error-container');
            if (container) container.classList.add('hidden');
        }

        function checkApiStatus() {
            fetch('<?= base_url('api/analytics/api-status') ?>')
                .then(r => r.json())
                .then(data => {
                    if (data.is_throttled) {
                        showError(data.message);
                    } else {
                        hideError();
                    }
                })
                .catch(() => {
                    showError('Gagal memverifikasi status koneksi API Google Analytics.');
                });
        }

        function formatPeriodLabel(startDate, endDate) {
            if (!startDate || startDate === '2023-07-01') {
                return 'Semua Waktu';
            }
            if (startDate === '7daysAgo') return '7 Hari Terakhir';
            if (startDate === '30daysAgo') return '30 Hari Terakhir';

            const endLabel = (!endDate || endDate === 'today') ? 'Hari Ini' : endDate;
            if (startDate === endLabel) return 'Hari Ini';
            return `${startDate} sampai ${endLabel}`;
        }

        function initSubpageContext() {
            const params = new URLSearchParams(window.location.search);
            const startDate = params.get('start_date');
            const endDate = params.get('end_date');

            const backLink = document.getElementById('back-to-overview-link');
            if (backLink && (startDate || endDate)) {
                const backUrl = new URL(backLink.href);
                if (startDate) backUrl.searchParams.set('start_date', startDate);
                if (endDate) backUrl.searchParams.set('end_date', endDate);
                backLink.href = backUrl.toString();
            }

            const periodBadge = document.getElementById('active-period-badge');
            if (periodBadge) {
                periodBadge.textContent = formatPeriodLabel(startDate, endDate);
            }
        }

        function buildApiUrl(baseUrl) {
            const params = new URLSearchParams(window.location.search);
            const url = new URL(baseUrl);
            if (params.has('start_date')) url.searchParams.set('start_date', params.get('start_date'));
            if (params.has('end_date')) url.searchParams.set('end_date', params.get('end_date'));
            return url;
        }

        async function fetchJson(url) {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Gagal terhubung ke server analitik.');
            }
            const data = await response.json();
            if (data && !Array.isArray(data) && data.status === 'error') {
                throw new Error(data.message || 'Terjadi kesalahan saat mengambil data analitik.');
            }
            return data;
        }

        return {
            escapeHtml,
            showError,
            hideError,
            checkApiStatus,
            formatPeriodLabel,
            initSubpageContext,
            buildApiUrl,
            fetchJson
        };
    })();
</script>
