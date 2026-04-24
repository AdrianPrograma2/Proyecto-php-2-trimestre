<?php

/**
 * Controlador ClienteController
 * 
 * Gestiona las operaciones CRUD para clientes.
 * Según el PDF: Los administradores podrán gestionar clientes: añadir, dar de baja, etc.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Controllers
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->paginate(10);
        return view('admin.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes,cif',
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'nullable|string|max:100',
            'moneda' => 'required|string|max:3',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ]);

        Cliente::create($validated);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Cliente::with(['cuotas', 'tareas'])->findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'cif' => 'required|string|max:20|unique:clientes,cif,' . $id,
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:100',
            'cuenta_corriente' => 'nullable|string|max:34',
            'pais' => 'nullable|string|max:100',
            'moneda' => 'required|string|max:3',
            'importe_cuota_mensual' => 'required|numeric|min:0',
        ]);

        $cliente->update($validated);

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}