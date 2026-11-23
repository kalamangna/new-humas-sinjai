<?php

use App\Controllers\Auth\Login;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Session;

/**
 * @internal
 */
final class AuthRedirectTest extends CIUnitTestCase
{
    public function testSessionRegenerateDestroyIsDisabledToAvoidAjaxRace(): void
    {
        $sessionConfig = new Session();
        $this->assertFalse($sessionConfig->regenerateDestroy);
    }

    public function testSanitizeRedirectPathAcceptsValidAdminPaths(): void
    {
        $this->assertSame('admin', Login::sanitizeRedirectPath('admin'));
        $this->assertSame('admin', Login::sanitizeRedirectPath('/admin'));
        $this->assertSame('admin/posts', Login::sanitizeRedirectPath('/admin/posts'));
        $this->assertSame('admin/profiles/12/edit', Login::sanitizeRedirectPath('admin/profiles/12/edit'));
        $this->assertSame(
            'admin/profiles?type=kepala-desa&page=2',
            Login::sanitizeRedirectPath('/admin/profiles?type=kepala-desa&page=2')
        );
    }

    public function testSanitizeRedirectPathRejectsUnsafeOrNonAdminTargets(): void
    {
        $this->assertNull(Login::sanitizeRedirectPath(null));
        $this->assertNull(Login::sanitizeRedirectPath(''));
        $this->assertNull(Login::sanitizeRedirectPath('https://evil.com/admin'));
        $this->assertNull(Login::sanitizeRedirectPath('//evil.com/admin'));
        $this->assertNull(Login::sanitizeRedirectPath('/posts'));
        $this->assertNull(Login::sanitizeRedirectPath('masuk'));
        $this->assertNull(Login::sanitizeRedirectPath('logout'));
        $this->assertNull(Login::sanitizeRedirectPath('api/analytics/overview'));
        $this->assertNull(Login::sanitizeRedirectPath('admin/session/ping'));
        $this->assertNull(Login::sanitizeRedirectPath("admin/posts\r\nSet-Cookie: x"));
    }
}
