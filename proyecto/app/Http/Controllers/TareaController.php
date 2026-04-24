<?php

/**
 * Controlador TareaController
 * 
 * Gestiona las operaciones CRUD para tareas (incidencias).
 * Según el PDF:
 * - Admin: Gestiona tareas, asigna operarios.
 * - Operario: Ve solo sus tareas, las completa.
 * - Cliente: Registra incidencias públicas (1.10).
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Controllers
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\Cliente;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     * Filtra según si es Admin (ve todo) o Operario (ve solo las suyas).
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->tipo === 'admin') {
            $tareas = Tarea::with(['cliente', 'empleado'])->latest('fecha_creacion')->paginate(15);
        } else {
            $tareas = Tarea::where('empleado_id', $user->id)
                           ->with(['cliente', 'empleado'])
                           ->latest('fecha_creacion')
                           ->paginate(15);
        }

        return view('tareas.index', compact('tareas'));
    }

    /**
     * Show the form for creating a new resource.
     * Solo accesible para Admins.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();
        return view('tareas.create', compact('clientes', 'operarios'));
    }

    /**
     * Store a newly created resource in storage.
     * ADMIN: Requiere operario asignado (obligatorio según PDF).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id', // OBLIGATORIO para Admin
            'persona_contacto' => 'required|string|max:150',
            'telefono_contacto' => 'required|string|max:20',
            'descripcion' => 'required|string',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'required|string',
            'poblacion' => 'required|string|max:100',
            'codigo_postal' => 'required|string|size:5',
            'provincia_ine' => 'required|string|size:2',
            'anotaciones_anteriores' => 'nullable|string',
        ]);

        // Validación: CP y Provincia deben coincidir
        if (substr($validated['codigo_postal'], 0, 2) !== $validated['provincia_ine']) {
            return back()->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia seleccionada.'])->withInput();
        }

        $validated['fecha_creacion'] = now();
        $validated['estado'] = 'P'; // Pendiente por defecto

        Tarea::create($validated);

        return redirect()->route('tareas.index')->with('success', 'Incidencia creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tarea $tarea)
    {
        // Verificar permisos
        if (Auth::user()->tipo !== 'admin' && Auth::id() !== $tarea->empleado_id) {
            abort(403);
        }

        return view('tareas.show', compact('tarea'));
    }

    /**
     * Show the form for editing the specified resource.
     * Solo Admin.
     */
    public function edit(Tarea $tarea)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();
        return view('tareas.edit', compact('tarea', 'clientes', 'operarios'));
    }

    /**
     * Update the specified resource in storage.
     * Solo Admin.
     */
    public function update(Request $request, Tarea $tarea)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'persona_contacto' => 'required|string|max:150',
            'telefono_contacto' => 'required|string|max:20',
            'descripcion' => 'required|string',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'required|string',
            'poblacion' => 'required|string|max:100',
            'codigo_postal' => 'required|string|size:5',
            'provincia_ine' => 'required|string|size:2',
            'estado' => 'required|in:P,R,C,A',
            'anotaciones_anteriores' => 'nullable|string',
        ]);

        if (substr($validated['codigo_postal'], 0, 2) !== $validated['provincia_ine']) {
            return back()->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia.'])->withInput();
        }

        $tarea->update($validated);

        return redirect()->route('tareas.index')->with('success', 'Incidencia actualizada.');
    }

    /**
     * Remove the specified resource from storage.
     * Solo Admin.
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')->with('success', 'Incidencia eliminada.');
    }

    /**
     * Muestra el formulario para completar la tarea (Operario).
     */
    public function completar(Tarea $tarea)
    {
        if (Auth::user()->tipo !== 'admin' && Auth::id() !== $tarea->empleado_id) {
            abort(403);
        }
        return view('tareas.completar', compact('tarea'));
    }

    /**
     * Procesa el completado de la tarea.
     */
    public function procesarCompletado(Request $request, Tarea $tarea)
    {
        $validated = $request->validate([
            'anotaciones_posteriores' => 'required|string',
            'fichero_resumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        if ($request->hasFile('fichero_resumen')) {
            $path = $request->file('fichero_resumen')->store('tareas_resumenes', 'private');
            $validated['fichero_resumen'] = $path;
        }

        $validated['estado'] = 'R'; // Realizada
        $validated['fecha_realizacion'] = now();

        $tarea->update($validated);

        return redirect()->route('tareas.index')->with('success', 'Tarea completada.');
    }

    // ========================================================================
    // PUNTO 1.10 - REGISTRO PÚBLICO DE INCIDENCIAS POR CLIENTES
    // ========================================================================

    /**
     * Muestra el formulario público para clientes (Punto 1.10 del PDF).
     * Sin necesidad de login, pero validando CIF y Teléfono.
     */
    public function crearPorCliente()
    {
        return view('tareas.publica.create');
    }

    /**
     * Guarda una incidencia pública validando CIF y Teléfono (Punto 1.10).
     * El cliente debe existir en BD con ese CIF y teléfono.
     */
    public function guardarPorCliente(Request $request)
    {
        // 1. Validación de campos
        $validated = $request->validate([
            'cif' => 'required|string|max:20',
            'telefono' => 'required|string|max:20',
            'persona_contacto' => 'required|string|max:150',
            'telefono_contacto' => 'required|string|max:20',
            'descripcion' => 'required|string',
            'correo_contacto' => 'required|email|max:100',
            'direccion' => 'required|string',
            'poblacion' => 'required|string|max:100',
            'codigo_postal' => 'required|string|size:5',
            'provincia_ine' => 'required|string|size:2',
        ]);

        // 2. Verificar si el cliente existe con ese CIF y Teléfono
        $cliente = Cliente::where('cif', $validated['cif'])
                          ->where('telefono', $validated['telefono'])
                          ->first();

        if (!$cliente) {
            return back()
                ->withErrors(['cif' => 'El CIF y el Teléfono no coinciden con ningún cliente registrado.'])
                ->withInput();
        }

        // 3. Validar CP vs Provincia
        if (substr($validated['codigo_postal'], 0, 2) !== $validated['provincia_ine']) {
            return back()
                ->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia.'])
                ->withInput();
        }

        // 4. Crear la tarea SIN operario asignado (null)
        Tarea::create([
            'cliente_id' => $cliente->id,
            'empleado_id' => null, // Pendiente de asignación por Admin
            'persona_contacto' => $validated['persona_contacto'],
            'telefono_contacto' => $validated['telefono_contacto'],
            'descripcion' => $validated['descripcion'],
            'correo_contacto' => $validated['correo_contacto'],
            'direccion' => $validated['direccion'],
            'poblacion' => $validated['poblacion'],
            'codigo_postal' => $validated['codigo_postal'],
            'provincia_ine' => $validated['provincia_ine'],
            'estado' => 'P', // Pendiente
            'fecha_creacion' => now(),
            'anotaciones_anteriores' => null,
        ]);

        return redirect()->route('home')
            ->with('success', 'Incidencia registrada correctamente. Un operario revisará su solicitud.');
    }
}