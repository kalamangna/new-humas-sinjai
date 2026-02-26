<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug', 'parent_id'];

    public function getAllWithDetails(array $filters = [])
    {
        $builder = $this->select('categories.*, p.name as parent_name')
            ->join('categories p', 'p.id = categories.parent_id', 'left')
            ->select('(SELECT COUNT(*) FROM post_categories WHERE category_id = categories.id) as post_count');

        if (!empty($filters['search'])) {
            $builder->like('categories.name', $filters['search']);
        }

        // We want a flattened hierarchical order: Parent A, Child A1, Child A2, Parent B...
        $builder->orderBy('COALESCE(categories.parent_id, categories.id)', 'ASC', false);
        $builder->orderBy('categories.parent_id', 'ASC'); 
        $builder->orderBy('categories.name', 'ASC');

        return $builder->findAll();
    }

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
