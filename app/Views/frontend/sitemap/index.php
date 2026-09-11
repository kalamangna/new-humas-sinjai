<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url>
        <loc><?= base_url() ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= base_url('posts') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= base_url('categories') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?= base_url('tags') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc><?= base_url('about') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= base_url('contact') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc><?= base_url('program-prioritas') ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?= base_url('live/radio') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
    <url>
        <loc><?= base_url('live/tv') ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>

    <?php if (!empty($profiles)) : ?>
        <?php foreach ($profiles as $profile) : ?>
            <url>
                <loc><?= base_url('profil/' . $profile['slug']) ?></loc>
                <lastmod><?= !empty($profile['updated_at']) ? date('Y-m-d', strtotime($profile['updated_at'])) : date('Y-m-d') ?></lastmod>
                <changefreq>monthly</changefreq>
                <priority>0.6</priority>
            </url>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php foreach ($posts as $post) : ?>
        <url>
            <loc><?= base_url('post/' . ($post['slug'] ?? '')) ?></loc>
            <lastmod><?= date('Y-m-d', strtotime($post['updated_at'] ?? $post['published_at'])) ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            <?php if (!empty($post['thumbnail'])) : ?>
                <image:image>
                    <image:loc><?= filter_var($post['thumbnail'], FILTER_VALIDATE_URL) ? esc($post['thumbnail']) : base_url($post['thumbnail']) ?></image:loc>
                    <image:title><?= esc($post['title']) ?></image:title>
                </image:image>
            <?php endif; ?>
        </url>
    <?php endforeach; ?>

    <?php foreach ($categories as $category) : ?>
        <url>
            <loc><?= base_url('category/' . $category['slug']) ?></loc>
            <lastmod><?= date('Y-m-d') ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    <?php endforeach; ?>

    <?php foreach ($tags as $tag) : ?>
        <url>
            <loc><?= base_url('tag/' . $tag['slug']) ?></loc>
            <lastmod><?= date('Y-m-d') ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.4</priority>
        </url>
    <?php endforeach; ?>
</urlset>