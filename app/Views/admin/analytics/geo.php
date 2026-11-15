<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik: Sebaran Lokasi<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-wrap items-center gap-2">
    <span id="active-period-badge" class="inline-flex items-center h-10 px-3 bg-sky-50 text-sky-700 font-bold text-[11px] rounded-lg border border-sky-100">Semua Waktu</span>
    <a id="back-to-overview-link" href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
        <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali ke Ringkasan
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('partials/analytics_error_banner') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-sky-600 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memetakan Lokasi Akses...</span>
</div>

<div id="analytics-content" class="hidden space-y-6">
    <!-- Ringkasan Proporsi per Negara (Full Width) -->
    <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200">
        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-6 flex items-center border-b border-slate-100 pb-4">
            <i class="fa-solid fa-fw fa-chart-pie mr-3 text-sky-600"></i>Proporsi Pengunjung per Negara
        </h3>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
            <div class="lg:col-span-5">
                <div id="countryChart" class="h-72"></div>
            </div>
            <div class="lg:col-span-7">
                <div id="country-breakdown" class="space-y-4"></div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Sebaran Pengunjung (Full Width) -->
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 sm:px-8 py-4 sm:py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-4">
            <div class="flex items-center">
                <i class="fa-solid fa-fw fa-map-marker-alt mr-3 text-sky-600"></i>
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Rincian Sebaran Pengunjung</h3>
            </div>
            <span id="geo-total-rows" class="text-[11px] font-bold text-slate-500"></span>
        </div>
        <div class="overflow-auto max-h-96">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        <th class="px-4 sm:px-6 py-3.5 w-1">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Negara</th>
                        <th class="px-4 sm:px-6 py-3.5">Wilayah</th>
                        <th class="px-4 sm:px-6 py-3.5">Kota</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Sesi</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Pengunjung</th>
                    </tr>
                </thead>
                <tbody id="geo-data" class="divide-y divide-slate-100 whitespace-nowrap"></tbody>
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
        const geoData = document.getElementById('geo-data');
        const geoTotalRows = document.getElementById('geo-total-rows');
        const countryChartEl = document.getElementById('countryChart');
        const countryBreakdownEl = document.getElementById('country-breakdown');
        const isDark = document.documentElement.classList.contains('dark');
        const url = buildApiUrl('<?= base_url('api/analytics/geo') ?>');
        const chartColors = ['#0369a1', '#0284c7', '#0ea5e9', '#38bdf8', '#7dd3fc', '#bae6fd', '#94a3b8'];

        fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                content.classList.remove('hidden');

                if (!Array.isArray(data) || data.length === 0) {
                    countryChartEl.innerHTML = '<div class="h-72 flex items-center justify-center text-xs font-bold text-slate-400">Belum ada data lokasi pada periode ini.</div>';
                    countryBreakdownEl.innerHTML = '<p class="text-xs text-slate-400 italic">Tidak ada ringkasan negara untuk ditampilkan.</p>';
                    geoData.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-slate-400 italic font-medium">Tidak ada data lokasi untuk periode ini.</td></tr>';
                    return;
                }

                const countryUsers = {};
                let grandTotalUsers = 0;
                data.forEach(item => {
                    const c = item.country || 'Tidak Diketahui';
                    const users = parseInt(item.totalUsers || 0, 10);
                    countryUsers[c] = (countryUsers[c] || 0) + users;
                    grandTotalUsers += users;
                });

                const sortedCountries = Object.entries(countryUsers).sort((a, b) => b[1] - a[1]);
                const topCountries = sortedCountries.slice(0, 6);
                const otherTotal = sortedCountries.slice(6).reduce((acc, [, val]) => acc + val, 0);
                if (otherTotal > 0) {
                    topCountries.push(['Lainnya', otherTotal]);
                }

                const options = {
                    chart: { type: 'donut', height: 288, fontFamily: 'inherit' },
                    theme: { mode: isDark ? 'dark' : 'light' },
                    series: topCountries.map(([, val]) => val),
                    labels: topCountries.map(([label]) => label),
                    colors: chartColors,
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    stroke: { show: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    name: { show: true, fontSize: '12px', fontWeight: 700 },
                                    value: { show: true, fontSize: '20px', fontWeight: 900, formatter: (val) => parseInt(val, 10).toLocaleString('id-ID') },
                                    total: {
                                        show: true,
                                        label: 'TOTAL',
                                        formatter: (w) => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toLocaleString('id-ID')
                                    }
                                }
                            }
                        }
                    }
                };

                new ApexCharts(countryChartEl, options).render();

                countryBreakdownEl.innerHTML = '';
                topCountries.forEach(([label, count], idx) => {
                    const percentNum = grandTotalUsers > 0 ? (count / grandTotalUsers) * 100 : 0;
                    const percentWidth = percentNum.toFixed(1);
                    const percentLabel = percentNum.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
                    const barColor = chartColors[idx] || '#94a3b8';
                    countryBreakdownEl.innerHTML += `
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: ${barColor}"></span>
                                    <span class="font-bold text-slate-800 truncate">${escapeHtml(label)}</span>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <span class="text-slate-500 font-semibold">${percentLabel}%</span>
                                    <span class="font-black text-slate-900">${count.toLocaleString('id-ID')} User</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all" style="width: ${percentWidth}%; background-color: ${barColor}"></div>
                            </div>
                        </div>
                    `;
                });

                if (geoTotalRows) {
                    geoTotalRows.textContent = `${data.length} lokasi teratas`;
                }

                geoData.innerHTML = '';
                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors';
                    row.innerHTML = `
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-bold text-slate-400">${index + 1}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-bold text-slate-900">${escapeHtml(item.country || 'Tidak Diketahui')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-medium text-slate-600">${escapeHtml(item.region || '-')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-xs text-slate-500">${escapeHtml(item.city || '-')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right text-xs font-bold text-slate-700">${parseInt(item.sessions || 0, 10).toLocaleString('id-ID')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right">
                            <span class="px-2.5 py-1 bg-sky-50 text-sky-700 text-[10px] font-black rounded-lg border border-sky-100">
                                ${parseInt(item.totalUsers || 0, 10).toLocaleString('id-ID')} User
                            </span>
                        </td>
                    `;
                    geoData.appendChild(row);
                });
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                showError('Gagal memuat data sebaran lokasi. Coba muat ulang halaman.');
                countryChartEl.innerHTML = '<div class="h-72 flex items-center justify-center text-xs font-bold text-rose-600">Gagal memuat grafik lokasi.</div>';
                countryBreakdownEl.innerHTML = '';
                geoData.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-rose-600 font-bold">Gagal menyinkronkan data sebaran lokasi.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>
