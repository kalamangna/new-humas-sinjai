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
        $search = $this->request->getGet('search');
        $type = $this->request->getGet('type');

        $query = $this->profileModel;

        if ($type) {
            $query->where('type', $type);
            // Custom sorting per type
            if ($type === 'lurah') {
                $query->orderBy('institution', 'ASC');
            } elseif ($type === 'kepala-desa') {
                $query->orderBy('kecamatan', 'ASC')->orderBy('institution', 'ASC');
            }
        } else {
            // Default type sorting for mixed view
            $query->orderBy('type', 'ASC');
        }

        // Common sorting fallback
        $query->orderBy('order', 'ASC')->orderBy('created_at', 'ASC');

        if ($search) {
            $query->groupStart()
                  ->like('name', $search)
                  ->orLike('position', $search)
                  ->orLike('institution', $search)
                  ->groupEnd();
        }

        $data = [
            'profiles' => $query->paginate(20, 'profiles'),
            'pager' => $this->profileModel->pager,
            'filters' => [
                'search' => $search,
                'type' => $type
            ]
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
        
        if (!$this->profileService->validate($data, $this->profileService->getValidationRules($data['type'] ?? null))) {
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
        
        if (!$this->profileService->validate($data, $this->profileService->getValidationRules($data['type'] ?? null, true))) {
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

    public function get_kecamatan()
    {
        $client = \Config\Services::curlrequest();
        $response = $client->get('http://apps.sinjaikab.go.id/api/pegawai/get_kecamatan');
        return $this->response->setJSON($response->getBody());
    }

    public function get_wilayah()
    {
        $tipe = $this->request->getGet('tipe');
        $client = \Config\Services::curlrequest();
        $response = $client->get('http://apps.sinjaikab.go.id/api/pegawai/get_wilayah', [
            'query' => ['tipe' => $tipe]
        ]);
        return $this->response->setJSON($response->getBody());
    }
}