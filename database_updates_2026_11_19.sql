-- Perombakan kategori profil Eselon II dan Eselon III
-- Menjadi: asisten-staf-ahli, kepala-opd, kabag-setda, camat
-- Jalankan di PhpMyAdmin pada database production (u784396033_humas2db)

-- 1. Perluas ENUM sementara
ALTER TABLE `profiles`
MODIFY COLUMN `type` ENUM(
    'bupati',
    'wakil-bupati',
    'sekda',
    'forkopimda',
    'eselon-ii',
    'eselon-iii',
    'asisten-staf-ahli',
    'kepala-opd',
    'kabag-setda',
    'camat',
    'lurah',
    'kepala-desa'
) NOT NULL;

-- 2. Pindahkan data Eselon II
UPDATE `profiles`
SET `type` = 'asisten-staf-ahli'
WHERE `type` = 'eselon-ii'
  AND (TRIM(`position`) LIKE 'Asisten%' OR TRIM(`position`) LIKE 'Staf Ahli%');

UPDATE `profiles`
SET `type` = 'kepala-opd'
WHERE `type` = 'eselon-ii';

-- 3. Pindahkan data Eselon III
UPDATE `profiles`
SET `type` = 'camat'
WHERE `type` = 'eselon-iii'
  AND TRIM(`position`) LIKE 'Camat%';

UPDATE `profiles`
SET `type` = 'kabag-setda'
WHERE `type` = 'eselon-iii'
  AND TRIM(`position`) LIKE 'Kepala%Bagian%';

UPDATE `profiles`
SET `type` = 'kepala-opd'
WHERE `type` = 'eselon-iii';

-- 4. Hapus tipe lama (eselon-ii, eselon-iii) dari ENUM
ALTER TABLE `profiles`
MODIFY COLUMN `type` ENUM(
    'bupati',
    'wakil-bupati',
    'sekda',
    'forkopimda',
    'asisten-staf-ahli',
    'kepala-opd',
    'kabag-setda',
    'camat',
    'lurah',
    'kepala-desa'
) NOT NULL;
