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
                    <span class="text-slate-400 truncate max-w-[150px] md:max-w-none">Panduan Integrasi</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-8">
        <p class="text-[11px] font-black text-blue-800 uppercase tracking-[0.4em] mb-4">Layanan Pengembang</p>
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight uppercase">
            Widget Berita
        </h1>
        <div class="mt-4 w-24 h-2 bg-blue-800 mx-auto rounded-full shadow-lg shadow-blue-900/20"></div>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 md:p-16">
                <!-- Description -->
                <section class="mb-10">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-6 flex items-center border-b border-slate-50 pb-4">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Tentang Widget
                    </h2>
                    <p class="text-slate-600 font-medium leading-relaxed">
                        Widget berita Humas Sinjai dirancang khusus untuk memudahkan instansi pemerintah, media lokal, dan mitra strategis dalam menyebarluaskan informasi resmi. Widget ini bersifat responsif, ringan, dan dapat disesuaikan dengan tema situs web anda.
                    </p>
                </section>

                <!-- Installation -->
                <section class="mb-10">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-8 flex items-center border-b border-slate-50 pb-4">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Langkah Pemasangan
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 text-blue-800 font-black rounded-xl flex items-center justify-center flex-shrink-0 mr-6 shadow-sm">1</div>
                            <p class="text-slate-600 font-bold mt-2 tracking-tight">Pilih tema widget yang sesuai (Terang atau Gelap).</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 text-blue-800 font-black rounded-xl flex items-center justify-center flex-shrink-0 mr-6 shadow-sm">2</div>
                            <p class="text-slate-600 font-bold mt-2 tracking-tight">Salin kode HTML dan Script yang tersedia di bawah.</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-blue-50 text-blue-800 font-black rounded-xl flex items-center justify-center flex-shrink-0 mr-6 shadow-sm">3</div>
                            <p class="text-slate-600 font-bold mt-2 tracking-tight">Tempelkan kode tersebut pada bagian yang diinginkan di situs anda.</p>
                        </div>
                    </div>
                </section>

                <!-- Code Embed -->
                <section class="mb-10 space-y-12">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-8 flex items-center border-b border-slate-50 pb-4">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Kode Integrasi
                    </h2>

                    <!-- Light Theme -->
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">A. Tema Terang (Bawaan)</h3>
                        <div class="relative group">
                            <button class="copy-btn absolute top-4 right-4 px-4 py-2 bg-blue-800 text-white text-[10px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow-lg" data-target="code-light">Salin Kode</button>
                            <pre id="code-light" class="bg-slate-50 border border-slate-200 rounded-2xl p-6 overflow-x-auto text-xs font-mono text-slate-700 leading-relaxed shadow-inner"><code>&lt;!-- Gaya Visual Widget --&gt;
&lt;link rel="stylesheet" href="https://humas.sinjaikab.go.id/v2/rss-widget/style.css"&gt;

&lt;!-- Kontainer Tampilan --&gt;
&lt;div id="humas-widget-light"&gt;&lt;/div&gt;

&lt;!-- Inisialisasi Script --&gt;
&lt;script src="https://humas.sinjaikab.go.id/v2/rss-widget/widget.js"
    data-container="humas-widget-light"
    data-limit="5"
    data-theme="light"
    data-title="BERITA SINJAI"&gt;
&lt;/script&gt;</code></pre>
                        </div>
                    </div>

                    <!-- Dark Theme -->
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">B. Tema Gelap</h3>
                        <div class="relative group">
                            <button class="copy-btn absolute top-4 right-4 px-4 py-2 bg-blue-800 text-white text-[10px] font-black uppercase tracking-widest rounded-lg opacity-0 group-hover:opacity-100 transition-all shadow-lg" data-target="code-dark">Salin Kode</button>
                            <pre id="code-dark" class="bg-slate-900 border border-slate-800 rounded-2xl p-6 overflow-x-auto text-xs font-mono text-slate-400 leading-relaxed shadow-inner"><code>&lt;!-- Gaya Visual Widget --&gt;
&lt;link rel="stylesheet" href="https://humas.sinjaikab.go.id/v2/rss-widget/style.css"&gt;

&lt;!-- Kontainer Tampilan --&gt;
&lt;div id="humas-widget-dark"&gt;&lt;/div&gt;

&lt;!-- Inisialisasi Script --&gt;
&lt;script src="https://humas.sinjaikab.go.id/v2/rss-widget/widget.js"
    data-container="humas-widget-dark"
    data-limit="5"
    data-theme="dark"
    data-title="BERITA SINJAI"&gt;
&lt;/script&gt;</code></pre>
                        </div>
                    </div>
                </section>

                <!-- Parameters -->
                <section class="mb-10">
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-8 flex items-center border-b border-slate-50 pb-4">
                        <span class="w-2 h-6 bg-blue-800 mr-4 rounded-full"></span>Konfigurasi Lanjut (Atribut Data)
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm font-medium border-collapse table-auto">
                            <thead>
                                <tr class="text-slate-400 uppercase text-[10px] tracking-widest border-b border-slate-100">
                                    <th class="py-4 pr-6">Atribut</th>
                                    <th class="py-4 pr-6 text-center">Status</th>
                                    <th class="py-4">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-6 pr-6 font-mono text-blue-800 font-bold">data-container</td>
                                    <td class="py-6 pr-6 text-center"><span class="px-2 py-1 bg-red-50 text-red-600 rounded text-[9px] font-black uppercase tracking-widest">Wajib</span></td>
                                    <td class="py-6 text-slate-500 leading-relaxed font-medium">ID kontainer <code>&lt;div&gt;</code> tempat widget muncul.</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-6 pr-6 font-mono text-blue-800 font-bold">data-limit</td>
                                    <td class="py-6 pr-6 text-center"><span class="px-2 py-1 bg-slate-50 text-slate-500 rounded text-[9px] font-black uppercase tracking-widest">Opsional</span></td>
                                    <td class="py-6 text-slate-500 leading-relaxed font-medium">Jumlah berita (1-10). Default: 5.</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-6 pr-6 font-mono text-blue-800 font-bold">data-theme</td>
                                    <td class="py-6 pr-6 text-center"><span class="px-2 py-1 bg-slate-50 text-slate-500 rounded text-[9px] font-black uppercase tracking-widest">Opsional</span></td>
                                    <td class="py-6 text-slate-500 leading-relaxed font-medium">Gunakan <code>light</code> atau <code>dark</code>.</td>
                                </tr>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-6 pr-6 font-mono text-blue-800 font-bold">data-title</td>
                                    <td class="py-6 pr-6 text-center"><span class="px-2 py-1 bg-slate-50 text-slate-500 rounded text-[9px] font-black uppercase tracking-widest">Opsional</span></td>
                                    <td class="py-6 text-slate-500 leading-relaxed font-medium">Judul kustom yang tampil di bagian atas widget.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Live Preview -->
                <section>
                    <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] mb-8 flex items-center justify-center">
                        <span class="w-10 h-0.5 bg-slate-200 mr-4"></span>Pratinjau Langsung<span class="w-10 h-0.5 bg-slate-200 ml-4"></span>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <div class="space-y-6">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center tracking-[0.2em]">Pratinjau Tema Terang</p>
                            <div id="rss-widget-preview-light" class="shadow-2xl rounded-2xl overflow-hidden"></div>
                        </div>
                        <div class="space-y-6">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-center tracking-[0.2em]">Pratinjau Tema Gelap</p>
                            <div id="rss-widget-preview-dark" class="shadow-2xl rounded-2xl overflow-hidden"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- Assets & Live Preview Logic -->
<link rel="stylesheet" href="<?= base_url('rss-widget/style.css') ?>">
<script src="<?= base_url('rss-widget/widget.js') ?>"
    data-container="rss-widget-preview-light"
    data-limit="5"
    data-theme="light">
</script>
<script src="<?= base_url('rss-widget/widget.js') ?>"
    data-container="rss-widget-preview-dark"
    data-limit="5"
    data-theme="dark">
</script>

<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('copy-btn')) {
            const btn = e.target;
            const pre = document.getElementById(btn.getAttribute('data-target'));
            const text = pre.innerText;

            navigator.clipboard.writeText(text).then(() => {
                const originalText = btn.innerText;
                btn.innerText = 'TERSERALIN!';
                btn.classList.add('bg-emerald-600');
                btn.classList.remove('bg-blue-800');

                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-blue-800');
                }, 2000);
            });
        }
    });
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- UserWay disabled on this page to prevent conflict with RSS widget previews -->
<?= $this->endSection() ?>