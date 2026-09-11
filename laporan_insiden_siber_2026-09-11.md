# LAPORAN INSIDEN SIBER
Target: humas.sinjaikab.go.id (145.79.28.184) | Periode: 10 – 11 September 2026

---

### 1. Ringkasan Insiden

| Parameter | Keterangan |
|---|---|
| **Target Layanan** | Portal Berita Humas Kabupaten Sinjai (`humas.sinjaikab.go.id`) |
| **Alamat IP Server** | `145.79.28.184` |
| **Kategori Insiden** | Akses Ilegal (Unauthorized Access), Upaya Peretasan Web (Defacement), Percobaan Eksekusi Kode Berkas (RCE Attempt), dan Probing SQL Injection |
| **Tingkat Risiko** | Tinggi |
| **Status Penanganan** | Selesai |
| **Waktu Terdeteksi** | 10 September 2026, 19:59 – 20:13 WITA |
| **Waktu Pemulihan** | 11 September 2026, 17:35 WITA |

---

### 2. Kronologi Kejadian

1. **10 September 2026 – 18:29 WITA**:
   Aktivitas publikasi artikel resmi terakhir yang terdata (ID Berita 1193) dilakukan oleh staf Humas Sinjai.
2. **10 September 2026 – 19:59 – 20:13 WITA**:
   - Penyerang memperoleh akses ke sistem melalui akun kredensial penulis/administrator yang rentan atau tertebak.
   - Penyerang menyuntikkan entri konten berita terdegradasi (ID Berita 1194) dengan tag berlabel peretasan `#hacked #malz` (ID Tag 4380, slug `hacked-malz`).
   - Penyerang berulang kali mencoba mengunggah berkas manipulasi (payload GIF/PNG/eksekusi kode) ke fungsi pemrosesan gambar (`processImage`) untuk mendapatkan webshell / Remote Code Execution.
   - Penyerang mengirimkan payload injeksi karakter kutip tunggal pada URL `admin/categories/edit/1%27`.
3. **11 September 2026 – 05:48 – 05:50 UTC (13:48 – 13:50 WITA)**:
   Dilakukan pembaruan kredensial akun pengguna sistem untuk menutup akses penyerang.
4. **11 September 2026 – 17:28 – 17:35 WITA**:
   Ditemukan sisa tag `#hacked #malz` di basis data produksi. Dilakukan audit menyeluruh, penghapusan tag ID 4380, serta pembersihan skrip sementara pada server.

---

### 3. Analisis Forensik & Vektor Serangan

#### A. Vektor Akses Awal (Initial Access)
- Penyerang tidak membobol enkripsi kata sandi secara langsung, melainkan mengeksploitasi kata sandi seeder awal/lemah yang belum diperbarui pada sistem produksi (`admin` / `humas`).
- Akses ini memungkinkan penyerang masuk melalui panel autentikasi `/masuk`.

#### B. Upaya Eksekusi Kode Jarak Jauh (Remote Code Execution Attempt)
- Log server mencatat kegagalan serial pada parser GD:
  - `imagecreatefromgif(): "/tmp/phpgPgEQq" is not a valid GIF file`
  - `imagepalettetotruecolor(): Argument #1 ($image) must be of type GdImage, false given`
  - `The supplied file is not a supported image type.`
- **Hasil**: Gagal. Sistem penanganan berkas memverifikasi validitas gambar dan mengonversi format ke WebP acak (`bin2hex(random_bytes(10)).webp`), sehingga berkas payload berbahaya tidak dapat tersimpan sebagai berkas skrip executable (`.php`).

#### C. Probing SQL Injection
- Permintaan: `GET admin/categories/edit/1%27`
- **Hasil**: Diblokir secara langsung oleh filter router framework CodeIgniter 4 (`BadRequestException: Disallowed characters: "1'"`). Tidak terjadi kebocoran data melalui injeksi URI.

#### D. Dampak Peretasan (Defacement)
- Penyerang berhasil mengaitkan tag `#hacked #malz` pada sebuah artikel.
- Artikel manipulasi (ID 1194) telah terhapus, namun menyisakan entri *orphan tag* (ID 4380) yang sempat dapat diakses pada rute `v1/tag/hacked-malz`.

---

### 4. Hasil Audit Integritas Sistem

1. **Integritas Direktori Web (`public/` & `uploads/`)**:
   - Pemindaian berkas membuktikan **tidak ada backdoor atau webshell** (`.php`, `.phtml`, `.phar`, `.cgi`, `.sh`) yang tertanam di seluruh subfolder `uploads/`.
   - Seluruh berkas baru pada direktori `uploads/` terkonfirmasi hanya berupa cache WebP dan aset gambar valid.
2. **Integritas Basis Data (`u784396033_humas2db`)**:
   - Tag peretasan ID 4380 telah dihapus total. Relasi pada tabel `post_tags` bernilai 0.
   - Tidak ditemukan akun pengguna liar. Akun pengguna aktif hanya 2: `admin` dan `humas`.
   - Nilai konfigurasi sistem pada tabel `site_settings` bersih dari injeksi kode (`<script>`, `<iframe>`).
3. **Pembersihan Berkas Sisa**:
   - 7 berkas skrip diagnostik pada direktori root FTP telah dibersihkan sepenuhnya.

---

### 5. Tindakan Penanggulangan yang Telah Diterapkan

1. **Rotasi Kredensial Pengguna**: Seluruh akun administratif dan penulis telah diperbarui kata sandinya.
2. **Eliminasi Jejak Defacement**: Pembersihan entri tag manipulasi dan artikel terkait dari basis data produksi.
3. **Pembersihan Server**: Seluruh berkas sementara pengujian di server telah dimusnahkan.
4. **Implementasi Audit Logging**: Tabel dan modul `audit_logs` telah aktif di produksi untuk mencatat alamat IP, agen pengguna, dan setiap tindakan login serta manipulasi data.

---

### 6. Rekomendasi Penguatan Keamanan Lanjutan

1. **Proteksi Direktori Unggahan (`uploads/.htaccess`)**:
   Mencegah eksekusi skrip PHP apa pun di dalam folder berkas statis dengan menonaktifkan handler:
   ```apache
   <FilesMatch "\.(php|phtml|phar|pl|py|jsp|asp|htm|html|shtml|sh|cgi)$">
       Order Allow,Deny
       Deny from all
   </FilesMatch>
   php_flag engine off
   ```
2. **Pembatasan Akses Halaman Masuk**:
   Menerapkan filter pembatasan IP (IP Whitelist) atau proteksi autentikasi lapis kedua (HTTP Basic Auth / Cloudflare Access / Turnstile Captcha) pada rute `/masuk` dan `/admin/*`.
3. **Kebijakan Kata Sandi Kuat**:
   Menghapus akun default seeder pada basis data produksi dan memberlakukan validasi minimal 12 karakter alfanumerik serta simbol.

---

CSIRT-SINJAIKAB • Tim Tanggap Insiden Siber Kabupaten Sinjai
