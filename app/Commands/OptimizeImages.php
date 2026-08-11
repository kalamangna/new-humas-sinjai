<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class OptimizeImages extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'image:optimize';
    protected $description = 'Mengompresi dan mengonversi gambar besar yang sudah terlanjur diunggah di folder public/uploads ke format WebP teroptimasi.';

    public function run(array $params)
    {
        CLI::write('Memulai optimasi gambar di folder public/uploads...', 'yellow');

        helper(['image']);
        $uploadPath = FCPATH . 'uploads';

        if (!is_dir($uploadPath)) {
            CLI::error("Folder uploads tidak ditemukan di {$uploadPath}");
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($uploadPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $count = 0;
        $savedBytes = 0;

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;

            $filePath = $file->getRealPath();
            $ext = strtolower($file->getExtension());

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

            $initialSize = filesize($filePath);

            // Lewati jika ukuran file sudah kecil (< 150 KB)
            if ($initialSize < 150 * 1024) continue;

            CLI::write("Mengompres: " . str_replace(FCPATH, '', $filePath) . " (" . round($initialSize / 1024, 2) . " KB)...", 'cyan');

            try {
                $tempOptimized = processImage($filePath, false);

                if ($tempOptimized && file_exists($tempOptimized)) {
                    $newSize = filesize($tempOptimized);

                    if ($newSize < $initialSize) {
                        copy($tempOptimized, $filePath);
                        @unlink($tempOptimized);
                        $diff = $initialSize - $newSize;
                        $savedBytes += $diff;
                        $count++;
                        CLI::write("  -> Berhasil disusutkan menjadi " . round($newSize / 1024, 2) . " KB (Hemat " . round($diff / 1024, 2) . " KB)", 'green');
                    } else {
                        @unlink($tempOptimized);
                        CLI::write("  -> Ukuran optimal sudah dicapai.", 'white');
                    }
                }
            } catch (\Exception $e) {
                CLI::error("  -> Gagal memproses " . $file->getFilename() . ": " . $e->getMessage());
            }
        }

        CLI::newLine();
        CLI::write("Selesai! Total {$count} gambar terkompresi. Total penghematan ruang: " . round($savedBytes / (1024 * 1024), 2) . " MB", 'green');
    }
}
