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

        return $this->render('Admin/Posts/index', $data);
    }

    public function new()
    {
        $data = [
            'categories'      => (new CategoryModel())->getHierarchical(),
            'tags'            => (new TagModel())->orderBy('name', 'ASC')->findAll(),
            'post_categories' => []
        ];

        return $this->render('Admin/Posts/new', $data);
    }

    public function create()
    {
        $pastedThumbnail = $this->request->getPost('pasted_thumbnail');
        
        if (!$this->postService->validate($this->request->getPost(), $this->postService->getValidationRules(false, !empty($pastedThumbnail)))) {
            return redirect()->back()->withInput()->with('errors', $this->postService->getErrors());
        }

        // Handle Image
        $thumbnailSource = $pastedThumbnail ?: $this->request->getFile('thumbnail');
        $thumbnail = $this->mediaService->saveImage($thumbnailSource, 'thumbnails');

        if (!$thumbnail && ($this->request->getFile('thumbnail')->getName() !== '' || !empty($pastedThumbnail))) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses gambar thumbnail.');
        }

        $data = [
            'title'             => $this->request->getPost('title'),
            'content'           => $this->request->getPost('content'),
            'status'            => $this->request->getPost('status'),
            'user_id'           => session()->get('user_id'),
            'thumbnail'         => $thumbnail,
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

        return $this->render('Admin/Posts/edit', $data);
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
            $newThumbnail = $this->mediaService->saveImage($pastedThumbnail ?: $thumbnailFile, 'thumbnails');
            if ($newThumbnail) {
                $this->mediaService->deleteImage($post['thumbnail']);
                $thumbnail = $newThumbnail;
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

        if ($this->postService->savePost($data, [
            'categories' => $this->request->getPost('categories') ?? [],
            'tags'       => $this->request->getPost('tags') ?? ''
        ], (int)$id)) {
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
            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/posts'))->with('error', 'Gagal menghapus berita.');
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
