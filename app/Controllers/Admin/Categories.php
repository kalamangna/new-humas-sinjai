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
        $filters = $this->request->getGet();
        $categories = $this->categoryModel->getAllWithDetails($filters);
        
        $totalCategories = $this->categoryModel->countAllResults();
        $postModel = new \App\Models\PostModel();
        $totalPosts = $postModel->countAllResults();

        $data = [
            'categories' => $categories,
            'filters'    => $filters,
            'total_categories' => $totalCategories,
            'total_posts' => $totalPosts,
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

        // Check if this category is already a parent
        $hasChildren = $this->categoryModel->where('parent_id', $id)->countAllResults() > 0;

        // Candidates for parent: any top-level category that is not this category
        // BUT if this category already has children, we shouldn't allow it to have a parent 
        // (to keep it 2 levels max as per current design)
        $parentCandidates = [];
        if (!$hasChildren) {
            $parentCandidates = $this->categoryModel->where('parent_id', null)
                                                   ->where('id !=', $id)
                                                   ->findAll();
        }

        $data = [
            'category' => $category,
            'categories' => $parentCandidates,
            'has_children' => $hasChildren,
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