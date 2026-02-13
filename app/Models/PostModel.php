<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'slug', 'content', 'thumbnail', 'thumbnail_caption', 'status', 'user_id', 'published_at'];
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
                ? $builder->orderBy('posts.published_at', 'DESC')->paginate(10)
                : $builder->orderBy('posts.published_at', 'DESC')->findAll();

            return $this->addGAData($posts);
        }

        $post = $builder->where(['posts.slug' => $slug])->first();

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
        $viewsData = $gaModel->getViewsBySlug($slugs);

        foreach ($posts as &$post) {
            $post['views'] = $viewsData[$post['slug']] ?? 0;
        }

        return $posts;
    }

    public function getPopularPosts()
    {
        $posts = $this->where('status', 'published')
            ->orderBy('published_at', 'DESC')
            ->findAll();

        $postsWithGA = $this->addGAData($posts);

        usort($postsWithGA, function ($a, $b) {
            return ($b['views'] ?? 0) - ($a['views'] ?? 0);
        });

        return array_slice($postsWithGA, 0, 5);
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
        $posts = $this->where('MONTH(published_at)', $month)
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
            ->like('title', $query)
            ->orLike('content', $query)
            ->orderBy('posts.published_at', 'DESC')
            ->findAll();

        return $this->addGAData($posts);
    }

    /**
     * Optimized Related News Feature
     * Priority: 1. Tags matching count, 2. Same category, 3. Fulltext similarity, 4. Latest fallback
     *
     * @param int $currentId ID of the news to exclude
     * @param array $categoryIds Array of category IDs from the current news
     * @param string $title Title of the current news for fulltext fallback
     * @param int $limit Number of results (default 4)
     * @return array
     */
    public function getRelatedNewsOptimized(int $currentId, array $categoryIds, string $title, int $limit = 4)
    {
        // Use cache to save resources if same news is accessed frequently
        $cacheKey = "related_news_{$currentId}";
        if ($cached = cache($cacheKey)) {
            return $cached;
        }

        $relatedIds = [];

        // --- PHASE 1: TAG-BASED (Primary) ---
        // Fetch tag IDs for current post
        $tagIds = array_column($this->getPostTags($currentId), 'id');
        
        if (!empty($tagIds)) {
            $tagMatches = $this->db->table('post_tags')
                ->select('post_id, COUNT(*) as match_count')
                ->whereIn('tag_id', $tagIds)
                ->where('post_id !=', $currentId)
                ->join('posts', 'posts.id = post_tags.post_id')
                ->where('posts.status', 'published')
                ->groupBy('post_id')
                ->orderBy('match_count', 'DESC')
                ->orderBy('posts.published_at', 'DESC')
                ->limit($limit)
                ->get()
                ->getResultArray();
            
            $relatedIds = array_column($tagMatches, 'post_id');
        }

        // --- PHASE 2: CATEGORY-BASED (Secondary) ---
        if (count($relatedIds) < $limit && !empty($categoryIds)) {
            $catMatches = $this->db->table('post_categories')
                ->select('post_id')
                ->whereIn('category_id', $categoryIds)
                ->where('post_id !=', $currentId);
            
            if (!empty($relatedIds)) {
                $catMatches->whereNotIn('post_id', $relatedIds);
            }

            $catMatches->join('posts', 'posts.id = post_categories.post_id')
                ->where('posts.status', 'published')
                ->orderBy('posts.published_at', 'DESC')
                ->limit($limit - count($relatedIds));
            
            $catIds = array_column($catMatches->get()->getResultArray(), 'post_id');
            $relatedIds = array_merge($relatedIds, $catIds);
        }

        // --- PHASE 3: FULLTEXT SEARCH (Third Fallback) ---
        if (count($relatedIds) < $limit && !empty($title)) {
            // Clean title for cleaner search
            $cleanTitle = $this->db->escapeString($title);
            
            $ftMatches = $this->select('id')
                ->where('id !=', $currentId)
                ->where('status', 'published');
            
            if (!empty($relatedIds)) {
                $ftMatches->whereNotIn('id', $relatedIds);
            }

            // Using raw query for FULLTEXT MATCH
            $ftMatches->where("MATCH(title, content) AGAINST('$cleanTitle')")
                ->orderBy("MATCH(title, content) AGAINST('$cleanTitle')", 'DESC')
                ->limit($limit - count($relatedIds));
            
            $ftIds = array_column($ftMatches->findAll(), 'id');
            $relatedIds = array_merge($relatedIds, $ftIds);
        }

        // --- PHASE 4: LATEST NEWS (Final Fallback) ---
        if (count($relatedIds) < $limit) {
            $latestMatches = $this->select('id')
                ->where('id !=', $currentId)
                ->where('status', 'published');
            
            if (!empty($relatedIds)) {
                $latestMatches->whereNotIn('id', $relatedIds);
            }

            $latestMatches->orderBy('published_at', 'DESC')
                ->limit($limit - count($relatedIds));
            
            $latestIds = array_column($latestMatches->findAll(), 'id');
            $relatedIds = array_merge($relatedIds, $latestIds);
        }

        // Fetch complete data only for the finalized IDs
        if (empty($relatedIds)) {
            return [];
        }

        $finalResults = $this->whereIn('id', $relatedIds)
            ->orderBy('FIELD(id, ' . implode(',', $relatedIds) . ')') // Maintain priority order
            ->findAll();

        // Save to cache for 1 hour
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