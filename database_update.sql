-- --------------------------------------------------------
-- Database Update: Humas Sinjai
-- Generated: 2026-02-16
-- --------------------------------------------------------

-- 1. Create site_settings table
CREATE TABLE IF NOT EXISTS `site_settings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(100) NOT NULL,
    `value` TEXT NULL,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `label` VARCHAR(255) NULL,
    `created_at` DATETIME NULL,
    `updated_at` DATETIME NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Insert initial site settings
INSERT INTO `site_settings` (`key`, `value`, `group`, `type`, `label`, `created_at`, `updated_at`) VALUES
('site_name', 'Humas Sinjai', 'general', 'text', 'Nama Situs', NOW(), NOW()),
('site_tagline', 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki', 'general', 'text', 'Tagline Situs', NOW(), NOW()),
('site_logo', 'humas.png', 'general', 'image', 'Logo Situs', NOW(), NOW()),
('developer_name', 'Diskominfo-SP Sinjai', 'general', 'text', 'Nama Pengembang', NOW(), NOW()),
('contact_address', 'Jl. Persatuan Raya No. 101, Balangnipa, Sinjai Utara, Sinjai, Sulawesi Selatan 92612', 'contact', 'textarea', 'Alamat Kantor', NOW(), NOW()),
('contact_email', 'humas@sinjaikab.go.id', 'contact', 'text', 'Email Resmi', NOW(), NOW()),
('contact_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15914.868748882522!2d120.25206256860015!3d-5.12061324749323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db951010072b7a9%3A0x8WX2QetWMMaGdYiw5!2sBalangnipa%2C%20Sinjai%20Utara%2C%20Sinjai%20Regency%2C%20South%20Sulawesi!5e0!3m2!1sen!2sid!4v1715600000000!5m2!1sen!2sid', 'contact', 'textarea', 'Embed Google Maps', NOW(), NOW()),
('contact_map_link', 'https://maps.app.goo.gl/8WX2QetWMMaGdYiw5', 'contact', 'text', 'Link Navigasi Maps', NOW(), NOW()),
('social_facebook', 'https://www.facebook.com/FP.KabupatenSinjai', 'social', 'text', 'Facebook URL', NOW(), NOW()),
('social_instagram', 'https://www.instagram.com/sinjaikab', 'social', 'text', 'Instagram URL', NOW(), NOW()),
('social_youtube', 'https://www.youtube.com/@SINJAITV', 'social', 'text', 'YouTube URL', NOW(), NOW()),
('social_tiktok', 'https://www.tiktok.com/@pemkabsinjai', 'social', 'text', 'TikTok URL', NOW(), NOW()),
('social_twitter', 'https://x.com/sinjaikab', 'social', 'text', 'Twitter/X URL', NOW(), NOW()),
('about_vision', 'Menjadi pusat informasi yang terpercaya, transparan, dan akurat dalam mendukung pembangunan serta pelayanan publik di Kabupaten Sinjai.', 'about', 'textarea', 'Visi Lembaga', NOW(), NOW()),
('about_mission', '["Menyediakan informasi publik yang cepat, akurat, dan mudah diakses oleh masyarakat.","Memperkuat komunikasi dua arah antara pemerintah dan masyarakat.","Mempromosikan potensi daerah dan keberhasilan pembangunan di Kabupaten Sinjai.","Meningkatkan citra positif pemerintah daerah melalui transparansi dan profesionalisme komunikasi publik."]', 'about', 'json', 'Misi Lembaga (JSON)', NOW(), NOW()),
('about_description_1', 'Bagian Hubungan Masyarakat (Humas) Pemerintah Kabupaten Sinjai merupakan unit kerja strategis yang bertanggung jawab dalam pengelolaan informasi dan komunikasi publik secara komprehensif. Kami hadir sebagai jembatan utama antara pemerintah dan masyarakat dalam menyampaikan berbagai informasi krusial seputar kebijakan, program strategis, serta realisasi kegiatan pembangunan di seluruh wilayah Kabupaten Sinjai.', 'about', 'textarea', 'Deskripsi Profil 1', NOW(), NOW()),
('about_description_2', 'Melalui berbagai kanal komunikasi modern, mulai dari media sosial, portal web resmi, hingga kegiatan sosialisasi tatap muka, Humas Sinjai berkomitmen menjadi garda terdepan dalam menyebarluaskan informasi publik yang konstruktif dan inspiratif bagi seluruh lapisan masyarakat Sinjai demi terwujudnya tata kelola pemerintahan yang baik (Good Governance).', 'about', 'textarea', 'Deskripsi Profil 2', NOW(), NOW()),
('about_tasks', '[["Pengelolaan Informasi", "Mengumpulkan, mengolah, and menyebarluaskan informasi resmi pemerintah."],["Media Relations", "Menjalin hubungan profesional dengan media massa nasional dan lokal."],["Publikasi Daerah", "Mempublikasikan berbagai kegiatan strategis dan prestasi pemerintah daerah."],["Dokumentasi Visual", "Mendokumentasikan seluruh kegiatan pemerintah melalui foto and video profesional."],["Manajemen Isu", "Menangani isu publik and menyampaikan klarifikasi resmi demi reputasi daerah."]]', 'about', 'json', 'Tugas Pokok & Fungsi (JSON)', NOW(), NOW()),
('about_teams', '[["Kepala Bidang", "Memimpin and mengkoordinasi seluruh kegiatan komunikasi publik.", "fa-user-tie"],["Staf Humas", "Melaksanakan tugas-tugas operasional and layanan informasi publik.", "fa-users"],["Tim Dokumentasi", "Produksi konten visual profesional melalui foto and video.", "fa-camera"]]', 'about', 'json', 'Struktur Organisasi (JSON)', NOW(), NOW());

-- 3. Cleanup: Remove "Aktif" entries
DELETE FROM `categories` WHERE `name` = 'Aktif';
DELETE FROM `tags` WHERE `name` = 'Aktif';

-- 4. Mark migrations as complete
INSERT IGNORE INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(20260215164051, 'App\Database\Migrations\CreateSiteSettingsTable', 'default', 'App', UNIX_TIMESTAMP(), (SELECT MAX(batch)+1 FROM (SELECT batch FROM migrations) AS temp)),
(20260215164825, 'App\Database\Migrations\RemoveFaviconSetting', 'default', 'App', UNIX_TIMESTAMP(), (SELECT MAX(batch) FROM migrations)),
(20260215170704, 'App\Database\Migrations\RemoveAktifEntries', 'default', 'App', UNIX_TIMESTAMP(), (SELECT MAX(batch) FROM migrations));
