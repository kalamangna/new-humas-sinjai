<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Ringkasan Analitik Lanjutan<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menyusun Laporan Strategis...</span>
</div>

<div id="analytics-summary" class="hidden space-y-10">
    <!-- Performance Summary -->
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-8 flex items-center border-b border-slate-50 pb-6">
            <i class="fas fa-chart-line mr-3 text-blue-800"></i>Ringkasan Kinerja Keseluruhan
        </h3>
        <div id="performance-summary" class="grid grid-cols-1 md:grid-cols-3 gap-8"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Top Articles -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
                <i class="fas fa-file-alt mr-3 text-blue-800 opacity-50"></i>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Top 5 Artikel Terpopuler</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-8 py-5">Judul Artikel</th>
                            <th class="px-8 py-5 text-right">Tampilan</th>
                        </tr>
                    </thead>
                    <tbody id="top-articles" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </div>

        <!-- Daily Visit Trends -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-10 flex items-center border-b border-slate-50 pb-6">
                <i class="fas fa-chart-bar mr-3 text-blue-800"></i>Tren Sesi Harian
            </h3>
            <div id="daily-visits-chart" class="h-64"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Traffic Sources -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
                <i class="fas fa-globe mr-3 text-emerald-600 opacity-50"></i>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Sumber Lalu Lintas Utama</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-8 py-5">Sumber</th>
                            <th class="px-8 py-5 text-right">Sesi</th>
                        </tr>
                    </thead>
                    <tbody id="traffic-sources" class="divide-y divide-slate-100"></tbody>
                </table>
            </div>
        </div>

        <!-- Device Types -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-10 flex items-center border-b border-slate-50 pb-6">
                <i class="fas fa-desktop mr-3 text-amber-500"></i>Distribusi Perangkat
            </h3>
            <div id="device-types-chart" class="h-64"></div>
        </div>
    </div>

    <!-- Narrative Insights -->
    <div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-16 text-white opacity-5">
            <i class="fas fa-lightbulb text-9xl"></i>
        </div>
        <div class="relative z-10">
            <h4 class="text-xs font-black text-blue-500 uppercase tracking-[0.4em] mb-4">Wawasan Naratif</h4>
            <div id="narrative-insights" class="text-slate-300 text-lg md:text-xl font-medium leading-relaxed max-w-3xl"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingSpinner = document.getElementById('loading-spinner');
        const analyticsSummary = document.getElementById('analytics-summary');
        const isDark = document.documentElement.classList.contains('dark');

        Promise.all([
            fetch('<?= base_url('api/analytics/overview') ?>').then(response => response.json()),
            fetch('<?= base_url('api/analytics/top-pages') ?>').then(response => response.json()),
            fetch('<?= base_url('api/analytics/traffic-sources') ?>').then(response => response.json()),
            fetch('<?= base_url('api/analytics/device-category') ?>').then(response => response.json())
        ]).then(([overview, topPages, trafficSources, deviceCategory]) => {
            loadingSpinner.classList.add('hidden');
            analyticsSummary.classList.remove('hidden');

            // Performance Summary
            const latest = overview[0] || {};
            const summaryData = [
                { label: 'Total Pengguna', value: parseInt(latest.totalUsers || 0).toLocaleString(), icon: 'fa-users', color: 'text-blue-800', bg: 'bg-blue-50' },
                { label: 'Tampilan Halaman', value: parseInt(latest.screenPageViews || 0).toLocaleString(), icon: 'fa-eye', color: 'text-emerald-600', bg: 'bg-emerald-50' },
                { label: 'Rata-rata Durasi', value: (latest.averageSessionDuration || 0).toFixed(0) + 's', icon: 'fa-clock', color: 'text-amber-600', bg: 'bg-amber-50' }
            ];

            const summaryEl = document.getElementById('performance-summary');
            summaryData.forEach(item => {
                summaryEl.innerHTML += `
                    <div class="flex items-center p-6 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-blue-800 transition-all">
                        <div class="w-12 h-12 ${item.bg} ${item.color} rounded-xl flex items-center justify-center mr-4 text-xl group-hover:bg-blue-800 group-hover:text-white transition-all">
                            <i class="fas ${item.icon}"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${item.label}</p>
                            <h4 class="text-2xl font-black text-slate-900 tracking-tight">${item.value}</h4>
                        </div>
                    </div>
                `;
            });

            // Top 5 Most-Viewed Articles
            const topArticlesBody = document.getElementById('top-articles');
            topPages.slice(0, 5).forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition-colors group';
                row.innerHTML = `
                    <td class="px-8 py-6 font-bold text-slate-900 text-xs tracking-tight">${item.pageTitle}</td>
                    <td class="px-8 py-6 text-right">
                        <span class="px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                            ${parseInt(item.screenPageViews).toLocaleString()}
                        </span>
                    </td>
                `;
                topArticlesBody.appendChild(row);
            });

            // Daily Visit Trends (Area Chart)
            const dailyOptions = {
                chart: { type: 'area', height: 256, toolbar: { show: false }, fontFamily: 'inherit' },
                theme: { mode: isDark ? 'dark' : 'light' },
                series: [{ name: 'Sesi', data: overview.map(item => parseInt(item.sessions)) }],
                xaxis: { categories: overview.map(item => item.date || '-'), axisBorder: { show: false }, axisTicks: { show: false } },
                colors: ['#1e40af'],
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 } },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f5f9' }
            };
            new ApexCharts(document.getElementById('daily-visits-chart'), dailyOptions).render();

            // Main Traffic Sources
            const trafficSourcesBody = document.getElementById('traffic-sources');
            trafficSources.slice(0, 5).forEach(item => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50 transition-colors group';
                row.innerHTML = `
                    <td class="px-8 py-6">
                        <div class="font-bold text-slate-900 text-xs tracking-tight">${item.sessionSource}</div>
                        <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest">${item.sessionMedium}</div>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100">
                            ${parseInt(item.sessions).toLocaleString()}
                        </span>
                    </td>
                `;
                trafficSourcesBody.appendChild(row);
            });

            // Dominant Device Types (Donut)
            const deviceData = deviceCategory.reduce((acc, item) => {
                acc[item.deviceCategory] = (acc[item.deviceCategory] || 0) + parseInt(item.sessions);
                return acc;
            }, {});

            const deviceOptions = {
                chart: { type: 'donut', height: 256, fontFamily: 'inherit' },
                theme: { mode: isDark ? 'dark' : 'light' },
                series: Object.values(deviceData),
                labels: Object.keys(deviceData),
                colors: ['#f59e0b', '#3b82f6', '#10b981'],
                legend: { position: 'bottom', fontSize: '10px', fontWeight: 700 },
                plotOptions: { pie: { donut: { size: '75%' } } },
                dataLabels: { enabled: false },
                stroke: { show: false }
            };
            new ApexCharts(document.getElementById('device-types-chart'), deviceOptions).render();

            // Narrative Insights
            const insightText = `Berdasarkan data terbaru, performa digital portal Humas Sinjai menunjukkan interaksi yang stabil dengan total tampilan halaman mencapai ${latest.screenPageViews.toLocaleString()}. Sumber lalu lintas utama didominasi oleh ${trafficSources[0]?.sessionSource || 'Direct Traffic'}, mengindikasikan loyalitas pembaca yang tinggi secara organik.`;
            document.getElementById('narrative-insights').innerHTML = `<p>${insightText}</p>`;
        });
    });
</script>
<?= $this->endSection() ?>