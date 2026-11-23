<?php

namespace App\Services\Content;

use App\Services\BaseService;
use App\Services\Media\MediaService;
use App\Models\ProfileModel;

class ProfileService extends BaseService
{
    public const TYPE_LABELS = [
        'bupati'            => 'Bupati',
        'wakil-bupati'      => 'Wakil Bupati',
        'sekda'             => 'Sekretaris Daerah',
        'forkopimda'        => 'Forkopimda',
        'asisten-staf-ahli' => 'Asisten dan Staf Ahli',
        'kepala-opd'        => 'Kepala OPD',
        'kabag-setda'       => 'Kepala Bagian Setda',
        'camat'             => 'Camat',
        'lurah'             => 'Lurah',
        'kepala-desa'       => 'Kepala Desa',
    ];

    public const OFFICIAL_DIRECTORY_TYPES = [
        'forkopimda',
        'asisten-staf-ahli',
        'kepala-opd',
        'kabag-setda',
        'camat',
        'lurah',
        'kepala-desa',
    ];

    public const MANUAL_ORDER_TYPES = [
        'forkopimda',
        'asisten-staf-ahli',
        'kepala-opd',
        'kabag-setda',
        'camat',
    ];

    public const SLUG_TYPES = [
        'bupati',
        'wakil-bupati',
        'sekda',
    ];

    public const REGION_TYPES = [
        'lurah',
        'kepala-desa',
    ];

    public const KEPALA_DESA_POSITIONS = [
        'Kepala Desa',
        'Plt. Kepala Desa',
        'Pj. Kepala Desa',
    ];

    public const STATIC_POSITIONS = [
        'camat'       => ['Camat', 'Plt. Camat', 'Pj. Camat'],
        'lurah'       => ['Lurah', 'Plt. Lurah', 'Pj. Lurah'],
        'kepala-desa' => self::KEPALA_DESA_POSITIONS,
    ];

    public const REGION_CACHE_TTL    = 86400;   // 24 jam
    public const REGION_FALLBACK_TTL = 2592000; // 30 hari untuk fallback saat API eksternal gangguan
    private const REGION_API_BASE    = 'https://apps.sinjaikab.go.id/api/pegawai';

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
        $hasBioAndImage = !in_array($type, self::OFFICIAL_DIRECTORY_TYPES, true);

        $rules = [
            'name'      => ['label' => 'Nama Lengkap', 'rules' => 'required|max_length[255]'],
            'position'  => [
                'label' => 'Jabatan',
                'rules' => ($type !== null && isset(self::STATIC_POSITIONS[$type]))
                    ? 'required|in_list[' . implode(',', self::STATIC_POSITIONS[$type]) . ']'
                    : 'required',
            ],
            'type'      => ['label' => 'Tipe', 'rules' => 'required'],
            'bio'       => ['label' => 'Biografi', 'rules' => $hasBioAndImage ? 'required' : 'permit_empty'],
            'order'     => in_array($type, self::MANUAL_ORDER_TYPES, true) ? 'required|integer' : 'permit_empty|integer',
            'kecamatan' => ['label' => 'Kecamatan', 'rules' => in_array($type, self::REGION_TYPES, true) ? 'required|max_length[100]' : 'permit_empty|max_length[100]'],
            'institution' => ['label' => ($type === 'kepala-desa' ? 'Desa' : ($type === 'lurah' ? 'Kelurahan' : ($type === 'camat' ? 'Kecamatan' : 'Instansi'))), 'rules' => 'required'],
            'kelurahan' => ['label' => 'Kelurahan', 'rules' => 'permit_empty|max_length[100]'],
            'desa'      => ['label' => 'Desa', 'rules' => 'permit_empty|max_length[100]'],
        ];

        $isImageOptional = $isUpdate || !$hasBioAndImage;

        if ($isImageOptional) {
            $rules['image'] = ['label' => 'Foto', 'rules' => 'permit_empty|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'];
        } else {
            $rules['image'] = ['label' => 'Foto', 'rules' => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'];
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

        // Sanitize bio to prevent Stored XSS
        if (isset($data['bio'])) {
            $data['bio'] = $this->sanitizeHtml($data['bio']);
        }

        // Generate Slug only for specific types
        if (in_array($data['type'], self::SLUG_TYPES, true)) {
            $name = trim($data['name'] ?? '');
            $position = trim($data['position'] ?? '');
            
            $slugBase = ($name !== '' && $name !== '-') ? $name : ($position !== '' ? $position : 'profile');
            $slug = $this->createAsciiSlug($slugBase);
            
            // If url_title still returns empty (e.g. all special chars), fallback to a random string or position slug
            if (empty($slug)) {
                $slug = $this->createAsciiSlug($position ?: 'profile') ?: 'profile-' . time();
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
        } else {
            $data['slug'] = null;
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

    public function getKecamatan(): ?array
    {
        return $this->getCachedRegionData(
            'profiles_region_kecamatan',
            self::REGION_API_BASE . '/get_kecamatan'
        );
    }

    public function getWilayah(string $tipe): ?array
    {
        $tipe = strtolower(trim($tipe));
        if (!in_array($tipe, ['kelurahan', 'desa'], true)) {
            return null;
        }

        return $this->getCachedRegionData(
            'profiles_region_wilayah_' . $tipe,
            self::REGION_API_BASE . '/get_wilayah',
            ['tipe' => $tipe]
        );
    }

    protected function getCachedRegionData(string $cacheKey, string $url, array $query = []): ?array
    {
        $cache = \Config\Services::cache();
        $cached = $cache->get($cacheKey);
        if (is_array($cached) && !empty($cached)) {
            return $cached;
        }

        $fresh = $this->requestRegionApi($url, $query);
        if (is_array($fresh) && !empty($fresh)) {
            $cache->save($cacheKey, $fresh, self::REGION_CACHE_TTL);
            $cache->save($cacheKey . '_fallback', $fresh, self::REGION_FALLBACK_TTL);

            return $fresh;
        }

        $fallback = $cache->get($cacheKey . '_fallback');
        if (is_array($fallback) && !empty($fallback)) {
            return $fallback;
        }

        return null;
    }

    protected function requestRegionApi(string $url, array $query = []): ?array
    {
        try {
            $options = [
                'timeout'         => 10,
                'connect_timeout' => 5,
            ];
            if (!empty($query)) {
                $options['query'] = $query;
            }

            $response = \Config\Services::curlrequest()->get($url, $options);
            $decoded = json_decode((string) $response->getBody(), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }

            return null;
        } catch (\Throwable $e) {
            log_message('error', '[ProfileService::requestRegionApi] ' . $e->getMessage());
            return null;
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
