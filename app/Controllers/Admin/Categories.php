<?php

namespace App\Controllers\Admin;

use App\Services\Content\CategoryService;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryService;
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->getHierarchical(),
        ];
        return $this->render('admin/categories/index', $data);
    }

    public function new()
    {
        $data = [
            'categories' => $this->categoryModel->where('parent_id', null)->findAll(),
        ];
        return $this->render('admin/categories/new', $data);
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->categoryService->validate($data, $this->categoryService->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->categoryService->getErrors());
        }

        if ($this->categoryService->saveCategory($data)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat kategori.');
    }

    public function edit($id = null)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $data = [
            'category' => $category,
            'categories' => $this->categoryModel->where('parent_id', null)->where('id !=', $id)->findAll(),
        ];
        return $this->render('admin/categories/edit', $data);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        if (!$this->categoryService->validate($data, $this->categoryService->getValidationRules((int)$id))) {
            return redirect()->back()->withInput()->with('errors', $this->categoryService->getErrors());
        }

        if ($this->categoryService->saveCategory($data, (int)$id)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori.');
    }

    public function delete($id = null)
    {
        if ($this->categoryService->deleteCategory((int)$id)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/categories'))->with('error', 'Gagal menghapus kategori.');
    }
}