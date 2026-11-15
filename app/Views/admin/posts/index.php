<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Berita<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/posts/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fa-solid fa-fw fa-circle-plus mr-2"></i>Tambah Berita
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
    <form action="<?= base_url('admin/posts') ?>" method="get" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label for="post-search" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari</label>
            <input id="post-search" type="text" name="search" class="w-full h-10 px-4 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Judul berita" value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div>
            <label for="post-category" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
            <select id="post-category" name="category" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>" <?= ($filters['category'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="post-author" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Penulis</label>
            <select id="post-author" name="author" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Penulis</option>
                <?php foreach ($users as $user) : ?>
                    <option value="<?= $user['id'] ?>" <?= ($filters['author'] ?? '') == $user['id'] ? 'selected' : '' ?>><?= esc($user['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="post-status" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
            <select id="post-status" name="status" class="w-full h-10 px-3 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Status</option>
                <option value="published" <?= ($filters['status'] ?? '') == 'published' ? 'selected' : '' ?>>Terbit</option>
                <option value="draft" <?= ($filters['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Konsep</option>
            </select>
        </div>
        <div class="flex flex-col justify-end sm:col-span-2 lg:col-span-1">
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center h-10 px-4 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Filter</button>
                <a href="<?= base_url('admin/posts') ?>" class="inline-flex items-center justify-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-blue-800 p-5 sm:p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($total_posts ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-newspaper text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-emerald-600 p-5 sm:p-6 rounded-2xl shadow-lg shadow-emerald-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Telah Terbit</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($published_posts ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-circle-check text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-amber-500 p-5 sm:p-6 rounded-2xl shadow-lg shadow-amber-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Draf Konsep</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($draft_posts ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-pen-to-square text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-sky-600 p-5 sm:p-6 rounded-2xl shadow-lg shadow-sky-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Hari Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($today_posts ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-calendar-day text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Informasi Berita</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Kategori</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Status</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Statistik</th>
                    <th class="px-4 sm:px-8 py-3 sm:py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($posts)) : ?>
                    <?php foreach ($posts as $post) : ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center">
                                    <div class="w-16 sm:w-20 h-10 sm:h-12 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                        <?php
                                        $thumbPath = $post['thumbnail'] ?? '';
                                        $thumbSrc = filter_var($thumbPath, FILTER_VALIDATE_URL) ? $thumbPath : (!empty($thumbPath) ? base_url($thumbPath) : '');
                                        ?>
                                        <?php if (!empty($thumbSrc)) : ?>
                                            <img src="<?= $thumbSrc ?>" class="w-full h-full object-cover">
                                        <?php else : ?>
                                            <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-fw fa-image text-slate-300"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-3 sm:ml-4 min-w-0">
                                        <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors truncate max-w-[180px] sm:max-w-xs" title="<?= esc($post['title']) ?>"><?= esc($post['title']) ?></div>
                                        <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-tighter"><?= esc($post['author_name'] ?? 'Penulis') ?> • <?= format_date($post['created_at']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex flex-wrap gap-1 mb-1">
                                    <?php if (!empty($post['category_name'])) : ?>
                                        <?php foreach (explode(',', $post['category_name']) as $name) : ?>
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[9px] font-black rounded uppercase"><?= esc(trim($name)) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><i class="fa-solid fa-fw fa-tags mr-1"></i><?= $post['tag_count'] ?> Tag</div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <?php if ($post['status'] === 'published') : ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>Terbit
                                    </span>
                                <?php else : ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>Konsep
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-bold text-slate-500 whitespace-nowrap">
                                <div class="flex items-center"><i class="fa-regular fa-fw fa-eye w-4 text-slate-400"></i><?= number_format($post['views'] ?? 0, 0, ',', '.') ?> Dilihat</div>
                                <div class="flex items-center mt-1"><i class="fa-regular fa-fw fa-calendar-check w-4 text-slate-400"></i><?= $post['published_at'] ? format_date($post['published_at'], 'short_date_only') : '-' ?></div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-right space-x-1 whitespace-nowrap w-1">
                                <?php if ($post['status'] === 'published') : ?>
                                    <a href="<?= base_url('post/' . esc($post['slug'] ?? '')) ?>" target="_blank" rel="noopener noreferrer" aria-label="Lihat berita" title="Lihat berita" class="inline-flex items-center p-2 bg-slate-100 text-sky-600 rounded-lg hover:bg-sky-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-fw fa-up-right-from-square"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/posts/' . $post['id'] . '/edit') ?>" aria-label="Ubah berita" title="Ubah berita" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-fw fa-pen-to-square"></i>
                                </a>
                                <form action="<?= base_url('admin/posts/' . $post['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" aria-label="Hapus berita" title="Hapus berita" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus berita ini? Berita yang dihapus tidak dapat dikembalikan.')">
                                        <i class="fa-solid fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fa-solid fa-fw fa-inbox text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Data tidak ditemukan</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($pager) && $pager->getPageCount('posts') > 1) : ?>
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
            Total: <span class="text-slate-900"><?= number_format($pager->getTotal('posts'), 0, ',', '.') ?></span>
        </div>
        <div>
            <?= $pager->only(['search', 'category', 'author', 'status'])->links('posts', 'custom_pager') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>