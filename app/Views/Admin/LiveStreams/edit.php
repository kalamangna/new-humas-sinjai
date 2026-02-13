<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Edit Live Stream<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/live-streams') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-fw fa-edit text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Edit Live Stream</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Perbarui konfigurasi tayangan</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <?php if (session()->has('errors')): ?>
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <ul class="list-disc list-inside text-xs text-red-600 font-bold space-y-1">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/live-streams/' . $stream['id']) ?>" method="post" class="space-y-8">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Judul Tayangan <span class="text-red-600">*</span></label>
                    <input type="text" name="title" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 outline-none transition-all" value="<?= old('title', $stream['title']) ?>" placeholder="Contoh: Siaran Langsung Pelantikan Pejabat">
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Facebook Video URL <span class="text-red-600">*</span></label>
                    <input type="url" name="live_url" required class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 outline-none transition-all" value="<?= old('live_url', $stream['live_url']) ?>" placeholder="https://www.facebook.com/watch/live/?v=...">
                    <p class="text-[10px] text-slate-400 font-medium">Pastikan URL berasal dari domain facebook.com</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" <?= old('is_active', $stream['is_active']) ? 'checked' : '' ?> class="w-4 h-4 text-blue-800 border-slate-300 rounded focus:ring-blue-800">
                    <label for="is_active" class="ml-3 text-[11px] font-black text-slate-900 uppercase tracking-[0.2em] cursor-pointer">Aktifkan Tayangan Ini Sekarang</label>
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/live-streams') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-fw fa-save mr-2 text-sm"></i>Perbarui Live Stream
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
