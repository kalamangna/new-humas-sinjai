<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Services\Media\MediaService;
use App\Models\ProfileModel;

class ProfileService extends BaseService
{
    protected $profileModel;
    protected $mediaService;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
        $this->mediaService = new MediaService();
    }

    public function getValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[255]',
            'position'  => 'required',
            'category'  => 'required',
            'bio'       => 'required',
            'sort_order' => 'required|numeric'
        ];

        if (!$isUpdate) {
            $rules['photo'] = 'uploaded[photo]|max_size[photo,2048]|is_image[photo]';
        }

        return $rules;
    }

    public function saveProfile(array $data, $photoFile, ?int $id = null): bool
    {
        if ($photoFile && $photoFile->isValid() && !$photoFile->hasMoved()) {
            $photoPath = $this->mediaService->saveImage($photoFile, 'profiles', false);
            if ($photoPath) {
                if ($id) {
                    $old = $this->profileModel->find($id);
                    $this->mediaService->deleteImage($old['photo_url']);
                }
                $data['photo_url'] = $photoPath;
            }
        }

        if ($id) {
            return $this->profileModel->update($id, $data);
        }

        return $this->profileModel->save($data);
    }

    public function deleteProfile(int $id): bool
    {
        $profile = $this->profileModel->find($id);
        if ($profile) {
            $this->mediaService->deleteImage($profile['photo_url']);
            return $this->profileModel->delete($id);
        }
        return false;
    }
}
