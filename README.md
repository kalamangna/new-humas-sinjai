# Humas Sinjai

Aplikasi web portal berita dan informasi publik untuk Humas Sinjai, dibangun dengan arsitektur modern menggunakan framework CodeIgniter 4 dan Tailwind CSS. Platform ini dirancang untuk desiminasi informasi pembangunan, agenda pemerintah, dan pelayanan publik secara cepat dan transparan.

## Daftar Isi

1.  [Core Functionality](#core-functionality)
2.  [Key Features](#key-features)
3.  [Technical Stack](#technical-stack)
4.  [Installation](#installation)
5.  [Usage](#usage)
6.  [Configuration](#configuration)
7.  [API Endpoints](#api-endpoints)

## 1. Core Functionality

Humas Sinjai berfungsi sebagai platform terpusat untuk diseminasi informasi publik. Aplikasi ini memungkinkan pengelolaan dan publikasi berita, informasi pejabat daerah, agenda pembangunan (program prioritas), serta menyediakan layanan live streaming TV dan Radio. Selain itu, platform ini juga dilengkapi dengan dashboard administrasi untuk manajemen konten, pemantauan analitik situs, dan pengaturan sistem yang komprehensif. Tujuan utamanya adalah meningkatkan transparansi dan aksesibilitas informasi bagi masyarakat Kabupaten Sinjai.

## 2. Key Features

### 🌐 Portal Publik (Front-end)

- **Beranda Informatif:** Menampilkan **Berita Terkini**, **Tag Populer**, dan Carousel Banner visual tinggi.
- **Indeks Berita:** Manajemen konten berita dengan kategorisasi sistematis dan sistem Tag.
- **Profil Pimpinan & Pejabat:** Halaman khusus biografi pejabat daerah yang dikelompokkan (Forkopimda, Eselon II-IV, hingga Kepala Desa).
- **Agenda Pembangunan:** Halaman khusus untuk mensosialisasikan **Program Prioritas** pemerintah kabupaten.
- **Live Streaming TV & Radio:** Akses siaran langsung **Sinjai TV** (Facebook Live) dan **Radio Suara Bersatu FM** secara real-time.
- **Pencarian Cerdas:** Fitur penelusuran informasi publik yang cepat dan akurat.
- **Layanan Integrasi (Widget):** Widget RSS yang dapat dipasang pada situs web eksternal (OPD/Instansi lain) untuk sinkronisasi berita otomatis.
- **SEO & Distribusi:** Optimasi otomatis via Sitemap.xml, RSS Feed, Canonical Tags, dan meta tag dinamis untuk media sosial.
- **Aksesibilitas:** Integrasi widget aksesibilitas untuk kemudahan navigasi bagi semua kalangan.
- **Optimasi UI/UX:** Navigasi mobile yang responsif dengan menu collapsible dan footer informatif dengan penempatan branding yang proporsional.

### 🛠 Dashboard Admin (Back-end)

- **Pusat Statistik & Analitik:** Dashboard yang terintegrasi langsung dengan **Google Analytics 4** untuk memantau:
  - Ikhtisar Kunjungan harian/bulanan dengan filter rentang tanggal dinamis.
  - Penelusuran Halaman Terpopuler dan Sumber Trafik.
  - Analisis Geografis dan Statistik Perangkat pengunjung.
- **Manajemen Laporan:** Modul khusus untuk arsip publikasi berita bulanan dengan fitur **Ekspor PDF** yang profesional untuk keperluan dokumentasi birokrasi.
- **Manajemen Konten (CMS):** Kontrol penuh atas Berita (Posting), Kategori, Tag, Profil Pejabat, dan Slide Carousel.
- **Pengaturan Situs Terpusat:** Kelola identitas situs (Nama, Logo, Tagline), Kontak (Email, Alamat, Maps), Media Sosial, dan Informasi Lembaga (Visi & Misi) langsung dari panel admin tanpa menyentuh kode.
- **Sistem Keamanan:** Otentikasi multi-role (Admin & Penulis) dengan manajemen profil pengguna yang aman.

### ⚙️ UI/UX & Konfigurasi Admin

- **Sidebar Pintar:** Sidebar responsif dengan fitur toggle (Expand/Collapse) pada tampilan desktop untuk area kerja yang lebih luas. Status sidebar tersimpan secara otomatis di browser (_localStorage_).
- **Standarisasi Terminologi:** Penggunaan istilah yang konsisten dan profesional (Berita, Penulis, Konsep, Terbitkan) di seluruh modul admin untuk memudahkan operasional.
- **Antarmuka Modern:** Desain berbasis kartu (Card-based) yang bersih dengan navigasi yang intuitif dan responsif di berbagai perangkat.

## 3. Technical Stack

- **Framework:** CodeIgniter 4.4+ (PHP 8.1+)
- **Styling:** Tailwind CSS 3.4
- **Database:** MySQL/MariaDB
- **Frontend Libraries:** Alpine.js (for interactive UI components)
- **Analytics Integration:** Google Analytics Data API v1
- **PDF Generation:** Dompdf
- **Icons:** FontAwesome 6 (Local Integration)
- **AI Integration:** Google Gemini API (for Tag Suggestion with fallback models)

## 4. Installation

### Prasyarat

- PHP >= 8.1
- Composer
- Node.js & npm (untuk build CSS)
- Ekstensi PHP: `intl`, `mbstring`, `json`, `mysqlnd`, `curl`, `dom`, `gd`, `xml`, `zip`

### Langkah-langkah Instalasi

1.  **Clone repositori:**
    ```bash
    git clone https://github.com/kalamangna/humas-sinjai.git
    cd humas-sinjai
    ```
2.  **Instal dependensi PHP:**
    ```bash
    composer install
    ```
3.  **Instal dependensi JavaScript:**
    ```bash
    npm install
    ```
4.  **Konfigurasi Environment:**
    Salin file `env` menjadi `.env` dan sesuaikan pengaturan database serta API Google Analytics (GA4 Property ID dan path ke file kredensial JSON Google Service Account).

    ```bash
    cp env .env
    ```

    Edit `.env` file:

    ```
    # Database Configuration
    database.default.hostname = localhost
    database.default.database = humas_sinjai
    database.default.username = root
    database.default.password =
    database.default.DBDriver = MySQLi

    # Google Analytics API Configuration
    GEMINI_API_KEY = your_gemini_api_key_here
    GOOGLE_ANALYTICS_PROPERTY_ID = your_ga4_property_id_here
    GOOGLE_SERVICE_ACCOUNT_CREDENTIALS = /path/to/your/google-service-account.json
    ```

5.  **Buat Database:**
    Buat database MySQL/MariaDB dengan nama yang sesuai dengan konfigurasi `.env` Anda (misal: `humas_sinjai`).
6.  **Jalankan Migrasi dan Seeder:**

    ```bash
    php spark migrate
    php spark db:seed SiteSettingsSeeder
    php spark db:seed UserSeeder
    php spark db:seed CategorySeeder
    php spark db:seed PostSeeder
    php spark db:seed TagSeeder
    php spark db:seed PostTagSeeder
    ```

    (Note: Some seeders might require manual adjustments if data dependencies are not met in order).

7.  **Build CSS (Tailwind):**
    Untuk melakukan build CSS produksi (minified):
    ```bash
    npm run build
    ```
    Selama pengembangan, Anda dapat menggunakan:
    ```bash
    npm run watch
    ```

## 5. Usage

Setelah instalasi selesai:

- Akses **Portal Publik** melalui URL utama aplikasi Anda (misal: `http://localhost:8080`).
- Akses **Dashboard Admin** melalui `http://localhost:8080/admin`.
  - Login dengan kredensial default: `admin@example.com` / `password`.
  - (Catatan: Kredensial ini dibuat oleh `UserSeeder`. Pastikan Anda telah menjalankan seeder tersebut).

## 6. Configuration

Semua konfigurasi utama situs (nama situs, logo, media sosial, informasi kontak, visi-misi) dapat dikelola melalui panel admin di bagian **Pengaturan Situs**. Untuk konfigurasi teknis seperti database, API Keys, dan pengaturan lingkungan lainnya, sesuaikan file `.env`.

## 7. API Endpoints

Aplikasi ini mengekspos beberapa API endpoint untuk data analitik dan fungsionalitas tertentu:

- **POST /api/tags/suggest:** Menghasilkan saran tag berita menggunakan AI berdasarkan judul dan konten.
- **GET /api/analytics/overview:** Mendapatkan data ringkasan analitik situs.
- **GET /api/analytics/top-pages:** Mendapatkan daftar halaman terpopuler.
- **GET /api/analytics/traffic-sources:** Mendapatkan sumber lalu lintas pengunjung.
- **GET /api/analytics/geo:** Mendapatkan data geografis pengunjung.
- **GET /api/analytics/device-category:** Mendapatkan statistik perangkat pengunjung.
- **GET /api/analytics/popular-posts:** Mendapatkan daftar berita terpopuler.
- **GET /api/analytics/monthly-post-stats:** Mendapatkan statistik tayangan berita bulanan.
- **GET /api/analytics/monthly-user-stats:** Mendapatkan statistik pertumbuhan pengguna bulanan.

Setiap endpoint analitik mendukung parameter query `start_date` dan `end_date` untuk filter rentang waktu.

---

&copy; 2026 Diskominfo-SP Kabupaten Sinjai. Dikembangkan untuk transparansi informasi publik.
