<!-- Navbar -->
<nav class="bg-blue-900 text-white shadow-xl sticky top-0 z-50 border-b border-blue-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <a href="<?= base_url('/') ?>" class="flex-shrink-0 flex items-center">
                    <img src="<?= base_url(get_setting('site_logo', 'humas.png')) ?>" alt="<?= esc(get_setting('site_name', 'Humas Sinjai')) ?>" class="h-10 w-auto">
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
                                <?php if (isset($categories)): ?>
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
                                <?php endif; ?>
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

        <a href="<?= base_url('posts') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('posts') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Berita</a>

        <a href="<?= base_url('program-prioritas') ?>" class="block px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest <?= url_is('program-prioritas') ? 'bg-blue-800 text-white shadow-lg' : 'text-blue-200 hover:bg-blue-900 hover:text-white' ?>">Program</a>

        <!-- Mobile Kategori Collapsible -->
        <div class="py-2">
            <button type="button" id="mobile-categories-button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-blue-200 hover:bg-blue-900 hover:text-white transition-all">
                <span>Kategori</span>
                <i class="fas fa-fw fa-chevron-down text-[10px] transition-transform duration-300" id="mobile-categories-arrow"></i>
            </button>
            <div id="mobile-categories-menu" class="hidden pl-4 pr-2 mt-2 space-y-4 border-l-2 border-blue-800 ml-4">
                <?php if (isset($categories)): ?>
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
                <?php endif; ?>
                <a href="<?= base_url('categories') ?>" class="block px-2 py-2 text-[10px] font-black text-sky-400 uppercase tracking-widest border-t border-blue-900/50 mt-2">Lihat Semua Kategori</a>
            </div>
        </div>

        <!-- Mobile Profil Collapsible -->
        <div class="py-2">
            <button type="button" id="mobile-profile-button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-blue-200 hover:bg-blue-900 hover:text-white transition-all">
                <span>Profil</span>
                <i class="fas fa-fw fa-chevron-down text-[10px] transition-transform duration-300" id="mobile-profile-arrow"></i>
            </button>
            <div id="mobile-profile-menu" class="hidden pl-4 pr-2 mt-2 space-y-1 border-l-2 border-blue-800 ml-4">
                <a href="<?= base_url('profil/bupati') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Bupati Sinjai</a>
                <a href="<?= base_url('profil/wakil-bupati') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Wakil Bupati</a>
                <a href="<?= base_url('profil/sekda') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Sekretaris Daerah</a>
                <div class="border-t border-blue-900/50 my-2"></div>
                <a href="<?= base_url('profil/pejabat-daerah') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Pejabat Daerah</a>
            </div>
        </div>

        <!-- Mobile Live Collapsible -->
        <div class="py-2">
            <button type="button" id="mobile-live-button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-black uppercase tracking-widest text-blue-200 hover:bg-blue-900 hover:text-white transition-all">
                <span class="flex items-center">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                    Live
                </span>
                <i class="fas fa-fw fa-chevron-down text-[10px] transition-transform duration-300" id="mobile-live-arrow"></i>
            </button>
            <div id="mobile-live-menu" class="hidden pl-4 pr-2 mt-2 space-y-1 border-l-2 border-blue-800 ml-4">
                <a href="<?= base_url('live/radio') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Suara Bersatu FM</a>
                <a href="<?= base_url('live/tv') ?>" class="block px-2 py-2 text-xs font-bold text-blue-100 hover:text-white">Sinjai TV</a>
            </div>
        </div>

        <div class="pt-6">
            <form action="<?= base_url('search') ?>" method="get" class="relative">
                <input type="text" name="q" placeholder="Cari Berita..." required
                    class="bg-blue-900 text-white placeholder-blue-400 text-xs font-bold uppercase tracking-wider rounded-xl border-none focus:ring-2 focus:ring-sky-500 pl-5 pr-12 py-4 w-full shadow-inner transition-all">
                <button type="submit" class="absolute right-0 top-0 h-full px-4 text-blue-400 hover:text-white transition-colors">
                    <i class="fas fa-fw fa-search"></i>
                </button>
            </form>
        </div>
    </div>
</nav>