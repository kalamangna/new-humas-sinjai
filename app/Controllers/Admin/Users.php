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
        if ($this->userService->createUser($this->request->getPost())) {
            return redirect()->to(base_url('admin/users'))->with('success', 'Pengguna berhasil dibuat.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to create user.');
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
        if ($this->userService->updateUser((int)$id, $this->request->getPost())) {
            return redirect()->to(base_url('admin/users'))->with('success', 'Pengguna berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('errors', 'Failed to update user.');
    }

    public function delete($id = null)
    {
        if ($this->userService->deleteUser((int)$id)) {
            return redirect()->to(base_url('admin/users'))->with('success', 'Pengguna berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/users'))->with('error', 'Error deleting user.');
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
        $userId = $this->request->getPost('user_id');
        
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

        if ($this->userService->updateUser((int)$userId, $this->request->getPost())) {
            if (session()->get('user_id') == $userId) {
                session()->set('name', $this->request->getPost('name'));
                session()->set('email', $this->request->getPost('email'));
            }
            return redirect()->to(base_url('admin'))->with('message', 'Pengaturan profil berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengaturan profil.');
    }
}
