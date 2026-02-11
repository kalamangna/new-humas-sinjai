<?php

namespace App\Controllers\Admin;

use App\Services\Content\CarouselService;
use App\Services\Media\MediaService;

class Carousel extends BaseController
{
    protected $carouselService;
    protected $mediaService;

    public function __construct()
    {
        $this->carouselService = new CarouselService();
        $this->mediaService = new MediaService();
    }

    public function index()
    {
        $data = [
            'slides' => $this->carouselService->getAllSlides(),
        ];

        return $this->render('Admin/Carousel/index', $data);
    }

    public function new()
    {
        return $this->render('Admin/Carousel/new');
    }

    public function create()
    {
        $validationRules = [
            'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'slide_order' => 'is_natural_no_zero',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $imagePath = $this->mediaService->saveImage($this->request->getFile('image'), 'carousel', false);

        if ($imagePath) {
            $this->carouselService->createSlide([
                'image_path' => $imagePath,
                'slide_order' => $this->request->getPost('slide_order'),
            ]);

            return redirect()->to(base_url('/admin/carousel'))->with('success', 'Slide berhasil dibuat.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengunggah gambar.');
    }

    public function edit($id = null)
    {
        $slide = $this->carouselService->getSlideById((int)$id);
        if (!$slide) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the slide: ' . $id);
        }

        return $this->render('Admin/Carousel/edit', ['slide' => $slide]);
    }

    public function update($id = null)
    {
        $validationRules = [
            'image' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            'slide_order' => 'is_natural_no_zero',
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $slide = $this->carouselService->getSlideById((int)$id);
        if (!$slide) {
            return redirect()->back()->with('error', 'Slide not found.');
        }

        $data = [
            'slide_order' => $this->request->getPost('slide_order'),
        ];

        $newImage = $this->request->getFile('image');
        if ($newImage && $newImage->isValid() && !$newImage->hasMoved()) {
            $this->mediaService->deleteImage($slide['image_path']);
            $data['image_path'] = $this->mediaService->saveImage($newImage, 'carousel', false);
        }

        $this->carouselService->updateSlide((int)$id, $data);

        return redirect()->to(base_url('/admin/carousel'))->with('success', 'Slide berhasil diperbarui.');
    }

    public function delete($id = null)
    {
        $slide = $this->carouselService->getSlideById((int)$id);
        if ($slide && $this->carouselService->deleteSlide((int)$id)) {
            $this->mediaService->deleteImage($slide['image_path']);
            return redirect()->to(base_url('/admin/carousel'))->with('success', 'Slide berhasil dihapus.');
        }

        return redirect()->to(base_url('/admin/carousel'))->with('error', 'Error deleting slide.');
    }
}