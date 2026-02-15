<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<?= view('layouts/partials/head') ?>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">
    
    <?= view('layouts/partials/navbar') ?>

    <!-- Main Content -->
    <main class="min-h-screen">
        <div class="<?= url_is('/') ? '' : 'pb-12 md:pb-20 pt-2 md:pt-4' ?>">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <?= view('layouts/partials/footer') ?>

    <?= view('layouts/partials/scripts') ?>
</body>

</html>