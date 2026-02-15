<!-- Footer -->
<footer class="bg-slate-950 text-slate-400 mt-20 border-t-8 border-blue-900">
    <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 items-start">
            <!-- Info -->
            <div class="space-y-6 text-center sm:text-left">
                <img src="<?= base_url(get_setting('site_logo', 'humas.png')) ?>" alt="<?= esc(get_setting('site_name')) ?>" class="h-14 w-auto mx-auto sm:mx-0">
                <div class="space-y-3">
                    <p class="text-sm leading-relaxed font-medium"><?= esc(get_setting('site_tagline')) ?></p>
                    <div class="inline-flex items-center px-3 py-1 bg-blue-900/50 text-sky-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-full border border-blue-800">
                        #samasamaki
                    </div>
                </div>
                <div class="flex justify-center sm:justify-start space-x-6">
                    <?php if ($fb = get_setting('social_facebook')): ?>
                        <a href="<?= esc($fb) ?>" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-facebook-f text-xl"></i></a>
                    <?php endif; ?>
                    <?php if ($ig = get_setting('social_instagram')): ?>
                        <a href="<?= esc($ig) ?>" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-instagram text-xl"></i></a>
                    <?php endif; ?>
                    <?php if ($yt = get_setting('social_youtube')): ?>
                        <a href="<?= esc($yt) ?>" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-youtube text-xl"></i></a>
                    <?php endif; ?>
                    <?php if ($tk = get_setting('social_tiktok')): ?>
                        <a href="<?= esc($tk) ?>" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-tiktok text-xl"></i></a>
                    <?php endif; ?>
                    <?php if ($tw = get_setting('social_twitter')): ?>
                        <a href="<?= esc($tw) ?>" target="_blank" class="hover:text-sky-400 transition-all hover:scale-110"><i class="fab fa-fw fa-x-twitter text-xl"></i></a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Links -->
            <div class="text-center sm:text-left">
                <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                    <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Navigasi
                </h3>
                <ul class="space-y-4 text-xs font-bold uppercase tracking-widest">
                    <li><a href="<?= base_url('/') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Beranda</a></li>
                    <li><a href="<?= base_url('about') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Tentang Kami</a></li>
                    <li><a href="<?= base_url('contact') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Hubungi Kami</a></li>
                    <li><a href="<?= base_url('widget') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>Panduan Widget</a></li>
                    <li><a href="<?= base_url('rss') ?>" class="hover:text-sky-400 flex items-center justify-center sm:justify-start transition-colors"><i class="fas fa-fw fa-chevron-right text-[8px] mr-3 opacity-30 text-blue-500"></i>RSS Feed</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="text-center sm:text-left">
                <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                    <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Kontak
                </h3>
                <ul class="space-y-6 text-sm font-medium">
                    <li class="flex items-center justify-center sm:justify-start">
                        <div class="bg-blue-900/50 p-2 rounded-lg mr-4 mt-1 flex-shrink-0"><i class="fas fa-fw fa-map-marker-alt text-sky-500"></i></div>
                        <span class="leading-relaxed text-xs"><?= esc(get_setting('contact_address')) ?></span>
                    </li>
                    <li class="flex items-center justify-center sm:justify-start">
                        <div class="bg-blue-900/50 p-2 rounded-lg mr-4 flex-shrink-0"><i class="fas fa-fw fa-envelope text-sky-500"></i></div>
                        <span class="text-xs"><?= esc(get_setting('contact_email')) ?></span>
                    </li>
                </ul>
            </div>

            <!-- Lapor -->
            <div class="text-center sm:text-left">
                <h3 class="text-white font-black text-xs uppercase tracking-[0.3em] mb-10 flex items-center justify-center sm:justify-start">
                    <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Pengaduan
                </h3>
                <a href="https://lapor.go.id/" target="_blank" class="inline-block bg-white p-4 rounded-2xl shadow-2xl hover:shadow-blue-900/20 transition-all hover:-translate-y-1">
                    <img src="<?= base_url('lapor.png') ?>" alt="Lapor" class="h-14 w-auto">
                </a>
                <p class="mt-6 text-[10px] font-black text-blue-500 uppercase tracking-widest italic">Sampaikan Aspirasi Anda</p>
            </div>
        </div>

        <div class="border-t border-slate-900 mt-20 pt-10 flex flex-col md:flex-row justify-between items-center text-[10px] tracking-[0.2em] uppercase font-black">
            <p class="text-slate-600">&copy; <?= date('Y') ?> <?= esc(get_setting('site_name')) ?></p>
            <p class="text-slate-600 mt-4 md:mt-0 text-[8px]">Dikembangkan oleh <?= esc(get_setting('developer_name')) ?></p>
        </div>
    </div>
</footer>