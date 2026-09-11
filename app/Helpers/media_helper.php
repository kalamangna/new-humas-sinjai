<?php

/**
 * Media Helper - Handles prioritized image path resolution
 */

if (!function_exists('getOgImage')) {
    /**
     * Resolves Open Graph image with folder priority
     * Automatically generates dedicated 1200x630 OG image (<200KB) on first access if missing
     */
    function getOgImage(?string $filename, ?string $fallbackPath = null): string
    {
        $priority = [
            'uploads/og/',
            'uploads/posts/',
            'uploads/thumbnails/'
        ];

        $url = resolve_media_url($filename, $priority, '');

        if ($url !== '') {
            return $url;
        }

        // On-the-fly generation: if OG image is missing, generate it immediately from fallback thumbnail
        if ($fallbackPath && $filename) {
            helper(['image']);
            $sourceLocal = resolve_local_media_path($fallbackPath);
            if ($sourceLocal && file_exists($sourceLocal) && function_exists('generateOgImage')) {
                $ogFilename = pathinfo($filename, PATHINFO_BASENAME);
                $targetOg = FCPATH . 'uploads/og/' . $ogFilename;
                if (generateOgImage($sourceLocal, $targetOg)) {
                    return base_url('uploads/og/' . $ogFilename);
                }
            }
        }

        if ($fallbackPath) {
            $url = resolve_media_url($fallbackPath, $priority, '');
        }

        return $url ?: base_url('meta.png');
    }
}

if (!function_exists('getThumbnailImage')) {
    /**
     * Resolves Thumbnail image with folder priority
     */
    function getThumbnailImage(?string $filename): string
    {
        $priority = [
            'uploads/thumbnails/',
            'uploads/posts/'
        ];

        return resolve_media_url($filename, $priority, 'meta.png');
    }
}

if (!function_exists('resolve_media_url')) {
    /**
     * Internal logic to find existing file across multiple root directories
     */
    function resolve_media_url(?string $filename, array $roots, string $fallback): string
    {
        if (empty($filename)) {
            return $fallback ? base_url($fallback) : '';
        }

        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        // Strip existing upload root prefixes to isolate the relative path/name
        $cleanPath = preg_replace('/^uploads\/(thumbnails|posts|og)\//', '', $filename);
        $basename = pathinfo($cleanPath, PATHINFO_BASENAME);

        foreach ($roots as $root) {
            $root = rtrim($root, '/') . '/';
            
            // 1. Try path with folders (structured: YYYY/MM/name.ext)
            if (is_file(FCPATH . $root . $cleanPath)) {
                return base_url($root . $cleanPath);
            }
            
            // 2. Try just the basename (flat: name.ext)
            if (is_file(FCPATH . $root . $basename)) {
                return base_url($root . $basename);
            }
        }

        return $fallback ? base_url($fallback) : '';
    }
}

if (!function_exists('resolve_local_media_path')) {
    /**
     * Resolves the absolute local filesystem path for a media file
     */
    function resolve_local_media_path(?string $filename, array $roots = ['uploads/posts/', 'uploads/thumbnails/', 'uploads/']): ?string
    {
        if (empty($filename) || filter_var($filename, FILTER_VALIDATE_URL)) {
            return null;
        }

        $cleanPath = preg_replace('/^uploads\/(thumbnails|posts|og)\//', '', ltrim($filename, '/'));
        $basename  = pathinfo($cleanPath, PATHINFO_BASENAME);

        foreach ($roots as $root) {
            $root = rtrim($root, '/') . '/';
            if (is_file(FCPATH . $root . $cleanPath)) {
                return FCPATH . $root . $cleanPath;
            }
            if (is_file(FCPATH . $root . $basename)) {
                return FCPATH . $root . $basename;
            }
        }

        if (is_file(FCPATH . ltrim($filename, '/'))) {
            return FCPATH . ltrim($filename, '/');
        }

        return null;
    }
}

if (!function_exists('getOptimizedImageUrl')) {
    /**
     * Resolves an optimized, resized WebP image URL, generating it automatically on first access.
     *
     * @param string|null $path Relative or absolute path / URL to image
     * @param int $maxWidth Max width to downscale (preserves aspect ratio)
     * @param int $quality WebP quality (default 80)
     * @return string URL to the optimized WebP image, or original URL if not optimizable
     */
    function getOptimizedImageUrl(?string $path, int $maxWidth = 1280, int $quality = 80): string
    {
        if (empty($path)) {
            return '';
        }

        // 1. If it's a full URL, extract path if it belongs to our domain
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($path);
            $imgHost = $parsedUrl['host'] ?? '';
            $siteHost = parse_url(base_url(), PHP_URL_HOST);

            if ($imgHost && ($siteHost === null || strcasecmp($imgHost, $siteHost) === 0 || strpos($imgHost, 'sinjaikab.go.id') !== false)) {
                $path = $parsedUrl['path'] ?? $path;
            } else {
                return $path;
            }
        }

        // 2. Normalize path (strip leading slash and v1/ subdirectory prefix)
        $cleanPath = ltrim($path, '/');
        $cleanPath = preg_replace('#^v1/#i', '', $cleanPath);

        $sourceFile = FCPATH . $cleanPath;
        if (!is_file($sourceFile)) {
            return base_url($cleanPath);
        }

        $ext = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));
        $filename = pathinfo($sourceFile, PATHINFO_FILENAME);
        $rawSubDir = dirname($cleanPath);
        $subDir = ($rawSubDir === '.' || $rawSubDir === '') ? '' : trim($rawSubDir, '/') . '/';

        // Target cache path: uploads/cache/{subDir}{filename}_w{maxWidth}.webp
        $cacheRelDir = 'uploads/cache/' . rtrim($subDir, '/');
        $cacheRelFile = 'uploads/cache/' . $subDir . $filename . '_w' . $maxWidth . '.webp';
        $cacheAbsDir = FCPATH . $cacheRelDir;
        $cacheAbsFile = FCPATH . $cacheRelFile;

        if (is_file($cacheAbsFile)) {
            return base_url($cacheRelFile);
        }

        if (function_exists('imagewebp')) {
            try {
                $info = @getimagesize($sourceFile);
                if ($info && !empty($info[0]) && !empty($info[1])) {
                    $srcW = $info[0];
                    $srcH = $info[1];
                    $mime = $info['mime'] ?? '';

                    // If already a WebP and smaller than maxWidth, return original
                    if ($ext === 'webp' && $srcW <= $maxWidth) {
                        return base_url($cleanPath);
                    }

                    if (!is_dir($cacheAbsDir)) {
                        mkdir($cacheAbsDir, 0755, true);
                    }

                    $srcImg = null;
                    switch ($mime) {
                        case 'image/jpeg':
                        case 'image/jpg':
                            $srcImg = @imagecreatefromjpeg($sourceFile);
                            break;
                        case 'image/png':
                            $srcImg = @imagecreatefrompng($sourceFile);
                            break;
                        case 'image/webp':
                            $srcImg = @imagecreatefromwebp($sourceFile);
                            break;
                    }

                    if ($srcImg) {
                        $targetW = min($srcW, $maxWidth);
                        $targetH = (int)round(($srcH / $srcW) * $targetW);

                        $dstImg = imagecreatetruecolor($targetW, $targetH);
                        imagealphablending($dstImg, false);
                        imagesavealpha($dstImg, true);

                        imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $targetW, $targetH, $srcW, $srcH);
                        imagewebp($dstImg, $cacheAbsFile, $quality);

                        imagedestroy($dstImg);
                        imagedestroy($srcImg);

                        if (is_file($cacheAbsFile)) {
                            return base_url($cacheRelFile);
                        }
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', '[getOptimizedImageUrl] ' . $e->getMessage());
            }
        }

        return base_url($cleanPath);
    }
}
