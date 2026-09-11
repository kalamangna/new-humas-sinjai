<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('admin'));
        }
        // show login form
        return view('auth/login');
    }

    public function login()
    {
        return $this->attemptLogin();
    }

    public function attemptLogin()
    {
        // Rate limiting: 5 attempts per minute per IP
        $throttler = \Config\Services::throttler();
        $ip = $this->request->getIPAddress();
        if ($throttler->check(md5('login_attempt_' . $ip), 5, MINUTE) === false) {
            return redirect()->back()->withInput()->with('error', 'Terlalu banyak percobaan login. Silakan tunggu 1 menit.');
        }

        // handle login attempt
        $session = session();
        $userModel = new UserModel();

        $email = trim((string)$this->request->getPost('email'));
        $password = (string)$this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID to prevent session fixation
            $session->regenerate();

            // Set session data
            $session->set([
                'user_id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'isLoggedIn' => true,
            ]);

            // Catat audit log login sukses
            audit_log('auth', 'login', 'Login berhasil', (int) $user['id'], $user['name'], $user['role']);

            // Auto-prune log > 90 hari saat Admin login
            if ($user['role'] === 'admin') {
                (new \App\Services\AuditLogService())->pruneOlderThan(90);
            }

            // Redirect to admin dashboard
            return redirect()->to(base_url('admin'));
        }

        // Catat audit log login gagal
        audit_log('auth', 'login_failed', 'Percobaan login gagal untuk: ' . ($email ?: 'tanpa email'));

        // Redirect back with error
        return redirect()->back()->withInput()->with('error', 'Email atau kata sandi tidak valid.');
    }

    public function logout()
    {
        if (session('isLoggedIn')) {
            audit_log('auth', 'logout', 'Logout dari sistem');
        }
        session()->destroy();
        return redirect()->to(base_url('masuk'));
    }
}
