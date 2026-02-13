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
            return redirect()->to(base_url('login'))->with('error', 'You do not have permission to access this page.');
        }

        // Restrict 'author' role
        if ($role === 'author') {
            $disallowedForAuthor = [
                'admin/users'
            ];

            foreach ($disallowedForAuthor as $segment) {
                if ($uri === $segment || strpos($uri, $segment . '/') === 0) {
                    return redirect()->to(base_url('admin'))->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
