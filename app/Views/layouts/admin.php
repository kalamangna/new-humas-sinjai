<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">

<?= view('layouts/partials/head_admin') ?>

<body class="h-full font-sans antialiased text-slate-900 bg-slate-100 sidebar-expanded">
    <style>
        @media (min-width: 1024px) {
            #sidebar { transition: width 0.3s ease, transform 0.3s ease; }
            .sidebar-expanded #sidebar { width: 16rem; }
            body:not(.sidebar-expanded) #sidebar { width: 5rem; }
            
            .sidebar-item-text { transition: opacity 0.2s ease; }
            body:not(.sidebar-expanded) .sidebar-item-text { opacity: 0; width: 0; overflow: hidden; display: none; }
            body:not(.sidebar-expanded) .sidebar-header { opacity: 0; height: 0; margin: 0; padding: 0; overflow: hidden; }
            body:not(.sidebar-expanded) .sidebar-header-container { border-top: none; }
            body:not(.sidebar-expanded) #sidebar-footer-content { display: none; }
            body:not(.sidebar-expanded) #sidebar-logo-img { display: none; }
            body:not(.sidebar-expanded) #sidebar nav a { justify-content: center; padding-left: 0; padding-right: 0; }
            body:not(.sidebar-expanded) #sidebar nav a i { margin-right: 0; }
            body:not(.sidebar-expanded) #sidebar .bg-slate-950 { justify-content: center; padding-left: 0; padding-right: 0; }
            body:not(.sidebar-expanded) #sidebar .bg-slate-950 #close-sidebar { display: none; }
        }
    </style>
    <div class="min-h-screen flex overflow-hidden">
        
        <?= view('layouts/partials/sidebar_admin') ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Mobile Sidebar Overlay -->
            <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/60 hidden transition-opacity lg:hidden"></div>

            <?= view('layouts/partials/navbar_admin') ?>

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

    <?= view('layouts/partials/scripts_admin') ?>
</body>

</html>