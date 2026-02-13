<?= $this->extend('Layouts/frontend') ?>

<?= $this->section('content') ?>
<section class="py-12 md:py-20 bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            <!-- Breadcrumb -->
            <nav class="flex mb-10" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 text-[10px] font-black uppercase tracking-[0.3em]">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-blue-800 dark:hover:text-blue-400 transition-colors">
                            <i class="fas fa-fw fa-home mr-2 text-blue-800 dark:text-blue-500"></i>Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-fw fa-chevron-right text-slate-300 dark:text-slate-700 text-[8px] mx-3"></i>
                            <span class="text-slate-400 dark:text-slate-600 uppercase">Sinjai TV</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                <div>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-14 h-14 bg-blue-800 dark:bg-blue-700 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-900/20">
                            <i class="fas fa-tv text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tighter uppercase leading-none">Sinjai TV</h1>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mt-2">Saluran Informasi Pembangunan</p>
                        </div>
                    </div>
                </div>

                <?php if ($is_live): ?>
                    <div class="inline-flex items-center px-5 py-2.5 bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-900/50 rounded-full self-start md:self-end">
                        <span class="relative flex h-3 w-3 mr-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                        </span>
                        <span class="text-[10px] font-black text-red-700 dark:text-red-400 uppercase tracking-widest">Sedang Live</span>
                    </div>
                <?php else: ?>
                    <div class="inline-flex items-center px-5 py-2.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-full self-start md:self-end">
                        <span class="w-3 h-3 rounded-full bg-slate-400 dark:bg-slate-700 mr-3"></span>
                        <span class="text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest">Siaran Offline</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Player Container -->
            <div class="bg-white dark:bg-slate-900 rounded-[3rem] shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transition-all duration-500 ring-1 ring-black/5 dark:ring-white/5">
                <?php if ($is_live): ?>
                    <div class="aspect-video relative bg-black">
                        <iframe 
                            src="https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/facebook/videos/<?= $video_id ?>/&show_text=0&width=560" 
                            class="absolute inset-0 w-full h-full"
                            style="border:none;overflow:hidden" 
                            scrolling="no" frameborder="0" 
                            allowfullscreen="true" 
                            allowtransparency="true"
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share; unload">
                        </iframe>
                    </div>
                    <div class="bg-blue-50 dark:bg-blue-950/30 p-6 border-t border-blue-100 dark:border-blue-900/50 flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center text-white shrink-0">
                                <i class="fab fa-facebook-f"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-blue-900 dark:text-blue-300 uppercase tracking-widest">Sumber Siaran</p>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Official Facebook Humas Sinjai</p>
                            </div>
                        </div>
                        <a href="https://www.facebook.com/watch/?v=<?= $video_id ?>" target="_blank" 
                           class="px-6 py-3 bg-blue-800 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-blue-900 transition-all shadow-lg shadow-blue-900/20 flex items-center">
                            <i class="fas fa-external-link-alt mr-2"></i>Lihat di Facebook
                        </a>
                    </div>
                <?php else: ?>
                    <div class="aspect-video flex flex-col items-center justify-center text-center p-12 bg-slate-100 dark:bg-slate-900/50">
                        <div class="w-24 h-24 bg-white dark:bg-slate-800 rounded-3xl flex items-center justify-center mb-8 border border-slate-200 dark:border-slate-700 shadow-xl transform rotate-3">
                            <i class="fas fa-video-slash text-3xl text-slate-300 dark:text-slate-600"></i>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">SIARAN BERHENTI</h2>
                        <p class="text-slate-500 dark:text-slate-400 font-medium max-w-md mx-auto leading-relaxed text-sm">
                            Saat ini tidak ada siaran langsung yang aktif. Ikuti halaman media sosial kami untuk mendapatkan notifikasi jadwal tayangan terbaru.
                        </p>
                        <div class="mt-10 flex flex-wrap justify-center gap-4">
                            <a href="https://www.facebook.com/<?= env('facebook.page_id') ?>" target="_blank" 
                               class="px-8 py-4 bg-blue-800 text-white font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/20">
                                <i class="fab fa-facebook mr-2"></i>Ikuti Facebook
                            </a>
                            <a href="<?= base_url('posts') ?>" 
                               class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 font-black text-[10px] uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                                Baca Berita
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Share and Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-4 flex items-center">
                        <span class="w-8 h-1 bg-blue-800 mr-3 rounded-full"></span>
                        Deskripsi Saluran
                    </h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium leading-relaxed">
                        Sinjai TV merupakan inisiatif digital dari Humas Pemerintah Kabupaten Sinjai untuk menghadirkan transparansi publik dan desiminasi informasi pembangunan secara visual dan real-time. Kami menayangkan liputan langsung agenda pimpinan daerah, rapat strategis, hingga potensi wisata dan budaya lokal.
                    </p>
                </div>
                <div class="flex flex-col gap-4">
                    <h3 class="text-[10px] font-black text-slate-400 dark:text-slate-600 uppercase tracking-[0.3em] mb-2">Bagikan Siaran</h3>
                    <?php $share_url = current_url(); ?>
                    <a href="https://api.whatsapp.com/send?text=<?= urlencode('Saksikan Sinjai TV secara langsung: ' . $share_url) ?>" target="_blank"
                        class="flex items-center px-6 py-4 bg-[#25D366] text-white rounded-2xl hover:opacity-90 transition-all group">
                        <i class="fab fa-whatsapp text-2xl mr-4 group-hover:scale-110 transition-transform"></i>
                        <div class="text-left">
                            <p class="text-[10px] font-black uppercase tracking-widest leading-none">WhatsApp</p>
                            <p class="text-[9px] font-bold opacity-80 mt-1 uppercase">Kirim Pesan</p>
                        </div>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($share_url) ?>" target="_blank"
                        class="flex items-center px-6 py-4 bg-[#1877F2] text-white rounded-2xl hover:opacity-90 transition-all group">
                        <i class="fab fa-facebook-f text-2xl mr-4 group-hover:scale-110 transition-transform"></i>
                        <div class="text-left">
                            <p class="text-[10px] font-black uppercase tracking-widest leading-none">Facebook</p>
                            <p class="text-[9px] font-bold opacity-80 mt-1 uppercase">Bagikan di Feed</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
<?= $this->endSection() ?>
