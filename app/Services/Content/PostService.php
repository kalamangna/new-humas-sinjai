<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\PostModel;
use App\Models\PostCategoryModel;
use App\Models\PostTagModel;
use App\Models\TagModel;
use App\Models\CategoryModel;
use App\Models\GoogleAnalyticsModel;
use App\Models\UserModel;

class PostService extends BaseService
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
     * Get validation rules for posts
     */
    public function getValidationRules(bool $isUpdate = false, bool $hasPastedThumbnail = false): array
    {
        $rules = [
            'title'      => 'required|min_length[3]|max_length[255]',
            'content'    => 'required',
            'categories' => 'required',
            'status'     => 'required',
            'tags'       => 'required'
        ];

        // Only require thumbnail upload if it's a new post AND no pasted thumbnail provided
        if (!$isUpdate && !$hasPastedThumbnail) {
            $rules['thumbnail'] = 'uploaded[thumbnail]|max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]';
        }

        return $rules;
    }

    /**
     * Unified Save (Create or Update)
     */
    public function savePost(array $data, array $relations, ?int $id = null): bool
    {
        $this->postModel->db->transStart();

        try {
            $data['slug'] = url_title($data['title'], '-', true);
            
            // Handle publish date logic
            if ($data['status'] === 'published') {
                if (empty($data['published_at'])) {
                    // Keep existing date if updating, or use current time
                    if ($id) {
                        $existing = $this->postModel->find($id);
                        $data['published_at'] = $existing['published_at'] ?: date('Y-m-d H:i:s');
                    } else {
                        $data['published_at'] = date('Y-m-d H:i:s');
                    }
                }
            } else {
                $data['published_at'] = null;
            }

            if ($id) {
                if (!$this->postModel->update($id, $data)) {
                    throw new \RuntimeException('Failed to update post database record.');
                }
                $postId = $id;
            } else {
                if (!$this->postModel->save($data)) {
                    throw new \RuntimeException('Failed to create post database record.');
                }
                $postId = $this->postModel->getInsertID();
            }

            // Sync Relations
            $this->syncCategories($postId, $relations['categories'] ?? []);
            $this->syncTags($postId, $relations['tags'] ?? '');

            $this->postModel->db->transComplete();
            return true;

        } catch (\Exception $e) {
            $this->postModel->db->transRollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    // --- Relation Helpers ---

    protected function syncCategories(int $postId, array $categoryIds)
    {
        // For updates, clear existing first
        $this->postCategoryModel->where('post_id', $postId)->delete();

        if (!empty($categoryIds)) {
            $catsToInsert = array_map(fn($catId) => [
                'post_id' => $postId, 
                'category_id' => $catId
            ], $categoryIds);
            $this->postCategoryModel->insertBatch($catsToInsert);
        }
    }

    protected function syncTags(int $postId, string $tagsString)
    {
        // For updates, clear existing first
        $this->postTagModel->where('post_id', $postId)->delete();

        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        if (empty($tagNames)) return;

        $tagIds = [];
        foreach ($tagNames as $name) {
            $slug = url_title($name, '-', true);
            $tag = $this->tagModel->where('slug', $slug)->first();
            
            if (!$tag) {
                // Determine ID manually if not auto-increment (unlikely but safe) or just insert
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
     * Delete a post
     */
    public function deletePost(int $id): bool
    {
        if ($this->postModel->delete($id)) {
            return true;
        }
        $this->setError('Failed to delete post.');
        return false;
    }

    // --- Retrieval Methods (Admin & Frontend) ---

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

    public function getPostStats()
    {
        return [
            'total'     => $this->postModel->countAllResults(),
            'published' => $this->postModel->where('status', 'published')->countAllResults(),
            'draft'     => $this->postModel->where('status', 'draft')->countAllResults(),
            'today'     => $this->postModel->where('DATE(created_at)', date('Y-m-d'))->countAllResults(),
        ];
    }

    private function addGADataToPosts(array $posts): array
    {
        if (empty($posts)) return $posts;
        $slugs = array_column($posts, 'slug');
        // Handle GA Model gracefully if service is unavailable
        try {
            $viewsData = $this->gaModel->getViewsBySlug($slugs);
        } catch (\Exception $e) {
            $viewsData = [];
        }

        foreach ($posts as &$post) {
            $post['views'] = $viewsData[$post['slug']] ?? 0;
        }
        return $posts;
    }

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

    public function searchPosts(string $query)
    {
        $posts = $this->postModel->searchAndAddGAData($query);
        return $this->postModel->withCategoriesAndTags($posts);
    }
}
