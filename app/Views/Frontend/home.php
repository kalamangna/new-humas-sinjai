<?= $this->extend('Layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero / Carousel Section -->
<section class="relative bg-slate-950 overflow-hidden">
    <?php if (!empty($slides)): ?>
        <div id="hero-carousel" class="relative w-full">
            <?php foreach ($slides as $index => $slide): ?>
                <!-- Use 'relative' for the first slide to give the container height, 'absolute' for others -->
                <div class="carousel-slide transition-opacity duration-1000 ease-in-out <?= $index === 0 ? 'relative opacity-100 z-10' : 'absolute inset-0 opacity-0 z-0' ?>" data-index="<?= $index ?>">
                    <img src="<?= esc($slide['image_path']) ?>"
                        class="w-full h-auto block"
                        alt="Slide <?= $index + 1 ?>">
                </div>
            <?php endforeach; ?>

            <!-- Controls -->
            <button id="prev-slide" class="hidden md:block absolute left-2 md:left-8 top-1/2 -translate-y-1/2 z-30 bg-blue-950/40 hover:bg-blue-900 text-white p-2 md:p-4 rounded-xl md:rounded-2xl transition-all border border-white/10 backdrop-blur-sm shadow-2xl">
                <i class="fas fa-fw fa-chevron-left text-sm md:text-xl"></i>
            </button>
            <button id="next-slide" class="hidden md:block absolute right-2 md:right-8 top-1/2 -translate-y-1/2 z-30 bg-blue-950/40 hover:bg-blue-900 text-white p-2 md:p-4 rounded-xl md:rounded-2xl transition-all border border-white/10 backdrop-blur-sm shadow-2xl">
                <i class="fas fa-fw fa-chevron-right text-sm md:text-xl"></i>
            </button>

            <!-- Indicators -->
            <div class="hidden md:flex absolute bottom-6 left-1/2 -translate-x-1/2 z-30 space-x-3">
                <?php foreach ($slides as $index => $slide): ?>
                    <button class="carousel-indicator w-2.5 h-2.5 rounded-full transition-all border border-white/20 <?= $index === 0 ? 'bg-blue-600 w-8' : 'bg-white/40' ?>" data-index="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Featured News Grid -->
<section class="py-4 md:py-8 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <p class="text-[11px] font-black text-blue-900 uppercase tracking-[0.5em] mb-4">Warta Terkini</p>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter uppercase">
                Berita Terbaru
            </h1>
            <div class="mt-8 w-24 h-2 bg-blue-900 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
        </div>        
                <?php if (!empty($posts)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                        <?php foreach ($posts as $index => $post): ?>
                            <?php if ($index < 6): ?>
                                <article class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200">
                                    <!-- Image Container -->
                                    <div class="relative h-64 overflow-hidden bg-slate-100">
                                        <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
                                            <?php if (!empty($post['thumbnail'])) : ?>
                                                <img src="<?= esc($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>" 
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-fw fa-image text-slate-300 text-6xl"></i>
                                                </div>
                                            <?php endif; ?>
                                        </a>
        
                                        <!-- Categories -->
                                        <?php if (!empty($post['categories'])) : ?>
                                            <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                                                <?php foreach ($post['categories'] as $category) : ?>
                                                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>" 
                                                        class="px-4 py-1.5 bg-blue-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg backdrop-blur-sm hover:bg-blue-950 transition-all shadow-xl">
                                                        <?= esc($category['name']) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
        
                                    <!-- Content -->
                                    <div class="p-10 flex flex-col flex-1">
                                        <h2 class="text-xl font-bold text-slate-900 mb-5 line-clamp-2 leading-tight group-hover:text-blue-900 transition-colors tracking-tight">
                                            <a href="<?= base_url('post/' . esc($post['slug'])) ?>">
                                                <?= esc($post['title']) ?>
                                            </a>
                                        </h2>
                                        
                                        <p class="text-slate-600 text-sm mb-10 line-clamp-3 leading-relaxed font-medium">
                                            <?= word_limiter(strip_tags($post['content']), 22) ?>
                                        </p>
        
                                        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">
                                            <span class="flex items-center">
                                                <i class="far fa-fw fa-calendar-alt mr-2 text-blue-900"></i>
                                                <?php
                                                    $dateField = $post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d'));
                                                    echo format_date($dateField, 'date_only');
                                                ?>
                                            </span>
                                            <span class="flex items-center">
                                                <i class="far fa-fw fa-user mr-2 text-blue-900"></i>
                                                <?= esc($post['author_name'] ?? 'Admin') ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
        
                    <!-- View All -->
                    <div class="mt-10 text-center">
                        <a href="<?= base_url('posts') ?>" class="inline-flex items-center px-12 py-6 bg-blue-900 text-white font-black uppercase tracking-[0.3em] text-xs rounded-2xl shadow-2xl shadow-blue-900/30 hover:bg-blue-950 hover:-translate-y-1 transition-all duration-300">
                            <i class="fas fa-fw fa-list-ul mr-4"></i>
                            Baca Selengkapnya
                        </a>
                    </div>
        
                <?php else: ?>
                    <div class="bg-white rounded-[3rem] p-20 text-center shadow-sm border border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                            <i class="fas fa-fw fa-inbox text-5xl"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight uppercase">Belum Ada Informasi</h2>
                        <p class="text-slate-500 mb-8 max-w-md mx-auto leading-relaxed font-medium">Saat ini belum ada pembaruan berita yang tersedia. Silakan periksa kembali dalam beberapa saat.</p>
                        <a href="<?= base_url() ?>" class="text-blue-900 font-black uppercase tracking-widest text-xs hover:underline flex items-center justify-center">
                            <i class="fas fa-fw fa-sync-alt mr-3"></i> Muat Ulang Halaman
                        </a>
                    </div>
                <?php endif; ?>    </div>
</section>

<script>
    // Pure JS Carousel with aspect ratio preservation
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const nextBtn = document.getElementById('next-slide');
        const prevBtn = document.getElementById('prev-slide');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((s, i) => {
                if (i === index) {
                    s.classList.replace('opacity-0', 'opacity-100');
                    s.classList.replace('z-0', 'z-10');
                    s.classList.replace('absolute', 'relative');
                } else {
                    s.classList.replace('opacity-100', 'opacity-0');
                    s.classList.replace('z-10', 'z-0');
                    s.classList.replace('relative', 'absolute');
                }
            });

            indicators.forEach((ind, i) => {
                if (i === index) {
                    ind.classList.replace('bg-white/40', 'bg-blue-600');
                    ind.classList.add('w-8');
                } else {
                    ind.classList.replace('bg-blue-600', 'bg-white/40');
                    ind.classList.remove('w-8');
                }
            });

            currentSlide = index;
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                let prev = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prev);
            });
        }

        // Auto play
        let autoPlay = setInterval(() => {
            if (slides.length > 1) {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }
        }, 7000);

        // Reset autoplay on interaction
        [nextBtn, prevBtn, ...indicators].forEach(el => {
            if (el) {
                el.addEventListener('click', () => {
                    clearInterval(autoPlay);
                    autoPlay = setInterval(() => {
                        let next = (currentSlide + 1) % slides.length;
                        showSlide(next);
                    }, 10000);
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>