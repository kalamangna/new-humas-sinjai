<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="flex justify-center my-12">
    <ul class="inline-flex items-center gap-1 md:gap-2">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" 
                    class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-blue-900 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angles-left text-[10px]"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" 
                    class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-blue-900 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-left text-xs"></i></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" 
                    class="flex items-center justify-center min-w-[2rem] h-8 md:min-w-[2.5rem] md:h-10 px-3 font-bold text-sm md:text-base transition-colors rounded-lg <?= $link['active'] ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-blue-900' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" 
                    class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-blue-900 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-right text-xs"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" 
                    class="flex items-center justify-center w-8 h-8 md:w-10 md:h-10 bg-white text-slate-500 border border-slate-200 rounded-lg hover:bg-slate-100 hover:text-blue-900 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angles-right text-[10px]"></i></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>