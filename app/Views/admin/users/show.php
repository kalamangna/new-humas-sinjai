<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Detail Pengguna<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/users') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-10 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center">
                <div class="w-20 h-20 bg-blue-100 text-blue-800 rounded-3xl flex items-center justify-center text-3xl font-black mr-6">
                    <?= substr($user['name'] ?? 'A', 0, 1) ?>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight"><?= esc($user['name']) ?></h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1"><?= esc($user['role']) ?></p>
                </div>
            </div>
        </div>
        
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                    <p class="text-sm font-bold text-slate-700"><?= esc($user['email']) ?></p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status Akun</label>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>Aktif
                    </span>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center space-x-3">
                <a href="<?= base_url('admin/users/' . $user['id'] . '/edit') ?>" class="inline-flex items-center px-6 py-3 bg-blue-800 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
                    <i class="fas fa-fw fa-edit mr-2"></i>Ubah Pengguna
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>