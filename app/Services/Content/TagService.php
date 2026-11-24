<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Models\TagModel;

class TagService extends BaseService
{
    private const PREFERRED_TAGS_CACHE_KEY = 'preferred_existing_tags';
    private const PREFERRED_TAGS_CACHE_TTL = 3600;

    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function getValidationRules(?int $id = null): array
    {
        return [
            'name' => 'required|min_length[2]|max_length[255]',
            'slug' => 'permit_empty|alpha_dash|is_unique[tags.slug,id,' . ($id ?? '0') . ']',
        ];
    }

    public function saveTag(array $data, ?int $id = null): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->createAsciiSlug($data['name']);
        }

        if ($id) {
            $result = $this->tagModel->update($id, $data);
        } else {
            $result = $this->tagModel->save($data);
        }

        if ($result) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
        }

        return $result;
    }

    public function deleteTag(int $id): bool
    {
        $result = $this->tagModel->delete($id);
        if ($result) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
        }

        return $result;
    }

    /**
     * Gabungkan tag sumber ke tag tujuan dalam satu transaksi database.
     */
    public function mergeTags(int $sourceId, int $targetId): bool
    {
        if ($sourceId <= 0 || $targetId <= 0 || $sourceId === $targetId) {
            return false;
        }

        $source = $this->tagModel->find($sourceId);
        $target = $this->tagModel->find($targetId);
        if (!$source || !$target) {
            return false;
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $db->query(
            'INSERT IGNORE INTO post_tags (post_id, tag_id) SELECT post_id, ? FROM post_tags WHERE tag_id = ?',
            [$targetId, $sourceId]
        );
        $this->tagModel->delete($sourceId);
        $db->transComplete();

        if ($db->transStatus()) {
            cache()->delete(self::PREFERRED_TAGS_CACHE_KEY);
            return true;
        }

        return false;
    }

    /**
     * Ambil daftar tag populer eksisting dari cache atau database untuk referensi AI.
     *
     * @return string[]
     */
    public function getPreferredExistingTags(int $limit = 150, int $minPosts = 3): array
    {
        $cached = cache(self::PREFERRED_TAGS_CACHE_KEY);
        if (is_array($cached)) {
            return $cached;
        }

        $tags = $this->tagModel->getPreferredTags($limit, $minPosts);
        cache()->save(self::PREFERRED_TAGS_CACHE_KEY, $tags, self::PREFERRED_TAGS_CACHE_TTL);

        return $tags;
    }

    public function suggestTags(string $title, string $content): array
    {
        $existingTags = $this->getPreferredExistingTags();
        $geminiService = new \App\Libraries\GeminiService();
        return $geminiService->suggestTags($title, $content, $existingTags);
    }
}