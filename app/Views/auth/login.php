<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
    <!-- Header -->
    <div class="text-center mb-6">
        <a href="<?= base_url('/') ?>" class="inline-block mb-3">
            <img src="<?= base_url('logo.png') ?>" alt="Logo Sinjai" class="h-16 w-auto mx-auto">
        </a>
        <h1 class="text-xl font-black text-slate-900 tracking-tight">Admin Panel</h1>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl flex items-center text-red-700 text-xs font-medium">
            <i class="fa-solid fa-circle-exclamation mr-2.5 flex-shrink-0 text-red-500"></i>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center text-emerald-700 text-xs font-medium">
            <i class="fa-solid fa-circle-check mr-2.5 flex-shrink-0 text-emerald-500"></i>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('masuk') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-fw fa-envelope text-xs"></i>
                </div>
                <input type="email" name="email" id="email" required
                    class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-800 focus:ring-2 focus:ring-blue-800/20 outline-none transition-all"
                    placeholder="nama@sinjaikab.go.id" value="<?= old('email') ?>">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="login-password" class="block text-xs font-bold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-fw fa-lock text-xs"></i>
                </div>
                <input type="password" name="password" id="login-password" required
                    class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-base sm:text-sm font-medium text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-800 focus:ring-2 focus:ring-blue-800/20 outline-none transition-all"
                    placeholder="••••••••">
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition-colors" aria-label="Lihat password">
                    <i class="fa-solid fa-fw fa-eye text-xs"></i>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 bg-blue-800 hover:bg-blue-900 text-white text-sm font-bold rounded-xl transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-800 focus:ring-offset-2">
                Masuk
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const input = document.getElementById('login-password');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>

<?= $this->endSection() ?>