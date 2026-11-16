<?php

namespace App\Controllers\Admin;

use App\Services\Auth\UserService;

class Users extends BaseController
{
    protected $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        $filters = ['search' => $this->request->getGet('search')];
        $result = $this->userService->getAdminUsers($filters);
        $stats = $this->userService->getUserStats();

        $data = array_merge($result, [
            'filters'         => $filters,
            'total_users'     => $stats['total'],
            'admin_users'     => $stats['admin'],
            'author_users'    => $stats['author'],
            'current_user_id' => session()->get('user_id'),
        ]);

        return $this->render('admin/users/index', $data);
    }

    public function new()
    {
        return $this->render('admin/users/new');
    }

    public function create()
    {
        $validationRules = [
            'name'     => 'required|min_length[3]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'role'     => 'required|in_list[admin,author]',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'name'     => trim((string) $this->request->getPost('name')),
            'email'    => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'role'     => (string) $this->request->getPost('role'),
        ];

        if ($this->userService->createUser($userData)) {
            audit_log('users', 'create', 'Menambah user: ' . $userData['name']);
            return redirect()->to(base_url('admin/users'))->with('success', 'User berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat user baru.');
    }

    public function show($id = null)
    {
        $user = $this->userService->getUserById((int)$id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the user: ' . $id);
        }
        return $this->render('admin/users/show', ['user' => $user]);
    }

    public function edit($id = null)
    {
        $user = $this->userService->getUserById((int)$id);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the user: ' . $id);
        }
        return $this->render('admin/users/edit', ['user' => $user]);
    }

    public function update($id = null)
    {
        $id = (int) $id;
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $validationRules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role'  => 'required|in_list[admin,author]',
        ];

        if ($this->request->getPost('password')) {
            $validationRules['password'] = 'min_length[8]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role = (string) $this->request->getPost('role');
        if ($id === (int) session()->get('user_id') && $role !== 'admin') {
            return redirect()->back()->withInput()->with('error', 'Role akun yang sedang digunakan tidak dapat diubah.');
        }

        $updateData = [
            'name'  => trim((string) $this->request->getPost('name')),
            'email' => trim((string) $this->request->getPost('email')),
            'role'  => $role,
        ];

        if ($this->request->getPost('password')) {
            $updateData['password'] = (string) $this->request->getPost('password');
        }

        if ($this->userService->updateUser($id, $updateData)) {
            audit_log('users', 'update', 'Mengubah user: ' . $updateData['name']);
            return redirect()->to(base_url('admin/users'))->with('success', 'User berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data user.');
    }

    public function delete($id = null)
    {
        $id = (int) $id;
        if ($id === (int) session()->get('user_id')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $user = $this->userService->getUserById($id);
        if (!$user) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        if ($this->userService->deleteUser($id)) {
            audit_log('users', 'delete', 'Menghapus user: ' . ($user['name'] ?? "#{$id}"));
            return redirect()->to(base_url('admin/users'))->with('success', 'User berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/users'))->with('error', 'Gagal menghapus user.');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userService->getUserById((int)$userId);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the user profile.');
        }
        return $this->render('admin/users/profile', ['user' => $user]);
    }

    public function settings()
    {
        $userId = session()->get('user_id');
        $user = $this->userService->getUserById((int)$userId);
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find user settings.');
        }
        return $this->render('admin/users/settings', ['user' => $user]);
    }

    public function update_settings()
    {
        $userId = (int) session()->get('user_id');
        if (!$userId) {
            return redirect()->to(base_url('masuk'))->with('error', 'Sesi login tidak valid.');
        }
        
        $validationRules = [
            'name'  => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']'
        ];

        if ($this->request->getPost('password')) {
            $validationRules['password'] = 'min_length[8]';
            $validationRules['password_confirm'] = 'matches[password]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Only allow updating safe profile fields (prevent role tampering)
        $updateData = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];
        if ($this->request->getPost('password')) {
            $updateData['password'] = $this->request->getPost('password');
        }

        if ($this->userService->updateUser($userId, $updateData)) {
            session()->set('name', $updateData['name']);
            session()->set('email', $updateData['email']);
            audit_log('users', 'update', 'Memperbarui profil akun sendiri');
            return redirect()->to(base_url('admin'))->with('message', 'Pengaturan profil berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan profil.');
    }
}
