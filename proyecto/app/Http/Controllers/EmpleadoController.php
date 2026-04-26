<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Autentica al usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirigir según el tipo de usuario
            if (Auth::user()->tipo === 'admin') {
                return redirect()->intended('/admin/empleados');
            }
            
            return redirect()->intended('/tareas');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales introducidas no son correctas.',
        ])->onlyInput('correo');
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * listado
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $empleados = Empleado::orderBy('nombre')->paginate(10);
        return view('admin.empleados.index', compact('empleados'));
    }

    /**
     * crear empleado
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.empleados.create');
    }

    /**
     * alta empleado
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:empleados,dni',
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:100|unique:empleados,correo',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_alta' => 'required|date',
            'tipo' => 'required|in:admin,operario',
            'password' => 'required|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        Empleado::create($validated);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado creado correctamente.');
    }

    /**
     * info empleado
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('admin.empleados.show', compact('empleado'));
    }

    /**
     * editar empleado
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view('admin.empleados.edit', compact('empleado'));
    }

    /**
     * actualizar
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $validated = $request->validate([
            'dni' => 'required|string|max:20|unique:empleados,dni,' . $id,
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|max:100|unique:empleados,correo,' . $id,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'fecha_alta' => 'required|date',
            'tipo' => 'required|in:admin,operario',
        ]);

        // Si se proporciona nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $empleado->update($validated);

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     *eliminar empleado
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        
        // No permitir borrar el propio empleado
        if ($empleado->id === Auth::id()) {
            return redirect()->route('admin.empleados.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $empleado->delete();

        return redirect()->route('admin.empleados.index')
            ->with('success', 'Empleado eliminado correctamente.');
    }
}