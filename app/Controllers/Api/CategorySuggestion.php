<?php

namespace App\Controllers\Api;

use App\Libraries\GeminiService;
use App\Models\CategoryModel;
use App\Services\Content\PostService;
use CodeIgniter\RESTful\ResourceController;

class CategorySuggestion extends ResourceController
{
    public function suggest()
    {
        $title = trim((string) $this->request->getPost('title'));
        $content = trim((string) $this->request->getPost('content'));

        if ($title === '' || $content === '') {
            return $this->fail('Judul dan konten berita wajib diisi.', 400);
        }

        $hierarchical = (new CategoryModel())->getHierarchical();
        $subCategories = [];
        foreach ($hierarchical as $parent) {
            foreach ($parent['children'] ?? [] as $child) {
                $subCategories[] = [
                    'id'          => (int) $child['id'],
                    'name'        => $child['name'],
                    'parent_name' => $parent['name'],
                ];
            }
        }

        $suggestedIds = (new GeminiService())->suggestCategories(
            $title,
            $content,
            $subCategories,
            PostService::MAX_CATEGORIES
        );

        if (empty($suggestedIds)) {
            return $this->fail('Gagal memuat saran kategori. Silakan pilih kategori secara manual.', 503);
        }

        return $this->respond($suggestedIds);
    }
}
