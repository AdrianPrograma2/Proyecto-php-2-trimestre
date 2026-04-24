@extends('layouts.app')

@section('title', 'Gestión de Tareas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-list me-2"></i>
            {{ auth()->user()->tipo === 'admin' ? 'Todas las Incidencias' : 'Mis Tareas Asignadas' }}
        </h2>
        @if(auth()->user()->tipo === 'admin')
            <a href="{{ route('tareas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Incidencia
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Descripción</th>
                            <th>Operario</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tareas as $tarea)
                            <tr>
                                <td>#{{ $tarea->id }}</td>
                                <td>{{ $tarea->cliente->nombre }}</td>
                                <td>{{ Str::limit($tarea->descripcion, 50) }}</td>
                                <td>{{ $tarea->empleado->nombre ?? '<span class="text-danger">Sin asignar</span>' }}</td>
                                <td>
                                    @switch($tarea->estado)
                                        @case('P') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                        @case('R') <span class="badge bg-success">Realizada</span> @break
                                        @case('C') <span class="badge bg-secondary">Cancelada</span> @break
                                        @default <span class="badge bg-info">Otro</span>
                                    @endswitch
                                </td>
                                <td>{{ $tarea->fecha_creacion->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('tareas.show', $tarea->id) }}" class="btn btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(auth()->user()->tipo === 'admin')
                                            <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar esta incidencia?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(auth()->user()->tipo === 'operario' && $tarea->estado === 'P')
                                            <a href="{{ route('tareas.completar', $tarea->id) }}" class="btn btn-outline-success" title="Completar">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4">No hay tareas registradas</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $tareas->links() }}</div>
</div>
@endsection