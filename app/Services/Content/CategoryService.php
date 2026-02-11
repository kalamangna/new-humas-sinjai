<?php

namespace App\Services\Content;

use App\Models\CategoryModel;

class CategoryService
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function getAdminCategories(array $filters = [], int $perPage = 10)
    {
        $builder = $this->categoryModel
            ->select('categories.*, parent.name as parent_name, COUNT(post_categories.post_id) as post_count')
            ->join('post_categories', 'post_categories.category_id = categories.id', 'left')
            ->join('categories as parent', 'parent.id = categories.parent_id', 'left')
            ->groupBy('categories.id');

        if (!empty($filters['search'])) {
            $builder->like('categories.name', $filters['search']);
        }

        return [
            'categories' => $builder->orderBy('categories.name', 'ASC')->paginate($perPage),
            'pager'      => $this->categoryModel->pager
        ];
    }

    public function getAllCategories()
    {
        return $this->categoryModel->orderBy('name', 'ASC')->findAll();
    }

    public function createCategory(array $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        if (empty($data['parent_id'])) $data['parent_id'] = null;

        return $this->categoryModel->save($data);
    }

    public function updateCategory(int $id, array $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        if (empty($data['parent_id'])) $data['parent_id'] = null;

        return $this->categoryModel->update($id, $data);
    }

    public function deleteCategory(int $id)
    {
        return $this->categoryModel->delete($id);
    }
}
