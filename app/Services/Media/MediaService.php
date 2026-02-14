<?php

namespace App\Services\Media;

use App\Services\BaseService;
use CodeIgniter\HTTP\Files\UploadedFile;

class MediaService extends BaseService
{
    public function __construct()
    {
        helper(['image']);
    }

    /**
     * Store image and return strictly the relative path for DB.
     * Path format: uploads/{domain}/YYYY/MM/{random}.webp
     */
    public function saveImage($source, string $domain = 'posts', bool $fit = true): ?string
    {
        if (empty($source)) return null;

        try {
            // 1. Path Generation (Relative for DB, Absolute for FS)
            $subFolder = $domain . '/' . date('Y') . '/' . date('m');
            $relativeDir = 'uploads/' . $subFolder;
            $absoluteDir = FCPATH . $relativeDir;

            if (!is_dir($absoluteDir)) {
                mkdir($absoluteDir, 0755, true);
            }

            // 2. Secure Random Naming
            $fileName = bin2hex(random_bytes(10)) . '.webp';
            $absolutePath = $absoluteDir . '/' . $fileName;
            $relativePath = $relativeDir . '/' . $fileName;

            // 3. Process Logic
            $tempPath = null;
            if ($source instanceof UploadedFile) {
                if (!$source->isValid()) {
                    throw new \RuntimeException($source->getErrorString());
                }
                // Use the existing processImage helper (should return a temp path to a webp file)
                $tempPath = processImage($source->getRealPath(), $fit);
            } elseif (is_string($source) && strpos($source, 'data:image') === 0) {
                // Handle Base64 pasted images
                $parts = explode(',', $source);
                $data = base64_decode($parts[1]);
                $tempPath = WRITEPATH . 'cache/' . uniqid() . '.webp';
                file_put_contents($tempPath, $data);
                
                // Process the temp file to ensure it's valid webp/resized
                $processed = processImage($tempPath, $fit);
                if ($processed !== $tempPath) {
                    @unlink($tempPath);
                    $tempPath = $processed;
                }
            }

            // 4. Final Move
            if ($tempPath && file_exists($tempPath)) {
                if (rename($tempPath, $absolutePath)) {
                    return $relativePath; // e.g. "uploads/posts/2026/02/abcd.webp"
                }
                @unlink($tempPath);
            }

        } catch (\Exception $e) {
            log_message('error', '[MediaService] ' . $e->getMessage());
            $this->setError("Gagal memproses gambar: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Delete image from filesystem using relative path
     */
    public function deleteImage(?string $relativePath): void
    {
        if (empty($relativePath)) return;

        // Ensure we only have the relative path (strip base_url if accidentally passed)
        $cleanPath = $relativePath;
        if (strpos($relativePath, 'http') === 0) {
            $cleanPath = ltrim(parse_url($relativePath, PHP_URL_PATH), '/');
            // If the app is in a subfolder, we might need more logic here, 
            // but since we aim to store relative paths, this is a safety fallback.
        }

        $fullPath = FCPATH . $cleanPath;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    /**
     * Specialized wrapper for TinyMCE uploads
     */
    public function uploadImage($file): ?string
    {
        return $this->saveImage($file, 'posts', false);
    }
}