<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Tentang Kami' => current_url()
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fa-solid fa-fw fa-house mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Tentang Kami</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Profil Lembaga</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Tentang Kami
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <!-- Visi Misi Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
        <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-xl transition-all duration-500">
            <div class="absolute top-0 right-0 p-8 text-slate-50 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-fw fa-bullseye text-9xl"></i>
            </div>
            <div class="w-16 h-16 bg-blue-800 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-900/20 mb-8">
                <i class="fa-solid fa-fw fa-bullseye text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-6 uppercase tracking-tight">Visi</h2>
            <p class="text-slate-600 leading-relaxed font-medium text-lg">
                <?= esc(get_setting('about_vision')) ?>
            </p>
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-200 relative overflow-hidden group hover:shadow-xl transition-all duration-500">
            <div class="absolute top-0 right-0 p-8 text-slate-50 opacity-10 group-hover:opacity-20 transition-opacity">
                <i class="fa-solid fa-fw fa-list-check text-9xl"></i>
            </div>
            <div class="w-16 h-16 bg-blue-800 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-900/20 mb-8">
                <i class="fa-solid fa-fw fa-list-check text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-6 uppercase tracking-tight">Misi</h2>
            <ul class="space-y-4">
                <?php
                $misis = get_setting('about_mission', []);
                foreach ($misis as $misi): ?>
                    <li class="flex items-start text-slate-600 font-medium">
                        <i class="fa-solid fa-fw fa-circle-check text-blue-800 mt-1.5 mr-4 text-sm"></i>
                        <span class="leading-relaxed"><?= esc($misi) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Main Description -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden mb-10">
        <div class="grid grid-cols-1 lg:grid-cols-12">
            <div class="lg:col-span-5 bg-slate-50 p-12 lg:p-16 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-slate-200">
                <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4 text-center lg:text-left">Profil Resmi</p>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-8 leading-tight text-center lg:text-left tracking-tight uppercase"><?= esc(get_setting('site_name')) ?></h2>
                <div class="w-20 h-2 bg-blue-800 rounded-full mx-auto lg:mx-0 mb-8"></div>

                <!-- Social Connections -->
                <div class="flex items-center justify-center lg:justify-start gap-4 mt-4">
                    <?php if ($fb = get_setting('social_facebook')): ?>
                        <a href="<?= esc($fb) ?>" target="_blank" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fa-brands fa-fw fa-facebook-f text-sm"></i></a>
                    <?php endif; ?>
                    <?php if ($ig = get_setting('social_instagram')): ?>
                        <a href="<?= esc($ig) ?>" target="_blank" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fa-brands fa-fw fa-instagram text-sm"></i></a>
                    <?php endif; ?>
                    <?php if ($tk = get_setting('social_tiktok')): ?>
                        <a href="<?= esc($tk) ?>" target="_blank" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fa-brands fa-fw fa-tiktok text-sm"></i></a>
                    <?php endif; ?>
                    <?php if ($yt = get_setting('social_youtube')): ?>
                        <a href="<?= esc($yt) ?>" target="_blank" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fa-brands fa-fw fa-youtube text-sm"></i></a>
                    <?php endif; ?>
                    <?php if ($tw = get_setting('social_twitter')): ?>
                        <a href="<?= esc($tw) ?>" target="_blank" class="w-12 h-12 bg-white border border-slate-200 text-slate-400 rounded-2xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fa-brands fa-fw fa-x-twitter text-sm"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="lg:col-span-7 p-12 lg:p-16">
                <div class="prose prose-slate lg:prose-lg max-w-none text-slate-600 font-medium leading-relaxed">
                    <p class="mb-8">
                        <?= esc(get_setting('about_description_1')) ?>
                    </p>
                    <p>
                        <?= esc(get_setting('about_description_2')) ?>
                    </p>
                </div>

                <div class="mt-16">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-10 flex items-center border-b border-slate-50 pb-6">
                        <i class="fa-solid fa-fw fa-layer-group mr-3 text-blue-800"></i>TUGAS POKOK & FUNGSI
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php
                        $tugas = get_setting('about_tasks', []);
                        foreach ($tugas as $t): ?>
                            <div class="flex items-start group">
                                <div class="w-10 h-10 bg-slate-100 text-blue-800 rounded-lg flex items-center justify-center flex-shrink-0 mr-5 group-hover:bg-blue-800 group-hover:text-white transition-all duration-300">
                                    <i class="fa-solid fa-fw fa-check text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900 text-sm mb-2 uppercase tracking-wide"><?= esc($t[0]) ?></h4>
                                    <p class="text-slate-500 text-xs leading-relaxed font-medium"><?= esc($t[1]) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership Section -->
    <div class="text-center mb-10">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Leadership</p>
        <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tight uppercase">Struktur Organisasi</h2>
        <div class="mt-6 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php
        $teams = get_setting('about_teams', []);
        foreach ($teams as $team): ?>
            <div class="bg-white text-center p-10 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-500 group">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 border-2 border-slate-100 group-hover:bg-blue-800 group-hover:border-blue-900 transition-all duration-500 shadow-inner">
                    <i class="fa-solid fa-fw <?= esc($team[2]) ?> text-slate-300 text-3xl group-hover:text-white transition-colors duration-500"></i>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-4 tracking-tight uppercase"><?= esc($team[0]) ?></h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed"><?= esc($team[1]) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>