<!-- Topbar -->
<header class="flex items-center justify-between h-20 px-6 bg-white border-b border-slate-200 sticky top-0 z-30 flex-shrink-0">
    <div class="flex items-center">
        <button id="open-sidebar" class="text-slate-500 hover:text-slate-900 p-2 mr-4 transition-colors">
            <i class="fa-solid fa-fw fa-bars text-xl"></i>
        </button>
        <div>
            <h1 class="text-lg font-bold text-slate-800 leading-none truncate max-w-[200px] sm:max-w-md">
                <?= $this->renderSection('page_title') ?? 'Dasbor' ?>
            </h1>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mt-1">Administrator <?= esc(get_setting('site_name', 'Humas Sinjai')) ?></p>
        </div>
    </div>

    <div class="flex items-center space-x-3 sm:space-x-6">
        <a href="<?= base_url('/') ?>" target="_blank" class="hidden sm:flex items-center px-4 py-2 text-xs font-bold uppercase tracking-wider text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
            <i class="fa-solid fa-fw fa-up-right-from-square mr-2"></i>Kunjungi Situs
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
                <i class="fa-solid fa-fw fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            <!-- Dropdown Menu -->
            <div class="absolute right-0 w-56 mt-0 bg-white rounded-xl shadow-2xl ring-1 ring-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-1 transition-all duration-200 z-50">
                <div class="py-2">
                    <a href="<?= base_url('admin/profile') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i class="fa-solid fa-fw fa-circle-user w-5 text-slate-400"></i>Profil Saya
                    </a>
                    <a href="<?= base_url('admin/settings') ?>" class="flex items-center px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        <i class="fa-solid fa-fw fa-gear w-5 text-slate-400"></i>Pengaturan Akun
                    </a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50">
                        <i class="fa-solid fa-fw fa-right-from-bracket w-5"></i>Keluar Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>