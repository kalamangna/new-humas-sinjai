<?php

namespace App\Services\Content;

use App\Models\ProfileModel;

class ProfileService
{
    protected $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    public function getAllProfiles()
    {
        return $this->profileModel->orderBy('order', 'ASC')->orderBy('created_at', 'DESC')->findAll();
    }

    public function getProfileById(int $id)
    {
        return $this->profileModel->find($id);
    }

    public function createProfile(array $data)
    {
        if (empty($data['slug'])) {
            $slugSource = $data['name'] ?: ($data['position'] ?: $data['type']);
            $slug = url_title($slugSource, '-', true);
            
            if (empty($slug) || $this->profileModel->where('slug', $slug)->first()) {
                $slug = ($slug ?: 'profile') . '-' . uniqid();
            }
            $data['slug'] = $slug;
        }

        return $this->profileModel->save($data);
    }

    public function updateProfile(int $id, array $data)
    {
        return $this->profileModel->update($id, $data);
    }

    public function deleteProfile(int $id)
    {
        return $this->profileModel->delete($id);
    }
}
