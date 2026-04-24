@extends('layouts.app')

@section('title', 'Gestión de Empleados')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users me-2"></i>Gestión de Empleados</h2>
                <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Empleado
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Tipo</th>
                            <th>Fecha Alta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $empleado)
                            <tr>
                                <td>{{ $empleado->dni }}</td>
                                <td>{{ $empleado->nombre }}</td>
                                <td>{{ $empleado->correo }}</td>
                                <td>{{ $empleado->telefono ?? 'N/A' }}</td>
                                <td>
                                    @if($empleado->tipo === 'admin')
                                        <span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Administrador</span>
                                    @else
                                        <span class="badge bg-info"><i class="fas fa-user me-1"></i>Operario</span>
                                    @endif
                                </td>
                                <td>{{ $empleado->fecha_alta->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.empleados.show', $empleado->id) }}" class="btn btn-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.empleados.edit', $empleado->id) }}" class="btn btn-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.empleados.destroy', $empleado->id) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">
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
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No hay empleados registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $empleados->links() }}
            </div>
        </div>
    </div>
</div>
@endsection