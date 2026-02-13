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
            'name'      => 'permit_empty|max_length[255]',
            'position'  => 'required',
            'type'      => 'required',
            'bio'       => 'permit_empty',
            'order'     => 'required|numeric'
        ];

        if (!$isUpdate) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]';
        } else {
            $rules['image'] = 'permit_empty|max_size[image,2048]|is_image[image]';
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
                    if (!empty($old['image'])) {
                        $this->mediaService->deleteImage($old['image']);
                    }
                }
                $data['image'] = $photoPath;
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
            if (!empty($profile['image'])) {
                $this->mediaService->deleteImage($profile['image']);
            }
            return $this->profileModel->delete($id);
        }
        return false;
    }
}
