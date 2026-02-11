<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Ringkasan Analitik Situs<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-col items-end">
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Periode Laporan</span>
    <div class="inline-flex items-center px-3 py-1 bg-white border border-slate-200 rounded-lg shadow-sm">
        <i class="far fa-fw fa-calendar-alt text-blue-800 mr-2 text-xs"></i>
        <span class="text-xs font-bold text-slate-700">Semua Waktu (All-Time)</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Metrics Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
    <!-- Total Pengguna -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center group-hover:bg-blue-800 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-users text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="total-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Pengguna</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Akumulasi pengunjung yang tercatat pada sistem analitik.</p>
    </div>

    <!-- Pengguna Baru -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-user-plus text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="new-users">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Pengguna Baru</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Pengunjung unik yang pertama kali mengakses portal informasi.</p>
    </div>

    <!-- Tampilan Halaman -->
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
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Total intensitas konten yang dibaca oleh seluruh pengunjung.</p>
    </div>

    <!-- Sesi -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-chart-line text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-3xl font-black text-slate-900 tracking-tight" id="sessions">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Sesi</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Jumlah interaksi aktif yang terjadi dalam satu periode kunjungan.</p>
    </div>

    <!-- Bounce Rate -->
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
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Persentase kunjungan singkat (hanya satu halaman).</p>
    </div>

    <!-- Avg duration -->
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col justify-between group hover:border-blue-800 transition-all">
        <div class="flex items-start justify-between">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i class="fas fa-fw fa-clock text-xl"></i>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-black text-slate-900 tracking-tight" id="average-session-duration">...</h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Rata-rata Durasi</p>
            </div>
        </div>
        <p class="mt-6 text-[10px] text-slate-400 font-medium leading-relaxed">Lama waktu rata-rata pengunjung berada di dalam portal.</p>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-chart-bar mr-3 text-blue-800"></i>Grafik Kunjungan Konten
            </h4>
            <a href="<?= base_url('admin/analytics/monthly-report') ?>" class="text-[10px] font-black text-blue-800 uppercase tracking-widest hover:underline">Detail Laporan</a>
        </div>
        <div id="monthly-post-chart-spinner" class="h-64 flex flex-col items-center justify-center space-y-4">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sinkronisasi Data...</span>
        </div>
        <div id="monthly-post-chart" class="hidden min-h-[256px]"></div>
    </div>

    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
        <div class="flex items-center justify-between mb-10 border-b border-slate-50 pb-6">
            <h4 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-user-friends mr-3 text-blue-800"></i>Tren Pertumbuhan User
            </h4>
        </div>
        <div id="monthly-user-chart-spinner" class="h-64 flex flex-col items-center justify-center space-y-4">
            <div class="w-10 h-10 border-4 border-slate-100 border-t-emerald-600 rounded-full animate-spin"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sinkronisasi Data...</span>
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
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <a href="<?= base_url('admin/analytics/top-pages') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-blue-800 transition-all text-center">
                <i class="fas fa-fw fa-file-alt text-3xl text-blue-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Halaman Populer</p>
            </a>
            <a href="<?= base_url('admin/analytics/traffic-sources') ?>" class="group bg-white/5 border border-white/10 p-8 rounded-3xl hover:bg-emerald-600 transition-all text-center">
                <i class="fas fa-fw fa-globe text-3xl text-emerald-500 group-hover:text-white mb-4 transition-colors"></i>
                <p class="text-[10px] font-black text-white uppercase tracking-widest">Sumber Kunjungan</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyPostChartSpinner = document.getElementById('monthly-post-chart-spinner');
        const monthlyPostChart = document.getElementById('monthly-post-chart');
        const monthlyUserChartSpinner = document.getElementById('monthly-user-chart-spinner');
        const monthlyUserChart = document.getElementById('monthly-user-chart');

        const isDark = document.documentElement.classList.contains('dark');

        fetch('<?= base_url('api/analytics/overview') ?>')
            .then(response => response.json())
            .then(data => {
                const { totalUsers, newUsers, sessions, screenPageViews, bounceRate, averageSessionDuration } = data[0];
                document.getElementById('total-users').innerHTML = totalUsers.toLocaleString();
                document.getElementById('new-users').innerHTML = newUsers.toLocaleString();
                document.getElementById('sessions').innerHTML = sessions.toLocaleString();
                document.getElementById('screen-page-views').innerHTML = screenPageViews.toLocaleString();
                document.getElementById('bounce-rate').innerHTML = `${(bounceRate * 100).toFixed(1)}%`;

                if (averageSessionDuration < 60) {
                    document.getElementById('average-session-duration').innerHTML = `${(averageSessionDuration % 60).toFixed(0)}s`;
                } else {
                    const minutes = Math.floor(averageSessionDuration / 60);
                    const seconds = (averageSessionDuration % 60).toFixed(0);
                    document.getElementById('average-session-duration').innerHTML = `${minutes}m ${seconds}s`;
                }
            })
            .catch(() => {
                ['total-users','new-users','sessions','screen-page-views','bounce-rate','average-session-duration'].forEach(id => document.getElementById(id).textContent = 'ERR');
            });

        fetch('<?= base_url('api/analytics/monthly-post-stats') ?>')
            .then(response => response.json())
            .then(data => {
                monthlyPostChartSpinner.classList.add('hidden');
                monthlyPostChart.classList.remove('hidden');
                const sorted = data.sort((a,b) => a.year - b.year || a.month - b.month);
                
                const options = {
                    chart: {
                        type: 'bar',
                        height: 256,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    theme: { mode: isDark ? 'dark' : 'light' },
                    series: [{
                        name: 'Page Views',
                        data: sorted.map(i => i.screenPageViews)
                    }],
                    xaxis: {
                        categories: sorted.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    colors: ['#1e40af'],
                    plotOptions: {
                        bar: { borderRadius: 4, columnWidth: '60%' }
                    },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#f1f5f9' }
                };

                new ApexCharts(monthlyPostChart, options).render();
            });

        fetch('<?= base_url('api/analytics/monthly-user-stats') ?>')
            .then(response => response.json())
            .then(data => {
                monthlyUserChartSpinner.classList.add('hidden');
                monthlyUserChart.classList.remove('hidden');
                const sorted = data.sort((a,b) => a.year - b.year || a.month - b.month);
                
                const options = {
                    chart: {
                        type: 'area',
                        height: 256,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    theme: { mode: isDark ? 'dark' : 'light' },
                    series: [{
                        name: 'Users',
                        data: sorted.map(i => i.totalUsers)
                    }],
                    xaxis: {
                        categories: sorted.map(i => i.formatted_date),
                        axisBorder: { show: false },
                        axisTicks: { show: false }
                    },
                    colors: ['#059669'],
                    stroke: { curve: 'smooth', width: 2 },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 }
                    },
                    dataLabels: { enabled: false },
                    grid: { borderColor: '#f1f5f9' }
                };

                new ApexCharts(monthlyUserChart, options).render();
            });
    });
</script>

<?= $this->endSection() ?>