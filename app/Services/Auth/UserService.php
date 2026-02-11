<?php

namespace App\Services\Auth;

use App\Models\UserModel;

class UserService
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAdminUsers(array $filters = [], int $perPage = 10)
    {
        $builder = $this->userModel
            ->select('users.*, COUNT(posts.id) as post_count')
            ->join('posts', 'posts.user_id = users.id', 'left')
            ->groupBy('users.id');

        if (!empty($filters['search'])) {
            $builder->like('users.name', $filters['search']);
        }

        return [
            'users' => $builder->orderBy('users.name', 'ASC')->paginate($perPage),
            'pager' => $this->userModel->pager
        ];
    }

    public function getUserStats()
    {
        return [
            'total'   => $this->userModel->countAllResults(),
            'admin'   => $this->userModel->where('role', 'admin')->countAllResults(),
            'author'  => $this->userModel->where('role', 'author')->countAllResults(),
        ];
    }

    public function getUserById(int $id)
    {
        return $this->userModel->find($id);
    }

    public function createUser(array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->userModel->save($data);
    }

    public function updateUser(int $id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return $this->userModel->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userModel->delete($id);
    }
}
