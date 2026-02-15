<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-800 flex-shrink-0">
    <div class="flex flex-col h-full">
                <!-- Brand -->
                <div class="flex items-center justify-between h-20 px-6 bg-slate-950 border-b border-slate-800">
                    <a href="<?= base_url('admin') ?>" class="flex items-center">
                        <img src="<?= base_url(get_setting('site_logo', 'humas.png')) ?>" alt="<?= esc(get_setting('site_name', 'Humas Sinjai')) ?>" class="h-10 w-auto" id="sidebar-logo-img">
                    </a>
                    <button id="close-sidebar" class="lg:hidden text-slate-500 hover:text-white">
                        <i class="fas fa-fw fa-times text-xl"></i>
                    </button>
                </div>
                        <!-- Navigation -->
                        <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto">
                            <a href="<?= base_url('admin') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                <i class="fas fa-fw fa-tachometer-alt w-6 opacity-75"></i>
                                <span class="ml-3 font-medium text-sm sidebar-item-text">Dasbor</span>
                            </a>
        
                            <?php if (in_array(session()->get('role'), ['admin', 'author'])) : ?>
                                <a href="<?= base_url('admin/posts') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/posts*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-newspaper w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Manajemen Berita</span>
                                </a>
                                <a href="<?= base_url('admin/categories') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/categories*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-folder w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Kategori Berita</span>
                                </a>
                                <a href="<?= base_url('admin/tags') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/tags*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-tags w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Label Berita</span>
                                </a>
                                <a href="<?= base_url('admin/carousel') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/carousel*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-images w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Banner Media</span>
                                </a>
                                <a href="<?= base_url('admin/profiles') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/profiles*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-user-tie w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Profil Pejabat</span>
                                </a>
                            <?php endif; ?>
        
                            <?php if (in_array(session()->get('role'), ['admin', 'author'])) : ?>
                                                                <a href="<?= base_url('admin/analytics/overview') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/analytics*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                                                    <i class="fas fa-fw fa-chart-line w-6 opacity-75"></i>
                                                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Analitik Situs</span>
                                                                </a>
                                                                <a href="<?= base_url('admin/reports') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/reports*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                                                    <i class="fas fa-fw fa-file-invoice w-6 opacity-75"></i>
                                                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Laporan Berita</span>
                                                                </a>                                            <?php endif; ?>        
                            <div class="pt-6 mt-6 border-t border-slate-800 sidebar-header-container">
                                <h3 class="px-4 mb-3 text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] sidebar-header">Konfigurasi Sistem</h3>
                                <?php if (session()->get('role') === 'admin') : ?>
                                    <a href="<?= base_url('admin/users') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/users*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                        <i class="fas fa-fw fa-users w-6 opacity-75"></i>
                                        <span class="ml-3 font-medium text-sm sidebar-item-text">Manajemen Pengguna</span>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/site-settings') ?>" class="flex items-center px-4 py-3 rounded-lg transition-all <?= url_is('admin/site-settings*') ? 'bg-blue-800 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white' ?>">
                                    <i class="fas fa-fw fa-cog w-6 opacity-75"></i>
                                    <span class="ml-3 font-medium text-sm sidebar-item-text">Pengaturan Situs</span>
                                </a>
                            </div>
                        </nav>
        
                        <!-- Footer Sidebar -->
                        <div class="p-6 bg-slate-950/40 border-t border-slate-800" id="sidebar-footer-content">
                            <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <span>Status Server</span>
                                <span class="flex items-center"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>Aktif</span>
                            </div>
                        </div>    </div>
</aside>