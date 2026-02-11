<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Pengaturan Akun<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-fw fa-cog text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Preferensi & Keamanan</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Kelola informasi login dan identitas anda</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form action="<?= base_url('admin/users/update_settings') ?>" method="post" class="space-y-8">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all <?= (isset(session('errors')['name'])) ? 'border-red-500' : '' ?>"
                            value="<?= old('name', $user['name']) ?>">
                        <?php if (isset(session('errors')['name'])) : ?>
                            <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['name'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Email Instansi <span class="text-red-600">*</span></label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all <?= (isset(session('errors')['email'])) ? 'border-red-500' : '' ?>"
                            value="<?= old('email', $user['email']) ?>">
                        <?php if (isset(session('errors')['email'])) : ?>
                            <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['email'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="pt-6 border-t border-slate-50 space-y-6">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Ganti Password Baru</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all <?= (isset(session('errors')['password'])) ? 'border-red-500' : '' ?>"
                                placeholder="Biarkan kosong jika tidak ingin ganti...">
                            <?php if (isset(session('errors')['password'])) : ?>
                                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['password'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirm"
                                class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all <?= (isset(session('errors')['password_confirm'])) ? 'border-red-500' : '' ?>"
                                placeholder="Ulangi password baru...">
                            <?php if (isset(session('errors')['password_confirm'])) : ?>
                                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['password_confirm'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <button type="submit" class="w-full py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-fw fa-save mr-2 text-sm"></i>Perbarui Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>