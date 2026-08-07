# Changelog

Semua perubahan penting pada proyek ini dicatat di sini.
Format mengacu pada [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [Unreleased]
### Changed
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
