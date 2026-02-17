<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation" class="flex justify-center my-12">
    <ul class="inline-flex items-center -space-x-px">
        <?php if ($pager->hasPrevious()) : ?>
            <li>
                <a href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" 
                    class="block px-4 py-2 ml-0 leading-tight text-slate-500 bg-white border border-slate-300 rounded-l-lg hover:bg-slate-50 hover:text-blue-800 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-double-left text-[10px]"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" 
                    class="block px-4 py-2 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-50 hover:text-blue-800 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-left text-[10px]"></i></span>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li>
                <a href="<?= $link['uri'] ?>" 
                    class="px-5 py-2 leading-tight border border-slate-300 font-bold transition-all <?= $link['active'] ? 'z-10 bg-blue-800 border-blue-900 text-white shadow-md' : 'bg-white text-slate-600 hover:bg-slate-50 hover:text-blue-800' ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li>
                <a href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" 
                    class="block px-4 py-2 leading-tight text-slate-500 bg-white border border-slate-300 hover:bg-slate-50 hover:text-blue-800 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-right text-[10px]"></i></span>
                </a>
            </li>
            <li>
                <a href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" 
                    class="block px-4 py-2 leading-tight text-slate-500 bg-white border border-slate-300 rounded-r-lg hover:bg-slate-50 hover:text-blue-800 transition-colors">
                    <span aria-hidden="true"><i class="fa-solid fa-fw fa-angle-double-right text-[10px]"></i></span>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>