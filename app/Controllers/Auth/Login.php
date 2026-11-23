<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Login extends BaseController
{
    public const IDLE_TIMEOUT_SECONDS = 1800;

    public function index()
    {
        if (session()->get('isLoggedIn')) {
            $lastActivity = (int) session()->get('last_activity');
            if ($lastActivity > 0 && (time() - $lastActivity) > self::IDLE_TIMEOUT_SECONDS) {
                session()->remove(['user_id', 'name', 'email', 'role', 'isLoggedIn', 'last_activity']);
                session()->regenerate(true);
                session()->setFlashdata('error', 'Sesi berakhir. Silakan login kembali.');
                return view('auth/login');
            }
            return redirect()->to(base_url('admin'));
        }
        // show login form
        return view('auth/login');
    }

    public function login()
    {
        return $this->attemptLogin();
    }

    public static function sanitizeRedirectPath(?string $raw): ?string
    {
        if ($raw === null) {
            return null;
        }

        $raw = trim($raw);
        if ($raw === '' || preg_match('/[\x00-\x1F\x7F]/', $raw) === 1 || str_starts_with($raw, '//')) {
            return null;
        }

        $parts = parse_url($raw);
        if ($parts === false) {
            return null;
        }

        if (isset($parts['scheme']) && !in_array(strtolower($parts['scheme']), ['http', 'https'], true)) {
            return null;
        }

        if (isset($parts['host'])) {
            $baseHost = parse_url(base_url(), PHP_URL_HOST);
            if (!$baseHost || strcasecmp($parts['host'], $baseHost) !== 0) {
                return null;
            }
        }

        $path = $parts['path'] ?? '';
        $basePath = trim((string) parse_url(base_url(), PHP_URL_PATH), '/');
        $normalized = ltrim($path, '/');

        if ($basePath !== '' && ($normalized === $basePath || str_starts_with($normalized, $basePath . '/'))) {
            $normalized = ltrim(substr($normalized, strlen($basePath)), '/');
        }

        if ($normalized !== 'admin' && !str_starts_with($normalized, 'admin/')) {
            return null;
        }

        if ($normalized === 'admin/session/ping' || str_starts_with($normalized, 'admin/session/ping/')) {
            return null;
        }

        $query = isset($parts['query']) && $parts['query'] !== '' ? '?' . $parts['query'] : '';
        return $normalized . $query;
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
                'last_activity' => time(),
            ]);

            // Catat audit log login sukses
            audit_log('auth', 'login', 'Login berhasil', (int) $user['id'], $user['name'], $user['role']);

            // Auto-prune log > 90 hari saat Admin login
            if ($user['role'] === 'admin') {
                (new \App\Services\AuditLogService())->pruneOlderThan(90);
            }

            $redirectPath = self::sanitizeRedirectPath($session->get('redirect_after_login'));
            $session->remove('redirect_after_login');

            // Redirect to last admin page or admin dashboard
            return redirect()->to($redirectPath ? base_url($redirectPath) : base_url('admin'));
        }

        // Catat audit log login gagal
        audit_log('auth', 'login_failed', 'Percobaan login gagal untuk: ' . ($email ?: 'tanpa email'));

        // Redirect back with error
        return redirect()->back()->withInput()->with('error', 'Email atau kata sandi tidak valid.');
    }

    public function logout()
    {
        $isTimeout = $this->request->getGet('reason') === 'timeout';

        if (session('isLoggedIn') && !$isTimeout) {
            audit_log('auth', 'logout', 'Logout dari sistem');
        }

        if ($isTimeout) {
            $next = self::sanitizeRedirectPath($this->request->getGet('next'));

            // Hapus data autentikasi dan regenerasi ID sesi agar flashdata tetap terbawa ke halaman login
            session()->remove(['user_id', 'name', 'email', 'role', 'isLoggedIn', 'last_activity']);
            session()->regenerate(true);

            if ($next !== null) {
                session()->set('redirect_after_login', $next);
            }

            return redirect()->to(base_url('masuk'))->with('error', 'Sesi berakhir. Silakan login kembali.');
        }

        session()->destroy();
        return redirect()->to(base_url('masuk'));
    }

    public function ping()
    {
        return $this->response->setJSON(['status' => 'ok']);
    }
}
