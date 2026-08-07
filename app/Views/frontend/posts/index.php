<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Berita' => current_url()
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fa-solid fa-fw fa-house mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Semua Berita</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Informasi Publik</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            <?= esc($seo['title']) ?>
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <?php if (!empty($posts)) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php foreach ($posts as $post) : ?>
                <?= view('components/post_card', ['post' => $post]) ?>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
            <div class="mt-10 flex flex-col md:flex-row items-center justify-between border-t-2 border-slate-100 pt-12">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Menampilkan <span class="text-slate-900"><?= number_format($pager->getCurrentPage() * $pager->getPerPage() - ($pager->getPerPage() - 1)) ?>-<?= number_format(min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal())) ?></span> dari <span class="text-blue-800"><?= number_format($pager->getTotal()) ?></span> Berita
                </div>
                <div>
                    <?= $pager->links('default', 'custom_pager') ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <div class="text-center py-32 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fa-solid fa-fw fa-inbox text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Belum Ada Berita</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Saat ini belum ada informasi berita yang dipublikasikan. Silakan kembali beberapa saat lagi untuk mendapatkan pembaruan terkini.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fa-solid fa-fw fa-house mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>