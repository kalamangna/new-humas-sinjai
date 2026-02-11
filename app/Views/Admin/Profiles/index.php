<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Manajemen Profil Pejabat<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/profiles/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fas fa-plus-circle mr-2"></i>Tambah Profil
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Visual</th>
                    <th class="px-8 py-5">Identitas & Jabatan</th>
                    <th class="px-8 py-5">Klasifikasi</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($profiles)) : ?>
                    <?php foreach ($profiles as $profile) : ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="w-14 h-20 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm flex-shrink-0">
                                    <?php if (!empty($profile['image'])) : ?>
                                        <img src="<?= esc($profile['image']) ?>" class="w-full h-full object-cover">
                                    <?php else : ?>
                                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user text-slate-300"></i></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors tracking-tight text-base"><?= $profile['name'] ? esc($profile['name']) : esc($profile['position']) ?></div>
                                <div class="text-[10px] text-blue-800 font-black uppercase tracking-widest mt-1"><?= esc($profile['position'] ?? '-') ?></div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-0.5"><?= esc($profile['institution'] ?? '-') ?></div>
                            </td>
                            <td class="px-8 py-6">
                                <?php
                                $typeLabels = [
                                    'bupati' => 'Bupati',
                                    'wakil-bupati' => 'Wakil Bupati',
                                    'sekda' => 'Sekda',
                                    'forkopimda' => 'Forkopimda',
                                    'eselon-ii' => 'Eselon II',
                                    'eselon-iii' => 'Eselon III',
                                    'eselon-iv' => 'Eselon IV',
                                    'kepala-desa' => 'Kepala Desa',
                                ];
                                ?>
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest rounded-lg border border-slate-200">
                                    <?= ($typeLabels[$profile['type']] ?? $profile['type']) ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap">
                                <a href="<?= base_url('admin/profiles/' . $profile['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                <form action="<?= base_url('admin/profiles/' . $profile['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus profil pejabat ini?')">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fas fa-user-friends text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Data profil belum tersedia</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>