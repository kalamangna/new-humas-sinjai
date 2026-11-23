<?php

use App\Services\Content\ProfileService;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class ProfileServiceTest extends CIUnitTestCase
{
    private ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();
        \Config\Services::resetSingle('validation');
        $this->profileService = new ProfileService();
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

    public function testOtherTypesKeepFreeTextPositionValidation(): void
    {
        $rules = $this->profileService->getValidationRules('camat');
        $data = [
            'type'        => 'camat',
            'name'        => 'Andi Ahmad',
            'position'    => 'Plt. Camat',
            'institution' => 'Sinjai Utara',
            'order'       => '1',
        ];

        $this->assertTrue(
            $this->profileService->validate($data, $rules),
            json_encode($this->profileService->getErrors())
        );
    }
}
