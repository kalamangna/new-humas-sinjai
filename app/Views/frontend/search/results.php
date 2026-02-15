<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Pencarian' => current_url()
]) ?>
<?= $this->endSection() ?>

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
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Hasil Pencarian</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-10">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Hasil Penelusuran</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Pencarian Berita
        </h1>
        <p class="mt-6 text-lg text-slate-600 font-medium leading-relaxed">
            <?php if (!empty($query)) : ?>
                Menampilkan hasil untuk kata kunci: <span class="text-blue-800 font-black italic">"<?= esc($query) ?>"</span>
            <?php else : ?>
                Silakan masukkan kata kunci untuk memulai pencarian.
            <?php endif; ?>
        </p>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <?php if (!empty($posts)) : ?>
        <!-- Info Alert -->
        <div class="bg-blue-50 border-l-4 border-blue-800 p-6 mb-8 rounded-r-2xl shadow-sm flex items-center">
            <div class="bg-blue-800 p-2 rounded-lg mr-5 shadow-lg shadow-blue-900/20">
                <i class="fas fa-fw fa-info-circle text-white text-xl"></i>
            </div>
            <p class="text-slate-700 font-bold uppercase tracking-wider text-[11px]">
                Ditemukan <span class="text-blue-800 text-lg mx-1"><?= number_format(count($posts)) ?></span> Berita yang relevan dengan pencarian anda.
            </p>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php foreach ($posts as $post) : ?>
                <article class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200">
                    <div class="relative h-60 overflow-hidden">
                        <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
                            <?php
                            $thumbPath = $post['thumbnail'] ?? '';
                            $thumbSrc = filter_var($thumbPath, FILTER_VALIDATE_URL) ? $thumbPath : (!empty($thumbPath) ? base_url($thumbPath) : '');
                            ?>
                            <?php if (!empty($thumbSrc)) : ?>
                                <img loading="lazy" src="<?= $thumbSrc ?>" alt="<?= esc($post['title']) ?>"
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
                                <?= format_date($post['published_at'] ?? $post['created_at'] ?? 'now', 'date_only') ?>
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
                    Menampilkan <span class="text-slate-900"><?= number_format(count($posts)) ?></span> Data Hasil Penelusuran.
                </div>
                <div>
                    <?= $pager->links('default', 'custom_pager') ?>
                </div>
            </div>
        <?php endif; ?>

    <?php else : ?>
        <!-- No Results -->
        <div class="text-center py-12 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-4xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-fw fa-search-minus text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight uppercase">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-8 max-w-xl mx-auto leading-relaxed font-medium">
                Mohon maaf, sistem tidak menemukan informasi yang sesuai dengan kata kunci <span class="text-blue-800 font-black italic">"<?= esc($query) ?>"</span>. Silakan gunakan kata kunci yang lebih spesifik atau umum.
            </p>

            <div class="flex flex-wrap gap-4 justify-center mb-10">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center px-8 py-4 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                    <i class="fas fa-fw fa-home mr-3 text-base"></i>Beranda
                </a>
                <a href="<?= base_url('categories') ?>" class="inline-flex items-center px-8 py-4 bg-slate-100 text-slate-700 font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-slate-200 transition-all border border-slate-200">
                    <i class="fas fa-fw fa-folder mr-3 text-base"></i>Semua Kategori
                </a>
            </div>

            <!-- Search Tips -->
            <div class="max-w-3xl mx-auto bg-slate-50 rounded-[2rem] p-10 border border-slate-100 text-left">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-8 flex items-center">
                    <i class="fas fa-fw fa-lightbulb text-yellow-500 mr-3 text-lg"></i> Panduan Optimasi Pencarian
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 text-blue-800 p-1.5 rounded-lg flex-shrink-0 mt-0.5"><i class="fas fa-fw fa-check text-[10px]"></i></div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">Gunakan kata kunci yang lebih umum untuk cakupan informasi yang lebih luas.</p>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 text-blue-800 p-1.5 rounded-lg flex-shrink-0 mt-0.5"><i class="fas fa-fw fa-check text-[10px]"></i></div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">Pastikan ejaan kata kunci sudah benar sesuai dengan KBBI atau istilah teknis.</p>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 text-blue-800 p-1.5 rounded-lg flex-shrink-0 mt-0.5"><i class="fas fa-fw fa-check text-[10px]"></i></div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">Coba gunakan variasi kata lain atau sinonim yang berkaitan dengan Tag berita.</p>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="bg-blue-100 text-blue-800 p-1.5 rounded-lg flex-shrink-0 mt-0.5"><i class="fas fa-fw fa-check text-[10px]"></i></div>
                        <p class="text-slate-600 text-sm font-medium leading-relaxed">Gunakan menu Kategori jika anda ingin mencari berita berdasarkan Tag khusus.</p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>