@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 rounded-3 mt-5">
            <div class="card-header bg-primary text-white text-center py-4">
                <h4 class="mb-0"><i class="fas fa-lock me-2"></i>Acceso a Plataforma</h4>
            </div>
            <div class="card-body p-4">
                
                <!-- Errores de Validación -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="correo" class="form-label fw-bold">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                   id="correo" name="correo" value="{{ old('correo') }}" required autofocus>
                        </div>
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Entrar
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <small class="text-muted">
                        Credenciales por defecto: <br>
                        Admin: admin@nosecaen.es | Operario: juan@nosecaen.es <br>
                        Contraseña: password
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection