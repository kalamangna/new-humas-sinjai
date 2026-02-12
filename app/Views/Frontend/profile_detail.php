<?= $this->extend('Layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-fw fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Profil Pejabat</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none normal-case"><?= esc($title) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <?php if (!empty($profile)) : ?>
        <div class="max-w-4xl mx-auto">
            <article class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 md:p-16">
                    <!-- Profile Header -->
                    <div class="text-center mb-10">
                        <div class="relative inline-block mb-10">
                            <?php 
                                $imgPath = $profile['image'] ?? '';
                                $imgSrc = filter_var($imgPath, FILTER_VALIDATE_URL) ? $imgPath : (!empty($imgPath) ? base_url($imgPath) : '');
                            ?>
                            <?php if (!empty($imgSrc)) : ?>
                                <img src="<?= $imgSrc ?>" alt="<?= esc($profile['name']) ?>" 
                                    class="w-64 h-auto md:w-80 rounded-3xl shadow-2xl border-8 border-slate-50 mx-auto transform hover:scale-105 transition-transform duration-500">
                            <?php else : ?>
                                <div class="w-64 h-80 bg-slate-50 rounded-3xl shadow-inner border-4 border-dashed border-slate-200 flex items-center justify-center mx-auto">
                                    <i class="fas fa-fw fa-user text-slate-200 text-8xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Accent Shape -->
                            <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-blue-800/10 rounded-full blur-2xl -z-10"></div>
                        </div>
                        
                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 mb-4 tracking-tight">
                            <?= $profile['name'] ? esc($profile['name']) : esc($profile['position']) ?>
                        </h1>
                        
                        <?php if ($profile['name']): ?>
                            <p class="text-lg md:text-xl text-slate-500 font-bold uppercase tracking-widest mb-2"><?= esc($profile['position']) ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($profile['institution'])) : ?>
                            <div class="inline-flex items-center px-6 py-2 bg-blue-50 text-blue-800 rounded-full text-xs font-black uppercase tracking-widest border border-blue-100">
                                <i class="fas fa-fw fa-landmark mr-3"></i><?= esc($profile['institution']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Profile Content -->
                    <div class="border-t border-slate-100 pt-16">
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-10 flex items-center justify-center lg:justify-start">
                            <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Biografi & Profil Lengkap
                        </h2>
                        
                        <div class="overflow-x-auto">
                            <div class="prose prose-slate lg:prose-xl max-w-none prose-headings:text-slate-900 prose-headings:font-black prose-p:leading-relaxed prose-p:text-slate-600">
                                <?= $profile['bio'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Card -->
                <div class="bg-slate-50 p-8 border-t border-slate-100 text-center">
                    <button onclick="history.back()" class="inline-flex items-center text-xs font-black text-slate-500 hover:text-blue-800 uppercase tracking-widest transition-colors">
                        <i class="fas fa-fw fa-arrow-left mr-3"></i>Kembali ke Daftar
                    </button>
                </div>
            </article>
        </div>
    <?php else : ?>
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-fw fa-user-slash text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Profil Tidak Tersedia</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Maaf, informasi detail untuk profil yang anda cari belum tersedia dalam sistem kami. Silakan kembali ke beranda utama.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fas fa-fw fa-home mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
