@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building me-2"></i>Gestión de Clientes</h2>
                <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Cliente
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>CIF</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>País</th>
                        <th>Cuota Mensual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->cif }}</td>
                            <td>{{ $cliente->nombre }}</td>
                            <td>{{ $cliente->telefono ?? 'N/A' }}</td>
                            <td>{{ $cliente->correo ?? 'N/A' }}</td>
                            <td>{{ $cliente->pais ?? 'España' }}</td>
                            <td>{{ number_format($cliente->importe_cuota_mensual, 2) }} {{ $cliente->moneda }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.clientes.show', $cliente->id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.clientes.edit', $cliente->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No hay clientes registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $clientes->links() }}
        </div>
    </div>
</div>
@endsection