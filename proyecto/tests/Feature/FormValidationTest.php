<?php



use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Empleado;
use App\Models\Cliente;

/**
 * Pruebas de Formularios - Problema 2 (PDF)
 * 
 * @author Tu Nombre
 * @version 1.0
 */

uses(RefreshDatabase::class);

test('login funciona con credenciales correctas', function () {
    $admin = Empleado::factory()->create([
        'correo' => 'admin@nosecaen.es',
        'password' => bcrypt('123456'),
        'tipo' => 'admin'
    ]);

    $response = $this->post('/login', [
        'correo' => 'admin@nosecaen.es',
        'password' => '123456'
    ]);

    $response->assertRedirect();
    $this->assertAuthenticatedAs($admin);
});

test('login falla con contraseña incorrecta', function () {
    Empleado::factory()->create([
        'correo' => 'admin@nosecaen.es',
        'password' => bcrypt('correcto')
    ]);

    $response = $this->post('/login', [
        'correo' => 'admin@nosecaen.es',
        'password' => 'malo'
    ]);

    $response->assertSessionHasErrors('correo');
    $this->assertGuest();
});

test('formulario de crear tarea valida campos obligatorios', function () {
    $admin = Empleado::factory()->create(['tipo' => 'admin']);
    $this->actingAs($admin);

    $response = $this->post('/tareas', []);

    $response->assertSessionHasErrors([
        'cliente_id',
        'empleado_id',
        'persona_contacto',
        'descripcion',
        'codigo_postal',
    ]);
});