<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role');
        $uri = ltrim($request->getUri()->getPath(), '/');

        // Basic check: must be admin, author, or streamer
        if (!in_array($role, ['admin', 'author', 'streamer'])) {
            return redirect()->to('/login')->with('error', 'You do not have permission to access this page.');
        }

        // Restrict 'author' role
        if ($role === 'author') {
            $disallowedForAuthor = [
                'admin/live-streams',
                'admin/users'
            ];

            foreach ($disallowedForAuthor as $segment) {
                if ($uri === $segment || strpos($uri, $segment . '/') === 0) {
                    return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
                }
            }
        }

        // Restrict 'streamer' role to ONLY live-streams, dashboard, and their own profile
        if ($role === 'streamer') {
            $isAllowed = false;
            
            // Allow if URI is exactly 'admin' or starts with 'admin/'
            if ($uri === 'admin' || strpos($uri, 'admin/') === 0) {
                // Now check for disallowed sub-segments within admin
                $disallowedSegments = [
                    'admin/posts',
                    'admin/categories',
                    'admin/tags',
                    'admin/profiles',
                    'admin/carousel',
                    'admin/users',
                    'admin/analytics'
                ];

                $isAllowed = true; // Default allow 'admin/*'
                foreach ($disallowedSegments as $segment) {
                    if (strpos($uri, $segment) === 0) {
                        // Special exceptions within disallowed segments: profile and settings
                        if ($uri === 'admin/profile' || $uri === 'admin/settings' || $uri === 'admin/users/update_settings') {
                            $isAllowed = true;
                        } else {
                            $isAllowed = false;
                        }
                        break;
                    }
                }
                
                // Explicitly allow live-streams
                if (strpos($uri, 'admin/live-streams') === 0) {
                    $isAllowed = true;
                }
            }

            if (!$isAllowed) {
                return redirect()->to(base_url('admin/live-streams'))->with('error', 'Anda hanya memiliki akses untuk mengelola Live Streaming.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
