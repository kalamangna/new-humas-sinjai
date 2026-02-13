<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Services\Content\TagService;

class TagSuggestion extends ResourceController
{
    protected $tagService;

    public function __construct()
    {
        $this->tagService = new TagService();
    }

    public function suggest()
    {
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');

        if (empty($title) || empty($content)) {
            return $this->fail('Title and content are required.', 400);
        }

        $suggestedTags = $this->tagService->suggestTags($title, $content);

        return $this->respond($suggestedTags);
    }
}