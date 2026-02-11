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
                    <span class="text-slate-400">Hubungi Kami</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Layanan Informasi</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Hubungi Kami
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-8">
        <!-- Contact Info Cards -->
        <div class="lg:col-span-5 space-y-8">
            <!-- Address Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-start group hover:border-blue-800 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 group-hover:bg-blue-800 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-fw fa-map-marker-alt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Alamat Kantor</h2>
                    <p class="text-slate-900 font-bold leading-relaxed tracking-tight text-lg">
                        Jl. Persatuan Raya No. 101, Balangnipa, Kec. Sinjai Utara, Kab. Sinjai
                    </p>
                </div>
            </div>

            <!-- Email Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-start group hover:border-blue-800 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 group-hover:bg-blue-800 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-fw fa-envelope text-xl"></i>
                </div>
                <div>
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Email Resmi</h2>
                    <p class="text-slate-900 font-bold leading-relaxed tracking-tight text-lg">
                        humas@sinjaikab.go.id
                    </p>
                    <p class="text-slate-500 text-xs mt-1 font-medium uppercase tracking-wider">Layanan surat elektronik 24/7</p>
                </div>
            </div>

            <!-- Social Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-start group hover:border-blue-800 transition-all duration-300">
                <div class="w-14 h-14 bg-blue-50 text-blue-800 rounded-2xl flex items-center justify-center flex-shrink-0 mr-6 group-hover:bg-blue-800 group-hover:text-white transition-all duration-300 shadow-sm">
                    <i class="fas fa-fw fa-share-alt text-xl"></i>
                </div>
                <div>
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Koneksi Digital</h2>
                    <div class="flex flex-wrap gap-3 mt-2">
                        <a href="https://www.facebook.com/FP.KabupatenSinjai" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fab fa-fw fa-facebook-f text-sm"></i></a>
                        <a href="https://www.instagram.com/sinjaikab" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fab fa-fw fa-instagram text-sm"></i></a>
                        <a href="https://www.tiktok.com/@pemkabsinjai" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fab fa-fw fa-tiktok text-sm"></i></a>
                        <a href="https://www.youtube.com/@SINJAITV" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fab fa-fw fa-youtube text-sm"></i></a>
                        <a href="https://x.com/sinjaikab" target="_blank" class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-blue-800 hover:text-white transition-all shadow-sm"><i class="fab fa-fw fa-x-twitter text-sm"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden h-full min-h-[450px] relative">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15914.868748882522!2d120.25206256860015!3d-5.12061324749323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db951010072b7a9%3A0x8WX2QetWMMaGdYiw5!2sBalangnipa%2C%20Sinjai%20Utara%2C%20Sinjai%20Regency%2C%20South%20Sulawesi!5e0!3m2!1sen!2sid!4v1715600000000!5m2!1sen!2sid"
                    class="absolute inset-0 w-full h-full border-0 grayscale opacity-80"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <div class="absolute bottom-6 right-6">
                    <a href="https://maps.app.goo.gl/8WX2QetWMMaGdYiw5" target="_blank" class="px-6 py-3 bg-blue-800 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-xl hover:bg-blue-900 transition-all flex items-center">
                        <i class="fas fa-fw fa-directions mr-2 text-base"></i>Buka Navigasi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Section -->
    <div class="bg-slate-900 rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-64 h-64 bg-blue-800/10 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="relative z-10">
            <h2 class="text-2xl md:text-4xl font-black text-white mb-6 tracking-tight uppercase">Punya Pengaduan atau Aspirasi?</h2>
            <p class="text-slate-400 mb-8 max-w-2xl mx-auto font-medium leading-relaxed">Gunakan layanan aspirasi dan pengaduan online rakyat (LAPOR!) untuk menyampaikan keluhan anda secara langsung kepada pihak berwenang.</p>
            <a href="https://lapor.go.id/" target="_blank" class="inline-block bg-white p-5 rounded-2xl hover:shadow-2xl hover:shadow-blue-900/40 transition-all hover:-translate-y-1">
                <img src="<?= base_url('lapor.png') ?>" alt="Lapor" class="h-16 w-auto">
            </a>
            <p class="mt-8 text-blue-500 font-black text-[10px] uppercase tracking-widest italic">#BeraniLAPORUntukPerubahan</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>