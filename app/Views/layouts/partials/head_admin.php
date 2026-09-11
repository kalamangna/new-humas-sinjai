<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Humas Sinjai</title>
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>?v=<?= filemtime(FCPATH . 'assets/css/app.css') ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.2.0/css/all.min.css">

    <!-- Third Party Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="<?= base_url('assets/tinymce/tinymce/tinymce.min.js') ?>" referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
            selector: 'textarea#content, textarea#bio',
            plugins: 'code table lists image',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image',
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = true;
                xhr.open('POST', '<?= site_url('admin/posts/upload_image') ?>');
                xhr.setRequestHeader('<?= csrf_header() ?>', '<?= csrf_hash() ?>');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status === 403) {
                        reject({ message: 'Akses ditolak atau token CSRF kedaluwarsa.', remove: true });
                        return;
                    }
                    if (xhr.status < 200 || xhr.status >= 300) {
                        reject('Gagal mengunggah: HTTP ' + xhr.status);
                        return;
                    }
                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (!json || typeof json.location !== 'string') {
                            reject('Format respons tidak valid.');
                            return;
                        }
                        resolve(json.location);
                    } catch (e) {
                        reject('Gagal memproses respons server.');
                    }
                };

                xhr.onerror = () => {
                    reject('Koneksi gagal saat mengunggah gambar.');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                xhr.send(formData);
            }),
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