<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Dasbor Utama<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<button class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-xs font-bold uppercase tracking-widest text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" onclick="window.location.reload()">
    <i class="fas fa-fw fa-sync-alt mr-2 text-blue-600"></i>Perbarui Data
</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <?php $isAdmin = session()->get('role') === 'admin'; ?>

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
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-emerald-600 transition-all dark:bg-slate-800 dark:border-slate-700 dark:hover:border-emerald-500">
        <div class="flex items-center mb-6">
            <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl group-hover:bg-emerald-600 group-hover:text-white transition-all dark:bg-emerald-900/30 dark:text-emerald-400">
                <i class="fas fa-fw fa-folder text-xl"></i>
            </div>
            <div class="ml-5">
                <h3 class="text-3xl font-black text-slate-900 tracking-tighter dark:text-slate-100"><?= $categoryCount ?? '0' ?></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Kategori Berita</p>
            </div>
        </div>
        <a href="<?= base_url('admin/categories') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-emerald-600 hover:text-emerald-700 uppercase tracking-[0.2em] flex items-center dark:border-slate-700 dark:text-emerald-400 dark:hover:text-emerald-300">
            Kelola Kategori <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
        </a>
    </div>

    <!-- Tags Card -->
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-purple-600 transition-all dark:bg-slate-800 dark:border-slate-700 dark:hover:border-purple-500">
        <div class="flex items-center mb-6">
            <div class="p-4 bg-purple-50 text-purple-600 rounded-2xl group-hover:bg-purple-600 group-hover:text-white transition-all dark:bg-purple-900/30 dark:text-purple-400">
                <i class="fas fa-fw fa-tags text-xl"></i>
            </div>
            <div class="ml-5">
                <h3 class="text-3xl font-black text-slate-900 tracking-tighter dark:text-slate-100"><?= $tagCount ?? '0' ?></h3>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Total Label</p>
            </div>
        </div>
        <a href="<?= base_url('admin/tags') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-purple-600 hover:text-purple-700 uppercase tracking-[0.2em] flex items-center dark:border-slate-700 dark:text-purple-400 dark:hover:text-purple-300">
            Kelola Label <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
        </a>
    </div>

    <?php if ($isAdmin) : ?>
        <!-- Users Card -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 flex flex-col group hover:border-orange-600 transition-all dark:bg-slate-800 dark:border-slate-700 dark:hover:border-orange-500">
            <div class="flex items-center mb-6">
                <div class="p-4 bg-orange-50 text-orange-600 rounded-2xl group-hover:bg-orange-600 group-hover:text-white transition-all dark:bg-orange-900/30 dark:text-orange-400">
                    <i class="fas fa-fw fa-users text-xl"></i>
                </div>
                <div class="ml-5">
                    <h3 class="text-3xl font-black text-slate-900 tracking-tighter dark:text-slate-100"><?= $userCount ?? '0' ?></h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sistem Pengguna</p>
                </div>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="mt-auto pt-4 border-t border-slate-50 text-[10px] font-black text-orange-600 hover:text-orange-700 uppercase tracking-[0.2em] flex items-center dark:border-slate-700 dark:text-orange-400 dark:hover:text-orange-300">
                Kelola Pengguna <i class="fas fa-fw fa-chevron-right ml-2 opacity-50"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden mb-10 dark:bg-slate-800 dark:border-slate-700">
    <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center dark:bg-slate-900/50 dark:border-slate-700">
        <i class="fas fa-fw fa-bolt mr-4 text-yellow-500"></i>
        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] dark:text-slate-100">Aksi Pintar Sistem</h2>
    </div>
    <div class="p-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
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
            <?php if ($isAdmin) : ?>
                <a href="<?= base_url('admin/users/new') ?>" class="flex flex-col items-center justify-center p-8 bg-slate-800 rounded-3xl text-white hover:bg-slate-950 transition-all group shadow-lg shadow-slate-900/20">
                    <i class="fas fa-fw fa-user-plus text-3xl mb-4 group-hover:scale-110 transition-transform"></i>
                    <span class="font-black text-[10px] uppercase tracking-widest text-center">Tambah Staf</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Popular Posts -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden dark:bg-slate-800 dark:border-slate-700">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between dark:bg-slate-900/50 dark:border-slate-700">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center dark:text-slate-100">
                <i class="fas fa-fw fa-fire mr-4 text-orange-500"></i>Berita Terpopuler
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-[9px] font-black text-slate-400 uppercase tracking-widest dark:bg-slate-900/80 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-4">Judul Berita</th>
                        <th class="px-8 py-4 text-right">Pembaca</th>
                    </tr>
                </thead>
                <tbody id="popular-posts-data" class="divide-y divide-slate-100 dark:divide-slate-700">
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
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden dark:bg-slate-800 dark:border-slate-700">
        <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between dark:bg-slate-900/50 dark:border-slate-700">
            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center dark:text-slate-100">
                <i class="fas fa-fw fa-history mr-4 text-blue-800"></i>Pembaruan Terkini
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100 text-[9px] font-black text-slate-400 uppercase tracking-widest dark:bg-slate-900/80 dark:border-slate-700">
                    <tr>
                        <th class="px-8 py-4">Judul Berita</th>
                        <th class="px-8 py-4 text-right">Tanggal Terbit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    <?php if (!empty($recentPosts)) : ?>
                        <?php foreach ($recentPosts as $post) : ?>
                            <tr class="hover:bg-slate-50 transition-colors group dark:hover:bg-slate-700/50">
                                <td class="px-8 py-5 text-xs font-bold text-slate-700 truncate max-w-xs group-hover:text-blue-800 transition-colors dark:text-slate-300 dark:group-hover:text-blue-400"><?= esc($post['title']) ?></td>
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
                    row.className = 'hover:bg-slate-50 transition-colors group dark:hover:bg-slate-700/50';
                    row.innerHTML = `
                        <td class="px-8 py-5 text-xs font-bold text-slate-700 truncate max-w-xs group-hover:text-blue-800 transition-colors dark:text-slate-300 dark:group-hover:text-blue-400">${item.title}</td>
                        <td class="px-8 py-5 text-right">
                            <span class="px-3 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
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