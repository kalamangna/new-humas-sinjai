<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\TagModel;

class TagService extends BaseService
{
    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function getValidationRules(int $id = null): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => 'permit_empty|alpha_dash|is_unique[tags.slug,id,' . ($id ?? '0') . ']',
        ];
    }

    public function saveTag(array $data, ?int $id = null): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = url_title($data['name'], '-', true);
        }

        if ($id) {
            return $this->tagModel->update($id, $data);
        }

        return $this->tagModel->save($data);
    }

    public function deleteTag(int $id): bool
    {
        return $this->tagModel->delete($id);
    }

    public function suggestTags(string $title, string $content): array
    {
        $geminiService = new \App\Libraries\GeminiService();
        return $geminiService->suggestTags($title, $content);
    }
}