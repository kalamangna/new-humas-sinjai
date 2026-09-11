<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="robots" content="noindex, nofollow">
    <title>Masuk - Humas Sinjai</title>
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css">
</head>

<body class="h-full flex flex-col justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <?= $this->renderSection('content') ?>

        <div class="mt-6 text-center">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center text-xs font-semibold text-slate-400 hover:text-slate-700 transition-colors">
                <i class="fa-solid fa-fw fa-arrow-left mr-2 text-[11px]"></i>Kembali ke Beranda
            </a>
        </div>
    </div>
</body>

</html>