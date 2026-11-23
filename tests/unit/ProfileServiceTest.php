<?php

use App\Services\Content\ProfileService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProfileServiceTest extends CIUnitTestCase
{
    private ProfileService $profileService;

    private const REGION_CACHE_KEYS = [
        'profiles_region_kecamatan',
        'profiles_region_kecamatan_fallback',
        'profiles_region_wilayah_desa',
        'profiles_region_wilayah_desa_fallback',
        'profiles_region_wilayah_kelurahan',
        'profiles_region_wilayah_kelurahan_fallback',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        \Config\Services::resetSingle('validation');
        $this->clearRegionCache();
        $this->profileService = new ProfileService();
    }

    protected function tearDown(): void
    {
        $this->clearRegionCache();
        parent::tearDown();
    }

    private function clearRegionCache(): void
    {
        $cache = \Config\Services::cache();
        foreach (self::REGION_CACHE_KEYS as $key) {
            $cache->delete($key);
        }
    }

    public function testKepalaDesaValidationRulesRequireAllowedPosition(): void
    {
        $rules = $this->profileService->getValidationRules('kepala-desa');

        $this->assertArrayHasKey('position', $rules);
        $this->assertSame(
            'required|in_list[Kepala Desa,Plt. Kepala Desa,Pj. Kepala Desa]',
            $rules['position']['rules']
        );
    }

    public function testKepalaDesaAcceptsDefinitivePltAndPjPositions(): void
    {
        $rules = $this->profileService->getValidationRules('kepala-desa');

        foreach (ProfileService::KEPALA_DESA_POSITIONS as $position) {
            $data = [
                'type'        => 'kepala-desa',
                'name'        => 'Budi Santoso',
                'position'    => $position,
                'kecamatan'   => 'Tellulimpoe',
                'institution' => 'Era Baru',
            ];

            $this->assertTrue(
                $this->profileService->validate($data, $rules),
                "Expected position '{$position}' to pass validation."
            );
        }
    }

    public function testKepalaDesaRejectsInvalidPosition(): void
    {
        $rules = $this->profileService->getValidationRules('kepala-desa');
        $data = [
            'type'        => 'kepala-desa',
            'name'        => 'Budi Santoso',
            'position'    => 'Kades',
            'kecamatan'   => 'Tellulimpoe',
            'institution' => 'Era Baru',
        ];

        $this->assertFalse($this->profileService->validate($data, $rules));
        $errors = $this->profileService->getErrors();
        $this->assertArrayHasKey('position', $errors);
    }

    public function testCamatAcceptsAllowedPositionsAndRejectsInvalid(): void
    {
        $rules = $this->profileService->getValidationRules('camat');

        foreach (ProfileService::STATIC_POSITIONS['camat'] as $position) {
            $data = [
                'type'        => 'camat',
                'name'        => 'Andi Ahmad',
                'position'    => $position,
                'institution' => 'Sinjai Utara',
                'order'       => '1',
            ];

            $this->assertTrue(
                $this->profileService->validate($data, $rules),
                json_encode($this->profileService->getErrors())
            );
        }

        $invalid = [
            'type'        => 'camat',
            'name'        => 'Andi Ahmad',
            'position'    => 'Camat Utama',
            'institution' => 'Sinjai Utara',
            'order'       => '1',
        ];
        $this->assertFalse($this->profileService->validate($invalid, $rules));
        $this->assertArrayHasKey('position', $this->profileService->getErrors());
    }

    public function testLurahAcceptsAllowedPositionsAndRejectsInvalid(): void
    {
        $rules = $this->profileService->getValidationRules('lurah');

        foreach (ProfileService::STATIC_POSITIONS['lurah'] as $position) {
            $data = [
                'type'        => 'lurah',
                'name'        => 'Sunarto',
                'position'    => $position,
                'kecamatan'   => 'Sinjai Barat',
                'institution' => 'Balakia',
            ];

            $this->assertTrue(
                $this->profileService->validate($data, $rules),
                json_encode($this->profileService->getErrors())
            );
        }

        $invalid = [
            'type'        => 'lurah',
            'name'        => 'Sunarto',
            'position'    => 'Kepala Kelurahan',
            'kecamatan'   => 'Sinjai Barat',
            'institution' => 'Balakia',
        ];
        $this->assertFalse($this->profileService->validate($invalid, $rules));
        $this->assertArrayHasKey('position', $this->profileService->getErrors());
    }

    public function testOtherTypesKeepFreeTextPositionValidation(): void
    {
        $rules = $this->profileService->getValidationRules('kepala-opd');
        $data = [
            'type'        => 'kepala-opd',
            'name'        => 'Drs. Hasanuddin',
            'position'    => 'Plt. Kepala Dinas',
            'institution' => 'Dinas Komunikasi, Informatika, dan Persandian',
            'order'       => '1',
        ];

        $this->assertTrue(
            $this->profileService->validate($data, $rules),
            json_encode($this->profileService->getErrors())
        );
    }

    public function testGetKecamatanCachesSuccessfulApiResponse(): void
    {
        $service = new class () extends ProfileService {
            public int $calls = 0;

            protected function requestRegionApi(string $url, array $query = []): ?array
            {
                $this->calls++;
                return [['kecamatan_nama' => 'Tellu Limpoe']];
            }
        };

        $first = $service->getKecamatan();
        $second = $service->getKecamatan();

        $this->assertSame(1, $service->calls);
        $this->assertSame([['kecamatan_nama' => 'Tellu Limpoe']], $first);
        $this->assertSame($first, $second);
    }

    public function testGetKecamatanDoesNotCacheFailedOrEmptyResponse(): void
    {
        $service = new class () extends ProfileService {
            public int $calls = 0;

            protected function requestRegionApi(string $url, array $query = []): ?array
            {
                $this->calls++;
                if ($this->calls === 1) {
                    return null;
                }
                if ($this->calls === 2) {
                    return [];
                }

                return [['kecamatan_nama' => 'Sinjai Utara']];
            }
        };

        $this->assertNull($service->getKecamatan());
        $this->assertNull($service->getKecamatan());
        $this->assertSame([['kecamatan_nama' => 'Sinjai Utara']], $service->getKecamatan());
        $this->assertSame(3, $service->calls);
    }

    public function testGetKecamatanUsesFallbackCacheWhenApiFailsAfterTtlExpiry(): void
    {
        $service = new class () extends ProfileService {
            public bool $shouldFail = false;

            protected function requestRegionApi(string $url, array $query = []): ?array
            {
                if ($this->shouldFail) {
                    return null;
                }

                return [['kecamatan_nama' => 'Bulupoddo']];
            }
        };

        $initial = $service->getKecamatan();
        $this->assertSame([['kecamatan_nama' => 'Bulupoddo']], $initial);

        \Config\Services::cache()->delete('profiles_region_kecamatan');
        $service->shouldFail = true;

        $fallback = $service->getKecamatan();
        $this->assertSame($initial, $fallback);
    }

    public function testGetWilayahSeparatesCachePerTypeAndRejectsInvalidType(): void
    {
        $service = new class () extends ProfileService {
            public int $calls = 0;

            protected function requestRegionApi(string $url, array $query = []): ?array
            {
                $this->calls++;
                if (($query['tipe'] ?? '') === 'desa') {
                    return [['desa_nama' => 'Era Baru']];
                }

                return [['kelurahan_nama' => 'Balangnipa']];
            }
        };

        $this->assertNull($service->getWilayah('invalid'));
        $this->assertSame(0, $service->calls);

        $desa = $service->getWilayah('desa');
        $kelurahan = $service->getWilayah('kelurahan');

        $this->assertSame(2, $service->calls);
        $this->assertSame([['desa_nama' => 'Era Baru']], $desa);
        $this->assertSame([['kelurahan_nama' => 'Balangnipa']], $kelurahan);

        $this->assertSame($desa, $service->getWilayah('DESA'));
        $this->assertSame(2, $service->calls);
    }

    public function testTypeConfigurationConstantsAreConsistent(): void
    {
        $allTypes = array_keys(ProfileService::TYPE_LABELS);

        $this->assertCount(10, $allTypes);
        $this->assertSame('Sekretaris Daerah', ProfileService::TYPE_LABELS['sekda']);

        foreach (ProfileService::OFFICIAL_DIRECTORY_TYPES as $type) {
            $this->assertContains($type, $allTypes);
        }
        foreach (ProfileService::MANUAL_ORDER_TYPES as $type) {
            $this->assertContains($type, $allTypes);
        }
        foreach (ProfileService::SLUG_TYPES as $type) {
            $this->assertContains($type, $allTypes);
        }
        foreach (ProfileService::REGION_TYPES as $type) {
            $this->assertContains($type, $allTypes);
        }
    }

    public function testBioImageAndOrderRulesFollowTypeConfiguration(): void
    {
        $bupatiRules = $this->profileService->getValidationRules('bupati');
        $this->assertSame('required', $bupatiRules['bio']['rules']);
        $this->assertStringStartsWith('uploaded[image]|', $bupatiRules['image']['rules']);
        $this->assertSame('permit_empty|integer', $bupatiRules['order']);

        $forkopimdaRules = $this->profileService->getValidationRules('forkopimda');
        $this->assertSame('permit_empty', $forkopimdaRules['bio']['rules']);
        $this->assertStringStartsWith('permit_empty|', $forkopimdaRules['image']['rules']);
        $this->assertSame('required|integer', $forkopimdaRules['order']);
    }
}
