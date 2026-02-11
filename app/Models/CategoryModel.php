<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'parent_id'];

    public function getHierarchical()
    {
        $allCategories = $this->orderBy('name', 'ASC')->findAll();
        $categories = [];

        foreach ($allCategories as $category) {
            if ($category['parent_id'] === null) {
                $children = [];
                foreach ($allCategories as $subCategory) {
                    if ($subCategory['parent_id'] == $category['id']) {
                        $children[] = $subCategory;
                    }
                }
                $category['children'] = $children;
                $categories[] = $category;
            }
        }

        return $categories;
    }
}
