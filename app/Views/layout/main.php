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
    <nav class="bg-blue-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="<?= base_url('/') ?>" class="flex-shrink-0 flex items-center">
                        <img src="<?= base_url('humas.png') ?>" alt="Logo Sinjai" class="h-10 w-auto">
                    </a>
                    <div class="hidden xl:ml-10 xl:flex xl:space-x-4">
                        <a href="<?= base_url('/') ?>" class="px-3 py-2 rounded-md text-sm font-medium <?= url_is('/') ? 'bg-blue-900' : 'hover:bg-blue-700' ?>">
                            <i class="fas fa-home mr-2 text-sky-400"></i>Beranda
                        </a>
                        <a href="<?= base_url('posts') ?>" class="px-3 py-2 rounded-md text-sm font-medium <?= url_is('posts') ? 'bg-blue-900' : 'hover:bg-blue-700' ?>">
                            <i class="fas fa-newspaper mr-2 text-sky-400"></i>Berita
                        </a>
                        <a href="<?= base_url('program-prioritas') ?>" class="px-3 py-2 rounded-md text-sm font-medium <?= url_is('program-prioritas') ? 'bg-blue-900' : 'hover:bg-blue-700' ?>">
                            <i class="fas fa-bullseye mr-2 text-sky-400"></i>Program
                        </a>
                        
                        <!-- Dropdown Kategori -->
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 inline-flex items-center">
                                <i class="fas fa-folder mr-2 text-sky-400"></i>Kategori
                                <i class="fas fa-chevron-down ml-1 text-xs opacity-70"></i>
                            </button>
                            <div class="absolute left-0 mt-0 w-56 rounded-md shadow-xl bg-white ring-1 ring-black ring-opacity-5 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <div class="py-1">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?= base_url('category/' . $category['slug']) ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                            <?= esc($category['name']) ?>
                                        </a>
                                    <?php endforeach; ?>
                                    <div class="border-t border-slate-100 my-1"></div>
                                    <a href="<?= base_url('categories') ?>" class="block px-4 py-2 text-sm text-blue-800 font-bold hover:bg-slate-100">
                                        Semua Kategori
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden xl:flex items-center">
                    <form action="<?= base_url('search') ?>" method="get" class="relative">
                        <input type="text" name="q" placeholder="Cari berita..." required
                            class="bg-blue-900 text-white placeholder-blue-300 text-sm rounded-full border-none focus:ring-2 focus:ring-sky-400 pl-4 pr-10 py-2 w-64">
                        <button type="submit" class="absolute right-0 top-0 mt-2 mr-3 text-blue-300 hover:text-white">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Mobile menu button -->
                <div class="xl:hidden flex items-center">
                    <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-blue-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden xl:hidden bg-blue-900 px-2 pt-2 pb-3 space-y-1">
            <a href="<?= base_url('/') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= url_is('/') ? 'bg-blue-950 text-white' : 'text-blue-100 hover:bg-blue-800' ?>">Beranda</a>
            <a href="<?= base_url('posts') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= url_is('posts') ? 'bg-blue-950 text-white' : 'text-blue-100 hover:bg-blue-800' ?>">Berita</a>
            <a href="<?= base_url('program-prioritas') ?>" class="block px-3 py-2 rounded-md text-base font-medium <?= url_is('program-prioritas') ? 'bg-blue-950 text-white' : 'text-blue-100 hover:bg-blue-800' ?>">Program</a>
            <div class="border-t border-blue-800 mt-2 pt-2">
                <form action="<?= base_url('search') ?>" method="get" class="px-3 pb-3">
                    <input type="text" name="q" placeholder="Cari berita..." class="w-full bg-blue-950 text-white rounded-md border-none px-4 py-2">
                </form>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="bg-red-50 border-l-4 border-red-600 text-red-800 p-4 mb-4 rounded-r-lg" role="alert">
                <p><?= session()->getFlashdata('error') ?></p>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 mb-4 rounded-r-lg" role="alert">
                <p><?= session()->getFlashdata('success') ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 mt-12 border-t-4 border-blue-800">
        <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <!-- Info -->
                <div class="space-y-6">
                    <img src="<?= base_url('humas.png') ?>" alt="Logo Sinjai" class="h-12 w-auto brightness-200">
                    <p class="text-sm leading-relaxed">Portal Berita Resmi Pemerintah Kabupaten Sinjai. Menyajikan informasi pembangunan dan pelayanan publik yang transparan dan akuntabel.</p>
                    <div class="flex space-x-5">
                        <a href="#" class="hover:text-sky-400 transition-colors"><i class="fab fa-facebook-f text-lg"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-colors"><i class="fab fa-instagram text-lg"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-colors"><i class="fab fa-youtube text-lg"></i></a>
                        <a href="#" class="hover:text-sky-400 transition-colors"><i class="fab fa-tiktok text-lg"></i></a>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center">
                        <span class="w-2 h-6 bg-sky-600 mr-3 rounded-full"></span>Navigasi
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-sky-400 flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 opacity-50"></i>Beranda</a></li>
                        <li><a href="<?= base_url('about') ?>" class="hover:text-sky-400 flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 opacity-50"></i>Tentang Kami</a></li>
                        <li><a href="<?= base_url('contact') ?>" class="hover:text-sky-400 flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 opacity-50"></i>Kontak</a></li>
                        <li><a href="<?= base_url('rss') ?>" class="hover:text-sky-400 flex items-center"><i class="fas fa-chevron-right text-[10px] mr-2 opacity-50"></i>RSS Feed</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center">
                        <span class="w-2 h-6 bg-sky-600 mr-3 rounded-full"></span>Kontak Kami
                    </h3>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-sky-500"></i>
                            <span class="leading-relaxed">Jl. Persatuan Raya No. 101, Balangnipa, Kec. Sinjai Utara, Kab. Sinjai</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-sky-500"></i>
                            <span>humas@sinjaikab.go.id</span>
                        </li>
                    </ul>
                </div>

                <!-- Lapor -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-6 flex items-center">
                        <span class="w-2 h-6 bg-sky-600 mr-3 rounded-full"></span>Pengaduan
                    </h3>
                    <a href="https://lapor.go.id/" target="_blank" class="block bg-white p-3 rounded-xl max-w-[200px] hover:shadow-lg transition-shadow">
                        <img src="<?= base_url('lapor.png') ?>" alt="Lapor" class="w-full">
                    </a>
                </div>
            </div>
            
            <div class="border-t border-slate-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center text-xs tracking-wider uppercase font-medium">
                <p>&copy; <?= date('Y') ?> Pemerintah Kabupaten Sinjai. All Rights Reserved.</p>
                <p class="mt-4 md:mt-0 flex items-center">
                    Humas Sinjai <span class="mx-2 text-slate-700">|</span> <i class="fas fa-shield-alt mr-1 text-emerald-600"></i> Official Portal
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button id="scroll-top" class="fixed bottom-6 right-6 bg-blue-800 text-white w-12 h-12 rounded-full shadow-2xl hidden flex items-center justify-center hover:bg-blue-700 transition-all z-40 border border-blue-600/50">
        <i class="fas fa-chevron-up"></i>
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
            if (window.pageYOffset > 300) {
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