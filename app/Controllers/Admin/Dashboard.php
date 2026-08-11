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

        $lastPost = $postModel->orderBy('published_at', 'DESC')->first();
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
            'lastPostUpdate' => $lastPost ? format_date($lastPost['published_at']) : 'N/A',
        ];

        return $this->render('admin/dashboard/index', $data);
    }

    public function optimizeImages()
    {
        helper(['image']);
        $uploadPath = FCPATH . 'uploads';

        if (!is_dir($uploadPath)) {
            return redirect()->back()->with('error', 'Folder uploads tidak ditemukan.');
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($uploadPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $count = 0;
        $savedBytes = 0;

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;

            $filePath = $file->getRealPath();
            $ext = strtolower($file->getExtension());

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

            $initialSize = filesize($filePath);
            if ($initialSize < 150 * 1024) continue;

            try {
                $tempOptimized = processImage($filePath, false);

                if ($tempOptimized && file_exists($tempOptimized)) {
                    $newSize = filesize($tempOptimized);

                    if ($newSize < $initialSize) {
                        copy($tempOptimized, $filePath);
                        @unlink($tempOptimized);
                        $savedBytes += ($initialSize - $newSize);
                        $count++;
                    } else {
                        @unlink($tempOptimized);
                    }
                }
            } catch (\Exception $e) {
                log_message('error', '[OptimizeImages Web] ' . $e->getMessage());
            }
        }

        $savedMB = round($savedBytes / (1024 * 1024), 2);
        return redirect()->back()->with('success', "Optimasi Selesai! {$count} gambar berhasil disusutkan. Total penghematan ruang: {$savedMB} MB.");
    }
}
