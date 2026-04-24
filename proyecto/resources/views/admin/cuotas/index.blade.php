@extends('layouts.app')

@section('title', 'Gestión de Cuotas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-euro-sign me-2"></i>Gestión de Cuotas</h2>
        <div>
            <!-- Botón especial para Generar Remesa (Punto 1.6) -->
            <form action="{{ route('admin.cuotas.remessa') }}" method="POST" class="d-inline me-2">
                @csrf
                <button type="submit" class="btn btn-success" onclick="return confirm('¿Generar la cuota mensual para TODOS los clientes?');">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Generar Remesa Mensual
                </button>
            </form>
            <a href="{{ route('admin.cuotas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Cuota Excepcional
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Cliente</th>
                        <th>Concepto</th>
                        <th>Fecha Emisión</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cuotas as $cuota)
                        <tr>
                            <td>{{ $cuota->cliente->nombre }}</td>
                            <td>{{ $cuota->concepto }}</td>
                            <td>{{ $cuota->fecha_emision->format('d/m/Y') }}</td>
                            <td>{{ number_format($cuota->importe, 2) }} €</td>
                            <td>
                                @if($cuota->pagada === 'S')
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Pagada</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-clock"></i> Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <!-- Ver -->
                                    <a href="{{ route('admin.cuotas.show', $cuota->id) }}" class="btn btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar -->
                                    <a href="{{ route('admin.cuotas.edit', $cuota->id) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <!-- PDF Factura (Punto 1.9) -->
                                    <a href="{{ route('admin.cuotas.factura', $cuota->id) }}" class="btn btn-danger" title="Descargar Factura PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                    <!-- Enviar Email (Punto 1.8) -->
                                    <form action="{{ route('admin.cuotas.enviar-email', $cuota->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Enviar factura por email"
                                                onclick="return confirm('¿Enviar factura al correo del cliente?');">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Eliminar -->
                                    <form action="{{ route('admin.cuotas.destroy', $cuota->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar esta cuota?');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">No hay cuotas registradas</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $cuotas->links() }}
        </div>
    </div>
</div>
@endsection