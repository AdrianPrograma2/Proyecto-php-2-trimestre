<?php



use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Pruebas de Rutas - Problema 2 (PDF)
 * 
 * @author Tu Nombre
 * @version 1.0
 */

uses(RefreshDatabase::class);

test('rutas publicas existen y devuelven 200', function () {
    $response = $this->get('/');
    $response->assertStatus(200);

    $response = $this->get('/login');
    $response->assertStatus(200);

    $response = $this->get('/incidencia/nueva');
    $response->assertStatus(200);
});

test('rutas protegidas redirigen al login si no hay sesion', function () {
    $protectedRoutes = ['/tareas', '/admin/empleados', '/admin/clientes', '/admin/cuotas'];
    
    foreach ($protectedRoutes as $route) {
        $response = $this->get($route);
        $response->assertRedirect('/login');
    }
});

test('rutas de admin bloquean a usuarios no administradores', function () {
    // Creamos un operario falso
    $operario = \App\Models\Empleado::factory()->create(['tipo' => 'operario']);
    $this->actingAs($operario);

    $adminRoutes = ['/admin/empleados', '/admin/clientes', '/admin/cuotas'];
    
    foreach ($adminRoutes as $route) {
        $response = $this->get($route);
       $status = $response->status();
expect(in_array($status, [403, 302]))->toBeTrue();
    }
});