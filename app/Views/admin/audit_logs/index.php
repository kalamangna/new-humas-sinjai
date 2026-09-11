<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Audit Log<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Overview -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-blue-800 p-5 sm:p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Log</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($stats['total'] ?? 0) ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-clock-rotate-left text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-emerald-600 p-5 sm:p-6 rounded-2xl shadow-lg shadow-emerald-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Hari Ini</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($stats['today'] ?? 0) ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-calendar-day text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-amber-500 p-5 sm:p-6 rounded-2xl shadow-lg shadow-amber-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Autentikasi</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($stats['auth'] ?? 0) ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-shield-halved text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-indigo-600 p-5 sm:p-6 rounded-2xl shadow-lg shadow-indigo-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Aktivitas Konten</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($stats['activities'] ?? 0) ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-pen-to-square text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
    <form action="<?= base_url('admin/audit-logs') ?>" method="get" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div class="lg:col-span-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari</label>
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Cari aktivitas, nama user, atau IP..." value="<?= esc($filters['search'] ?? '') ?>">
            </div>
        </div>

        <!-- Modul -->
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Modul</label>
            <select name="module" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Modul</option>
                <?php foreach ($modules as $modKey => $modLabel) : ?>
                    <option value="<?= $modKey ?>" <?= ($filters['module'] ?? '') === $modKey ? 'selected' : '' ?>><?= $modLabel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Aksi -->
        <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aksi</label>
            <select name="action" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <option value="">Semua Aksi</option>
                <?php foreach ($actions as $actKey => $actLabel) : ?>
                    <option value="<?= $actKey ?>" <?= ($filters['action'] ?? '') === $actKey ? 'selected' : '' ?>><?= $actLabel ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tanggal & Tombol Aksi -->
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all text-center">
                Filter
            </button>
            <a href="<?= base_url('admin/audit-logs') ?>" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Waktu</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Pelaku</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Modul</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Aksi</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Keterangan</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">IP & Info</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($logs)) : ?>
                    <?php foreach ($logs as $log) : ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <!-- Waktu -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs text-slate-500 font-semibold">
                                <?= format_date($log['created_at'], 'short') ?>
                            </td>

                            <!-- User / Pelaku -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900 text-sm">
                                    <?= esc($log['user_name'] ?? 'Sistem') ?>
                                </div>
                                <?php if (!empty($log['user_role'])) : ?>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
                                        <?= $log['user_role'] === 'admin' ? 'Admin' : 'Penulis' ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Modul -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <?php
                                $modClass = match ($log['module']) {
                                    'auth'       => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'posts'      => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'categories' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'tags'       => 'bg-teal-50 text-teal-700 border-teal-200',
                                    'profiles'   => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                                    'carousel'   => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
                                    'users'      => 'bg-orange-50 text-orange-700 border-orange-200',
                                    default      => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                                $modLabel = $modules[$log['module']] ?? ucfirst($log['module']);
                                ?>
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wide border <?= $modClass ?>">
                                    <?= esc($modLabel) ?>
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <?php
                                $actClass = match ($log['action']) {
                                    'login'        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'logout'       => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'login_failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'create'       => 'bg-sky-50 text-sky-700 border-sky-200',
                                    'update'       => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'delete'       => 'bg-red-50 text-red-700 border-red-200',
                                    default        => 'bg-slate-50 text-slate-700 border-slate-200',
                                };
                                $actLabel = $actions[$log['action']] ?? ucfirst($log['action']);
                                ?>
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider border <?= $actClass ?>">
                                    <?= esc($actLabel) ?>
                                </span>
                            </td>

                            <!-- Keterangan -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-800">
                                <?= esc($log['description']) ?>
                            </td>

                            <!-- IP & Info Perangkat -->
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs text-slate-500 font-mono">
                                <div><?= esc($log['ip_address'] ?? '-') ?></div>
                                <?php if (!empty($log['user_agent'])) : ?>
                                    <div class="text-[10px] text-slate-400 truncate max-w-xs font-sans" title="<?= esc($log['user_agent']) ?>">
                                        <?= esc($log['user_agent']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fa-solid fa-fw fa-clock-rotate-left text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Belum ada riwayat aktivitas</p>
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
            Total: <span class="text-slate-900"><?= number_format($pager->getTotal()) ?></span> Catatan Log
        </div>
        <div>
            <?= $pager->links('default', 'custom_pager') ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
