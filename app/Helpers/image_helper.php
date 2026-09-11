<?php

if (!function_exists('processImage')) {
    function processImage($file, $fit = true)
    {
        if (empty($file) || !file_exists($file)) {
            log_message('error', '[processImage] Input file does not exist: ' . ($file ?: 'null'));
            return null;
        }

        // Verify that the file is indeed a valid image
        $imageInfo = @getimagesize($file);
        if ($imageInfo === false) {
            log_message('error', '[processImage] File is not a valid image: ' . $file);
            return null;
        }

        try {
            $image = \Config\Services::image()
                ->withFile($file);
                
            if ($fit) {
                $image->fit(1200, 630, 'center');
            }

            $uploadDir = WRITEPATH . 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $ext = 'jpg';
            }

            $tempPath = $uploadDir . uniqid() . '.' . $ext;

            $quality = 85;
            do {
                $image->save($tempPath, $quality);
                $fileSize = @filesize($tempPath);
                $quality -= 10;
            } while ($fileSize > 120 * 1024 && $quality >= 30);

            return $tempPath;
        } catch (\Throwable $e) {
            log_message('error', '[processImage] Error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('generateOgImage')) {
    /**
     * Generate Open Graph image (1200x630, JPG, optimized under 200KB)
     */
    function generateOgImage($sourcePath, $targetPath)
    {
        if (empty($sourcePath) || !file_exists($sourcePath)) {
            return false;
        }

        try {
            $dir = dirname($targetPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $image = \Config\Services::image()
                ->withFile($sourcePath);

            // Fit to 1200x630 landscape
            $image->fit(1200, 630, 'center');

            // Save with progressive quality reduction to strictly stay below 180KB
            // (WhatsApp crawlers reject large cards > ~200KB, 180KB gives safe headroom)
            $quality = 75;
            do {
                $image->save($targetPath, $quality);
                $fileSize = @filesize($targetPath);
                $quality -= 10;
            } while ($fileSize > 180 * 1024 && $quality >= 30);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[generateOgImage] Error: ' . $e->getMessage());
            return false;
        }
    }
}