<?php

namespace App\Controllers\Admin;

use App\Models\LiveStreamModel;

class LiveStreams extends BaseController
{
    protected $liveStreamModel;

    public function __construct()
    {
        $this->liveStreamModel = new LiveStreamModel();
    }

    public function index()
    {
        $streams = $this->liveStreamModel->orderBy('created_at', 'DESC')->findAll();
        $data = [
            'streams' => $streams,
            'count'   => count($streams),
            'title'   => 'Kelola Live Streaming'
        ];

        return $this->render('Admin/LiveStreams/index', $data);
    }

    public function new()
    {
        if ($this->liveStreamModel->countAllResults() > 0) {
            return redirect()->to(base_url('admin/live-streams'))->with('error', 'Hanya diperbolehkan memiliki 1 konfigurasi live stream. Silakan edit yang sudah ada.');
        }

        $data = [
            'title' => 'Tambah Live Streaming'
        ];
        return $this->render('Admin/LiveStreams/new', $data);
    }

    public function create()
    {
        if ($this->liveStreamModel->countAllResults() > 0) {
            return redirect()->to(base_url('admin/live-streams'))->with('error', 'Gagal: Record live stream sudah ada.');
        }

        $rules = [
            'title'    => 'required|min_length[3]|max_length[255]',
            'live_url' => 'required|valid_url|regex_match[/facebook\.com/]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->liveStreamModel->save([
            'title'     => $this->request->getPost('title'),
            'live_url'  => $this->request->getPost('live_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/live-streams'))->with('success', 'Live stream berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $stream = $this->liveStreamModel->find($id);
        if (!$stream) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Live stream tidak ditemukan: ' . $id);
        }

        $data = [
            'stream' => $stream,
            'title'  => 'Edit Live Streaming'
        ];

        return $this->render('Admin/LiveStreams/edit', $data);
    }

    public function update($id = null)
    {
        $rules = [
            'title'    => 'required|min_length[3]|max_length[255]',
            'live_url' => 'required|valid_url|regex_match[/facebook\.com/]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->liveStreamModel->update($id, [
            'title'     => $this->request->getPost('title'),
            'live_url'  => $this->request->getPost('live_url'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/live-streams'))->with('success', 'Live stream berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        if ($this->liveStreamModel->delete($id)) {
            return redirect()->to(base_url('admin/live-streams'))->with('success', 'Live stream berhasil dihapus.');
        }

        return redirect()->to(base_url('admin/live-streams'))->with('error', 'Gagal menghapus live stream.');
    }

    public function setActive($id = null)
    {
        if ($this->liveStreamModel->setActive((int)$id)) {
            return redirect()->to(base_url('admin/live-streams'))->with('success', 'Live stream diaktifkan.');
        }

        return redirect()->to(base_url('admin/live-streams'))->with('error', 'Gagal mengaktifkan live stream.');
    }

    public function deactivate($id = null)
    {
        if ($this->liveStreamModel->update($id, ['is_active' => 0])) {
            return redirect()->to(base_url('admin/live-streams'))->with('success', 'Live stream dinonaktifkan.');
        }

        return redirect()->to(base_url('admin/live-streams'))->with('error', 'Gagal menonaktifkan live stream.');
    }
}
