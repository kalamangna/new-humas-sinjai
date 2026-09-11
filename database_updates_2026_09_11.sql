-- =========================================================================
-- DATABASE UPDATES - 2026-09-11
-- =========================================================================
-- Berkas ini memuat perintah SQL manual untuk sinkronisasi database ke server
-- production (misalnya via phpMyAdmin / Database Manager) tanpa akses terminal.
-- =========================================================================

-- 1. Buat Tabel `audit_logs` (Fitur Audit Log Sistem)
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(5) UNSIGNED DEFAULT NULL,
  `user_name` VARCHAR(100) DEFAULT NULL,
  `user_role` VARCHAR(50) DEFAULT NULL,
  `module` VARCHAR(50) NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_at` (`created_at`),
  KEY `module` (`module`),
  KEY `action` (`action`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Catat Riwayat Migrasi ke Tabel `migrations` (Opsional tapi Direkomendasikan)
-- Menandai migrasi 2026-09-11-083800 sebagai selesai agar sinkron dengan CodeIgniter
INSERT INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`)
SELECT '2026-09-11-083800', 'App\\Database\\Migrations\\CreateAuditLogsTable', 'default', 'App', UNIX_TIMESTAMP(), COALESCE(MAX(`batch`), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` WHERE `version` = '2026-09-11-083800'
);
