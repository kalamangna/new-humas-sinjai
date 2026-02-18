# Humas Sinjai

Aplikasi web portal berita dan informasi publik untuk Humas Sinjai, dibangun dengan arsitektur modern menggunakan framework CodeIgniter 4 dan Tailwind CSS.

## 1. Core Functionality
Platform terpusat untuk diseminasi informasi publik, berita, profil pejabat, dan agenda pemerintahan Kabupaten Sinjai. Dilengkapi dasbor administrasi komprehensif untuk manajemen konten dan pemantauan analitik situs.

## 2. Key Features
### Portal Publik (Front-end)
- **Beranda Dinamis:** Menampilkan berita terkini, tag populer, dan integrasi live streaming.
- **Live Streaming:** Radio Suara Bersatu FM dan Sinjai TV dengan deteksi otomatis status siaran.
- **Pencarian Cerdas:** Memudahkan masyarakat menemukan informasi publik secara cepat.
- **Optimasi SEO:** Metadata otomatis untuk visibilitas mesin pencari yang lebih baik.

### Dashboard Admin (Back-end)
- **Analitik GA4:** Pemantauan statistik pengunjung, halaman populer, dan demografi secara real-time.
- **Manajemen Konten:** Kontrol penuh atas berita, kategori, tag, profil pejabat, dan carousel visual.
- **Pelaporan:** Modul pembuatan laporan bulanan dalam format PDF.
- **Sistem Keamanan:** Manajemen user dengan hak akses berbasis role (Admin & Author).

## 3. Technical Stack
- **Backend:** CodeIgniter 4.4+ (PHP 8.1+), MySQL/MariaDB.
- **Frontend:** Tailwind CSS 3.4, Alpine.js.
- **Integrasi:** Google Analytics Data API, Google Gemini API (AI Tagging), Facebook Graph API (Live Detection), Dompdf, FontAwesome 7 (CDN).

## 4. Installation
Pastikan sistem Anda telah memenuhi persyaratan teknis (PHP 8.1 & Node.js).

```bash
# 1. Clone repositori
git clone https://github.com/username/new-humas-sinjai.git
cd new-humas-sinjai

# 2. Instal dependensi PHP
composer install

# 3. Instal dependensi Frontend
npm install

# 4. Setup environment
cp env .env
# Edit .env dan sesuaikan konfigurasi database serta API keys

# 5. Jalankan migrasi dan seeder database
php spark migrate
php spark db:seed SiteSettingsSeeder
```
**Outcome:** Lingkungan pengembangan siap digunakan dengan struktur database yang lengkap.

## 5. Usage
### Pengembangan (Development)
Jalankan server lokal dan pemantau aset frontend:
```bash
# Terminal 1: PHP Server
php spark serve

# Terminal 2: Tailwind CSS Watch
npm run dev
```

### Produksi (Production)
Kompilasi aset untuk performa optimal:
```bash
npm run build
```
**Outcome:** Aplikasi berjalan pada `localhost:8080` dengan sinkronisasi gaya visual yang aktif.

## 6. Configuration
Konfigurasi utama dikelola melalui file `.env`:
- **Database:** `database.default.hostname`, `database.default.database`, dsb.
- **Gemini AI:** `GEMINI_API_KEY` untuk fitur saran tag otomatis.
- **GA4:** `GA4_PROPERTY_ID` untuk sinkronisasi data analitik.
- **Facebook API:** `facebook.page_id` dan `facebook.page_token` untuk deteksi otomatis live streaming Sinjai TV.

## 7. API Endpoints
Aplikasi menyediakan beberapa endpoint internal untuk fungsionalitas dinamis:
- `GET /api/analytics/overview`: Mendapatkan ringkasan statistik kunjungan.
- `POST /api/tags/suggest`: Mendapatkan saran tag berbasis AI untuk konten berita.
- `GET /api/analytics/popular-posts`: Mendapatkan daftar berita paling banyak dilihat.

## 8. Contribution
1. Fork Repositori.
2. Buat branch fitur baru (`git checkout -b feature/FiturKeren`).
3. Commit perubahan Anda (`git commit -m 'feat: Menambah Fitur Keren'`).
4. Push ke branch tersebut (`git push origin feature/FiturKeren`).
5. Buat Pull Request.

## 9. License
Didistribusikan di bawah Lisensi MIT. Lihat file `LICENSE` untuk informasi lebih lanjut.

---
&copy; 2026 Diskominfo-SP Kabupaten Sinjai.