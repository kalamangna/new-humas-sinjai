<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Kategori<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/categories/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fa-solid fa-fw fa-circle-plus mr-2"></i>Tambah Kategori
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Search Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
    <form action="<?= base_url('admin/categories') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="md:col-span-3">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari</label>
            <input type="text" name="search" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Masukkan ..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div class="flex flex-col justify-end">
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Cari</button>
                <a href="<?= base_url('admin/categories') ?>" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-blue-800 p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total</p>
                <h3 class="text-3xl font-black mt-1"><?= $total_categories ?? '0' ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-folder text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-sky-600 p-6 rounded-2xl shadow-lg shadow-sky-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Berita</p>
                <h3 class="text-3xl font-black mt-1"><?= $total_posts ?? '0' ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-newspaper text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Nama</th>
                    <th class="px-8 py-5">Slug</th>
                    <th class="px-8 py-5">Induk</th>
                    <th class="px-8 py-5">Total Berita</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="categories-data" class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-center mr-4 group-hover:bg-blue-800 group-hover:text-white transition-all">
                                        <i class="fa-solid fa-fw fa-folder"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 tracking-tight leading-tight"><?= esc($category['name']) ?></div>
                                        <?php if (!empty($category['description'])) : ?>
                                            <div class="text-[10px] text-slate-400 font-medium italic mt-0.5"><?= esc($category['description']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-100 text-slate-500 font-mono text-[10px] rounded-md border border-slate-200">/<?= esc($category['slug']) ?></span>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-600 font-bold tracking-tight">
                                <?= esc($category['parent_name'] ?? '-') ?>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                    <?= $category['post_count'] ?? '0' ?> Berita
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap w-1">
                                <a href="<?= base_url('admin/categories/' . $category['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-fw fa-pen-to-square"></i>
                                </a>
                                <form action="<?= base_url('admin/categories/' . $category['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        <i class="fa-solid fa-fw fa-trash"></i>
                                    </button>
                                </form>
                                <?php if (($category['post_count'] ?? 0) > 0): ?>
                                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>" target="_blank" class="inline-flex items-center p-2 bg-slate-100 text-sky-600 rounded-lg hover:bg-sky-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-fw fa-up-right-from-square"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fa-solid fa-fw fa-folder-open text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Belum ada kategori</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($pager) && $pager->getPageCount() > 1) : ?>
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
            Total Data: <span class="text-slate-900"><?= number_format($pager->getTotal()) ?></span> Kategori
        </div>
        <div>
            <?= $pager->links('default', 'custom_pager') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>