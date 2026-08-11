<?php

if (!function_exists('processImage')) {
    function processImage($file, $fit = true)
    {
        if (empty($file) || !file_exists($file)) {
            log_message('error', '[processImage] Input file does not exist: ' . ($file ?: 'null'));
            return $file;
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
            return $file;
        }
    }
}

if (!function_exists('generateOgImage')) {
    /**
     * Generate Open Graph image (1200x630, JPG, 75% quality)
     */
    function generateOgImage($sourcePath, $targetPath)
    {
        if (empty($sourcePath) || !file_exists($sourcePath)) {
            return false;
        }

        try {
            $image = \Config\Services::image()
                ->withFile($sourcePath);

            // If original image is portrait, crop center to landscape before resize
            // fit() already handles this by cropping to the specified dimensions from the center.
            $image->fit(1200, 630, 'center');

            // Save as JPG with 75% quality
            $image->save($targetPath, 75);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[generateOgImage] Error: ' . $e->getMessage());
            return false;
        }
    }
}