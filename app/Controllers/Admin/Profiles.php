<?php

namespace App\Controllers\Admin;

use App\Services\Content\ProfileService;
use App\Services\Media\MediaService;

class Profiles extends BaseController
{
    protected $profileService;
    protected $mediaService;

    public function __construct()
    {
        $this->profileService = new ProfileService();
        $this->mediaService = new MediaService();
    }

    public function index()
    {
        $data = [
            'profiles' => $this->profileService->getAllProfiles(),
        ];

        return $this->render('Admin/Profiles/index', $data);
    }

    public function new()
    {
        return $this->render('Admin/Profiles/new');
    }

    public function create()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $selectedType = $this->request->getPost('type');
        $isTableType = in_array($selectedType, ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa']);

        $image = null;
        if (!$isTableType) {
            $image = $this->mediaService->saveImage(
                $this->request->getPost('pasted_image') ?: $this->request->getFile('image'),
                'profiles',
                false // fit = false for profiles
            );
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'position'    => $this->request->getPost('position'),
            'institution' => $this->request->getPost('institution'),
            'type'        => $this->request->getPost('type'),
            'bio'         => $this->request->getPost('bio'),
            'image'       => $image,
            'order'       => (int)($this->request->getPost('order') ?? 0),
        ];

        if ($this->profileService->createProfile($data)) {
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to save profile.');
    }

    public function edit($id = null)
    {
        $profile = $this->profileService->getProfileById((int)$id);
        if (!$profile) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the profile: ' . $id);
        }

        return $this->render('Admin/Profiles/edit', ['profile' => $profile]);
    }

    public function update($id = null)
    {
        $profile = $this->profileService->getProfileById((int)$id);
        if (!$profile) {
            return redirect()->back()->with('error', 'Profile not found.');
        }

        if (!$this->validate($this->getValidationRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $selectedType = $this->request->getPost('type');
        $isTableType = in_array($selectedType, ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa']);

        $image = $profile['image'];
        if (!$isTableType) {
            $newImageSource = $this->request->getPost('pasted_image') ?: $this->request->getFile('image');
            if ($newImageSource && ($newImageSource instanceof \CodeIgniter\HTTP\FileUpload ? $newImageSource->getName() !== '' : true)) {
                $this->mediaService->deleteImage($profile['image']);
                $image = $this->mediaService->saveImage($newImageSource, 'profiles', false);
            }
        }

        $data = [
            'name'        => $this->request->getPost('name'),
            'position'    => $this->request->getPost('position'),
            'institution' => $this->request->getPost('institution'),
            'type'        => $this->request->getPost('type'),
            'bio'         => $this->request->getPost('bio'),
            'image'       => $image,
            'order'       => (int)($this->request->getPost('order') ?? 0),
        ];

        if ($this->profileService->updateProfile((int)$id, $data)) {
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update profile.');
    }

    public function delete($id = null)
    {
        $profile = $this->profileService->getProfileById((int)$id);
        if ($profile && $this->profileService->deleteProfile((int)$id)) {
            $this->mediaService->deleteImage($profile['image']);
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/profiles'))->with('error', 'Error deleting profile.');
    }

    protected function getValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'name' => 'permit_empty|max_length[255]',
            'type' => 'required|in_list[bupati,wakil-bupati,sekda,forkopimda,eselon-ii,eselon-iii,eselon-iv,kepala-desa]',
        ];

        $selectedType = $this->request->getPost('type');
        $isTableType = in_array($selectedType, ['forkopimda', 'eselon-ii', 'eselon-iii', 'eselon-iv', 'kepala-desa']);

        if (!$isUpdate && !$isTableType && empty($this->request->getPost('pasted_image'))) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]';
        }

        return $rules;
    }
}