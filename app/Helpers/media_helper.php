<?php

/**
 * Media Helper - Handles prioritized image path resolution
 */

if (!function_exists('getOgImage')) {
    /**
     * Resolves Open Graph image with folder priority
     * Handles backward compatibility by checking an alternative filename/path
     */
    function getOgImage(?string $filename, ?string $fallbackPath = null): string
    {
        $priority = [
            'uploads/og/',
            'uploads/posts/',
            'uploads/thumbnails/'
        ];

        $url = resolve_media_url($filename, $priority, '');

        if ($url === '' && $fallbackPath) {
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
