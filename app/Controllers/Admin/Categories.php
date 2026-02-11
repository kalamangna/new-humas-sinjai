<?php

namespace App\Controllers\Admin;

use App\Services\Content\CategoryService;

class Categories extends BaseController
{
    protected $categoryService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
    }

    public function index()
    {
        $filters = ['search' => $this->request->getGet('search')];
        $result = $this->categoryService->getAdminCategories($filters);

        $data = array_merge($result, [
            'filters'           => $filters,
            'total_categories'  => $this->data['total_categories'],
            'total_posts'       => $this->data['total_posts'],
        ]);

        return $this->render('Admin/Categories/index', $data);
    }

    public function new()
    {
        $data['categories'] = $this->categoryService->getAllCategories();
        return $this->render('Admin/Categories/new', $data);
    }

    public function create()
    {
        if ($this->categoryService->createCategory($this->request->getPost())) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dibuat.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to create category.');
    }

    public function edit($id = null)
    {
        $category = (new \App\Models\CategoryModel())->find($id);
        if (!$category) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the category: ' . $id);
        }

        $data = [
            'category'   => $category,
            'categories' => $this->categoryService->getAllCategories()
        ];

        return $this->render('Admin/Categories/edit', $data);
    }

    public function update($id = null)
    {
        if ($this->categoryService->updateCategory((int)$id, $this->request->getPost())) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to update category.');
    }

    public function delete($id = null)
    {
        if ($this->categoryService->deleteCategory((int)$id)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/categories'))->with('error', 'Error deleting category.');
    }
}
