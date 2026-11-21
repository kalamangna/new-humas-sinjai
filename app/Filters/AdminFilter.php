<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Controllers\Auth\Login;

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
            // Sembunyikan panel admin dan rute login dari scanner publik dengan respon 404 murni
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Validasi batas tidak aktif (idle timeout) di sisi server
        $lastActivity = (int) session()->get('last_activity');
        if ($lastActivity > 0 && (time() - $lastActivity) > Login::IDLE_TIMEOUT_SECONDS) {
            session()->remove(['user_id', 'name', 'email', 'role', 'isLoggedIn', 'last_activity']);
            session()->regenerate(true);

            if ($request->isAJAX() || strpos($uri, 'api/') !== false) {
                return service('response')->setStatusCode(401)->setJSON([
                    'status' => 'error',
                    'message' => 'Sesi berakhir. Silakan login kembali.'
                ]);
            }

            return redirect()->to(base_url('masuk'))->with('error', 'Sesi berakhir. Silakan login kembali.');
        }

        session()->set('last_activity', time());

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
