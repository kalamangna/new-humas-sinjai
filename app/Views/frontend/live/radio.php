<?= $this->extend('layouts/frontend') ?>

<?= $this->section('schema') ?>
<?= generate_schema_breadcrumb([
    'Suara Bersatu FM' => current_url()
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
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Suara Bersatu FM</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-12">
        <div class="inline-flex items-center px-4 py-1.5 bg-slate-50 border border-slate-100 rounded-full transition-all duration-500 mb-6" id="status-badge">
            <span class="relative flex h-2 w-2 mr-2.5">
                <span id="status-ping" class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-0 transition-opacity duration-500"></span>
                <span id="status-dot" class="relative inline-flex rounded-full h-2 w-2 bg-slate-400 transition-colors duration-500"></span>
            </span>
            <span id="status-msg" class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em]">Siaran Siap</span>
        </div>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase mb-6"><?= esc($title) ?></h1>
        <div class="mt-8 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <!-- Player Section -->
    <div class="max-w-xl mx-auto mb-10">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden p-8 md:p-12 text-center group hover:shadow-xl transition-all duration-500">
            <!-- Integrated Radio Visual & Play Button -->
            <div class="relative w-64 h-64 mx-auto mb-10">
                <div class="absolute inset-0 bg-blue-900 rounded-[3rem] rotate-6 opacity-5 group-hover:rotate-12 transition-transform duration-700"></div>
                
                <button id="play-btn" class="relative w-64 h-64 bg-slate-900 rounded-[3rem] flex flex-col items-center justify-center shadow-2xl ring-4 ring-slate-50 overflow-hidden hover:scale-105 active:scale-95 transition-all duration-500 group/btn">
                    <img src="<?= base_url('sbfm.png') ?>" class="absolute w-48 h-auto opacity-20 group-hover/btn:scale-110 transition-transform duration-[3s] object-contain" alt="Logo">
                    
                    <div class="relative z-10 flex flex-col items-center space-y-6">
                        <!-- 1. Equalizer (Top) -->
                        <div id="equalizer" class="flex items-end space-x-2 h-12 opacity-20 transition-all duration-500">
                            <div class="w-2 bg-blue-500 rounded-full h-2 bar-1"></div>
                            <div class="w-2 bg-blue-500 rounded-full h-2 bar-2"></div>
                            <div class="w-2 bg-blue-500 rounded-full h-2 bar-3"></div>
                            <div class="w-2 bg-blue-500 rounded-full h-2 bar-4"></div>
                            <div class="w-2 bg-blue-500 rounded-full h-2 bar-5"></div>
                        </div>

                        <!-- 2. Frequency (Middle - Persistent) -->
                        <div class="text-3xl font-black text-white tracking-tighter drop-shadow-md">
                            95,5 <span class="text-xs text-blue-400 uppercase font-bold ml-0.5 tracking-widest">FM</span>
                        </div>

                        <!-- 3. Play Icon & Loading (Bottom) -->
                        <div class="relative">
                            <div id="play-icon-container" class="transition-transform duration-300">
                                <i id="play-icon" class="fa-solid fa-play text-4xl text-white ml-1 drop-shadow-lg"></i>
                            </div>
                            
                            <!-- Loading Spinner (Inside) -->
                            <div id="buffering-indicator" class="hidden absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-10 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute inset-0 bg-blue-600/10 mix-blend-overlay"></div>
                </button>
            </div>

            <!-- Audio Player -->
            <div class="w-full max-w-xs mx-auto">
                <audio id="radio-player" class="hidden">
                    <source src="<?= esc($stream_url) ?>" type="audio/mpeg">
                </audio>

                <div class="space-y-6">
                    <div class="flex items-center space-x-4 bg-slate-50 px-6 py-4 rounded-2xl border border-slate-100">
                        <button id="mute-btn" class="text-slate-400 hover:text-blue-800 transition-colors"><i id="volume-icon" class="fa-solid fa-volume-high text-base"></i></button>
                        <input type="range" id="volume-slider" min="0" max="1" step="0.01" value="1" class="flex-1 accent-blue-800 h-1 bg-slate-200 rounded-full cursor-pointer appearance-none">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes equalizer { 0%, 100% { height: 15%; } 50% { height: 100%; } }
    .playing .bar-1 { animation: equalizer 0.8s ease-in-out infinite; }
    .playing .bar-2 { animation: equalizer 1.2s ease-in-out infinite 0.1s; }
    .playing .bar-3 { animation: equalizer 0.9s ease-in-out infinite 0.2s; }
    .playing .bar-4 { animation: equalizer 1.1s ease-in-out infinite 0.3s; }
    .playing .bar-5 { animation: equalizer 1.0s ease-in-out infinite 0.4s; }
</style>


<script>
    const audio = document.getElementById('radio-player');
    const playBtn = document.getElementById('play-btn');
    const playIcon = document.getElementById('play-icon');
    const volumeSlider = document.getElementById('volume-slider');
    const volumeIcon = document.getElementById('volume-icon');
    const muteBtn = document.getElementById('mute-btn');
    const statusMsg = document.getElementById('status-msg');
    const bufferingIndicator = document.getElementById('buffering-indicator');
    const equalizer = document.getElementById('equalizer');

    let lastVolume = 1;

    playBtn.addEventListener('click', () => {
        if (audio.paused) {
            statusMsg.innerText = 'Menyambungkan...';
            showLoading(true);
            audio.load();
            audio.play().then(() => updateUI(true)).catch(() => {
                statusMsg.innerText = 'Gagal memuat siaran';
                statusMsg.classList.add('text-red-600');
                showLoading(false);
            });
        } else {
            audio.pause();
            updateUI(false);
            statusMsg.innerText = 'Siaran Dihentikan';
        }
    });

    function showLoading(isLoading) {
        if (isLoading) {
            bufferingIndicator.classList.remove('hidden');
            playIcon.classList.add('opacity-0');
        } else {
            bufferingIndicator.classList.add('hidden');
            playIcon.classList.remove('opacity-0');
        }
    }

    function updateUI(isPlaying) {
        const dot = document.getElementById('status-dot');
        const badge = document.getElementById('status-badge');
        const ping = document.getElementById('status-ping');
        const iconContainer = document.getElementById('play-icon-container');
        
        if (isPlaying) {
            playIcon.classList.replace('fa-play', 'fa-pause');
            playIcon.classList.remove('ml-1');
            statusMsg.innerText = 'Streaming Aktif';
            statusMsg.classList.replace('text-slate-500', 'text-red-600');
            showLoading(false);
            
            equalizer.classList.add('playing');
            equalizer.classList.replace('opacity-50', 'opacity-100');
            if (iconContainer) iconContainer.classList.add('scale-110');
            
            if (badge) badge.classList.replace('bg-slate-50', 'bg-red-50');
            if (badge) badge.classList.replace('border-slate-100', 'border-red-100');
            if (dot) dot.classList.replace('bg-slate-400', 'bg-red-600');
            if (ping) ping.classList.replace('opacity-0', 'opacity-100');
            if (ping) ping.classList.add('animate-ping');
        } else {
            playIcon.classList.replace('fa-pause', 'fa-play');
            playIcon.classList.add('ml-1');
            showLoading(false);
            statusMsg.innerText = 'Siaran Dihentikan';
            statusMsg.classList.replace('text-red-600', 'text-slate-500');

            equalizer.classList.remove('playing');
            equalizer.classList.replace('opacity-100', 'opacity-50');
            if (iconContainer) iconContainer.classList.remove('scale-110');

            if (badge) badge.classList.replace('bg-red-50', 'bg-slate-50');
            if (badge) badge.classList.replace('border-red-100', 'border-slate-100');
            if (dot) dot.classList.replace('bg-red-600', 'bg-slate-400');
            if (ping) ping.classList.replace('opacity-100', 'opacity-0');
            if (ping) ping.classList.remove('animate-ping');
        }
    }

    volumeSlider.addEventListener('input', (e) => {
        audio.volume = e.target.value;
        updateVolumeIcon(e.target.value);
    });

    muteBtn.addEventListener('click', () => {
        if (audio.volume > 0) { lastVolume = audio.volume; audio.volume = 0; volumeSlider.value = 0; }
        else { audio.volume = lastVolume; volumeSlider.value = lastVolume; }
        updateVolumeIcon(audio.volume);
    });

    function updateVolumeIcon(volume) {
        volumeIcon.className = 'fa-solid text-lg ' + (volume == 0 ? 'fa-volume-xmark' : (volume < 0.5 ? 'fa-volume-low' : 'fa-volume-high'));
    }

    audio.addEventListener('waiting', () => showLoading(true));
    audio.addEventListener('playing', () => showLoading(false));
    audio.addEventListener('error', () => { statusMsg.innerText = 'Siaran Offline'; statusMsg.classList.add('text-red-600'); updateUI(false); });
</script>

<?= $this->endSection() ?>