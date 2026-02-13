<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Tambah Akun Baru<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/users') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-fw fa-user-plus text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Akun Pengguna Baru</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Berikan akses sistem kepada pengguna baru</p>
            </div>
        </div>

        <div class="p-8 md:p-12">

            <form action="<?= base_url('admin/users') ?>" method="post" class="space-y-8">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Lengkap <span class="text-red-600">*</span></label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                            value="<?= old('name') ?>" placeholder="Nama lengkap...">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Email Instansi <span class="text-red-600">*</span></label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                            value="<?= old('email') ?>" placeholder="email@sinjaikab.go.id">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Password Sistem <span class="text-red-600">*</span></label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                            placeholder="Minimal 8 karakter...">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Hak Akses / Role <span class="text-red-600">*</span></label>
                        <select name="role" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none appearance-none cursor-pointer">
                            <option value="" disabled <?= empty(old('role')) ? 'selected' : '' ?>>-- Pilih Role Akses --</option>
                            <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Administrator</option>
                            <option value="author" <?= old('role') == 'author' ? 'selected' : '' ?>>Penulis</option>
                        </select>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/users') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-fw fa-save mr-2 text-sm"></i>Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>