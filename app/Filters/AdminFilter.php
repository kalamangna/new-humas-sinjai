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

        // Basic check: must be admin or author
        if (!in_array($role, ['admin', 'author'])) {
            if ($request->isAJAX() || strpos($uri, 'api/') !== false) {
                return service('response')->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ]);
            }
            return redirect()->to(base_url('masuk'))->with('error', 'You do not have permission to access this page.');
        }

        // Restrict 'author' role
        if ($role === 'author') {
            $disallowedForAuthor = [
                'admin/users',
                'admin/site-settings',
                'admin/settings/update',
                'admin/audit-logs'
            ];

            // Specific exception: author is allowed to update their own account profile/password
            $isSelfUpdate = (strpos($uri, 'admin/users/update_settings') !== false);

            if (! $isSelfUpdate) {
                foreach ($disallowedForAuthor as $segment) {
                    if (strpos($uri, $segment) !== false) {
                        return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
                    }
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
