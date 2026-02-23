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
        <div class="inline-flex items-center px-4 py-1.5 bg-slate-50 border-slate-100 rounded-full mb-6 border">
            <span class="relative flex h-2 w-2 mr-2.5">
                <span class="relative inline-flex rounded-full h-2 w-2 bg-slate-400"></span>
            </span>
            <span class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">
                Off-Air
            </span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase mb-6"><?= esc($title) ?></h1>
        <div class="mt-8 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <div class="max-w-5xl mx-auto mb-10 px-2 sm:px-0">
        <!-- Main Player -->
        <div class="bg-slate-900 rounded-3xl sm:rounded-[3rem] shadow-2xl border border-slate-800 overflow-hidden relative group ring-1 ring-white/10">

            <!-- Live Section Wrapper -->
            <div id="live-section" class="aspect-video w-full bg-black relative">
                <!-- Facebook Live Embed -->
                <iframe id="facebook-live-iframe" src="<?= esc($stream_url) ?>"
                        class="absolute inset-0 w-full h-full"
                        style="border:none;overflow:hidden"
                        scrolling="no"
                        frameborder="0"
                        allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                        aria-label="Siaran Langsung Sinjai TV"
                        tabindex="0">
                </iframe>

                <!-- Overlay -->
                <div id="live-overlay" class="absolute inset-0 bg-black bg-opacity-70 flex flex-col items-center justify-center transition-opacity duration-500 ease-in-out opacity-100 <?= $is_live ? 'hidden opacity-0' : '' ?>">
                    <span class="text-white text-2xl md:text-4xl font-bold uppercase tracking-wide">OFF AIR</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- No more specific JavaScript for overlay or button control -->
<?= $this->endSection() ?>