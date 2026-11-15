<?php

namespace App\Controllers\Admin;

use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $postModel = new PostModel();
        $categoryModel = new CategoryModel();
        $tagModel = new TagModel();
        $userModel = new UserModel();

        $data = [
            'postCount' => $postModel->countAllResults(),
            'categoryCount' => $categoryModel->countAllResults(),
            'tagCount' => $tagModel->countAllResults(),
            'userCount' => $userModel->countAllResults(),
            'recentPosts' => $postModel
                ->select('posts.title, posts.published_at, users.name as author_name')
                ->join('users', 'users.id = posts.user_id', 'left')
                ->where('posts.status', 'published')
                ->orderBy('posts.published_at', 'DESC')
                ->limit(5)
                ->findAll(),
        ];

        return $this->render('admin/dashboard/index', $data);
    }
}
