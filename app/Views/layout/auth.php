<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Humas Sinjai</title>
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">
    
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="h-full">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-100">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="<?= base_url('/') ?>" class="flex justify-center">
                <img src="<?= base_url('logo.png') ?>" alt="Logo Sinjai" class="h-20 w-auto">
            </a>
            <h2 class="mt-6 text-center text-3xl font-black text-slate-900 tracking-tight uppercase">
                Admin E-Portal
            </h2>
            <p class="mt-2 text-center text-sm font-bold text-slate-500 uppercase tracking-widest">
                Humas Kabupaten Sinjai
            </p>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
            <?= $this->renderSection('content') ?>
            
            <div class="mt-8 text-center">
                <a href="<?= base_url('/') ?>" class="text-[10px] font-black text-slate-400 hover:text-blue-800 uppercase tracking-[0.3em] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda Utama
                </a>
            </div>
        </div>
    </div>
</body>
</html>