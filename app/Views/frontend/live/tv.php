<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Sinjai TV' => current_url()
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fa-solid fa-fw fa-house mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Sinjai TV</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-12">
        <p class="text-[11px] font-black text-red-600 uppercase tracking-[0.4em] mb-4">Siaran Langsung</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase mb-6"><?= esc($title) ?></h1>
        <div class="mt-8 w-24 h-2 bg-red-600 mx-auto rounded-full shadow-lg shadow-red-900/20"></div>
    </div>

    <div class="max-w-5xl mx-auto mb-10">
        <!-- Main Player -->
        <div class="bg-slate-900 rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative group ring-1 ring-white/10">
            <?php if ($is_live): ?>
                <div class="aspect-video w-full bg-black relative">
                    <!-- Facebook Live Embed -->
                    <iframe src="<?= esc($stream_url) ?>" 
                            class="absolute inset-0 w-full h-full" 
                            style="border:none;overflow:hidden" 
                            scrolling="no" 
                            frameborder="0" 
                            allowfullscreen="true" 
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" 
                            aria-label="Siaran Langsung Sinjai TV">
                    </iframe>
                </div>
            <?php else: ?>
                <div class="aspect-video w-full flex flex-col items-center justify-center bg-slate-950 p-12 text-center overflow-hidden">
                    <div class="relative mb-12">
                        <div class="absolute inset-0 bg-red-600 rounded-full blur-3xl opacity-10"></div>
                        <img src="<?= base_url('sinjaitv.png') ?>" class="relative w-48 md:w-64 h-auto opacity-40 group-hover:scale-105 transition-transform duration-700" alt="Sinjai TV Logo">
                    </div>
                    <h3 class="text-2xl font-black text-white tracking-tight mb-4 uppercase italic">Siaran Sedang Off-Air</h3>
                    <p class="text-slate-500 text-base max-w-sm font-medium tracking-tight leading-relaxed">Mohon kembali beberapa saat lagi saat jadwal siaran berlangsung.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>