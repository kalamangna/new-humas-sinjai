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
        <ol class="flex flex-wrap items-center gap-y-2 gap-x-1 md:gap-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
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
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight uppercase flex items-center">
                <?= esc($seo['title']) ?>
            </h1>
        </div>
        <div class="hidden md:block w-24 h-1.5 bg-blue-900 rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <?php if (!empty($posts)) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
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
        <div class="text-center py-20 max-w-xl mx-auto">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-5 text-slate-300">
                <i class="fa-solid fa-fw fa-inbox text-3xl"></i>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Berita</h2>
            <p class="text-slate-500 text-sm mb-6">Saat ini belum ada informasi berita yang dipublikasikan. Silakan kembali beberapa saat lagi.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-6 py-2.5 bg-slate-100 text-slate-600 font-bold uppercase tracking-widest text-[10px] rounded-lg hover:bg-slate-200 transition-all">
                <i class="fa-solid fa-fw fa-house mr-2"></i>Beranda
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>