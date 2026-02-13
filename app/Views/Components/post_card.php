<article class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-slate-200 h-full">
    <!-- Image Container -->
    <div class="relative h-64 overflow-hidden bg-slate-100">
        <a href="<?= base_url('post/' . esc($post['slug'])) ?>" class="block h-full">
            <?php 
                $thumbPath = $post['thumbnail'] ?? '';
                $thumbSrc = filter_var($thumbPath, FILTER_VALIDATE_URL) ? $thumbPath : (!empty($thumbPath) ? base_url($thumbPath) : '');
            ?>
            <?php if (!empty($thumbSrc)) : ?>
                <img src="<?= $thumbSrc ?>" alt="<?= esc($post['title']) ?>" 
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-fw fa-image text-slate-300 text-6xl"></i>
                </div>
            <?php endif; ?>
        </a>

        <!-- Categories -->
        <?php if (!empty($post['categories'])) : ?>
            <div class="absolute top-6 left-6 flex flex-wrap gap-2">
                <?php foreach ($post['categories'] as $category) : ?>
                    <a href="<?= base_url('category/' . esc($category['slug'])) ?>" 
                        class="px-4 py-1.5 bg-blue-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg backdrop-blur-sm hover:bg-blue-950 transition-all shadow-xl">
                        <?= esc($category['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="p-10 flex flex-col flex-1">
        <h2 class="text-xl font-bold text-slate-900 mb-5 line-clamp-2 leading-tight group-hover:text-blue-900 transition-colors tracking-tight">
            <a href="<?= base_url('post/' . esc($post['slug'])) ?>">
                <?= esc($post['title']) ?>
            </a>
        </h2>
        
        <p class="text-slate-600 text-sm mb-10 line-clamp-3 leading-relaxed font-medium">
            <?= word_limiter(strip_tags($post['content']), 22) ?>
        </p>

        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">
            <span class="flex items-center">
                <i class="far fa-fw fa-calendar-alt mr-2 text-blue-900"></i>
                <?php
                    $dateField = $post['published_at'] ?: ($post['created_at'] ?: date('Y-m-d'));
                    echo format_date($dateField, 'date_only');
                ?>
            </span>
            <span class="flex items-center">
                <i class="far fa-fw fa-user mr-2 text-blue-900"></i>
                <?= esc($post['author_name'] ?? 'Admin') ?>
            </span>
        </div>
    </div>
</article>