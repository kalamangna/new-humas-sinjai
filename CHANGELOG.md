# Changelog

Semua perubahan penting pada proyek ini dicatat di sini.
Format mengacu pada [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]
### Added
- **UI/UX**: Menambahkan logo ASN BerAKHLAK dan EVP ke dalam layout footer secara berdampingan.
- **UI/UX**: Menambahkan seksi "Berita Terpopuler" di Beranda dengan desain kartu *trending* dan daftar angka.
- **Database**: Menambahkan `ProfileSeeder.php` dan `SinjaiPostSeeder.php` untuk pasokan data pengujian (*dummy data*) yang stabil.

### Changed
- **UI/UX**: Memusatkan markup desain kartu artikel ke komponen tunggal `post_card.php` dan membuang ratusan baris kode ganda (DRY) pada halaman Cari, Tag, Kategori, dan Daftar Berita.
- **UI/UX**: Memindahkan *badge* kategori keluar dari gambar menuju bagian bawah ringkasan teks pada komponen kartu artikel.
- **UI/UX**: Menghapus teks subjudul ganda (eyebrow text) dari atas judul utama pada 12 tampilan halaman untuk menghasilkan tampilan bersih/minimalis.
- **UI/UX**: Mengatur jarak lega antara elemen hero (slider) dengan bagian daftar Berita Terbaru menjadi `pt-16` / `pt-24` di halaman utama.
- **UI/UX**: Memodifikasi `breadcrumb` (navigasi halaman) agar responsif dan bisa membungkus/jatuh ke baris baru (*wrap*) pada layar ponsel dengan menggunakan `flex-wrap` dan `gap`.
- **UI/UX**: Mengubah *tagline* di footer sehingga tidak bertabrakan (duplikat) dengan elemen badge `#samasamaki`.
- **UI/UX**: Memperkecil ukuran judul (`h1`) pada halaman detail berita menjadi lebih proporsional (`text-3xl` hingga `text-5xl`) untuk meningkatkan kenyamanan membaca (readability).
- **UI/UX**: Merombak Beranda dengan desain *Magazine-Split* (1 Headline utama dan 4 sub-berita di sampingnya) untuk meminimalisir penumpukan elemen visual.
- **UI/UX**: Menyeragamkan seluruh desain *Header* halaman dalam (Indeks Kategori, Detail Kategori, Hasil Pencarian, dll) dengan struktur rata kiri dan aksen garis biru yang seragam dengan Beranda.
- **UI/UX**: Merombak komponen navigasi *Pagination* menjadi gaya *Clean Corporate Flat* tanpa efek layang berlebih agar sejalan dengan identitas visual korporat.
- **UI/UX**: Memastikan elemen struktur *Footer* secara konsisten menerapkan perataan kiri di semua dimensi layar (terkecuali elemen teks *Copyright* pada *mobile*).
- **System**: Menaikkan batas paginasi baris dari 10 menjadi 12 *post* per halaman agar tampilan katalog 3-kolom, 2-kolom, maupun 1-kolom selalu proporsional dan tidak berlubang.
- **Performance**: Mengurangi drastis batasan kompresi *filesize* gambar dari 300KB menjadi 100KB di `image_helper.php` untuk menekan ukuran laman (*network payloads*) dan memperbaiki LCP pada hasil audit Lighthouse.
- **Performance**: Menambahkan `fetchpriority="high"` untuk gambar utama, dan `loading="lazy"` disertai atribut dimensi eksplisit (`width`, `height`) pada gambar logo dan *thumbnail* untuk menghilangkan *Cumulative Layout Shift* (CLS).
- **Accessibility**: Menyempurnakan atribut ARIA pada *navbar* (pencarian, menu) dan kontras warna pada *footer* untuk mencapai skor aksesibilitas Lighthouse 96+.
- **Server/Deployment**: Mengubah struktur deployment production Hostinger. Folder `v1` sekarang merupakan *symlink* yang mengarah langsung ke `new-humas-sinjai/public`. Hal ini memungkinkan perubahan frontend (CSS/JS/Views) langsung live saat GitHub Webhook bekerja tanpa perlu *upload* FTP manual.

## [2026-08-07]

### Fixed
- **GeminiService**: Ganti `getenv('GEMINI_API_KEY')` ke `env('GEMINI_API_KEY')` agar API key terbaca dengan benar di production (CI4 menggunakan helper `env()`, bukan PHP native `getenv()`).
- **GeminiService**: Update `GEMINI_API_KEY` dengan key baru yang valid.
- **GeminiService**: Perbaiki fallback tag hardcoded dari tag generik menjadi 10 tag kontekstual Sinjai (`Sinjai`, `Berita Sinjai`, `Kabupaten Sinjai`, dll.).
- **GeminiService**: Refactor dari 2 model hardcoded (`PRIMARY_MODEL` + `FALLBACK_MODEL`) ke `MODEL_CHAIN` array — iterasi otomatis ke model berikutnya jika gagal. Chain: `gemini-3.6-flash` → `gemini-3.5-flash` → `gemini-2.5-flash` → `gemini-2.5-flash-lite` → `gemini-2.0-flash` → hardcoded fallback.
- **PostModel**: Perbaiki algoritma berita terkait (Phase 3 FULLTEXT) yang selalu fallback ke "berita terbaru". Root cause: MySQL `NATURAL LANGUAGE MODE` mengabaikan kata yang muncul di >50% dokumen (threshold rule), sehingga selalu mengembalikan 0 hasil pada database kecil. Solusi: ganti ke `BOOLEAN MODE` dengan OR logic dan tambahkan `try-catch` agar gracefully fallback ke Phase 4 jika FULLTEXT index belum ada.

### Changed
- **.gitignore**: Tambahkan `FTP.md` agar file konfigurasi FTP tidak ter-commit ke repository.
- **Admin/Posts (new & edit)**: Reset daftar tag sebelum mengisi hasil saran Gemini, agar tag lama tidak tertumpuk dengan tag baru dari API.
