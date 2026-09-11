<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Pengaturan<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form action="<?= base_url('admin/settings/update') ?>" method="post">
    <?= csrf_field() ?>

    <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <!-- Tabs Header -->
        <div class="bg-slate-50 border-b border-slate-200 px-4 sm:px-8 overflow-x-auto">
            <nav class="flex space-x-4 sm:space-x-8 whitespace-nowrap min-w-max" aria-label="Tabs">
                <?php 
                $groups = [
                    'general' => 'Umum',
                    'contact' => 'Kontak',
                    'social'  => 'Sosial',
                    'about'   => 'Tentang'
                ];
                $activeGroup = 'general';
                foreach ($groups as $key => $label): ?>
                    <button type="button" 
                        onclick="switchTab('<?= $key ?>')"
                        id="tab-btn-<?= $key ?>"
                        class="tab-btn py-4 sm:py-5 px-2 sm:px-1 border-b-2 font-black text-[10px] uppercase tracking-widest transition-all <?= $key === $activeGroup ? 'border-blue-800 text-blue-800' : 'border-transparent text-slate-400 hover:text-slate-600' ?>">
                        <?= $label ?>
                    </button>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-4 sm:p-8 md:p-12">
            <?php foreach ($groups as $groupKey => $groupLabel): ?>
                <div id="tab-content-<?= $groupKey ?>" class="tab-content <?= $groupKey === $activeGroup ? '' : 'hidden' ?> space-y-6 sm:space-y-8">
                    <div class="grid grid-cols-1 gap-6 sm:gap-8">
                        <?php if (isset($grouped_settings[$groupKey])): ?>
                            <?php foreach ($grouped_settings[$groupKey] as $setting): ?>
                                <div class="max-w-3xl">
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 sm:mb-3">
                                        <?= esc($setting['label']) ?>
                                    </label>
                                    
                                    <?php if ($setting['type'] === 'text'): ?>
                                        <input type="text" name="settings[<?= $setting['key'] ?>]" value="<?= esc($setting['value']) ?>"
                                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 focus:border-transparent transition-all">
                                    
                                    <?php elseif ($setting['type'] === 'textarea'): ?>
                                        <textarea name="settings[<?= $setting['key'] ?>]" rows="4"
                                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 focus:border-transparent transition-all"><?= esc($setting['value']) ?></textarea>
                                    
                                    <?php elseif ($setting['type'] === 'image'): ?>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-xl sm:rounded-2xl border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                <img src="<?= base_url($setting['value']) ?>" class="max-w-full max-h-full object-contain">
                                            </div>
                                            <input type="text" name="settings[<?= $setting['key'] ?>]" value="<?= esc($setting['value']) ?>"
                                                class="flex-1 min-w-0 bg-slate-50 border-slate-200 rounded-xl px-4 sm:px-5 py-2.5 sm:py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-800 focus:border-transparent transition-all">
                                        </div>
                                        <p class="mt-2 text-[9px] text-slate-400 font-medium italic">*Masukkan path file di folder public (contoh: logo.png)</p>

                                    <?php elseif ($setting['type'] === 'json'): ?>
                                        <div class="p-4 sm:p-6 bg-slate-50 rounded-xl sm:rounded-2xl border border-slate-200 border-dashed">
                                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-3 sm:mb-4 flex items-center">
                                                <i class="fa-solid fa-fw fa-triangle-exclamation mr-2"></i> Pengaturan JSON (Raw)
                                            </p>
                                            <textarea name="settings[<?= $setting['key'] ?>]" rows="6"
                                                class="w-full bg-white border-slate-200 rounded-xl px-4 sm:px-5 py-2.5 sm:py-3 text-xs font-mono text-slate-600 focus:ring-2 focus:ring-blue-800 focus:border-transparent transition-all"><?= esc($setting['value']) ?></textarea>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer Actions -->
        <div class="bg-slate-50 border-t border-slate-200 px-4 sm:px-8 py-4 sm:py-6 flex justify-end">
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 sm:px-8 py-3.5 sm:py-4 bg-blue-800 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20 text-center">
                <i class="fa-solid fa-fw fa-floppy-disk mr-2 sm:mr-3"></i> Simpan
            </button>
        </div>
    </div>
</form>

<!-- Open Graph Image Maintenance Card -->
<div class="mt-6 sm:mt-8 bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-slate-200 p-6 sm:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h4 class="text-sm sm:text-base font-black text-slate-800 tracking-tight">Sinkronisasi Gambar Open Graph</h4>
            <p class="text-xs text-slate-500 mt-1 font-medium max-w-xl">
                Buat otomatis berkas gambar 1200×630 px (&lt;200 KB) untuk semua berita agar pratinjau saat dibagikan ke WhatsApp dan media sosial selalu tampil penuh di bagian atas tanpa perlu akses terminal.
            </p>
        </div>
        <form action="<?= base_url('admin/site-settings/generate-og') ?>" method="post" class="flex-shrink-0">
            <?= csrf_field() ?>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 bg-slate-800 text-white font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-slate-900 transition-all shadow-md">
                <i class="fa-solid fa-fw fa-wand-magic-sparkles mr-2"></i> Sinkronkan Sekarang
            </button>
        </form>
    </div>
</div>

<script>
    function switchTab(groupId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        // Show selected content
        document.getElementById('tab-content-' + groupId).classList.remove('hidden');
        
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-800', 'text-blue-800');
            btn.classList.add('border-transparent', 'text-slate-400');
        });
        
        const activeBtn = document.getElementById('tab-btn-' + groupId);
        activeBtn.classList.remove('border-transparent', 'text-slate-400');
        activeBtn.classList.add('border-blue-800', 'text-blue-800');
    }
</script>

<?= $this->endSection() ?>
