<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Kategori' => current_url()
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-fw fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Kategori Informasi</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Indeks Informasi</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            <?= esc($seo['title']) ?>
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full"></div>
    </div>

    <?php if (!empty($categories)) : ?>
        <div class="max-w-5xl mx-auto grid grid-cols-1 gap-12">
            <?php foreach ($categories as $category) : ?>
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Category Header -->
                    <div class="px-8 py-8 bg-slate-50 border-b border-slate-200 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div class="p-4 bg-blue-800 text-white rounded-2xl shadow-lg shadow-blue-900/20 mr-6">
                                <i class="fas fa-fw fa-folder-open text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight"><?= esc($category['name']) ?></h2>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Klasifikasi Utama</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 md:p-12">
                        <?php if (isset($subCategories[$category['id']])) : ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php foreach ($subCategories[$category['id']] as $subCategory) : ?>
                                    <a href="<?= base_url('category/' . esc($subCategory['slug'])) ?>"
                                        class="flex items-center justify-between p-5 bg-white border border-slate-100 rounded-2xl hover:border-blue-800 hover:shadow-xl hover:shadow-blue-900/5 transition-all group">
                                        <div class="flex items-center min-w-0">
                                            <i class="fas fa-fw fa-folder text-slate-200 group-hover:text-blue-800 mr-4 transition-colors flex-shrink-0"></i>
                                            <h3 class="font-bold text-slate-700 group-hover:text-slate-900 transition-colors truncate tracking-tight"><?= esc($subCategory['name']) ?></h3>
                                        </div>
                                        <div class="flex-shrink-0 ml-4 px-3 py-1 bg-slate-50 text-slate-400 text-[10px] font-black rounded-lg border border-slate-100 group-hover:bg-blue-50 group-hover:text-blue-800 group-hover:border-blue-100 transition-all">
                                            <?= number_format($subCategory['post_count']) ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="py-6 px-8 border-2 border-dashed border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 italic text-sm font-medium">
                                Belum ada sub-kategori tersedia untuk klasifikasi ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="text-center py-24 bg-white rounded-[3rem] border border-slate-200 shadow-sm px-8 max-w-2xl mx-auto">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-10 text-slate-200">
                <i class="fas fa-fw fa-folder-minus text-5xl"></i>
            </div>
            <h2 class="text-3xl font-black text-slate-900 mb-4 tracking-tight">Data Tidak Ditemukan</h2>
            <p class="text-slate-500 mb-8 leading-relaxed font-medium">Saat ini belum ada kategori informasi yang dipublikasikan oleh sistem. Silakan periksa kembali dalam beberapa saat.</p>
            <a href="<?= base_url() ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fas fa-fw fa-home mr-3 text-base"></i>Beranda Utama
            </a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>