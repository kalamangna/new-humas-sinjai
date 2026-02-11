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
                    <span class="text-slate-400">Program Prioritas</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Agenda Pembangunan</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Program Prioritas
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <?php if (!empty($posts)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php foreach ($posts as $post): ?>
                <article class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200">
                    <div class="relative h-64 overflow-hidden">
                        <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
                            <?php if (!empty($post['thumbnail'])) : ?>
                                <img src="<?= esc($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php else: ?>
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center">
                                    <i class="fas fa-fw fa-bullseye text-slate-200 text-6xl"></i>
                                </div>
                            <?php endif; ?>
                        </a>

                        <!-- Category Badge -->
                        <?php if (!empty($post['categories'])) : ?>
                            <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                                <?php foreach ($post['categories'] as $category) : ?>
                                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>"
                                        class="px-4 py-1.5 bg-blue-800 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-xl shadow-blue-900/20 hover:bg-blue-900 transition-colors">
                                        <?= esc($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-10 flex flex-col flex-1">
                        <h2 class="text-2xl font-bold text-slate-900 mb-5 line-clamp-2 leading-tight group-hover:text-blue-900 transition-colors tracking-tight">
                            <a href="<?= base_url('post/' . esc($post['slug'])) ?>">
                                <?= esc($post['title']) ?>
                            </a>
                        </h2>

                        <p class="text-slate-600 text-sm mb-10 line-clamp-3 leading-relaxed font-medium">
                            <?= word_limiter(strip_tags($post['content']), 25) ?>
                        </p>

                        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-bold uppercase tracking-wider">
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
            <div class="mt-10 flex flex-col md:flex-row items-center justify-between border-t-2 border-slate-100 pt-12">
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Menampilkan <span class="text-slate-900"><?= number_format($pager->getCurrentPage() * $pager->getPerPage() - ($pager->getPerPage() - 1)) ?>-<?= number_format(min($pager->getCurrentPage() * $pager->getPerPage(), $pager->getTotal())) ?></span> Data Program
                </div>
                <div>
                    <?= $pager->links('default', 'custom_bootstrap') ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-fw fa-inbox text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight uppercase">Data Belum Tersedia</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Saat ini belum ada data program prioritas yang tersedia dalam sistem. Silakan periksa kembali beberapa saat lagi.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fas fa-fw fa-home mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>