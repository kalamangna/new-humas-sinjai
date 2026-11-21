<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Controllers\Auth\Login;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(site_url('masuk'));
        }

        $lastActivity = (int) session()->get('last_activity');
        if ($lastActivity > 0 && (time() - $lastActivity) > Login::IDLE_TIMEOUT_SECONDS) {
            session()->remove(['user_id', 'name', 'email', 'role', 'isLoggedIn', 'last_activity']);
            session()->regenerate(true);
            return redirect()->to(site_url('masuk'))->with('error', 'Sesi berakhir. Silakan login kembali.');
        }

        session()->set('last_activity', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
