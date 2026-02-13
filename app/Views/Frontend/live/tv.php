<?= $this->extend('Layouts/frontend') ?>

<?= $this->section('content') ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
            <li class="inline-flex items-center">
                <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 transition-colors">
                    <i class="fas fa-fw fa-home mr-2 text-blue-800"></i>Beranda
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none uppercase">Live Streaming</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-slate-300 text-[8px] mx-3"></i>
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none uppercase">Sinjai TV</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center px-4 py-2 <?= $active_stream ? 'bg-red-50 border-red-100' : 'bg-slate-50 border-slate-100' ?> rounded-full mb-6 border">
            <span class="relative flex h-3 w-3 mr-3">
                <?php if ($active_stream): ?>
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                <?php else: ?>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-slate-400"></span>
                <?php endif; ?>
            </span>
            <span class="text-[10px] font-black <?= $active_stream ? 'text-red-600' : 'text-slate-500' ?> uppercase tracking-[0.3em]">
                <?= $active_stream ? 'Siaran Langsung' : 'Sedang Offline' ?>
            </span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Sinjai TV
        </h1>
        <p class="mt-4 text-slate-500 font-bold uppercase tracking-widest text-xs">
            <?= $active_stream ? esc($active_stream['title']) : 'Saluran Informasi Pembangunan Daerah' ?>
        </p>
        <div class="mt-8 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <div class="max-w-5xl mx-auto">
        <!-- TV Card -->
        <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-200 overflow-hidden mb-12">
            <?php if ($active_stream): ?>
                <div class="aspect-video bg-black relative">
                    <!-- Facebook Live Embed -->
                    <iframe src="https://www.facebook.com/plugins/video.php?href=<?= urlencode($active_stream['live_url']) ?>&show_text=0&width=560" 
                        class="absolute inset-0 w-full h-full border-none"
                        style="border:none;overflow:hidden" 
                        scrolling="no" frameborder="0" 
                        allowfullscreen="true" 
                        allowtransparency="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share; unload">
                    </iframe>
                </div>
                <div class="bg-blue-50 p-4 border-b border-blue-100 flex justify-center">
                    <a href="<?= esc($active_stream['live_url']) ?>" target="_blank" class="text-[10px] font-black text-blue-800 uppercase tracking-widest flex items-center hover:underline">
                        <i class="fab fa-fw fa-facebook mr-2"></i>Video Terkendala? Lihat di Facebook
                    </a>
                </div>
            <?php else: ?>
                <div class="aspect-video bg-slate-900 flex flex-col items-center justify-center text-center p-8">
                    <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mb-6 border-4 border-slate-700">
                        <i class="fas fa-fw fa-tv text-slate-600 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white uppercase tracking-tight mb-2">Belum Ada Tayangan</h3>
                    <p class="text-slate-400 text-sm font-medium max-w-sm">
                        Saat ini tidak ada siaran langsung yang aktif. Silakan kembali lagi nanti untuk informasi program terbaru.
                    </p>
                </div>
            <?php endif; ?>

            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-center md:text-left">
                        <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-2">Informasi Program</h2>
                        <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-xl">
                            Saksikan berbagai program menarik mulai dari berita daerah, dialog interaktif, hingga dokumenter pembangunan Kabupaten Sinjai.
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap justify-center gap-3">
                        <?php $share_url = current_url(); ?>
                        <a href="https://api.whatsapp.com/send?text=<?= urlencode('Sedang menonton Sinjai TV - ' . $share_url) ?>" target="_blank"
                            class="inline-flex items-center px-5 py-2.5 bg-[#25D366] text-white font-bold text-[10px] uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
                            <i class="fab fa-fw fa-whatsapp mr-2 text-sm"></i>Bagikan WA
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($share_url) ?>" target="_blank"
                            class="inline-flex items-center px-5 py-2.5 bg-[#1877F2] text-white font-bold text-[10px] uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
                            <i class="fab fa-fw fa-facebook mr-2 text-sm"></i>Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>