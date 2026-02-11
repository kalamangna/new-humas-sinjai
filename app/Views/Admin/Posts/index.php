<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Berita<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/posts/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fas fa-fw fa-plus-circle mr-2"></i>Buat Berita
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
    <form action="<?= base_url('admin/posts') ?>" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari Judul</label>
            <input type="text" name="search" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Kata kunci..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kategori</label>
            <select name="category" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'] ?>" <?= ($filters['category'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Penulis</label>
            <select name="author" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Penulis</option>
                <?php foreach ($users as $user) : ?>
                    <option value="<?= $user['id'] ?>" <?= ($filters['author'] ?? '') == $user['id'] ? 'selected' : '' ?>><?= esc($user['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Status</option>
                <option value="published" <?= ($filters['status'] ?? '') == 'published' ? 'selected' : '' ?>>Tayang</option>
                <option value="draft" <?= ($filters['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Konsep</option>
            </select>
        </div>
        <div class="flex flex-col justify-end">
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Terapkan</button>
                <a href="<?= base_url('admin/posts') ?>" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-blue-800 p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Berita</p>
                <h3 class="text-3xl font-black mt-1"><?= $total_posts ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-newspaper text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-emerald-600 p-6 rounded-2xl shadow-lg shadow-emerald-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Tayang</p>
                <h3 class="text-3xl font-black mt-1"><?= $published_posts ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-check-circle text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-amber-500 p-6 rounded-2xl shadow-lg shadow-amber-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Konsep</p>
                <h3 class="text-3xl font-black mt-1"><?= $draft_posts ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-edit text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-sky-600 p-6 rounded-2xl shadow-lg shadow-sky-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Hari Ini</p>
                <h3 class="text-3xl font-black mt-1"><?= $today_posts ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-calendar-day text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-6 py-4">Informasi Berita</th>
                    <th class="px-6 py-4">Kategori & Label</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Statistik</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($posts)) : ?>
                    <?php foreach ($posts as $post) : ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-20 h-12 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                        <?php if (!empty($post['thumbnail'])) : ?>
                                            <img src="<?= esc($post['thumbnail']) ?>" class="w-full h-full object-cover">
                                        <?php else : ?>
                                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-fw fa-image text-slate-300"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ml-4 min-w-0">
                                        <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors truncate max-w-xs"><?= esc($post['title']) ?></div>
                                        <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-tighter"><?= esc($post['author_name'] ?? 'Admin') ?> • <?= format_date($post['created_at']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 mb-1">
                                    <?php if (!empty($post['category_name'])) : ?>
                                        <?php foreach (explode(',', $post['category_name']) as $name) : ?>
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-800 text-[9px] font-black rounded uppercase"><?= esc(trim($name)) ?></span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="text-[9px] text-slate-400 font-bold uppercase tracking-widest"><i class="fas fa-fw fa-tags mr-1"></i><?= $post['tag_count'] ?> Label</div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($post['status'] === 'published') : ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>Tayang
                                    </span>
                                <?php else : ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>Konsep
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                <div class="flex items-center"><i class="far fa-fw fa-eye w-4 text-slate-400"></i><?= number_format($post['views'] ?? 0) ?> Dilihat</div>
                                <div class="flex items-center mt-1"><i class="far fa-fw fa-calendar-check w-4 text-slate-400"></i><?= $post['published_at'] ? date('d/m/y', strtotime($post['published_at'])) : '-' ?></div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                <a href="<?= base_url('admin/posts/' . $post['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/posts/' . $post['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus berita ini?')">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </form>
                                <?php if ($post['status'] === 'published') : ?>
                                    <a href="<?= base_url('post/' . esc($post['slug'])) ?>" target="_blank" class="inline-flex items-center p-2 bg-slate-100 text-sky-600 rounded-lg hover:bg-sky-600 hover:text-white transition-all">
                                        <i class="fas fa-fw fa-external-link-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fas fa-fw fa-inbox text-2xl"></i>
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
            Total Data: <span class="text-slate-900"><?= number_format($pager->getTotal('posts')) ?></span> Berita
        </div>
        <div>
            <?= $pager->only(['search', 'category', 'author'])->links('posts', 'custom_bootstrap') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>