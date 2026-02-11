<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Profil Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-10 bg-slate-50 border-b border-slate-200 text-center">
            <div class="w-24 h-24 bg-blue-800 text-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-blue-900/20 text-3xl font-black">
                <?= substr(esc($user['name']), 0, 1) ?>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight"><?= esc($user['name']) ?></h2>
            <div class="mt-2 inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-200">
                <?= ucfirst(esc($user['role'])) ?>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-slate-100 pb-8">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email Terdaftar</label>
                        <p class="text-slate-900 font-bold tracking-tight"><?= esc($user['email']) ?></p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Bergabung</label>
                        <p class="text-slate-900 font-bold tracking-tight"><?= date('d F Y, H:i', strtotime($user['created_at'])) ?></p>
                    </div>
                </div>

                <div class="text-center">
                    <a href="<?= base_url('admin/settings') ?>" class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-800 hover:text-white hover:border-blue-900 border border-slate-200 transition-all">
                        <i class="fas fa-fw fa-user-cog mr-3 text-sm"></i>Edit Profil & Keamanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>