<?php

/**
 * Media Helper - Handles prioritized image path resolution
 */

if (!function_exists('getOgImage')) {
    /**
     * Resolves Open Graph image with folder priority
     */
    function getOgImage(?string $filename): string
    {
        $priority = [
            'uploads/og/',
            'uploads/posts/',
            'uploads/thumbnails/'
        ];

        return resolve_media_url($filename, $priority, 'meta.png');
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
            return base_url($fallback);
        }

        if (filter_var($filename, FILTER_VALIDATE_URL)) {
            return $filename;
        }

        // Strip existing upload root prefixes to isolate the relative path/name
        $cleanPath = preg_replace('/^uploads\/(thumbnails|posts|og)\//', '', $filename);

        foreach ($roots as $root) {
            $targetPath = rtrim($root, '/') . '/' . ltrim($cleanPath, '/');
            if (is_file(FCPATH . $targetPath)) {
                return base_url($targetPath);
            }
        }

        return base_url($fallback);
    }
}
