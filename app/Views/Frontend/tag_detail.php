<?= $this->extend('Layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-fw fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <a href="<?= base_url('tags') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">Tag Berita</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px]"><?= esc($tag['name'] ?? 'Detail') ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Informasi Terkait</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Topik: <?= esc($tag['name'] ?? 'Tag') ?>
        </h1>
        <?php if (!empty($tag['description'])) : ?>
            <p class="mt-6 text-lg text-slate-600 max-w-2xl mx-auto font-medium leading-relaxed"><?= esc($tag['description']) ?></p>
        <?php endif; ?>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <!-- Posts Grid -->
    <?php if (!empty($posts)) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php foreach ($posts as $post) : ?>
                <article class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200">
                    <div class="relative h-60 overflow-hidden">
                        <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
                            <?php if (!empty($post['thumbnail'])) : ?>
                                <img src="<?= filter_var($post['thumbnail'], FILTER_VALIDATE_URL) ? $post['thumbnail'] : base_url($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                    <i class="fas fa-fw fa-newspaper text-slate-200 text-6xl"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="p-8 flex flex-col flex-1">
                        <h2 class="text-xl font-bold text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-blue-800 transition-colors tracking-tight">
                            <a href="<?= base_url('post/' . esc($post['slug'])) ?>">
                                <?= esc($post['title']) ?>
                            </a>
                        </h2>
                        
                        <p class="text-slate-600 text-sm mb-8 line-clamp-3 leading-relaxed font-medium">
                            <?= word_limiter(strip_tags($post['content']), 22) ?>
                        </p>

                        <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                            <span class="flex items-center">
                                <i class="far fa-fw fa-calendar-alt mr-2 text-blue-700"></i>
                                <?php
                                    $dateField = $post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d'));
                                    echo format_date($dateField, 'date_only');
                                ?>
                            </span>
                            <span class="flex items-center">
                                <i class="far fa-fw fa-user mr-2 text-blue-700"></i>
                                <?= esc($post['author_name'] ?? 'Admin') ?>
                            </span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
            <div class="mt-12 flex flex-col md:flex-row items-center justify-between border-t-2 border-slate-100 pt-12">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Menampilkan Arsip Berita berdasarkan topik <span class="text-blue-800">#<?= esc($tag['name'] ?? '') ?></span>.
                </div>
                <div>
                    <?= $pager->links('default', 'custom_bootstrap') ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <!-- Empty State -->
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-fw fa-tag text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight uppercase">Berita Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Maaf, saat ini belum tersedia berita yang dikaitkan dengan topik ini. Silakan jelajahi topik lainnya atau kembali ke beranda.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center px-8 py-4 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                    <i class="fas fa-fw fa-arrow-left mr-3 text-base"></i>Beranda Utama
                </a>
                <a href="<?= base_url('tags') ?>" class="inline-flex items-center px-8 py-4 bg-slate-100 text-slate-700 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-slate-200 transition-all border border-slate-200">
                    <i class="fas fa-fw fa-tags mr-3 text-base"></i>Indeks Topik
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
