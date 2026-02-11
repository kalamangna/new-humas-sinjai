<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Edit Kategori<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/categories') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-edit text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Perbarui Kategori</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #<?= $category['id'] ?> • <?= esc($category['name']) ?></p>
            </div>
        </div>

        <div class="p-8 md:p-12">

            <form action="<?= base_url('admin/categories/' . $category['id']) ?>" method="post" class="space-y-8">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Kategori <span class="text-red-600">*</span></label>
                        <input type="text" name="name" required
                            class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all"
                            value="<?= old('name', $category['name']) ?>" placeholder="Nama kategori...">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Induk Kategori</label>
                        <select name="parent_id" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                            <option value="">-- Tanpa Induk --</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>" <?= old('parent_id', $category['parent_id']) == $cat['id'] ? 'selected' : '' ?>><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/categories') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-save mr-2 text-sm"></i>Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>