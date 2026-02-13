<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\CategoryModel;

class CategoryService extends BaseService
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function getValidationRules(int $id = null): array
    {
        return [
            'name' => 'required|min_length[3]|max_length[255]',
            'slug' => 'permit_empty|alpha_dash|is_unique[categories.slug,id,' . ($id ?? '0') . ']',
        ];
    }

    public function saveCategory(array $data, ?int $id = null): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = url_title($data['name'], '-', true);
        }

        if ($id) {
            return $this->categoryModel->update($id, $data);
        }

        return $this->categoryModel->save($data);
    }

    public function deleteCategory(int $id): bool
    {
        // Add business logic: check if category has posts before deleting if required
        return $this->categoryModel->delete($id);
    }

    public function getHierarchical(): array
    {
        return $this->categoryModel->getHierarchical();
    }
}