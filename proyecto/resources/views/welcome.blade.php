@extends('layouts.app')

@section('content')
<div class="text-center py-5">
    <div class="mb-4">
        <i class="fas fa-tools fa-5x text-primary mb-3"></i>
        <h1 class="display-4 fw-bold text-dark">Gestión de Incidencias</h1>
        <p class="lead text-muted">Plataforma digital para Nosecaen S.L.</p>
    </div>

    <div class="row justify-content-center g-4 mt-2">
        <!-- Sección Pública -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-clipboard-list fa-2x text-info"></i>
                    </div>
                    <h5 class="card-title">Soy Cliente</h5>
                    <p class="card-text text-muted small">Registrar una nueva incidencia o avería sin necesidad de usuario.</p>
                    <a href="{{ route('incidencia.publica') }}" class="btn btn-outline-info w-100">
                        Registrar Incidencia
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección Privada -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <div class="bg-light rounded-circle d-inline-flex p-3 mb-3">
                        <i class="fas fa-user-shield fa-2x text-primary"></i>
                    </div>
                    <h5 class="card-title">Soy Empleado</h5>
                    <p class="card-text text-muted small">Acceder al panel de tareas o administración del sistema.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection