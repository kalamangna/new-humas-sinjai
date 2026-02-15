# Humas Sinjai

Aplikasi web portal berita dan informasi publik untuk Humas Sinjai, dibangun dengan arsitektur modern menggunakan framework CodeIgniter 4 dan Tailwind CSS. Platform ini dirancang untuk desiminasi informasi pembangunan, agenda pemerintah, dan pelayanan publik secara cepat dan transparan.

## Daftar Isi

1.  [Core Functionality](#core-functionality)
2.  [Key Features](#key-features)
3.  [Technical Stack](#technical-stack)


## 1. Core Functionality

Humas Sinjai adalah platform web terpusat yang didesain untuk mendiseminasikan informasi publik, pembangunan, dan agenda pemerintahan Kabupaten Sinjai. Tujuan utamanya adalah meningkatkan transparansi dan aksesibilitas informasi, memungkinkan pengelolaan konten berita, profil pejabat, serta menyediakan layanan live streaming TV dan Radio. Dilengkapi dengan dasbor administrasi, sistem ini menawarkan kapabilitas manajemen konten yang komprehensif, pemantauan analitik situs secara mendalam, dan pengaturan sistem yang fleksibel.

## 2. Key Features

### 🌐 Portal Publik (Front-end)

-   **Berita & Informasi Dinamis:** Tampilan beranda interaktif dengan berita terkini, tag populer, dan carousel banner visual. Dilengkapi indeks berita komprehensif, sistem kategorisasi, dan halaman khusus untuk profil pejabat daerah dan agenda pembangunan (program prioritas).
-   **Live Streaming Terintegrasi:** Akses langsung ke siaran Sinjai TV (via Facebook Live) dan Radio Suara Bersatu FM.
-   **Pencarian & Distribusi Cerdas:** Fitur pencarian cepat dan akurat, dioptimalkan dengan Sitemap.xml, RSS Feed, Canonical Tags, dan meta tag dinamis untuk SEO dan berbagi media sosial.
-   **Aksesibilitas & Responsivitas:** Desain responsif dengan navigasi mobile intuitif, footer informatif, dan widget aksesibilitas untuk pengalaman pengguna yang inklusif.
-   **Layanan Integrasi Widget:** Menyediakan widget RSS yang mudah dipasang di situs eksternal untuk sinkronisasi berita otomatis.

### 🛠 Dashboard Admin (Back-end)

-   **Analitik Situs Komprehensif:** Dasbor terintegrasi dengan Google Analytics 4 untuk pemantauan kunjungan (harian/bulanan), halaman terpopuler, sumber trafik, analisis geografis, dan statistik perangkat pengunjung.
-   **Manajemen Konten Lengkap:** Kontrol penuh atas Berita (Posting), Kategori, Tag, Profil Pejabat, dan Slide Carousel melalui antarmuka CMS yang user-friendly.
-   **Pelaporan & Dokumentasi:** Modul pelaporan arsip publikasi berita bulanan dengan fitur ekspor PDF profesional.
-   **Pengaturan Sistem Terpusat:** Pengelolaan identitas situs, kontak, media sosial, dan informasi lembaga (Visi & Misi) langsung dari panel admin.
-   **Keamanan & Akses:** Sistem otentikasi multi-role (Admin & Penulis) untuk manajemen pengguna yang aman dan terstruktur.

### ⚙️ UI/UX & Konfigurasi Admin

-   **Antarmuka Adaptif:** Sidebar responsif dengan toggle expand/collapse (status tersimpan lokal) untuk fleksibilitas ruang kerja.
-   **Desain Modern & Intuitif:** Antarmuka berbasis kartu (Card-based) yang bersih, navigasi responsif di berbagai perangkat, dan terminologi yang konsisten di seluruh modul admin.

## 3. Technical Stack

-   **Backend Framework:** CodeIgniter 4.4+ (PHP 8.1+)
-   **Frontend Styling:** Tailwind CSS 3.4
-   **Database:** MySQL/MariaDB
-   **JavaScript Framework:** Alpine.js (untuk interaktivitas UI)
-   **Integrasi Pihak Ketiga:**
    -   Google Analytics Data API v1 (Analitik situs)
    -   Google Gemini API (Saran Tag Berita berbasis AI dengan fallback model)
    -   Dompdf (Generasi PDF)
    -   FontAwesome 6 (Ikon, terintegrasi secara lokal)
-   **Development Tools:** Composer, Node.js, npm.

---

&copy; 2026 Diskominfo-SP Kabupaten Sinjai. Dikembangkan untuk transparansi informasi publik.
