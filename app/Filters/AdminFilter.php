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
            
            // 1. Explicitly allow the dashboard (exact match or empty if filtered at group level)
            if ($uri === 'admin' || $uri === 'admin/') {
                $isAllowed = true;
            }
            // 2. Explicitly allow live-streams
            elseif (strpos($uri, 'admin/live-streams') === 0) {
                $isAllowed = true;
            }
            // 3. Allow profile and settings
            elseif ($uri === 'admin/profile' || $uri === 'admin/settings' || $uri === 'admin/users/update_settings') {
                $isAllowed = true;
            }

            if (!$isAllowed) {
                return redirect()->to(base_url('admin'))->with('error', 'Anda hanya memiliki akses untuk Dashboard dan Live Streaming.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
