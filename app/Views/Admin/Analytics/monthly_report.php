<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Laporan Bulanan<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex items-center gap-3">
    <select id="month-select" class="bg-white border border-slate-200 rounded-lg text-xs font-bold uppercase tracking-wider px-4 py-2 focus:ring-2 focus:ring-blue-800 outline-none shadow-sm transition-all">
        <?php foreach ($months as $m): ?>
            <option value="<?= $m['year'] ?>/<?= $m['month'] ?>" <?= ($m['year'] == $year && $m['month'] == $month) ? 'selected' : '' ?>>
                <?= format_date($m['year'] . '-' . $m['month'] . '-01', 'month_year') ?>
            </option>
        <?php endforeach; ?>
    </select>
    <a href="<?= base_url("admin/analytics/download-monthly-report/{$year}/{$month}") ?>" id="download-pdf-btn" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-rose-700 transition-all shadow-lg shadow-rose-900/20">
        <i class="fas fa-fw fa-file-pdf mr-2" id="btn-icon"></i>
        <div id="loading-spinner" class="hidden w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2"></div>
        Download PDF
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden mb-10">
    <!-- Header -->
    <div class="px-8 py-10 bg-slate-50 border-b border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-xs font-black text-blue-800 uppercase tracking-[0.3em] mb-3 flex items-center">
                    <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Arsip Publikasi Konten
                </h3>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                    Laporan Bulanan - <?= format_date($year . '-' . $month . '-01', 'month_year') ?>
                </h2>
            </div>
            <div class="bg-white px-6 py-4 rounded-2xl border border-slate-200 shadow-sm text-center md:text-right">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Intensitas Berita</p>
                <h4 class="text-3xl font-black text-slate-900 tracking-tighter"><?= count($posts) ?> <span class="text-sm font-bold text-slate-400">Konten</span></h4>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Informasi Konten</th>
                    <th class="px-8 py-5">Ringkasan</th>
                    <th class="px-8 py-5">Tampilan</th>
                    <th class="px-8 py-5">Waktu Publikasi</th>
                    <th class="px-8 py-5 text-right">Tautan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 tracking-tight text-xs leading-relaxed max-w-xs group-hover:text-blue-800 transition-colors">
                                    <?= esc($post['title']) ?>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-[10px] text-slate-500 leading-relaxed max-w-sm line-clamp-2">
                                    <?= word_limiter(strip_tags($post['content']), 20) ?>
                                </p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                    <?= number_format($post['views']) ?> Views
                                </span>
                            </td>
                            <td class="px-8 py-6 text-[10px] font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">
                                <?= format_date($post['published_at'], 'date_only') ?>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="<?= base_url('post/' . esc($post['slug'])) ?>" target="_blank" class="inline-flex items-center p-2 bg-slate-100 text-slate-400 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all shadow-sm">
                                    <i class="fas fa-fw fa-external-link-alt text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                                <i class="fas fa-fw fa-inbox text-3xl"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Data Tidak Ditemukan</h4>
                            <p class="text-xs text-slate-400 mt-2">Tidak ada aktivitas publikasi pada periode ini.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('month-select').addEventListener('change', function() {
        const selected = this.value;
        window.location.href = `<?= base_url('admin/analytics/monthly-report/') ?>${selected}`;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const downloadBtn = document.getElementById('download-pdf-btn');
        const loadingSpinner = document.getElementById('loading-spinner');
        const btnIcon = document.getElementById('btn-icon');

        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                downloadBtn.classList.add('opacity-50', 'pointer-events-none');
                btnIcon.classList.add('hidden');
                loadingSpinner.classList.remove('hidden');

                // Re-enable button after a delay (simulating PDF generation start)
                setTimeout(() => {
                    downloadBtn.classList.remove('opacity-50', 'pointer-events-none');
                    btnIcon.classList.remove('hidden');
                    loadingSpinner.classList.add('hidden');
                }, 5000);
            });
        }
    });
</script>

<?= $this->endSection() ?>
