<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Analitik: Halaman Terpopuler<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-wrap items-center gap-2">
    <span id="active-period-badge" class="inline-flex items-center h-10 px-3 bg-blue-50 text-blue-800 font-bold text-[11px] rounded-lg border border-blue-100">Semua Waktu</span>
    <a id="back-to-overview-link" href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
        <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali ke Ringkasan
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('partials/analytics_error_banner') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menganalisa Data Halaman...</span>
</div>

<div id="analytics-content" class="hidden">
    <div class="bg-white rounded-2xl sm:rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 sm:px-8 py-4 sm:py-6 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fa-solid fa-fw fa-list-ol mr-3 text-blue-800"></i>10 Halaman Paling Sering Dikunjungi
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-4 sm:px-6 py-3.5 sm:py-4">Halaman</th>
                        <th class="px-4 sm:px-6 py-3.5 sm:py-4">Path</th>
                        <th class="px-4 sm:px-6 py-3.5 sm:py-4 text-right w-1 whitespace-nowrap">Tayangan</th>
                        <th class="px-4 sm:px-6 py-3.5 sm:py-4 text-right w-1 whitespace-nowrap">Pengunjung</th>
                    </tr>
                </thead>
                <tbody id="top-pages-data" class="divide-y divide-slate-100"></tbody>
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
        const topPagesData = document.getElementById('top-pages-data');
        const url = buildApiUrl('<?= base_url('api/analytics/top-pages') ?>');

        fetchJson(url)
            .then(data => {
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                topPagesData.innerHTML = '';

                if (!Array.isArray(data) || data.length === 0) {
                    topPagesData.innerHTML = '<tr><td colspan="4" class="py-20 text-center text-slate-400 italic font-medium">Tidak ada data halaman untuk periode ini.</td></tr>';
                    return;
                }

                data.forEach((page, index) => {
                    const row = document.createElement('tr');
                    const titleText = escapeHtml(page.pageTitle || 'Tanpa Judul');
                    const pathText = escapeHtml(page.pagePath || '/');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-4 sm:px-6 py-3.5 sm:py-4">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-blue-50 text-blue-800 rounded-lg flex-shrink-0 flex items-center justify-center font-black text-xs mr-3 sm:mr-4 border border-blue-100 group-hover:bg-blue-800 group-hover:text-white transition-all">
                                    ${index + 1}
                                </span>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate max-w-xs sm:max-w-md lg:max-w-xl tracking-tight text-xs sm:text-sm" title="${titleText}">${titleText}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 sm:py-4">
                            <span class="px-2.5 sm:px-3 py-1 bg-slate-100 text-slate-500 font-mono text-[10px] rounded-md border border-slate-200 italic truncate max-w-xs inline-block" title="${pathText}">
                                ${pathText}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 sm:py-4 text-right text-xs sm:text-sm font-bold text-slate-700 whitespace-nowrap">
                            ${parseInt(page.screenPageViews || 0, 10).toLocaleString()} <span class="text-[9px] sm:text-[10px] text-slate-400 font-medium uppercase tracking-tighter ml-1">Dilihat</span>
                        </td>
                        <td class="px-4 sm:px-6 py-3.5 sm:py-4 text-right whitespace-nowrap">
                            <span class="px-2.5 sm:px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                ${parseInt(page.totalUsers || 0, 10).toLocaleString()} User
                            </span>
                        </td>
                    `;
                    topPagesData.appendChild(row);
                });
            })
            .catch((err) => {
                console.error(err);
                spinner.classList.add('hidden');
                content.classList.remove('hidden');
                showError('Gagal memuat daftar halaman terpopuler. Coba muat ulang halaman.');
                topPagesData.innerHTML = '<tr><td colspan="4" class="py-20 text-center text-rose-600 font-bold">Gagal menyinkronkan data halaman terpopuler.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>
