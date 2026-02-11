<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Tambah Tag Baru<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/tags') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-plus-circle text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Label / Tag Baru</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Tambah klasifikasi berita baru</p>
            </div>
        </div>

        <div class="p-8 md:p-12">

            <form action="<?= base_url('admin/tags') ?>" method="post" class="space-y-8">
                <?= csrf_field() ?>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Label <span class="text-red-600">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                        value="<?= old('name') ?>" placeholder="Contoh: Infrastruktur">
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/tags') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-save mr-2 text-sm"></i>Simpan Label
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>