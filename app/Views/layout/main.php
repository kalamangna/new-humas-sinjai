<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) . ' - ' : '' ?>Humas Sinjai</title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    
    <link rel="canonical" href="<?= current_url() ?>">

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
        function gtag() { dataLayer.push(arguments); }
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
                        
                        <!-- Dropdown Profil -->
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-lg text-sm font-bold uppercase tracking-wider hover:bg-blue-800 transition-all inline-flex items-center">
                                Profil
                                <i class="fas fa-chevron-down ml-2 text-[10px] opacity-50"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                                <div class="py-2">
                                    <a href="<?= base_url('profil/bupati') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Bupati Sinjai</a>
                                    <a href="<?= base_url('profil/wakil-bupati') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Wakil Bupati</a>
                                    <a href="<?= base_url('profil/sekda') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Sekretaris Daerah</a>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="<?= base_url('profil/forkopimda') ?>" class="block px-4 py-3 text-xs font-black text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-widest">Forkopimda</a>
                                </div>
                            </div>
                        </div>

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
                                <i class="fas fa-chevron-down ml-2 text-[10px] opacity-50"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-64 rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                                <div class="py-2 max-h-96 overflow-y-auto scrollbar-thin">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?= base_url('category/' . $category['slug']) ?>" class="block px-4 py-3 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-900 uppercase tracking-tighter border-l-4 border-transparent hover:border-blue-800">
                                            <?= esc($category['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="<?= base_url('categories') ?>" class="block px-4 py-3 text-xs font-black text-blue-800 hover:bg-blue-50 uppercase tracking-widest">
                                        Seluruh Kategori
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden xl:flex items-center">
                    <form action="<?= base_url('search') ?>" method="get" class="relative">
                        <input type="text" name="q" placeholder="Pencarian informasi..." required
                            class="bg-blue-950 text-white placeholder-blue-400 text-xs font-bold uppercase tracking-wider rounded-xl border-none focus:ring-2 focus:ring-sky-500 pl-5 pr-12 py-3 w-72 shadow-inner transition-all">
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 text-blue-400 hover:text-white transition-colors">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <div class="xl:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-3 rounded-xl text-white hover:bg-blue-800 focus:outline-none transition-colors border border-blue-700/50">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden xl:hidden bg-blue-950 px-4 pt-4 pb-8 space-y-2 border-t border-blue-800">
            <a href="<?= base_url('/') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('/') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Beranda</a>
            
            <div class="py-2">
                <p class="px-4 text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">Profil Daerah</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= base_url('profil/bupati') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Bupati</a>
                    <a href="<?= base_url('profil/wakil-bupati') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Wakil Bupati</a>
                    <a href="<?= base_url('profil/sekda') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Sekda</a>
                    <a href="<?= base_url('profil/forkopimda') ?>" class="px-4 py-2 text-xs font-bold text-blue-100 hover:text-white">Forkopimda</a>
                </div>
            </div>

            <a href="<?= base_url('posts') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('posts') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Berita</a>
            <a href="<?= base_url('program-prioritas') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('program-prioritas') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Program</a>
            
            <div class="pt-4 mt-4 border-t border-blue-900">
                <form action="<?= base_url('search') ?>" method="get">
                    <input type="text" name="q" placeholder="Cari Berita..." class="w-full bg-blue-900 text-white text-sm font-bold uppercase tracking-widest rounded-xl border-none px-5 py-4 focus:ring-2 focus:ring-sky-500">
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen">
        <!-- Flash Messages (Only if NOT on homepage, homepage handles it manually) -->
        <?php if (!url_is('/')) : ?>
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="bg-red-50 border-l-4 border-red-600 text-red-900 p-5 rounded-r-2xl shadow-lg shadow-red-900/5 animate-in slide-in-from-top duration-300" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-600 mr-3 text-lg"></i>
                            <p class="font-bold text-xs uppercase tracking-tight"><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="bg-emerald-50 border-l-4 border-emerald-600 text-emerald-900 p-5 rounded-r-2xl shadow-lg shadow-emerald-900/5 animate-in slide-in-from-top duration-300" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-emerald-600 mr-3 text-lg"></i>
                            <p class="font-bold text-xs uppercase tracking-tight"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 mt-20 border-t-8 border-blue-900">
        <div class="max-w-7xl mx-auto px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16">
                <!-- Info -->
                <div class="space-y-8 text-center md:text-left">
                    <img src="<?= base_url('humas.png') ?>" alt="Humas Sinjai" class="h-14 w-auto brightness-200 mx-auto md:mx-0">
                    <p class="text-sm leading-relaxed font-medium">Portal Berita Resmi Pemerintah Kabupaten Sinjai. Menyajikan informasi pembangunan dan pelayanan publik yang transparan, akuntabel, dan inspiratif.</p>
                    <div class="flex justify-center md:justify-start space-x-6">
                        <a href="#" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-youtube text-xl"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-tiktok text-xl"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center md:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Navigasi Utama
                    </h3>
                    <ul class="space-y-4 text-xs font-bold uppercase tracking-widest">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-sky-400 flex items-center transition-colors"><i class="fas fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Beranda Utama</a></li>
                        <li><a href="<?= base_url('about') ?>" class="hover:text-sky-400 flex items-center transition-colors"><i class="fas fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Tentang Kami</a></li>
                        <li><a href="<?= base_url('contact') ?>" class="hover:text-sky-400 flex items-center transition-colors"><i class="fas fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Hubungi Kami</a></li>
                        <li><a href="<?= base_url('rss') ?>" class="hover:text-sky-400 flex items-center transition-colors"><i class="fas fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Layanan RSS</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center md:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Saluran Kontak
                    </h3>
                    <ul class="space-y-6 text-sm font-medium">
                        <li class="flex items-start">
                            <div class="bg-blue-900/50 p-2 rounded-lg mr-4 mt-1"><i class="fas fa-map-marker-alt text-sky-500"></i></div>
                            <span class="leading-relaxed text-xs">Jl. Persatuan Raya No. 101, Balangnipa, Sinjai Utara, Sinjai</span>
                        </li>
                        <li class="flex items-center">
                            <div class="bg-blue-900/50 p-2 rounded-lg mr-4"><i class="fas fa-envelope text-sky-500"></i></div>
                            <span class="text-xs">humas@sinjaikab.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Lapor -->
                <div class="text-center md:text-left">
                    <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center md:justify-start">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Pengaduan Publik
                    </h3>
                    <a href="https://lapor.go.id/" target="_blank" class="inline-block bg-white p-4 rounded-2xl shadow-2xl hover:shadow-blue-900/20 transition-all hover:-translate-y-1">
                        <img src="<?= base_url('lapor.png') ?>" alt="Lapor" class="h-14 w-auto">
                    </a>
                    <p class="mt-6 text-[10px] font-black text-blue-500 uppercase tracking-widest italic">Sampaikan Aspirasi Anda</p>
                </div>
            </div>
            
            <div class="border-t border-slate-900 mt-20 pt-10 flex flex-col md:flex-row justify-between items-center text-[10px] tracking-[0.2em] uppercase font-black">
                <p class="text-slate-600">&copy; <?= date('Y') ?> Pemerintah Kabupaten Sinjai. Hak Cipta Dilindungi.</p>
                <div class="mt-6 md:mt-0 flex items-center text-slate-500">
                    OFFICIAL PORTAL <span class="mx-4 text-slate-800">|</span> <i class="fas fa-shield-alt mr-2 text-emerald-700"></i> SECURE ACCESS
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button id="scroll-top" class="fixed bottom-8 right-8 bg-blue-900 text-white w-14 h-14 rounded-2xl shadow-2xl hidden flex items-center justify-center hover:bg-blue-800 transition-all z-40 border border-blue-700/30 group hover:-translate-y-1">
        <i class="fas fa-chevron-up text-lg group-hover:scale-125 transition-transform"></i>
    </button>

    <script>
        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</body>
</html>