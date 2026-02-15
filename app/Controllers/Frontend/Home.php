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

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Beranda';

        return view('frontend/home/index', $data);
    }

    public function post($slug = null)
    {
        $post = $this->postService->getPostBySlug((string)$slug);

        if (empty($post)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the post: ' . $slug);
        }

        $data = [
            'post'          => $post,
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

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = $post['title'];
        $data['seo']['description'] = substr(strip_tags($post['content']), 0, 160);
        $data['seo']['keywords'] = implode(', ', array_column($post['tags'], 'name'));
        $data['seo']['image'] = !empty($post['thumbnail']) 
                                ? (filter_var($post['thumbnail'], FILTER_VALIDATE_URL) ? $post['thumbnail'] : base_url($post['thumbnail'])) 
                                : base_url('meta.png');
        $data['seo']['type'] = 'article';

        return view('frontend/posts/detail', $data);
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
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Kategori: ' . $category['name'];
        $data['seo']['description'] = 'Telusuri semua berita dalam kategori ' . $category['name'] . ' di Humas Sinjai.';

        return view('frontend/categories/detail', $data);
    }

    public function tag($slug)
    {
        $tag = (new TagModel())->where('slug', $slug)->first();

        if (empty($tag)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the tag: ' . $slug);
        }

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
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Tag: ' . $tag['name'];
        $data['seo']['description'] = 'Telusuri semua berita dengan tag ' . $tag['name'] . ' di Humas Sinjai.';

        return view('frontend/tags/detail', $data);
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
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Hasil pencarian untuk: ' . $query;

        return view('frontend/search/results', $data);
    }

    public function posts()
    {
        $result = $this->postService->getPublicPosts(10);
        
        $data = [
            'posts'       => $result['posts'],
            'pager'       => $result['pager'],
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Semua Berita';

        return view('frontend/posts/index', $data);
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
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Semua Kategori';

        return view('frontend/categories/index', $data);
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
        ];

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Semua Tag';

        return view('frontend/tags/index', $data);
    }

    public function rss()
    {
        $postModel = new \App\Models\PostModel();
        $posts = $postModel->where('status', 'published')->orderBy('published_at', 'DESC')->limit(20)->findAll();

        $this->response->setHeader('Content-Type', 'application/rss+xml');

        $data = [
            'posts' => $posts,
        ];

        return view('frontend/rss/index', $data);
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
        return view('frontend/sitemap/index', $data);
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

        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Program Prioritas';
        $data['seo']['description'] = 'Program prioritas Pemerintah Kabupaten Sinjai.';

        return view('frontend/pages/program_prioritas', $data);
    }

    public function error404()
    {
        $this->response->setStatusCode(404);
        $data['seo'] = $this->seoData;
        $data['seo']['title'] = 'Halaman Tidak Ditemukan';
        return view('errors/html/error_404_frontend', $data);
    }
}