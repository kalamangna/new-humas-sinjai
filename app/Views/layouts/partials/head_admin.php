<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Humas Sinjai</title>
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">

    <!-- Third Party Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="<?= base_url('assets/tinymce/tinymce/tinymce.min.js') ?>" referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
            selector: 'textarea#content, textarea#bio',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_url: '<?= site_url('admin/posts/upload_image') ?>',
            relative_urls: false,
            remove_script_host: false,
            license_key: 'gpl'
        });

        function previewImage(inputId = 'thumbnail', previewId = 'thumbnail-preview', containerId = 'thumbnail-preview-container') {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const container = document.getElementById(containerId);
            
            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.readAsDataURL(input.files[0]);
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    if (container) {
                        container.classList.remove('hidden');
                    }
                }
            }
        }
    </script>
</head>