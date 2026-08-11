<?php

if (!function_exists('processImage')) {
    function processImage($file, $fit = true)
    {
        if (empty($file) || !file_exists($file)) {
            log_message('error', '[processImage] Input file does not exist: ' . ($file ?: 'null'));
            return $file;
        }

        try {
            $info = @getimagesize($file);
            if (!$info) return $file;

            $mime = $info['mime'];
            $srcImg = null;

            switch ($mime) {
                case 'image/jpeg':
                    $srcImg = @imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                    $srcImg = @imagecreatefrompng($file);
                    break;
                case 'image/webp':
                    $srcImg = @imagecreatefromwebp($file);
                    break;
                default:
                    return $file;
            }

            if (!$srcImg) return $file;

            $origW = imagesx($srcImg);
            $origH = imagesy($srcImg);

            $targetW = $origW;
            $targetH = $origH;

            if ($fit && ($origW > 1200 || $origH > 630)) {
                $targetW = 1200;
                $targetH = 630;
            }

            $dstImg = imagecreatetruecolor($targetW, $targetH);
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($dstImg, false);
                imagesavealpha($dstImg, true);
            }

            imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $targetW, $targetH, $origW, $origH);

            $uploadDir = WRITEPATH . 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $tempPath = $uploadDir . uniqid() . '.webp';

            // Iteratively reduce quality to meet file size target
            $quality = 80;
            do {
                imagewebp($dstImg, $tempPath, $quality);
                $fileSize = @filesize($tempPath);
                $quality -= 10;
            } while ($fileSize > 120 * 1024 && $quality >= 30);

            imagedestroy($srcImg);
            imagedestroy($dstImg);

            return $tempPath;
        } catch (\Exception $e) {
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