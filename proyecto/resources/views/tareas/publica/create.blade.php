@extends('layouts.app')

@section('title', 'Registrar Incidencia')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 mt-5">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0"><i class="fas fa-tools me-2"></i>Registro de Incidencia</h3>
                    <p class="mb-0 small">Acceso exclusivo para clientes registrados</p>
                </div>
                <div class="card-body p-4">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Para registrar una incidencia, debe verificar su identidad introduciendo el 
                        <strong>CIF</strong> y el <strong>Teléfono</strong> de su empresa.
                    </div>

                    <form action="{{ route('incidencia.publica.store') }}" method="POST">
                        @csrf

                        <h5 class="text-primary mb-3 border-bottom pb-2">1. Verificación de Cliente</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">CIF de la Empresa *</label>
                                <input type="text" name="cif" class="form-control @error('cif') is-invalid @enderror" 
                                       value="{{ old('cif') }}" placeholder="Ej: A12345678" required>
                                @error('cif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Introduzca el CIF registrado en nuestra base de datos</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Teléfono de la Empresa *</label>
                                <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" 
                                       value="{{ old('telefono') }}" placeholder="Ej: 959123456" required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Teléfono principal de su empresa</div>
                            </div>
                        </div>

                        <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">2. Datos de la Incidencia</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Persona de Contacto *</label>
                            <input type="text" name="persona_contacto" class="form-control @error('persona_contacto') is-invalid @enderror" 
                                   value="{{ old('persona_contacto') }}" required>
                            @error('persona_contacto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Teléfono de Contacto *</label>
                                <input type="text" name="telefono_contacto" class="form-control @error('telefono_contacto') is-invalid @enderror" 
                                       value="{{ old('telefono_contacto') }}" required>
                                @error('telefono_contacto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Correo Electrónico *</label>
                                <input type="email" name="correo_contacto" class="form-control @error('correo_contacto') is-invalid @enderror" 
                                       value="{{ old('correo_contacto') }}" required>
                                @error('correo_contacto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Dirección del Trabajo *</label>
                            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" 
                                   value="{{ old('direccion') }}" placeholder="Calle, número, piso..." required>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Población *</label>
                                <input type="text" name="poblacion" class="form-control @error('poblacion') is-invalid @enderror" 
                                       value="{{ old('poblacion') }}" required>
                                @error('poblacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Código Postal *</label>
                                <input type="text" name="codigo_postal" class="form-control @error('codigo_postal') is-invalid @enderror" 
                                       value="{{ old('codigo_postal') }}" maxlength="5" placeholder="21001" required>
                                @error('codigo_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Provincia (INE) *</label>
                                <select name="provincia_ine" class="form-select @error('provincia_ine') is-invalid @enderror" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="21" {{ old('provincia_ine') == '21' ? 'selected' : '' }}>Huelva (21)</option>
                                    <option value="41" {{ old('provincia_ine') == '41' ? 'selected' : '' }}>Sevilla (41)</option>
                                    <option value="29" {{ old('provincia_ine') == '29' ? 'selected' : '' }}>Málaga (29)</option>
                                    <option value="11" {{ old('provincia_ine') == '11' ? 'selected' : '' }}>Cádiz (11)</option>
                                    <option value="14" {{ old('provincia_ine') == '14' ? 'selected' : '' }}>Córdoba (14)</option>
                                </select>
                                @error('provincia_ine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Los 2 primeros dígitos deben coincidir con el CP</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción de la Avería/Incidencia *</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="4" placeholder="Describa detalladamente el problema..." required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Registrar Incidencia
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i> Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection