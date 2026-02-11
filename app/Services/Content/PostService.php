<?php

namespace App\Services\Content;

use App\Models\PostModel;
use App\Models\PostCategoryModel;
use App\Models\PostTagModel;
use App\Models\TagModel;
use App\Models\CategoryModel;
use App\Models\GoogleAnalyticsModel;
use App\Models\UserModel;

class PostService
{
    protected $postModel;
    protected $postCategoryModel;
    protected $postTagModel;
    protected $tagModel;
    protected $categoryModel;
    protected $gaModel;
    protected $userModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->postCategoryModel = new PostCategoryModel();
        $this->postTagModel = new PostTagModel();
        $this->tagModel = new TagModel();
        $this->categoryModel = new CategoryModel();
        $this->gaModel = new GoogleAnalyticsModel();
        $this->userModel = new UserModel();
    }

    /**
     * Get list of posts with filters for Admin
     */
    public function getAdminPosts(array $filters = [], int $perPage = 10)
    {
        $builder = $this->postModel
            ->select('posts.*, users.name as author_name, GROUP_CONCAT(DISTINCT categories.name) as category_name, COUNT(DISTINCT post_tags.tag_id) as tag_count')
            ->join('users', 'users.id = posts.user_id', 'left')
            ->join('post_categories', 'post_categories.post_id = posts.id', 'left')
            ->join('categories', 'categories.id = post_categories.category_id', 'left')
            ->join('post_tags', 'post_tags.post_id = posts.id', 'left')
            ->groupBy('posts.id, users.name');

        if (!empty($filters['search'])) {
            $builder->like('posts.title', $filters['search']);
        }
        if (!empty($filters['category'])) {
            $builder->whereIn('posts.id', function ($subquery) use ($filters) {
                $subquery->select('post_id')->from('post_categories')->where('category_id', $filters['category']);
            });
        }
        if (!empty($filters['author'])) {
            $builder->where('posts.user_id', $filters['author']);
        }
        if (!empty($filters['status'])) {
            $builder->where('posts.status', $filters['status']);
        }

        $posts = $builder->orderBy('posts.created_at', 'DESC')->paginate($perPage, 'posts');
        
        // Add GA data
        $postsWithGA = $this->addGADataToPosts($posts);

        return [
            'posts' => $postsWithGA,
            'pager' => $this->postModel->pager
        ];
    }

    /**
     * Get stats for admin dashboard cards
     */
    public function getPostStats()
    {
        return [
            'total'     => $this->postModel->countAllResults(),
            'published' => $this->postModel->where('status', 'published')->countAllResults(),
            'draft'     => $this->postModel->where('status', 'draft')->countAllResults(),
            'today'     => $this->postModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
        ];
    }

    /**
     * Create a new post with relations
     */
    public function createPost(array $data, array $categoryIds, string $tagsString)
    {
        $this->postModel->db->transStart();

        try {
            if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }

            if (!$this->postModel->save($data)) {
                throw new \RuntimeException('Failed to save post');
            }

            $postId = $this->postModel->getInsertID();

            // Sync categories
            if (!empty($categoryIds)) {
                $catsToInsert = array_map(fn($catId) => [
                    'post_id' => $postId, 
                    'category_id' => $catId
                ], $categoryIds);
                $this->postCategoryModel->insertBatch($catsToInsert);
            }

            // Sync tags
            $this->syncTags($postId, $tagsString);

            $this->postModel->db->transComplete();
            return $postId;
        } catch (\Exception $e) {
            $this->postModel->db->transRollback();
            throw $e;
        }
    }

    /**
     * Update an existing post with relations
     */
    public function updatePost(int $id, array $data, array $categoryIds, string $tagsString)
    {
        $this->postModel->db->transStart();

        try {
            $post = $this->postModel->find($id);
            if (!$post) throw new \RuntimeException('Post not found');

            if ($data['status'] === 'published') {
                if (empty($post['published_at']) && empty($data['published_at'])) {
                    $data['published_at'] = date('Y-m-d H:i:s');
                }
            } else {
                $data['published_at'] = null;
            }

            if (!$this->postModel->update($id, $data)) {
                throw new \RuntimeException('Failed to update post');
            }

            // Sync categories
            $this->postModel->db->table('post_categories')->where('post_id', $id)->delete();
            if (!empty($categoryIds)) {
                $catsToInsert = array_map(fn($catId) => [
                    'post_id' => $id, 
                    'category_id' => $catId
                ], $categoryIds);
                $this->postCategoryModel->insertBatch($catsToInsert);
            }

            // Sync tags
            $this->postModel->db->table('post_tags')->where('post_id', $id)->delete();
            $this->syncTags($id, $tagsString);

            $this->postModel->db->transComplete();
            return true;
        } catch (\Exception $e) {
            $this->postModel->db->transRollback();
            throw $e;
        }
    }

    /**
     * Delete a post
     */
    public function deletePost(int $id)
    {
        return $this->postModel->delete($id);
    }

    /**
     * Internal helper to sync tags from comma separated string
     */
    protected function syncTags(int $postId, string $tagsString)
    {
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        if (empty($tagNames)) return;

        $tagIds = [];
        foreach ($tagNames as $name) {
            $slug = url_title($name, '-', true);
            $tag = $this->tagModel->where('slug', $slug)->first();
            
            if (!$tag) {
                $tagId = $this->tagModel->insert(['name' => $name, 'slug' => $slug]);
                $tagIds[] = $tagId;
            } else {
                $tagIds[] = $tag['id'];
            }
        }

        if (!empty($tagIds)) {
            $tagsToInsert = array_map(fn($tagId) => [
                'post_id' => $postId, 
                'tag_id' => $tagId
            ], $tagIds);
            $this->postTagModel->insertBatch($tagsToInsert);
        }
    }

    /**
     * Add GA data to posts array
     */
    private function addGADataToPosts(array $posts): array
    {
        if (empty($posts)) return $posts;
        $slugs = array_column($posts, 'slug');
        $viewsData = $this->gaModel->getViewsBySlug($slugs);

        foreach ($posts as &$post) {
            $post['views'] = $viewsData[$post['slug']] ?? 0;
        }
        return $posts;
    }

    // --- Frontend Methods ---

    public function getPostBySlug(string $slug)
    {
        $post = $this->postModel->getPosts($slug);
        if (!$post) return null;

        $enriched = $this->postModel->withCategoriesAndTags([$post]);
        return $enriched[0];
    }

    public function getPublicPosts(int $perPage = 10)
    {
        $posts = $this->postModel->getPosts(false, true);
        return [
            'posts' => $this->postModel->withCategoriesAndTags($posts),
            'pager' => $this->postModel->pager
        ];
    }

    public function getRecentPosts(int $limit = 5)
    {
        return $this->postModel->getRecentPosts($limit);
    }

    public function getPopularPosts(int $limit = 5)
    {
        return $this->postModel->getPopularPosts($limit);
    }

    public function getRelatedPosts(array $post, int $limit = 6)
    {
        $related = $this->postModel->getRelatedPosts($post, $limit);
        return $this->postModel->withCategoriesAndTags($related);
    }

    public function searchPosts(string $query)
    {
        $posts = $this->postModel->searchAndAddGAData($query);
        return $this->postModel->withCategoriesAndTags($posts);
    }
}