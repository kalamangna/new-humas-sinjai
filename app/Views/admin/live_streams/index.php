<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Live Streaming<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<?php if (($count ?? 0) === 0): ?>
    <a href="<?= base_url('admin/live-streams/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
        <i class="fas fa-fw fa-plus-circle mr-2"></i>Tambah Live Stream
    </a>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Judul</th>
                    <th class="px-8 py-5">Facebook URL</th>
                    <th class="px-8 py-5">Status</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($streams)): ?>
                    <?php foreach ($streams as $stream): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="font-bold text-slate-700"><?= esc($stream['title']) ?></span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-xs text-slate-500 max-w-xs truncate font-medium">
                                    <a href="<?= esc($stream['live_url']) ?>" target="_blank" class="hover:text-blue-800 transition-colors underline">
                                        <?= esc($stream['live_url']) ?>
                                    </a>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <?php if ($stream['is_active']): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-800 border border-red-200">
                                        <span class="w-1.5 h-1.5 bg-red-600 rounded-full mr-1.5 animate-pulse"></span>
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600 border border-slate-200">
                                        Offline
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap w-1">
                                <?php if (!$stream['is_active']): ?>
                                    <form action="<?= base_url('admin/live-streams/set-active/' . $stream['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center p-2 bg-slate-100 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Aktifkan">
                                            <i class="fas fa-fw fa-play"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= base_url('admin/live-streams/deactivate/' . $stream['id']) ?>" method="post" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="inline-flex items-center p-2 bg-slate-100 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition-all shadow-sm" title="Nonaktifkan">
                                            <i class="fas fa-fw fa-stop"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/live-streams/edit/' . $stream['id']) ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm" title="Edit">
                                    <i class="fas fa-fw fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/live-streams/' . $stream['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus live stream ini?')" title="Hapus">
                                        <i class="fas fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-8 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fas fa-fw fa-tv text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada live stream tersedia</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
