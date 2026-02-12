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
        try {
            $fileName = uniqid() . '.webp';
            $destDir = FCPATH . 'uploads/' . $folder;
            $destPath = $destDir . '/' . $fileName;

            if (!is_dir($destDir)) {
                if (!mkdir($destDir, 0755, true)) {
                    log_message('error', "[MediaService] Failed to create directory: $destDir");
                    return null;
                }
            }

            $tempPath = null;

            if ($source instanceof FileUpload) {
                if (!$source->isValid()) {
                    log_message('error', '[MediaService] Upload invalid: ' . $source->getErrorString() . ' (' . $source->getError() . ')');
                    return null;
                }
                if ($source->hasMoved()) {
                    log_message('error', '[MediaService] Upload already moved');
                    return null;
                }
                $tempPath = processImage($source->getRealPath(), $fit);
            } elseif (is_string($source) && strpos($source, 'data:image') === 0) {
                // Handle base64 pasted image
                $parts = explode(',', $source);
                if (count($parts) < 2) {
                    log_message('error', '[MediaService] Invalid base64 format');
                    return null;
                }
                $data = base64_decode($parts[1]);
                
                $tempPath = WRITEPATH . 'uploads/' . uniqid() . '.webp';
                if (file_put_contents($tempPath, $data) === false) {
                    log_message('error', '[MediaService] Failed to write base64 data to temp file: ' . $tempPath);
                    return null;
                }
                
                $processedPath = processImage($tempPath, $fit);
                if ($processedPath !== $tempPath) {
                    @unlink($tempPath);
                }
                $tempPath = $processedPath;
            } else {
                log_message('error', '[MediaService] Source is neither FileUpload nor data:image string');
                return null;
            }

            if ($tempPath && file_exists($tempPath)) {
                $moved = @rename($tempPath, $destPath);
                
                if (!$moved) {
                    log_message('debug', "[MediaService] Rename failed, trying copy for $tempPath to $destPath");
                    if (@copy($tempPath, $destPath)) {
                        @unlink($tempPath);
                        $moved = true;
                    }
                }

                if ($moved) {
                    return base_url('uploads/' . $folder . '/' . $fileName);
                }
                
                log_message('error', "[MediaService] Failed to move file to destination: $destPath");
                @unlink($tempPath);
            } else {
                log_message('error', '[MediaService] processImage return path does not exist: ' . ($tempPath ?: 'null'));
            }
        } catch (\Exception $e) {
            log_message('error', '[MediaService] Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        }

        return null;
    }

    /**
     * Delete an existing image file
     */
    public function deleteImage(?string $url)
    {
        if (empty($url)) return;

        // Strip base_url if present
        $path = $url;
        if (strpos($url, 'http') === 0) {
            $path = ltrim(parse_url($url, PHP_URL_PATH), '/');
        }
        
        $fullPath = FCPATH . $path;
        if (file_exists($fullPath) && is_file($fullPath)) {
            @unlink($fullPath);
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
            if ($file->move($destDir, $newName)) {
                return base_url('uploads/' . $folder . '/' . $newName);
            }
        }
        return null;
    }
}