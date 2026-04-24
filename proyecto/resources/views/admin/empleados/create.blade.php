@extends('layouts.app')

@section('title', 'Crear Empleado')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-user-plus me-2"></i>Crear Nuevo Empleado</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.empleados.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="dni" class="form-label fw-bold">DNI <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('dni') is-invalid @enderror" 
                               id="dni" name="dni" value="{{ old('dni') }}" required>
                        @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                               id="correo" name="correo" value="{{ old('correo') }}" required>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" name="telefono" value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label fw-bold">Dirección</label>
                    <textarea class="form-control @error('direccion') is-invalid @enderror" 
                              id="direccion" name="direccion" rows="2">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_alta" class="form-label fw-bold">Fecha de Alta <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('fecha_alta') is-invalid @enderror" 
                               id="fecha_alta" name="fecha_alta" value="{{ old('fecha_alta', date('Y-m-d')) }}" required>
                        @error('fecha_alta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select @error('tipo') is-invalid @enderror" 
                                id="tipo" name="tipo" required>
                            <option value="operario" {{ old('tipo') === 'operario' ? 'selected' : '' }}>Operario</option>
                            <option value="admin" {{ old('tipo') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">
                <h5><i class="fas fa-lock me-2"></i>Credenciales de Acceso</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-bold">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Mínimo 8 caracteres</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">Confirmar Contraseña <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.empleados.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Empleado
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection