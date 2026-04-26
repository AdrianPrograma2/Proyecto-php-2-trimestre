<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Importar Controladores
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\TareaController;

/*
| 1. RUTAS PÚBLICAS

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

| 2. RUTAS PROTEGIDAS (Requieren Login)
*/
Route::middleware(['auth'])->group(function () {
    

// PROBLEMA 3.1 - RUTAS AJAX (DataTables + jQuery)

// API JSON para DataTables
Route::get('/tareas/data', [TareaController::class, 'getDatosTareas'])->name('tareas.data');

// CRUD AJAX
Route::post('/tareas/ajax', [TareaController::class, 'storeAjax'])->name('tareas.storeAjax');
Route::put('/tareas/ajax/{id}', [TareaController::class, 'updateAjax'])->name('tareas.updateAjax');
Route::delete('/tareas/ajax/{id}', [TareaController::class, 'destroyAjax'])->name('tareas.destroyAjax');

// Vista del Problema 3.1
Route::get('/tareas/vista-dynamic', function () {
    return view('tareas.vista-dynamic');
})->name('tareas.vista-dynamic');
    // PROBLEMA 3.2 - VISTAS CON VUE.JS + CDN
    Route::get('/tareas/vista-vue', function () {
        return view('tareas.vista-vue');
    })->name('tareas.vista-vue');


    // PROBLEMA 3.3 - VISTAS CON VUE.JS + VITE + COMPONENTES .vue
    Route::get('/tareas/vista-vite', function () {
        return view('tareas.vista-vite');
    })->name('tareas.vista-vite');

    // API endpoints para Vue 3 (3.3)
    Route::get('/api/tareas', [TareaController::class, 'getTareasApi']);
    Route::get('/api/clientes', [TareaController::class, 'getClientesApi']);
    Route::get('/api/operarios', [TareaController::class, 'getOperariosApi']);

    // Panel de Tareas (CRUD Normal - Problema 1)
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

    // Panel de ADMINISTRADOR (Solo admins)

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
        Route::post('admin/cuotas/remesa-mensual', [CuotaController::class, 'generarRemesa'])
            ->name('admin.cuotas.remessa');

        // 1.8 - Enviar factura por email manualmente
        Route::post('admin/cuotas/{cuota}/enviar-email', [CuotaController::class, 'enviarFacturaEmail'])
            ->name('admin.cuotas.enviar-email');

        // 1.9 - Descargar factura PDF
        Route::get('admin/cuotas/{cuota}/factura', [CuotaController::class, 'descargarFactura'])
            ->name('admin.cuotas.factura');
    });
});