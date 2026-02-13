<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?>Humas Sinjai</title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="canonical" href="<?= rtrim(current_url(), '/') ?>">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?= isset($description) ? esc($description) : 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki' ?>">
    <meta name="keywords" content="<?= isset($keywords) ? esc($keywords) : 'Humas Sinjai, Berita Sinjai, Sinjai, Pemerintah Kabupaten Sinjai' ?>">
    <meta name="author" content="<?= isset($author) ? esc($author) : 'Humas Sinjai' ?>">
    <meta name="image" content="<?= isset($image) ? $image : base_url('meta.png') ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= isset($title) ? esc($title) : 'Humas Sinjai' ?>">
    <meta property="og:description" content="<?= isset($description) ? esc($description) : 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki' ?>">
    <meta property="og:image" content="<?= isset($image) ? $image : base_url('meta.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= isset($title) ? esc($title) : 'Humas Sinjai' ?>">
    <meta name="twitter:description" content="<?= isset($description) ? esc($description) : 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki' ?>">
    <meta name="twitter:image" content="<?= isset($image) ? $image : base_url('meta.png') ?>">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QEW3BM9KJ7"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-QEW3BM9KJ7');
    </script>
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-blue-900 text-white shadow-xl sticky top-0 z-50 border-b border-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="<?= base_url('/') ?>" class="flex-shrink-0 flex items-center">
                        <img src="<?= base_url('humas.png') ?>" alt="Humas Sinjai" class="h-10 w-auto">
                    </a>
                    <div class="hidden xl:ml-10 xl:flex xl:space-x-2">
                        <a href="<?= base_url('/') ?>" class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider <?= url_is('/') ? 'bg-blue-800 shadow-inner' : 'hover:bg-blue-800 transition-colors' ?>">
                            Beranda
                        </a>

                        <a href="<?= base_url('posts') ?>" class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider <?= url_is('posts') ? 'bg-blue-800 shadow-inner' : 'hover:bg-blue-800 transition-colors' ?>">
                            Berita
                        </a>
                        <a href="<?= base_url('program-prioritas') ?>" class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider <?= url_is('program-prioritas') ? 'bg-blue-800 shadow-inner' : 'hover:bg-blue-800 transition-colors' ?>">
                            Program
                        </a>

                        <!-- Dropdown Kategori -->
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider hover:bg-blue-800 transition-all inline-flex items-center">
                                Kategori
                                <i class="fas fa-fw fa-chevron-down ml-2 text-[10px] opacity-50"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-72 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                                <div class="py-2 max-h-[80vh] overflow-y-auto scrollbar-thin">
                                    <?php foreach ($categories as $category) : ?>
                                        <div class="px-4 py-2 mt-2">
                                            <span class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50 pb-1 mb-1">
                                                <?= esc($category['name']) ?>
                                            </span>
                                            <?php if (isset($subCategories[$category['id']])) : ?>
                                                <?php foreach ($subCategories[$category['id']] as $subCategory) : ?>
                                                    <a href="<?= base_url('category/' . esc($subCategory['slug'])) ?>" class="block py-2 text-xs font-bold text-slate-700 hover:text-blue-800 transition-colors">
                                                        <?= esc($subCategory['name']) ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <a href="<?= base_url('category/' . esc($category['slug'])) ?>" class="block py-2 text-xs font-bold text-slate-700 hover:text-blue-800 transition-colors">
                                                    <?= esc($category['name']) ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="border-t border-slate-100 mt-2 pt-2">
                                        <a href="<?= base_url('categories') ?>" class="block px-4 py-3 text-xs font-black text-blue-800 hover:bg-blue-50 uppercase tracking-widest">
                                            Seluruh Kategori
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Profil -->
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider hover:bg-blue-800 transition-all inline-flex items-center">
                                Profil
                                <i class="fas fa-fw fa-chevron-down ml-2 text-[10px] opacity-50"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="<?= base_url('profil/bupati') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Bupati Sinjai</a>
                                    <a href="<?= base_url('profil/wakil-bupati') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Wakil Bupati</a>
                                    <a href="<?= base_url('profil/sekda') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Sekretaris Daerah</a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="<?= base_url('profil/pejabat-daerah') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Pejabat Daerah</a>
                                </div>
                            </div>
                        </div>

                        <!-- Dropdown Live -->
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider hover:bg-blue-800 transition-all inline-flex items-center">
                                <span class="relative flex h-2 w-2 mr-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                Live
                                <i class="fas fa-fw fa-chevron-down ml-2 text-[10px] opacity-50"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="<?= base_url('live/radio') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Suara Bersatu FM</a>
                                    <a href="<?= base_url('live/tv') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Sinjai TV</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden xl:flex items-center">
                    <form action="<?= base_url('search') ?>" method="get" class="relative">
                        <input type="text" name="q" placeholder="Cari Berita..." required
                            class="bg-blue-950 text-white placeholder-blue-400 text-xs font-bold uppercase tracking-wider rounded-xl border-none focus:ring-2 focus:ring-sky-500 pl-5 pr-12 py-3 w-72 shadow-inner transition-all">
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 text-blue-400 hover:text-white transition-colors">
                            <i class="fas fa-fw fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <div class="xl:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-3 rounded-xl text-white hover:bg-blue-800 focus:outline-none transition-colors border border-blue-700/50">
                        <i class="fas fa-fw fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden xl:hidden bg-blue-950 px-4 pt-4 pb-8 space-y-2 border-t border-blue-800 max-h-[calc(100vh-5rem)] overflow-y-auto">
            <a href="<?= base_url('/') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('/') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Beranda</a>

            <div class="py-2">
                <p class="px-4 text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">Profil</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= base_url('profil/bupati') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Bupati</a>
                    <a href="<?= base_url('profil/wakil-bupati') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Wakil Bupati</a>
                    <a href="<?= base_url('profil/sekda') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Sekda</a>
                    <a href="<?= base_url('profil/pejabat-daerah') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Pejabat Daerah</a>
                </div>
            </div>

            <a href="<?= base_url('posts') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('posts') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Berita</a>
            
            <!-- Mobile Kategori Collapsible -->
            <div class="py-2">
                <button type="button" id="mobile-categories-button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-blue-200 hover:bg-blue-900 hover:text-white transition-all">
                    <span>Kategori Berita</span>
                    <i class="fas fa-fw fa-chevron-down text-[10px] transition-transform duration-300" id="mobile-categories-arrow"></i>
                </button>
                <div id="mobile-categories-menu" class="hidden pl-4 pr-2 mt-2 space-y-4 border-l-2 border-blue-800 ml-4">
                    <?php foreach ($categories as $category) : ?>
                        <div>
                            <p class="px-2 text-[9px] font-black text-blue-500 uppercase tracking-[0.2em] mb-2"><?= esc($category['name']) ?></p>
                            <div class="grid grid-cols-1 gap-1">
                                <?php if (isset($subCategories[$category['id']])) : ?>
                                    <?php foreach ($subCategories[$category['id']] as $subCategory) : ?>
                                        <a href="<?= base_url('category/' . esc($subCategory['slug'])) ?>" class="px-2 py-1.5 text-xs font-bold text-blue-100 hover:text-white block"><?= esc($subCategory['name']) ?></a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>" class="px-2 py-1.5 text-xs font-bold text-blue-100 hover:text-white block"><?= esc($category['name']) ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <a href="<?= base_url('categories') ?>" class="block px-2 py-2 text-[10px] font-black text-sky-400 uppercase tracking-widest border-t border-blue-900/50 mt-2">Lihat Semua Kategori</a>
                </div>
            </div>

            <a href="<?= base_url('program-prioritas') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('program-prioritas') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Program</a>

            <div class="py-2">
                <p class="px-4 text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2 flex items-center">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    Live Streaming
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= base_url('live/radio') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Radio</a>
                    <a href="<?= base_url('live/tv') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Sinjai TV</a>
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-blue-900">
                <form action="<?= base_url('search') ?>" method="get">
                    <input type="text" name="q" placeholder="Cari Berita..." class="w-full bg-blue-900 text-white text-sm font-bold uppercase tracking-widest rounded-xl border-none px-5 py-4 focus:ring-2 focus:ring-sky-500">
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="<?= url_is('/') ? '' : 'pb-12 md:pb-20 pt-2 md:pt-4' ?>">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 mt-20 border-t-8 border-blue-900">
        <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 items-start">
                <!-- Info -->
                <div class="space-y-8 text-center sm:text-left">
                    <div>
                        <img src="<?= base_url('humas.png') ?>" alt="Humas Sinjai" class="h-14 w-auto mx-auto sm:mx-0">
                        <div class="mt-4 inline-flex items-center px-3 py-1 bg-blue-900/50 text-sky-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-blue-800">
                            #samasamaki
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed font-medium">Portal Berita Resmi Pemerintah Kabupaten Sinjai. Menyajikan informasi pembangunan dan pelayanan publik yang transparan, akuntabel, dan inspiratif.</p>
                    <div class="flex justify-center sm:justify-start space-x-6">
                        <a href="https://www.facebook.com/FP.KabupatenSinjai" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-facebook-f text-xl"></i></a>
                        <a href="https://www.instagram.com/sinjaikab" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-instagram text-xl"></i></a>
                        <a href="https://www.youtube.com/@SINJAITV" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-youtube text-xl"></i></a>
                        <a href="https://www.tiktok.com/@pemkabsinjai" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-tiktok text-xl"></i></a>
                        <a href="https://x.com/sinjaikab" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-x-twitter text-xl"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div class="text-center sm:text-left">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Navigasi Utama
                    </h3>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-widest">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Beranda Utama</a></li>
                        <li><a href="<?= base_url('about') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Tentang Kami</a></li>
                        <li><a href="<?= base_url('contact') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Hubungi Kami</a></li>
                        <li><a href="<?= base_url('widget') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Panduan Widget</a></li>
                        <li><a href="<?= base_url('rss') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Layanan RSS</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="text-center sm:text-left">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Saluran Kontak
                    </h3>
                    <ul class="space-y-6 text-sm font-medium">
                        <li class="flex items-center justify-center sm:justify-start">
                            <div class="bg-blue-900/50 p-2 rounded-lg mr-4 mt-1 flex-shrink-0"><i class="fas fa-fw fa-map-marker-alt text-sky-500"></i></div>
                            <span class="leading-relaxed text-xs">Jl. Persatuan Raya No. 101, Balangnipa, Sinjai Utara, Sinjai</span>
                        </li>
                        <li class="flex items-center justify-center sm:justify-start">
                            <div class="bg-blue-900/50 p-2 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-fw fa-envelope text-sky-500"></i></div>
                            <span class="text-xs">humas@sinjaikab.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Lapor -->
                <div class="text-center sm:text-left">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Pengaduan Publik
                    </h3>
                    <a href="https://lapor.go.id/" target="_blank" class="inline-block bg-white p-4 rounded-2xl shadow-2xl hover:shadow-blue-900/20 transition-all hover:-translate-y-1">
                        <img src="<?= base_url('lapor.png') ?>" alt="Lapor" class="h-14 w-auto">
                    </a>
                    <p class="mt-6 text-[10px] font-black text-blue-500 uppercase tracking-widest italic">Sampaikan Aspirasi Anda</p>
                </div>
            </div>

            <div class="border-t border-slate-900 mt-20 pt-10 flex flex-col md:flex-row justify-between items-center text-[10px] tracking-[0.2em] uppercase font-black">
                <p class="text-slate-600">&copy; <?= date('Y') ?> Humas Sinjai.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button id="scroll-top" class="fixed bottom-8 right-8 bg-blue-900 text-white w-14 h-14 rounded-2xl shadow-2xl hidden flex items-center justify-center hover:bg-blue-800 transition-all z-40 border border-blue-700/30 group hover:-translate-y-1">
        <i class="fas fa-fw fa-chevron-up text-lg group-hover:scale-125 transition-transform"></i>
    </button>

    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.toggle('hidden');
                if (!isHidden) {
                    document.body.classList.add('overflow-hidden');
                } else {
                    document.body.classList.remove('overflow-hidden');
                }
            });
        }

        // Mobile Categories Toggle
        const catBtn = document.getElementById('mobile-categories-button');
        const catMenu = document.getElementById('mobile-categories-menu');
        const catArrow = document.getElementById('mobile-categories-arrow');
        if (catBtn && catMenu) {
            catBtn.addEventListener('click', () => {
                catMenu.classList.toggle('hidden');
                catArrow.classList.toggle('rotate-180');
            });
        }

        // Scroll to Top
        const scrollTopBtn = document.getElementById('scroll-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 400) {
                scrollTopBtn.classList.remove('hidden');
                scrollTopBtn.classList.add('flex');
            } else {
                scrollTopBtn.classList.add('hidden');
                scrollTopBtn.classList.remove('flex');
            }
        });
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    
    <?= $this->renderSection('scripts') ?>
    <script src="https://cdn.userway.org/widget.js" data-account="S41ThPrHz4" data-position="5"></script>
</body>

</html>