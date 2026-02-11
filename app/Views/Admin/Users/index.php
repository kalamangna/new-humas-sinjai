<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Pengguna<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/users/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fas fa-fw fa-plus-circle mr-2"></i>Tambah Pengguna
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Search Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
    <form action="<?= base_url('admin/users') ?>" method="get" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Cari nama pengguna..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-6 py-2 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Terapkan</button>
            <a href="<?= base_url('admin/users') ?>" class="px-6 py-2 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200">Reset</a>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-blue-800 p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Pengguna</p>
                <h3 class="text-3xl font-black mt-1"><?= $total_users ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-users text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-emerald-600 p-6 rounded-2xl shadow-lg shadow-emerald-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Aktif</p>
                <h3 class="text-3xl font-black mt-1"><?= $active_users ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-user-check text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-rose-600 p-6 rounded-2xl shadow-lg shadow-rose-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Admin</p>
                <h3 class="text-3xl font-black mt-1"><?= $admin_users ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-user-shield text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-amber-500 p-6 rounded-2xl shadow-lg shadow-amber-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Penulis</p>
                <h3 class="text-3xl font-black mt-1"><?= $author_users ?? '0' ?></h3>
            </div>
            <i class="fas fa-fw fa-user-edit text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Informasi Pengguna</th>
                    <th class="px-8 py-5">Hak Akses & Peran</th>
                    <th class="px-8 py-5">Produktivitas Konten</th>
                    <th class="px-8 py-5">Tanggal Terdaftar</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="users-data" class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center text-blue-800 font-black mr-4">
                                        <?= substr(esc($user['name']), 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 group-hover:text-blue-800 transition-colors tracking-tight"><?= esc($user['name']) ?></div>
                                        <div class="text-[10px] text-slate-400 font-bold tracking-tighter mt-0.5"><?= esc($user['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <?php
                                $roleClass = match ($user['role'] ?? 'user') {
                                    'admin' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'editor' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'author' => 'bg-sky-50 text-sky-600 border-sky-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100'
                                };
                                ?>
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border <?= $roleClass ?>">
                                    <?= ucfirst($user['role'] ?? 'user') ?>
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                    <?= $user['post_count'] ?? '0' ?> Berita
                                </span>
                            </td>
                            <td class="px-8 py-6 text-xs font-bold text-slate-500 whitespace-nowrap">
                                <?= format_date($user['created_at'] ?? 'now') ?>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap w-1">
                                <a href="<?= base_url('admin/users/' . $user['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-fw fa-edit text-xs"></i>
                                </a>
                                <?php if (($user['id'] ?? 0) !== ($current_user_id ?? 0)): ?>
                                    <form action="<?= base_url('admin/users/' . $user['id']) ?>" method="post" class="inline">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus akun <?= esc($user['name']) ?>?')">
                                            <i class="fas fa-fw fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fas fa-fw fa-user-slash text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada data pengguna</p>
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
            Total Data: <span class="text-slate-900"><?= number_format($pager->getTotal()) ?></span> Pengguna
        </div>
        <div>
            <?= $pager->links('default', 'custom_bootstrap') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>