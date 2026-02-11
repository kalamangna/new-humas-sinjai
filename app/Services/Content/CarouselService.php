<?php

namespace App\Services\Content;

use App\Models\CarouselSlideModel;

class CarouselService
{
    protected $carouselModel;

    public function __construct()
    {
        $this->carouselModel = new CarouselSlideModel();
    }

    public function getAllSlides()
    {
        return $this->carouselModel->orderBy('slide_order', 'ASC')->findAll();
    }

    public function getSlideById(int $id)
    {
        return $this->carouselModel->find($id);
    }

    public function createSlide(array $data)
    {
        return $this->carouselModel->save($data);
    }

    public function updateSlide(int $id, array $data)
    {
        return $this->carouselModel->update($id, $data);
    }

    public function deleteSlide(int $id)
    {
        return $this->carouselModel->delete($id);
    }
}
