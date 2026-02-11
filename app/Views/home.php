<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Hero / Carousel Section -->
<section class="relative bg-slate-900 overflow-hidden border-b-8 border-blue-900">
    <?php if (!empty($slides)): ?>
        <div id="hero-carousel" class="relative h-[400px] md:h-[650px]">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-slide absolute inset-0 transition-opacity duration-1000 ease-in-out <?= $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' ?>" data-index="<?= $index ?>">
                    <img src="<?= esc($slide['image_path']) ?>" class="w-full h-full object-cover md:object-contain bg-slate-950" alt="Slide">
                    <!-- Subtle Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                </div>
            <?php endforeach; ?>
            
            <!-- Controls -->
            <button id="prev-slide" class="absolute left-6 top-1/2 -translate-y-1/2 z-20 bg-slate-900/40 hover:bg-blue-800 text-white p-4 rounded-full transition-all border border-white/10 backdrop-blur-sm">
                <i class="fas fa-chevron-left text-xl"></i>
            </button>
            <button id="next-slide" class="absolute right-6 top-1/2 -translate-y-1/2 z-20 bg-slate-900/40 hover:bg-blue-800 text-white p-4 rounded-full transition-all border border-white/10 backdrop-blur-sm">
                <i class="fas fa-chevron-right text-xl"></i>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex space-x-3">
                <?php foreach ($slides as $index => $slide): ?>
                    <button class="carousel-indicator w-3 h-3 rounded-full transition-all border border-white/20 <?= $index === 0 ? 'bg-blue-600 w-8' : 'bg-white/40' ?>" data-index="<?= $index ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- Featured News Grid -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 flex items-center justify-center tracking-tight">
                <i class="fas fa-newspaper text-blue-800 mr-5"></i>
                BERITA TERBARU
            </h2>
            <div class="mt-6 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
            <p class="mt-4 text-slate-500 font-medium uppercase tracking-widest text-sm">Informasi Terkini Pemerintah Kabupaten Sinjai</p>
        </div>

        <?php if (!empty($posts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php foreach ($posts as $index => $post): ?>
                    <?php if ($index < 6): ?>
                        <article class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200">
                            <!-- Image Container -->
                            <div class="relative h-60 overflow-hidden">
                                <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
                                    <?php if (!empty($post['thumbnail'])) : ?>
                                        <img src="<?= esc($post['thumbnail']) ?>" alt="<?= esc($post['title']) ?>" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center">
                                            <i class="fas fa-image text-slate-300 text-6xl"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>

                                <!-- Categories -->
                                <?php if (!empty($post['categories'])) : ?>
                                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                                        <?php foreach ($post['categories'] as $category) : ?>
                                            <a href="<?= base_url('category/' . esc($category['slug'])) ?>" 
                                                class="px-3 py-1 bg-blue-800 text-white text-[10px] font-bold uppercase tracking-wider rounded-md backdrop-blur-sm hover:bg-blue-900 transition-colors">
                                                <?= esc($category['name']) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Content -->
                            <div class="p-8 flex flex-col flex-1">
                                <h3 class="text-xl font-bold text-slate-900 mb-4 line-clamp-2 leading-tight group-hover:text-blue-800 transition-colors tracking-tight">
                                    <a href="<?= base_url('post/' . esc($post['slug'])) ?>">
                                        <?= esc($post['title']) ?>
                                    </a>
                                </h3>
                                
                                <p class="text-slate-600 text-sm mb-8 line-clamp-3 leading-relaxed">
                                    <?= word_limiter(strip_tags($post['content']), 22) ?>
                                </p>

                                <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-bold uppercase tracking-wider">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-blue-700"></i>
                                        <?php
                                            $dateField = $post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d'));
                                            echo format_date($dateField, 'date_only');
                                        ?>
                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-user mr-2 text-blue-700"></i>
                                        <?= esc($post['author_name'] ?? 'Admin') ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- View All -->
            <div class="mt-20 text-center">
                <a href="<?= base_url('posts') ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-sm rounded-xl shadow-xl hover:bg-blue-900 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-list-ul mr-3"></i>
                    LIHAT SEMUA BERITA
                </a>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300">
                    <i class="fas fa-inbox text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Belum ada berita</h3>
                <p class="text-slate-500 mb-10 max-w-md mx-auto">Informasi terbaru saat ini belum tersedia. Silakan kembali lagi nanti.</p>
                <a href="<?= base_url() ?>" class="text-blue-800 font-bold hover:underline flex items-center justify-center">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh Halaman
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    // Pure JS Carousel
    document.addEventListener('DOMContentLoaded', () => {
        const slides = document.querySelectorAll('.carousel-slide');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const nextBtn = document.getElementById('next-slide');
        const prevBtn = document.getElementById('prev-slide');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach(s => {
                s.classList.replace('opacity-100', 'opacity-0');
                s.classList.replace('z-10', 'z-0');
            });
            indicators.forEach(i => {
                i.classList.replace('bg-blue-600', 'bg-white/40');
                i.classList.remove('w-8');
            });
            
            slides[index].classList.replace('opacity-0', 'opacity-100');
            slides[index].classList.replace('z-0', 'z-10');
            indicators[index].classList.replace('bg-white/40', 'bg-blue-600');
            indicators[index].classList.add('w-8');
            currentSlide = index;
        }

        if(nextBtn) {
            nextBtn.addEventListener('click', () => {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            });
        }

        if(prevBtn) {
            prevBtn.addEventListener('click', () => {
                let prev = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(prev);
            });
        }

        // Auto play
        let autoPlay = setInterval(() => {
            if(slides.length > 1) {
                let next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }
        }, 6000);

        // Reset autoplay on interaction
        [nextBtn, prevBtn, ...indicators].forEach(el => {
            if(el) {
                el.addEventListener('click', () => {
                    clearInterval(autoPlay);
                    autoPlay = setInterval(() => {
                        let next = (currentSlide + 1) % slides.length;
                        showSlide(next);
                    }, 8000);
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>