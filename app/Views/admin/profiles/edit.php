<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Ubah Profil Pejabat<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/profiles') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fa-solid fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fa-solid fa-fw fa-pen-to-square text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Pembaruan Data</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #<?= $profile['id'] ?> • <?= esc($profile['name'] ?? 'Profil') ?></p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form action="<?= base_url('admin/profiles/' . $profile['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-10">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Column 1: Identity -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('name', $profile['name']) ?>">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Jabatan</label>
                            <input type="text" name="position" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('position', $profile['position']) ?>">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Instansi / OPD</label>
                            <input type="text" name="institution" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('institution', $profile['institution'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Column 2: Classification -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Tipe Klasifikasi <span class="text-red-600">*</span></label>
                            <select name="type" id="type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none appearance-none cursor-pointer">
                                <option value="bupati" <?= old('type', $profile['type']) == 'bupati' ? 'selected' : '' ?>>Bupati</option>
                                <option value="wakil-bupati" <?= old('type', $profile['type']) == 'wakil-bupati' ? 'selected' : '' ?>>Wakil Bupati</option>
                                <option value="sekda" <?= old('type', $profile['type']) == 'sekda' ? 'selected' : '' ?>>Sekda</option>
                                <option value="forkopimda" <?= old('type', $profile['type']) == 'forkopimda' ? 'selected' : '' ?>>Forkopimda</option>
                                <option value="eselon-ii" <?= old('type', $profile['type']) == 'eselon-ii' ? 'selected' : '' ?>>Eselon II</option>
                                <option value="eselon-iii" <?= old('type', $profile['type']) == 'eselon-iii' ? 'selected' : '' ?>>Eselon III</option>
                                <option value="eselon-iv" <?= old('type', $profile['type']) == 'eselon-iv' ? 'selected' : '' ?>>Eselon IV</option>
                                <option value="kepala-desa" <?= old('type', $profile['type']) == 'kepala-desa' ? 'selected' : '' ?>>Kepala Desa</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Urutan Tampil</label>
                            <input type="number" name="order" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('order', $profile['order']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Foto Profil Section -->
                <div id="image-container" class="space-y-4 pt-10 border-t border-slate-100">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Foto Profil</label>
                    <label class="block cursor-pointer">
                        <div class="flex items-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl hover:border-blue-800 hover:bg-slate-100 transition-all">
                            <i class="fa-solid fa-fw fa-camera text-slate-400 mr-3"></i>
                            <span class="text-sm font-bold text-slate-500 truncate" id="file-name"><?= $profile['image'] ? 'Ganti Foto' : 'Pilih Foto' ?></span>
                            <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage('image', 'image-preview', 'image-preview-container'); document.getElementById('file-name').innerText = this.files[0].name;">
                        </div>
                    </label>
                    
                    <div id="image-preview-container" class="mt-4 ring-4 ring-slate-50 rounded-2xl overflow-hidden shadow-xl border border-slate-200 inline-block <?= empty($profile['image']) ? 'hidden' : '' ?>">
                        <?php 
                            $imgPath = $profile['image'] ?? '';
                            $imgSrc = filter_var($imgPath, FILTER_VALIDATE_URL) ? $imgPath : (!empty($imgPath) ? base_url($imgPath) : '');
                        ?>
                        <img id="image-preview" src="<?= $imgSrc ?>" class="h-64 w-auto object-cover">
                    </div>
                </div>

                <div id="bio-container" class="space-y-4">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Biografi Singkat</label>
                    <textarea name="bio" id="bio" rows="10" class="w-full"><?= old('bio', $profile['bio']) ?></textarea>
                </div>

                <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/profiles') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fa-solid fa-fw fa-floppy-disk mr-2 text-sm"></i>Perbarui Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('partials/admin_validation_script') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const bioContainer = document.getElementById('bio-container');
        const imageContainer = document.getElementById('image-container');

        function toggleFields() {
            const hideList = ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa'];
            const isHidden = hideList.includes(typeSelect.value);
            bioContainer.style.display = isHidden ? 'none' : 'block';
            imageContainer.style.display = isHidden ? 'none' : 'block';
        }

        typeSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>

<?= $this->endSection() ?>