<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Alquileres')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background-color: #2c3e50; }
        .sidebar a { color: #bdc3c7; text-decoration: none; }
        .sidebar a:hover, .sidebar a.active { color: #fff; background-color: #34495e; border-radius: 6px; }
        .sidebar .nav-link { padding: 10px 16px; display: block; }
        .sidebar .section-title { color: #7f8c8d; font-size: 0.75rem; text-transform: uppercase;
                                   letter-spacing: 1px; padding: 16px 16px 4px; }
        .main-content { padding: 24px; }
        .card { border: none; box-shadow: 0 1px 4px rgba(0,0,0,.08); border-radius: 10px; }
        .badge-disponible   { background-color: #27ae60; }
        .badge-ocupada      { background-color: #e74c3c; }
        .badge-mantenimiento{ background-color: #f39c12; }
        .badge-activo       { background-color: #2980b9; }
        .badge-vencido      { background-color: #7f8c8d; }
        .badge-cancelado    { background-color: #e74c3c; }
        .badge-pendiente    { background-color: #f39c12; }
        .badge-pagado       { background-color: #27ae60; }
        /* ❌ Ocultar "Showing..." */
      /* 🔥 ocultar texto "Showing..." */
        /* 🔥 ocultar texto "Showing..." */
/* 🔥 ocultar texto "Showing..." de Laravel */

    </style>
    @stack('styles')
</head>


<form id="logout-auto" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

<body>
<div class="d-flex">
    {{-- Sidebar --}}
    <div class="sidebar p-3" style="width:240px; flex-shrink:0;">
        <div class="text-white fw-bold fs-5 mb-4 px-2">
            <i class="bi bi-building me-2"></i>Sistema de Alquileres
        </div>
        <span class="section-title">Módulos</span>
        <a href="{{ route('propiedad.index') }}"
           class="nav-link {{ request()->routeIs('propiedad*') ? 'active' : '' }}">
            <i class="bi bi-house-door me-2"></i>Propiedades
        </a>
        <a href="{{ route('unidad.index') }}"
           class="nav-link {{ request()->routeIs('unidad*') ? 'active' : '' }}">
            <i class="bi bi-door-open me-2"></i>Unidades
        </a>
        <a href="{{ route('inquilino.index') }}"
           class="nav-link {{ request()->routeIs('inquilino*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>Inquilinos
        </a>
        <a href="{{ route('contrato.index') }}"
           class="nav-link {{ request()->routeIs('contrato*') ? 'active' : '' }}">
            <i class="bi bi-file-text me-2"></i>Contratos
        </a>
        <a href="{{ route('gasto.index') }}"
           class="nav-link {{ request()->routeIs('gasto*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack me-2"></i>Gastos
        </a>
        <a href="{{ route('pagos.index') }}"
        class="nav-link {{ request()->routeIs('pagos*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin me-2"></i>Pagos
        </a>
        <a href="{{ route('solicitudes.index') }}"
        class="nav-link {{ request()->routeIs('solicitudes*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin me-2"></i>Solicitudes
        </a>
        <a href="{{ route('tareas.index') }}"
            class="nav-link {{ request()->routeIs('tareas*') ? 'active' : '' }}">
                <i class="bi bi-cash-coin me-2"></i>Tareas
        </a>
        @auth
            @if(auth()->user()->esAdmin())
                <a href="{{ route('usuarios.index') }}"
                class="nav-link {{ request()->routeIs('usuarios*') ? 'active' : '' }}">
                    <i class="bi bi-person me-2"></i>Usuarios
                </a>
            @endif
        @endauth
        @auth
        @if(auth()->user()->esAdmin())
            <a href="{{ route('auditoria.index') }}" 
                class="nav-link {{ request()->routeIs('auditoria*') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-2"></i>Auditoría
            </a>
        @endif
    @endauth
    
        @auth
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="btn btn-danger w-100 mt-3">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </button>
    </form>
@endauth

    </div>


    {{-- Contenido principal --}}
    <div class="main-content flex-grow-1">
        {{-- Alertas --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
    
</div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
