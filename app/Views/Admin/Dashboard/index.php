<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<button class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors shadow-sm" onclick="window.location.reload()">
    <i class="fas fa-sync-alt mr-2 text-blue-600"></i>Refresh
</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php $isAdmin = session()->get('role') === 'admin'; ?>
    
    <!-- Posts Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                <i class="fas fa-newspaper text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-slate-900"><?= $postCount ?? '0' ?></h3>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Berita</p>
            </div>
        </div>
        <a href="<?= base_url('admin/posts') ?>" class="mt-auto pt-4 border-t border-slate-50 text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center">
            LIHAT SEMUA <i class="fas fa-chevron-right ml-1"></i>
        </a>
    </div>

    <!-- Categories Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                <i class="fas fa-folder text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-slate-900"><?= $categoryCount ?? '0' ?></h3>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</p>
            </div>
        </div>
        <a href="<?= base_url('admin/categories') ?>" class="mt-auto pt-4 border-t border-slate-50 text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center">
            LIHAT SEMUA <i class="fas fa-chevron-right ml-1"></i>
        </a>
    </div>

    <!-- Tags Card -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
        <div class="flex items-center mb-4">
            <div class="p-3 bg-purple-50 rounded-xl text-purple-600">
                <i class="fas fa-tags text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-2xl font-bold text-slate-900"><?= $tagCount ?? '0' ?></h3>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Tags</p>
            </div>
        </div>
        <a href="<?= base_url('admin/tags') ?>" class="mt-auto pt-4 border-t border-slate-50 text-xs font-bold text-purple-600 hover:text-purple-800 flex items-center">
            LIHAT SEMUA <i class="fas fa-chevron-right ml-1"></i>
        </a>
    </div>

    <?php if ($isAdmin) : ?>
        <!-- Users Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-orange-50 rounded-xl text-orange-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-2xl font-bold text-slate-900"><?= $userCount ?? '0' ?></h3>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Pengguna</p>
                </div>
            </div>
            <a href="<?= base_url('admin/users') ?>" class="mt-auto pt-4 border-t border-slate-50 text-xs font-bold text-orange-600 hover:text-orange-800 flex items-center">
                LIHAT SEMUA <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h3 class="font-bold text-slate-900 flex items-center">
            <i class="fas fa-bolt mr-3 text-yellow-500"></i>Aksi Cepat
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="<?= base_url('admin/posts/new') ?>" class="flex flex-col items-center justify-center p-6 bg-blue-600 rounded-xl text-white hover:bg-blue-700 transition-all group">
                <i class="fas fa-plus-circle text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-bold text-sm">Berita Baru</span>
            </a>
            <a href="<?= base_url('admin/categories/new') ?>" class="flex flex-col items-center justify-center p-6 bg-emerald-600 rounded-xl text-white hover:bg-emerald-700 transition-all group">
                <i class="fas fa-folder-plus text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-bold text-sm">Kategori Baru</span>
            </a>
            <a href="<?= base_url('admin/tags/new') ?>" class="flex flex-col items-center justify-center p-6 bg-purple-600 rounded-xl text-white hover:bg-purple-700 transition-all group">
                <i class="fas fa-tag text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                <span class="font-bold text-sm">Tag Baru</span>
            </a>
            <?php if ($isAdmin) : ?>
                <a href="<?= base_url('admin/users/new') ?>" class="flex flex-col items-center justify-center p-6 bg-orange-600 rounded-xl text-white hover:bg-orange-700 transition-all group">
                    <i class="fas fa-user-plus text-3xl mb-3 group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold text-sm">User Baru</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Data Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Popular Posts -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 flex items-center">
                <i class="fas fa-fire mr-3 text-orange-500"></i>Terpopuler
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Judul Berita</th>
                        <th class="px-6 py-3 text-right">Views</th>
                    </tr>
                </thead>
                <tbody id="popular-posts-data" class="divide-y divide-slate-100">
                    <tr>
                        <td colspan="2" class="px-6 py-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
                            <p class="mt-4 text-slate-500 text-sm">Memuat data...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 flex items-center">
                <i class="fas fa-history mr-3 text-blue-600"></i>Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Judul Berita</th>
                        <th class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($recentPosts)) : ?>
                        <?php foreach ($recentPosts as $post) : ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-700 truncate max-w-xs"><?= esc($post['title']) ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500 whitespace-nowrap"><?= format_date($post['published_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="2" class="px-6 py-12 text-center text-slate-500">Belum ada berita.</td>
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
                    popularPostsData.innerHTML = '<tr><td colspan="2" class="px-6 py-8 text-center text-slate-500">Tidak ada data.</td></tr>';
                    return;
                }
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-slate-50 transition-colors';
                    row.innerHTML = `
                        <td class="px-6 py-4 text-sm font-medium text-slate-700 truncate max-w-xs">${item.title}</td>
                        <td class="px-6 py-4 text-sm text-orange-600 font-bold text-right">${item.views.toLocaleString()}</td>
                    `;
                    popularPostsData.appendChild(row);
                });
            })
            .catch(error => {
                popularPostsData.innerHTML = '<tr><td colspan="2" class="px-6 py-8 text-center text-red-500 font-medium italic">Gagal memuat data analitik.</td></tr>';
            });
    });
</script>

<?= $this->endSection() ?>