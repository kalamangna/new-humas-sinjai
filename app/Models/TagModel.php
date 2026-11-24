<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table = 'tags';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'slug'];

    public function getPopularTags(int $limit = 10): array
    {
        return $this->select('tags.name, tags.slug, COUNT(post_tags.post_id) as post_count')
                    ->join('post_tags', 'post_tags.tag_id = tags.id')
                    ->groupBy('tags.id')
                    ->orderBy('post_count', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Ambil daftar nama tag populer sebagai referensi controlled vocabulary AI.
     *
     * @return string[]
     */
    public function getPreferredTags(int $limit = 150, int $minPosts = 3): array
    {
        $rows = $this->select('tags.name, COUNT(post_tags.post_id) as post_count')
            ->join('post_tags', 'post_tags.tag_id = tags.id')
            ->groupBy('tags.id')
            ->having('COUNT(post_tags.post_id) >=', max(1, $minPosts))
            ->orderBy('post_count', 'DESC')
            ->limit(max(1, $limit))
            ->findAll();

        return array_values(array_filter(array_column($rows, 'name')));
    }
}
