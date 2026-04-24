@extends('layouts.app')

@section('title', 'Completar Tarea')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i>Completar Tarea #{{ $tarea->id }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> {{ $tarea->cliente->nombre }}</p>
            <p><strong>Descripción:</strong> {{ $tarea->descripcion }}</p>
            <hr>
            
            <form action="{{ route('tareas.procesarCompletado', $tarea->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Anotaciones del Operario (Resultado) *</label>
                    <textarea name="anotaciones_posteriores" class="form-control" rows="4" placeholder="Describe el trabajo realizado, piezas cambiadas, etc." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Adjuntar Ficha/Resumen (PDF/Imagen)</label>
                    <input type="file" name="fichero_resumen" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                    <div class="form-text">Opcional. Archivo con el parte de trabajo.</div>
                </div>

                <div class="text-end">
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-success">Marcar como Realizada</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection