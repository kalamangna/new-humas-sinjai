# Changelog

Semua perubahan penting pada proyek ini dicatat di sini.
Format mengacu pada [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]

## [2026-09-11]
### Security
- **Authentication**: Mengubah rute login dari `/login` menjadi `/masuk`, serta memblokir akses rute `/login` dan `/auth/login` dengan respon 404 untuk mitigasi serangan otomatis dan *brute force*.
- **Rate Limiting**: Menerapkan CodeIgniter Throttler pada endpoint login (maksimum 5 percobaan per menit per alamat IP) dan regenerasi session ID saat login sukses untuk mencegah *session fixation*.
- **Upload Hardening**: Memperketat validasi berkas pada `Posts::upload_image`, `MediaService`, dan `image_helper.php` dengan pengecekan MIME type dan verifikasi gambar (`getimagesize()`), serta menolak penyimpanan berkas berbahaya.
- **Upload Hardening**: Menambahkan berkas proteksi `public/uploads/.htaccess` untuk mematikan eksekusi script engine PHP (`php_flag engine off`) dan melarang eksekusi script.
- **Access Control (IDOR & BOLA)**: Menambal celah IDOR pada `Users::update_settings` dan memvalidasi kepemilikan artikel pada `Posts::edit`, `update`, dan `delete` sehingga role Author tidak dapat mengedit/menghapus berita milik user lain.
- **Access Control (RBAC)**: Memperbaiki deteksi rute pada `AdminFilter` agar kebal terhadap prefix subdirektori (`/v1/`), serta membatasi akses role Author ke `admin/users` dan `admin/site-settings`.
- **Content Sanitization (Anti-XSS)**: Menambahkan filter sanitasi HTML pada `PostService::savePost` untuk membuang tag berbahaya (`<script>`, `<iframe>`, atribut `onerror`/`onload`) guna mitigasi Stored XSS.
- **Security Headers & Cookies**: Mengaktifkan kembali filter global `secureheaders` (`X-Frame-Options`, `X-Content-Type-Options`), mengaktifkan `Cookie::$secure = true`, dan mengaktifkan `Session::$regenerateDestroy = true`.
- **SSRF & MITM Defense**: Mengalihkan proxy API data pegawai wilayah di `Profiles.php` menggunakan protokol terenkripsi `https://apps.sinjaikab.go.id`.
- **API Endpoint Protection**: Menempatkan seluruh rute `/api/*` (analitik Google Analytics dan saran tag) di bawah proteksi autentikasi `AdminFilter` serta mengembalikan respons HTTP 401 JSON jika diakses tanpa autentikasi.
- **CSRF Protection on TinyMCE**: Mengintegrasikan `images_upload_handler` pada TinyMCE dengan token CSRF, menghapus pengecualian CSRF pada endpoint `admin/posts/upload_image`, dan mengonfigurasi `Security::$regenerate = false` agar formulir berita tetap valid saat melakukan unggahan media AJAX.
- **Input Sanitization & Injection Prevention**: Memperketat penanganan parameter unduh PDF laporan bulanan dengan konversi tipe numerik (`(int)$year`, `(int)$month`) dan sanitasi teks kata kunci pencarian (`strip_tags`) untuk mencegah kegagalan tipe data dan injeksi header.

### Added
- **UI/UX**: Menambahkan logo ASN BerAKHLAK dan EVP ke dalam layout footer secara berdampingan.
- **UI/UX**: Menambahkan blok "Tag Populer" (*Trending Tags*) di Beranda untuk menampilkan 10 tag dengan interaksi terbanyak.
- **UI/UX**: Menambahkan seksi "Berita Terpopuler" di Beranda dengan desain kartu *trending* dan daftar angka.
- **Database**: Menambahkan `ProfileSeeder.php` dan `SinjaiPostSeeder.php` untuk pasokan data pengujian (*dummy data*) yang stabil.

### Changed
- **Performance & Best Practices**: Mengubah pemuatan widget aksesibilitas UserWay menjadi *On-Demand* (hanya dimuat saat tombol diklik pengunjung) dan menghapus injeksi skrip pada halaman login & admin guna mengeliminasi cookie pihak ketiga (*third-party cookies*), menyelesaikan masalah Chrome DevTools, dan menaikkan skor Lighthouse Best Practices.
- **UI/UX**: Menyederhanakan seluruh antarmuka komponen *empty state* pada widget Beranda, widget Halaman Detail, serta lima halaman indeks penuh (Berita, Kategori, Tag, Program, Profil) agar terlihat lebih bersih, profesional, dan menyatu dengan *layout*.
- **UI/UX**: Mengubah teks *footer* dari "Dikembangkan oleh Diskominfo-SP Sinjai" menjadi teks instansi dinamis tanpa awalan ("Dikembangkan oleh").
- **UI/UX**: Mengubah judul seksi "Berita Terpopuler" menjadi "Berita Populer" dan menukar posisinya ke bagian bawah "Program Prioritas".
- **Accessibility**: Meningkatkan aksesibilitas tombol Hero Carousel dengan menambahkan `aria-label` yang deskriptif pada kontrol *prev/next* & indikator slide, serta memperbesar area sentuh (*touch target*) pada indikator.
- **Accessibility**: Meningkatkan rasio kontras warna (*color contrast*) pada elemen mikro (seperti ikon *views* dan teks *empty state*) dari abu-abu muda (`slate-400`) ke gelap (`slate-500`) dan oranye (`orange-600` ke `orange-700`) demi memenuhi standar aksesibilitas Lighthouse.
- **System**: Mengubah respon *endpoint* `upload_image` di `Admin\Posts` agar mengembalikan `JSON` murni tanpa menyertakan pesan *Error HTML* dari server guna mencegah kegagalan *JSON Parse* pada sisi klien (TinyMCE).
- **UI/UX**: Memusatkan markup desain kartu artikel ke komponen tunggal `post_card.php` dan membuang ratusan baris kode ganda (DRY) pada halaman Cari, Tag, Kategori, dan Daftar Berita.
- **UI/UX**: Memindahkan *badge* kategori keluar dari gambar menuju bagian bawah ringkasan teks pada komponen kartu artikel.
- **UI/UX**: Menghapus teks subjudul ganda (eyebrow text) dari atas judul utama pada 12 tampilan halaman untuk menghasilkan tampilan bersih/minimalis.
- **UI/UX**: Mengatur jarak lega antara elemen hero (slider) dengan bagian daftar Berita Terbaru menjadi `pt-16` / `pt-24` di halaman utama.
- **UI/UX**: Memodifikasi `breadcrumb` (navigasi halaman) agar responsif dan bisa membungkus/jatuh ke baris baru (*wrap*) pada layar ponsel dengan menggunakan `flex-wrap` dan `gap`.
- **UI/UX**: Mengubah *tagline* di footer sehingga tidak bertabrakan (duplikat) dengan elemen badge `#samasamaki`.
- **UI/UX**: Memperkecil ukuran judul (`h1`) pada halaman detail berita menjadi lebih proporsional (`text-3xl` hingga `text-5xl`) untuk meningkatkan kenyamanan membaca (readability).
- **UI/UX**: Merombak tampilan Beranda dengan desain *Magazine-Split* (1 Headline utama dan 4 sub-berita di sampingnya) untuk meminimalisir penumpukan elemen visual.
- **UI/UX**: Memperbarui desain *card* pada seksi "Berita Terpopuler" untuk menampilkan *thumbnail* besar (*aspect-video*) dan angka urutan bergaya *glassmorphism*.
- **UI/UX**: Menyeragamkan identitas visual jumlah *views* pada Berita Terpopuler dengan menggunakan ikon mata (`fa-eye`) dan warna oranye terang (`text-orange-600`) agar selaras dengan desain *widget* di halaman detail.
- **UI/UX**: Menyeragamkan seluruh desain *Header* halaman dalam (Indeks Kategori, Detail Kategori, Hasil Pencarian, dll) dengan struktur rata kiri dan aksen garis biru yang seragam dengan Beranda.
- **UI/UX**: Merombak komponen navigasi *Pagination* menjadi gaya *Clean Corporate Flat* tanpa efek layang berlebih agar sejalan dengan identitas visual korporat.
- **UI/UX**: Memastikan elemen struktur *Footer* secara konsisten menerapkan perataan kiri di semua dimensi layar (terkecuali elemen teks *Copyright* pada *mobile*).
- **System**: Menaikkan batas paginasi baris dari 10 menjadi 12 *post* per halaman agar tampilan katalog 3-kolom, 2-kolom, maupun 1-kolom selalu proporsional dan tidak berlubang.
- **System**: Mengubah fungsi `getPopularPosts` di `PostModel` untuk memiliki *fallback* data sekunder dari kolom *views* lokal apabila API Google Analytics gagal mengembalikan data atau artikel tidak ditemukan (meningkatkan stabilitas *Production*).
- **System**: Menambahkan mekanisme *buffering* pada permintaan API Google Analytics untuk Berita Terpopuler (`$limit + 5`) guna mencegah berkurangnya jumlah berita akibat ketidakcocokan data di *database*.
- **System**: Membuka blokir pada kolom `views` di `allowedFields` milik `PostModel` agar nilai popularitas simulasi dari seeder dapat tersimpan secara lokal.
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
