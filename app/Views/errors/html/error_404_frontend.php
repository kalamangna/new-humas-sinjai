<?= $this->extend('layouts/frontend') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto px-4 py-24 sm:px-6 lg:px-8">
    <div class="text-center">
        <h1 class="text-9xl font-black text-blue-800 opacity-10">404</h1>
        <div class="relative -mt-20">
            <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Halaman Tidak Ditemukan</h2>
            <p class="text-lg text-slate-500 font-medium max-w-lg mx-auto leading-relaxed mb-12">
                Maaf, portal kami tidak dapat menemukan informasi yang anda cari. Pastikan alamat yang anda masukkan sudah benar.
            </p>
            <a href="<?= base_url('/') ?>" class="inline-flex items-center px-10 py-5 bg-blue-800 text-white font-black uppercase tracking-widest text-xs rounded-2xl hover:bg-blue-900 transition-all shadow-xl shadow-blue-900/10">
                <i class="fas fa-fw fa-home mr-3 text-base"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>