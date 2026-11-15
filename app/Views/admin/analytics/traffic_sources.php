<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik: Sumber Kunjungan<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-wrap items-center gap-2">
    <span id="active-period-badge" class="inline-flex items-center h-10 px-3 bg-emerald-50 text-emerald-700 font-bold text-[11px] rounded-lg border border-emerald-100">Semua Waktu</span>
    <a id="back-to-overview-link" href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
        <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali ke Ringkasan
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('partials/analytics_error_banner') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-emerald-600 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Melacak Jalur Akses...</span>
</div>

<div id="analytics-content" class="hidden">
    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 sm:px-8 py-4 sm:py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-4">
            <div class="flex items-center">
                <i class="fa-solid fa-fw fa-globe mr-3 text-emerald-600"></i>
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Analisis Sumber Kunjungan</h2>
            </div>
            <span id="traffic-total-rows" class="text-[11px] font-bold text-slate-500"></span>
        </div>

        <div class="overflow-auto max-h-96">
            <table class="w-full text-left border-collapse">
                <thead class="sticky top-0 z-10 bg-slate-50">
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">
                        <th class="px-4 sm:px-6 py-3.5 w-1">No</th>
                        <th class="px-4 sm:px-6 py-3.5">Sumber</th>
                        <th class="px-4 sm:px-6 py-3.5 w-1">Media</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Sesi</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Tayangan</th>
                        <th class="px-4 sm:px-6 py-3.5 text-right w-1">Pengunjung</th>
                    </tr>
                </thead>
                <tbody id="traffic-sources-data" class="divide-y divide-slate-100 whitespace-nowrap"></tbody>
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
        const trafficSourcesData = document.getElementById('traffic-sources-data');
        const trafficTotalRows = document.getElementById('traffic-total-rows');
        const url = buildApiUrl('<?= base_url('api/analytics/traffic-sources') ?>');

        fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                trafficSourcesData.innerHTML = '';

                if (!Array.isArray(data) || data.length === 0) {
                    trafficSourcesData.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-slate-400 italic font-medium">Tidak ada data sumber kunjungan untuk periode ini.</td></tr>';
                    return;
                }

                if (trafficTotalRows) {
                    trafficTotalRows.textContent = `${data.length} sumber teratas`;
                }

                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-4 sm:px-6 py-3.5 text-xs font-bold text-slate-400">${index + 1}</td>
                        <td class="px-4 sm:px-6 py-3.5">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full mr-3 opacity-60 group-hover:opacity-100 transition-opacity flex-shrink-0"></div>
                                <span class="font-bold text-slate-900 text-xs">${escapeHtml(item.sessionSource || 'Langsung')}</span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5">
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider rounded border border-slate-200">${escapeHtml(item.sessionMedium || 'Tanpa Media')}</span>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 text-right text-xs font-bold text-slate-700">${parseInt(item.sessions || 0, 10).toLocaleString('id-ID')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right text-xs font-bold text-slate-700">${parseInt(item.screenPageViews || 0, 10).toLocaleString('id-ID')}</td>
                        <td class="px-4 sm:px-6 py-3.5 text-right">
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black rounded-lg border border-emerald-100">
                                ${parseInt(item.totalUsers || 0, 10).toLocaleString('id-ID')} User
                            </span>
                        </td>
                    `;
                    trafficSourcesData.appendChild(row);
                });
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                showError('Gagal memuat data sumber kunjungan. Coba muat ulang halaman.');
                trafficSourcesData.innerHTML = '<tr><td colspan="6" class="py-16 text-center text-rose-600 font-bold">Gagal menyinkronkan data sumber kunjungan.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>
