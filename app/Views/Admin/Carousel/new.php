<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Tambah Slide Baru<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/carousel') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-plus-circle text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Slide Baru</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Visual beranda utama portal</p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form action="<?= base_url('admin/carousel') ?>" method="post" enctype="multipart/form-data" class="space-y-8">
                <?= csrf_field() ?>

                <div class="space-y-4">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Pilih Gambar Slide <span class="text-red-600">*</span></label>
                    <label class="block">
                        <div class="flex items-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl hover:border-blue-800 hover:bg-slate-100 transition-all cursor-pointer">
                            <i class="fas fa-image text-slate-400 mr-4"></i>
                            <span class="text-sm font-bold text-slate-500" id="file-name">Klik untuk memilih file...</span>
                            <input type="file" name="image" required class="hidden" onchange="previewImage(); document.getElementById('file-name').innerText = this.files[0].name;">
                        </div>
                    </label>
                    <p class="text-[10px] text-slate-400 font-medium">Rekomendasi ukuran: 1920x800 px. Maks: 2MB.</p>
                    
                    <div id="image-preview-container" class="mt-6 hidden ring-4 ring-slate-50 rounded-2xl overflow-hidden shadow-2xl border border-slate-200">
                        <img id="image-preview" class="w-full h-auto">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Urutan Tampil</label>
                    <input type="number" name="slide_order" class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-900 focus:border-blue-800 outline-none transition-all" value="<?= old('slide_order', 0) ?>">
                </div>

                <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/carousel') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fas fa-save mr-2 text-sm"></i>Simpan Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage() {
        const input = document.querySelector('input[name="image"]');
        const preview = document.querySelector('#image-preview');
        const container = document.querySelector('#image-preview-container');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?= $this->endSection() ?>