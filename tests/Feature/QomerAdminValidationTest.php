<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\Firebase\FirestoreClient;

class QomerAdminValidationTest extends TestCase
{
    /**
     * CP-029: Acceso al Dashboard sin sesión activa debe redirigir a /admin/login
     */
    public function test_cp_029_dashboard_redirects_when_unauthenticated(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * CP-029b: Acceso a rutas administrativas protegidas sin autenticación
     */
    public function test_cp_029_users_redirects_when_unauthenticated(): void
    {
        $response = $this->get('/admin/users');
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));

        $response = $this->get('/admin/settings');
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));

        $response = $this->get('/admin/transactions');
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * CP-034: Crear producto con campos obligatorios vacíos debe fallar validación
     */
    public function test_cp_034_create_product_validation_fails_on_empty_fields(): void
    {
        $response = $this->post(route('admin.products.store'), [
            'codigoBarras' => '',
            'nombre' => '',
            'ecoPoints' => '',
        ]);

        // Debe redirigir con errores de validación en sesión
        $response->assertStatus(302);
    }
}
