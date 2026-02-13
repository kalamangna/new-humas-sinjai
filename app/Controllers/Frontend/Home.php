<?php

namespace App\Controllers\Frontend;

use App\Services\Content\PostService;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\PostCategoryModel;
use App\Models\CarouselSlideModel;

class Home extends BaseController
{
    protected $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function index(): string
    {
        $data['posts'] = $this->postService->getPublicPosts()['posts'];
        $data['slides'] = (new CarouselSlideModel())->orderBy('slide_order', 'ASC')->findAll();

        return view('Frontend/home', $data);
    }

    public function post($slug = null)
    {
        $post = $this->postService->getPostBySlug((string)$slug);

        if (empty($post)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the post: ' . $slug);
        }

        $data = [
            'post'          => $post,
            'title'         => $post['title'],
            'description'   => substr(strip_tags($post['content']), 0, 160),
            'keywords'      => implode(', ', array_column($post['tags'], 'name')),
            'image'         => !empty($post['thumbnail']) 
                                ? (filter_var($post['thumbnail'], FILTER_VALIDATE_URL) ? $post['thumbnail'] : base_url($post['thumbnail'])) 
                                : base_url('meta.png'),
            'tags'          => $post['tags'],
            'recent_posts'  => $this->postService->getRecentPosts(5),
            'popular_posts' => $this->postService->getPopularPosts(5),
            'related_posts' => (new \App\Models\PostModel())->getRelatedNewsOptimized(
                $post['id'], 
                array_column($post['categories'], 'id'), 
                $post['title'], 
                4
            )
        ];

        return view('Frontend/post_detail', $data);
    }

    public function category($slug)
    {
        $category = (new CategoryModel())->where('slug', $slug)->first();

        if (empty($category)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the category: ' . $slug);
        }

        $result = $this->postService->getAdminPosts(['category' => $category['id']], 10);

        $data = [
            'category'    => $category,
            'posts'       => $result['posts'],
            'pager'       => $result['pager'],
            'title'       => 'Kategori: ' . $category['name'],
            'description' => 'Telusuri semua berita dalam kategori ' . $category['name'] . ' di Humas Sinjai.',
            'keywords'    => 'Humas Sinjai, Berita Sinjai, ' . $category['name'],
        ];

        return view('Frontend/category_detail', $data);
    }

    public function tag($slug)
    {
        $tag = (new TagModel())->where('slug', $slug)->first();

        if (empty($tag)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the tag: ' . $slug);
        }

        // We can reuse getAdminPosts or add a specific tag filter to getAdminPosts if needed
        // For now, let's assume getAdminPosts can handle tag filter or we add it to Service
        // Actually I didn't add tag filter to getAdminPosts. Let's fix that in Service later if needed.
        // For now using custom logic or updating Service.
        
        $postTagModel = new \App\Models\PostTagModel();
        $postIds = array_column($postTagModel->where('tag_id', $tag['id'])->findAll(), 'post_id');

        $data['posts'] = [];
        $data['pager'] = null;

        if (!empty($postIds)) {
            $postModel = new \App\Models\PostModel();
            $posts = $postModel->whereIn('posts.id', $postIds)->where('status', 'published')->orderBy('posts.published_at', 'DESC')->paginate(10);
            $data['posts'] = $postModel->withCategoriesAndTags($posts);
            $data['pager'] = $postModel->pager;
        }

        $data['tag'] = $tag;
        $data['title'] = 'Tag: ' . $tag['name'];
        $data['description'] = 'Telusuri semua berita dengan tag ' . $tag['name'] . ' di Humas Sinjai.';
        $data['keywords'] = 'Humas Sinjai, Berita Sinjai, ' . $tag['name'];

        return view('Frontend/tag_detail', $data);
    }

    public function search()
    {
        $query = $this->request->getGet('q');
        if (strlen($query) < 3) {
            return redirect()->back()->with('error', 'Kata kunci pencarian minimal harus 3 karakter.');
        }

        $posts = $this->postService->searchPosts($query);

        $data = [
            'posts'       => $posts,
            'query'       => $query,
            'title'       => 'Hasil pencarian untuk: ' . $query,
            'description' => 'Hasil pencarian untuk kata kunci ' . $query . ' di Humas Sinjai.',
            'keywords'    => 'pencarian, ' . $query,
        ];

        return view('Frontend/search_results', $data);
    }

    public function posts()
    {
        $result = $this->postService->getPublicPosts(10);
        
        $data = [
            'posts'       => $result['posts'],
            'pager'       => $result['pager'],
            'title'       => 'Semua Berita',
            'description' => 'Telusuri semua berita terbaru dari Humas Sinjai.',
            'keywords'    => 'berita, humas sinjai, sinjai, berita terbaru',
        ];

        return view('Frontend/posts', $data);
    }

    public function categories()
    {
        $categoryModel = new CategoryModel();
        $allCategories = $categoryModel
            ->select('categories.*, COUNT(post_categories.post_id) as post_count')
            ->join('post_categories', 'post_categories.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->findAll();

        $categories = [];
        $subCategories = [];

        foreach ($allCategories as $category) {
            if ($category['parent_id'] === null) {
                $categories[] = $category;
            } else {
                $subCategories[$category['parent_id']][] = $category;
            }
        }

        $data = [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'title' => 'Semua Kategori',
            'description' => 'Telusuri semua kategori berita di Humas Sinjai.',
            'keywords' => 'kategori, berita, humas sinjai, sinjai',
        ];

        return view('Frontend/categories', $data);
    }

    public function tags()
    {
        $tagModel = new TagModel();
        $data = [
            'tags' => $tagModel
                ->select('tags.*, COUNT(post_tags.post_id) as post_count')
                ->join('post_tags', 'post_tags.tag_id = tags.id', 'left')
                ->groupBy('tags.id')
                ->orderBy('tags.name', 'ASC')
                ->findAll(),
            'title' => 'Semua Tag',
            'description' => 'Telusuri semua tag berita di Humas Sinjai.',
            'keywords' => 'tag, berita, humas sinjai, sinjai',
        ];

        return view('Frontend/tags', $data);
    }

    public function rss()
    {
        $postModel = new \App\Models\PostModel();
        $posts = $postModel->where('status', 'published')->orderBy('published_at', 'DESC')->limit(20)->findAll();

        $this->response->setHeader('Content-Type', 'application/rss+xml');

        $data = [
            'posts' => $posts,
        ];

        return view('Frontend/rss', $data);
    }

    public function sitemap()
    {
        $postModel = new \App\Models\PostModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'posts' => $postModel->where('status', 'published')->orderBy('published_at', 'DESC')->findAll(),
            'categories' => $categoryModel->findAll(),
            'tags' => $tagModel->findAll(),
        ];

        $this->response->setHeader('Content-Type', 'application/xml');
        return view('Frontend/sitemap', $data);
    }

    public function programPrioritas()
    {
        $categoryModel = new CategoryModel();
        $parentCategory = $categoryModel->where('slug', 'program-prioritas')->first();

        if (empty($parentCategory)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the category: program-prioritas');
        }

        $childCategories = $categoryModel->where('parent_id', $parentCategory['id'])->findAll();
        $childCategoryIds = array_column($childCategories, 'id');

        $postModel = new \App\Models\PostModel();
        $data['posts'] = [];
        $data['pager'] = null;

        if (!empty($childCategoryIds)) {
            $postCategoryModel = new PostCategoryModel();
            $postIds = array_column($postCategoryModel->whereIn('category_id', $childCategoryIds)->findAll(), 'post_id');

            if (!empty($postIds)) {
                $posts = $postModel->whereIn('posts.id', $postIds)->getPosts(false, true);
                $data['posts'] = $postModel->withCategoriesAndTags($posts);
                $data['pager'] = $postModel->pager;
            }
        }

        $data['title'] = 'Program Prioritas';
        $data['description'] = 'Program prioritas Pemerintah Kabupaten Sinjai.';
        $data['keywords'] = 'program prioritas, sinjai, pemerintah kabupaten sinjai';

        return view('Frontend/program_prioritas', $data);
    }

    public function error404()
    {
        $this->response->setStatusCode(404);
        $data['title'] = 'Halaman Tidak Ditemukan';
        return view('errors/html/error_404_frontend', $data);
    }
}