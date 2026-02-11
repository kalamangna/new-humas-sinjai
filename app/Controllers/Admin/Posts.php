<?php

namespace App\Controllers\Admin;

use App\Services\Content\PostService;
use App\Services\Media\MediaService;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\UserModel;
use App\Models\PostCategoryModel;

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
        $filters = [
            'search'   => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'author'   => $this->request->getGet('author'),
            'status'   => $this->request->getGet('status'),
        ];

        $result = $this->postService->getAdminPosts($filters);
        
        $data = array_merge($result, [
            'filters'         => $filters,
            'categories'      => (new CategoryModel())->findAll(),
            'users'           => (new UserModel())->findAll(),
            'stats'           => $this->postService->getPostStats()
        ]);

        return $this->render('Admin/Posts/index', $data);
    }

    public function new()
    {
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();

        $data = [
            'categories'      => $categoryModel->getHierarchical(),
            'tags'            => $tagModel->orderBy('name', 'ASC')->findAll(),
            'post_categories' => []
        ];

        return $this->render('Admin/Posts/new', $data);
    }

    public function create()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $thumbnail = $this->mediaService->saveImage(
            $this->request->getPost('pasted_thumbnail') ?: $this->request->getFile('thumbnail'),
            'thumbnails'
        );

        $postData = [
            'title'             => $this->request->getPost('title'),
            'slug'              => url_title($this->request->getPost('title'), '-', true),
            'content'           => $this->request->getPost('content'),
            'status'            => $this->request->getPost('status'),
            'user_id'           => session()->get('user_id'),
            'thumbnail'         => $thumbnail,
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
        ];

        try {
            $this->postService->createPost(
                $postData,
                $this->request->getPost('categories') ?? [],
                $this->request->getPost('tags') ?? ''
            );
            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit($id = null)
    {
        $postModel = new \App\Models\PostModel();
        $post = $this->postService->getPostBySlug((string)$id);
        
        if (!$post) {
            $post = $postModel->find($id);
            if ($post) {
                $post = $postModel->withCategoriesAndTags([$post])[0];
            }
        }
        
        if (!$post) {
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
        $postModel = new \App\Models\PostModel();
        $existingPost = $postModel->find($id);

        if (!$this->validate($this->getValidationRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $thumbnail = $existingPost['thumbnail'];
        $newThumbnailSource = $this->request->getPost('pasted_thumbnail') ?: $this->request->getFile('thumbnail');

        if ($newThumbnailSource && ($newThumbnailSource instanceof \CodeIgniter\HTTP\FileUpload ? $newThumbnailSource->getName() !== '' : true)) {
            $this->mediaService->deleteImage($existingPost['thumbnail']);
            $thumbnail = $this->mediaService->saveImage($newThumbnailSource, 'thumbnails');
        }

        $postData = [
            'title'             => $this->request->getPost('title'),
            'slug'              => url_title($this->request->getPost('title'), '-', true),
            'content'           => $this->request->getPost('content'),
            'status'            => $this->request->getPost('status'),
            'user_id'           => session()->get('user_id'),
            'thumbnail'         => $thumbnail,
            'thumbnail_caption' => $this->request->getPost('thumbnail_caption'),
        ];

        try {
            $this->postService->updatePost(
                (int)$id,
                $postData,
                $this->request->getPost('categories') ?? [],
                $this->request->getPost('tags') ?? ''
            );
            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        if ($this->postService->deletePost((int)$id)) {
            return redirect()->to(base_url('admin/posts'))->with('success', 'Berita berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/posts'))->with('error', 'Error deleting post.');
    }

    public function upload_image()
    {
        $file = $this->request->getFile('file');
        $url = $this->mediaService->uploadImage($file);

        if ($url) {
            return $this->response->setJSON(['location' => $url]);
        }
        return $this->response->setStatusCode(500, 'Image upload failed.');
    }

    protected function getValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'title'      => 'required|min_length[3]|max_length[255]',
            'content'    => 'required',
            'categories' => 'required',
            'status'     => 'required',
            'tags'       => 'required'
        ];

        if (!$isUpdate && empty($this->request->getPost('pasted_thumbnail'))) {
            $rules['thumbnail'] = 'uploaded[thumbnail]|max_size[thumbnail,2048]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png,image/webp]';
        }

        return $rules;
    }
}