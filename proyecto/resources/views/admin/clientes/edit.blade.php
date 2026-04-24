@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="container">
    <h2><i class="fas fa-building me-2"></i>Editar Cliente</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cif" class="form-label fw-bold">CIF *</label>
                        <input type="text" class="form-control @error('cif') is-invalid @enderror" 
                               id="cif" name="cif" value="{{ old('cif', $cliente->cif) }}" required>
                        @error('cif')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre *</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                               id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}">
                        @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="correo" class="form-label fw-bold">Correo</label>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                               id="correo" name="correo" value="{{ old('correo', $cliente->correo) }}">
                        @error('correo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cuenta_corriente" class="form-label fw-bold">Cuenta Corriente</label>
                    <input type="text" class="form-control @error('cuenta_corriente') is-invalid @enderror" 
                           id="cuenta_corriente" name="cuenta_corriente" value="{{ old('cuenta_corriente', $cliente->cuenta_corriente) }}">
                    @error('cuenta_corriente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="pais" class="form-label fw-bold">País</label>
                        <input type="text" class="form-control @error('pais') is-invalid @enderror" 
                               id="pais" name="pais" value="{{ old('pais', $cliente->pais) }}">
                        @error('pais')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="moneda" class="form-label fw-bold">Moneda *</label>
                        <input type="text" class="form-control @error('moneda') is-invalid @enderror" 
                               id="moneda" name="moneda" value="{{ old('moneda', $cliente->moneda) }}" required>
                        @error('moneda')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="importe_cuota_mensual" class="form-label fw-bold">Cuota Mensual *</label>
                        <input type="number" step="0.01" class="form-control @error('importe_cuota_mensual') is-invalid @enderror" 
                               id="importe_cuota_mensual" name="importe_cuota_mensual" 
                               value="{{ old('importe_cuota_mensual', $cliente->importe_cuota_mensual) }}" required>
                        @error('importe_cuota_mensual')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.clientes.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection