<?php

namespace App\Services\Content;

use App\Models\TagModel;

class TagService
{
    protected $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function getAdminTags(array $filters = [], int $perPage = 10)
    {
        $builder = $this->tagModel
            ->select('tags.*, COUNT(post_tags.post_id) as post_count')
            ->join('post_tags', 'post_tags.tag_id = tags.id', 'left')
            ->groupBy('tags.id');

        if (!empty($filters['search'])) {
            $builder->like('tags.name', $filters['search']);
        }

        return [
            'tags'  => $builder->orderBy('tags.name', 'ASC')->paginate($perPage),
            'pager' => $this->tagModel->pager
        ];
    }

    public function createTag(array $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        return $this->tagModel->save($data);
    }

    public function updateTag(int $id, array $data)
    {
        $data['slug'] = url_title($data['name'], '-', true);
        return $this->tagModel->update($id, $data);
    }

    public function deleteTag(int $id)
    {
        return $this->tagModel->delete($id);
    }
}
