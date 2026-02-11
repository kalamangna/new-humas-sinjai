<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Konten Terpopuler<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/analytics/overview') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div id="loading-spinner" class="py-20 flex flex-col items-center justify-center space-y-4">
    <div class="w-12 h-12 border-4 border-slate-100 border-t-blue-800 rounded-full animate-spin"></div>
    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menganalisa Data Halaman...</span>
</div>

<div id="analytics-content" class="hidden">
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-list-ol mr-3 text-blue-800"></i>TOP 10 HALAMAN TERPOPULER
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                        <th class="px-8 py-5">Peringkat & Judul Halaman</th>
                        <th class="px-8 py-5">Alamat (Path)</th>
                        <th class="px-8 py-5 w-1 whitespace-nowrap">Intensitas</th>
                        <th class="px-8 py-5 text-right w-1 whitespace-nowrap">Unique Users</th>
                    </tr>
                </thead>
                <tbody id="top-pages-data" class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const topPagesData = document.getElementById('top-pages-data');

        fetch('<?= base_url('api/analytics/top-pages') ?>')
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('analytics-content').classList.remove('hidden');
                topPagesData.innerHTML = '';

                data.forEach((page, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <span class="w-8 h-8 bg-blue-50 text-blue-800 rounded-lg flex-shrink-0 flex items-center justify-center font-black text-xs mr-4 border border-blue-100 group-hover:bg-blue-800 group-hover:text-white transition-all">
                                    ${index + 1}
                                </span>
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-900 truncate max-w-xs md:max-w-md tracking-tight">${page.pageTitle || 'Tanpa Judul'}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 font-mono text-[10px] rounded-md border border-slate-200 italic truncate max-w-[150px] inline-block">
                                ${page.pagePath || '/'}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold text-slate-700">
                            ${parseInt(page.screenPageViews).toLocaleString()} <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter ml-1">Hits</span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                ${parseInt(page.totalUsers).toLocaleString()} User
                            </span>
                        </td>
                    `;
                    topPagesData.appendChild(row);
                });
            })
            .catch(() => {
                topPagesData.innerHTML = '<tr><td colspan="4" class="py-20 text-center text-red-500 font-bold">Gagal sinkronisasi data analitik.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>