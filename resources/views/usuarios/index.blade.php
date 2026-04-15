@extends('welcome')
@section('title', 'Usuarios')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-people me-2"></i>Usuarios</h4>

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
    </a>
</div>

{{-- 🔍 FILTROS --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-center">

            {{-- 🔥 IMPORTANTE: mantener estado --}}
            <input type="hidden" name="incluir_inactivos" value="{{ request('incluir_inactivos') }}">

            {{-- BUSCADOR --}}
            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por nombre, usuario o correo..."
                    value="{{ request('search') }}">
            </div>

            {{-- ROL --}}
            <div class="col-md-3">
                <select name="rol" class="form-select">
                    <option value="">-- Todos los roles --</option>
                    <option value="admin" {{ request('rol')=='admin'?'selected':'' }}>Admin</option>
                    <option value="empleado" {{ request('rol')=='empleado'?'selected':'' }}>Empleado</option>
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>

                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                    Limpiar
                </a>
            </div>

        </form>
    </div>
</div>

{{-- 🔥 FILTRO SIMPLE (ACTIVOS / TODOS) --}}
<div class="mb-3 d-flex gap-2">

    {{-- ACTIVOS --}}
    <a href="{{ route('usuarios.index', array_merge(request()->query(), ['incluir_inactivos' => null])) }}"
       class="btn btn-sm {{ !request()->has('incluir_inactivos') ? 'btn-primary' : 'btn-outline-primary' }}">
        Activos
    </a>

    {{-- TODOS --}}
    <a href="{{ route('usuarios.index', array_merge(request()->query(), ['incluir_inactivos' => 'true'])) }}"
       class="btn btn-sm {{ request('incluir_inactivos') == 'true' ? 'btn-dark' : 'btn-outline-dark' }}">
        Todos
    </a>

</div>

{{-- 📋 TABLA --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $usuario)
                <tr>

                    {{-- 👤 USUARIO + FOTO --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">

                            @if($usuario->foto_perfil_url)
                                <img src="{{ asset('storage/'.$usuario->foto_perfil_url) }}"
                                     class="rounded-circle"
                                     width="40" height="40"
                                     style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                     style="width:40px; height:40px;">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif

                            <div>
                                <strong>{{ $usuario->nombre }}</strong><br>
                                <small class="text-muted">{{ $usuario->username }}</small>
                            </div>

                        </div>
                    </td>

                    {{-- EMAIL --}}
                    <td>{{ $usuario->email }}</td>

                    {{-- ROL --}}
                    <td>
                        <span class="badge bg-{{ $usuario->rol == 'admin' ? 'dark' : 'info' }}">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                    </td>

                    {{-- ESTADO --}}
                    <td>
                        @if(!$usuario->activo)
                            <span class="badge bg-secondary">Inactivo</span>
                        @else
                            <span class="badge bg-success">Activo</span>
                        @endif
                    </td>

                    {{-- ACCIONES --}}
                    <td class="text-end">

                        {{-- VER --}}
                        <a href="{{ route('usuarios.show', $usuario) }}"
                           class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </a>

                        {{-- EDITAR --}}
                        <a href="{{ route('usuarios.edit', $usuario) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- DESACTIVAR --}}
                        @if($usuario->activo)
                        <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('¿Desactivar usuario?')">
                                <i class="bi bi-x"></i>
                            </button>
                        </form>
                        @endif

                        {{-- ACTIVAR --}}
                        @if(!$usuario->activo)
                        <form action="{{ route('usuarios.activar', $usuario) }}" method="POST" class="d-inline">
                            @csrf @method('PUT')
                            <button class="btn btn-sm btn-success">
                                <i class="bi bi-check"></i>
                            </button>
                        </form>
                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        No hay usuarios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

{{-- PAGINACIÓN --}}
<div class="mt-3">
    {{ $usuarios->links() }}
</div>

@endsection