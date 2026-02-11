<?php

namespace App\Controllers\Admin;

use App\Services\Content\TagService;

class Tags extends BaseController
{
    protected $tagService;

    public function __construct()
    {
        $this->tagService = new TagService();
    }

    public function index()
    {
        $filters = ['search' => $this->request->getGet('search')];
        $result = $this->tagService->getAdminTags($filters);

        $data = array_merge($result, [
            'filters'    => $filters,
            'total_tags' => $this->data['total_tags'],
            'total_posts' => $this->data['total_posts'],
        ]);

        return $this->render('Admin/Tags/index', $data);
    }

    public function new()
    {
        return $this->render('Admin/Tags/new');
    }

    public function create()
    {
        if ($this->tagService->createTag($this->request->getPost())) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil dibuat.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to create tag.');
    }

    public function edit($id = null)
    {
        $tag = (new \App\Models\TagModel())->find($id);
        if (!$tag) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the tag: ' . $id);
        }

        return $this->render('Admin/Tags/edit', ['tag' => $tag]);
    }

    public function update($id = null)
    {
        if ($this->tagService->updateTag((int)$id, $this->request->getPost())) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to update tag.');
    }

    public function delete($id = null)
    {
        if ($this->tagService->deleteTag((int)$id)) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/tags'))->with('error', 'Error deleting tag.');
    }
}
