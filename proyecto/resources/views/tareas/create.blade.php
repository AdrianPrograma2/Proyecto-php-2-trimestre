@extends('layouts.app')

@section('title', 'Nueva Incidencia')

@section('content')
<div class="container">
    <h2><i class="fas fa-plus-circle me-2"></i>Registrar Nueva Incidencia</h2>
    
    <form action="{{ route('tareas.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        
        <div class="row g-3">
            <!-- Selección de Cliente -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Cliente *</label>
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

            <!-- Asignación de Operario (Solo Admin) -->
            <div class="col-md-6">
                <label class="form-label fw-bold">Operario Asignado</label>
                <select name="empleado_id" class="form-select">
                    <option value="">-- Sin asignar --</option>
                    @foreach($operarios as $operario)
                        <option value="{{ $operario->id }}" {{ old('empleado_id') == $operario->id ? 'selected' : '' }}>
                            {{ $operario->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Persona de Contacto -->
            <div class="col-md-6">
                <label class="form-label">Persona de Contacto *</label>
                <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" value="{{ old('persona_contacto') }}" required>
                @error('persona_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Teléfono -->
            <div class="col-md-6">
                <label class="form-label">Teléfono Contacto *</label>
                <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" value="{{ old('telefono_contacto') }}" required>
                @error('telefono_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Correo -->
            <div class="col-md-6">
                <label class="form-label">Correo Electrónico *</label>
                <input type="email" name="correo_contacto" class="form-control @error('correo_contacto') is-invalid @enderror" value="{{ old('correo_contacto') }}" required>
                @error('correo_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Dirección -->
            <div class="col-md-12">
                <label class="form-label">Dirección Completa *</label>
                <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" required>
                @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Población -->
            <div class="col-md-4">
                <label class="form-label">Población *</label>
                <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" value="{{ old('poblacion') }}" required>
                @error('poblacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- CP -->
            <div class="col-md-4">
                <label class="form-label">Código Postal *</label>
                <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" value="{{ old('codigo_postal') }}" maxlength="5" required>
                @error('codigo_postal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Provincia (INE) -->
            <div class="col-md-4">
                <label class="form-label">Provincia (INE) *</label>
                <select name="provincia_ine" class="form-select @error('provincia_ine') is-invalid @enderror" required>
                    <option value="">Seleccionar...</option>
                    <option value="21" {{ old('provincia_ine') == '21' ? 'selected' : '' }}>Huelva (21)</option>
                    <option value="41" {{ old('provincia_ine') == '41' ? 'selected' : '' }}>Sevilla (41)</option>
                    <option value="29" {{ old('provincia_ine') == '29' ? 'selected' : '' }}>Málaga (29)</option>
                    <!-- Añadir más provincias si es necesario -->
                </select>
                @error('provincia_ine')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Descripción -->
            <div class="col-md-12">
                <label class="form-label">Descripción de la Tarea *</label>
                <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" rows="3" required>{{ old('descripcion') }}</textarea>
                @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <!-- Anotaciones -->
            <div class="col-md-12">
                <label class="form-label">Anotaciones Adicionales</label>
                <textarea name="anotaciones_anteriores" class="form-control" rows="2">{{ old('anotaciones_anteriores') }}</textarea>
            </div>
        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('tareas.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar Incidencia</button>
        </div>
    </form>
</div>
@endsection