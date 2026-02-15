<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>

<!-- Hero / Carousel Section -->
<section class="relative bg-slate-950 overflow-hidden">
    <?php if (!empty($slides)): ?>
        <div id="hero-carousel" class="relative w-full">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-slide transition-opacity duration-1000 ease-in-out <?= $index === 0 ? 'relative opacity-100 z-10' : 'absolute inset-0 opacity-0 z-0' ?>" data-index="<?= $index ?>">
                    <?php 
                        $slidePath = $slide['image_path'] ?? '';
                        $slideSrc = filter_var($slidePath, FILTER_VALIDATE_URL) ? $slidePath : (!empty($slidePath) ? base_url($slidePath) : '');
                    ?>
                    <img src="<?= $slideSrc ?>" class="w-full h-auto block" alt="Slide <?= $index + 1 ?>">
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
<section class="pt-4 md:pt-8 pb-12 md:pb-20 bg-slate-50">
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
                        <?= view('components/post_card', ['post' => $post]) ?>
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
        <?php endif; ?>    
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/carousel.js') ?>"></script>
<?= $this->endSection() ?>