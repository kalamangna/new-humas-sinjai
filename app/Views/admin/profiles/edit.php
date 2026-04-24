<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Profil<?= $this->endSection() ?>

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
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Ubah Profil</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #<?= $profile['id'] ?></p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form action="<?= base_url('admin/profiles/' . $profile['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-10 needs-validation" novalidate>
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Column 1: Identity -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Tipe <span class="text-red-600">*</span></label>
                            <select name="type" id="type" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none appearance-none cursor-pointer">
                                <option value="bupati" <?= old('type', $profile['type']) == 'bupati' ? 'selected' : '' ?>>Bupati</option>
                                <option value="wakil-bupati" <?= old('type', $profile['type']) == 'wakil-bupati' ? 'selected' : '' ?>>Wakil Bupati</option>
                                <option value="sekda" <?= old('type', $profile['type']) == 'sekda' ? 'selected' : '' ?>>Sekda</option>
                                <option value="forkopimda" <?= old('type', $profile['type']) == 'forkopimda' ? 'selected' : '' ?>>Forkopimda</option>
                                <option value="eselon-ii" <?= old('type', $profile['type']) == 'eselon-ii' ? 'selected' : '' ?>>Eselon II</option>
                                <option value="eselon-iii" <?= old('type', $profile['type']) == 'eselon-iii' ? 'selected' : '' ?>>Eselon III</option>
                                <option value="lurah" <?= old('type', $profile['type']) == 'lurah' ? 'selected' : '' ?>>Lurah</option>
                                <option value="kepala-desa" <?= old('type', $profile['type']) == 'kepala-desa' ? 'selected' : '' ?>>Kepala Desa</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('name', $profile['name']) ?>">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Jabatan <span class="text-red-600">*</span></label>
                            <input type="text" name="position" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('position', $profile['position']) ?>">
                        </div>

                        <!-- New Fields for Region -->
                        <div id="kecamatan-container" class="space-y-4 hidden">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan_select" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none appearance-none cursor-pointer" data-selected="<?= old('kecamatan', $profile['kecamatan'] ?? '') ?>">
                                <option value="">Pilih Kecamatan...</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <label id="institution_label" class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Instansi / OPD</label>
                            <input type="text" name="institution" id="institution_input" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('institution', $profile['institution'] ?? '') ?>" placeholder="Masukkan instansi">
                            <select name="institution" id="institution_select" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none appearance-none cursor-pointer hidden" disabled data-selected="<?= old('institution', $profile['institution'] ?? '') ?>">
                                <option value="">Pilih Desa...</option>
                            </select>
                        </div>
                        
                        <div id="kelurahan-container" class="space-y-4 hidden">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Kelurahan</label>
                            <input type="text" name="kelurahan" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('kelurahan', $profile['kelurahan'] ?? '') ?>" placeholder="Nama Kelurahan">
                        </div>
                        
                        <div id="desa-container" class="space-y-4 hidden">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Desa</label>
                            <input type="text" name="desa" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('desa', $profile['desa'] ?? '') ?>" placeholder="Nama Desa">
                        </div>
                    </div>

                    <!-- Column 2: Classification -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Urutan Tampil <span class="text-red-600">*</span></label>
                            <input type="number" name="order" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-800 outline-none transition-all" value="<?= old('order', $profile['order']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Foto Profil Section -->
                <div id="image-container" class="space-y-4 pt-10 border-t border-slate-100">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Foto</label>
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
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Biografi</label>
                    <textarea name="bio" id="bio" rows="10" class="w-full"><?= old('bio', $profile['bio']) ?></textarea>
                </div>

                <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/profiles') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                        <i class="fa-solid fa-fw fa-floppy-disk mr-2 text-sm"></i>Simpan
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
        const institutionLabel = document.getElementById('institution_label');
        const institutionInput = document.getElementById('institution_input');
        const institutionSelect = document.getElementById('institution_select');
        const bioContainer = document.getElementById('bio-container');
        const imageContainer = document.getElementById('image-container');
        const kecamatanContainer = document.getElementById('kecamatan-container');
        const kelurahanContainer = document.getElementById('kelurahan-container');
        const desaContainer = document.getElementById('desa-container');
        const kecamatanSelect = document.getElementById('kecamatan_select');

        function toggleFields() {
            if (!typeSelect || !institutionLabel || !institutionInput || !institutionSelect) return;

            const hideList = ['forkopimda', 'eselon-ii', 'eselon-iii', 'lurah', 'kepala-desa'];
            const isHidden = hideList.includes(typeSelect.value);
            if (bioContainer) bioContainer.style.display = isHidden ? 'none' : 'block';
            if (imageContainer) imageContainer.style.display = isHidden ? 'none' : 'block';
            
            const type = typeSelect.value;
            const positionInput = document.querySelector('input[name="position"]');
            
            if (positionInput) {
                if (type === 'kepala-desa') {
                    if (!positionInput.value || positionInput.value === '') positionInput.value = 'Kepala Desa';
                    positionInput.readOnly = true;
                    positionInput.classList.add('bg-slate-100', 'text-slate-500');
                } else {
                    positionInput.readOnly = false;
                    positionInput.classList.remove('bg-slate-100', 'text-slate-500');
                }
            }
            
            if (type === 'lurah') {
                institutionLabel.textContent = 'Instansi / OPD';
                institutionInput.placeholder = 'e.g. Kecamatan Sinjai Utara';
                institutionInput.style.display = 'block';
                institutionInput.disabled = false;
                institutionSelect.style.display = 'none';
                institutionSelect.disabled = true;
                if (kecamatanContainer) kecamatanContainer.style.display = 'none';
                if (kelurahanContainer) kelurahanContainer.style.display = 'none';
                if (desaContainer) desaContainer.style.display = 'none';
            } else if (type === 'kepala-desa') {
                institutionLabel.textContent = 'Desa';
                institutionInput.style.display = 'none';
                institutionInput.disabled = true;
                institutionSelect.style.display = 'block';
                institutionSelect.disabled = false;
                if (kecamatanContainer) kecamatanContainer.style.display = 'block';
                if (kelurahanContainer) kelurahanContainer.style.display = 'none';
                if (desaContainer) desaContainer.style.display = 'none';
                if (kecamatanSelect && kecamatanSelect.value) fetchWilayah(kecamatanSelect.value, 'Desa', institutionSelect, institutionSelect.dataset.selected);
            } else {
                institutionLabel.textContent = 'Instansi / OPD';
                institutionInput.placeholder = 'Masukkan instansi / OPD';
                institutionInput.style.display = 'block';
                institutionInput.disabled = false;
                institutionSelect.style.display = 'none';
                institutionSelect.disabled = true;
                if (kecamatanContainer) kecamatanContainer.style.display = 'none';
                if (kelurahanContainer) kelurahanContainer.style.display = 'none';
                if (desaContainer) desaContainer.style.display = 'none';
            }
        }

        async function fetchKecamatan() {
            try {
                const response = await fetch('<?= base_url('admin/profiles/get_kecamatan') ?>');
                const data = await response.json();
                
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                const selectedKecamatan = kecamatanSelect.dataset.selected;
                
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.kecamatan_nama; 
                        option.textContent = item.kecamatan_nama;
                        if (item.kecamatan_nama === selectedKecamatan) {
                            option.selected = true;
                        }
                        kecamatanSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching kecamatan:', error);
            }
        }

        async function fetchWilayah(kecamatan, tipe, selectElement, selectedValue = null) {
            selectElement.innerHTML = `<option value="">Pilih ${tipe}...</option>`;
            selectElement.disabled = true;

            if (!kecamatan) return;

            try {
                const response = await fetch(`<?= base_url('admin/profiles/get_wilayah') ?>?tipe=${tipe}`);
                const data = await response.json();
                
                if (data && data.length > 0) {
                    data.forEach(item => {
                        if (!item.kecamatan_nama || item.kecamatan_nama === kecamatan) {
                            const option = document.createElement('option');
                            const namaWilayah = item.desa_nama;
                            option.value = (tipe === 'Desa' ? '' : 'Kelurahan ') + namaWilayah; 
                            option.textContent = (tipe === 'Desa' ? '' : 'Kelurahan ') + namaWilayah;
                            if (option.value === selectedValue) {
                                option.selected = true;
                            }
                            selectElement.appendChild(option);
                        }
                    });
                    selectElement.disabled = false;
                }
            } catch (error) {
                console.error(`Error fetching ${tipe}:`, error);
            }
        }

        typeSelect.addEventListener('change', toggleFields);
        kecamatanSelect.addEventListener('change', () => {
            if (typeSelect.value === 'kepala-desa') {
                fetchWilayah(kecamatanSelect.value, 'Desa', institutionSelect, institutionSelect.dataset.selected);
            }
        });

        fetchKecamatan().then(() => {
            toggleFields();
        });
    });
</script>

<?= $this->endSection() ?>