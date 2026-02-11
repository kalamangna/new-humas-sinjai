<?= $this->extend('Layouts/auth') ?>

<?= $this->section('content') ?>

<div class="bg-white py-10 px-8 shadow-2xl rounded-[2.5rem] border border-slate-200 ring-8 ring-white">
    <!-- Flash Message -->
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 border-l-4 border-red-600 p-4 mb-6 rounded-r-xl shadow-sm flex items-center">
            <i class="fas fa-fw fa-exclamation-triangle text-red-600 mr-3"></i>
            <p class="text-xs font-bold text-red-800 uppercase tracking-tight"><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-600 p-4 mb-6 rounded-r-xl shadow-sm flex items-center">
            <i class="fas fa-fw fa-check-circle text-emerald-600 mr-3"></i>
            <p class="text-xs font-bold text-emerald-800 uppercase tracking-tight"><?= session()->getFlashdata('success') ?></p>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post" class="space-y-8">
        <?= csrf_field() ?>

        <div class="space-y-6">
            <!-- Email -->
            <div>
                <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Identitas Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-800 transition-colors">
                        <i class="fas fa-fw fa-envelope"></i>
                    </div>
                    <input type="email" name="email" required
                        class="block w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-300 focus:border-blue-800 focus:bg-white outline-none transition-all"
                        placeholder="staf@sinjaikab.go.id" value="<?= old('email') ?>">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Kredensial Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300 group-focus-within:text-blue-800 transition-colors">
                        <i class="fas fa-fw fa-key"></i>
                    </div>
                    <input type="password" name="password" id="login-password" required
                        class="block w-full pl-12 pr-12 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold text-slate-900 placeholder-slate-300 focus:border-blue-800 focus:bg-white outline-none transition-all"
                        placeholder="••••••••">
                    <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-300 hover:text-slate-600 transition-colors">
                        <i class="fas fa-fw fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-800 focus:ring-blue-800 border-slate-300 rounded cursor-pointer" <?= old('remember') ? 'checked' : '' ?>>
                <label for="remember" class="ml-2 block text-xs font-bold text-slate-500 uppercase tracking-tighter cursor-pointer">Ingat Perangkat</label>
            </div>
            <div class="text-xs">
                <a href="#" class="font-black text-blue-800 uppercase tracking-tighter hover:text-blue-900">Masalah Login?</a>
            </div>
        </div>

        <div>
            <button type="submit" class="group relative w-full flex justify-center py-4 px-4 bg-blue-800 text-white text-xs font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-all shadow-xl shadow-blue-900/20">
                Masuk Sistem
                <span class="absolute right-6 inset-y-0 flex items-center">
                    <i class="fas fa-fw fa-chevron-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </span>
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