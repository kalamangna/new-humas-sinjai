<?php

namespace App\Controllers\Admin;

use App\Services\Content\ProfileService;
use App\Models\ProfileModel;

class Profiles extends BaseController
{
    protected $profileService;
    protected $profileModel;

    public function __construct()
    {
        $this->profileService = new ProfileService();
        $this->profileModel = new ProfileModel();
    }

    public function index()
    {
        $data = [
            'profiles' => $this->profileModel->orderBy('type', 'ASC')->orderBy('order', 'ASC')->findAll(),
        ];
        return $this->render('admin/profiles/index', $data);
    }

    public function new()
    {
        return $this->render('admin/profiles/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->profileService->validate($data, $this->profileService->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->profileService->getErrors());
        }

        if ($this->profileService->saveProfile($data, $this->request->getFile('image'))) {
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil disimpan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan profil.');
    }

    public function edit($id = null)
    {
        $profile = $this->profileModel->find($id);
        if (!$profile) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        return $this->render('admin/profiles/edit', ['profile' => $profile]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        if (!$this->profileService->validate($data, $this->profileService->getValidationRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->profileService->getErrors());
        }

        if ($this->profileService->saveProfile($data, $this->request->getFile('image'), (int)$id)) {
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
    }

    public function delete($id = null)
    {
        if ($this->profileService->deleteProfile((int)$id)) {
            return redirect()->to(base_url('admin/profiles'))->with('success', 'Profil berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/profiles'))->with('error', 'Gagal menghapus profil.');
    }
}