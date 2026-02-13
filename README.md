# Humas Sinjai - Portal Berita Resmi Pemerintah Kabupaten Sinjai

Aplikasi web portal berita dan informasi publik untuk Humas Sinjai, dibangun dengan arsitektur modern menggunakan framework CodeIgniter 4 dan Tailwind CSS. Platform ini dirancang untuk desiminasi informasi pembangunan, agenda pemerintah, dan pelayanan publik secara cepat dan transparan.

## Fitur Utama

### 🌐 Portal Publik (Front-end)

- **Beranda Informatif:** Menampilkan **Berita Terkini**, **Topik Populer**, dan Carousel Banner visual tinggi.
- **Indeks Berita:** Manajemen konten berita dengan kategorisasi sistematis dan sistem label (Tag).
- **Profil Pimpinan & Pejabat:** Halaman khusus biografi pejabat daerah yang dikelompokkan (Forkopimda, Eselon II-IV, hingga Kepala Desa).
- **Agenda Pembangunan:** Halaman khusus untuk mensosialisasikan **Program Prioritas** pemerintah kabupaten.
- **Pencarian Cerdas:** Fitur penelusuran informasi publik yang cepat dan akurat.
- **Layanan Integrasi (Widget):** Widget RSS yang dapat dipasang pada situs web eksternal (OPD/Instansi lain) untuk sinkronisasi berita otomatis.
- **SEO & Distribusi:** Optimasi otomatis via Sitemap.xml, RSS Feed, Canonical Tags, dan meta tag dinamis untuk media sosial.
- **Aksesibilitas:** Integrasi widget aksesibilitas untuk kemudahan navigasi bagi semua kalangan.

### 🛠 Dashboard Admin (Back-end)

- **Pusat Statistik & Analitik:** Dashboard yang terintegrasi langsung dengan **Google Analytics 4** untuk memantau:
  - Ikhtisar Kunjungan harian/bulanan.
  - Penelusuran Halaman Terpopuler.
  - Analisis Sumber Trafik dan Demografi Pengunjung.
  - **Ekspor Laporan PDF:** Cetak laporan statistik kunjungan bulanan secara otomatis.
- **Manajemen Konten (CMS):**
  - **Editor Berita Modern:** Editor teks kaya (TinyMCE) dengan kemampuan unggah gambar otomatis dan fitur *paste* langsung dari clipboard.
  - **AI SEO Assistant:** Integrasi **Gemini AI** untuk menyarankan Label (Tag) SEO secara otomatis berdasarkan konten berita.
  - **Manajemen Media:** Pengaturan Banner Utama (Carousel) dan galeri unggahan.
- **Manajemen Taksonomi:** Pengelolaan Kategori Informasi dan Topik (Tag) secara dinamis.
- **Manajemen Pejabat:** Pengelolaan data pimpinan daerah lengkap dengan foto dan biografi terformat.
- **Keamanan:** Proteksi CSRF, sistem autentikasi admin, dan validasi input yang ketat.

### 🚀 Teknologi & Integrasi

- **Framework:** CodeIgniter 4.5+ (PHP 8.1+).
- **Frontend Engine:** Tailwind CSS 3.4 (Modern, utility-first design).
- **Arsitektur:** Service-Oriented Layer (Lapis Layanan) untuk logika bisnis yang bersih dan *maintainable*.
- **AI Service:** Google Gemini API (Model: `gemini-1.5-flash`).
- **Analytics Service:** Google Analytics 4 (via Google Cloud Service Account).
- **Libraries:** TinyMCE, FontAwesome 6, Dompdf.

## Persyaratan Sistem

- PHP version 8.1 atau lebih baru.
- Ekstensi PHP: `intl`, `mbstring`, `json`, `curl`, `gd` (untuk pengolahan gambar).
- Database MySQL/MariaDB.
- Composer & Node.js (untuk build Tailwind).

## Konfigurasi Cepat

1.  **Environment:**
    Salin file `env` menjadi `.env` dan sesuaikan parameter berikut:

    ```env
    CI_ENVIRONMENT = production
    app.baseURL = 'https://humas.sinjaikab.go.id/'

    # Konfigurasi Database
    database.default.hostname = localhost
    database.default.database = humas_sinjai
    database.default.username = root
    database.default.password = 

    # Integrasi Google Cloud & Analytics
    GOOGLE_APPLICATION_CREDENTIALS = writable/keys/google-service-account.json
    GA_PROPERTY_ID = '123456789'

    # Integrasi AI
    GEMINI_API_KEY = 'YOUR_API_KEY'

    # Live Streaming
    stream.radio.url = 'http://103.155.105.10:8000/stream'
    stream.tv.url = 'https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Fsinjaikab%2Flive&show_text=0&width=560'
    ```

2.  **Instalasi & Build:**

    ```bash
    composer install
    npm install
    npm run build
    ```

3.  **Migrasi Database:**

    ```bash
    php spark migrate
    ```

## Struktur Arsitektur

- `app/Services`: Logika bisnis inti (Content, Media, Analytics, Auth).
- `app/Controllers`: Handler permintaan (Thin Controllers).
- `app/Models`: Definisi entitas data.
- `app/Views`: Template UI berbasis komponen.
- `public/uploads`: Penyimpanan media publik (Berita, Profil, Carousel).