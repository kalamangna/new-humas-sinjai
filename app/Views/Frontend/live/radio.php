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
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none uppercase">Radio</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center px-4 py-2 bg-red-50 rounded-full mb-6 border border-red-100">
            <span class="relative flex h-3 w-3 mr-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
            </span>
            <span class="text-[10px] font-black text-red-600 uppercase tracking-[0.3em]">Sedang Mengudara</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Suara Bersatu FM
        </h1>
        <p class="mt-4 text-slate-500 font-bold uppercase tracking-widest text-xs">95.5 MHz • Suara Rakyat Sinjai</p>
        <div class="mt-8 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <div class="max-w-3xl mx-auto">
        <!-- Player Card -->
        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden mb-12">
            <div class="p-8 md:p-16 text-center">
                <!-- Radio Visualizer Placeholder -->
                <div class="w-32 h-32 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-10 border-4 border-white shadow-xl shadow-blue-900/10">
                    <i class="fas fa-fw fa-tower-broadcast text-blue-800 text-4xl"></i>
                </div>

                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 mb-10">
                    <audio controls class="w-full">
                        <source src="<?= $stream_url ?>" type="audio/mpeg">
                        Browser anda tidak mendukung pemutar audio.
                    </audio>
                </div>

                <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-md mx-auto">
                    Dengarkan siaran berita, musik, dan informasi pembangunan daerah secara langsung melalui Radio Suara Bersatu FM Sinjai.
                </p>
            </div>

            <!-- Share Footer -->
            <div class="bg-slate-50 p-8 border-t border-slate-100 text-center">
                <div class="flex flex-wrap justify-center gap-3">
                    <?php $share_url = current_url(); ?>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode('Sedang mendengarkan Radio Suara Bersatu FM - ' . $share_url) ?>" target="_blank"
                        class="inline-flex items-center px-5 py-2.5 bg-[#25D366] text-white font-bold text-[10px] uppercase tracking-widest rounded-xl hover:opacity-90 transition-all">
                        <i class="fab fa-fw fa-whatsapp mr-2 text-sm"></i>Bagikan ke WA
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

<?= $this->endSection() ?>
