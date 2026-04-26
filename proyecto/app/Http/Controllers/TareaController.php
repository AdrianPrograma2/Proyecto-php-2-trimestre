<?php


namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Empleado;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TareaController extends Controller
{
    /**
     * listado
     * Filtra según si es Admin (ve todo) o Operario (ve solo las suyas).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->tipo === 'admin') {
            $tareas = Tarea::with(['cliente', 'empleado'])
                ->latest('fecha_creacion')
                ->paginate(15);
        } else {
            $tareas = Tarea::where('empleado_id', $user->id)
                ->with(['cliente', 'empleado'])
                ->latest('fecha_creacion')
                ->paginate(15);
        }

        return view('tareas.index', compact('tareas'));
    }

    /**
     * crear tarea
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();
        return view('tareas.create', compact('clientes', 'operarios'));
    }

    /**
     * guardar tarea
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
            'anotaciones_anteriores' => 'nullable|string',
        ]);

        // Validación: CP y Provincia deben coincidir
        if (substr($validated['codigo_postal'], 0, 2) !== $validated['provincia_ine']) {
            return back()
                ->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia seleccionada.'])
                ->withInput();
        }

        $validated['fecha_creacion'] = now();
        $validated['estado'] = 'P'; // Pendiente por defecto

        Tarea::create($validated);

        return redirect()->route('tareas.index')
            ->with('success', 'Incidencia creada correctamente.');
    }

    /**
     * buscar tarea
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\View\View
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
     * editar tarea
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\View\View
     */
    public function edit(Tarea $tarea)
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $operarios = Empleado::where('tipo', 'operario')->orderBy('nombre')->get();
        return view('tareas.edit', compact('tarea', 'clientes', 'operarios'));
    }

    /**
     * actualizar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\RedirectResponse
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
            return back()
                ->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia.'])
                ->withInput();
        }

        $tarea->update($validated);

        return redirect()->route('tareas.index')
            ->with('success', 'Incidencia actualizada.');
    }

    /**
     * eliminar
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect()->route('tareas.index')
            ->with('success', 'Incidencia eliminada.');
    }

    /**
     * Muestra el formulario para completar la tarea (Operario).
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\View\View
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function procesarCompletado(Request $request, Tarea $tarea)
    {
        $validated = $request->validate([
            'anotaciones_posteriores' => 'required|string',
            'fichero_resumen' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        ]);

        if ($request->hasFile('fichero_resumen')) {
            $path = $request->file('fichero_resumen')
                ->store('tareas_resumenes', 'private');
            $validated['fichero_resumen'] = $path;
        }

        $validated['estado'] = 'R'; // Realizada
        $validated['fecha_realizacion'] = now();

        $tarea->update($validated);

        return redirect()->route('tareas.index')
            ->with('success', 'Tarea completada.');
    }


    // PUNTO 1.10 - REGISTRO PÚBLICO DE INCIDENCIAS POR CLIENTES


    /**
     * Muestra el formulario público para clientes (Punto 1.10 del PDF).
     * Sin necesidad de login, pero validando CIF y Teléfono.
     *
     * @return \Illuminate\View\View
     */
    public function crearPorCliente()
    {
        return view('tareas.publica.create');
    }

    /**
     * Guarda una incidencia pública validando CIF y Teléfono (Punto 1.10).
     * El cliente debe existir en BD con ese CIF y teléfono.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guardarPorCliente(Request $request)
    {
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

        // Verificar si el cliente existe con ese CIF y Teléfono
        $cliente = Cliente::where('cif', $validated['cif'])
            ->where('telefono', $validated['telefono'])
            ->first();

        if (!$cliente) {
            return back()
                ->withErrors(['cif' => 'El CIF y el Teléfono no coinciden con ningún cliente registrado.'])
                ->withInput();
        }

        // Validar CP vs Provincia
        if (substr($validated['codigo_postal'], 0, 2) !== $validated['provincia_ine']) {
            return back()
                ->withErrors(['codigo_postal' => 'El código postal no coincide con la provincia.'])
                ->withInput();
        }

        // Crear la tarea SIN operario asignado (null)
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
            'estado' => 'P',
            'fecha_creacion' => now(),
            'anotaciones_anteriores' => null,
        ]);

        return redirect()->route('home')
            ->with('success', 'Incidencia registrada correctamente. Un operario revisará su solicitud.');
    }

    // PROBLEMA 3.1 - MÉTODOS AJAX (DataTables / jQuery)


    /**
     * API endpoint para DataTables (Server-side processing) - Problema 3.1
     * Devuelve JSON con botones HTML en el campo acciones.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDatosTareas(Request $request)
    {
        try {
            Log::info('Entrando a getDatosTareas');
            
            $user = Auth::user();
            Log::info('Usuario: ' . $user->id . ' - Tipo: ' . $user->tipo);
            
            $query = Tarea::with(['cliente', 'empleado']);

            // Filtrar por rol: Operarios solo ven sus tareas
            if ($user->tipo !== 'admin') {
                $query->where('empleado_id', $user->id);
            }

            // Búsqueda global (Input de búsqueda de DataTables)
            if ($request->filled('search.value')) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('descripcion', 'like', "%{$search}%")
                      ->orWhere('persona_contacto', 'like', "%{$search}%")
                      ->orWhere('poblacion', 'like', "%{$search}%");
                });
            }

            $totalRecords = $query->count();

            $start = (int) ($request->start ?? 0);
            $length = (int) ($request->length ?? 10);
            
            // Si DataTables envía -1 (mostrar todos), usamos un límite seguro
            if ($length === -1) {
                $length = 10000;
            }

            $data = $query->limit($length)->offset($start)->get();

            $rows = [];
            foreach ($data as $tarea) {
                $rows[] = [
                    'id' => $tarea->id,
                    'cliente' => $tarea->cliente->nombre ?? '-',
                    'persona' => $tarea->persona_contacto,
                    'descripcion' => Str::limit($tarea->descripcion, 50),
                    'estado' => $tarea->estado,
                    'fecha' => $tarea->fecha_creacion->format('d/m/Y'),
                    'acciones' => '
                        <button class="btn btn-sm btn-warning btn-edit" data-id="'.$tarea->id.'">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="'.$tarea->id.'">
                            <i class="fas fa-trash"></i>
                        </button>
                    '
                ];
            }

            Log::info('Registros encontrados: ' . count($rows));

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $rows
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en getDatosTareas: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al cargar datos',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear tarea vía AJAX (Problema 3.1 y 3.2).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAjax(Request $request)
    {
        try {
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
                'estado' => 'sometimes|in:P,R,C,A'
            ]);

            $validated['fecha_creacion'] = now();
            $validated['estado'] = $validated['estado'] ?? 'P';
            
            $tarea = Tarea::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tarea creada correctamente',
                'data' => $tarea
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en storeAjax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar tarea vía AJAX (Problema 3.1 y 3.2).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAjax(Request $request, $id)
    {
        try {
            $tarea = Tarea::findOrFail($id);
            
            $validated = $request->validate([
                'cliente_id' => 'sometimes|required|exists:clientes,id',
                'empleado_id' => 'sometimes|required|exists:empleados,id',
                'persona_contacto' => 'sometimes|required|string|max:150',
                'telefono_contacto' => 'sometimes|required|string|max:20',
                'descripcion' => 'sometimes|required|string',
                'correo_contacto' => 'sometimes|required|email|max:100',
                'direccion' => 'sometimes|required|string',
                'poblacion' => 'sometimes|required|string|max:100',
                'codigo_postal' => 'sometimes|required|string|size:5',
                'provincia_ine' => 'sometimes|required|string|size:2',
                'estado' => 'sometimes|required|in:P,R,C,A'
            ]);

            $tarea->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Tarea actualizada correctamente',
                'data' => $tarea
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en updateAjax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar tarea vía AJAX (Problema 3.1 y 3.2).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAjax($id)
    {
        try {
            $tarea = Tarea::findOrFail($id);
            $tarea->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Tarea eliminada correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en destroyAjax: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la tarea'
            ], 500);
        }
    }

    // PROBLEMA 3.3 - API PARA VUE 3 + VITE (Componentes .vue)

    /**
     * API endpoint para Vue 3 (componente TareaCrud.vue) - Problema 3.3
     * Devuelve JSON limpio sin HTML en las acciones.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTareasApi()
    {
        try {
            $user = Auth::user();
            $query = Tarea::with(['cliente', 'empleado']);

            // Filtrar por rol
            if ($user->tipo !== 'admin') {
                $query->where('empleado_id', $user->id);
            }

            $tareas = $query->latest('fecha_creacion')->get();

            return response()->json($tareas->map(function($tarea) {
                return [
                    'id' => $tarea->id,
                    'cliente_id' => $tarea->cliente_id,
                    'cliente_nombre' => $tarea->cliente->nombre ?? 'Sin cliente',
                    'empleado_id' => $tarea->empleado_id,
                    'persona_contacto' => $tarea->persona_contacto,
                    'telefono_contacto' => $tarea->telefono_contacto,
                    'descripcion' => $tarea->descripcion,
                    'correo_contacto' => $tarea->correo_contacto,
                    'direccion' => $tarea->direccion,
                    'poblacion' => $tarea->poblacion,
                    'codigo_postal' => $tarea->codigo_postal,
                    'provincia_ine' => $tarea->provincia_ine,
                    'estado' => $tarea->estado,
                    'fecha_creacion' => $tarea->fecha_creacion->format('d/m/Y'),
                ];
            }));
            
        } catch (\Exception $e) {
            Log::error('Error en getTareasApi: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar tareas'], 500);
        }
    }

    /**
     * API para obtener clientes (Vue 3 - Problema 3.3)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClientesApi()
    {
        $clientes = Cliente::orderBy('nombre')->get(['id', 'nombre']);
        return response()->json($clientes);
    }

    /**
     * API para obtener operarios (Vue 3 - Problema 3.3)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOperariosApi()
    {
        $operarios = Empleado::where('tipo', 'operario')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
        return response()->json($operarios);
    }
}