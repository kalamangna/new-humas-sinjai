<?php

namespace App\Services\Media;

use CodeIgniter\HTTP\FileUpload;

class MediaService
{
    /**
     * Process and save an image from a FileUpload object or base64 string
     */
    public function saveImage($source, string $folder, bool $fit = true): ?string
    {
        $fileName = uniqid() . '.webp';
        $destDir = FCPATH . 'uploads/' . $folder;
        $destPath = $destDir . '/' . $fileName;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        if ($source instanceof FileUpload) {
            if (!$source->isValid() || $source->hasMoved()) {
                return null;
            }
            $tempPath = processImage($source->getRealPath(), $fit);
        } elseif (is_string($source) && strpos($source, 'data:image') === 0) {
            // Handle base64 pasted image
            list($type, $data) = explode(';', $source);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $tempPath = WRITEPATH . 'uploads/' . uniqid() . '.webp';
            file_put_contents($tempPath, $data);
            $processedPath = processImage($tempPath, $fit);
            unlink($tempPath);
            $tempPath = $processedPath;
        } else {
            return null;
        }

        if (file_exists($tempPath)) {
            $moved = @rename($tempPath, $destPath);
            
            if (!$moved) {
                if (@copy($tempPath, $destPath)) {
                    @unlink($tempPath);
                    $moved = true;
                }
            }

            if ($moved) {
                return base_url('uploads/' . $folder . '/' . $fileName);
            }
            @unlink($tempPath);
        }

        return null;
    }

    /**
     * Delete an existing image file
     */
    public function deleteImage(?string $url)
    {
        if (empty($url)) return;

        $path = FCPATH . ltrim(parse_url($url, PHP_URL_PATH), '/');
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    /**
     * Handle generic image upload (e.g. from editor)
     */
    public function uploadImage(FileUpload $file, string $folder = 'posts'): ?string
    {
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $destDir = FCPATH . 'uploads/' . $folder;
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            $file->move($destDir, $newName);
            return base_url('uploads/' . $folder . '/' . $newName);
        }
        return null;
    }
}