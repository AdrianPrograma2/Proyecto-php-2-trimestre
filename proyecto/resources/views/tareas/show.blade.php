@extends('layouts.app')

@section('title', 'Ver Tarea')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-clipboard-list me-2"></i>Detalles de la Incidencia #{{ $tarea->id }}</h2>
                <div class="btn-group">
                    @if(auth()->user()->tipo === 'admin')
                        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a>
                    @endif
                    
                    @if(auth()->user()->tipo === 'operario' && $tarea->estado === 'P')
                        <a href="{{ route('tareas.completar', $tarea->id) }}" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Completar
                        </a>
                    @endif
                    
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Información de la Incidencia</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cliente:</strong>
                            <p class="mb-0">{{ $tarea->cliente->nombre }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Operario Asignado:</strong>
                            <p class="mb-0">{{ $tarea->empleado->nombre ?? '<span class="text-muted">Sin asignar</span>' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Persona de Contacto:</strong>
                            <p class="mb-0">{{ $tarea->persona_contacto }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Teléfono Contacto:</strong>
                            <p class="mb-0">{{ $tarea->telefono_contacto }}</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Correo Electrónico:</strong>
                        <p class="mb-0">{{ $tarea->correo_contacto }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Dirección:</strong>
                        <p class="mb-0">{{ $tarea->direccion }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Población:</strong>
                            <p class="mb-0">{{ $tarea->poblacion }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Código Postal:</strong>
                            <p class="mb-0">{{ $tarea->codigo_postal }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Provincia:</strong>
                            <p class="mb-0">{{ $tarea->provincia_ine }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong>Descripción:</strong>
                        <p class="mb-0">{{ $tarea->descripcion }}</p>
                    </div>

                    @if($tarea->anotaciones_anteriores)
                        <div class="mb-3">
                            <strong>Anotaciones Anteriores:</strong>
                            <p class="mb-0">{{ $tarea->anotaciones_anteriores }}</p>
                        </div>
                    @endif

                    @if($tarea->anotaciones_posteriores)
                        <div class="alert alert-success">
                            <strong>Anotaciones del Operario:</strong>
                            <p class="mb-0">{{ $tarea->anotaciones_posteriores }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Estado</h5>
                </div>
                <div class="card-body text-center">
                    @switch($tarea->estado)
                        @case('P')
                            <span class="badge bg-warning fs-6">Pendiente</span>
                            @break
                        @case('R')
                            <span class="badge bg-success fs-6">Realizada</span>
                            @break
                        @case('C')
                            <span class="badge bg-secondary fs-6">Completada</span>
                            @break
                        @default
                            <span class="badge bg-danger fs-6">Anulada</span>
                    @endswitch
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Fechas</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Creación:</strong><br>{{ $tarea->fecha_creacion->format('d/m/Y H:i') }}</p>
                    @if($tarea->fecha_realizacion)
                        <p class="mb-0"><strong>Realización:</strong><br>{{ $tarea->fecha_realizacion->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection