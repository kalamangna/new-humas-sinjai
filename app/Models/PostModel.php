<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'content', 'thumbnail', 'thumbnail_caption', 'status', 'user_id', 'published_at', 'views'];
    protected $useTimestamps = true;

    // New methods for fetching related data
    public function getPostCategories(int $postId)
    {
        $postCategoryModel = new \App\Models\PostCategoryModel();
        return $postCategoryModel
            ->select('categories.id, categories.name, categories.slug')
            ->join('categories', 'categories.id = post_categories.category_id')
            ->where('post_id', $postId)
            ->findAll();
    }

    public function getPostTags(int $postId)
    {
        $postTagModel = new \App\Models\PostTagModel();
        return $postTagModel
            ->select('tags.id, tags.name, tags.slug')
            ->join('tags', 'tags.id = post_tags.tag_id')
            ->where('post_id', $postId)
            ->findAll();
    }

    public function withCategoriesAndTags(array $posts)
    {
        foreach ($posts as &$post) {
            if (isset($post['id'])) {
                $post['categories'] = $this->getPostCategories($post['id']);
                $post['tags'] = $this->getPostTags($post['id']);
            } else {
                $post['categories'] = [];
                $post['tags'] = [];
            }
        }
        return $posts;
    }

    public function getPosts($slug = false, $paginate = false, $publishedOnly = true)
    {
        $builder = $this->select('posts.*, users.name as author_name')
            ->join('users', 'users.id = posts.user_id', 'left');

        if ($publishedOnly) {
            $builder->where('posts.status', 'published');
        }

        if ($slug === false) {
            $posts = $paginate
                ? $builder->orderBy('posts.published_at', 'DESC')->paginate(12)
                : $builder->orderBy('posts.published_at', 'DESC')->findAll();

            return $this->addGAData($posts);
        }

        if (!$publishedOnly && is_numeric($slug)) {
            $builder->groupStart()
                ->where('posts.slug', (string)$slug)
                ->orWhere('posts.id', (int)$slug)
            ->groupEnd();
        } else {
            $builder->where('posts.slug', (string)$slug);
        }

        $post = $builder->first();

        if ($post) {
            $postsWithGA = $this->addGAData([$post]);
            return $postsWithGA[0] ?? $post;
        }

        return $post;
    }

    private function addGAData(array $posts): array
    {
        if (empty($posts)) {
            return $posts;
        }

        $slugs = array_column($posts, 'slug');
        $gaModel = new \App\Models\GoogleAnalyticsModel();
        // Pass null for startDate and endDate to use the getViewsBySlug's default (all-time)
        $viewsData = $gaModel->getViewsBySlug($slugs, null, null);

        foreach ($posts as &$post) {
            $slug = $post['slug'] ?? '' ?? null;
            $post['views'] = ($slug && isset($viewsData[$slug])) ? $viewsData[$slug] : 0;
        }

        return $posts;
    }

    public function getPopularPosts(int $limit = 5)
    {
        $gaModel = new \App\Models\GoogleAnalyticsModel();
        try {
            $popularGA = $gaModel->getPopularPosts('2023-07-01', 'today', $limit + 5);
        } catch (\Exception $e) {
            $popularGA = [];
        }
        
        if (!empty($popularGA)) {
            $slugs = [];
            $viewsMap = [];
            foreach ($popularGA as $item) {
                if (preg_match('/\/post\/([^\/\?]+)/', $item['path'], $matches)) {
                    $slug = $matches[1];
                    $slugs[] = $slug;
                    $viewsMap[$slug] = $item['views'];
                }
            }

            if (!empty($slugs)) {
                $posts = $this->whereIn('slug', $slugs)
                    ->where('status', 'published')
                    ->findAll();

                if (!empty($posts)) {
                    foreach ($posts as &$post) {
                        $slug = $post['slug'] ?? '' ?? null;
                        $post['views'] = ($slug && isset($viewsMap[$slug])) ? $viewsMap[$slug] : 0;
                    }

                    usort($posts, function ($a, $b) {
                        return $b['views'] - $a['views'];
                    });

                    return array_slice($posts, 0, $limit);
                }
            }
        }

        // Fallback to local views column if GA fails or returns empty
        return $this->where('status', 'published')
            ->orderBy('views', 'DESC')
            ->orderBy('published_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function getRecentPosts()
    {
        $posts = $this->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->findAll(5);

        return $this->addGAData($posts);
    }

    public function getPostsByMonthYear($month, $year)
    {
        $posts = $this->where('status', 'published')
            ->where('MONTH(published_at)', $month)
            ->where('YEAR(published_at)', $year)
            ->findAll();

        return $this->addGAData($posts);
    }

    public function getDistinctMonths()
    {
        return $this->select('YEAR(published_at) as year, MONTH(published_at) as month')
            ->distinct()
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->findAll();
    }

    public function searchAndAddGAData(string $query): array
    {
        $posts = $this->where('status', 'published')
            ->groupStart()
                ->like('title', $query)
                ->orLike('content', $query)
            ->groupEnd()
            ->orderBy('posts.published_at', 'DESC')
            ->findAll();

        return $this->addGAData($posts);
    }

    /**
     * Optimized Related News Feature
     * Priority: 1. Skor gabungan kecocokan kategori & tag, 2. Fulltext judul, 3. Berita terbaru
     *
     * @param int        $currentId   ID berita yang dikecualikan
     * @param array      $categoryIds Daftar ID kategori berita saat ini
     * @param string     $title       Judul berita untuk fallback fulltext
     * @param int        $limit       Jumlah hasil (default 4)
     * @param array|null $tagIds      Daftar ID tag opsional agar tidak kueri ulang
     * @return array
     */
    public function getRelatedNewsOptimized(int $currentId, array $categoryIds, string $title, int $limit = 4, ?array $tagIds = null)
    {
        $limit = max(1, $limit);
        $cacheKey = "related_news_{$currentId}_{$limit}";
        if ($cached = cache($cacheKey)) {
            return $cached;
        }

        $categoryIds = array_values(array_unique(array_filter(array_map('intval', $categoryIds))));
        if ($tagIds === null) {
            $tagIds = array_column($this->getPostTags($currentId), 'id');
        }
        $tagIds = array_values(array_unique(array_filter(array_map('intval', $tagIds))));

        $relatedIds = [];

        // --- PHASE 1: SKOR GABUNGAN KATEGORI (bobot 3) + TAG (bobot 1) ---
        // Menggabungkan kategori dan tag mencegah tag generik (mis. "Sinjai") mendominasi hasil lintas topik.
        if (!empty($categoryIds) || !empty($tagIds)) {
            $joins = [];
            $scoreParts = [];
            $matchConditions = [];
            $bindings = [];

            if (!empty($categoryIds)) {
                $catPlaceholders = implode(',', array_fill(0, count($categoryIds), '?'));
                $joins[] = "LEFT JOIN (
                    SELECT post_id, COUNT(*) AS cat_matches
                    FROM post_categories
                    WHERE category_id IN ({$catPlaceholders}) AND post_id != ?
                    GROUP BY post_id
                ) cat ON cat.post_id = p.id";
                $scoreParts[] = '(COALESCE(cat.cat_matches, 0) * 3)';
                $matchConditions[] = 'cat.cat_matches IS NOT NULL';
                $bindings = array_merge($bindings, $categoryIds, [$currentId]);
            }

            if (!empty($tagIds)) {
                $tagPlaceholders = implode(',', array_fill(0, count($tagIds), '?'));
                $joins[] = "LEFT JOIN (
                    SELECT post_id, COUNT(*) AS tag_matches
                    FROM post_tags
                    WHERE tag_id IN ({$tagPlaceholders}) AND post_id != ?
                    GROUP BY post_id
                ) tag ON tag.post_id = p.id";
                $scoreParts[] = 'COALESCE(tag.tag_matches, 0)';
                $matchConditions[] = 'tag.tag_matches IS NOT NULL';
                $bindings = array_merge($bindings, $tagIds, [$currentId]);
            }

            $scoreExpr = implode(' + ', $scoreParts);
            $whereMatch = implode(' OR ', $matchConditions);
            $joinSql = implode("\n", $joins);

            $sql = "SELECT p.id, ({$scoreExpr}) AS relevance_score
                    FROM posts p
                    {$joinSql}
                    WHERE p.status = 'published'
                      AND p.id != ?
                      AND ({$whereMatch})
                    ORDER BY relevance_score DESC, p.published_at DESC
                    LIMIT ?";
            $bindings[] = $currentId;
            $bindings[] = $limit;

            $scoredMatches = $this->db->query($sql, $bindings)->getResultArray();
            $relatedIds = array_map('intval', array_column($scoredMatches, 'id'));
        }

        // --- PHASE 2: FULLTEXT SEARCH (Fallback) ---
        // Menggunakan builder terpisah agar kegagalan FULLTEXT tidak membocorkan state ke query berikutnya.
        if (count($relatedIds) < $limit && !empty($title)) {
            try {
                $words = preg_split('/\s+/', strip_tags($title));
                $cleanWords = array_map(fn($w) => preg_replace('/[^\p{L}\p{N}]/u', '', $w), $words);
                $keywords = array_filter($cleanWords, fn($w) => mb_strlen($w) >= 4);
                $cleanKeywords = array_slice(array_values($keywords), 0, 10);
                $booleanQuery = $this->db->escapeString(implode(' ', $cleanKeywords));

                if (!empty($booleanQuery)) {
                    $ftMatches = $this->db->table('posts')
                        ->select('id')
                        ->where('id !=', $currentId)
                        ->where('status', 'published');

                    if (!empty($relatedIds)) {
                        $ftMatches->whereNotIn('id', $relatedIds);
                    }

                    $ftMatches->where("MATCH(title, content) AGAINST('{$booleanQuery}' IN BOOLEAN MODE)", null, false)
                        ->orderBy("MATCH(title, content) AGAINST('{$booleanQuery}' IN BOOLEAN MODE)", 'DESC', false)
                        ->limit($limit - count($relatedIds));

                    $ftIds = array_map('intval', array_column($ftMatches->get()->getResultArray(), 'id'));
                    $relatedIds = array_merge($relatedIds, $ftIds);
                }
            } catch (\Exception $e) {
                log_message('warning', '[getRelatedNewsOptimized] FULLTEXT search failed: ' . $e->getMessage());
            }
        }

        // --- PHASE 3: LATEST NEWS (Final Fallback) ---
        if (count($relatedIds) < $limit) {
            $latestMatches = $this->db->table('posts')
                ->select('id')
                ->where('id !=', $currentId)
                ->where('status', 'published');

            if (!empty($relatedIds)) {
                $latestMatches->whereNotIn('id', $relatedIds);
            }

            $latestMatches->orderBy('published_at', 'DESC')
                ->limit($limit - count($relatedIds));

            $latestIds = array_map('intval', array_column($latestMatches->get()->getResultArray(), 'id'));
            $relatedIds = array_merge($relatedIds, $latestIds);
        }

        $relatedIds = array_values(array_unique(array_filter($relatedIds)));
        if (empty($relatedIds)) {
            return [];
        }

        $finalResults = $this->whereIn('id', $relatedIds)
            ->orderBy('FIELD(id, ' . implode(',', $relatedIds) . ')', '', false)
            ->findAll();

        // Simpan cache selama 1 jam
        cache()->save($cacheKey, $finalResults, 3600);

        return $finalResults;
    }

    /**
     * LEGACY - Keeping for compatibility if needed elsewhere
     */
    public function getRelatedPosts(array $currentPost, int $limit = 6)
    {
        $catIds = array_column($currentPost['categories'] ?? [], 'id');
        return $this->getRelatedNewsOptimized($currentPost['id'], $catIds, $currentPost['title'], $limit);
    }
}