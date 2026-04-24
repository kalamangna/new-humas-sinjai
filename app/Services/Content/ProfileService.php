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
        helper('url');
        $this->profileModel = new ProfileModel();
        $this->mediaService = new MediaService();
    }

    public function getValidationRules(?string $type = null, bool $isUpdate = false): array
    {
        $hideImageTypes = ['forkopimda', 'eselon-ii', 'eselon-iii', 'lurah', 'kepala-desa'];
        $hasBioAndImage = !in_array($type, $hideImageTypes);

        $rules = [
            'name'      => 'required|max_length[255]',
            'position'  => 'required',
            'type'      => 'required',
            'bio'       => $hasBioAndImage ? 'required' : 'permit_empty',
            'order'     => in_array($type, ['kepala-desa', 'lurah']) ? 'permit_empty|integer' : 'required|integer',
            'kecamatan' => in_array($type, ['kepala-desa', 'lurah']) ? 'required|max_length[100]' : 'permit_empty|max_length[100]',
            'institution' => 'required',
            'kelurahan' => 'permit_empty|max_length[100]',
            'desa'      => 'permit_empty|max_length[100]',
        ];

        $isImageOptional = $isUpdate || !$hasBioAndImage;

        if ($isImageOptional) {
            $rules['image'] = 'permit_empty|max_size[image,2048]|is_image[image]';
        } else {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]';
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

        // Generate Slug
        $name = trim($data['name'] ?? '');
        $position = trim($data['position'] ?? '');
        
        $slugBase = ($name !== '' && $name !== '-') ? $name : ($position !== '' ? $position : 'profile');
        $slug = url_title($slugBase, '-', true);
        
        // If url_title still returns empty (e.g. all special chars), fallback to a random string or position slug
        if (empty($slug)) {
            $slug = url_title($position ?: 'profile', '-', true) ?: 'profile-' . time();
        }

        if (!$id) {
            $data['slug'] = $this->generateUniqueSlug($slug);
        } else {
            $existing = $this->profileModel->find($id);
            // Re-generate slug if name or position changed significantly, or if it doesn't have one
            $oldSlugBase = !empty($existing['name']) ? $existing['name'] : ($existing['position'] ?? '');
            if (empty($existing['slug']) || $slugBase !== $oldSlugBase) {
                $data['slug'] = $this->generateUniqueSlug($slug, $id);
            }
        }

        if ($id) {
            return $this->profileModel->update($id, $data);
        }

        return $this->profileModel->save($data);
    }

    protected function generateUniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $builder = $this->profileModel->where('slug', $slug);
            if ($excludeId) {
                $builder->where('id !=', $excludeId);
            }

            if ($builder->countAllResults() === 0) {
                return $slug;
            }

            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
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
