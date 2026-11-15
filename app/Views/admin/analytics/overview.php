<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Analitik<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-col items-center sm:items-end w-full sm:w-auto">
    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 pr-1 hidden sm:block">Filter Periode</span>
    <div class="relative inline-block text-left w-full sm:w-auto" id="period-dropdown-container">
        <button type="button" id="period-dropdown-btn" class="inline-flex items-center justify-between sm:justify-start h-10 px-4 bg-white border border-slate-200 rounded-xl shadow-sm text-[11px] font-bold text-slate-700 hover:bg-slate-50 transition-colors w-full sm:w-auto">
            <div class="flex items-center">
                <i class="fa-regular fa-fw fa-calendar-days text-blue-800 mr-2 text-xs"></i>
                <span id="selected-period-label">Semua Waktu</span>
            </div>
            <i class="fa-solid fa-fw fa-chevron-down ml-3 text-[9px] opacity-50"></i>
        </button>
        <div id="period-dropdown-menu" class="hidden absolute right-0 mt-2 w-full sm:w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2">
            <div class="py-1">
                <button type="button" onclick="setPeriod('today', 'Hari Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Hari Ini</button>
                <button type="button" onclick="setPeriod('7daysAgo', '7 Hari Terakhir')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">7 Hari Terakhir</button>
                <button type="button" onclick="setPeriod('30daysAgo', '30 Hari Terakhir')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">30 Hari Terakhir</button>
                <button type="button" onclick="setPeriod('thisMonth', 'Bulan Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Bulan Ini</button>
                <button type="button" onclick="setPeriod('thisYear', 'Tahun Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Tahun Ini</button>
                <button type="button" onclick="setPeriod('allTime', 'Semua Waktu')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Semua Waktu</button>
                <button type="button" onclick="toggleCustomDates()" class="w-full text-left px-4 py-2.5 text-xs font-black text-blue-800 hover:bg-blue-50 transition-colors">Rentang Kustom</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Custom Date Inputs -->
<div id="custom-date-container" class="hidden mb-6 bg-white p-4 sm:p-5 rounded-2xl border border-blue-100 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 flex-1">
            <div class="w-full sm:w-auto">
                <label for="start-date" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 pl-1">Tanggal Mulai</label>
                <input type="date" id="start-date" class="h-10 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 outline-none w-full sm:w-auto">
            </div>
            <div class="w-full sm:w-auto">
                <label for="end-date" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 pl-1">Tanggal Selesai</label>
                <input type="date" id="end-date" class="h-10 px-3 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 outline-none w-full sm:w-auto">
            </div>
            <button type="button" onclick="applyCustomDates()" id="apply-custom-btn" class="inline-flex items-center justify-center h-10 px-5 bg-blue-800 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-900 transition-all shadow-sm w-full sm:w-auto disabled:opacity-60 disabled:cursor-not-allowed">
                <span id="apply-text">Terapkan Tanggal</span>
                <div id="apply-spinner" class="hidden w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin ml-2"></div>
            </button>
        </div>
        <button type="button" onclick="toggleCustomDates()" class="inline-flex items-center justify-center h-10 px-4 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors w-full sm:w-auto">Tutup</button>
    </div>
    <p id="custom-date-error" class="hidden text-xs font-bold text-rose-600 mt-2 pl-1"></p>
</div>

<?= $this->include('partials/analytics_error_banner') ?>

<!-- Metrics Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">
    <!-- Total Pengunjung -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center group-hover:bg-blue-800 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-users text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="total-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Pengunjung</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Akumulasi individu unik yang mengakses portal berita.</p>
    </div>

    <!-- Pengunjung Baru -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-user-check text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="new-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Pengunjung Baru</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Pengunjung yang baru pertama kali tercatat dalam sistem.</p>
    </div>

    <!-- Tampilan Berita -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-eye text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="screen-page-views">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Tampilan Halaman</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Total akumulasi pembacaan seluruh konten berita.</p>
    </div>

    <!-- Sesi Kunjungan -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-chart-line text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="sessions">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sesi Kunjungan</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Jumlah periode interaksi aktif user di situs.</p>
    </div>

    <!-- Rasio Pantulan -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-chart-pie text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight" id="bounce-rate">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Rasio Pantulan</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Persentase pengunjung yang hanya membuka satu halaman.</p>
    </div>

    <!-- Rata-rata Durasi -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all flex-shrink-0">
                <i class="fa-solid fa-fw fa-clock text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight" id="average-session-duration">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Durasi Kunjungan</p>
            </div>
        </div>
        <p class="mt-4 sm:mt-6 text-xs text-slate-500 font-medium leading-relaxed">Rata-rata waktu yang dihabiskan pengunjung di portal.</p>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 mb-8 sm:mb-10">
    <!-- Tren Kunjungan -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6 sm:mb-10 border-b border-slate-50 pb-4 sm:pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fa-solid fa-fw fa-chart-area mr-3 text-blue-800"></i>Tren Kunjungan Berita
            </h4>
        </div>
        <div id="monthly-post-chart-spinner" class="h-64 flex flex-col items-center justify-center space-y-4">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memproses Data...</span>
        </div>
        <div id="monthly-post-chart" class="hidden min-h-[256px]"></div>
    </div>

    <!-- User Trends -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-6 sm:mb-10 border-b border-slate-50 pb-4 sm:pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fa-solid fa-fw fa-user-group mr-3 text-emerald-600"></i>Tren Pertumbuhan Pengunjung
            </h4>
        </div>
        <div id="monthly-user-chart-spinner" class="h-64 flex flex-col items-center justify-center space-y-4">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-emerald-600 rounded-full animate-spin"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sinkronisasi Tren...</span>
        </div>
        <div id="monthly-user-chart" class="hidden min-h-[256px]"></div>
    </div>
</div>

<!-- Advanced Reports -->
<div class="bg-slate-900 rounded-2xl sm:rounded-[3rem] p-6 sm:p-10 md:p-16 border border-slate-800 shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 right-0 p-16 text-white opacity-5 hidden sm:block">
        <i class="fa-solid fa-fw fa-rocket text-9xl"></i>
    </div>
    <div class="relative z-10">
        <h4 class="text-xs font-black text-blue-500 uppercase tracking-[0.4em] mb-3 sm:mb-4">Analisis Strategis</h4>
        <h2 class="text-xl sm:text-2xl md:text-4xl font-black text-white mb-6 sm:mb-12 tracking-tight">Modul Laporan Lanjutan</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6" id="advanced-links">
            <a href="<?= base_url('admin/analytics/top-pages') ?>" class="group bg-white/5 border border-white/10 p-5 sm:p-8 rounded-2xl sm:rounded-3xl hover:bg-blue-800 transition-all text-center">
                <i class="fa-solid fa-fw fa-file-lines text-2xl sm:text-3xl text-blue-500 group-hover:text-white mb-3 sm:mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Halaman Populer</p>
            </a>
            <a href="<?= base_url('admin/analytics/traffic-sources') ?>" class="group bg-white/5 border border-white/10 p-5 sm:p-8 rounded-2xl sm:rounded-3xl hover:bg-emerald-600 transition-all text-center">
                <i class="fa-solid fa-fw fa-globe text-2xl sm:text-3xl text-emerald-500 group-hover:text-white mb-3 sm:mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Sumber Kunjungan</p>
            </a>
            <a href="<?= base_url('admin/analytics/geo') ?>" class="group bg-white/5 border border-white/10 p-5 sm:p-8 rounded-2xl sm:rounded-3xl hover:bg-sky-600 transition-all text-center">
                <i class="fa-solid fa-fw fa-map-marker-alt text-2xl sm:text-3xl text-sky-500 group-hover:text-white mb-3 sm:mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Sebaran Lokasi</p>
            </a>
            <a href="<?= base_url('admin/analytics/device-category') ?>" class="group bg-white/5 border border-white/10 p-5 sm:p-8 rounded-2xl sm:rounded-3xl hover:bg-amber-500 transition-all text-center">
                <i class="fa-solid fa-fw fa-desktop text-2xl sm:text-3xl text-amber-500 group-hover:text-white mb-3 sm:mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Statistik Perangkat</p>
            </a>
        </div>
    </div>
</div>

<?= $this->include('partials/analytics_subpage_scripts') ?>

<script>
    let currentStartDate = '2023-07-01';
    let currentEndDate = 'today';
    let postChart = null;
    let userChart = null;

    function formatLocalDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function showCustomDateError(message) {
        const errorEl = document.getElementById('custom-date-error');
        const startInput = document.getElementById('start-date');
        const endInput = document.getElementById('end-date');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
        [startInput, endInput].forEach(el => {
            if (el) el.classList.add('border-rose-500');
        });
    }

    function clearCustomDateError() {
        const errorEl = document.getElementById('custom-date-error');
        const startInput = document.getElementById('start-date');
        const endInput = document.getElementById('end-date');
        if (errorEl) {
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }
        [startInput, endInput].forEach(el => {
            if (el) el.classList.remove('border-rose-500');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.getElementById('period-dropdown-btn');
        const dropdownMenu = document.getElementById('period-dropdown-menu');

        dropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            dropdownMenu.classList.add('hidden');
        });

        // Pertahankan periode dari query string saat kembali dari sub-halaman
        const params = new URLSearchParams(window.location.search);
        if (params.has('start_date')) {
            currentStartDate = params.get('start_date');
        }
        if (params.has('end_date')) {
            currentEndDate = params.get('end_date');
        }
        document.getElementById('selected-period-label').innerText = window.AnalyticsHelpers.formatPeriodLabel(currentStartDate, currentEndDate);
        updateAdvancedLinks();

        loadAllData();
    });

    function setPeriod(period, periodLabel) {
        document.getElementById('selected-period-label').innerText = periodLabel;
        document.getElementById('custom-date-container').classList.add('hidden');
        clearCustomDateError();

        const now = new Date();

        switch (period) {
            case 'today':
                currentStartDate = formatLocalDate(now);
                currentEndDate = 'today';
                break;
            case '7daysAgo':
                currentStartDate = '7daysAgo';
                currentEndDate = 'today';
                break;
            case '30daysAgo':
                currentStartDate = '30daysAgo';
                currentEndDate = 'today';
                break;
            case 'thisMonth':
                currentStartDate = formatLocalDate(new Date(now.getFullYear(), now.getMonth(), 1));
                currentEndDate = 'today';
                break;
            case 'thisYear':
                currentStartDate = formatLocalDate(new Date(now.getFullYear(), 0, 1));
                currentEndDate = 'today';
                break;
            case 'allTime':
            default:
                currentStartDate = '2023-07-01';
                currentEndDate = 'today';
                break;
        }

        updateAdvancedLinks();
        loadAllData();
    }

    function toggleCustomDates() {
        clearCustomDateError();
        document.getElementById('custom-date-container').classList.toggle('hidden');
    }

    function applyCustomDates() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;

        if (!start || !end) {
            showCustomDateError('Pilih tanggal mulai dan tanggal selesai terlebih dahulu.');
            return;
        }

        if (start > end) {
            showCustomDateError('Tanggal mulai tidak boleh melebihi tanggal selesai.');
            return;
        }

        clearCustomDateError();

        const applyBtn = document.getElementById('apply-custom-btn');
        const applyText = document.getElementById('apply-text');
        const applySpinner = document.getElementById('apply-spinner');

        if (applyBtn) applyBtn.disabled = true;
        if (applyText) applyText.textContent = 'Memuat...';
        if (applySpinner) applySpinner.classList.remove('hidden');

        currentStartDate = start;
        currentEndDate = end;
        document.getElementById('selected-period-label').innerText = `${start} sampai ${end}`;

        updateAdvancedLinks();

        window.AnalyticsHelpers.checkApiStatus();
        resetMetricSpinners();

        Promise.allSettled([loadOverview(), loadPostStats(), loadUserStats()]).finally(() => {
            if (applyBtn) applyBtn.disabled = false;
            if (applyText) applyText.textContent = 'Terapkan Tanggal';
            if (applySpinner) applySpinner.classList.add('hidden');
        });
    }

    function updateAdvancedLinks() {
        const container = document.getElementById('advanced-links');
        const links = container.querySelectorAll('a');
        links.forEach(link => {
            const url = new URL(link.href);
            url.searchParams.set('start_date', currentStartDate);
            url.searchParams.set('end_date', currentEndDate);
            link.href = url.toString();
        });
    }

    function resetMetricSpinners() {
        const metrics = [
            { id: 'total-users', color: 'border-t-blue-800' },
            { id: 'new-users', color: 'border-t-emerald-600' },
            { id: 'screen-page-views', color: 'border-t-sky-600' },
            { id: 'sessions', color: 'border-t-amber-600' },
            { id: 'bounce-rate', color: 'border-t-rose-600' },
            { id: 'average-session-duration', color: 'border-t-indigo-600' }
        ];

        metrics.forEach(m => {
            const el = document.getElementById(m.id);
            if (el) el.innerHTML = `<div class="w-6 h-6 border-2 border-slate-100 ${m.color} rounded-full animate-spin ml-auto mb-1"></div>`;
        });
    }

    function loadAllData() {
        window.AnalyticsHelpers.checkApiStatus();
        resetMetricSpinners();

        loadOverview();
        loadPostStats();
        loadUserStats();
    }

    function loadOverview() {
        const url = new URL('<?= base_url('api/analytics/overview') ?>');
        url.searchParams.set('start_date', currentStartDate);
        url.searchParams.set('end_date', currentEndDate);

        return window.AnalyticsHelpers.fetchJson(url)
            .then(data => {
                const summary = Array.isArray(data) && data.length > 0 ? data[0] : {};
                const totalUsers = parseInt(summary.totalUsers || 0, 10);
                const newUsers = parseInt(summary.newUsers || 0, 10);
                const sessions = parseInt(summary.sessions || 0, 10);
                const screenPageViews = parseInt(summary.screenPageViews || 0, 10);
                const bounceRate = parseFloat(summary.bounceRate || 0);
                const averageSessionDuration = parseFloat(summary.averageSessionDuration || 0);

                document.getElementById('total-users').textContent = totalUsers.toLocaleString('id-ID');
                document.getElementById('new-users').textContent = newUsers.toLocaleString('id-ID');
                document.getElementById('sessions').textContent = sessions.toLocaleString('id-ID');
                document.getElementById('screen-page-views').textContent = screenPageViews.toLocaleString('id-ID');
                document.getElementById('bounce-rate').textContent = `${(bounceRate * 100).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 })}%`;

                const totalSeconds = Math.round(averageSessionDuration);
                if (totalSeconds < 60) {
                    document.getElementById('average-session-duration').textContent = `${totalSeconds} dtk`;
                } else {
                    const minutes = Math.floor(totalSeconds / 60);
                    const seconds = totalSeconds % 60;
                    document.getElementById('average-session-duration').textContent = `${minutes}m ${seconds}d`;
                }
            })
            .catch((err) => {
                console.error(err);
                window.AnalyticsHelpers.showError('Gagal memuat ringkasan statistik. Coba beberapa saat lagi atau periksa kuota API.');

                ['total-users', 'new-users', 'sessions', 'screen-page-views', 'bounce-rate', 'average-session-duration'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = '-';
                });
            });
    }

    function renderChartEmptyState(chartEl, message, isError = false) {
        const textColor = isError ? 'text-rose-600' : 'text-slate-400';
        chartEl.innerHTML = `<div class="h-64 flex items-center justify-center text-xs font-bold ${textColor} text-center px-4">${window.AnalyticsHelpers.escapeHtml(message)}</div>`;
    }

    function loadPostStats() {
        const url = new URL('<?= base_url('api/analytics/monthly-post-stats') ?>');
        url.searchParams.set('start_date', currentStartDate);
        url.searchParams.set('end_date', currentEndDate);

        const spinner = document.getElementById('monthly-post-chart-spinner');
        const chartEl = document.getElementById('monthly-post-chart');
        spinner.classList.remove('hidden');
        chartEl.classList.add('hidden');

        return window.AnalyticsHelpers.fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');

                if (postChart) {
                    postChart.destroy();
                    postChart = null;
                }

                if (!Array.isArray(data) || data.length === 0) {
                    renderChartEmptyState(chartEl, 'Belum ada data tren tayangan pada periode ini.');
                    return;
                }

                chartEl.innerHTML = '';
                const options = {
                    chart: {
                        type: 'area',
                        height: 256,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    series: [{
                        name: 'Tayangan Berita',
                        data: data.map(i => i.screenPageViews)
                    }],
                    xaxis: {
                        categories: data.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                            formatter: (val) => Math.round(val).toLocaleString('id-ID')
                        }
                    },
                    colors: ['#1e40af'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 }
                    },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        theme: 'light',
                        x: { show: true },
                        y: { formatter: (val) => val.toLocaleString('id-ID') + ' Dilihat' }
                    }
                };

                postChart = new ApexCharts(chartEl, options);
                postChart.render();
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');
                if (postChart) {
                    postChart.destroy();
                    postChart = null;
                }
                renderChartEmptyState(chartEl, 'Gagal memuat grafik tren tayangan berita.', true);
            });
    }

    function loadUserStats() {
        const url = new URL('<?= base_url('api/analytics/monthly-user-stats') ?>');
        url.searchParams.set('start_date', currentStartDate);
        url.searchParams.set('end_date', currentEndDate);

        const spinner = document.getElementById('monthly-user-chart-spinner');
        const chartEl = document.getElementById('monthly-user-chart');
        spinner.classList.remove('hidden');
        chartEl.classList.add('hidden');

        return window.AnalyticsHelpers.fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');

                if (userChart) {
                    userChart.destroy();
                    userChart = null;
                }

                if (!Array.isArray(data) || data.length === 0) {
                    renderChartEmptyState(chartEl, 'Belum ada data tren pengunjung pada periode ini.');
                    return;
                }

                chartEl.innerHTML = '';
                const options = {
                    chart: {
                        type: 'area',
                        height: 256,
                        toolbar: { show: false },
                        fontFamily: 'inherit',
                        zoom: { enabled: false }
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3 },
                    series: [{
                        name: 'Pengunjung',
                        data: data.map(i => i.totalUsers)
                    }],
                    xaxis: {
                        categories: data.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: {
                            style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                            formatter: (val) => Math.round(val).toLocaleString('id-ID')
                        }
                    },
                    colors: ['#059669'],
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 }
                    },
                    grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                    tooltip: {
                        theme: 'light',
                        x: { show: true },
                        y: { formatter: (val) => val.toLocaleString('id-ID') + ' Pengunjung' }
                    }
                };

                userChart = new ApexCharts(chartEl, options);
                userChart.render();
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');
                if (userChart) {
                    userChart.destroy();
                    userChart = null;
                }
                renderChartEmptyState(chartEl, 'Gagal memuat grafik pertumbuhan pengunjung.', true);
            });
    }
</script>

<?= $this->endSection() ?>