<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik: Statistik Perangkat<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-wrap items-center gap-2">
    <span id="active-period-badge" class="inline-flex items-center h-10 px-3 bg-amber-50 text-amber-700 font-bold text-[11px] rounded-lg border border-amber-100">Semua Waktu</span>
    <a id="back-to-overview-link" href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
        <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali ke Ringkasan
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('partials/analytics_error_banner') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-amber-500 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menganalisa Spesifikasi Akses...</span>
</div>

<div id="analytics-content" class="hidden space-y-6">
    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 flex flex-col">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4 text-center">Kategori Perangkat</h3>
            <div id="deviceChart" class="h-48"></div>
            <div id="device-breakdown" class="mt-4 pt-4 border-t border-slate-100 space-y-2"></div>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 flex flex-col">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4 text-center">Sistem Operasi</h3>
            <div id="osChart" class="h-48"></div>
            <div id="os-breakdown" class="mt-4 pt-4 border-t border-slate-100 space-y-2"></div>
        </div>
        <div class="bg-white p-5 sm:p-6 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 flex flex-col">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4 text-center">Peramban</h3>
            <div id="browserChart" class="h-48"></div>
            <div id="browser-breakdown" class="mt-4 pt-4 border-t border-slate-100 space-y-2"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 sm:px-8 py-4 sm:py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-4">
            <div class="flex items-center">
                <i class="fa-solid fa-fw fa-microchip mr-3 text-amber-500"></i>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Detail Teknis Pengunjung</h3>
            </div>
            <span id="device-total-rows" class="text-[11px] font-bold text-slate-500"></span>
        </div>
        <div class="overflow-auto max-h-96">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        <th class="px-4 sm:px-6 py-3.5 w-1">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Perangkat</th>
                        <th class="px-4 sm:px-6 py-3.5">Sistem Operasi</th>
                        <th class="px-4 sm:px-6 py-3.5">Peramban</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Sesi</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Tayangan</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Pengunjung</th>
                    </tr>
                </thead>
                <tbody id="device-data" class="divide-y divide-slate-100 whitespace-nowrap"></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('partials/analytics_subpage_scripts') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const { escapeHtml, initSubpageContext, checkApiStatus, buildApiUrl, fetchJson, showError } = window.AnalyticsHelpers;
        initSubpageContext();
        checkApiStatus();

        const spinner = document.getElementById('loading-spinner');
        const content = document.getElementById('analytics-content');
        const deviceData = document.getElementById('device-data');
        const deviceTotalRows = document.getElementById('device-total-rows');
        const chartIds = ['deviceChart', 'osChart', 'browserChart'];
        const breakdownIds = ['device-breakdown', 'os-breakdown', 'browser-breakdown'];
        const isDark = document.documentElement.classList.contains('dark');
        const url = buildApiUrl('<?= base_url('api/analytics/device-category') ?>');

        function toTopSeries(mapObj, limit = 5) {
            const sorted = Object.entries(mapObj).sort((a, b) => b[1] - a[1]);
            const top = sorted.slice(0, limit);
            const other = sorted.slice(limit).reduce((acc, [, val]) => acc + val, 0);
            if (other > 0) top.push(['Lainnya', other]);
            return {
                labels: top.map(([k]) => k),
                series: top.map(([, v]) => v),
                total: sorted.reduce((acc, [, val]) => acc + val, 0)
            };
        }

        function renderBreakdown(containerId, topData, colors) {
            const el = document.getElementById(containerId);
            if (!el) return;
            el.innerHTML = '';
            topData.labels.forEach((label, idx) => {
                const val = topData.series[idx] || 0;
                const pct = topData.total > 0 ? ((val / topData.total) * 100).toFixed(1) : '0.0';
                const color = colors[idx] || '#94a3b8';
                el.innerHTML += `
                    <div class="flex items-center justify-between text-xs gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: ${color}"></span>
                            <span class="font-semibold text-slate-700 truncate">${escapeHtml(label)}</span>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-slate-400 font-medium">${pct}%</span>
                            <span class="font-bold text-slate-900">${val.toLocaleString()}</span>
                        </div>
                    </div>
                `;
            });
        }

        function getDeviceIcon(d) {
            d = (d || '').toLowerCase();
            return 'fa-fw ' + (d.includes('mobile') ? 'fa-mobile-screen-button' : (d.includes('tablet') ? 'fa-tablet-screen-button' : 'fa-desktop'));
        }

        fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                content.classList.remove('hidden');

                if (!Array.isArray(data) || data.length === 0) {
                    chartIds.forEach(id => {
                        document.getElementById(id).innerHTML = '<div class="h-48 flex items-center justify-center text-xs font-bold text-slate-400">Belum ada data.</div>';
                    });
                    breakdownIds.forEach(id => {
                        document.getElementById(id).innerHTML = '';
                    });
                    deviceData.innerHTML = '<tr><td colspan="7" class="py-16 text-center text-slate-400 italic font-medium">Tidak ada data perangkat untuk periode ini.</td></tr>';
                    return;
                }

                const stats = { device: {}, os: {}, browser: {} };
                data.forEach(i => {
                    const users = parseInt(i.totalUsers || 0, 10);
                    const dev = i.deviceCategory || 'Lainnya';
                    const os = i.operatingSystem || 'Tidak Diketahui';
                    const browser = i.browser || 'Tidak Diketahui';
                    stats.device[dev] = (stats.device[dev] || 0) + users;
                    stats.os[os] = (stats.os[os] || 0) + users;
                    stats.browser[browser] = (stats.browser[browser] || 0) + users;
                });

                const cfg = (labels, series, colors) => ({
                    chart: { type: 'donut', height: 192, fontFamily: 'inherit' },
                    theme: { mode: isDark ? 'dark' : 'light' },
                    series: series,
                    labels: labels,
                    colors: colors,
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '78%',
                                labels: {
                                    show: true,
                                    name: { show: false },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontWeight: 900,
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        offsetY: 5,
                                        formatter: (val) => parseInt(val, 10).toLocaleString()
                                    },
                                    total: {
                                        show: true,
                                        label: 'TOTAL',
                                        formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString()
                                    }
                                }
                            }
                        }
                    },
                    stroke: { show: false }
                });

                const deviceColors = ['#d97706', '#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#94a3b8'];
                const osColors = ['#059669', '#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#94a3b8'];
                const browserColors = ['#1d4ed8', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#94a3b8'];

                const deviceTop = toTopSeries(stats.device);
                const osTop = toTopSeries(stats.os);
                const browserTop = toTopSeries(stats.browser);

                new ApexCharts(document.getElementById('deviceChart'), cfg(deviceTop.labels, deviceTop.series, deviceColors)).render();
                new ApexCharts(document.getElementById('osChart'), cfg(osTop.labels, osTop.series, osColors)).render();
                new ApexCharts(document.getElementById('browserChart'), cfg(browserTop.labels, browserTop.series, browserColors)).render();

                renderBreakdown('device-breakdown', deviceTop, deviceColors);
                renderBreakdown('os-breakdown', osTop, osColors);
                renderBreakdown('browser-breakdown', browserTop, browserColors);

                if (deviceTotalRows) {
                    deviceTotalRows.textContent = `${data.length} kombinasi teratas`;
                }

                deviceData.innerHTML = '';
                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-bold text-slate-400">${index + 1}</td>
                        <td class="px-4 sm:px-6 py-3.5">
                            <div class="flex items-center">
                                <i class="fa-solid ${getDeviceIcon(item.deviceCategory)} mr-3 text-slate-400 group-hover:text-amber-500 transition-colors"></i>
                                <span class="font-bold text-slate-900 text-xs capitalize">${escapeHtml(item.deviceCategory || 'Lainnya')}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-semibold text-slate-700">${escapeHtml(item.operatingSystem || '-')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-xs text-slate-600">${escapeHtml(item.browser || '-')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right text-xs font-bold text-slate-700">${parseInt(item.sessions || 0, 10).toLocaleString()}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right text-xs font-bold text-slate-700">${parseInt(item.screenPageViews || 0, 10).toLocaleString()}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-lg border border-amber-100">
                                ${parseInt(item.totalUsers || 0, 10).toLocaleString()} User
                            </span>
                        </td>
                    `;
                    deviceData.appendChild(row);
                });
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                showError('Gagal memuat data statistik perangkat. Coba muat ulang halaman.');
                chartIds.forEach(id => {
                    document.getElementById(id).innerHTML = '<div class="h-48 flex items-center justify-center text-xs font-bold text-rose-600">Gagal memuat grafik.</div>';
                });
                breakdownIds.forEach(id => {
                    document.getElementById(id).innerHTML = '';
                });
                deviceData.innerHTML = '<tr><td colspan="7" class="py-16 text-center text-rose-600 font-bold">Gagal menyinkronkan data statistik perangkat.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>
