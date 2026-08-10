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
}
