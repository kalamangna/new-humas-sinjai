<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Breadcrumb -->
    <nav class="flex mb-10" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-bold uppercase tracking-widest">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-slate-300 text-[10px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[200px]"><?= esc($post['title'] ?? '') ?></span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Main Content -->
        <div class="lg:col-span-8">
            <article class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-12">
                <!-- Post Header -->
                <div class="p-8 md:p-12">
                    <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight mb-8 tracking-tight">
                        <?= esc($post['title']) ?>
                    </h1>

                    <!-- Post Meta -->
                    <div class="flex flex-wrap items-center gap-8 text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-10 pb-10 border-b border-slate-100">
                        <div class="flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-blue-700"></i>
                            <span>
                                <?php
                                $dateField = $post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d'));
                                echo format_date($dateField, 'date_only');
                                ?>
                            </span>
                        </div>

                        <?php if (!empty($post['author_name'])) : ?>
                            <div class="flex items-center">
                                <i class="far fa-user mr-2 text-blue-700"></i>
                                <span><?= esc($post['author_name']) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($post['categories'])) : ?>
                            <div class="flex items-center flex-wrap gap-2">
                                <i class="far fa-folder mr-2 text-blue-700"></i>
                                <?php foreach ($post['categories'] as $category) : ?>
                                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>" 
                                        class="px-3 py-1 bg-blue-50 text-blue-800 font-black rounded-md hover:bg-blue-800 hover:text-white transition-all">
                                        <?= esc($category['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($post['thumbnail'])) : ?>
                        <figure class="mb-12">
                            <img src="<?= esc($post['thumbnail']) ?>" class="w-full h-auto rounded-2xl shadow-2xl border border-slate-100" alt="<?= esc($post['title']) ?>">
                            <?php if (!empty($post['thumbnail_caption'])) : ?>
                                <figcaption class="mt-5 text-center text-xs text-slate-500 italic font-medium">
                                    <i class="fas fa-camera mr-2 opacity-50"></i><?= esc($post['thumbnail_caption']) ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="prose prose-slate lg:prose-xl max-w-none prose-headings:text-slate-900 prose-headings:font-black prose-a:text-blue-800 prose-a:font-bold prose-img:rounded-2xl">
                        <?= $post['content'] ?>
                    </div>

                    <!-- Share Section -->
                    <div class="mt-16 pt-10 border-t border-slate-100">
                        <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] mb-6 flex items-center">
                            <i class="fas fa-share-alt mr-3 text-blue-800"></i>Bagikan Informasi
                        </h3>
                        <div class="flex flex-wrap gap-4">
                            <?php $share_url = urlencode(current_url()); ?>
                            <a href="https://api.whatsapp.com/send?text=<?= $share_url ?>" target="_blank" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-900/10">
                                <i class="fab fa-whatsapp mr-2 text-base"></i>WhatsApp
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>" target="_blank" 
                                class="inline-flex items-center px-6 py-3 bg-blue-700 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/10">
                                <i class="fab fa-facebook mr-2 text-base"></i>Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= $share_url ?>" target="_blank" 
                                class="inline-flex items-center px-6 py-3 bg-slate-950 text-white font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-black transition-all shadow-lg">
                                <i class="fab fa-x-twitter mr-2 text-base"></i>Twitter
                            </a>
                            <button onclick="copyToClipboard(this)" 
                                class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all border border-slate-200">
                                <i class="fas fa-link mr-2"></i>Salin Tautan
                            </button>
                        </div>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($tags)) : ?>
                        <div class="mt-12 pt-10 border-t border-slate-100">
                            <div class="flex items-center flex-wrap gap-3">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mr-2">Topik Terkait:</span>
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?= base_url('tag/' . esc($tag['slug'])) ?>" 
                                        class="px-4 py-1.5 bg-slate-50 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200 hover:bg-blue-800 hover:text-white hover:border-blue-900 transition-all">
                                        #<?= esc($tag['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </article>

            <!-- Related Posts -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 md:p-12">
                <h3 class="text-lg font-black text-slate-900 uppercase tracking-widest mb-10 flex items-center">
                    <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Berita Terkait
                </h3>
                <?php if (!empty($related_posts)) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php foreach ($related_posts as $related) : ?>
                            <a href="<?= base_url('post/' . esc($related['slug'])) ?>" class="group block relative rounded-2xl overflow-hidden aspect-[16/10] border border-slate-200 shadow-sm">
                                <img src="<?= esc($related['thumbnail']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Related">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent p-6 flex flex-col justify-end">
                                    <h4 class="text-white font-bold leading-tight group-hover:text-sky-300 transition-colors tracking-tight"><?= esc($related['title']) ?></h4>
                                    <div class="mt-3 text-[10px] font-bold text-slate-300 uppercase tracking-widest flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-sky-400"></i>
                                        <?= format_date($related['published_at'] ?? $related['created_at'] ?? 'now', 'date_only') ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="text-slate-400 text-sm italic text-center py-6 border-2 border-dashed border-slate-100 rounded-2xl">Tidak ada informasi terkait lainnya.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-12">
            <!-- Recent Posts Widget -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                        <i class="fas fa-history mr-3 text-blue-800"></i>Berita Terbaru
                    </h3>
                </div>
                <div class="p-8 space-y-8">
                    <?php if (!empty($recent_posts)) : ?>
                        <?php foreach ($recent_posts as $recent) : ?>
                            <a href="<?= base_url('post/' . esc($recent['slug'])) ?>" class="group flex gap-5">
                                <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden shadow-md border border-slate-100">
                                    <img src="<?= esc($recent['thumbnail']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500" alt="Recent">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-blue-800 transition-colors leading-snug tracking-tight">
                                        <?= esc($recent['title']) ?>
                                    </h4>
                                    <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <?= format_date($recent['published_at'] ?? $recent['created_at'] ?? 'now', 'date_only') ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="text-[10px] text-slate-400 font-bold uppercase text-center">Data belum tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Popular Posts Widget -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em] flex items-center">
                        <i class="fas fa-fire mr-3 text-orange-600"></i>Paling Banyak Dibaca
                    </h3>
                </div>
                <div class="p-8 space-y-8">
                    <?php if (!empty($popular_posts)) : ?>
                        <?php foreach ($popular_posts as $popular) : ?>
                            <a href="<?= base_url('post/' . esc($popular['slug'])) ?>" class="group flex gap-5">
                                <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden shadow-md border border-slate-100">
                                    <img src="<?= esc($popular['thumbnail']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500" alt="Popular">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-bold text-slate-900 line-clamp-2 group-hover:text-blue-800 transition-colors leading-snug tracking-tight">
                                        <?= esc($popular['title']) ?>
                                    </h4>
                                    <div class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center justify-between">
                                        <span><?= format_date($popular['published_at'] ?? 'now', 'date_only') ?></span>
                                        <span class="flex items-center text-orange-600"><i class="far fa-eye mr-1"></i><?= number_format($popular['views']) ?></span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    function copyToClipboard(btn) {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check mr-2"></i>Tersalin!';
            btn.classList.replace('bg-slate-100', 'bg-emerald-600');
            btn.classList.add('text-white');
            btn.classList.remove('text-slate-700', 'border-slate-200');

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.replace('bg-emerald-600', 'bg-slate-100');
                btn.classList.remove('text-white');
                btn.classList.add('text-slate-700', 'border-slate-200');
            }, 3000);
        });
    }
</script>

<?= $this->endSection() ?>