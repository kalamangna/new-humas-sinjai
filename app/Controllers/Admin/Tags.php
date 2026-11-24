<?php

namespace App\Controllers\Admin;

use App\Services\Content\TagService;
use App\Models\TagModel;

class Tags extends BaseController
{
    protected $tagService;
    protected $tagModel;

    public function __construct()
    {
        $this->tagService = new TagService();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $filters = $this->request->getGet();
        $totalTags = $this->tagModel->countAllResults();
        $totalPosts = (new \App\Models\PostModel())->countAllResults();

        $this->tagModel->select('tags.*, (SELECT COUNT(*) FROM post_tags WHERE post_tags.tag_id = tags.id) as post_count');

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $this->tagModel->groupStart()
                ->like('tags.name', $search)
                ->orLike('tags.slug', $search)
                ->groupEnd();
        }

        $data = [
            'tags' => $this->tagModel->orderBy('tags.name', 'ASC')->paginate(20),
            'pager' => $this->tagModel->pager,
            'filters' => $filters,
            'total_tags' => $totalTags,
            'total_posts' => $totalPosts,
        ];
        return $this->render('admin/tags/index', $data);
    }

    public function new()
    {
        return $this->render('admin/tags/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->tagService->validate($data, $this->tagService->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->tagService->getErrors());
        }

        if ($this->tagService->saveTag($data)) {
            audit_log('tags', 'create', 'Menambah tag: ' . ($data['name'] ?? ''));
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat tag.');
    }

    public function edit($id = null)
    {
        $tag = $this->tagModel->find($id);
        if (!$tag) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        return $this->render('admin/tags/edit', ['tag' => $tag]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        if (!$this->tagService->validate($data, $this->tagService->getValidationRules((int)$id))) {
            return redirect()->back()->withInput()->with('errors', $this->tagService->getErrors());
        }

        if ($this->tagService->saveTag($data, (int)$id)) {
            audit_log('tags', 'update', 'Mengubah tag: ' . ($data['name'] ?? ''));
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui tag.');
    }

    public function delete($id = null)
    {
        $tag = $this->tagModel->find((int)$id);
        if ($this->tagService->deleteTag((int)$id)) {
            audit_log('tags', 'delete', 'Menghapus tag: ' . ($tag['name'] ?? "#{$id}"));
            return redirect()->to(base_url('admin/tags'))->with('success', 'Tag berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/tags'))->with('error', 'Gagal menghapus tag.');
    }

    public function search()
    {
        $query = trim((string) $this->request->getGet('q'));
        $exclude = (int) $this->request->getGet('exclude');

        $builder = $this->tagModel->select(
            'tags.id, tags.name, tags.slug, (SELECT COUNT(*) FROM post_tags WHERE post_tags.tag_id = tags.id) as post_count'
        );

        if ($exclude > 0) {
            $builder->where('tags.id !=', $exclude);
        }

        if ($query !== '') {
            $builder->groupStart()
                ->like('tags.name', $query)
                ->orLike('tags.slug', $query)
                ->groupEnd();
        }

        $results = $builder
            ->orderBy('post_count', 'DESC')
            ->orderBy('tags.name', 'ASC')
            ->findAll(20);

        return $this->response->setJSON($results);
    }

    public function merge()
    {
        $sourceId = (int) $this->request->getPost('source_id');
        $targetId = (int) $this->request->getPost('target_id');

        if ($sourceId <= 0 || $targetId <= 0 || $sourceId === $targetId) {
            return redirect()->back()->with('error', 'Pilih tag tujuan yang berbeda dari tag sumber.');
        }

        $source = $this->tagModel->find($sourceId);
        $target = $this->tagModel->find($targetId);
        if (!$source || !$target) {
            return redirect()->back()->with('error', 'Tag sumber atau tag tujuan tidak ditemukan.');
        }

        if ($this->tagService->mergeTags($sourceId, $targetId)) {
            audit_log('tags', 'merge', "Menggabungkan tag {$source['name']} ke {$target['name']}");
            return redirect()->back()->with('success', "Tag {$source['name']} berhasil digabungkan ke {$target['name']}.");
        }

        return redirect()->back()->with('error', 'Tag gagal digabungkan. Periksa kembali tag sumber dan tujuan.');
    }

    public function detectDuplicates()
    {
        try {
            $suggestions = $this->tagService->detectDuplicateSuggestions();
            return $this->response->setJSON([
                'suggestions' => $suggestions,
                'csrf_hash'   => csrf_hash(),
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[Admin\Tags::detectDuplicates] ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'error'     => 'Gagal memindai duplikat tag menggunakan AI.',
                'csrf_hash' => csrf_hash(),
            ]);
        }
    }

    public function mergeBatch()
    {
        $pairs = $this->request->getPost('pairs');
        if (!is_array($pairs) || empty($pairs)) {
            return redirect()->to(base_url('admin/tags'))->with('error', 'Pilih minimal satu pasangan tag untuk digabungkan.');
        }

        $result = $this->tagService->mergeBatch($pairs);
        foreach ($result['merged'] as $item) {
            audit_log('tags', 'merge', "Menggabungkan tag {$item['source_name']} ke {$item['target_name']}");
        }

        $mergedCount = count($result['merged']);
        if ($mergedCount > 0) {
            return redirect()->to(base_url('admin/tags'))->with('success', "{$mergedCount} pasangan tag berhasil digabungkan.");
        }

        return redirect()->to(base_url('admin/tags'))->with('error', 'Tidak ada pasangan tag yang berhasil digabungkan.');
    }
}