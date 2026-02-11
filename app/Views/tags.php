<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="flex mb-16" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400">Indeks Topik</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-20">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Klasifikasi Berita</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight">
            <i class="fas fa-tags text-blue-800 mr-5 opacity-20"></i><?= esc($title) ?>
        </h1>
        <div class="mt-8 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
        <p class="mt-8 text-lg text-slate-500 max-w-2xl mx-auto font-medium">Temukan berita berdasarkan topik dan label kata kunci yang anda cari.</p>
    </div>

    <?php if (!empty($tags)) : ?>
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 p-10 md:p-16">
                <div class="flex flex-wrap gap-4 justify-center">
                    <?php foreach ($tags as $tag) : ?>
                        <a href="<?= base_url('tag/' . esc($tag['slug'])) ?>"
                            class="inline-flex items-center px-6 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-700 hover:bg-blue-800 hover:text-white hover:border-blue-900 transition-all group shadow-sm">
                            <i class="fas fa-tag mr-3 text-slate-300 group-hover:text-sky-300 transition-colors"></i>
                            <span class="font-bold text-sm tracking-tight"><?= esc($tag['name']) ?></span>
                            <span class="ml-4 px-2 py-0.5 bg-slate-200 text-slate-500 text-[10px] font-black rounded-lg group-hover:bg-blue-900 group-hover:text-white transition-all">
                                <?= number_format($tag['post_count']) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-tags text-5xl"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Belum Ada Tag</h3>
            <p class="text-slate-500 mb-12 leading-relaxed">Saat ini sistem belum memiliki label kata kunci atau tag untuk klasifikasi berita. Silakan kembali lagi nanti.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fas fa-home mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>