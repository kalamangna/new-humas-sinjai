-- =========================================================================
-- DATABASE UPDATES - 2026-04-24
-- =========================================================================
-- This file contains the structural and data modifications executed via
-- CodeIgniter migrations and seeders today.
-- =========================================================================

-- 1. Structural Changes (from MakeProfileSlugNullable Migration)
-- Make the slug column nullable to allow empty slugs for regional profiles
ALTER TABLE profiles MODIFY COLUMN slug VARCHAR(255) NULL;

-- 2. Data Standardization (from UpdateKepalaDesaData Migration)
-- Set position to 'Kepala Desa' and strip 'Desa ' prefix from institution
UPDATE profiles 
SET position = 'Kepala Desa', 
    institution = TRIM(LEADING 'Desa ' FROM institution) 
WHERE type = 'kepala-desa';

-- 3. Data Standardization (from UpdateLurahDataFormat Migration)
-- Extract Kelurahan name from position into institution, and normalize position
UPDATE profiles 
SET institution = TRIM(SUBSTRING(position, 7)), 
    position = 'Lurah' 
WHERE type = 'lurah' AND position LIKE 'Lurah %';

-- 4. Slug Policy Enforcement (from NullifySlugsForOtherTypesSeeder)
-- Remove slugs for all profiles except the top-level officials
UPDATE profiles 
SET slug = NULL 
WHERE type NOT IN ('bupati', 'wakil-bupati', 'sekda');

-- 5. Regional Data Normalization (from NormalizeRegionNamesSeeder & FixSpecificMismatchesSeeder)
-- Sync database regional naming with the remote API format
UPDATE profiles SET institution = 'Sangiasseri' WHERE institution = 'Sangiaseri';
UPDATE profiles SET institution = 'Bulu Kamase' WHERE institution = 'Bulukamase';
UPDATE profiles SET institution = 'Samaturue' WHERE institution = 'Samaturu';
UPDATE profiles SET institution = 'Tongke-Tongke' WHERE institution = 'Tongke-tongke';
UPDATE profiles SET institution = 'Tompobulu' WHERE institution = 'Tompo Bulu';
UPDATE profiles SET institution = 'Lappacinrana' WHERE institution = 'Lappa Cinrana';
UPDATE profiles SET institution = 'Tellu Limpoe' WHERE institution = 'Tellulimpoe';

-- 6. Ordering Policy Enforcement (from ResetProfileOrder Migration)
-- Reset 'order' column to 0 for types that no longer use manual ordering
UPDATE profiles 
SET `order` = 0 
WHERE type IN ('bupati', 'wakil-bupati', 'sekda', 'lurah', 'kepala-desa');
