<?= $this->extend('Layouts/admin') ?>

<?= $this->section('page_title') ?>Edit Informasi Berita<?= $this->endSection() ?>

<?= $this->section('page_actions') ?>
<a href="<?= base_url('admin/posts') ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 font-bold text-[10px] uppercase tracking-[0.2em] rounded-lg hover:bg-slate-200 transition-all border border-slate-200">
    <i class="fas fa-fw fa-arrow-left mr-2"></i>Kembali
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-8 py-6 bg-slate-50 border-b border-slate-200 flex items-center">
            <div class="w-10 h-10 bg-blue-800 text-white rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-fw fa-edit text-sm"></i>
            </div>
            <div>
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Editor Berita</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">ID: #<?= $post['id'] ?> • Terakhir diperbarui <?= date('d/m/y H:i', strtotime($post['updated_at'])) ?></p>
            </div>
        </div>

        <div class="p-8 md:p-12">
            <form action="<?= base_url('admin/posts/' . $post['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-10">
                <input type="hidden" name="_method" value="PUT">
                <?= csrf_field() ?>

                <!-- Title Section -->
                <div class="space-y-4">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Judul Berita <span class="text-red-600">*</span></label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-xl font-bold text-slate-900 placeholder-slate-300 focus:border-blue-800 focus:bg-white outline-none transition-all <?= (isset(session('errors')['title'])) ? 'border-red-500' : '' ?>"
                        value="<?= old('title', $post['title']) ?>" placeholder="Masukkan judul berita...">
                    <?php if (isset(session('errors')['title'])) : ?>
                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['title'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Content Section -->
                <div class="space-y-4">
                    <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Konten Informasi <span class="text-red-600">*</span></label>
                    <div class="<?= (isset(session('errors')['content'])) ? 'ring-2 ring-red-500 rounded-2xl overflow-hidden' : '' ?>">
                        <textarea name="content" id="content" rows="20" class="w-full"><?= old('content', $post['content']) ?></textarea>
                    </div>
                    <?php if (isset(session('errors')['content'])) : ?>
                        <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider"><?= session('errors')['content'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Media & Meta Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Media Upload -->
                    <div class="space-y-6">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Thumbnail Berita <span class="text-red-600">*</span></label>
                            <div class="flex items-center space-x-2">
                                <label class="flex-1 cursor-pointer">
                                    <div class="flex items-center px-4 py-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl hover:border-blue-800 hover:bg-slate-100 transition-all">
                                        <i class="fas fa-fw fa-image text-slate-400 mr-3"></i>
                                        <span class="text-sm font-bold text-slate-500 truncate" id="file-name"><?= $post['thumbnail'] ? 'Ganti Gambar Utama' : 'Pilih Gambar' ?></span>
                                        <input type="file" name="thumbnail" id="thumbnail" class="hidden" accept="image/*" onchange="previewImage(); document.getElementById('file-name').innerText = this.files[0].name;">
                                    </div>
                                </label>
                                <button type="button" id="paste-thumbnail-btn" class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-blue-800 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-fw fa-paste"></i>
                                </button>
                            </div>
                            <input type="hidden" name="pasted_thumbnail" id="pasted_thumbnail">
                            
                            <div id="thumbnail-preview-container" class="mt-4 ring-4 ring-slate-50 rounded-2xl overflow-hidden shadow-xl border border-slate-200 <?= empty($post['thumbnail']) ? 'hidden' : '' ?>">
                                <img id="thumbnail-preview" src="<?= !empty($post['thumbnail']) ? $post['thumbnail'] : '' ?>" class="w-full h-auto">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Keterangan Gambar</label>
                            <input type="text" name="thumbnail_caption" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-800 outline-none" value="<?= old('thumbnail_caption', $post['thumbnail_caption'] ?? '') ?>">
                        </div>

                        <div id="published_at_container" class="space-y-4" style="display: <?= old('status', $post['status']) === 'published' ? 'block' : 'none' ?>;">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Tanggal Publikasi</label>
                            <input type="datetime-local" name="published_at" id="published_at" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-800 outline-none" value="<?= old('published_at', $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '') ?>">
                        </div>
                    </div>

                    <!-- Category & Tags -->
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Kategori <span class="text-red-600">*</span></label>
                            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 h-48 overflow-y-auto space-y-4 scrollbar-thin">
                                <?php foreach ($categories as $category) : ?>
                                    <div>
                                        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2"><?= esc($category['name']) ?></h6>
                                        <?php if (!empty($category['children'])) : ?>
                                            <div class="grid grid-cols-1 gap-2">
                                                <?php foreach ($category['children'] as $child) : ?>
                                                    <label class="flex items-center group cursor-pointer">
                                                        <input type="checkbox" name="categories[]" value="<?= $child['id'] ?>" class="w-4 h-4 text-blue-800 rounded border-slate-300 focus:ring-blue-800" <?= in_array($child['id'], old('categories', $post_categories)) ? 'checked' : '' ?>>
                                                        <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-blue-800 transition-colors"><?= esc($child['name']) ?></span>
                                                    </label>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-slate-900 uppercase tracking-[0.2em]">Label / Tags <span class="text-red-600">*</span></label>
                            <?php 
                                $oldTags = old('tags');
                                $displayTags = !is_null($oldTags) ? (is_array($oldTags) ? $oldTags : explode(',', $oldTags)) : $post_tag_names;
                                $displayTags = array_filter(array_map('trim', $displayTags));
                            ?>
                            <div id="tag-container" class="bg-slate-50 border border-slate-200 rounded-2xl p-4 min-h-[100px] flex flex-wrap gap-2 text-[10px] font-black uppercase tracking-widest">
                                <?php foreach ($displayTags as $tagName) : ?>
                                    <div class="tag-badge inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg border border-blue-200" data-tag="<?= esc($tagName) ?>">
                                        <?= esc($tagName) ?>
                                        <button type="button" class="ml-2 hover:text-red-600 remove-tag"><i class="fas fa-fw fa-times-circle"></i></button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="tags" id="tags-input" value="<?= esc(implode(',', $displayTags)) ?>">
                            
                            <div class="flex gap-2">
                                <input type="text" id="manual-tag-input" class="flex-1 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-800" placeholder="Tambah tag manual...">
                                <button type="button" id="add-manual-tag-btn" class="p-2 bg-slate-800 text-white rounded-xl hover:bg-slate-950 transition-all"><i class="fas fa-fw fa-plus"></i></button>
                            </div>
                            <button type="button" id="suggest-tags-btn" class="w-full py-3 bg-blue-50 text-blue-800 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-blue-100 transition-all border border-blue-100">
                                <i class="fas fa-fw fa-wand-magic-sparkles mr-2 text-sm"></i>Analisa Ulang Tag (AI)
                            </button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="status" id="post-status" value="<?= old('status', $post['status']) ?>">

                <!-- Submit Section -->
                <div class="pt-10 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="<?= base_url('admin/posts') ?>" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all text-center">
                        Batal
                    </a>
                    <?php if ($post['status'] === 'published') : ?>
                        <button type="submit" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                            <i class="fas fa-fw fa-save mr-2 text-sm"></i>Simpan Perubahan
                        </button>
                    <?php else : ?>
                        <button type="submit" onclick="document.getElementById('post-status').value='draft'" class="px-8 py-4 bg-slate-100 text-slate-600 font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 transition-all">
                            Simpan Konsep
                        </button>
                        <button type="submit" onclick="document.getElementById('post-status').value='published'" class="px-10 py-4 bg-blue-800 text-white font-black text-[11px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                            Tayangkan Sekarang
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('Partials/admin_validation_script') ?>

<script>
    // Paste logic
    document.getElementById('paste-thumbnail-btn').addEventListener('click', async () => {
        try {
            const items = await navigator.clipboard.read();
            for (const item of items) {
                const types = item.types.filter(t => t.startsWith('image/'));
                if (types.length) {
                    const blob = await item.getType(types[0]);
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('pasted_thumbnail').value = event.target.result;
                        document.getElementById('thumbnail-preview').src = event.target.result;
                        document.getElementById('thumbnail-preview-container').classList.remove('hidden');
                        document.getElementById('file-name').innerText = "Gambar Clipboard";
                    };
                    reader.readAsDataURL(blob);
                }
            }
        } catch (e) {}
    });

    // Tag logic
    const tagContainer = document.getElementById('tag-container');
    const tagsInput = document.getElementById('tags-input');
    const manualTagInput = document.getElementById('manual-tag-input');
    
    function updateTagsInput() {
        const tags = Array.from(tagContainer.querySelectorAll('.tag-badge')).map(b => b.dataset.tag);
        tagsInput.value = tags.join(',');
    }

    function addTag(tag) {
        if (!tag || Array.from(tagContainer.querySelectorAll('.tag-badge')).some(b => b.dataset.tag.toLowerCase() === tag.toLowerCase())) return;
        const badge = document.createElement('div');
        badge.className = 'tag-badge inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg border border-blue-200';
        badge.dataset.tag = tag;
        badge.innerHTML = `${tag} <button type="button" class="ml-2 hover:text-red-600"><i class="fas fa-fw fa-times-circle"></i></button>`;
        badge.querySelector('button').onclick = () => { badge.remove(); updateTagsInput(); };
        tagContainer.appendChild(badge);
        updateTagsInput();
    }

    document.getElementById('add-manual-tag-btn').onclick = () => { addTag(manualTagInput.value.trim()); manualTagInput.value = ''; };
    document.querySelectorAll('.remove-tag').forEach(btn => btn.onclick = () => { btn.closest('.tag-badge').remove(); updateTagsInput(); });

    // AI Suggestion
    document.getElementById('suggest-tags-btn').onclick = function() {
        const title = document.getElementById('title').value;
        const content = tinymce.get('content').getContent();
        if(!title || !content) return alert('Isi data dahulu.');
        this.disabled = true; this.innerHTML = '<i class="fas fa-fw fa-circle-notch fa-spin mr-2"></i>Menganalisa...';
        fetch('<?= base_url('api/tags/suggest') ?>', {
            method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ '<?= csrf_token() ?>': '<?= csrf_hash() ?>', title, content })
        }).then(r => r.json()).then(data => data.forEach(addTag)).finally(() => { 
            this.disabled = false; this.innerHTML = '<i class="fas fa-fw fa-wand-magic-sparkles mr-2"></i>Analisa Ulang Tag (AI)'; 
        });
    };
</script>

<?= $this->endSection() ?>