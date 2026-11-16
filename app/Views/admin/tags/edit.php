<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Tag<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/tags') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-5 sm:px-8 py-4 sm:py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fa-solid fa-fw fa-pen-to-square text-sm"></i>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Ubah Tag</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #<?= $tag['id'] ?> • <?= esc($tag['name']) ?></p>
            </div>
        </div>

        <div class="p-4 sm:p-8 md:p-12">

            <form action="<?= base_url('admin/tags/' . $tag['id']) ?>" method="post" class="space-y-6 sm:space-y-8">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama <span class="text-red-600">*</span></label>
                    <input type="text" name="name" required
                        class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                        value="<?= old('name', $tag['name']) ?>" placeholder="Masukkan nama tag">
                </div>

                <div class="pt-6 sm:pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                    <a href="<?= base_url('admin/tags') ?>" class="w-full sm:w-auto px-6 sm:px-8 py-3.5 sm:py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-xl sm:rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 sm:px-10 py-3.5 sm:py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-xl sm:rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20 text-center">
                        <i class="fa-solid fa-fw fa-floppy-disk mr-2 text-sm"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>