<?php

namespace App\Controllers\Admin;

use App\Services\Content\CarouselService;
use App\Models\CarouselSlideModel;

class Carousel extends BaseController
{
    protected $carouselService;
    protected $carouselModel;

    public function __construct()
    {
        $this->carouselService = new CarouselService();
        $this->carouselModel = new CarouselSlideModel();
    }

    public function index()
    {
        $data = [
            'slides' => $this->carouselModel->orderBy('slide_order', 'ASC')->findAll(),
        ];
        return $this->render('admin/carousel/index', $data);
    }

    public function new()
    {
        return $this->render('admin/carousel/new');
    }

    public function create()
    {
        $data = $this->request->getPost();
        
        if (!$this->carouselService->validate($data, $this->carouselService->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->carouselService->getErrors());
        }

        if ($this->carouselService->saveSlide($data, $this->request->getFile('image'))) {
            return redirect()->to(base_url('admin/carousel'))->with('success', 'Slide berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan slide.');
    }

    public function edit($id = null)
    {
        $slide = $this->carouselModel->find($id);
        if (!$slide) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        return $this->render('admin/carousel/edit', ['slide' => $slide]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        
        if (!$this->carouselService->validate($data, $this->carouselService->getValidationRules(true))) {
            return redirect()->back()->withInput()->with('errors', $this->carouselService->getErrors());
        }

        if ($this->carouselService->saveSlide($data, $this->request->getFile('image'), (int)$id)) {
            return redirect()->to(base_url('admin/carousel'))->with('success', 'Slide berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui slide.');
    }

    public function delete($id = null)
    {
        if ($this->carouselService->deleteSlide((int)$id)) {
            return redirect()->to(base_url('admin/carousel'))->with('success', 'Slide berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/carousel'))->with('error', 'Gagal menghapus slide.');
    }
}
