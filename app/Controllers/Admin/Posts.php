<?php

namespace App\Controllers\Admin;

use App\Services\Content\PostService;
use App\Services\Media\MediaService;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\UserModel;

class Posts extends BaseController
{
    protected $postService;
    protected $mediaService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->mediaService = new MediaService();
    }

    public function index()
    {
        $filters = $this->request->getGet(['search', 'category', 'author', 'status']);

        $result = $this->postService->getAdminPosts($filters);
        $stats = $this->postService->getPostStats();
        
        $data = array_merge($result, [
            'filters'         => $filters,
            'categories'      => (new CategoryModel())->findAll(),
            'users'           => (new UserModel())->findAll(),
            'total_posts'     => $stats['total'],
            'published_posts' => $stats['published'],
            'draft_posts'     => $stats['draft'],
            'today_posts'     => $stats['today']
        ]);

        return $this->render('admin/posts/index', $data);
    }

    public function new()
    {
        $data = [
            'categories'      => (new CategoryModel())->getHierarchical(),
            'tags'            => (new TagModel())->orderBy('name', 'ASC')->findAll(),
            'post_categories' => []
        ];

        return $this->render('admin/posts/new', $data);
    }

    public function create()
    {
        $pastedThumbnail = $this->request->getPost('pasted_thumbnail');
        
        if (!$this->postService->validate($this->request->getPost(), $this->postService->getValidationRules(false, !empty($pastedThumbnail)))) {
            return redirect()->back()->withInput()->with('errors', $this->postService->getErrors());
        }

        $title = $this->request->getPost('title');
        $slug = url_title($title, '-', true);

        // Handle Image
        $thumbnailSource = $pastedThumbnail ?: $this->request->getFile('thumbnail');
        $imagePaths = $this->generateArticleImages($thumbnailSource, $slug);

        if (!$imagePaths && ($this->request->getFile('thumbnail')->getName() !== '' || !empty($pastedThumbnail))) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses gambar berita.');
        }

        $data = [
            'title'             => $title,
            'content'           => $this->request->getPost('content'),
            'status'            => $this->request->getPost('status'),
            'user_id'           => session()->get('user_id'),
            'thumbnail'         => $imagePaths['thumbnail'] ?? null,
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
        ];

        if ($this->postService->savePost($data, [
            'categories' => $this->request->getPost('categories') ?? [],
            'tags'       => $this->request->getPost('tags') ?? ''
        ])) {
            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil diterbitkan.');
        }

        return redirect()->back()->withInput()->with('error', $this->postService->getError());
    }

    public function edit($id = null)
    {
        $post = $this->postService->getPostBySlug((string)$id, false);
        
        if (!$post) {
            // Fallback try find by ID if slug fails (legacy support)
            // But getPostBySlug in service basically wraps getPosts($slug) which usually handles ID/Slug in model
            // If strictly ID is passed, model usually handles it. 
            // Let's rely on service returning null if not found.
             throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the post: ' . $id);
        }

        $data = [
            'post'            => $post,
            'categories'      => (new CategoryModel())->getHierarchical(),
            'tags'            => (new TagModel())->orderBy('name', 'ASC')->findAll(),
            'post_categories' => array_column($post['categories'] ?? [], 'id'),
            'post_tag_ids'    => array_column($post['tags'] ?? [], 'id'),
            'post_tag_names'  => array_column($post['tags'] ?? [], 'name'),
        ];

        return $this->render('admin/posts/edit', $data);
    }

    public function update($id = null)
    {
        $post = $this->postService->getPostBySlug((string)$id, false);
        if (!$post) {
            return redirect()->to(base_url('admin/posts'))->with('error', 'Berita tidak ditemukan.');
        }

        if (!$this->postService->validate($this->request->getPost(), $this->postService->getValidationRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->postService->getErrors());
        }

        // Handle Image
        $thumbnail = $post['thumbnail'];
        $pastedThumbnail = $this->request->getPost('pasted_thumbnail');
        $thumbnailFile = $this->request->getFile('thumbnail');
        
        $hasNewThumbnail = !empty($pastedThumbnail) || ($thumbnailFile && $thumbnailFile->isValid() && !$thumbnailFile->hasMoved());

        if ($hasNewThumbnail) {
            $imagePaths = $this->generateArticleImages($pastedThumbnail ?: $thumbnailFile, $post['slug'] ?? '');
            if ($imagePaths) {
                if (!empty($post['thumbnail'])) {
                    $this->mediaService->deleteImage($post['thumbnail']);
                    // Clean related posts version
                    $postImage = str_replace('thumbnails', 'posts', $post['thumbnail']);
                    if (file_exists(FCPATH . $postImage)) @unlink(FCPATH . $postImage);
                }
                $thumbnail = $imagePaths['thumbnail'];
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal memproses gambar baru.');
            }
        }

        $data = [
            'title'             => $this->request->getPost('title'),
            'content'           => $this->request->getPost('content'),
            'status'            => $this->request->getPost('status'),
            'published_at'      => $this->request->getPost('published_at') ?: null,
            'thumbnail'         => $thumbnail,
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
        ];

        if ($postId = $this->postService->savePost($data, [
            'categories' => $this->request->getPost('categories') ?? [],
            'tags'       => $this->request->getPost('tags') ?? ''
        ], (int)$id)) {
            // Get updated post to check for slug changes
            $postModel = new \App\Models\PostModel();
            $updatedPost = $postModel->find($postId);

            // Handle Slug change for OG Image
            $oldSlug = $post['slug'] ?? '' ?? null;
            $newSlug = $updatedPost['slug'] ?? null;

            if ($oldSlug && $newSlug && $oldSlug !== $newSlug) {
                $oldOg = FCPATH . 'uploads/og/' . $oldSlug . '.jpg';
                $newOg = FCPATH . 'uploads/og/' . $newSlug . '.jpg';
                if (file_exists($oldOg)) rename($oldOg, $newOg);
            }

            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', $this->postService->getError());
    }

    public function delete($id = null)
    {
        // Get post to delete image first
        $post = $this->postService->getPostBySlug((string)$id, false);
        if ($post && $this->postService->deletePost((int)$id)) {
            $this->mediaService->deleteImage($post['thumbnail']);
            $this->mediaService->deleteOgImage($post['slug'] ?? '');
            
            // Cleanup related post version
            $postImage = str_replace('thumbnails', 'posts', $post['thumbnail'] ?? '');
            if (!empty($postImage) && file_exists(FCPATH . $postImage)) @unlink(FCPATH . $postImage);

            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/posts'))->with('error', 'Gagal menghapus berita.');
    }

    /**
     * Standardized Image Generation for Articles
     * TASK 1 & 2 Implementation
     */
    private function generateArticleImages($source, string $slug): ?array
    {
        if (empty($source)) return null;

        try {
            $tempPath = null;
            $extension = 'jpg';

            if ($source instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                if (!$source->isValid()) return null;
                $tempPath = $source->getRealPath();
                $extension = $source->getExtension();
            } elseif (is_string($source) && strpos($source, 'data:image') === 0) {
                $parts = explode(',', $source);
                $data = base64_decode($parts[1]);
                $tempPath = WRITEPATH . 'cache/' . uniqid() . '.tmp';
                file_put_contents($tempPath, $data);
            }

            if (!$tempPath || !file_exists($tempPath)) return null;

            $subDir = date('Y/m');
            $paths = [
                'posts' => 'uploads/posts/' . $subDir,
                'thumbnails' => 'uploads/thumbnails/' . $subDir,
                'og' => 'uploads/og'
            ];

            foreach ($paths as $dir) {
                if (!is_dir(FCPATH . $dir)) mkdir(FCPATH . $dir, 0755, true);
            }

            $fileName = bin2hex(random_bytes(8));
            $image = \Config\Services::image('gd');

            // 1. POSTS (Max width: 1600px, quality 80%, original extension)
            $postRelPath = $paths['posts'] . '/' . $fileName . '.' . $extension;
            $image->withFile($tempPath)
                  ->resize(1600, 1600, true, 'width')
                  ->save(FCPATH . $postRelPath, 80);

            // 2. THUMBNAILS (400x250, crop center, JPG, 75%)
            $thumbRelPath = $paths['thumbnails'] . '/' . $fileName . '.jpg';
            $image->withFile($tempPath)
                  ->fit(400, 250, 'center')
                  ->save(FCPATH . $thumbRelPath, 75);

            // 3. OG IMAGE (1200x630, crop center, slug.jpg, JPG, 75%)
            $ogRelPath = $paths['og'] . '/' . $slug . '.jpg';
            $image->withFile($tempPath)
                  ->fit(1200, 630, 'center')
                  ->save(FCPATH . $ogRelPath, 75);

            if (is_string($source)) @unlink($tempPath);

            return [
                'post' => $postRelPath,
                'thumbnail' => $thumbRelPath,
                'og' => $ogRelPath
            ];

        } catch (\Exception $e) {
            log_message('error', '[generateArticleImages] ' . $e->getMessage());
            return null;
        }
    }

    public function upload_image()
    {
        $file = $this->request->getFile('file');
        $url = $this->mediaService->uploadImage($file);

        if ($url) {
            return $this->response->setJSON(['location' => base_url($url)]);
        }
        return $this->response->setStatusCode(500, 'Image upload failed.');
    }
}
