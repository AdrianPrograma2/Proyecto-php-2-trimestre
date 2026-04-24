<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de incidencias para Nosecaen S.L.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestión de Incidencias - Nosecaen S.L.')</title>
    
    <!-- Bootstrap 5 CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome (Iconos) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-light">

    <!-- Barra de Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-elevator me-2"></i>Nosecaen S.L.
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('tareas.index') }}">Mis Tareas</a>
                        </li>
                        @if(auth()->user()->tipo === 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    Administración
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.empleados.index') }}">Empleados</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.clientes.index') }}">Clientes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.cuotas.index') }}">Cuotas</a></li>
                                </ul>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link btn btn-light text-primary fw-bold px-3" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i> Acceder
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->nombre }}
                                <span class="badge bg-warning text-dark ms-1">{{ ucfirst(auth()->user()->tipo) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="container">
        <!-- Alertas Globales -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center text-muted mt-5 py-3 border-top">
        <small>&copy; {{ date('Y') }} Nosecaen S.L. - Gestión de Mantenimiento</small>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>