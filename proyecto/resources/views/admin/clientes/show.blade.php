@extends('layouts.app')

@section('title', 'Ver Cliente')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building me-2"></i>Detalles del Cliente</h2>
        <div class="btn-group">
            <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>CIF:</strong>
                            <p>{{ $cliente->cif }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Nombre:</strong>
                            <p>{{ $cliente->nombre }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Teléfono:</strong>
                            <p>{{ $cliente->telefono ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Correo:</strong>
                            <p>{{ $cliente->correo ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cuenta Corriente:</strong>
                            <p>{{ $cliente->cuenta_corriente ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>País:</strong>
                            <p>{{ $cliente->pais ?? 'España' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Moneda:</strong>
                            <p>{{ $cliente->moneda }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Cuota Mensual:</strong>
                            <p>{{ number_format($cliente->importe_cuota_mensual, 2) }} {{ $cliente->moneda }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Resumen</h5>
                </div>
                <div class="card-body">
                    <p><strong>Cuotas:</strong> {{ $cliente->cuotas->count() }}</p>
                    <p><strong>Tareas:</strong> {{ $cliente->tareas->count() }}</p>
                    <p><strong>Pendientes:</strong> 
                        {{ $cliente->cuotas->where('pagada', 'N')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection