@extends('layouts.app')

@section('title', 'Editar Tarea')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="fas fa-edit me-2"></i>Editar Incidencia #{{ $tarea->id }}</h2>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label fw-bold">Cliente *</label>
                        <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}" 
                                    {{ old('cliente_id', $tarea->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="empleado_id" class="form-label fw-bold">Operario Asignado *</label>
                        <select name="empleado_id" class="form-select @error('empleado_id') is-invalid @enderror" required>
                            <option value="">Sin asignar</option>
                            @foreach($operarios as $operario)
                                <option value="{{ $operario->id }}" 
                                    {{ old('empleado_id', $tarea->empleado_id) == $operario->id ? 'selected' : '' }}>
                                    {{ $operario->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('empleado_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="persona_contacto" class="form-label fw-bold">Persona de Contacto *</label>
                        <input type="text" class="form-control @error('persona_contacto') is-invalid @enderror" 
                               id="persona_contacto" name="persona_contacto" 
                               value="{{ old('persona_contacto', $tarea->persona_contacto) }}" required>
                        @error('persona_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telefono_contacto" class="form-label fw-bold">Teléfono Contacto *</label>
                        <input type="text" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                               id="telefono_contacto" name="telefono_contacto" 
                               value="{{ old('telefono_contacto', $tarea->telefono_contacto) }}" required>
                        @error('telefono_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="correo_contacto" class="form-label fw-bold">Correo Electrónico *</label>
                    <input type="email" class="form-control @error('correo_contacto') is-invalid @enderror" 
                           id="correo_contacto" name="correo_contacto" 
                           value="{{ old('correo_contacto', $tarea->correo_contacto) }}" required>
                    @error('correo_contacto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label fw-bold">Dirección *</label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                           id="direccion" name="direccion" 
                           value="{{ old('direccion', $tarea->direccion) }}" required>
                    @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="poblacion" class="form-label fw-bold">Población *</label>
                        <input type="text" class="form-control @error('poblacion') is-invalid @enderror" 
                               id="poblacion" name="poblacion" 
                               value="{{ old('poblacion', $tarea->poblacion) }}" required>
                        @error('poblacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="codigo_postal" class="form-label fw-bold">Código Postal *</label>
                        <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" 
                               id="codigo_postal" name="codigo_postal" maxlength="5"
                               value="{{ old('codigo_postal', $tarea->codigo_postal) }}" required>
                        @error('codigo_postal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="provincia_ine" class="form-label fw-bold">Provincia (INE) *</label>
                        <select name="provincia_ine" class="form-select @error('provincia_ine') is-invalid @enderror" required>
                            <option value="21" {{ old('provincia_ine', $tarea->provincia_ine) == '21' ? 'selected' : '' }}>Huelva (21)</option>
                            <option value="41" {{ old('provincia_ine', $tarea->provincia_ine) == '41' ? 'selected' : '' }}>Sevilla (41)</option>
                            <option value="29" {{ old('provincia_ine', $tarea->provincia_ine) == '29' ? 'selected' : '' }}>Málaga (29)</option>
                        </select>
                        @error('provincia_ine')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-bold">Descripción *</label>
                    <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="3" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label fw-bold">Estado</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror">
                        <option value="P" {{ old('estado', $tarea->estado) == 'P' ? 'selected' : '' }}>Pendiente</option>
                        <option value="R" {{ old('estado', $tarea->estado) == 'R' ? 'selected' : '' }}>Realizada</option>
                        <option value="C" {{ old('estado', $tarea->estado) == 'C' ? 'selected' : '' }}>Completada</option>
                        <option value="A" {{ old('estado', $tarea->estado) == 'A' ? 'selected' : '' }}>Anulada</option>
                    </select>
                    @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="anotaciones_anteriores" class="form-label fw-bold">Anotaciones Anteriores</label>
                    <textarea name="anotaciones_anteriores" class="form-control" 
                              rows="2">{{ old('anotaciones_anteriores', $tarea->anotaciones_anteriores) }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Actualizar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection