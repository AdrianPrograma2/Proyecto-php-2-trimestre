@extends('layouts.app')

@section('title', 'Ver Empleado')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-circle me-2"></i>Detalles del Empleado</h2>
                <div class="btn-group">
                    <a href="{{ route('admin.empleados.edit', $empleado->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Información Personal</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-id-badge me-2"></i>DNI:</strong>
                            <p class="mb-0">{{ $empleado->dni }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-user me-2"></i>Nombre:</strong>
                            <p class="mb-0">{{ $empleado->nombre }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-envelope me-2"></i>Correo:</strong>
                            <p class="mb-0">{{ $empleado->correo }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-phone me-2"></i>Teléfono:</strong>
                            <p class="mb-0">{{ $empleado->telefono ?? 'No disponible' }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fas fa-map-marker-alt me-2"></i>Dirección:</strong>
                        <p class="mb-0">{{ $empleado->direccion ?? 'No disponible' }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calendar-alt me-2"></i>Fecha de Alta:</strong>
                            <p class="mb-0">{{ $empleado->fecha_alta->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-user-tag me-2"></i>Tipo:</strong>
                            <p class="mb-0">
                                @if($empleado->tipo === 'admin')
                                    <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Administrador</span>
                                @else
                                    <span class="badge bg-info"><i class="fas fa-user me-1"></i>Operario</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Este empleado tiene acceso al sistema y puede realizar operaciones según su tipo de usuario.
                    </p>
                    
                    @if($empleado->tareas->count() > 0)
                        <hr>
                        <h6><i class="fas fa-tasks me-2"></i>Tareas Asignadas</h6>
                        <p class="mb-0">{{ $empleado->tareas->count() }} tarea(s)</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection