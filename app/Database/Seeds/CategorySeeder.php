<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CategoryModel;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categoryModel = new CategoryModel();

        // 1. Matikan Foreign Key check agar bisa truncate tabel categories yang mungkin terhubung dengan post_categories
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        $this->db->table('categories')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "Tabel categories berhasil dikosongkan.\n";

        // 2. Daftar Kategori Utama (Parent Categories)
        $mainCategories = [
            'Pemerintahan',
            'Pendidikan',
            'Kesehatan',
            'Infrastruktur',
            'Sosial & Ekonomi',
            'Pariwisata & Budaya',
            'Hukum & Kriminal'
        ];

        foreach ($mainCategories as $catName) {
            $categoryModel->insert([
                'name' => $catName,
                'slug' => strtolower(url_title($catName, '-', true)),
            ]);
        }

        // 3. Kategori Khusus dengan Sub-Kategori: Program Prioritas
        $parentCategoryName = 'Program Prioritas';
        $parentCategorySlug = 'program-prioritas';

        $categoryModel->insert([
            'name' => $parentCategoryName,
            'slug' => $parentCategorySlug,
        ]);

        $parentId = $categoryModel->getInsertID();

        // Sub-Kategori untuk Program Prioritas
        $childCategories = [
            'Pendidikan Berkualitas',
            'Kesehatan Terjangkau',
            'Infrastruktur Merata',
            'Ekonomi Kreatif',
            'Reformasi Birokrasi',
            'Lingkungan Hidup',
        ];

        foreach ($childCategories as $childName) {
            $categoryModel->insert([
                'name'      => $childName,
                'slug'      => strtolower(url_title($childName, '-', true)),
                'parent_id' => $parentId,
            ]);
        }

        echo "Berhasil membuat kategori baru secara komprehensif!\n";
    }
}