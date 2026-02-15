<?php

namespace App\Controllers\Admin;

use App\Services\Content\TagService;
use App\Models\TagModel;

class Tags extends BaseController
{
    protected $tagService;
    protected $tagModel;

    public function __construct()
    {
        $this->tagService = new TagService();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $data = [
            'tags' => $this->tagModel->orderBy('name', 'ASC')->paginate(20),
            'pager' => $this->tagModel->pager,
        ];
        return $this->render('admin/tags/index', $data);
    }

    public function new()
    {
        return $this->render('admin/tags/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->tagService->validate($data, $this->tagService->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->tagService->getErrors());
        }

        if ($this->tagService->saveTag($data)) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Label berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat label.');
    }

    public function edit($id = null)
    {
        $tag = $this->tagModel->find($id);
        if (!$tag) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        return $this->render('admin/tags/edit', ['tag' => $tag]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        if (!$this->tagService->validate($data, $this->tagService->getValidationRules((int)$id))) {
            return redirect()->back()->withInput()->with('errors', $this->tagService->getErrors());
        }

        if ($this->tagService->saveTag($data, (int)$id)) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Label berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui label.');
    }

    public function delete($id = null)
    {
        if ($this->tagService->deleteTag((int)$id)) {
            return redirect()->to(base_url('admin/tags'))->with('success', 'Label berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/tags'))->with('error', 'Gagal menghapus label.');
    }
}