<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Profil<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/profiles/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fa-solid fa-fw fa-circle-plus mr-2"></i>Tambah Profil
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
    <form action="<?= base_url('admin/profiles') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari</label>
            <input type="text" name="search" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Masukkan nama, jabatan, instansi..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipe</label>
            <select name="type" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Tipe</option>
                <option value="bupati" <?= ($filters['type'] ?? '') == 'bupati' ? 'selected' : '' ?>>Bupati</option>
                <option value="wakil-bupati" <?= ($filters['type'] ?? '') == 'wakil-bupati' ? 'selected' : '' ?>>Wakil Bupati</option>
                <option value="sekda" <?= ($filters['type'] ?? '') == 'sekda' ? 'selected' : '' ?>>Sekretaris Daerah</option>
                <option value="forkopimda" <?= ($filters['type'] ?? '') == 'forkopimda' ? 'selected' : '' ?>>Forkopimda</option>
                <option value="eselon-ii" <?= ($filters['type'] ?? '') == 'eselon-ii' ? 'selected' : '' ?>>Eselon II</option>
                <option value="eselon-iii" <?= ($filters['type'] ?? '') == 'eselon-iii' ? 'selected' : '' ?>>Eselon III</option>
                <option value="lurah" <?= ($filters['type'] ?? '') == 'lurah' ? 'selected' : '' ?>>Lurah</option>
                <option value="kepala-desa" <?= ($filters['type'] ?? '') == 'kepala-desa' ? 'selected' : '' ?>>Kepala Desa</option>
            </select>
        </div>
        <div class="flex flex-col justify-end">
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Cari</button>
                <a href="<?= base_url('admin/profiles') ?>" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">Reset</a>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5 w-[100px]">Foto</th>
                    <th class="px-8 py-5">Identitas</th>
                    <th class="px-8 py-5 w-[150px]">Tipe</th>
                    <th class="px-8 py-5 w-[100px] text-center">Urutan</th>
                    <th class="px-8 py-5 text-right w-[120px]">Aksi</th>
                </tr>
            </thead>
            <tbody id="profiles-data" class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($profiles)) : ?>
                    <?php foreach ($profiles as $profile) : ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6 w-1">
                                <div class="w-14 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm flex-shrink-0">
                                    <?php
                                    $imgPath = $profile['image'] ?? '';
                                    $imgSrc = filter_var($imgPath, FILTER_VALIDATE_URL) ? $imgPath : (!empty($imgPath) ? base_url($imgPath) : '');
                                    ?>
                                    <?php if (!empty($imgSrc)) : ?>
                                        <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                                    <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-fw fa-user text-slate-300"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors tracking-tight text-base leading-tight"><?= $profile['name'] ? esc($profile['name']) : esc($profile['position']) ?></div>
                                <div class="text-[10px] text-blue-800 font-black uppercase tracking-widest mt-1"><?= esc($profile['position'] ?? '-') ?></div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-0.5"><?= esc($profile['institution'] ?? '-') ?></div>
                            </td>
                            <td class="px-8 py-6 w-1">
                                <?php
                                $typeLabels = [
                                    'bupati' => 'Bupati',
                                    'wakil-bupati' => 'Wakil Bupati',
                                    'sekda' => 'Sekretaris Daerah',
                                    'forkopimda' => 'Forkopimda',
                                    'eselon-ii' => 'Eselon II',
                                    'eselon-iii' => 'Eselon III',
                                    'lurah' => 'Lurah',
                                    'kepala-desa' => 'Kepala Desa',
                                ];
                                ?>
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-slate-200">
                                    <?= ($typeLabels[$profile['type']] ?? $profile['type']) ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 w-1 text-center">
                                <?php if (in_array($profile['type'], ['forkopimda', 'eselon-ii', 'eselon-iii'])) : ?>
                                    <span class="px-3 py-1 bg-slate-50 text-slate-500 font-mono text-[10px] font-bold rounded-lg border border-slate-200">
                                        <?= esc($profile['order']) ?>
                                    </span>
                                <?php else : ?>
                                    <span class="text-slate-300">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap w-1">
                                <a href="<?= base_url('admin/profiles/' . $profile['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-fw fa-pen-to-square text-xs"></i>
                                </a>
                                <form action="<?= base_url('admin/profiles/' . $profile['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fa-solid fa-fw fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fa-solid fa-fw fa-user-group text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Belum ada profil</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($pager) && $pager->getPageCount('profiles') > 1) : ?>
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
            Total: <span class="text-slate-900"><?= number_format($pager->getTotal('profiles')) ?></span>
        </div>
        <div>
            <?= $pager->only(['search', 'type'])->links('profiles', 'custom_pager') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>