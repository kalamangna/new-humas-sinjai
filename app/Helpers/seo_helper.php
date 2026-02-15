<?php

if (!function_exists('generate_seo_tags')) {
    /**
     * Generates standard SEO meta tags
     */
    function generate_seo_tags(array $data = []): string
    {
        $title = isset($data['title']) ? esc($data['title']) . ' - Humas Sinjai' : 'Humas Sinjai - Portal Berita Resmi Pemerintah Kabupaten Sinjai';
        $description = isset($data['description']) ? esc(limit_char($data['description'], 160)) : 'Portal Berita Resmi Pemerintah Kabupaten Sinjai #samasamaki';
        $keywords = isset($data['keywords']) ? esc($data['keywords']) : 'Humas Sinjai, Berita Sinjai, Sinjai, Pemerintah Kabupaten Sinjai';
        $author = isset($data['author']) ? esc($data['author']) : 'Humas Sinjai';
        $image = isset($data['image']) ? $data['image'] : base_url('meta.png');
        $url = current_url();
        $canonical = rtrim($url, '/');

        $tags = [
            '<title>' . $title . '</title>',
            '<meta name="description" content="' . $description . '">',
            '<meta name="keywords" content="' . $keywords . '">',
            '<meta name="author" content="' . $author . '">',
            '<meta name="image" content="' . $image . '">',
            '<meta name="robots" content="index, follow, max-image-preview:large">',
            '<link rel="canonical" href="' . $canonical . '">',
            '',
            '<!-- Open Graph -->',
            '<meta property="og:title" content="' . $title . '">',
            '<meta property="og:description" content="' . $description . '">',
            '<meta property="og:image" content="' . $image . '">',
            '<meta property="og:url" content="' . $url . '">',
            '<meta property="og:type" content="' . (isset($data['type']) ? $data['type'] : 'website') . '">',
            '<meta property="og:site_name" content="Humas Sinjai">',
            '',
            '<!-- Twitter Card -->',
            '<meta name="twitter:card" content="summary_large_image">',
            '<meta name="twitter:title" content="' . $title . '">',
            '<meta name="twitter:description" content="' . $description . '">',
            '<meta name="twitter:image" content="' . $image . '">',
        ];

        return implode("
    ", $tags);
    }
}

if (!function_exists('limit_char')) {
    function limit_char(string $str, int $limit = 160): string
    {
        $str = strip_tags($str);
        if (strlen($str) <= $limit) {
            return $str;
        }
        return substr($str, 0, $limit - 3) . '...';
    }
}

if (!function_exists('generate_schema_org')) {
    /**
     * Generates Organization JSON-LD
     */
    function generate_schema_org(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Humas Sinjai',
            'url' => base_url(),
            'logo' => base_url('logo.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+62-XXX-XXXX-XXXX',
                'contactType' => 'customer service',
                'areaServed' => 'ID',
                'availableLanguage' => 'Indonesian'
            ],
            'sameAs' => [
                'https://www.facebook.com/FP.KabupatenSinjai',
                'https://www.instagram.com/sinjaikab',
                'https://www.youtube.com/@SINJAITV',
                'https://www.tiktok.com/@pemkabsinjai',
                'https://x.com/sinjaikab'
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}

if (!function_exists('generate_schema_news')) {
    /**
     * Generates NewsArticle JSON-LD
     */
    function generate_schema_news(array $post): string
    {
        $publishedAt = isset($post['published_at']) ? date('c', strtotime($post['published_at'])) : date('c', strtotime($post['created_at']));
        $modifiedAt = isset($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : $publishedAt;
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $post['title'],
            'image' => [
                !empty($post['thumbnail']) 
                    ? (filter_var($post['thumbnail'], FILTER_VALIDATE_URL) ? $post['thumbnail'] : base_url($post['thumbnail'])) 
                    : base_url('meta.png')
            ],
            'datePublished' => $publishedAt,
            'dateModified' => $modifiedAt,
            'author' => [
                [
                    '@type' => 'Person',
                    'name' => $post['author_name'] ?? 'Humas Sinjai',
                    'url' => base_url()
                ]
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Humas Sinjai',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => base_url('logo.png')
                ]
            ],
            'description' => limit_char($post['content'], 160)
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}

if (!function_exists('generate_schema_breadcrumb')) {
    /**
     * Generates BreadcrumbList JSON-LD
     */
    function generate_schema_breadcrumb(array $items): string
    {
        $itemListElement = [];
        $i = 1;
        
        // Always start with Home
        $itemListElement[] = [
            '@type' => 'ListItem',
            'position' => $i++,
            'name' => 'Beranda',
            'item' => base_url()
        ];

        foreach ($items as $name => $url) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $i++,
                'name' => $name,
                'item' => $url
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }
}
