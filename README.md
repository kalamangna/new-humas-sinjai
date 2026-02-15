# Humas Sinjai - Portal Berita Resmi Pemerintah Kabupaten Sinjai

Aplikasi web portal berita dan informasi publik untuk Humas Sinjai, dibangun dengan arsitektur modern menggunakan framework CodeIgniter 4 dan Tailwind CSS. Platform ini dirancang untuk desiminasi informasi pembangunan, agenda pemerintah, dan pelayanan publik secara cepat dan transparan.

## Fitur Utama

### 🌐 Portal Publik (Front-end)

- **Beranda Informatif:** Menampilkan **Berita Terkini**, **Topik Populer**, dan Carousel Banner visual tinggi.
- **Indeks Berita:** Manajemen konten berita dengan kategorisasi sistematis dan sistem label (Tag).
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
- **Manajemen Konten (CMS):** Kontrol penuh atas Berita (Posting), Kategori, Label, Profil Pejabat, dan Slide Carousel.
- **Pengaturan Situs Terpusat:** Kelola identitas situs (Nama, Logo, Tagline), Kontak (Email, Alamat, Maps), Media Sosial, dan Informasi Lembaga (Visi & Misi) langsung dari panel admin tanpa menyentuh kode.
- **Sistem Keamanan:** Otentikasi multi-role (Admin & Penulis) dengan manajemen profil pengguna yang aman.

### ⚙️ UI/UX & Konfigurasi Admin

- **Sidebar Pintar:** Sidebar responsif dengan fitur toggle (Expand/Collapse) pada tampilan desktop untuk area kerja yang lebih luas. Status sidebar tersimpan secara otomatis di browser (*localStorage*).
- **Standarisasi Terminologi:** Penggunaan istilah yang konsisten dan profesional (Berita, Penulis, Konsep, Terbitkan) di seluruh modul admin untuk memudahkan operasional.
- **Antarmuka Modern:** Desain berbasis kartu (Card-based) yang bersih dengan navigasi yang intuitif dan responsif di berbagai perangkat.

## Teknologi Utama

- **Framework:** CodeIgniter 4.4+ (PHP 8.1+)
- **Styling:** Tailwind CSS 3.4
- **Database:** MySQL/MariaDB
- **Integrasi:** Google Analytics Data API v1, Dompdf (PDF Generation), FontAwesome 6.

## Pengembangan

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & npm (untuk build CSS)

### Instalasi
1. Clone repositori:
   ```bash
   git clone https://github.com/kalamangna/humas-sinjai.git
   ```
2. Instal dependensi PHP:
   ```bash
   composer install
   ```
3. Instal dependensi JS:
   ```bash
   npm install
   ```
4. Konfigurasi environment:
   Salin `env` ke `.env` dan sesuaikan pengaturan database serta API Google Analytics.
5. Jalankan migrasi dan seeder:
   ```bash
   php spark migrate
   php spark db:seed SiteSettingsSeeder
   ```

### Build CSS (Tailwind)
Untuk melakukan build CSS produksi (minified):
```bash
npm run build
```

---
&copy; 2026 Diskominfo-SP Kabupaten Sinjai. Dikembangkan untuk transparansi informasi publik.