<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Services\Media\MediaService;
use App\Models\CarouselSlideModel;

class CarouselService extends BaseService
{
    protected $carouselModel;
    protected $mediaService;

    public function __construct()
    {
        $this->carouselModel = new CarouselSlideModel();
        $this->mediaService = new MediaService();
    }

    public function getValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'title'       => 'required',
            'slide_order' => 'required|numeric'
        ];

        if (!$isUpdate) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]';
        }

        return $rules;
    }

    public function saveSlide(array $data, $imageFile, ?int $id = null): bool
    {
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imagePath = $this->mediaService->saveImage($imageFile, 'carousel', true); 
            if ($imagePath) {
                if ($id) {
                    $old = $this->carouselModel->find($id);
                    $this->mediaService->deleteImage($old['image_path']);
                }
                $data['image_path'] = $imagePath;
            }
        }

        if ($id) {
            return $this->carouselModel->update($id, $data);
        }

        return $this->carouselModel->save($data);
    }

    public function deleteSlide(int $id): bool
    {
        $slide = $this->carouselModel->find($id);
        if ($slide) {
            $this->mediaService->deleteImage($slide['image_path']);
            return $this->carouselModel->delete($id);
        }
        return false;
    }
}