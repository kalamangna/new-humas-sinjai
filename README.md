# Humas Sinjai

Portal berita dan informasi publik modern untuk Kabupaten Sinjai. Platform ini dirancang untuk kecepatan, kemudahan pengelolaan konten, dan integrasi cerdas.

## 🚀 Tech Stack

### Backend & Core
- **Framework:** PHP 8.1+ dengan **CodeIgniter 4.4+**
- **Database:** **MySQL / MariaDB** (Menggunakan Full-text search untuk pencarian berita)
- **Service Layer:** Arsitektur berbasis Service untuk memisahkan logika bisnis dari Controller.
- **Reporting:** **Dompdf** untuk pembuatan laporan dinamis dalam format PDF.

### Frontend & UI
- **Styling:** **Tailwind CSS 3.4** (Optimasi JIT, custom components)
- **Interactivity:** **Alpine.js** untuk komponen UI yang reaktif dan ringan.
- **Asset Pipeline:** **Vite & PostCSS** untuk kompilasi asset yang cepat.
- **Iconography:** FontAwesome 6 (Pro/Solid).

### Smart Integrations
- **AI Intelligence:** **Google Gemini Pro API** untuk saran tag otomatis dan asisten konten.
- **Data Analytics:** **Google Analytics 4 (GA4) Data API** untuk visualisasi statistik langsung di dashboard.
- **Social Sharing:** Integrasi **Facebook Graph API** dan generator gambar Open Graph otomatis.

## ✨ Fitur Unggulan

### 📰 Content Management System (CMS)
- **Manajemen Berita:** Editor rich-text (TinyMCE) dengan dukungan galeri gambar dan caption.
- **Hierarki Kategori:** Pengelolaan kategori induk dan sub-kategori (2-level) dengan validasi pencegahan siklus.
- **Smart Tagging:** Saran tag otomatis berbasis AI untuk meningkatkan SEO.
- **Optimasi Gambar:** Pemrosesan otomatis ukuran thumbnail dan kompresi kualitas (WebP support).

### 📊 Dashboard Admin & Analitik
- **Real-time Stats:** Visualisasi data pengunjung, halaman populer, dan referer langsung dari GA4.
- **Fixed Layout UI:** Antarmuka admin yang responsif dengan sidebar statis untuk produktivitas maksimal.
- **Manajemen User:** Sistem peran (Admin, Author, Streamer) dengan kontrol akses yang ketat.
- **Laporan Otomatis:** Export statistik dan daftar berita ke format PDF yang profesional.

### 🌐 Public Portal
- **Pencarian Cerdas:** Mesin pencari berbasis relevansi teks.
- **Live Streaming:** Integrasi pemutar streaming untuk Radio Sinjai FM dan Sinjai TV.
- **SEO Optimized:** Meta tag dinamis, Open Graph otomatis, dan struktur URL ramah SEO.
- **Responsive Design:** Pengalaman pengguna yang konsisten di perangkat mobile, tablet, dan desktop.

## 🛠️ Instalasi & Pengembangan

### Prasyarat
- PHP 8.1 atau lebih tinggi
- MySQL 5.7 / MariaDB 10.3 atau lebih tinggi
- Composer
- Node.js & NPM

### Langkah-langkah
1. **Clone Repository & Install Dependensi:**
   ```bash
   composer install
   npm install
   ```
2. **Konfigurasi Environment:**
   ```bash
   cp env .env
   # Edit .env: Masukkan DATABASE, app.baseURL, dan API KEYS (Gemini, GA4, FB)
   ```
3. **Migrasi Database:**
   ```bash
   php spark migrate
   php spark db:seed SiteSettingsSeeder
   ```
4. **Jalankan Development Server:**
   ```bash
   # Terminal 1: PHP Server
   php spark serve
   
   # Terminal 2: CSS Watcher
   npm run dev
   ```

### Build Produksi
```bash
npm run build
```

---
&copy; 2026 **Diskominfo-SP Kabupaten Sinjai**.