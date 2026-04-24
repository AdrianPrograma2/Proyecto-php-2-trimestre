<?php

/**
 * Archivo de Rutas (web.php)
 * 
 * Define todas las rutas de la aplicación Nosecaen S.L.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Importar Controladores
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\TareaController;

/*
|--------------------------------------------------------------------------
| 1. RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autenticación
Route::get('/login', [EmpleadoController::class, 'showLoginForm'])->name('login');
Route::post('/login', [EmpleadoController::class, 'authenticate'])->name('login.store');
Route::post('/logout', [EmpleadoController::class, 'logout'])->name('logout');

// 1.10 - Clientes pueden registrar incidencias (Sin login)
Route::get('/incidencia/nueva', [TareaController::class, 'crearPorCliente'])->name('incidencia.publica');
Route::post('/incidencia/nueva', [TareaController::class, 'guardarPorCliente'])->name('incidencia.publica.store');


/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Requieren Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ------------------------------------------------------------------------
    // Panel de Tareas
    // ------------------------------------------------------------------------
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::get('/tareas/crear', [TareaController::class, 'create'])->name('tareas.create');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');
    
    Route::get('/tareas/{tarea}/editar', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{tarea}', [TareaController::class, 'update'])->name('tareas.update');
    
    Route::delete('/tareas/{tarea}', [TareaController::class, 'destroy'])->name('tareas.destroy');

    // Operario marca tarea como completada
    Route::get('/tareas/{tarea}/completar', [TareaController::class, 'completar'])->name('tareas.completar');
    Route::post('/tareas/{tarea}/completar', [TareaController::class, 'procesarCompletado'])->name('tareas.procesarCompletado');


    // ------------------------------------------------------------------------
    // Panel de ADMINISTRADOR (Solo admins)
    // ------------------------------------------------------------------------
    Route::middleware(['admin'])->group(function () {
        
        // Gestionar Empleados
        Route::resource('admin/empleados', EmpleadoController::class)->names([
            'index' => 'admin.empleados.index',
            'create' => 'admin.empleados.create',
            'store' => 'admin.empleados.store',
            'show' => 'admin.empleados.show',
            'edit' => 'admin.empleados.edit',
            'update' => 'admin.empleados.update',
            'destroy' => 'admin.empleados.destroy',
        ]);

        // Gestionar Clientes
        Route::resource('admin/clientes', ClienteController::class)->names([
            'index' => 'admin.clientes.index',
            'create' => 'admin.clientes.create',
            'store' => 'admin.clientes.store',
            'show' => 'admin.clientes.show',
            'edit' => 'admin.clientes.edit',
            'update' => 'admin.clientes.update',
            'destroy' => 'admin.clientes.destroy',
        ]);

        // Gestionar Cuotas
        Route::resource('admin/cuotas', CuotaController::class)->names([
            'index' => 'admin.cuotas.index',
            'create' => 'admin.cuotas.create',
            'store' => 'admin.cuotas.store',
            'show' => 'admin.cuotas.show',
            'edit' => 'admin.cuotas.edit',
            'update' => 'admin.cuotas.update',
            'destroy' => 'admin.cuotas.destroy',
        ]);

        // 1.6 - Generar remesa mensual
        Route::post('admin/cuotas/remesa-mensual', [CuotaController::class, 'generarRemesa'])->name('admin.cuotas.remessa');
        // 1.8 - Enviar factura por email manualmente
Route::post('admin/cuotas/{cuota}/enviar-email', [CuotaController::class, 'enviarFacturaEmail'])
    ->name('admin.cuotas.enviar-email');
        
        // 1.9 - Descargar factura PDF (RUTA QUE FALTABA)
        Route::get('admin/cuotas/{cuota}/factura', [CuotaController::class, 'descargarFactura'])->name('admin.cuotas.factura');
    });
});