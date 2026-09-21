<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Support\SessionArea;
use Tests\TestCase;

class SessionAreaTest extends TestCase
{
    public function test_maps_role_to_area_and_back(): void
    {
        $this->assertSame('superadmin', SessionArea::areaForRole(Role::SUPER_ADMIN));
        $this->assertSame('owner', SessionArea::areaForRole(Role::OWNER));
        $this->assertSame('admin', SessionArea::areaForRole(Role::ADMIN));
        $this->assertSame('gudang', SessionArea::areaForRole(Role::GUDANG));
        $this->assertSame('produksi', SessionArea::areaForRole(Role::PRODUKSI));
        $this->assertNull(SessionArea::areaForRole(Role::CUSTOMER));
        $this->assertNull(SessionArea::areaForRole(null));

        $this->assertSame(Role::OWNER, SessionArea::roleForArea('owner'));
        $this->assertNull(SessionArea::roleForArea('customer'));
    }

    public function test_maps_path_to_area(): void
    {
        $this->assertSame('owner', SessionArea::areaForPath('owner/profil'));
        $this->assertSame('superadmin', SessionArea::areaForPath('/superadmin'));
        $this->assertSame('gudang', SessionArea::areaForPath('gudang/barang-masuk'));
        $this->assertNull(SessionArea::areaForPath('notifikasi/get'));
        $this->assertNull(SessionArea::areaForPath('customer/shop'));
        $this->assertNull(SessionArea::areaForPath('/'));
    }

    public function test_referer_fallback_resolves_area(): void
    {
        $this->assertSame('owner', SessionArea::areaForReferer('http://localhost/owner/dashboard'));
        $this->assertSame('admin', SessionArea::areaForReferer('http://localhost/admin/komplain?x=1'));
        $this->assertNull(SessionArea::areaForReferer('http://localhost/customer/shop'));
        $this->assertNull(SessionArea::areaForReferer(null));
    }

    public function test_cookie_name_is_prefixed_per_area(): void
    {
        $this->assertSame('raliva_owner_session', SessionArea::cookieNameForArea('owner'));
        $this->assertSame('raliva_superadmin_session', SessionArea::cookieNameForArea('superadmin'));
    }

    public function test_disabled_by_default(): void
    {
        config(['session.multi_role' => false]);
        $this->assertFalse(SessionArea::isEnabled());

        config(['session.multi_role' => true]);
        $this->assertTrue(SessionArea::isEnabled());
    }
}
