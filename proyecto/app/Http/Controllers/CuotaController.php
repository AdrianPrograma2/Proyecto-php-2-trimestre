<?php

/**
 * Controlador CuotaController
 * 
 * Gestiona las operaciones CRUD para las cuotas (facturas) de la empresa.
 * Funcionalidades obligatorias según PDF:
 * - 1.5: Gestión CRUD (Añadir excepcional, corregir, borrar, ver lista).
 * - 1.6: Añadir remesa mensual a todos los clientes.
 * - 1.8: Enviar factura por correo (automático y manual).
 * - 1.9: Crear factura en formato PDF.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Controllers
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Cuota;
use App\Models\Cliente;
use App\Mail\FacturaCuotaMail;
use Carbon\Carbon;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuotas = Cuota::with('cliente')
                       ->orderBy('fecha_emision', 'desc')
                       ->paginate(15);
                       
        return view('admin.cuotas.index', compact('cuotas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('admin.cuotas.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     * CORREGIDO: Carga la relación cliente antes de enviar email
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        // Crear la cuota
        $cuota = Cuota::create($validated);

        // 1.8 - Enviar email automáticamente al crear la factura
        // Cargamos explícitamente la relación con cliente
        $cuota->load('cliente');
        
        // Verificamos que el cliente exista y tenga correo registrado
        if ($cuota->cliente && $cuota->cliente->correo) {
            try {
                Mail::to($cuota->cliente->correo)
                    ->send(new FacturaCuotaMail($cuota));
            } catch (\Exception $e) {
                // Si falla el email, registramos el error pero no impedimos la creación
                Log::error('Error al enviar email de factura: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.cuotas.index')
            ->with('success', 'Cuota creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cuota = Cuota::with('cliente')->findOrFail($id);
        return view('admin.cuotas.show', compact('cuota'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cuota = Cuota::findOrFail($id);
        $clientes = Cliente::orderBy('nombre')->get();
        return view('admin.cuotas.edit', compact('cuota', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cuota = Cuota::findOrFail($id);

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'concepto' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
            'importe' => 'required|numeric|min:0',
            'pagada' => 'required|in:S,N',
            'fecha_pago' => 'nullable|date',
            'notas' => 'nullable|string',
        ]);

        $cuota->update($validated);

        return redirect()->route('admin.cuotas.index')
            ->with('success', 'Cuota actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cuota = Cuota::findOrFail($id);
        $cuota->delete();

        return redirect()->route('admin.cuotas.index')
            ->with('success', 'Cuota eliminada correctamente.');
    }

    /**
     * Genera la remesa mensual automáticamente (Punto 1.6 del PDF).
     */
    public function generarRemesa()
    {
        $hoy = Carbon::now();
        $fechaEmision = $hoy->format('Y-m-01');
        $mesActual = $hoy->format('Y-m');

        $clientes = Cliente::all();
        $creadas = 0;

        foreach ($clientes as $cliente) {
            $existe = Cuota::where('cliente_id', $cliente->id)
                           ->where('fecha_emision', 'like', $mesActual . '%')
                           ->exists();

            if (!$existe && $cliente->importe_cuota_mensual > 0) {
                Cuota::create([
                    'cliente_id' => $cliente->id,
                    'concepto' => 'Mantenimiento mensual ' . $hoy->format('F Y'),
                    'fecha_emision' => $fechaEmision,
                    'importe' => $cliente->importe_cuota_mensual,
                    'pagada' => 'N',
                    'notas' => 'Generada automáticamente por sistema (Remesa Mensual)',
                ]);
                $creadas++;
            }
        }

        return redirect()->route('admin.cuotas.index')
            ->with('success', "Remesa mensual generada. Se han creado {$creadas} nuevas cuotas.");
    }

    /**
     * Genera y descarga el PDF de la factura (Punto 1.9 del PDF).
     */
    public function descargarFactura($id)
    {
        $cuota = Cuota::with('cliente')->findOrFail($id);
        $pdf = Pdf::loadView('cuotas.factura', compact('cuota'));
        return $pdf->download('factura_'.$cuota->id.'.pdf');
    }

    /**
     * Reenvía la factura por correo electrónico manualmente.
     */
    public function enviarFacturaEmail($id)
    {
        $cuota = Cuota::with('cliente')->findOrFail($id);

        if (!$cuota->cliente || !$cuota->cliente->correo) {
            return back()->with('error', 'El cliente no tiene correo electrónico registrado.');
        }

        try {
            Mail::to($cuota->cliente->correo)
                ->send(new FacturaCuotaMail($cuota));

            return back()->with('success', 'Factura enviada correctamente a ' . $cuota->cliente->correo);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el email: ' . $e->getMessage());
        }
    }
}