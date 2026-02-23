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
                
            $image->convert(IMAGETYPE_WEBP);

            $uploadDir = WRITEPATH . 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tempPath = $uploadDir . uniqid() . '.webp';

            // Iteratively reduce quality to meet file size target
            $quality = 85;
            do {
                $image->save($tempPath, $quality);
                $fileSize = filesize($tempPath);
                $quality -= 5;
            } while ($fileSize > 300 * 1024 && $quality >= 50);

            return $tempPath;
        } catch (\Exception $e) {
            log_message('error', '[processImage] Error: ' . $e->getMessage());
            return $file; // Fallback to original file path if processing fails
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