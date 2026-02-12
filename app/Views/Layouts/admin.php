<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Humas Sinjai</title>
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Third Party Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="<?= base_url('assets/tinymce/tinymce/tinymce.min.js') ?>" referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
            selector: 'textarea#content, textarea#bio',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_url: '<?= site_url('admin/posts/upload_image') ?>',
            relative_urls: false,
            remove_script_host: false,
            license_key: 'gpl'
        });

        function previewImage() {
            const thumbnail = document.querySelector('#thumbnail');
            const thumbnailPreview = document.querySelector('#thumbnail-preview');
            const container = document.querySelector('#thumbnail-preview-container');
            
            if (thumbnail.files && thumbnail.files[0]) {
                const oFReader = new FileReader();
                oFReader.readAsDataURL(thumbnail.files[0]);
                oFReader.onload = function(oFREvent) {
                    thumbnailPreview.src = oFREvent.target.result;
                    if (container) container.classList.remove('hidden');
                    thumbnailPreview.style.display = 'block';
                }
            }
        }
    </script>
</head>

<body class="h-full font-sans antialiased text-slate-900 bg-slate-100">
    <div class="min-h-screen flex overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-800 flex-shrink-0">
            <div class="flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center justify-between h-20 px-6 bg-slate-950 border-b border-slate-800">
                    <a href="<?= base_url('admin') ?>" class="flex items-center space-x-3">
                        <!-- Official Logo Humas Sinjai - Untouched -->
                        <img src="<?= base_url('humas.png') ?>" alt="Logo" class="h-10 w-auto">
                    </a>
                    <button id="close-sidebar" class="lg:hidden text-slate-500 hover:text-white">
                        <i class="fas fa-fw fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
                    <a href="<?= base_url('admin') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-tachometer-alt w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Dasbor</span>
                    </a>
                    <a href="<?= base_url('admin/posts') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/posts*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-newspaper w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Kelola Berita</span>
                    </a>
                    <a href="<?= base_url('admin/categories') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/categories*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-folder w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Kelola Kategori</span>
                    </a>
                    <a href="<?= base_url('admin/tags') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/tags*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-tags w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Kelola Label</span>
                    </a>
                    <a href="<?= base_url('admin/carousel') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/carousel*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-images w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Media Banner</span>
                    </a>
                    <a href="<?= base_url('admin/profiles') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/profiles*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-user-tie w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Profil Pejabat</span>
                    </a>
                    <a href="<?= base_url('admin/analytics/overview') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/analytics*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                        <i class="fas fa-fw fa-chart-line w-6 opacity-75"></i>
                        <span class="ml-3 font-medium text-sm">Analitik Situs</span>
                    </a>

                    <?php if (session()->get('role') === 'admin') : ?>
                        <div class="pt-6 mt-6 border-t border-slate-800">
                            <h3 class="px-4 mb-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em]">Konfigurasi Sistem</h3>
                            <a href="<?= base_url('admin/users') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/users*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                <i class="fas fa-fw fa-users w-6 opacity-75"></i>
                                <span class="ml-3 font-medium text-sm">Manajemen Pengguna</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </nav>

                <!-- Footer Sidebar -->
                <div class="p-6 bg-slate-950/40 border-t border-slate-800">
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <span>Status Server</span>
                        <span class="flex items-center"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>Aktif</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Mobile Sidebar Overlay -->
            <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/60 hidden transition-opacity lg:hidden"></div>

            <!-- Topbar -->
            <header class="flex items-center justify-between h-20 px-6 bg-white border-b border-slate-200 sticky top-0 z-30 flex-shrink-0">
                <div class="flex items-center">
                    <button id="open-sidebar" class="text-slate-500 hover:text-slate-900 lg:hidden p-2 mr-4">
                        <i class="fas fa-fw fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 leading-none truncate max-w-[200px] sm:max-w-md">
                            <?= $this->renderSection('page_title') ?? 'Dasbor' ?>
                        </h1>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-1">Administrator Humas Sinjai</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-6">
                    <a href="<?= base_url('/') ?>" target="_blank" class="hidden sm:flex items-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                        <i class="fas fa-fw fa-external-link-alt mr-2"></i>Kunjungi Situs
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 focus:outline-none py-2">
                            <div class="w-10 h-10 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-blue-800 font-black flex-shrink-0">
                                <?= substr(session()->get('name') ?? 'A', 0, 1) ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-xs font-bold text-slate-900 leading-none"><?= session()->get('name') ?? 'Administrator' ?></p>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter mt-1"><?= ucfirst(session()->get('role') ?? 'pengguna') ?></p>
                            </div>
                            <i class="fas fa-fw fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 w-56 mt-0 bg-white rounded-xl shadow-2xl ring-1 ring-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                            <div class="py-2">
                                <a href="<?= base_url('admin/profile') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-fw fa-user-circle w-5 text-slate-400"></i>Profil Saya
                                </a>
                                <a href="<?= base_url('admin/settings') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-fw fa-cog w-5 text-slate-400"></i>Pengaturan Akun
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50">
                                    <i class="fas fa-fw fa-sign-out-alt w-5"></i>Keluar Sistem
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-10">
                <div class="max-w-full mx-auto">
                    <!-- Page Actions -->
                    <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 tracking-tight sm:hidden"><?= $this->renderSection('page_title') ?? 'Dasbor' ?></h1>
                        </div>
                        <div class="flex items-center space-x-3">
                            <?= $this->renderSection('page_actions') ?>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="bg-emerald-50 border-l-4 border-emerald-500 p-5 mb-8 rounded-r-xl shadow-sm flex items-center animate-in fade-in slide-in-from-top-2 duration-300">
                            <div class="bg-emerald-500 rounded-full p-1.5 mr-4">
                                <i class="fas fa-fw fa-check text-white text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-emerald-900"><?= session()->getFlashdata('success') ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-5 mb-8 rounded-r-xl shadow-sm flex items-center animate-in fade-in slide-in-from-top-2 duration-300">
                            <div class="bg-red-500 rounded-full p-1.5 mr-4">
                                <i class="fas fa-fw fa-exclamation-triangle text-white text-xs"></i>
                            </div>
                            <span class="text-sm font-bold text-red-900"><?= session()->getFlashdata('error') ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 mb-8 rounded-r-xl shadow-sm animate-in fade-in slide-in-from-top-2 duration-300">
                            <div class="flex items-center mb-3">
                                <div class="bg-red-500 rounded-full p-1.5 mr-4">
                                    <i class="fas fa-fw fa-exclamation-triangle text-white text-xs"></i>
                                </div>
                                <span class="text-sm font-black text-red-900 uppercase tracking-widest">Terjadi Kesalahan Data</span>
                            </div>
                            <ul class="list-disc list-inside space-y-1 ml-10">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li class="text-xs font-bold text-red-800"><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Render Section Content -->
                    <div class="animate-in fade-in duration-500">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar Toggles
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        if (openBtn) openBtn.addEventListener('click', () => toggleSidebar(true));
        if (closeBtn) closeBtn.addEventListener('click', () => toggleSidebar(false));
        if (overlay) overlay.addEventListener('click', () => toggleSidebar(false));

        // Auto-dismiss alerts
        document.querySelectorAll('.bg-emerald-50, .bg-red-50').forEach(alert => {
            setTimeout(() => {
                alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                setTimeout(() => alert.remove(), 500);
            }, 6000);
        });
    </script>
    <script src="https://cdn.userway.org/widget.js" data-account="S41ThPrHz4"></script>
</body>

</html>