<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\FirestoreUser;

class QomerSecurityAuthTest extends TestCase
{
    private function makeUser(string $role, string $uid = 'test-uid'): FirestoreUser
    {
        return new FirestoreUser(
            uid: $uid,
            name: 'Test User ' . $role,
            email: strtolower($role) . '@qomer.test',
            role: $role,
            status: 'ACTIVE'
        );
    }

    /**
     * [SEC-AUTH-001] Acceso sin autenticación a todas las rutas protegidas.
     * Resultado esperado: HTTP 302 Redirect hacia admin.login
     */
    public function test_sec_auth_001_unauthenticated_access_is_blocked_for_all_admin_routes(): void
    {
        $protectedRoutes = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/transactions',
            '/admin/redemptions',
            '/admin/settings',
            '/admin/reports',
            '/admin/audit',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(302);
            $response->assertRedirect(route('admin.login'));
        }
    }

    /**
     * [SEC-AUTH-002] Control de Acceso Vertical: Usuario con rol normal 'USER' intenta acceder a rutas administrativas.
     * Resultado esperado: HTTP 403 Forbidden (bloqueado por EnsureRole).
     */
    public function test_sec_auth_002_user_with_role_user_is_forbidden_on_admin_routes(): void
    {
        $user = $this->makeUser('USER', 'uid-normal-user');

        $protectedRoutes = [
            '/admin/dashboard',
            '/admin/users',
            '/admin/transactions',
            '/admin/settings',
            '/admin/reports',
            '/admin/audit',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->actingAs($user)->get($route);
            $response->assertStatus(403);
        }
    }

    /**
     * [SEC-AUTH-003] Control de Acceso Vertical: Usuario con rol no reconocido 'INVITADO'.
     * Resultado esperado: HTTP 403 Forbidden.
     */
    public function test_sec_auth_003_unauthorized_role_is_forbidden(): void
    {
        $user = $this->makeUser('INVITADO', 'uid-guest');
        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * [SEC-AUTH-004] Acceso Permitido: Usuario con rol 'ADMIN' accede al dashboard y rutas administrativas.
     * Resultado esperado: HTTP 200 OK.
     */
    public function test_sec_auth_004_admin_can_access_dashboard_and_settings(): void
    {
        $admin = $this->makeUser('ADMIN', 'uid-admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
    }

    /**
     * [SEC-AUTH-005] Acceso Permitido: Usuario con rol 'SUPERVISOR' accede a rutas administrativas autorizadas.
     * Resultado esperado: HTTP 200 OK.
     */
    public function test_sec_auth_005_supervisor_can_access_dashboard(): void
    {
        $supervisor = $this->makeUser('SUPERVISOR', 'uid-supervisor');

        $response = $this->actingAs($supervisor)->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    /**
     * [SEC-AUTH-006] Cierre de sesión (Logout): Cierra sesión y posterior intento de acceso es redirigido.
     * Resultado esperado: Sesión invalidada y redirect a login.
     */
    public function test_sec_auth_006_logout_invalidates_session(): void
    {
        $admin = $this->makeUser('ADMIN', 'uid-admin');

        $response = $this->actingAs($admin)->post(route('admin.logout'));
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));

        $this->assertGuest();

        // Intento subsiguiente de acceder a dashboard sin sesión
        $responseAfter = $this->get('/admin/dashboard');
        $responseAfter->assertStatus(302);
        $responseAfter->assertRedirect(route('admin.login'));
    }

    /**
     * [SEC-AUTH-007] Manipulación de Verbo HTTP: Intentar enviar GET a logout
     * Resultado esperado: HTTP 405 Method Not Allowed
     */
    public function test_sec_auth_007_logout_requires_post(): void
    {
        $admin = $this->makeUser('ADMIN', 'uid-admin');
        $response = $this->actingAs($admin)->get('/admin/logout');
        $response->assertStatus(405);
    }
}
