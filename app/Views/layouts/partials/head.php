<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?= generate_seo_tags($seo ?? []) ?>

    <!-- Preconnect ke CDN utama untuk performa koneksi cepat -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">

    <!-- Font Awesome (Non-blocking Asynchronous Load) -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css" as="style">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css"></noscript>
    <!-- JSON-LD Schemas -->
    <?= generate_schema_org() ?>
    <?= $this->renderSection('schema') ?>

    <!-- Google tag (gtag.js) - Deferred to idle for 0ms main thread blocking -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-QEW3BM9KJ7');

        window.addEventListener('DOMContentLoaded', () => {
            const loadGTM = () => {
                if (window.gtmLoaded) return;
                window.gtmLoaded = true;
                const s = document.createElement('script');
                s.async = true;
                s.src = 'https://www.googletagmanager.com/gtag/js?id=G-QEW3BM9KJ7';
                document.head.appendChild(s);
            };
            if ('requestIdleCallback' in window) {
                requestIdleCallback(loadGTM, { timeout: 3000 });
            } else {
                setTimeout(loadGTM, 2000);
            }
        });
    </script>
</head>