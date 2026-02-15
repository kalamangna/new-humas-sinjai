<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik Situs<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-col sm:flex-row items-center sm:items-end gap-3 w-full sm:w-auto">
    <div class="flex flex-col items-center sm:items-end w-full sm:w-auto">
        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 pr-1 hidden sm:block">Filter Periode</span>
        <div class="relative inline-block text-left w-full sm:w-auto" id="period-dropdown-container">
            <button type="button" id="period-dropdown-btn" class="inline-flex items-center justify-between sm:justify-start h-10 px-4 bg-white border border-slate-200 rounded-xl shadow-sm text-[11px] font-bold text-slate-700 hover:bg-slate-50 transition-colors w-full sm:w-auto">
                <div class="flex items-center">
                    <i class="far fa-fw fa-calendar-alt text-blue-800 mr-2 text-xs"></i>
                    <span id="selected-period-label">Semua Waktu</span>
                </div>
                <i class="fas fa-fw fa-chevron-down ml-3 text-[9px] opacity-50"></i>
            </button>
            <div id="period-dropdown-menu" class="hidden absolute right-0 mt-2 w-full sm:w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden animate-in fade-in slide-in-from-top-2">
                <div class="py-1">
                    <button type="button" onclick="setPeriod('today', 'Hari Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Hari Ini</button>
                    <button type="button" onclick="setPeriod('7daysAgo', '7 Hari Terakhir')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">7 Hari Terakhir</button>
                    <button type="button" onclick="setPeriod('30daysAgo', '30 Hari Terakhir')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">30 Hari Terakhir</button>
                    <button type="button" onclick="setPeriod('thisMonth', 'Bulan Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Bulan Ini</button>
                    <button type="button" onclick="setPeriod('thisYear', 'Tahun Ini')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Tahun Ini</button>
                    <button type="button" onclick="setPeriod('allTime', 'Semua Waktu')" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition-colors border-b border-slate-50">Semua Waktu</button>
                    <button type="button" onclick="toggleCustomDates()" class="w-full text-left px-4 py-2.5 text-xs font-black text-blue-800 hover:bg-blue-50 transition-colors">Rentang Kustom...</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Date Inputs -->
    <div id="custom-date-container" class="hidden flex flex-col sm:flex-row items-center sm:items-end gap-3 bg-white p-3 rounded-2xl border border-blue-100 shadow-xl shadow-blue-900/5 animate-in slide-in-from-top-2 w-full sm:w-auto">
        <div class="w-full sm:w-auto">
            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 pl-1">Mulai</label>
            <input type="date" id="start-date" class="h-9 px-3 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 outline-none w-full sm:w-auto">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1 pl-1">Selesai</label>
            <input type="date" id="end-date" class="h-9 px-3 border border-slate-200 rounded-xl text-[11px] font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 outline-none w-full sm:w-auto">
        </div>
        <button type="button" onclick="applyCustomDates()" id="apply-custom-btn" class="inline-flex items-center justify-center h-9 px-4 bg-blue-800 text-white rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20 w-full sm:w-auto">
            <span id="apply-text">OK</span>
            <div id="apply-spinner" class="hidden w-3 h-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
        </button>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- Total Pengunjung -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center group-hover:bg-blue-800 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-users text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="total-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Pengunjung</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Akumulasi individu unik yang mengakses portal berita.</p>
    </div>

    <!-- Pengunjung Baru -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-user-check text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="new-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Pengunjung Baru</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Pengunjung yang baru pertama kali tercatat dalam sistem.</p>
    </div>

    <!-- Tampilan Berita -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center group-hover:bg-sky-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-eye text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="screen-page-views">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Tampilan Halaman</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Total akumulasi pembacaan seluruh konten berita.</p>
    </div>

    <!-- Sesi Kunjungan -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-chart-line text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="sessions">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sesi Kunjungan</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Jumlah periode interaksi aktif pengguna di situs.</p>
    </div>

    <!-- Rasio Pantulan -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center group-hover:bg-rose-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-chart-pie text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="bounce-rate">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Bounce Rate</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Persentase pengunjung yang hanya membuka satu halaman.</p>
    </div>

    <!-- Rata-rata Durasi -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-clock text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-black text-slate-900 tracking-tight" id="average-session-duration">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Durasi Kunjungan</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Rata-rata waktu yang dihabiskan pengunjung di portal.</p>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
    <!-- View Trends -->
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-chart-area mr-3 text-blue-800"></i>Tren Kunjungan Berita
            </h4>
        </div>
        <div id="monthly-post-chart-spinner" class="h-64 flex flex-col items-center justify-center space-y-4">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memproses Data...</span>
        </div>
        <div id="monthly-post-chart" class="hidden min-h-[256px]"></div>
    </div>

    <!-- User Trends -->
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-user-friends mr-3 text-emerald-600"></i>Tren Pertumbuhan Pengunjung
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
<div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 border border-slate-800 shadow-2xl relative overflow-hidden">
    <div class="absolute top-0 right-0 p-16 text-white opacity-5">
        <i class="fas fa-fw fa-rocket text-9xl"></i>
    </div>
    <div class="relative z-10">
        <h4 class="text-xs font-black text-blue-500 uppercase tracking-[0.4em] mb-4">Analisis Strategis</h4>
        <h2 class="text-2xl md:text-4xl font-black text-white mb-12 tracking-tight">Modul Laporan Lanjutan</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6" id="advanced-links">
            <a href="<?= base_url('admin/analytics/top-pages') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-blue-800 transition-all text-center">
                <i class="fas fa-fw fa-file-alt text-3xl text-blue-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Halaman Populer</p>
            </a>
            <a href="<?= base_url('admin/analytics/traffic-sources') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-emerald-600 transition-all text-center">
                <i class="fas fa-fw fa-globe text-3xl text-emerald-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Sumber Lalu Lintas</p>
            </a>
            <a href="<?= base_url('admin/analytics/geo') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-sky-600 transition-all text-center">
                <i class="fas fa-fw fa-map-marker-alt text-3xl text-sky-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Sebaran Lokasi</p>
            </a>
            <a href="<?= base_url('admin/analytics/device-category') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-amber-500 transition-all text-center">
                <i class="fas fa-fw fa-desktop text-3xl text-amber-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Statistik Perangkat</p>
            </a>
        </div>
    </div>
</div>

<script>
    let currentStartDate = '2023-07-01';
    let currentEndDate = 'today';
    let postChart = null;
    let userChart = null;

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

        loadAllData();
    });

    function setPeriod(period, periodLabel) {
        document.getElementById('selected-period-label').innerText = periodLabel;
        document.getElementById('custom-date-container').classList.add('hidden');
        
        const now = new Date();
        const formatDate = (date) => date.toISOString().split('T')[0];

        switch(period) {
            case 'today':
                currentStartDate = formatDate(now);
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
                currentStartDate = formatDate(new Date(now.getFullYear(), now.getMonth(), 1));
                currentEndDate = 'today';
                break;
            case 'thisYear':
                currentStartDate = formatDate(new Date(now.getFullYear(), 0, 1));
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
        document.getElementById('custom-date-container').classList.toggle('hidden');
    }

    function applyCustomDates() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;

        if (!start || !end) {
            alert('Silakan pilih tanggal mulai dan selesai.');
            return;
        }

        const applyBtn = document.getElementById('apply-custom-btn');
        const applyText = document.getElementById('apply-text');
        const applySpinner = document.getElementById('apply-spinner');

        if (applyBtn) applyBtn.disabled = true;
        if (applyText) applyText.classList.add('hidden');
        if (applySpinner) applySpinner.classList.remove('hidden');

        currentStartDate = start;
        currentEndDate = end;
        document.getElementById('selected-period-label').innerText = `${start} s/d ${end}`;
        
        updateAdvancedLinks();
        
        // Wrapped in loadAllData logic but we want to know when it's done to reset the button
        loadOverview().finally(() => {
            if (applyBtn) applyBtn.disabled = false;
            if (applyText) applyText.classList.remove('hidden');
            if (applySpinner) applySpinner.classList.add('hidden');
        });
        loadPostStats();
        loadUserStats();
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

    function loadAllData() {
        // Reset metrics to loading state with spinners
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

        loadOverview();
        loadPostStats();
        loadUserStats();
    }

    function loadOverview() {
        const url = new URL('<?= base_url('api/analytics/overview') ?>');
        url.searchParams.set('start_date', currentStartDate);
        url.searchParams.set('end_date', currentEndDate);

        return fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') throw new Error(data.message);
                const {
                    totalUsers,
                    newUsers,
                    sessions,
                    screenPageViews,
                    bounceRate,
                    averageSessionDuration
                } = data[0];
                document.getElementById('total-users').innerHTML = parseInt(totalUsers).toLocaleString();
                document.getElementById('new-users').innerHTML = parseInt(newUsers).toLocaleString();
                document.getElementById('sessions').innerHTML = parseInt(sessions).toLocaleString();
                document.getElementById('screen-page-views').innerHTML = parseInt(screenPageViews).toLocaleString();
                document.getElementById('bounce-rate').innerHTML = `${(parseFloat(bounceRate) * 100).toFixed(1)}%`;

                const duration = parseFloat(averageSessionDuration);
                if (duration < 60) {
                    document.getElementById('average-session-duration').innerHTML = `${duration.toFixed(0)} dtk`;
                } else {
                    const minutes = Math.floor(duration / 60);
                    const seconds = (duration % 60).toFixed(0);
                    document.getElementById('average-session-duration').innerHTML = `${minutes}m ${seconds}s`;
                }
            })
            .catch((err) => {
                console.error(err);
                ['total-users', 'new-users', 'sessions', 'screen-page-views', 'bounce-rate', 'average-session-duration'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = 'ERR';
                });
            });
    }

    function loadPostStats() {
        const url = new URL('<?= base_url('api/analytics/monthly-post-stats') ?>');
        url.searchParams.set('start_date', currentStartDate);
        url.searchParams.set('end_date', currentEndDate);

        const spinner = document.getElementById('monthly-post-chart-spinner');
        const chartEl = document.getElementById('monthly-post-chart');
        spinner.classList.remove('hidden');
        chartEl.classList.add('hidden');

        fetch(url)
            .then(response => response.json())
            .then(data => {
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');
                const sorted = data;

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
                        data: sorted.map(i => i.screenPageViews)
                    }],
                    xaxis: {
                        categories: sorted.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: { 
                            style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                            formatter: (val) => val.toLocaleString()
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
                        y: { formatter: (val) => val.toLocaleString() + ' Hits' }
                    }
                };

                if (postChart) postChart.destroy();
                postChart = new ApexCharts(chartEl, options);
                postChart.render();
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

        fetch(url)
            .then(response => response.json())
            .then(data => {
                spinner.classList.add('hidden');
                chartEl.classList.remove('hidden');
                const sorted = data;

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
                        data: sorted.map(i => i.totalUsers)
                    }],
                    xaxis: {
                        categories: sorted.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 } }
                    },
                    yaxis: {
                        labels: { 
                            style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                            formatter: (val) => val.toLocaleString()
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
                        y: { formatter: (val) => val.toLocaleString() + ' Pengunjung' }
                    }
                };

                if (userChart) userChart.destroy();
                userChart = new ApexCharts(chartEl, options);
                userChart.render();
            });
    }
</script>

<?= $this->endSection() ?>