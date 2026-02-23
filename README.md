# Humas Sinjai

Portal berita dan informasi publik Kabupaten Sinjai. Dibangun dengan CodeIgniter 4 dan Tailwind CSS.

## Fitur Utama
- **Public Portal:** Berita, pencarian cerdas, dan live streaming (Radio & TV).
- **Admin Dashboard:** Manajemen konten (Berita, Kategori, Tag, Profil), laporan PDF, dan integrasi Google Analytics 4.
- **AI-Powered:** Saran tag otomatis menggunakan Google Gemini API.
- **Optimasi Media:** Pemrosesan gambar otomatis untuk thumbnail dan Open Graph (Social Sharing).

## Tech Stack
- **Backend:** PHP 8.1+ (CodeIgniter 4.4+)
- **Frontend:** Tailwind CSS 3.4, Alpine.js
- **Database:** MySQL / MariaDB
- **Integrasi:** GA4 Data API, Gemini AI, Facebook Graph API, Dompdf

## Instalasi
1. Clone & Install dependensi:
   ```bash
   composer install
   npm install
   ```
2. Setup Environment:
   ```bash
   cp env .env
   # Sesuaikan konfigurasi Database & API Keys di .env
   ```
3. Database Setup:
   ```bash
   php spark migrate
   php spark db:seed SiteSettingsSeeder
   ```

## Penggunaan
- **Development:** 
  ```bash
  php spark serve
  npm run dev
  ```
- **Production Build:**
  ```bash
  npm run build
  ```

---
&copy; 2026 Diskominfo-SP Kabupaten Sinjai.
