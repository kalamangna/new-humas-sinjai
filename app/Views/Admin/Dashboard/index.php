<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Dasbor Utama<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<button class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold uppercase tracking-widest text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" onclick="window.location.reload()">
    <i class="fas fa-fw fa-sync-alt mr-2 text-blue-600"></i>Perbarui Data
</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<?php 
    $role = session()->get('role');
    $isAdmin = $role === 'admin'; 
    $isAuthor = $role === 'author';
    $isStreamer = $role === 'streamer';

    $gridCols = 'lg:grid-cols-4';
    if ($isAuthor) $gridCols = 'lg:grid-cols-3';
    if ($isStreamer) $gridCols = 'lg:grid-cols-1';
?>
<div class="grid grid-cols-1 md:grid-cols-2 <?= $gridCols ?> gap-6 mb-10">
    <?php if ($isAdmin || $isAuthor) : ?>
        <!-- Posts Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-blue-800 transition-all">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-blue-50 text-blue-800 rounded-2xl group-hover:bg-blue-800 group-hover:text-white transition-all">
                    <i class="fas fa-fw fa-newspaper text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter"><?= $postCount ?? '0' ?></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Berita</p>
                </div>
            </div>
            <a href="<?= base_url('admin/posts') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-blue-800 hover:text-blue-900 uppercase tracking-[0.2em] flex items-center">
                Kelola Berita <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>

        <!-- Categories Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-emerald-600 transition-all">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-fw fa-folder text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter"><?= $categoryCount ?? '0' ?></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Kategori Berita</p>
                </div>
            </div>
            <a href="<?= base_url('admin/categories') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-[0.2em] flex items-center">
                Kelola Kategori <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>

        <!-- Tags Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-purple-600 transition-all">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-purple-600 group-hover:text-white transition-all">
                    <i class="fas fa-fw fa-tags text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter"><?= $tagCount ?? '0' ?></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Label</p>
                </div>
            </div>
            <a href="<?= base_url('admin/tags') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-purple-600 hover:text-purple-700 uppercase tracking-[0.2em] flex items-center">
                Kelola Label <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($isStreamer) : ?>
        <!-- Live Streaming Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-red-600 transition-all">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-red-50 text-red-600 rounded-2xl group-hover:bg-red-600 group-hover:text-white transition-all">
                    <i class="fas fa-fw fa-tv text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Live</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Streaming Tv</p>
                </div>
            </div>
            <a href="<?= base_url('admin/live-streams') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-red-600 hover:text-red-700 uppercase tracking-[0.2em] flex items-center">
                Kelola Live <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($isAdmin) : ?>
        <!-- Users Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-orange-600 transition-all">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-all">
                    <i class="fas fa-fw fa-users text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter"><?= $userCount ?? '0' ?></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sistem Pengguna</p>
                </div>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-orange-600 hover:text-orange-700 uppercase tracking-[0.2em] flex items-center">
                Kelola Pengguna <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden mb-10">
    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center">
        <i class="fas fa-fw fa-bolt mr-4 text-yellow-500"></i>
        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Aksi Pintar Sistem</h2>
    </div>
    <div class="p-8">
        <?php
            $actionCols = 'lg:grid-cols-4';
            if ($isAuthor) $actionCols = 'lg:grid-cols-3';
            if ($isStreamer) $actionCols = 'lg:grid-cols-1';
        ?>
        <div class="grid grid-cols-2 <?= $actionCols ?> gap-6">
            <?php if ($isAdmin || $isAuthor) : ?>
                <a href="<?= base_url('admin/posts/new') ?>" class="flex flex-col items-center justify-center p-8 bg-blue-800 rounded-3xl text-white hover:bg-blue-900 transition-all group shadow-lg shadow-blue-900/20">
                    <i class="fas fa-fw fa-plus-circle text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest text-center">Buat Berita</span>
                </a>
                <a href="<?= base_url('admin/categories/new') ?>" class="flex flex-col items-center justify-center p-8 bg-emerald-600 rounded-3xl text-white hover:bg-emerald-700 transition-all group shadow-lg shadow-emerald-900/20">
                    <i class="fas fa-fw fa-folder-plus text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest text-center">Tambah Kategori</span>
                </a>
                <a href="<?= base_url('admin/tags/new') ?>" class="flex flex-col items-center justify-center p-8 bg-purple-600 rounded-3xl text-white hover:bg-purple-700 transition-all group shadow-lg shadow-purple-900/20">
                    <i class="fas fa-fw fa-tag text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest text-center">Tambah Label</span>
                </a>
            <?php endif; ?>

            <?php if ($isStreamer) : ?>
                <?php if (($live_stream_count ?? 0) === 0) : ?>
                    <a href="<?= base_url('admin/live-streams/new') ?>" class="flex flex-col items-center justify-center p-8 bg-red-600 rounded-3xl text-white hover:bg-red-700 transition-all group shadow-lg shadow-red-900/20">
                        <i class="fas fa-fw fa-tv text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                        <span class="font-black text-[10px] uppercase tracking-widest text-center">Tambah Live Stream</span>
                    </a>
                <?php else : ?>
                    <a href="<?= base_url('admin/live-streams') ?>" class="flex flex-col items-center justify-center p-8 bg-red-600 rounded-3xl text-white hover:bg-red-700 transition-all group shadow-lg shadow-red-900/20">
                        <i class="fas fa-fw fa-tv text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                        <span class="font-black text-[10px] uppercase tracking-widest text-center">Kelola Live Stream</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($isAdmin) : ?>
                <a href="<?= base_url('admin/users/new') ?>" class="flex flex-col items-center justify-center p-8 bg-slate-800 rounded-3xl text-white hover:bg-slate-950 transition-all group shadow-lg shadow-slate-900/20">
                    <i class="fas fa-fw fa-user-plus text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest text-center">Tambah Pengguna</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Data Tables -->
<?php if (!$isStreamer) : ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Popular Posts -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-fire mr-4 text-orange-500"></i>Berita Terpopuler
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Judul Berita</th>
                        <th class="px-8 py-4 text-right">Pembaca</th>
                    </tr>
                </thead>
                <tbody id="popular-posts-data" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="2" class="px-8 py-16 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-800 border-t-transparent"></div>
                            <p class="mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Singkronisasi Sistem...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                <i class="fas fa-fw fa-history mr-4 text-blue-800"></i>Pembaruan Terkini
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Judul Berita</th>
                        <th class="px-8 py-4 text-right">Tanggal Terbit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($recentPosts)) : ?>
                        <?php foreach ($recentPosts as $post) : ?>
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5 text-xs font-bold text-slate-700 truncate max-w-xs group-hover:text-blue-800 transition-colors"><?= esc($post['title']) ?></td>
                                <td class="px-8 py-5 text-right whitespace-nowrap">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter italic">
                                        <?= format_date($post['published_at'], 'date_only') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2" class="px-8 py-16 text-center text-slate-400 italic text-sm font-medium">Belum ada pembaruan konten.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popularPostsData = document.getElementById('popular-posts-data');

        fetch('<?= base_url('api/analytics/popular-posts') ?>')
            .then(response => response.json())
            .then(data => {
                popularPostsData.innerHTML = '';
                if (data.length === 0) {
                    popularPostsData.innerHTML = '<tr><td colspan="2" class="px-8 py-12 text-center text-slate-400 italic font-medium">Data belum tersedia.</td></tr>';
                    return;
                }
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors group';
                    row.innerHTML = `
                        <td class="px-8 py-5 text-xs font-bold text-slate-700 truncate max-w-xs group-hover:text-blue-800 transition-colors">${item.title}</td>
                        <td class="px-8 py-5 text-right whitespace-nowrap">
                            <span class="px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                ${item.views.toLocaleString()} Hits
                            </span>
                        </td>
                    `;
                    popularPostsData.appendChild(row);
                });
            })
            .catch(error => {
                popularPostsData.innerHTML = '<tr><td colspan="2" class="px-8 py-12 text-center text-red-500 font-black uppercase text-[10px] tracking-widest">Sinkronisasi Gagal.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>