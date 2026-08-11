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
        @set_time_limit(60);
        @ini_set('memory_limit', '256M');

        helper(['image']);
        $uploadPath = FCPATH . 'uploads';

        if (!is_dir($uploadPath)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Folder uploads tidak ditemukan.'
            ]);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($uploadPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $count = 0;
        $savedBytes = 0;
        $processedFiles = [];
        $maxPerBatch = 10; // 10 file per AJAX batch request

        foreach ($iterator as $file) {
            if ($file->isDir()) continue;

            $filePath = $file->getRealPath();
            $ext = strtolower($file->getExtension());

            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) continue;

            $initialSize = filesize($filePath);
            if ($initialSize < 150 * 1024) continue;

            $relativePath = str_replace(FCPATH, '', $filePath);

            try {
                $tempOptimized = processImage($filePath, false);

                if ($tempOptimized && file_exists($tempOptimized)) {
                    $newSize = filesize($tempOptimized);

                    if ($newSize < $initialSize) {
                        copy($tempOptimized, $filePath);
                        @unlink($tempOptimized);
                        $saved = $initialSize - $newSize;
                        $savedBytes += $saved;
                        $count++;
                        $processedFiles[] = [
                            'file' => $relativePath,
                            'old_size_kb' => round($initialSize / 1024, 2),
                            'new_size_kb' => round($newSize / 1024, 2),
                            'saved_kb' => round($saved / 1024, 2)
                        ];
                    } else {
                        @unlink($tempOptimized);
                    }
                }
            } catch (\Throwable $e) {
                log_message('error', '[OptimizeImages Web] ' . $e->getMessage());
            }

            if ($count >= $maxPerBatch) {
                break;
            }
        }

        $savedMB = round($savedBytes / (1024 * 1024), 2);

        return $this->response->setJSON([
            'status' => 'success',
            'processed_count' => $count,
            'saved_mb' => $savedMB,
            'files' => $processedFiles,
            'has_more' => ($count >= $maxPerBatch)
        ]);
    }
}
