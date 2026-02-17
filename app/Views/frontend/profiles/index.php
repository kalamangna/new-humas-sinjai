<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    $seo['title'] => current_url()
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
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none"><?= esc($seo['title']) ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Informasi Pejabat Daerah</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            <?= esc($seo['title']) ?>
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <?php
    $hasData = false;
    foreach ($groupedProfiles as $profiles) {
        if (!empty($profiles)) {
            $hasData = true;
            break;
        }
    }
    ?>

    <?php if ($hasData) : ?>
        <div class="max-w-5xl mx-auto">
            <?php
            $displayOrder = ['Forkopimda', 'Eselon II', 'Eselon III', 'Eselon IV', 'Kepala Desa'];
            foreach ($displayOrder as $groupName) :
                $profiles = $groupedProfiles[$groupName] ?? [];
                if (!empty($profiles)) : ?>
                    <div class="mb-10">
                        <div class="flex items-center mb-8">
                            <span class="w-2 h-10 bg-blue-800 mr-5 rounded-full"></span>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight"><?= esc($groupName) ?></h2>
                        </div>

                        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse table-auto">
                                    <thead>
                                        <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-black text-slate-500 uppercase tracking-widest">
                                            <th class="px-8 py-5 whitespace-nowrap">Nama Pejabat</th>
                                            <th class="px-8 py-5 whitespace-nowrap">Jabatan</th>
                                            <th class="px-8 py-5 whitespace-nowrap">
                                                <?php
                                                if (strpos($groupName, 'Eselon') !== false) echo 'OPD';
                                                elseif ($groupName == 'Kepala Desa') echo 'Desa';
                                                else echo 'Instansi';
                                                ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php foreach ($profiles as $profile) : ?>
                                            <tr class="hover:bg-slate-50 transition-colors group">
                                                <td class="px-8 py-6">
                                                    <?php
                                                    $imgPath = $profile['image'] ?? '';
                                                    $imgSrc = filter_var($imgPath, FILTER_VALIDATE_URL) ? $imgPath : (!empty($imgPath) ? base_url($imgPath) : '');
                                                    ?>
                                                    <?php if (!empty($imgSrc)) : ?>
                                                        <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-slate-100 mb-2">
                                                            <img loading="lazy" src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors whitespace-nowrap"><?= $profile['name'] ? esc($profile['name']) : '-' ?></div>
                                                </td>
                                                <td class="px-8 py-6 text-sm text-slate-600 font-medium leading-relaxed whitespace-nowrap">
                                                    <?= esc($profile['position'] ?? '-') ?>
                                                </td>
                                                <td class="px-8 py-6 text-sm text-slate-600 font-medium whitespace-nowrap">
                                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs border border-slate-200">
                                                        <?= esc($profile['institution'] ?? '-') ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fa-solid fa-fw fa-users-slash text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Data Belum Tersedia</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Daftar <?= esc($seo['title']) ?> saat ini belum tersedia dalam basis data kami. Silakan kembali beberapa saat lagi.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fa-solid fa-fw fa-house mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>