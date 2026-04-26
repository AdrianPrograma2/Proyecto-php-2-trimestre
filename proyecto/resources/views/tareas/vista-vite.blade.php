@extends('layouts.app')

@section('title', 'Problema 3.3 - Vue con Vite')

@push('styles')
<style>
    /* Estilos adicionales si son necesarios */
</style>
@endpush

@section('content')
<div class="container mt-4">
    <!-- Meta CSRF para Axios -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="fas fa-bolt text-warning"></i> Problema 3.3 - Vue.js + Vite</h2>
            <p class="text-muted">Gestión de tareas con componentes Vue compilados con Vite</p>
        </div>
    </div>

    <!-- Contenedor del componente Vue -->
    <div id="app">
        <tarea-crud></tarea-crud>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/app.js'])
@endpush