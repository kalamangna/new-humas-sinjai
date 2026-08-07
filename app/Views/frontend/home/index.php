<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero / Carousel Section -->
<section class="relative bg-slate-50 pt-6 md:pt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if (!empty($slides)): ?>
            <div id="hero-carousel" class="relative w-full bg-slate-900 rounded-xl md:rounded-2xl overflow-hidden shadow-xl border border-slate-200">
                <?php foreach ($slides as $index => $slide): ?>
                    <div class="carousel-slide transition-opacity duration-1000 ease-in-out <?= $index === 0 ? 'relative opacity-100 z-10' : 'absolute inset-0 opacity-0 z-0' ?>" data-index="<?= $index ?>">
                        <?php 
                            $slidePath = $slide['image_path'] ?? '';
                            $slideSrc = filter_var($slidePath, FILTER_VALIDATE_URL) ? $slidePath : (!empty($slidePath) ? base_url($slidePath) : '');
                        ?>
                        <img src="<?= $slideSrc ?>" class="w-full h-auto object-contain block" alt="Slide <?= $index + 1 ?>" width="3870" height="1227" <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>>
                    </div>
                <?php endforeach; ?>

            <!-- Controls -->
            <button id="prev-slide" class="hidden md:block absolute left-2 md:left-8 top-1/2 -translate-y-1/2 z-30 bg-blue-950/40 hover:bg-blue-900 text-white p-2 md:p-4 rounded-xl md:rounded-2xl transition-all border border-white/10 backdrop-blur-sm shadow-2xl">
                <i class="fa-solid fa-fw fa-chevron-left text-sm md:text-xl"></i>
            </button>
            <button id="next-slide" class="hidden md:block absolute right-2 md:right-8 top-1/2 -translate-y-1/2 z-30 bg-blue-950/40 hover:bg-blue-900 text-white p-2 md:p-4 rounded-xl md:rounded-2xl transition-all border border-white/10 backdrop-blur-sm shadow-2xl">
                <i class="fa-solid fa-fw fa-chevron-right text-sm md:text-xl"></i>
            </button>

            <!-- Indicators -->
            <div class="hidden md:flex absolute bottom-6 left-1/2 -translate-x-1/2 z-30 space-x-3">
                <?php foreach ($slides as $index => $slide): ?>
                    <button class="carousel-indicator w-2.5 h-2.5 rounded-full transition-all border border-white/20 <?= $index === 0 ? 'bg-blue-600 w-8' : 'bg-white/40' ?>" data-index="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured News Grid -->
<section class="pt-16 md:pt-24 pb-12 md:pb-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight uppercase flex items-center">
                    Berita Terbaru
                </h1>
            </div>
            <div class="w-24 h-1.5 bg-blue-900 rounded-full shadow-lg shadow-blue-900/20"></div>
        </div>        
        
        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12">
                
                <!-- Kiri: Headline Utama (65%) -->
                <div class="lg:col-span-8">
                    <?php $headline = $posts[0]; ?>
                    <article class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-slate-200 h-full flex flex-col">
                        <div class="relative aspect-video overflow-hidden bg-slate-100">
                            <a href="<?= base_url('post/' . esc($headline['slug'] ?? '')) ?>" class="block h-full w-full">
                                <?php 
                                    $thumbPath = $headline['thumbnail'] ?? '';
                                    $thumbSrc = filter_var($thumbPath, FILTER_VALIDATE_URL) ? $thumbPath : (!empty($thumbPath) ? base_url($thumbPath) : '');
                                ?>
                                <?php if (!empty($thumbSrc)) : ?>
                                    <img loading="lazy" src="<?= $thumbSrc ?>" alt="<?= esc($headline['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fa-solid fa-fw fa-image text-slate-300 text-6xl"></i>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="p-8 md:p-10 flex flex-col flex-1">
                            <?php if (!empty($headline['categories'])) : ?>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php foreach ($headline['categories'] as $category) : ?>
                                        <a href="<?= base_url('category/' . esc($category['slug'])) ?>" class="px-4 py-1 bg-blue-50 text-blue-900 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-blue-100 transition-colors border border-blue-100">
                                            <?= esc($category['name']) ?>
                                        </a>
                                    <?php break; endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-5 line-clamp-3 leading-tight group-hover:text-blue-900 transition-colors tracking-tight">
                                <a href="<?= base_url('post/' . esc($headline['slug'] ?? '')) ?>">
                                    <?= esc($headline['title']) ?>
                                </a>
                            </h2>
                            
                            <p class="text-slate-600 text-base mb-8 line-clamp-3 leading-relaxed font-medium">
                                <?= word_limiter(strip_tags($headline['content']), 35) ?>
                            </p>

                            <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-500 font-black uppercase tracking-[0.2em]">
                                <span class="flex items-center">
                                    <i class="fa-regular fa-fw fa-calendar-days mr-2 text-blue-900"></i>
                                    <?= format_date($headline['published_at'] ?: ($headline['created_at'] ?: date('Y-m-d')), 'date_only') ?>
                                </span>
                                <span class="flex items-center">
                                    <i class="fa-regular fa-fw fa-user mr-2 text-blue-900"></i>
                                    <?= esc($headline['author_name'] ?? 'Admin') ?>
                                </span>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Kanan: List Berita Lainnya (35%) -->
                <div class="lg:col-span-4 flex flex-col space-y-6">
                    <?php foreach ($posts as $index => $post): ?>
                        <?php if ($index > 0 && $index <= 4): ?>
                            <article class="group bg-white rounded-3xl p-4 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-slate-100 flex gap-5 items-center">
                                <div class="flex-shrink-0 w-28 h-28 md:w-32 md:h-32 rounded-2xl overflow-hidden bg-slate-50 relative">
                                    <a href="<?= base_url('post/' . esc($post['slug'] ?? '')) ?>" class="block h-full w-full">
                                        <?php 
                                            $pThumbPath = $post['thumbnail'] ?? '';
                                            $pThumbSrc = filter_var($pThumbPath, FILTER_VALIDATE_URL) ? $pThumbPath : (!empty($pThumbPath) ? base_url($pThumbPath) : '');
                                        ?>
                                        <?php if (!empty($pThumbSrc)) : ?>
                                            <img loading="lazy" src="<?= $pThumbSrc ?>" alt="<?= esc($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fa-solid fa-fw fa-image text-slate-200 text-2xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="flex-1 py-1 pr-2 min-w-0">
                                    <?php if (!empty($post['categories'])) : ?>
                                        <div class="mb-2">
                                            <span class="inline-block max-w-full truncate text-[9px] font-black uppercase tracking-widest text-blue-800 bg-blue-50 px-2 py-1 rounded-md">
                                                <?= esc($post['categories'][0]['name']) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="text-sm md:text-base font-black text-slate-900 line-clamp-3 leading-snug group-hover:text-blue-800 transition-colors tracking-tight">
                                        <a href="<?= base_url('post/' . esc($post['slug'] ?? '')) ?>">
                                            <?= esc($post['title']) ?>
                                        </a>
                                    </h3>
                                    <div class="mt-3 text-[9px] font-bold text-slate-400 uppercase tracking-widest flex items-center">
                                        <i class="fa-regular fa-fw fa-calendar-days mr-1.5 text-slate-300"></i>
                                        <?= format_date($post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d')), 'date_only') ?>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- View All -->
            <div class="mt-12 text-center">
                <a href="<?= base_url('posts') ?>" class="inline-flex items-center px-12 py-6 bg-blue-900 text-white font-black uppercase tracking-[0.3em] text-xs rounded-2xl shadow-2xl shadow-blue-900/30 hover:bg-blue-950 hover:-translate-y-1 transition-all duration-300">
                    <i class="fa-solid fa-fw fa-list-ul mr-4"></i>
                    Lihat Semua Berita
                </a>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-[3rem] p-20 text-center shadow-sm border border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                    <i class="fa-solid fa-fw fa-inbox text-5xl"></i>
                </div>
                <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight uppercase">Belum Ada Informasi</h2>
                <p class="text-slate-500 mb-8 max-w-md mx-auto leading-relaxed font-medium">Saat ini belum ada pembaruan berita yang tersedia. Silakan periksa kembali dalam beberapa saat.</p>
                <a href="<?= base_url() ?>" class="text-blue-900 font-black uppercase tracking-widest text-xs hover:underline flex items-center justify-center">
                    <i class="fa-solid fa-fw fa-arrows-rotate mr-3"></i> Muat Ulang Halaman
                </a>
            </div>
        <?php endif; ?>    
    </div>
</section>

<!-- Popular News Section -->
<!-- Popular News Section -->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-100 pb-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase flex items-center">
                    Berita Terpopuler
                </h2>
            </div>
            <div class="w-16 h-1.5 bg-blue-900 rounded-full shadow-lg shadow-blue-900/20"></div>
        </div>

        <?php if (!empty($popular_posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
                <?php foreach ($popular_posts as $index => $popular): ?>
                    <article class="group relative flex gap-6 items-start bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-blue-50 hover:border-blue-100 transition-all duration-300">
                        <!-- Ranking Number -->
                        <div class="flex-shrink-0 text-5xl font-black text-slate-200 group-hover:text-blue-900/20 transition-colors w-12 text-center -mt-2">
                            <?= $index + 1 ?>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <?php if (!empty($popular['categories'])) : ?>
                                <div class="mb-3">
                                    <span class="text-[9px] font-black uppercase tracking-widest text-blue-800">
                                        <?= esc($popular['categories'][0]['name']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="text-base font-black text-slate-900 line-clamp-3 leading-snug group-hover:text-blue-900 transition-colors tracking-tight mb-4">
                                <a href="<?= base_url('post/' . esc($popular['slug'] ?? '')) ?>">
                                    <?= esc($popular['title']) ?>
                                </a>
                            </h3>
                            
                            <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-auto">
                                <span class="flex items-center">
                                    <i class="fa-regular fa-fw fa-calendar-days mr-2 text-slate-300"></i>
                                    <?= format_date($popular['published_at'] ?? 'now', 'date_only') ?>
                                </span>
                                <span class="flex items-center text-blue-800">
                                    <i class="fa-regular fa-fw fa-eye mr-1.5"></i>
                                    <?= number_format($popular['views'] ?? 0) ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12 bg-slate-50 rounded-3xl border border-slate-100">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada data popularitas berita.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Program Prioritas Section -->
<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6 border-b border-slate-200 pb-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight uppercase flex items-center">
                    Program Prioritas
                </h2>
            </div>
            <div class="w-24 h-1.5 bg-blue-900 rounded-full shadow-lg shadow-blue-900/20"></div>
        </div>

        <?php if (!empty($program_posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 lg:gap-12">
                <?php foreach ($program_posts as $post): ?>
                    <?= view('components/post_card', ['post' => $post]) ?>
                <?php endforeach; ?>
            </div>
            
            <!-- View All -->
            <div class="mt-12 text-center">
                <a href="<?= base_url('program-prioritas') ?>" class="inline-flex items-center px-12 py-6 bg-blue-900 text-white font-black uppercase tracking-[0.3em] text-xs rounded-2xl shadow-2xl shadow-blue-900/30 hover:bg-blue-950 hover:-translate-y-1 transition-all duration-300">
                    <i class="fa-solid fa-fw fa-star mr-4"></i>
                    Lihat Semua Program
                </a>
            </div>
        <?php else: ?>
            <div class="text-center py-12 bg-white rounded-3xl border border-slate-200 border-dashed shadow-sm">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada publikasi program prioritas saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/carousel.js') ?>"></script>
<?= $this->endSection() ?>