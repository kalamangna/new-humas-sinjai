<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik: Statistik Perangkat<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-amber-500 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menganalisa Spesifikasi Akses...</span>
</div>

<div id="analytics-content" class="hidden space-y-10">
    <!-- Charts -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">KATEGORI PERANGKAT</h3>
            <div id="deviceChart" class="h-48"></div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">SISTEM OPERASI</h3>
            <div id="osChart" class="h-48"></div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 text-center">PERAMBAN (BROWSER)</h3>
            <div id="browserChart" class="h-48"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <i class="fa-solid fa-fw fa-microchip mr-3 text-amber-500 opacity-50"></i>
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">DETAIL TEKNIS PENGUNJUNG</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        <th class="px-8 py-5">Platform / Perangkat</th>
                        <th class="px-8 py-5 w-1">OS</th>
                        <th class="px-8 py-5 w-1">Browser</th>
                        <th class="px-8 py-5 text-right w-1">Pengunjung</th>
                    </tr>
                </thead>
                <tbody id="device-data" class="divide-y divide-slate-100 whitespace-nowrap"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deviceData = document.getElementById('device-data');
        const isDark = document.documentElement.classList.contains('dark');
        const params = new URLSearchParams(window.location.search);
        const url = new URL('<?= base_url('api/analytics/device-category') ?>');
        if (params.has('start_date')) url.searchParams.set('start_date', params.get('start_date'));
        if (params.has('end_date')) url.searchParams.set('end_date', params.get('end_date'));

        fetch(url)
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('analytics-content').classList.remove('hidden');
                
                if (data.length === 0) {
                    deviceData.innerHTML = '<tr><td colspan="4" class="py-20 text-center text-slate-400 italic font-medium">Tidak ada data untuk periode ini.</td></tr>';
                    return;
                }

                const stats = { device: {}, os: {}, browser: {} };
                data.forEach(i => {
                    stats.device[i.deviceCategory] = (stats.device[i.deviceCategory] || 0) + parseInt(i.totalUsers);
                    stats.os[i.operatingSystem] = (stats.os[i.operatingSystem] || 0) + parseInt(i.totalUsers);
                    stats.browser[i.browser] = (stats.browser[i.browser] || 0) + parseInt(i.totalUsers);
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
                                size: '80%',
                                labels: {
                                    show: true,
                                    name: { show: false },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                        fontWeight: 900,
                                        color: isDark ? '#f8fafc' : '#0f172a',
                                        offsetY: 5,
                                        formatter: (val) => parseInt(val).toLocaleString()
                                    },
                                    total: {
                                        show: true,
                                        label: 'TOTAL',
                                        formatter: (w) => {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString();
                                        }
                                    }
                                }
                            }
                        }
                    },
                    stroke: { show: false }
                });
                
                new ApexCharts(document.getElementById('deviceChart'), cfg(Object.keys(stats.device), Object.values(stats.device), ['#f59e0b', '#fbbf24', '#fcd34d'])).render();
                new ApexCharts(document.getElementById('osChart'), cfg(Object.keys(stats.os), Object.values(stats.os), ['#10b981', '#34d399', '#6ee7b7'])).render();
                new ApexCharts(document.getElementById('browserChart'), cfg(Object.keys(stats.browser), Object.values(stats.browser), ['#3b82f6', '#60a5fa', '#93c5fd'])).render();

                deviceData.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <i class="fa-solid ${getDeviceIcon(item.deviceCategory)} mr-4 text-slate-300 group-hover:text-amber-500 transition-colors"></i>
                                <span class="font-bold text-slate-900 text-xs uppercase tracking-tight">${item.deviceCategory || 'Lainnya'}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">${item.operatingSystem || '-'}</td>
                        <td class="px-8 py-6 text-[10px] font-bold text-slate-400 italic">${item.browser || '-'}</td>
                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-amber-50 text-amber-700 text-[10px] font-black rounded-lg border border-amber-100">
                                ${parseInt(item.totalUsers).toLocaleString()} User
                            </span>
                        </td>
                    `;
                    deviceData.appendChild(row);
                });
            });

        function getDeviceIcon(d) {
            d = (d||'').toLowerCase();
            return 'fa-fw ' + (d.includes('mobile') ? 'fa-mobile-screen-button' : (d.includes('tablet') ? 'fa-tablet-screen-button' : 'fa-desktop'));
        }
    });
</script>

<?= $this->endSection() ?>