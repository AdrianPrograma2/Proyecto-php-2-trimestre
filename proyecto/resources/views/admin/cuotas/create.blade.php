@extends('layouts.app')

@section('title', 'Nueva Cuota')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-plus-circle me-2"></i>Nueva Cuota Excepcional</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.cuotas.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="cliente_id" class="form-label fw-bold">Cliente *</label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="concepto" class="form-label fw-bold">Concepto *</label>
                    <input type="text" class="form-control @error('concepto') is-invalid @enderror" 
                           id="concepto" name="concepto" value="{{ old('concepto') }}" required>
                    @error('concepto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_emision" class="form-label fw-bold">Fecha de Emisión *</label>
                        <input type="date" class="form-control @error('fecha_emision') is-invalid @enderror" 
                               id="fecha_emision" name="fecha_emision" value="{{ old('fecha_emision', date('Y-m-d')) }}" required>
                        @error('fecha_emision')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="importe" class="form-label fw-bold">Importe (€) *</label>
                        <input type="number" step="0.01" class="form-control @error('importe') is-invalid @enderror" 
                               id="importe" name="importe" value="{{ old('importe') }}" required>
                        @error('importe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="pagada" class="form-label fw-bold">Estado de Pago *</label>
                    <select name="pagada" class="form-select @error('pagada') is-invalid @enderror" required>
                        <option value="N" {{ old('pagada') === 'N' ? 'selected' : '' }}>Pendiente</option>
                        <option value="S" {{ old('pagada') === 'S' ? 'selected' : '' }}>Pagada</option>
                    </select>
                    @error('pagada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="fecha_pago" class="form-label fw-bold">Fecha de Pago</label>
                    <input type="date" class="form-control @error('fecha_pago') is-invalid @enderror" 
                           id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago') }}">
                    @error('fecha_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="notas" class="form-label fw-bold">Notas</label>
                    <textarea class="form-control @error('notas') is-invalid @enderror" 
                              id="notas" name="notas" rows="3">{{ old('notas') }}</textarea>
                    @error('notas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.cuotas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Cuota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection