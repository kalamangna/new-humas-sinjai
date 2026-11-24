<?= $this->extend('layouts/admin') ?>

<?= $this->section('page_title') ?>Kelola Tag<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<div class="flex flex-wrap items-center gap-2">
    <button type="button" id="detect-ai-duplicates-btn" class="inline-flex items-center h-10 px-4 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all shadow-sm">
        <i class="fa-solid fa-fw fa-wand-magic-sparkles mr-2"></i>Deteksi Duplikat AI
    </button>
    <a href="<?= base_url('admin/tags/new') ?>" class="inline-flex items-center h-10 px-4 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20">
        <i class="fa-solid fa-fw fa-circle-plus mr-2"></i>Tambah Tag
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Search Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 mb-6 sm:mb-8">
    <form action="<?= base_url('admin/tags') ?>" method="get" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="sm:col-span-2 lg:col-span-3">
            <label for="tag-search" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Cari</label>
            <input id="tag-search" type="text" name="search" class="w-full h-10 px-4 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none" placeholder="Infrastruktur" value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div class="flex flex-col justify-end">
            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center h-10 px-4 bg-slate-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-900 transition-all">Filter</button>
                <a href="<?= base_url('admin/tags') ?>" class="inline-flex items-center justify-center h-10 px-4 bg-slate-100 text-slate-600 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200 text-center">Reset</a>
            </div>
        </div>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-blue-800 p-5 sm:p-6 rounded-2xl shadow-lg shadow-blue-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($total_tags ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-tags text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
    <div class="bg-sky-600 p-5 sm:p-6 rounded-2xl shadow-lg shadow-sky-900/20 text-white">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest opacity-70">Total Berita</p>
                <h3 class="text-2xl sm:text-3xl font-black mt-1"><?= number_format($total_posts ?? 0, 0, ',', '.') ?></h3>
            </div>
            <i class="fa-solid fa-fw fa-newspaper text-2xl sm:text-3xl opacity-30"></i>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Nama</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Slug</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4">Total Berita</th>
                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="tags-data" class="divide-y divide-slate-100 whitespace-nowrap">
                <?php if (!empty($tags)): ?>
                    <?php foreach ($tags as $tag): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-50 text-blue-800 rounded-xl flex items-center justify-center mr-3 sm:mr-4 group-hover:bg-blue-800 group-hover:text-white transition-all flex-shrink-0">
                                        <i class="fa-solid fa-fw fa-tag text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 tracking-tight leading-tight"><?= esc($tag['name']) ?></div>
                                        <?php if (!empty($tag['description'])) : ?>
                                            <div class="text-[10px] text-slate-400 font-medium italic mt-0.5 truncate max-w-xs"><?= esc($tag['description']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-500 font-mono text-[10px] rounded-md border border-slate-200">/<?= esc($tag['slug']) ?></span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-800 text-[10px] font-black rounded-lg border border-blue-100">
                                    <?= number_format($tag['post_count'] ?? 0, 0, ',', '.') ?> Berita
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-right space-x-1 whitespace-nowrap w-1">
                                <button type="button"
                                    data-tag-id="<?= (int) $tag['id'] ?>"
                                    data-tag-name="<?= esc($tag['name'], 'attr') ?>"
                                    onclick="openMergeModal(this)"
                                    aria-label="Gabungkan tag"
                                    title="Gabungkan tag"
                                    class="inline-flex items-center p-2 bg-slate-100 text-amber-700 rounded-lg hover:bg-amber-600 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-fw fa-code-merge"></i>
                                </button>
                                <a href="<?= base_url('admin/tags/' . $tag['id'] . '/edit') ?>" aria-label="Ubah tag" title="Ubah tag" class="inline-flex items-center p-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fa-solid fa-fw fa-pen-to-square"></i>
                                </a>
                                <form action="<?= base_url('admin/tags/' . $tag['id']) ?>" method="post" class="inline">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <?= csrf_field() ?>
                                    <button type="submit" aria-label="Hapus tag" title="Hapus tag" class="p-2 bg-slate-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Hapus tag ini? Tag yang dihapus akan dilepaskan dari berita terkait.')">
                                        <i class="fa-solid fa-fw fa-trash"></i>
                                    </button>
                                </form>
                                <?php if (($tag['post_count'] ?? 0) > 0): ?>
                                    <a href="<?= base_url('tag/' . esc($tag['slug'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="Lihat halaman tag" title="Lihat halaman tag" class="inline-flex items-center p-2 bg-slate-100 text-sky-600 rounded-lg hover:bg-sky-600 hover:text-white transition-all shadow-sm">
                                        <i class="fa-solid fa-fw fa-up-right-from-square"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="fa-solid fa-fw fa-tags text-2xl"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Belum ada tag</p>
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
            Total: <span class="text-slate-900"><?= number_format($pager->getTotal(), 0, ',', '.') ?></span> Tag
        </div>
        <div>
            <?= $pager->links('default', 'custom_pager') ?>
        </div>
    </div>
<?php endif; ?>

<!-- Merge Tag Modal -->
<div id="merge-tag-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" role="dialog" aria-modal="true" aria-labelledby="merge-modal-title">
    <div class="w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 id="merge-modal-title" class="text-sm font-black uppercase tracking-widest text-slate-900">Gabungkan Tag</h3>
            <button type="button" onclick="closeMergeModal()" aria-label="Tutup modal" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                <i class="fa-solid fa-fw fa-xmark"></i>
            </button>
        </div>
        <form id="merge-tag-form" action="<?= base_url('admin/tags/merge') ?>" method="post" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="source_id" id="merge-source-id">
            <input type="hidden" name="target_id" id="merge-target-id">

            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Tag Sumber yang Akan Dihapus</p>
                <p id="merge-source-name" class="text-base font-bold text-slate-900"></p>
                <p class="text-xs text-slate-500 mt-1">Semua berita pada tag sumber akan dipindahkan ke tag tujuan.</p>
            </div>

            <div>
                <label for="merge-target-search" class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Cari Tag Tujuan</label>
                <input id="merge-target-search" type="text" autocomplete="off" placeholder="Bupati Sinjai" class="w-full h-10 px-4 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-800 outline-none">
                <p id="merge-selected-label" class="hidden mt-2 text-xs font-bold text-blue-800"></p>
                <div id="merge-target-results" class="mt-2 max-h-52 overflow-y-auto divide-y divide-slate-100 border border-slate-200 rounded-lg bg-white"></div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeMergeModal()" class="inline-flex items-center justify-center h-10 px-4 bg-slate-100 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200">Batal</button>
                <button type="submit" id="merge-submit-btn" disabled class="inline-flex items-center justify-center h-10 px-5 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Gabungkan Tag</button>
            </div>
        </form>
    </div>
</div>

<!-- AI Duplicate Detection Modal -->
<div id="ai-duplicate-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" role="dialog" aria-modal="true" aria-labelledby="ai-modal-title">
    <div class="w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 id="ai-modal-title" class="text-sm font-black uppercase tracking-widest text-slate-900">Rekomendasi Gabung Tag AI</h3>
            <button type="button" id="ai-modal-close-btn" aria-label="Tutup modal" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-slate-500 hover:bg-slate-200 hover:text-slate-800 transition-colors">
                <i class="fa-solid fa-fw fa-xmark"></i>
            </button>
        </div>

        <div id="ai-duplicate-loading" class="p-6 space-y-3">
            <div class="h-12 bg-slate-100 rounded-xl animate-pulse"></div>
            <div class="h-12 bg-slate-100 rounded-xl animate-pulse"></div>
            <div class="h-12 bg-slate-100 rounded-xl animate-pulse"></div>
        </div>

        <div id="ai-duplicate-empty" class="hidden p-8 text-center">
            <p class="text-sm font-bold text-slate-700">Tidak ditemukan tag duplikat yang perlu digabungkan.</p>
        </div>

        <div id="ai-duplicate-error" class="hidden p-6 text-center">
            <p id="ai-duplicate-error-text" class="text-sm font-bold text-red-600">Gagal memindai duplikat tag menggunakan AI.</p>
        </div>

        <form id="ai-merge-batch-form" action="<?= base_url('admin/tags/merge-batch') ?>" method="post" class="hidden">
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="ai-merge-csrf">
            <div id="ai-duplicate-list" class="max-h-96 overflow-y-auto divide-y divide-slate-100 px-6"></div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                <button type="button" id="ai-modal-cancel-btn" class="inline-flex items-center justify-center h-10 px-4 bg-slate-100 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-slate-200 transition-all border border-slate-200">Batal</button>
                <button type="submit" id="ai-merge-submit-btn" class="inline-flex items-center justify-center h-10 px-5 bg-blue-800 text-white font-bold text-xs uppercase tracking-widest rounded-lg hover:bg-blue-900 transition-all disabled:opacity-50 disabled:cursor-not-allowed">Gabungkan Terpilih</button>
            </div>
        </form>
    </div>
</div>

<script>
(() => {
    const modal = document.getElementById('merge-tag-modal');
    const form = document.getElementById('merge-tag-form');
    const sourceIdInput = document.getElementById('merge-source-id');
    const targetIdInput = document.getElementById('merge-target-id');
    const sourceNameEl = document.getElementById('merge-source-name');
    const searchInput = document.getElementById('merge-target-search');
    const resultsEl = document.getElementById('merge-target-results');
    const selectedLabel = document.getElementById('merge-selected-label');
    const submitBtn = document.getElementById('merge-submit-btn');
    const searchEndpoint = '<?= base_url('admin/tags/search') ?>';
    let debounceTimer = null;

    function resetSelection() {
        targetIdInput.value = '';
        selectedLabel.textContent = '';
        selectedLabel.classList.add('hidden');
        submitBtn.disabled = true;
    }

    function renderResults(items) {
        resultsEl.innerHTML = '';
        if (!Array.isArray(items) || items.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'px-4 py-3 text-xs text-slate-500';
            empty.textContent = 'Tag tujuan tidak ditemukan';
            resultsEl.appendChild(empty);
            return;
        }

        items.forEach((item) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full px-4 py-2.5 text-left flex items-center justify-between hover:bg-blue-50 transition-colors';

            const nameSpan = document.createElement('span');
            nameSpan.className = 'text-sm font-semibold text-slate-800';
            nameSpan.textContent = item.name;

            const countSpan = document.createElement('span');
            countSpan.className = 'px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-black rounded-md';
            countSpan.textContent = `${item.post_count || 0} Berita`;

            btn.appendChild(nameSpan);
            btn.appendChild(countSpan);
            btn.addEventListener('click', () => {
                targetIdInput.value = item.id;
                selectedLabel.textContent = `Tujuan terpilih: ${item.name}`;
                selectedLabel.classList.remove('hidden');
                submitBtn.disabled = false;
            });
            resultsEl.appendChild(btn);
        });
    }

    async function fetchTargets(query) {
        const exclude = sourceIdInput.value;
        const url = `${searchEndpoint}?q=${encodeURIComponent(query)}&exclude=${encodeURIComponent(exclude)}`;
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) return;
            const data = await response.json();
            renderResults(data);
        } catch (_) {
            resultsEl.innerHTML = '<div class="px-4 py-3 text-xs text-red-600">Gagal memuat daftar tag</div>';
        }
    }

    window.openMergeModal = function (trigger) {
        sourceIdInput.value = trigger.dataset.tagId || '';
        sourceNameEl.textContent = trigger.dataset.tagName || '';
        searchInput.value = '';
        resetSelection();
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        fetchTargets('');
        searchInput.focus();
    };

    window.closeMergeModal = function () {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        submitBtn.textContent = 'Gabungkan Tag';
    };

    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchTargets(searchInput.value.trim()), 250);
    });

    form.addEventListener('submit', () => {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menggabungkan...';
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeMergeModal();
        }
    });

    const aiBtn = document.getElementById('detect-ai-duplicates-btn');
    const aiModal = document.getElementById('ai-duplicate-modal');
    const aiCloseBtn = document.getElementById('ai-modal-close-btn');
    const aiCancelBtn = document.getElementById('ai-modal-cancel-btn');
    const aiLoading = document.getElementById('ai-duplicate-loading');
    const aiEmpty = document.getElementById('ai-duplicate-empty');
    const aiError = document.getElementById('ai-duplicate-error');
    const aiErrorText = document.getElementById('ai-duplicate-error-text');
    const aiForm = document.getElementById('ai-merge-batch-form');
    const aiList = document.getElementById('ai-duplicate-list');
    const aiCsrfInput = document.getElementById('ai-merge-csrf');
    const aiSubmitBtn = document.getElementById('ai-merge-submit-btn');
    const detectEndpoint = '<?= base_url('admin/tags/detect-duplicates') ?>';
    const csrfHeaderName = '<?= csrf_header() ?>';

    function closeAiModal() {
        aiModal.classList.add('hidden');
        aiModal.classList.remove('flex');
        aiSubmitBtn.textContent = 'Gabungkan Terpilih';
    }

    function updateAiSubmitState() {
        const checked = aiList.querySelectorAll('input[type="checkbox"]:checked').length;
        aiSubmitBtn.disabled = checked === 0;
    }

    function renderAiSuggestions(suggestions) {
        aiList.innerHTML = '';
        if (!Array.isArray(suggestions) || suggestions.length === 0) {
            aiEmpty.classList.remove('hidden');
            return;
        }

        suggestions.forEach((item) => {
            const row = document.createElement('label');
            row.className = 'flex items-start gap-3 py-3.5 cursor-pointer hover:bg-slate-50 transition-colors';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'pairs[]';
            checkbox.value = `${item.source_id}:${item.target_id}`;
            checkbox.checked = true;
            checkbox.className = 'mt-1 h-4 w-4 rounded border-slate-300 text-blue-800 focus:ring-blue-800';
            checkbox.addEventListener('change', updateAiSubmitState);

            const content = document.createElement('div');
            content.className = 'min-w-0 flex-1';

            const title = document.createElement('div');
            title.className = 'text-sm font-bold text-slate-900 flex flex-wrap items-center gap-2';

            const srcSpan = document.createElement('span');
            srcSpan.className = 'text-red-700';
            srcSpan.textContent = `${item.source_name} · ${item.source_count || 0} Berita`;

            const arrow = document.createElement('i');
            arrow.className = 'fa-solid fa-fw fa-arrow-right text-slate-400 text-xs';

            const dstSpan = document.createElement('span');
            dstSpan.className = 'text-blue-800';
            dstSpan.textContent = `${item.target_name} · ${item.target_count || 0} Berita`;

            title.appendChild(srcSpan);
            title.appendChild(arrow);
            title.appendChild(dstSpan);

            const reason = document.createElement('p');
            reason.className = 'text-xs text-slate-500 mt-1';
            reason.textContent = item.reason || '';

            content.appendChild(title);
            content.appendChild(reason);
            row.appendChild(checkbox);
            row.appendChild(content);
            aiList.appendChild(row);
        });

        aiForm.classList.remove('hidden');
        updateAiSubmitState();
    }

    aiBtn.addEventListener('click', async () => {
        aiBtn.disabled = true;
        aiLoading.classList.remove('hidden');
        aiEmpty.classList.add('hidden');
        aiError.classList.add('hidden');
        aiForm.classList.add('hidden');
        aiModal.classList.remove('hidden');
        aiModal.classList.add('flex');

        try {
            const response = await fetch(detectEndpoint, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeaderName]: aiCsrfInput.value,
                },
            });
            const data = await response.json();
            if (data.csrf_hash) {
                aiCsrfInput.value = data.csrf_hash;
            }
            if (!response.ok) {
                throw new Error(data.error || 'Gagal memindai duplikat tag.');
            }
            renderAiSuggestions(data.suggestions || []);
        } catch (err) {
            aiErrorText.textContent = err.message || 'Gagal memindai duplikat tag menggunakan AI.';
            aiError.classList.remove('hidden');
        } finally {
            aiLoading.classList.add('hidden');
            aiBtn.disabled = false;
        }
    });

    aiCloseBtn.addEventListener('click', closeAiModal);
    aiCancelBtn.addEventListener('click', closeAiModal);
    aiModal.addEventListener('click', (event) => {
        if (event.target === aiModal) {
            closeAiModal();
        }
    });
    aiForm.addEventListener('submit', () => {
        aiSubmitBtn.disabled = true;
        aiSubmitBtn.textContent = 'Menggabungkan...';
    });
})();
</script>

<?= $this->endSection() ?>