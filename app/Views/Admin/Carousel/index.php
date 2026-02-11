<?= $this->extend('layout/admin') ?>

<?= $this->section('page_title') ?>Manajemen Slider<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/carousel/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
    <i class="fas fa-plus-circle mr-2"></i>Tambah Slide
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-8 py-5">Urutan</th>
                    <th class="px-8 py-5">Pratinjau Visual</th>
                    <th class="px-8 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($slides)): ?>
                    <?php foreach ($slides as $slide): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="w-8 h-8 bg-slate-100 text-slate-600 font-black rounded-lg flex items-center justify-center border border-slate-200"><?= esc($slide['slide_order']) ?></span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="w-48 h-24 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm">
                                    <img src="<?= esc($slide['image_path']) ?>" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right space-x-1 whitespace-nowrap">
                                <a href="<?= base_url('admin/carousel/' . $slide['id'] . '/edit') ?>" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('admin/carousel/' . $slide['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all" onclick="return confirm('Hapus slide ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-8 py-24 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200">
                                <i class="fas fa-images text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada slide tersedia</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>